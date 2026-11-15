<script setup lang="ts">
import { ref } from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import WorkspaceLayout from '@/Layouts/WorkspaceLayout.vue';
import RupiahInput from '@/Components/RupiahInput.vue';
import { swalSuccess, swalError } from '@/utils/swal';
import { ArrowLeft, Copy, Check, ExternalLink, CreditCard, CheckCircle2 } from 'lucide-vue-next';

const props = defineProps<{
  invoice: any;
  publicUrl: string;
}>();

const copied = ref(false);
const showPayModal = ref(false);

const payForm = useForm({
  payment_installment_id: '' as string | number,
  amount: Math.max(0, Number(props.invoice.grand_total) - Number(props.invoice.paid_amount)),
  payment_date: new Date().toISOString().slice(0, 10),
  payment_method: 'bank_transfer',
  reference_number: '',
  notes: '',
});

const formatIDR = (val: number | string) =>
  new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(Number(val || 0));

const copyPublicLink = async () => {
  try {
    await navigator.clipboard.writeText(props.publicUrl);
    copied.value = true;
    swalSuccess('Tautan publik disalin!');
    setTimeout(() => (copied.value = false), 2000);
  } catch {
    swalError('Gagal Menyalin', 'Silakan salin URL secara manual.');
  }
};

const openPayModal = (installment?: any) => {
  if (installment) {
    payForm.payment_installment_id = installment.id;
    payForm.amount = Math.max(0, Number(installment.amount) - Number(installment.paid_amount || 0));
  } else {
    payForm.payment_installment_id = '';
    payForm.amount = Math.max(0, Number(props.invoice.grand_total) - Number(props.invoice.paid_amount));
  }
  showPayModal.value = true;
};

const submitPayment = () => {
  payForm.post(route('payments.store', props.invoice.id), {
    onSuccess: () => {
      showPayModal.value = false;
      payForm.reset('reference_number', 'notes');
    },
    onError: () => swalError('Gagal Mencatat Pembayaran', 'Periksa nominal dan metode pembayaran.'),
  });
};
</script>

<template>
  <Head :title="`Invoice ${invoice.invoice_number} — InvoiceRapi`" />

  <WorkspaceLayout :title="`Invoice ${invoice.invoice_number}`">
    <div class="max-w-4xl mx-auto space-y-6">
      <!-- Top Bar -->
      <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-center gap-3">
          <Link :href="route('invoices.index')" class="p-2 rounded-xl bg-white border border-slate-200 text-slate-600 hover:text-slate-900">
            <ArrowLeft class="w-4 h-4" />
          </Link>
          <div>
            <h1 class="text-2xl font-black text-slate-900">{{ invoice.invoice_number }}</h1>
            <p class="text-sm text-slate-500">Klien: <strong class="text-slate-800">{{ invoice.client?.name }}</strong></p>
          </div>
        </div>

        <div class="flex flex-wrap items-center gap-2">
          <button
            @click="copyPublicLink"
            class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-white border border-slate-200 text-xs font-bold text-slate-700 hover:bg-slate-50"
          >
            <Check v-if="copied" class="w-4 h-4 text-emerald-600" />
            <Copy v-else class="w-4 h-4" />
            {{ copied ? 'Tersalin!' : 'Salin Link Publik' }}
          </button>
          <a
            :href="publicUrl"
            target="_blank"
            class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-white border border-slate-200 text-xs font-bold text-slate-700 hover:bg-slate-50"
          >
            <ExternalLink class="w-4 h-4" /> Preview Publik
          </a>
          <button
            v-if="invoice.status !== 'paid'"
            @click="openPayModal()"
            class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-violet-600 hover:bg-violet-700 text-white text-xs font-bold shadow-sm"
          >
            <CreditCard class="w-4 h-4" /> Catat Pembayaran
          </button>
        </div>
      </div>

      <!-- Summary Card -->
      <div class="bg-white rounded-2xl border border-slate-100 p-6 grid grid-cols-2 sm:grid-cols-4 gap-4">
        <div>
          <p class="text-xs text-slate-500">Total Tagihan</p>
          <p class="text-lg font-black text-slate-900 mt-0.5">{{ formatIDR(invoice.grand_total) }}</p>
        </div>
        <div>
          <p class="text-xs text-slate-500">Sudah Dibayar</p>
          <p class="text-lg font-black text-emerald-600 mt-0.5">{{ formatIDR(invoice.paid_amount) }}</p>
        </div>
        <div>
          <p class="text-xs text-slate-500">Sisa Piutang</p>
          <p class="text-lg font-black text-amber-600 mt-0.5">
            {{ formatIDR(Math.max(0, Number(invoice.grand_total) - Number(invoice.paid_amount))) }}
          </p>
        </div>
        <div>
          <p class="text-xs text-slate-500">Jatuh Tempo</p>
          <p class="text-sm font-bold text-slate-900 mt-1">{{ invoice.due_date }}</p>
        </div>
      </div>

      <!-- Line Items -->
      <div class="bg-white rounded-2xl border border-slate-100 p-6 space-y-3">
        <h2 class="text-sm font-black uppercase tracking-wider text-slate-500">Rincian Item</h2>
        <div class="overflow-x-auto">
          <table class="w-full text-left text-sm">
            <thead>
              <tr class="border-b border-slate-100 text-xs text-slate-500">
                <th class="py-2">Item</th>
                <th class="py-2">Qty</th>
                <th class="py-2">Harga</th>
                <th class="py-2 text-right">Subtotal</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
              <tr v-for="item in invoice.items" :key="item.id">
                <td class="py-3 font-semibold text-slate-900">{{ item.name }}</td>
                <td class="py-3 text-slate-600">{{ item.quantity }}</td>
                <td class="py-3 text-slate-600">{{ formatIDR(item.unit_price) }}</td>
                <td class="py-3 text-right font-bold text-slate-900">
                  {{ formatIDR(Number(item.quantity) * Number(item.unit_price) - Number(item.discount || 0)) }}
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Installments -->
      <div class="bg-white rounded-2xl border border-slate-100 p-6 space-y-3">
        <h2 class="text-sm font-black uppercase tracking-wider text-slate-500">Jadwal Termin Pembayaran</h2>
        <div class="space-y-2.5">
          <div
            v-for="inst in invoice.installments"
            :key="inst.id"
            class="p-4 rounded-xl border border-slate-100 bg-slate-50/70 flex flex-col sm:flex-row sm:items-center justify-between gap-3"
          >
            <div>
              <p class="font-bold text-slate-900 text-sm">{{ inst.name }}</p>
              <p class="text-xs text-slate-500">Jatuh tempo: {{ inst.due_date }}</p>
            </div>
            <div class="flex items-center gap-4">
              <div class="text-right">
                <p class="font-black text-slate-900 text-sm">{{ formatIDR(inst.amount) }}</p>
                <p class="text-[11px] text-emerald-600">Terbayar: {{ formatIDR(inst.paid_amount || 0) }}</p>
              </div>
              <span
                v-if="inst.status === 'paid'"
                class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 text-xs font-bold"
              >
                <CheckCircle2 class="w-3.5 h-3.5" /> Lunas
              </span>
              <button
                v-else
                @click="openPayModal(inst)"
                class="px-3 py-1.5 rounded-lg bg-violet-600 text-white text-xs font-bold hover:bg-violet-700"
              >
                Bayar Termin
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- Payment History -->
      <div v-if="invoice.payments?.length" class="bg-white rounded-2xl border border-slate-100 p-6 space-y-3">
        <h2 class="text-sm font-black uppercase tracking-wider text-slate-500">Riwayat Pembayaran</h2>
        <div class="divide-y divide-slate-100 text-sm">
          <div v-for="p in invoice.payments" :key="p.id" class="py-3 flex items-center justify-between">
            <div>
              <p class="font-bold text-slate-900">{{ formatIDR(p.amount) }}</p>
              <p class="text-xs text-slate-500">{{ p.payment_date }} • {{ p.payment_method }} {{ p.reference_number ? `(${p.reference_number})` : '' }}</p>
            </div>
            <span class="text-xs text-slate-400">Dicatat oleh {{ p.creator?.name || 'Sistem' }}</span>
          </div>
        </div>
      </div>
    </div>

    <!-- Payment Modal -->
    <div v-if="showPayModal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-sm p-4">
      <div class="bg-white rounded-3xl max-w-md w-full p-6 shadow-2xl space-y-4">
        <div class="flex items-center justify-between">
          <h3 class="text-lg font-black text-slate-900">Catat Pembayaran</h3>
          <button @click="showPayModal = false" class="text-xs font-bold text-slate-500">Tutup</button>
        </div>

        <form @submit.prevent="submitPayment" class="space-y-4">
          <div>
            <label class="block text-xs font-bold text-slate-700 mb-1">Nominal Pembayaran (Rp) *</label>
            <RupiahInput v-model="payForm.amount" required />
          </div>
          <div class="grid grid-cols-2 gap-3">
            <div>
              <label class="block text-xs font-bold text-slate-700 mb-1">Tanggal *</label>
              <input v-model="payForm.payment_date" type="date" required class="w-full px-3 py-2.5 rounded-xl border border-slate-200 text-sm" />
            </div>
            <div>
              <label class="block text-xs font-bold text-slate-700 mb-1">Metode *</label>
              <select v-model="payForm.payment_method" class="w-full px-3 py-2.5 rounded-xl border border-slate-200 text-sm bg-white">
                <option value="bank_transfer">Transfer Bank</option>
                <option value="qris">QRIS</option>
                <option value="cash">Tunai</option>
                <option value="other">Lainnya</option>
              </select>
            </div>
          </div>
          <div>
            <label class="block text-xs font-bold text-slate-700 mb-1">No. Referensi / Berita</label>
            <input v-model="payForm.reference_number" type="text" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm" />
          </div>
          <div class="flex justify-end gap-2 pt-2">
            <button type="button" @click="showPayModal = false" class="px-4 py-2 rounded-xl border border-slate-200 text-xs font-bold text-slate-600">Batal</button>
            <button type="submit" :disabled="payForm.processing" class="px-5 py-2 rounded-xl bg-violet-600 text-white text-xs font-bold hover:bg-violet-700">
              {{ payForm.processing ? 'Menyimpan...' : 'Simpan Pembayaran' }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </WorkspaceLayout>
</template>

