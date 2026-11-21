<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import RupiahInput from '@/Components/RupiahInput.vue';
import { swalError } from '@/utils/swal';
import { Crown, Save, Check } from 'lucide-vue-next';

const props = defineProps<{
  plans: Record<string, any>;
}>();

const form = useForm({
  plans: JSON.parse(JSON.stringify(props.plans)),
});

const featureLabels: Record<string, string> = {
  dp_termin: 'Atur Jadwal DP & Multi-Termin',
  auto_reminder: 'Pengingat Jatuh Tempo Otomatis',
  receivables_report: 'Laporan Piutang Lengkap',
  custom_public_link: 'Tautan Invoice Publik Kustom',
  csv_export_audit: 'Ekspor CSV & Audit Log Transaksi',
};

const submit = () => {
  form.post(route('admin.plans.update'), {
    preserveScroll: true,
    onError: () => swalError('Gagal Menyimpan Paket', 'Periksa kembali nilai harga dan kuota yang Anda masukkan.'),
  });
};
</script>

<template>
  <Head title="Pengaturan Paket — Admin InvoiceRapi" />

  <AdminLayout>
    <div class="space-y-6">
      <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
          <h1 class="text-2xl font-black text-slate-900 tracking-tight flex items-center gap-2">
            <Crown class="w-6 h-6 text-violet-600" />
            Pengaturan Paket &amp; Fitur
          </h1>
          <p class="text-sm text-slate-500 mt-0.5">
            Atur harga promo, batas kuota bulanan, dan fitur yang aktif pada paket Gratis, Pro, dan Bisnis.
          </p>
        </div>

        <button
          type="button"
          @click="submit"
          :disabled="form.processing"
          class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-violet-600 hover:bg-violet-700 disabled:opacity-50 text-white text-sm font-bold shadow-sm transition self-start"
        >
          <Save class="w-4 h-4" />
          {{ form.processing ? 'Menyimpan...' : 'Simpan Pengaturan Paket' }}
        </button>
      </div>

      <form @submit.prevent="submit" class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div
          v-for="slug in ['gratis', 'pro', 'bisnis']"
          :key="slug"
          class="bg-white rounded-2xl border p-6 shadow-sm space-y-5"
          :class="slug === 'pro' ? 'border-violet-300 ring-2 ring-violet-100' : 'border-slate-200'"
        >
          <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <div>
              <span class="text-[10px] font-bold uppercase tracking-wider text-violet-600">Paket {{ slug }}</span>
              <input
                v-model="form.plans[slug].name"
                type="text"
                required
                class="block mt-1 w-full px-3 py-1.5 rounded-lg border border-slate-200 text-base font-black text-slate-900"
              />
            </div>
          </div>

          <!-- Prices -->
          <div class="space-y-3">
            <div>
              <label class="block text-xs font-bold text-slate-700 mb-1">Harga Bulanan (Rp)</label>
              <RupiahInput v-model="form.plans[slug].monthly_price" />
            </div>
            <div>
              <label class="block text-xs font-bold text-slate-700 mb-1">Harga Tahunan / Tahun (Rp)</label>
              <RupiahInput v-model="form.plans[slug].annual_price" />
            </div>
          </div>

          <!-- Quotas -->
          <div class="grid grid-cols-3 gap-2.5 pt-2 border-t border-slate-100">
            <div>
              <label class="block text-[11px] font-bold text-slate-600 mb-1">Invoice/Bln</label>
              <input
                v-model.number="form.plans[slug].max_invoices_per_month"
                type="number"
                min="1"
                placeholder="∞"
                class="w-full px-2.5 py-2 rounded-lg border border-slate-200 text-xs font-bold"
              />
            </div>
            <div>
              <label class="block text-[11px] font-bold text-slate-600 mb-1">Max Klien</label>
              <input
                v-model.number="form.plans[slug].max_clients"
                type="number"
                min="1"
                placeholder="∞"
                class="w-full px-2.5 py-2 rounded-lg border border-slate-200 text-xs font-bold"
              />
            </div>
            <div>
              <label class="block text-[11px] font-bold text-slate-600 mb-1">Max User</label>
              <input
                v-model.number="form.plans[slug].max_users"
                type="number"
                min="1"
                required
                class="w-full px-2.5 py-2 rounded-lg border border-slate-200 text-xs font-bold"
              />
            </div>
          </div>
          <p class="text-[10px] text-slate-400">Kosongkan Invoice/Klien untuk kuota tanpa batas (∞).</p>

          <!-- Features -->
          <div class="space-y-2.5 pt-2 border-t border-slate-100">
            <p class="text-xs font-black uppercase tracking-wider text-slate-500">Fitur Aktif</p>
            <label
              v-for="(label, key) in featureLabels"
              :key="key"
              class="flex items-center justify-between gap-2 p-2.5 rounded-xl bg-slate-50 border border-slate-100 cursor-pointer hover:bg-violet-50/40 transition"
            >
              <span class="text-xs font-semibold text-slate-700">{{ label }}</span>
              <input
                v-model="form.plans[slug].features[key]"
                type="checkbox"
                class="rounded border-slate-300 text-violet-600 focus:ring-violet-500"
              />
            </label>
          </div>
        </div>
      </form>
    </div>
  </AdminLayout>
</template>
