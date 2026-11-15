<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { Printer, CheckCircle2, Clock } from 'lucide-vue-next';

const props = defineProps<{
  invoice: any;
}>();

const formatIDR = (val: number | string) =>
  new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(Number(val || 0));

const printPage = () => {
  window.print();
};
</script>

<template>
  <Head :title="`Invoice ${invoice.invoice_number} — ${invoice.tenant?.name || 'InvoiceRapi'}`" />

  <div class="min-h-screen bg-[#FAF8FF] py-10 px-4 sm:px-6 print:bg-white print:py-0">
    <div class="max-w-3xl mx-auto space-y-5">
      <!-- Action Bar -->
      <div class="flex items-center justify-between print:hidden">
        <span class="text-xs font-bold text-violet-700 bg-violet-50 border border-violet-200 px-3 py-1 rounded-full">
          Tautan Invoice Resmi
        </span>
        <button
          @click="printPage"
          class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-violet-600 hover:bg-violet-700 text-white text-xs font-bold shadow-sm transition"
        >
          <Printer class="w-4 h-4" />
          Cetak / Simpan PDF
        </button>
      </div>

      <!-- Invoice Card -->
      <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-8 space-y-8 print:border-0 print:shadow-none print:p-0">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-4 border-b border-slate-100 pb-6">
          <div>
            <p class="text-xl font-black text-slate-900">{{ invoice.tenant?.name || 'Bisnis' }}</p>
            <p v-if="invoice.tenant?.email" class="text-xs text-slate-500 mt-0.5">{{ invoice.tenant.email }}</p>
            <p v-if="invoice.tenant?.phone" class="text-xs text-slate-500">{{ invoice.tenant.phone }}</p>
          </div>
          <div class="sm:text-right">
            <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Invoice</p>
            <p class="text-2xl font-black text-violet-700">{{ invoice.invoice_number }}</p>
            <span
              class="inline-flex items-center gap-1 mt-2 px-3 py-1 rounded-full text-xs font-bold"
              :class="invoice.status === 'paid' ? 'bg-emerald-50 text-emerald-700' : 'bg-amber-50 text-amber-700'"
            >
              <CheckCircle2 v-if="invoice.status === 'paid'" class="w-3.5 h-3.5" />
              <Clock v-else class="w-3.5 h-3.5" />
              {{ invoice.status === 'paid' ? 'Lunas' : 'Menunggu Pembayaran' }}
            </span>
          </div>
        </div>

        <!-- Client & Dates -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 text-sm">
          <div>
            <p class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-1">Ditagihkan Kepada</p>
            <p class="font-bold text-slate-900">{{ invoice.client?.name }}</p>
            <p v-if="invoice.client?.company_name" class="text-slate-600 text-xs">{{ invoice.client.company_name }}</p>
            <p v-if="invoice.client?.email" class="text-slate-500 text-xs mt-0.5">{{ invoice.client.email }}</p>
          </div>
          <div class="sm:text-right space-y-1">
            <p class="text-xs text-slate-500">Tanggal Invoice: <strong class="text-slate-800">{{ invoice.invoice_date }}</strong></p>
            <p class="text-xs text-slate-500">Jatuh Tempo: <strong class="text-slate-800">{{ invoice.due_date }}</strong></p>
            <p v-if="invoice.project" class="text-xs text-slate-500">Proyek: <strong class="text-slate-800">{{ invoice.project.name }}</strong></p>
          </div>
        </div>

        <!-- Items -->
        <div class="overflow-x-auto">
          <table class="w-full text-left text-sm">
            <thead>
              <tr class="border-b border-slate-200 text-xs text-slate-500 uppercase">
                <th class="py-2.5">Item / Jasa</th>
                <th class="py-2.5">Qty</th>
                <th class="py-2.5">Harga</th>
                <th class="py-2.5 text-right">Subtotal</th>
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

        <!-- Totals -->
        <div class="border-t border-slate-100 pt-4 flex flex-col items-end space-y-1.5 text-sm">
          <div class="flex justify-between w-full sm:w-64">
            <span class="text-slate-500">Total Tagihan</span>
            <span class="font-black text-slate-900">{{ formatIDR(invoice.grand_total) }}</span>
          </div>
          <div class="flex justify-between w-full sm:w-64">
            <span class="text-slate-500">Sudah Dibayar</span>
            <span class="font-bold text-emerald-600">{{ formatIDR(invoice.paid_amount) }}</span>
          </div>
          <div class="flex justify-between w-full sm:w-64 pt-2 border-t border-slate-100">
            <span class="font-bold text-slate-800">Sisa Tagihan</span>
            <span class="font-black text-violet-700 text-base">
              {{ formatIDR(Math.max(0, Number(invoice.grand_total) - Number(invoice.paid_amount))) }}
            </span>
          </div>
        </div>

        <!-- Installments -->
        <div v-if="invoice.installments?.length" class="space-y-2">
          <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Jadwal Termin Pembayaran</p>
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
            <div v-for="inst in invoice.installments" :key="inst.id" class="p-3 rounded-xl bg-slate-50 border border-slate-100 flex items-center justify-between text-xs">
              <div>
                <p class="font-bold text-slate-800">{{ inst.name }}</p>
                <p class="text-slate-500">Tempo: {{ inst.due_date }}</p>
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

        <!-- Payment Instructions -->
        <div v-if="invoice.payment_instructions || invoice.notes" class="p-4 rounded-2xl bg-violet-50/60 border border-violet-100 text-xs text-slate-700 space-y-1.5">
          <p v-if="invoice.payment_instructions"><strong>Instruksi Pembayaran:</strong> {{ invoice.payment_instructions }}</p>
          <p v-if="invoice.notes"><strong>Catatan:</strong> {{ invoice.notes }}</p>
        </div>
      </div>
    </div>
  </div>
</template>

