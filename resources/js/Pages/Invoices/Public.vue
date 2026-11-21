<script setup lang="ts">
import { computed } from 'vue';
import { Head } from '@inertiajs/vue3';
import { Printer, CheckCircle2, Clock, AlertCircle, Building2, Mail, Phone, ShieldCheck } from 'lucide-vue-next';
import { formatIDR, formatDateID, formatQty } from '@/utils/format';

const props = defineProps<{
  invoice: any;
}>();

const grandTotal = computed(() => Number(props.invoice.total ?? props.invoice.grand_total ?? 0));
const totalPaid = computed(() => Number(props.invoice.total_paid ?? props.invoice.paid_amount ?? 0));
const balanceDue = computed(() =>
  Number(props.invoice.balance_due ?? Math.max(0, grandTotal.value - totalPaid.value))
);

const statusMeta = computed(() => {
  const s = props.invoice.status;
  if (s === 'paid') return { label: 'LUNAS', cls: 'bg-emerald-50 text-emerald-700 border-emerald-200', icon: CheckCircle2 };
  if (s === 'partial') return { label: 'DIBAYAR SEBAGIAN', cls: 'bg-amber-50 text-amber-700 border-amber-200', icon: Clock };
  if (s === 'overdue') return { label: 'JATUH TEMPO', cls: 'bg-red-50 text-red-700 border-red-200', icon: AlertCircle };
  return { label: 'MENUNGGU PEMBAYARAN', cls: 'bg-violet-50 text-violet-700 border-violet-200', icon: Clock };
});

const printPage = () => {
  window.print();
};
</script>

<template>
  <Head :title="`Invoice ${invoice.invoice_number} — ${invoice.tenant?.name || 'InvoiceRapi'}`" />

  <div class="min-h-screen bg-slate-100 py-6 sm:py-10 px-3 sm:px-6 print:bg-white print:py-0 print:px-0">
    <div class="max-w-3xl mx-auto space-y-4">
      <!-- Top Action Bar (Hidden when printing) -->
      <div class="flex items-center justify-between gap-3 print:hidden">
        <span class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-600 bg-white border border-slate-200 px-3 py-1.5 rounded-full shadow-xs">
          <ShieldCheck class="w-3.5 h-3.5 text-violet-600" />
          Dokumen Tagihan Resmi
        </span>
        <button
          @click="printPage"
          class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-violet-700 hover:bg-violet-800 text-white text-xs font-bold shadow-sm transition"
        >
          <Printer class="w-4 h-4" />
          Cetak / Unduh PDF
        </button>
      </div>

      <!-- Official Corporate Invoice Paper -->
      <div class="bg-white rounded-2xl sm:rounded-3xl border border-slate-200/80 shadow-lg overflow-hidden print:rounded-none print:border-0 print:shadow-none">
        <!-- Letterhead Band -->
        <div class="bg-gradient-to-r from-violet-700 via-violet-800 to-indigo-900 text-white px-6 sm:px-10 py-7 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
          <div class="space-y-1">
            <div class="flex items-center gap-2.5">
              <div class="w-9 h-9 rounded-xl bg-white/15 border border-white/20 flex items-center justify-center font-black text-base">
                {{ invoice.tenant?.name?.charAt(0)?.toUpperCase() || 'B' }}
              </div>
              <h1 class="text-xl sm:text-2xl font-black tracking-tight">
                {{ invoice.tenant?.name || 'Bisnis Anda' }}
              </h1>
            </div>
            <div class="flex flex-wrap items-center gap-x-4 gap-y-1 text-xs text-violet-100/90 pt-1">
              <span v-if="invoice.tenant?.email" class="inline-flex items-center gap-1">
                <Mail class="w-3.5 h-3.5" /> {{ invoice.tenant.email }}
              </span>
              <span v-if="invoice.tenant?.phone" class="inline-flex items-center gap-1">
                <Phone class="w-3.5 h-3.5" /> {{ invoice.tenant.phone }}
              </span>
            </div>
            <p v-if="invoice.tenant?.address" class="text-xs text-violet-100/80 max-w-md">
              {{ invoice.tenant.address }}
            </p>
          </div>

          <div class="sm:text-right">
            <p class="text-[11px] font-bold uppercase tracking-[0.2em] text-violet-200">INVOICE</p>
            <p class="text-xl sm:text-2xl font-black tracking-tight mt-0.5">{{ invoice.invoice_number }}</p>
            <span
              class="inline-flex items-center gap-1.5 mt-2.5 px-3 py-1 rounded-full text-[11px] font-extrabold border bg-white"
              :class="statusMeta.cls"
            >
              <component :is="statusMeta.icon" class="w-3.5 h-3.5" />
              {{ statusMeta.label }}
            </span>
          </div>
        </div>

        <div class="p-6 sm:p-10 space-y-8">
          <!-- Billing Info Grid -->
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 bg-slate-50/80 rounded-2xl p-5 border border-slate-100">
            <div class="space-y-1">
              <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Ditagihkan Kepada</p>
              <p class="text-base font-black text-slate-900">{{ invoice.client?.name || '-' }}</p>
              <p v-if="invoice.client?.company_name" class="text-xs font-semibold text-slate-700 flex items-center gap-1.5">
                <Building2 class="w-3.5 h-3.5 text-slate-400" /> {{ invoice.client.company_name }}
              </p>
              <p v-if="invoice.client?.email" class="text-xs text-slate-600">{{ invoice.client.email }}</p>
              <p v-if="invoice.client?.phone" class="text-xs text-slate-600">{{ invoice.client.phone }}</p>
              <p v-if="invoice.client?.address" class="text-xs text-slate-500 pt-0.5">{{ invoice.client.address }}</p>
            </div>

            <div class="sm:text-right space-y-2 text-xs">
              <div>
                <span class="text-slate-400 block uppercase font-bold tracking-wider text-[10px]">Tanggal Terbit</span>
                <span class="text-sm font-bold text-slate-900">{{ formatDateID(invoice.invoice_date) }}</span>
              </div>
              <div>
                <span class="text-slate-400 block uppercase font-bold tracking-wider text-[10px]">Jatuh Tempo</span>
                <span class="text-sm font-bold text-red-600">{{ formatDateID(invoice.due_date) }}</span>
              </div>
              <div v-if="invoice.project">
                <span class="text-slate-400 block uppercase font-bold tracking-wider text-[10px]">Proyek</span>
                <span class="text-sm font-bold text-slate-900">{{ invoice.project.name }}</span>
              </div>
            </div>
          </div>

          <!-- Items Table -->
          <div class="overflow-x-auto rounded-2xl border border-slate-200/80">
            <table class="w-full text-left text-sm border-collapse">
              <thead>
                <tr class="bg-slate-50 border-b border-slate-200 text-[11px] font-bold uppercase tracking-wider text-slate-500">
                  <th class="py-3 px-4 w-10 text-center">No</th>
                  <th class="py-3 px-4">Deskripsi Item / Jasa</th>
                  <th class="py-3 px-4 text-center">Qty</th>
                  <th class="py-3 px-4 text-right">Harga Satuan</th>
                  <th class="py-3 px-4 text-right">Total</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-slate-100">
                <tr v-for="(item, idx) in invoice.items" :key="item.id" class="hover:bg-slate-50/50">
                  <td class="py-3.5 px-4 text-center text-xs font-bold text-slate-400">{{ Number(idx) + 1 }}</td>
                  <td class="py-3.5 px-4">
                    <p class="font-bold text-slate-900">{{ item.name }}</p>
                    <p v-if="item.description" class="text-xs text-slate-500 mt-0.5">{{ item.description }}</p>
                  </td>
                  <td class="py-3.5 px-4 text-center font-semibold text-slate-700">{{ formatQty(item.quantity) }}</td>
                  <td class="py-3.5 px-4 text-right text-slate-600">{{ formatIDR(item.unit_price) }}</td>
                  <td class="py-3.5 px-4 text-right font-bold text-slate-900">
                    {{ formatIDR(item.total_price ?? (Number(item.quantity) * Number(item.unit_price) - Number(item.discount || 0))) }}
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

          <!-- Totals Summary -->
          <div class="flex flex-col sm:flex-row justify-between items-start gap-6">
            <!-- Payment Instructions -->
            <div class="flex-1 w-full space-y-3">
              <div v-if="invoice.payment_instructions || invoice.notes" class="p-4 rounded-2xl bg-violet-50/70 border border-violet-100 text-xs text-slate-700 space-y-2">
                <div v-if="invoice.payment_instructions">
                  <p class="font-black text-violet-900 uppercase tracking-wider text-[10px] mb-1">Instruksi Pembayaran</p>
                  <p class="whitespace-pre-line leading-relaxed">{{ invoice.payment_instructions }}</p>
                </div>
                <div v-if="invoice.notes" class="pt-1 border-t border-violet-100">
                  <p class="font-black text-slate-700 uppercase tracking-wider text-[10px] mb-0.5">Catatan</p>
                  <p class="whitespace-pre-line text-slate-600">{{ invoice.notes }}</p>
                </div>
              </div>
            </div>

            <!-- Totals Box -->
            <div class="w-full sm:w-72 bg-slate-50 rounded-2xl p-5 border border-slate-100 space-y-2.5 text-sm">
              <div class="flex justify-between text-xs text-slate-500">
                <span>Subtotal</span>
                <span class="font-semibold text-slate-800">{{ formatIDR(invoice.subtotal ?? grandTotal) }}</span>
              </div>
              <div v-if="Number(invoice.discount) > 0" class="flex justify-between text-xs text-slate-500">
                <span>Diskon</span>
                <span class="font-semibold text-emerald-600">-{{ formatIDR(invoice.discount) }}</span>
              </div>
              <div v-if="Number(invoice.tax) > 0" class="flex justify-between text-xs text-slate-500">
                <span>Pajak / PPN</span>
                <span class="font-semibold text-slate-800">{{ formatIDR(invoice.tax) }}</span>
              </div>
              <div class="flex justify-between pt-2 border-t border-slate-200 font-bold text-slate-900">
                <span>Total Tagihan</span>
                <span>{{ formatIDR(grandTotal) }}</span>
              </div>
              <div class="flex justify-between text-xs text-slate-500">
                <span>Sudah Dibayar</span>
                <span class="font-bold text-emerald-600">{{ formatIDR(totalPaid) }}</span>
              </div>
              <div class="flex justify-between pt-2.5 border-t-2 border-violet-200 text-base font-black text-violet-800">
                <span>Sisa Tagihan</span>
                <span>{{ formatIDR(balanceDue) }}</span>
              </div>
            </div>
          </div>

          <!-- Installments Schedule (only if > 1 or explicitly named) -->
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
          <div class="pt-6 border-t border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-2 text-[11px] text-slate-400">
            <p>Terima kasih atas kepercayaan Anda berbisnis bersama <strong class="text-slate-600">{{ invoice.tenant?.name }}</strong>.</p>
            <p>Diterbitkan resmi via <span class="font-bold text-violet-700">InvoiceRapi</span></p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
