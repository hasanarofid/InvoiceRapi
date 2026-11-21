<script setup lang="ts">
import { computed } from 'vue';
import { Head } from '@inertiajs/vue3';
import {
  Printer,
  Download,
  CheckCircle2,
  Clock,
  AlertCircle,
  Building2,
  Mail,
  Phone,
  MapPin,
  ShieldCheck,
  Calendar,
  FolderKanban,
} from 'lucide-vue-next';
import { formatIDR, formatDateID, formatQty } from '@/utils/format';

const props = defineProps<{
  invoice: any;
  pdfUrl?: string;
}>();

const grandTotal = computed(() => Number(props.invoice.total ?? props.invoice.grand_total ?? 0));
const totalPaid = computed(() => Number(props.invoice.total_paid ?? props.invoice.paid_amount ?? 0));
const balanceDue = computed(() =>
  Number(props.invoice.balance_due ?? Math.max(0, grandTotal.value - totalPaid.value))
);
const paymentAccount = computed(
  () => props.invoice.bank_account ?? props.invoice.tenant?.bank_accounts?.[0] ?? null
);

const statusMeta = computed(() => {
  const s = props.invoice.status;
  if (s === 'paid') return { label: 'LUNAS', cls: 'bg-emerald-50 text-emerald-700 border-emerald-200', icon: CheckCircle2 };
  if (s === 'partial') return { label: 'DIBAYAR SEBAGIAN', cls: 'bg-amber-50 text-amber-700 border-amber-200', icon: Clock };
  if (s === 'overdue') return { label: 'JATUH TEMPO', cls: 'bg-red-50 text-red-700 border-red-200', icon: AlertCircle };
  return null;
});

const printPage = () => {
  window.print();
};
</script>

<template>
  <Head :title="`Invoice ${invoice.invoice_number} — ${invoice.tenant?.name || 'InvoiceRapi'}`" />

  <div class="min-h-screen bg-slate-100 py-6 sm:py-10 px-3 sm:px-6 print:bg-white print:py-0 print:px-0">
    <div class="max-w-3xl mx-auto space-y-4">
      <!-- Top Action Bar -->
      <div class="flex items-center justify-between gap-3 print:hidden">
        <span class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-600 bg-white border border-slate-200 px-3 py-1.5 rounded-full shadow-xs">
          <ShieldCheck class="w-3.5 h-3.5 text-violet-600" />
          Dokumen Tagihan Resmi
        </span>
        <div class="flex items-center gap-2">
          <button
            @click="printPage"
            class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 text-xs font-bold shadow-xs transition"
          >
            <Printer class="w-4 h-4" />
            Cetak
          </button>
          <a
            :href="pdfUrl || `/i/${invoice.public_token}/pdf`"
            class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-violet-700 hover:bg-violet-800 text-white text-xs font-bold shadow-sm transition"
          >
            <Download class="w-4 h-4" />
            Unduh PDF
          </a>
        </div>
      </div>

      <!-- Clean Minimalist Corporate Invoice Sheet -->
      <div class="relative bg-white rounded-2xl sm:rounded-3xl border border-slate-200/80 shadow-lg overflow-hidden print:rounded-none print:border-0 print:shadow-none">
        <!-- Subtle Corner Accents -->
        <div class="pointer-events-none absolute -top-16 -right-16 w-48 h-48 rounded-full bg-violet-100/70"></div>
        <div class="pointer-events-none absolute -bottom-16 -left-16 w-44 h-44 rounded-full bg-violet-100/60"></div>

        <div class="relative z-10 p-6 sm:p-10 space-y-7">
          <!-- Brand Header -->
          <div class="flex items-start gap-4 pb-5 border-b border-violet-300/80">
            <div class="w-12 h-12 rounded-2xl bg-violet-100 text-violet-700 flex items-center justify-center font-black text-2xl shrink-0">
              {{ invoice.tenant?.name?.charAt(0)?.toUpperCase() || 'B' }}
            </div>
            <div class="space-y-0.5 min-w-0">
              <h1 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">
                {{ invoice.tenant?.name || 'Bisnis Anda' }}
              </h1>
              <p v-if="invoice.tenant?.email" class="text-xs text-slate-600 flex items-center gap-1.5">
                <Mail class="w-3.5 h-3.5 text-slate-400" /> {{ invoice.tenant.email }}
              </p>
              <p v-if="invoice.tenant?.phone_whatsapp || invoice.tenant?.phone" class="text-xs text-slate-600 flex items-center gap-1.5">
                <Phone class="w-3.5 h-3.5 text-slate-400" /> {{ invoice.tenant.phone_whatsapp || invoice.tenant.phone }}
              </p>
              <p v-if="invoice.tenant?.address" class="text-xs text-slate-500 flex items-center gap-1.5">
                <MapPin class="w-3.5 h-3.5 text-slate-400" /> {{ invoice.tenant.address }}
              </p>
            </div>
          </div>

          <!-- INVOICE Title & Status -->
          <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div>
              <p class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight leading-none">INVOICE</p>
              <p class="text-sm sm:text-base font-medium text-slate-600 mt-1">{{ invoice.invoice_number }}</p>
            </div>
            <span
              v-if="statusMeta"
              class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full text-xs font-extrabold border self-start sm:self-center"
              :class="statusMeta.cls"
            >
              <component :is="statusMeta.icon" class="w-3.5 h-3.5" />
              {{ statusMeta.label }}
            </span>
          </div>

          <!-- Client & Dates Grid -->
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 py-2">
            <div class="space-y-1 sm:pr-6 sm:border-r sm:border-slate-200">
              <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Ditagihkan Kepada</p>
              <p class="text-base font-black text-slate-900">{{ invoice.client?.name || '-' }}</p>
              <p v-if="invoice.client?.company_name" class="text-xs font-semibold text-slate-600 flex items-center gap-1.5">
                <Building2 class="w-3.5 h-3.5 text-slate-400" /> {{ invoice.client.company_name }}
              </p>
              <p v-if="invoice.client?.address" class="text-xs text-slate-600 flex items-center gap-1.5">
                <MapPin class="w-3.5 h-3.5 text-slate-400" /> {{ invoice.client.address }}
              </p>
              <p v-if="invoice.client?.email" class="text-xs text-slate-500">{{ invoice.client.email }}</p>
              <p v-if="invoice.client?.phone" class="text-xs text-slate-500">{{ invoice.client.phone }}</p>
            </div>

            <div class="space-y-2.5 text-xs">
              <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                <span class="text-slate-500 flex items-center gap-1.5">
                  <Calendar class="w-3.5 h-3.5 text-slate-400" /> Tanggal Invoice
                </span>
                <span class="font-bold text-slate-900">{{ formatDateID(invoice.invoice_date) }}</span>
              </div>
              <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                <span class="text-slate-500 flex items-center gap-1.5">
                  <Calendar class="w-3.5 h-3.5 text-slate-400" /> Jatuh Tempo
                </span>
                <span class="font-bold text-red-600">{{ formatDateID(invoice.due_date) }}</span>
              </div>
              <div v-if="invoice.project" class="flex items-center justify-between">
                <span class="text-slate-500 flex items-center gap-1.5">
                  <FolderKanban class="w-3.5 h-3.5 text-slate-400" /> Proyek
                </span>
                <span class="font-bold text-slate-900">{{ invoice.project.name }}</span>
              </div>
            </div>
          </div>

          <!-- Items Table (Soft Lavender Header) -->
          <div class="overflow-x-auto rounded-2xl border border-slate-200/80">
            <table class="w-full text-left text-sm border-collapse">
              <thead>
                <tr class="bg-violet-50/90 text-xs font-bold text-slate-800 border-b border-violet-100">
                  <th class="py-3 px-4 w-10 text-center">No</th>
                  <th class="py-3 px-4">Deskripsi Layanan / Item</th>
                  <th class="py-3 px-4 text-center">Qty</th>
                  <th class="py-3 px-4 text-right">Harga Satuan</th>
                  <th class="py-3 px-4 text-right">Total</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-slate-100">
                <tr v-for="(item, idx) in invoice.items" :key="item.id">
                  <td class="py-3.5 px-4 text-center text-xs font-semibold text-slate-500">{{ Number(idx) + 1 }}</td>
                  <td class="py-3.5 px-4">
                    <p class="font-bold text-slate-900">{{ item.name }}</p>
                    <p v-if="item.description" class="text-xs text-slate-500 mt-0.5">{{ item.description }}</p>
                  </td>
                  <td class="py-3.5 px-4 text-center font-semibold text-slate-700">{{ formatQty(item.quantity) }}</td>
                  <td class="py-3.5 px-4 text-right text-slate-700">{{ formatIDR(item.unit_price) }}</td>
                  <td class="py-3.5 px-4 text-right font-bold text-slate-900">
                    {{ formatIDR(item.total_price ?? (Number(item.quantity) * Number(item.unit_price) - Number(item.discount || 0))) }}
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

          <!-- Payment Box & Totals Split -->
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 items-start">
            <!-- Payment Instructions Box -->
            <div class="sm:pr-6 sm:border-r sm:border-slate-200">
              <div class="p-4 sm:p-5 rounded-2xl bg-violet-50/80 border border-violet-100 space-y-2.5 text-xs text-slate-700">
                <p class="text-xs font-black uppercase tracking-wider text-violet-700">PEMBAYARAN:</p>

                <table v-if="paymentAccount" class="w-full text-xs text-slate-800">
                  <tbody>
                    <tr>
                      <td class="py-1 text-slate-500 w-28">Bank</td>
                      <td class="py-1 font-bold">: {{ paymentAccount.bank_name }}</td>
                    </tr>
                    <tr>
                      <td class="py-1 text-slate-500">No. Rekening</td>
                      <td class="py-1 font-bold font-mono">: {{ paymentAccount.account_number }}</td>
                    </tr>
                    <tr>
                      <td class="py-1 text-slate-500">Atas Nama</td>
                      <td class="py-1 font-bold">: {{ paymentAccount.account_holder }}</td>
                    </tr>
                    <tr>
                      <td class="py-1 text-slate-500">Berita Transfer</td>
                      <td class="py-1 font-bold">: {{ invoice.invoice_number }}</td>
                    </tr>
                  </tbody>
                </table>

                <p v-if="invoice.payment_instructions" class="whitespace-pre-line leading-relaxed text-slate-700" :class="paymentAccount ? 'pt-2 border-t border-violet-200/60' : ''">
                  {{ invoice.payment_instructions }}
                </p>
                <p v-else-if="!paymentAccount" class="leading-relaxed text-slate-700">
                  Silakan lakukan transfer sebelum jatuh tempo dengan mencantumkan berita transfer <strong>{{ invoice.invoice_number }}</strong>.
                </p>
                <div v-if="invoice.notes" class="pt-2 border-t border-violet-200/60 text-slate-600">
                  <span class="font-bold text-slate-700">Catatan:</span> {{ invoice.notes }}
                </div>
              </div>
            </div>

            <!-- Totals -->
            <div class="space-y-2.5 text-sm">
              <div class="flex justify-between px-2 text-xs text-slate-600">
                <span>Subtotal</span>
                <span class="font-semibold text-slate-900">{{ formatIDR(invoice.subtotal ?? grandTotal) }}</span>
              </div>
              <div v-if="Number(invoice.discount) > 0" class="flex justify-between px-2 text-xs text-slate-600">
                <span>Diskon</span>
                <span class="font-semibold text-emerald-600">-{{ formatIDR(invoice.discount) }}</span>
              </div>
              <div v-if="Number(invoice.tax) > 0" class="flex justify-between px-2 text-xs text-slate-600">
                <span>Pajak / PPN</span>
                <span class="font-semibold text-slate-900">{{ formatIDR(invoice.tax) }}</span>
              </div>
              <div class="flex justify-between px-2 pt-2.5 border-t border-slate-200 font-bold text-slate-900">
                <span>Total Tagihan</span>
                <span>{{ formatIDR(grandTotal) }}</span>
              </div>
              <div class="flex justify-between px-2 text-xs text-slate-600">
                <span>Sudah Dibayar</span>
                <span class="font-bold text-emerald-600">{{ formatIDR(totalPaid) }}</span>
              </div>
              <div class="flex justify-between items-center px-4 py-3 rounded-xl bg-violet-100/80 text-violet-800 font-black text-base">
                <span>Sisa Tagihan</span>
                <span>{{ formatIDR(balanceDue) }}</span>
              </div>
            </div>
          </div>

          <!-- Installments Schedule (if multi-termin) -->
          <div v-if="invoice.installments?.length > 1" class="space-y-2.5 pt-2 border-t border-slate-100">
            <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Jadwal Termin Pembayaran</p>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
              <div v-for="inst in invoice.installments" :key="inst.id" class="p-3.5 rounded-xl bg-slate-50 border border-slate-100 flex items-center justify-between text-xs">
                <div>
                  <p class="font-bold text-slate-900">{{ inst.name }}</p>
                  <p class="text-slate-500">Jatuh tempo: {{ formatDateID(inst.due_date) }}</p>
                </div>
                <div class="text-right">
                  <p class="font-black text-slate-900">{{ formatIDR(inst.amount) }}</p>
                  <p :class="inst.status === 'paid' ? 'text-emerald-600 font-bold' : 'text-amber-600 font-semibold'">
                    {{ inst.status === 'paid' ? 'Lunas' : 'Belum Lunas' }}
                  </p>
                </div>
              </div>
            </div>
          </div>

          <!-- Official Footer -->
          <div class="pt-4 space-y-3">
            <p class="text-xs italic text-slate-600">
              Terima kasih atas kepercayaan Anda berbisnis bersama <strong class="not-italic text-slate-900">{{ invoice.tenant?.name }}</strong>.
            </p>
            <div class="pt-3 border-t border-violet-300/80 text-right text-[11px] text-slate-500">
              Diterbitkan resmi via <strong class="text-violet-700">InvoiceRapi</strong>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
