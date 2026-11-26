<script setup lang="ts">
import { ref, computed } from 'vue';
import { Head, Link, router, useForm, usePage } from '@inertiajs/vue3';
import WorkspaceLayout from '@/Layouts/WorkspaceLayout.vue';
import StatusBadge from '@/Components/UI/StatusBadge.vue';
import RupiahInput from '@/Components/RupiahInput.vue';
import { swalConfirm, swalError } from '@/utils/swal';
import { formatIDR } from '@/utils/format';
import { Package, Plus, Search, Pencil, Trash2, Lock } from 'lucide-vue-next';

interface ServiceItem {
  id: number;
  name: string;
  unit?: string | null;
  unit_price: number | string;
  description?: string | null;
  is_active: boolean;
}

const props = defineProps<{
  items: {
    data: ServiceItem[];
    links: Array<{ url: string | null; label: string; active: boolean }>;
  };
  filters: {
    search?: string;
    status?: string;
  };
}>();

const page = usePage();
const user = computed(() => (page.props.auth as any)?.user);
const canDelete = computed(() => ['owner', 'tenant_admin', 'admin'].includes(user.value?.role || ''));
const isSubscriptionLocked = computed(() => Boolean((page.props.planUsage as Record<string, any> | null)?.is_subscription_locked));

const search = ref(props.filters.search || '');
const statusFilter = ref(props.filters.status || 'active');
const showModal = ref(false);
const editingItem = ref<ServiceItem | null>(null);

const form = useForm({
  name: '',
  unit: '',
  unit_price: 0 as number | string,
  description: '',
  is_active: true,
});

const applyFilter = () => {
  router.get(route('items.index'), {
    search: search.value || undefined,
    status: statusFilter.value || 'all',
  }, { preserveState: true, replace: true });
};

const openCreate = () => {
  if (isSubscriptionLocked.value) return;
  editingItem.value = null;
  form.reset();
  form.clearErrors();
  form.unit_price = 0;
  form.is_active = true;
  showModal.value = true;
};

const openEdit = (item: ServiceItem) => {
  if (isSubscriptionLocked.value) return;
  editingItem.value = item;
  form.clearErrors();
  form.name = item.name;
  form.unit = item.unit || '';
  form.unit_price = Number(item.unit_price || 0);
  form.description = item.description || '';
  form.is_active = item.is_active;
  showModal.value = true;
};

const submit = () => {
  const onError = () => swalError('Gagal Menyimpan', 'Periksa kembali data jasa/barang Anda.');
  if (editingItem.value) {
    form.put(route('items.update', editingItem.value.id), {
      onSuccess: () => { showModal.value = false; },
      onError,
    });
  } else {
    form.post(route('items.store'), {
      onSuccess: () => { showModal.value = false; form.reset(); },
      onError,
    });
  }
};

const deleteItem = async (item: ServiceItem) => {
  const ok = await swalConfirm('Hapus Item?', `Item "${item.name}" akan dihapus dari master jasa/barang.`, 'Ya, Hapus');
  if (!ok) return;
  router.delete(route('items.destroy', item.id));
};
</script>

<template>
  <Head title="Master Jasa & Barang — InvoiceRapi" />

  <WorkspaceLayout title="Jasa / Barang">
    <div class="space-y-6">
      <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
          <h1 class="text-2xl font-black text-slate-900 tracking-tight">Master Jasa &amp; Barang</h1>
          <p class="text-sm text-slate-500 mt-0.5">
            Simpan daftar layanan/produk beserta harga bakunya agar otomatis terisi saat membuat invoice.
          </p>
        </div>

        <Link
          v-if="isSubscriptionLocked"
          :href="route('billing.index')"
          class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-amber-500 hover:bg-amber-600 text-white text-sm font-bold shadow-sm transition self-start"
        >
          <Lock class="w-4 h-4" />
          Aktifkan Paket
        </Link>
        <button
          v-else
          @click="openCreate"
          class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-violet-600 hover:bg-violet-700 text-white text-sm font-bold shadow-sm transition self-start"
        >
          <Plus class="w-4 h-4" />
          Tambah Jasa / Barang
        </button>
      </div>

      <!-- Filter Bar -->
      <div class="bg-white rounded-2xl border border-slate-100 p-4 flex flex-col sm:flex-row gap-3">
        <div class="relative flex-1">
          <Search class="w-4 h-4 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2" />
          <input
            v-model="search"
            @keyup.enter="applyFilter"
            type="text"
            placeholder="Cari nama jasa, barang, atau satuan..."
            class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-violet-500"
          />
        </div>
        <select
          v-model="statusFilter"
          @change="applyFilter"
          class="px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-violet-500"
        >
          <option value="all">Semua Status</option>
          <option value="active">Aktif</option>
          <option value="inactive">Nonaktif</option>
        </select>
        <button @click="applyFilter" class="px-4 py-2.5 rounded-xl bg-slate-900 text-white text-sm font-bold hover:bg-slate-800 transition">
          Cari
        </button>
      </div>

      <!-- Table -->
      <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden shadow-sm">
        <div v-if="items.data.length === 0" class="p-12 text-center">
          <Package class="w-10 h-10 text-slate-300 mx-auto mb-3" />
          <p class="text-sm font-bold text-slate-700">Belum ada master jasa / barang</p>
          <p class="text-xs text-slate-500 mt-1">
            Tambahkan layanan atau produk Anda sekali saja, lalu tinggal ketik namanya saat membuat invoice.
          </p>
        </div>

        <div v-else class="overflow-x-auto">
          <table class="w-full text-left border-collapse">
            <thead>
              <tr class="border-b border-slate-100 bg-slate-50/70 text-[11px] font-bold uppercase tracking-wider text-slate-500">
                <th class="px-5 py-3.5">Nama Jasa / Barang</th>
                <th class="px-5 py-3.5">Satuan</th>
                <th class="px-5 py-3.5">Harga Satuan</th>
                <th class="px-5 py-3.5">Status</th>
                <th class="px-5 py-3.5 text-right">Aksi</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-sm">
              <tr v-for="item in items.data" :key="item.id" class="hover:bg-slate-50/60 transition">
                <td class="px-5 py-4">
                  <p class="font-bold text-slate-900">{{ item.name }}</p>
                  <p v-if="item.description" class="text-xs text-slate-500 mt-0.5 line-clamp-1">{{ item.description }}</p>
                </td>
                <td class="px-5 py-4 text-xs font-semibold text-slate-600">{{ item.unit || '-' }}</td>
                <td class="px-5 py-4 font-bold text-slate-900">{{ formatIDR(item.unit_price) }}</td>
                <td class="px-5 py-4">
                  <StatusBadge :status="item.is_active ? 'active' : 'inactive'" />
                </td>
                <td class="px-5 py-4 text-right space-x-2">
                  <button
                    @click="openEdit(item)"
                    :disabled="isSubscriptionLocked"
                    class="p-2 rounded-lg text-slate-500 hover:text-violet-600 hover:bg-violet-50 disabled:opacity-40 disabled:pointer-events-none transition"
                    title="Edit"
                  >
                    <Pencil class="w-4 h-4" />
                  </button>
                  <button
                    v-if="canDelete"
                    @click="deleteItem(item)"
                    :disabled="isSubscriptionLocked"
                    class="p-2 rounded-lg text-slate-500 hover:text-red-600 hover:bg-red-50 disabled:opacity-40 disabled:pointer-events-none transition"
                    title="Hapus"
                  >
                    <Trash2 class="w-4 h-4" />
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- Modal Create / Edit -->
    <div v-if="showModal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-sm p-4">
      <div class="bg-white rounded-3xl max-w-lg w-full p-6 shadow-2xl space-y-5">
        <div class="flex items-center justify-between">
          <h2 class="text-lg font-black text-slate-900">{{ editingItem ? 'Edit Jasa / Barang' : 'Tambah Jasa / Barang' }}</h2>
          <button @click="showModal = false" class="text-slate-400 hover:text-slate-700 text-sm font-bold">Tutup</button>
        </div>

        <form @submit.prevent="submit" class="space-y-4">
          <div>
            <label class="block text-xs font-bold text-slate-700 mb-1">Nama Jasa / Barang *</label>
            <input
              v-model="form.name"
              type="text"
              required
              placeholder="Contoh: Paket Dokumentasi Wedding / Nasi Kotak"
              class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm"
            />
            <p v-if="form.errors.name" class="text-xs text-red-600 mt-1">{{ form.errors.name }}</p>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            <div>
              <label class="block text-xs font-bold text-slate-700 mb-1">Harga Satuan (Rp) *</label>
              <RupiahInput v-model="form.unit_price" required />
              <p v-if="form.errors.unit_price" class="text-xs text-red-600 mt-1">{{ form.errors.unit_price }}</p>
            </div>
            <div>
              <label class="block text-xs font-bold text-slate-700 mb-1">Satuan (Opsional)</label>
              <input
                v-model="form.unit"
                type="text"
                placeholder="Contoh: paket, pcs, hari, jam"
                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm"
              />
            </div>
          </div>

          <div>
            <label class="block text-xs font-bold text-slate-700 mb-1">Catatan / Deskripsi (Opsional)</label>
            <textarea v-model="form.description" rows="2" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm"></textarea>
          </div>

          <label v-if="editingItem" class="flex items-center gap-2.5 p-3 rounded-xl bg-slate-50 border border-slate-200 cursor-pointer">
            <input v-model="form.is_active" type="checkbox" class="rounded text-violet-600 focus:ring-violet-500" />
            <span class="text-xs font-bold text-slate-700">Aktif (tampil di pilihan otomatis Buat Invoice)</span>
          </label>

          <div class="flex justify-end gap-2 pt-2">
            <button type="button" @click="showModal = false" class="px-4 py-2.5 rounded-xl border border-slate-200 text-sm font-bold text-slate-600">Batal</button>
            <button type="submit" :disabled="form.processing" class="px-5 py-2.5 rounded-xl bg-violet-600 hover:bg-violet-700 text-white text-sm font-bold">
              {{ form.processing ? 'Menyimpan...' : 'Simpan Item' }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </WorkspaceLayout>
</template>
