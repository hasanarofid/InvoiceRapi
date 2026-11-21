<script setup lang="ts">
import { ref } from 'vue';
import { Head, router, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { formatDateID } from '@/utils/format';
import {
  CheckCircle2,
  XCircle,
  Clock,
  ExternalLink,
  CreditCard,
  X
} from 'lucide-vue-next';

interface PaymentRow {
  id: number;
  tenant_name: string;
  user_name: string;
  user_email: string;
  plan: string;
  billing_cycle: string;
  amount: number;
  sender_name: string;
  sender_bank: string | null;
  transfer_date: string;
  proof_url: string;
  status: 'pending' | 'approved' | 'rejected';
  admin_notes: string | null;
  created_at: string;
}

const props = defineProps<{
  payments: {
    data: PaymentRow[];
    links: any[];
  };
  filters: {
    status: string;
  };
  counts: {
    all: number;
    pending: number;
    approved: number;
    rejected: number;
  };
}>();

const selectedPayment = ref<PaymentRow | null>(null);
const verifyForm = useForm({
  action: 'approve' as 'approve' | 'reject',
  admin_notes: '',
});

const openVerifyModal = (payment: PaymentRow, action: 'approve' | 'reject') => {
  selectedPayment.value = payment;
  verifyForm.action = action;
  verifyForm.admin_notes = '';
};

const closeVerifyModal = () => {
  selectedPayment.value = null;
};

const submitVerify = () => {
  if (!selectedPayment.value) return;
  verifyForm.post(route('admin.payments.verify', selectedPayment.value.id), {
    preserveScroll: true,
    onSuccess: () => closeVerifyModal(),
  });
};

const filterStatus = (status: string) => {
  router.get(route('admin.payments.index'), { status }, { preserveState: true, preserveScroll: true });
};

const formatRupiah = (val: number) =>
  new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(val);
</script>

<template>
  <Head title="Verifikasi Pembayaran — Admin InvoiceRapi" />

  <AdminLayout>
    <div class="space-y-6">
      <div class="bg-white rounded-2xl border border-violet-100 p-6 shadow-invoicerapi-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
          <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-bold bg-violet-50 text-violet-700 border border-violet-200/70">
            <CreditCard class="w-3.5 h-3.5" />
            VERIFIKASI TRANSFER BCA 4650499269
          </div>
          <h2 class="text-2xl font-extrabold text-slate-900 mt-2">Verifikasi Bukti Pembayaran Paket</h2>
          <p class="text-xs sm:text-sm text-slate-500 mt-0.5">
            Setujui atau tolak bukti transfer upgrade paket Pro / Bisnis dari tenant.
          </p>
        </div>

        <div class="flex flex-wrap gap-2">
          <button
            v-for="tab in ([
              { id: 'all', label: 'Semua', count: counts.all },
              { id: 'pending', label: 'Menunggu', count: counts.pending },
              { id: 'approved', label: 'Disetujui', count: counts.approved },
              { id: 'rejected', label: 'Ditolak', count: counts.rejected },
            ])"
            :key="tab.id"
            type="button"
            @click="filterStatus(tab.id)"
            :class="[
              'px-3.5 py-2 rounded-xl text-xs font-bold transition flex items-center gap-1.5',
              filters.status === tab.id ? 'bg-violet-700 text-white' : 'bg-slate-100 text-slate-600 hover:bg-violet-50 hover:text-violet-700'
            ]"
          >
            <span>{{ tab.label }}</span>
            <span class="px-1.5 py-0.5 rounded-full text-[10px] bg-white/20">{{ tab.count }}</span>
          </button>
        </div>
      </div>

      <!-- Table -->
      <div class="bg-white rounded-2xl border border-violet-100 shadow-invoicerapi-sm overflow-hidden">
        <div v-if="payments.data.length === 0" class="p-10 text-center text-sm text-slate-500">
          Belum ada pengajuan pembayaran pada filter ini.
        </div>

        <div v-else class="overflow-x-auto">
          <table class="w-full text-left text-xs sm:text-sm">
            <thead class="bg-[#FAF8FF] border-b border-violet-100 text-xs font-bold text-slate-500 uppercase">
              <tr>
                <th class="px-5 py-3.5">Tenant &amp; Pengguna</th>
                <th class="px-5 py-3.5">Paket &amp; Nominal</th>
                <th class="px-5 py-3.5">Pengirim</th>
                <th class="px-5 py-3.5">Bukti</th>
                <th class="px-5 py-3.5">Status</th>
                <th class="px-5 py-3.5 text-right">Aksi</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
              <tr v-for="row in payments.data" :key="row.id" class="hover:bg-violet-50/30 transition">
                <td class="px-5 py-4">
                  <p class="font-bold text-slate-900">{{ row.tenant_name }}</p>
                  <p class="text-xs text-slate-500">{{ row.user_name }} &bull; {{ row.user_email }}</p>
                </td>
                <td class="px-5 py-4">
                  <p class="font-bold text-violet-700 uppercase">{{ row.plan }} ({{ row.billing_cycle === 'annual' ? 'Tahunan' : 'Bulanan' }})</p>
                  <p class="font-black text-slate-900">{{ formatRupiah(row.amount) }}</p>
                </td>
                <td class="px-5 py-4 text-slate-600">
                  <p class="font-semibold text-slate-800">{{ row.sender_name }}</p>
                  <p class="text-xs">{{ row.sender_bank || '-' }} &bull; {{ formatDateID(row.transfer_date) }}</p>
                </td>
                <td class="px-5 py-4">
                  <a
                    :href="row.proof_url"
                    target="_blank"
                    class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-violet-50 text-violet-700 text-xs font-bold hover:bg-violet-100 transition"
                  >
                    <span>Lihat Bukti</span>
                    <ExternalLink class="w-3 h-3" />
                  </a>
                </td>
                <td class="px-5 py-4">
                  <span
                    v-if="row.status === 'approved'"
                    class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-emerald-100 text-emerald-700 text-xs font-bold"
                  >
                    <CheckCircle2 class="w-3.5 h-3.5" /> Disetujui
                  </span>
                  <span
                    v-else-if="row.status === 'rejected'"
                    class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-red-100 text-red-700 text-xs font-bold"
                  >
                    <XCircle class="w-3.5 h-3.5" /> Ditolak
                  </span>
                  <span
                    v-else
                    class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-amber-100 text-amber-800 text-xs font-bold"
                  >
                    <Clock class="w-3.5 h-3.5" /> Menunggu
                  </span>
                </td>
                <td class="px-5 py-4 text-right">
                  <div v-if="row.status === 'pending'" class="inline-flex items-center gap-2">
                    <button
                      type="button"
                      @click="openVerifyModal(row, 'approve')"
                      class="px-3 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold transition"
                    >
                      Setujui
                    </button>
                    <button
                      type="button"
                      @click="openVerifyModal(row, 'reject')"
                      class="px-3 py-1.5 rounded-lg bg-red-50 hover:bg-red-100 text-red-600 text-xs font-bold transition"
                    >
                      Tolak
                    </button>
                  </div>
                  <span v-else class="text-xs text-slate-400">Selesai</span>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- Verify Modal -->
    <div v-if="selectedPayment" class="fixed inset-0 z-50 bg-slate-900/50 backdrop-blur-xs flex items-center justify-center p-4">
      <div class="bg-white rounded-2xl border border-violet-100 shadow-2xl max-w-md w-full p-6 space-y-4">
        <div class="flex items-center justify-between">
          <h3 class="text-lg font-extrabold text-slate-900">
            {{ verifyForm.action === 'approve' ? 'Setujui Pembayaran' : 'Tolak Pembayaran' }}
          </h3>
          <button type="button" @click="closeVerifyModal" class="p-1 text-slate-400 hover:text-slate-700">
            <X class="w-5 h-5" />
          </button>
        </div>

        <p class="text-xs text-slate-600">
          Tenant: <strong>{{ selectedPayment.tenant_name }}</strong> &bull;
          Nominal: <strong>{{ formatRupiah(selectedPayment.amount) }}</strong>
        </p>

        <div>
          <label class="block text-xs font-semibold text-slate-700 mb-1">Catatan Admin (Opsional)</label>
          <textarea
            v-model="verifyForm.admin_notes"
            rows="3"
            placeholder="Tambahkan catatan untuk pengguna..."
            class="w-full rounded-xl border-slate-200 text-xs focus:border-violet-600 focus:ring-violet-600"
          ></textarea>
        </div>

        <div class="flex justify-end gap-2 pt-2">
          <button
            type="button"
            @click="closeVerifyModal"
            class="px-4 py-2 rounded-xl bg-slate-100 text-slate-700 text-xs font-bold"
          >
            Batal
          </button>
          <button
            type="button"
            @click="submitVerify"
            :disabled="verifyForm.processing"
            :class="[
              'px-4 py-2 rounded-xl text-white text-xs font-bold transition disabled:opacity-50',
              verifyForm.action === 'approve' ? 'bg-emerald-600 hover:bg-emerald-700' : 'bg-red-600 hover:bg-red-700'
            ]"
          >
            {{ verifyForm.processing ? 'Memproses...' : (verifyForm.action === 'approve' ? 'Konfirmasi & Aktifkan Paket' : 'Konfirmasi Penolakan') }}
          </button>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

