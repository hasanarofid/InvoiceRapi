<script setup lang="ts">
import { ref } from 'vue';
import { useForm, usePage } from '@inertiajs/vue3';
import { Building2, Upload } from 'lucide-vue-next';
import { swalError } from '@/utils/swal';

const page = usePage();
const tenant = (page.props.auth as any)?.user?.tenant;

const previewUrl = ref<string | null>(tenant?.logo_url || null);

const form = useForm({
  name: tenant?.name || '',
  phone_whatsapp: tenant?.phone_whatsapp || '',
  email: tenant?.email || '',
  address: tenant?.address || '',
  logo: null as File | null,
});

const handleFileChange = (event: Event) => {
  const target = event.target as HTMLInputElement;
  const file = target.files?.[0];
  if (!file) return;
  form.logo = file;
  previewUrl.value = URL.createObjectURL(file);
};

const submit = () => {
  form.post(route('profile.business.update'), {
    forceFormData: true,
    preserveScroll: true,
    onError: () => swalError('Gagal Menyimpan Profil Usaha', 'Periksa nama usaha atau format/ukuran logo (maks 2MB).'),
  });
};
</script>

<template>
  <section class="space-y-5">
    <header>
      <h2 class="text-base font-black text-slate-900 flex items-center gap-2">
        <Building2 class="w-4 h-4 text-violet-600" />
        Identitas &amp; Logo Usaha
      </h2>
      <p class="mt-1 text-sm text-slate-500">
        Logo dan info usaha akan otomatis tampil pada panel workspace, halaman invoice publik, dan PDF resmi.
      </p>
    </header>

    <form @submit.prevent="submit" class="space-y-5 max-w-xl">
      <!-- Logo Upload -->
      <div class="flex items-center gap-4">
        <div class="w-16 h-16 rounded-2xl border border-slate-200 bg-violet-50 flex items-center justify-center overflow-hidden shrink-0">
          <img v-if="previewUrl" :src="previewUrl" alt="Logo Usaha" class="w-full h-full object-contain p-1 bg-white" />
          <span v-else class="text-2xl font-black text-violet-700">
            {{ form.name?.charAt(0)?.toUpperCase() || 'B' }}
          </span>
        </div>

        <div class="space-y-1.5">
          <label class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-violet-50 hover:bg-violet-100 text-violet-700 text-xs font-bold cursor-pointer transition border border-violet-200/70">
            <Upload class="w-3.5 h-3.5" />
            <span>{{ previewUrl ? 'Ganti Logo Usaha' : 'Upload Logo Usaha' }}</span>
            <input type="file" accept="image/png,image/jpeg,image/webp" class="hidden" @change="handleFileChange" />
          </label>
          <p class="text-[11px] text-slate-400">Format JPG, PNG, atau WebP. Maksimal 2 MB.</p>
          <p v-if="form.errors.logo" class="text-xs text-red-600 font-medium">{{ form.errors.logo }}</p>
        </div>
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
          <label class="block text-xs font-bold text-slate-700 mb-1.5">Nama Usaha / Bisnis *</label>
          <input
            v-model="form.name"
            type="text"
            required
            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-violet-500"
          />
          <p v-if="form.errors.name" class="mt-1 text-xs text-red-600">{{ form.errors.name }}</p>
        </div>

        <div>
          <label class="block text-xs font-bold text-slate-700 mb-1.5">No. WhatsApp Usaha</label>
          <input
            v-model="form.phone_whatsapp"
            type="text"
            placeholder="08123456789"
            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-violet-500"
          />
        </div>
      </div>

      <div>
        <label class="block text-xs font-bold text-slate-700 mb-1.5">Email Usaha (Opsional)</label>
        <input
          v-model="form.email"
          type="email"
          class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-violet-500"
        />
      </div>

      <div>
        <label class="block text-xs font-bold text-slate-700 mb-1.5">Alamat Usaha (Opsional)</label>
        <textarea
          v-model="form.address"
          rows="2"
          placeholder="Jl. Contoh No. 123, Kota"
          class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-violet-500"
        ></textarea>
      </div>

      <button
        type="submit"
        :disabled="form.processing"
        class="px-5 py-2.5 rounded-xl bg-violet-600 hover:bg-violet-700 disabled:opacity-50 text-white text-sm font-bold shadow-sm transition"
      >
        {{ form.processing ? 'Menyimpan...' : 'Simpan Profil & Logo Usaha' }}
      </button>
    </form>
  </section>
</template>

