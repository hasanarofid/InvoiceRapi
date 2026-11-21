<script setup lang="ts">
import { ref } from 'vue';
import { Head, router, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { swalConfirm, swalError } from '@/utils/swal';
import {
  MessageSquareQuote,
  Plus,
  Pencil,
  Trash2,
  X,
  Upload,
  Eye,
  EyeOff,
  Quote,
} from 'lucide-vue-next';

interface Testimonial {
  id: number;
  name: string;
  role: string;
  quote: string;
  metric: string | null;
  avatar_url: string | null;
  sort_order: number;
  is_active: boolean;
}

defineProps<{
  testimonials: Testimonial[];
}>();

const isModalOpen = ref(false);
const editingId = ref<number | null>(null);
const avatarPreview = ref<string | null>(null);

const form = useForm({
  name: '',
  role: '',
  quote: '',
  metric: '',
  sort_order: 0,
  is_active: true,
  avatar: null as File | null,
  remove_avatar: false,
});

const openCreate = () => {
  editingId.value = null;
  form.reset();
  form.clearErrors();
  form.is_active = true;
  form.sort_order = 0;
  form.remove_avatar = false;
  avatarPreview.value = null;
  isModalOpen.value = true;
};

const openEdit = (item: Testimonial) => {
  editingId.value = item.id;
  form.clearErrors();
  form.name = item.name;
  form.role = item.role;
  form.quote = item.quote;
  form.metric = item.metric ?? '';
  form.sort_order = item.sort_order;
  form.is_active = item.is_active;
  form.avatar = null;
  form.remove_avatar = false;
  avatarPreview.value = item.avatar_url;
  isModalOpen.value = true;
};

const closeModal = () => {
  isModalOpen.value = false;
  avatarPreview.value = null;
};

const handleAvatarChange = (e: Event) => {
  const target = e.target as HTMLInputElement;
  const file = target.files?.[0];
  if (!file) return;
  form.avatar = file;
  form.remove_avatar = false;
  avatarPreview.value = URL.createObjectURL(file);
};

const clearAvatar = () => {
  form.avatar = null;
  form.remove_avatar = true;
  avatarPreview.value = null;
};

const submit = () => {
  const url = editingId.value
    ? route('admin.testimonials.update', editingId.value)
    : route('admin.testimonials.store');

  form.post(url, {
    forceFormData: true,
    preserveScroll: true,
    onSuccess: () => closeModal(),
    onError: () => swalError('Gagal Menyimpan', 'Periksa kembali data testimoni yang Anda isi.'),
  });
};

const deleteItem = async (item: Testimonial) => {
  const confirmed = await swalConfirm(
    'Hapus Testimoni?',
    `Testimoni dari "${item.name}" akan dihapus dari daftar.`,
    'Ya, Hapus'
  );
  if (!confirmed) return;

  router.delete(route('admin.testimonials.destroy', item.id), {
    preserveScroll: true,
  });
};
</script>

<template>
  <Head title="Testimoni Klien — Admin InvoiceRapi" />

  <AdminLayout>
    <div class="space-y-6">
      <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
          <h1 class="text-2xl font-black text-slate-900 tracking-tight flex items-center gap-2">
            <MessageSquareQuote class="w-6 h-6 text-violet-600" />
            Testimoni Klien
          </h1>
          <p class="text-sm text-slate-500 mt-0.5">
            Kelola cerita pengguna &amp; foto klien untuk ditampilkan di halaman utama InvoiceRapi.
          </p>
        </div>

        <button
          type="button"
          @click="openCreate"
          class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-violet-600 hover:bg-violet-700 text-white text-sm font-bold shadow-sm transition self-start"
        >
          <Plus class="w-4 h-4" />
          Tambah Testimoni
        </button>
      </div>

      <!-- Visibility Notice -->
      <div class="rounded-2xl border border-violet-200/80 bg-violet-50/70 px-4 py-3 text-xs sm:text-sm text-violet-900 flex items-start gap-2.5">
        <EyeOff v-if="testimonials.filter((t) => t.is_active).length === 0" class="w-4 h-4 text-violet-700 shrink-0 mt-0.5" />
        <Eye v-else class="w-4 h-4 text-emerald-600 shrink-0 mt-0.5" />
        <div>
          <span v-if="testimonials.filter((t) => t.is_active).length === 0">
            <strong>Status Publik: Disembunyikan.</strong> Saat ini belum ada testimoni berstatus <em>Aktif</em>, sehingga bagian Testimoni &amp; menu Testimoni di halaman depan otomatis disembunyikan.
          </span>
          <span v-else>
            <strong>Status Publik: Ditampilkan ({{ testimonials.filter((t) => t.is_active).length }} testimoni aktif).</strong> Testimoni aktif otomatis tampil di halaman utama.
          </span>
        </div>
      </div>

      <!-- Empty State -->
      <div
        v-if="testimonials.length === 0"
        class="bg-white rounded-3xl border border-dashed border-violet-200 p-10 text-center space-y-3"
      >
        <div class="w-12 h-12 rounded-2xl bg-violet-50 text-violet-600 flex items-center justify-center mx-auto">
          <MessageSquareQuote class="w-6 h-6" />
        </div>
        <h2 class="text-base font-extrabold text-slate-900">Belum Ada Testimoni Klien</h2>
        <p class="text-sm text-slate-500 max-w-md mx-auto">
          Tambahkan testimoni asli dari klien beserta foto profilnya kapan saja saat Anda sudah siap menampilkannya di halaman utama.
        </p>
        <button
          type="button"
          @click="openCreate"
          class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-violet-600 hover:bg-violet-700 text-white text-xs font-bold transition"
        >
          <Plus class="w-4 h-4" />
          Tambah Testimoni Pertama
        </button>
      </div>

      <!-- Testimonials Grid -->
      <div v-else class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">
        <div
          v-for="item in testimonials"
          :key="item.id"
          class="bg-white rounded-2xl border p-5 shadow-xs flex flex-col justify-between space-y-4"
          :class="item.is_active ? 'border-violet-100' : 'border-slate-200 opacity-75'"
        >
          <div class="space-y-3">
            <div class="flex items-center justify-between gap-2">
              <div class="flex items-center gap-2">
                <span
                  class="px-2.5 py-0.5 rounded-full text-[11px] font-bold border"
                  :class="
                    item.is_active
                      ? 'bg-emerald-50 text-emerald-700 border-emerald-200'
                      : 'bg-slate-100 text-slate-600 border-slate-200'
                  "
                >
                  {{ item.is_active ? 'Aktif' : 'Disembunyikan' }}
                </span>
                <span v-if="item.metric" class="px-2.5 py-0.5 rounded-full bg-violet-50 text-violet-700 text-[11px] font-bold border border-violet-100">
                  {{ item.metric }}
                </span>
              </div>
              <span class="text-[11px] font-semibold text-slate-400">Urutan #{{ item.sort_order }}</span>
            </div>

            <div class="flex items-start gap-2">
              <Quote class="w-4 h-4 text-violet-300 shrink-0 mt-1" />
              <p class="text-sm text-slate-600 leading-relaxed line-clamp-4">"{{ item.quote }}"</p>
            </div>
          </div>

          <div class="pt-4 border-t border-slate-100 flex items-center justify-between gap-3">
            <div class="flex items-center gap-3 min-w-0">
              <img
                v-if="item.avatar_url"
                :src="item.avatar_url"
                :alt="item.name"
                class="w-10 h-10 rounded-full object-cover border border-violet-100 shrink-0"
              />
              <div
                v-else
                class="w-10 h-10 rounded-full bg-violet-100 text-violet-700 font-bold text-xs flex items-center justify-center shrink-0"
              >
                {{ item.name.charAt(0).toUpperCase() }}
              </div>
              <div class="min-w-0">
                <p class="text-sm font-bold text-slate-900 truncate">{{ item.name }}</p>
                <p class="text-xs text-slate-500 truncate">{{ item.role }}</p>
              </div>
            </div>

            <div class="flex items-center gap-1 shrink-0">
              <button
                type="button"
                @click="openEdit(item)"
                class="p-2 rounded-lg text-slate-500 hover:text-violet-700 hover:bg-violet-50 transition"
                title="Edit"
              >
                <Pencil class="w-4 h-4" />
              </button>
              <button
                type="button"
                @click="deleteItem(item)"
                class="p-2 rounded-lg text-slate-500 hover:text-red-600 hover:bg-red-50 transition"
                title="Hapus"
              >
                <Trash2 class="w-4 h-4" />
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal Create / Edit -->
    <div
      v-if="isModalOpen"
      class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-xs"
    >
      <div class="bg-white rounded-3xl border border-violet-100 shadow-2xl w-full max-w-lg max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
          <h2 class="text-lg font-black text-slate-900">
            {{ editingId ? 'Edit Testimoni Klien' : 'Tambah Testimoni Klien' }}
          </h2>
          <button
            type="button"
            @click="closeModal"
            class="p-1.5 rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-100"
          >
            <X class="w-5 h-5" />
          </button>
        </div>

        <form @submit.prevent="submit" class="p-6 space-y-4">
          <!-- Client Avatar Upload -->
          <div>
            <label class="block text-xs font-bold text-slate-700 mb-2">Foto / Gambar Client (Opsional)</label>
            <div class="flex items-center gap-4">
              <img
                v-if="avatarPreview"
                :src="avatarPreview"
                alt="Preview Foto Client"
                class="w-16 h-16 rounded-full object-cover border-2 border-violet-200 shrink-0"
              />
              <div
                v-else
                class="w-16 h-16 rounded-full bg-violet-50 border border-dashed border-violet-300 text-violet-400 flex items-center justify-center shrink-0"
              >
                <Upload class="w-5 h-5" />
              </div>

              <div class="space-y-1.5">
                <div class="flex flex-wrap items-center gap-2">
                  <label class="cursor-pointer inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-violet-50 hover:bg-violet-100 text-violet-700 text-xs font-bold transition">
                    <Upload class="w-3.5 h-3.5" />
                    Pilih Gambar
                    <input
                      type="file"
                      accept="image/png,image/jpeg,image/webp"
                      class="hidden"
                      @change="handleAvatarChange"
                    />
                  </label>
                  <button
                    v-if="avatarPreview"
                    type="button"
                    @click="clearAvatar"
                    class="px-3 py-2 rounded-xl text-xs font-bold text-red-600 hover:bg-red-50 transition"
                  >
                    Hapus Foto
                  </button>
                </div>
                <p class="text-[11px] text-slate-400">JPG, PNG, atau WEBP maksimal 2 MB.</p>
                <p v-if="form.errors.avatar" class="text-xs text-red-600">{{ form.errors.avatar }}</p>
              </div>
            </div>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label class="block text-xs font-bold text-slate-700 mb-1">Nama Client *</label>
              <input
                v-model="form.name"
                type="text"
                required
                placeholder="Contoh: Nadia Kusuma"
                class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-violet-500 focus:border-violet-500"
              />
              <p v-if="form.errors.name" class="text-xs text-red-600 mt-1">{{ form.errors.name }}</p>
            </div>

            <div>
              <label class="block text-xs font-bold text-slate-700 mb-1">Jabatan / Nama Usaha *</label>
              <input
                v-model="form.role"
                type="text"
                required
                placeholder="Contoh: Founder, Mulia Wedding"
                class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-violet-500 focus:border-violet-500"
              />
              <p v-if="form.errors.role" class="text-xs text-red-600 mt-1">{{ form.errors.role }}</p>
            </div>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label class="block text-xs font-bold text-slate-700 mb-1">Badge Metrik (Opsional)</label>
              <input
                v-model="form.metric"
                type="text"
                placeholder="Contoh: 40+ Acara / Tahun"
                class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-violet-500 focus:border-violet-500"
              />
              <p v-if="form.errors.metric" class="text-xs text-red-600 mt-1">{{ form.errors.metric }}</p>
            </div>

            <div>
              <label class="block text-xs font-bold text-slate-700 mb-1">Urutan Tampil</label>
              <input
                v-model.number="form.sort_order"
                type="number"
                min="0"
                class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-violet-500 focus:border-violet-500"
              />
            </div>
          </div>

          <div>
            <label class="block text-xs font-bold text-slate-700 mb-1">Isi Testimoni *</label>
            <textarea
              v-model="form.quote"
              rows="4"
              required
              placeholder="Tuliskan pengalaman klien menggunakan InvoiceRapi..."
              class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-violet-500 focus:border-violet-500"
            ></textarea>
            <p v-if="form.errors.quote" class="text-xs text-red-600 mt-1">{{ form.errors.quote }}</p>
          </div>

          <label class="flex items-center gap-2.5 p-3 rounded-xl bg-slate-50 border border-slate-200 cursor-pointer">
            <input
              v-model="form.is_active"
              type="checkbox"
              class="rounded text-violet-600 focus:ring-violet-500"
            />
            <span class="text-xs font-bold text-slate-700">Tampilkan di halaman utama (Aktif)</span>
          </label>

          <div class="flex items-center justify-end gap-2.5 pt-2 border-t border-slate-100">
            <button
              type="button"
              @click="closeModal"
              class="px-4 py-2 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-100 transition"
            >
              Batal
            </button>
            <button
              type="submit"
              :disabled="form.processing"
              class="px-5 py-2 rounded-xl bg-violet-600 hover:bg-violet-700 disabled:opacity-50 text-white text-xs font-bold shadow-sm transition"
            >
              {{ form.processing ? 'Menyimpan...' : editingId ? 'Simpan Perubahan' : 'Simpan Testimoni' }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </AdminLayout>
</template>

