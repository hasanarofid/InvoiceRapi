<script setup lang="ts">
import { ref, computed } from 'vue';
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import WorkspaceLayout from '@/Layouts/WorkspaceLayout.vue';
import { swalConfirm } from '@/utils/swal';
import { FileText, Plus, Search, Eye, Crown, Lock, Trash2 } from 'lucide-vue-next';

interface Invoice {
  id: number;
  invoice_number: string;
  invoice_date: string;
  due_date: string;
  grand_total: number | string;
  paid_amount: number | string;
  status: string;
  client?: { name: string; company_name?: string | null };
  project?: { name: string } | null;
}

const props = defineProps<{
  invoices: {
    data: Invoice[];
    links: Array<{ url: string | null; label: string; active: boolean }>;
  };
  filters: {
    search?: string;
    status?: string;
  };
  metrics: {
    total_count: number;
    unpaid_amount: number | string;
    overdue_count: number;
  };
}>();

const page = usePage();
const planUsage = computed(() => (page.props.planUsage as Record<string, any> | null) ?? null);
const invoiceLimitReached = computed(() => Boolean(planUsage.value?.invoice_limit_reached));

const search = ref(props.filters.search || '');
const statusFilter = ref(props.filters.status || '');

const formatIDR = (val: number | string) =>
  new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(Number(val || 0));

const applyFilter = (newStatus?: string) => {
  if (typeof newStatus === 'string') {
    statusFilter.value = newStatus;
  }
  router.get(route('invoices.index'), {
    search: search.value || undefined,
    status: statusFilter.value || undefined,
  }, { preserveState: true, replace: true });
};

const deleteInvoice = async (inv: Invoice) => {
  const ok = await swalConfirm('Hapus Invoice?', `Invoice ${inv.invoice_number} akan diarsipkan (soft delete).`, 'Ya, Hapus');
  if (!ok) return;
  router.delete(route('invoices.destroy', inv.id));
};

const statusBadge = (status: string) => {
  const map: Record<string, { label: string; cls: string }> = {
    paid: { label: 'Lunas', cls: 'bg-emerald-50 text-emerald-700' },
    partial: { label: 'Sebagian', cls: 'bg-amber-50 text-amber-700' },
    overdue: { label: 'Terlambat', cls: 'bg-red-50 text-red-700' },
    sent: { label: 'Terkirim', cls: 'bg-blue-50 text-blue-700' },
    draft: { label: 'Draft', cls: 'bg-slate-100 text-slate-600' },
    cancelled: { label: 'Dibatalkan', cls: 'bg-slate-100 text-slate-500' },
  };
  return map[status] || { label: status, cls: 'bg-slate-100 text-slate-600' };
};
</script>

<template>
  <Head title="Invoice — InvoiceRapi" />

  <WorkspaceLayout title="Invoice">
    <div class="space-y-6">
      <!-- Header -->
      <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
          <h1 class="text-2xl font-black text-slate-900 tracking-tight">Daftar Invoice</h1>
          <p class="text-sm text-slate-500 mt-0.5">
            Pantau tagihan, termin DP, dan status pelunasan klien Anda
            <span v-if="planUsage?.max_invoices_per_month" class="ml-1 font-semibold text-violet-700">
              ({{ planUsage.invoices_this_month }}/{{ planUsage.max_invoices_per_month }} bulan ini)
            </span>
          </p>
        </div>

        <Link
          v-if="!invoiceLimitReached"
          :href="route('invoices.create')"
          class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-violet-600 hover:bg-violet-700 text-white text-sm font-bold shadow-sm transition"
        >
          <Plus class="w-4 h-4" />
          Buat Invoice
        </Link>
        <Link
          v-else
          :href="route('billing.index')"
          class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-amber-500 hover:bg-amber-600 text-white text-sm font-bold shadow-sm transition"
        >
          <Crown class="w-4 h-4" />
          Kuota Invoice Habis — Upgrade Paket
        </Link>
      </div>

      <!-- Limit Alert -->
      <div v-if="invoiceLimitReached" class="p-4 rounded-2xl bg-amber-50 border border-amber-200 flex items-center justify-between gap-4">
        <div class="flex items-center gap-3">
          <Lock class="w-5 h-5 text-amber-600 shrink-0" />
          <p class="text-sm text-amber-900">
            Kuota <strong>{{ planUsage?.max_invoices_per_month }} invoice/bulan</strong> pada paket {{ planUsage?.plan_name }} sudah penuh. Upgrade ke Pro untuk hingga 100 invoice/bulan.
          </p>
        </div>
        <Link :href="route('billing.index')" class="text-xs font-bold text-amber-800 underline shrink-0">Upgrade Sekarang</Link>
      </div>

      <!-- Metrics -->
      <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white rounded-2xl border border-slate-100 p-4">
          <p class="text-xs font-semibold text-slate-500">Total Invoice</p>
          <p class="text-2xl font-black text-slate-900 mt-1">{{ metrics.total_count }}</p>
        </div>
        <div class="bg-white rounded-2xl border border-slate-100 p-4">
          <p class="text-xs font-semibold text-slate-500">Total Piutang Aktif</p>
          <p class="text-2xl font-black text-amber-600 mt-1">{{ formatIDR(metrics.unpaid_amount) }}</p>
        </div>
        <div class="bg-white rounded-2xl border border-slate-100 p-4">
          <p class="text-xs font-semibold text-slate-500">Invoice Terlambat</p>
          <p class="text-2xl font-black text-red-600 mt-1">{{ metrics.overdue_count }}</p>
        </div>
      </div>

      <!-- Filter & Tabs -->
      <div class="bg-white rounded-2xl border border-slate-100 p-4 space-y-3">
        <div class="flex flex-wrap gap-2">
          <button
            v-for="tab in [
              { val: '', label: 'Semua' },
              { val: 'sent', label: 'Terkirim' },
              { val: 'partial', label: 'Sebagian' },
              { val: 'overdue', label: 'Terlambat' },
              { val: 'paid', label: 'Lunas' },
              { val: 'draft', label: 'Draft' },
            ]"
            :key="tab.val"
            @click="applyFilter(tab.val)"
            class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition"
            :class="statusFilter === tab.val ? 'bg-violet-600 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'"
          >
            {{ tab.label }}
          </button>
        </div>
        <div class="flex gap-2">
          <div class="relative flex-1">
            <Search class="w-4 h-4 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2" />
            <input
              v-model="search"
              @keyup.enter="applyFilter()"
              type="text"
              placeholder="Cari nomor invoice atau nama klien..."
              class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-violet-500"
            />
          </div>
          <button @click="applyFilter()" class="px-4 py-2.5 rounded-xl bg-slate-900 text-white text-sm font-bold hover:bg-slate-800 transition">
            Cari
          </button>
        </div>
      </div>

      <!-- Table -->
      <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden shadow-sm">
        <div v-if="invoices.data.length === 0" class="p-12 text-center">
          <FileText class="w-10 h-10 text-slate-300 mx-auto mb-3" />
          <p class="text-sm font-bold text-slate-700">Belum ada invoice</p>
          <p class="text-xs text-slate-500 mt-1">Klik "Buat Invoice" untuk membuat tagihan pertama Anda.</p>
        </div>

        <div v-else class="overflow-x-auto">
          <table class="w-full text-left border-collapse">
            <thead>
              <tr class="border-b border-slate-100 bg-slate-50/70 text-[11px] font-bold uppercase tracking-wider text-slate-500">
                <th class="px-5 py-3.5">No. Invoice</th>
                <th class="px-5 py-3.5">Klien &amp; Proyek</th>
                <th class="px-5 py-3.5">Jatuh Tempo</th>
                <th class="px-5 py-3.5">Total / Terbayar</th>
                <th class="px-5 py-3.5">Status</th>
                <th class="px-5 py-3.5 text-right">Aksi</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-sm">
              <tr v-for="inv in invoices.data" :key="inv.id" class="hover:bg-slate-50/60 transition">
                <td class="px-5 py-4 font-bold text-violet-700">
                  <Link :href="route('invoices.show', inv.id)" class="hover:underline">{{ inv.invoice_number }}</Link>
                </td>
                <td class="px-5 py-4">
                  <p class="font-bold text-slate-900">{{ inv.client?.name || '-' }}</p>
                  <p v-if="inv.project" class="text-xs text-slate-500">{{ inv.project.name }}</p>
                </td>
                <td class="px-5 py-4 text-xs text-slate-600">{{ inv.due_date }}</td>
                <td class="px-5 py-4">
                  <p class="font-bold text-slate-900">{{ formatIDR(inv.grand_total) }}</p>
                  <p class="text-xs text-emerald-600">Dibayar: {{ formatIDR(inv.paid_amount) }}</p>
                </td>
                <td class="px-5 py-4">
                  <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-bold" :class="statusBadge(inv.status).cls">
                    {{ statusBadge(inv.status).label }}
                  </span>
                </td>
                <td class="px-5 py-4 text-right space-x-2">
                  <Link :href="route('invoices.show', inv.id)" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-violet-50 text-violet-700 hover:bg-violet-100 text-xs font-bold transition">
                    <Eye class="w-3.5 h-3.5" /> Detail
                  </Link>
                  <button
                    @click="deleteInvoice(inv)"
                    class="p-2 rounded-lg text-slate-400 hover:text-red-600 hover:bg-red-50 transition align-middle"
                    title="Hapus Invoice"
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
  </WorkspaceLayout>
</template>

