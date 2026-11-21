<script setup lang="ts">
import { computed } from 'vue';
import { Head, Link, usePage } from '@inertiajs/vue3';
import WorkspaceLayout from '@/Layouts/WorkspaceLayout.vue';
import {
  FileText,
  CheckCircle2,
  Clock,
  AlertCircle,
  TrendingUp,
  PlusCircle,
  Crown,
  Lock,
  Sparkles,
  ArrowRight,
  Users
} from 'lucide-vue-next';

interface Props {
  tenant: {
    name: string;
    business_type: string;
    plan: string;
    expires_at: string;
    has_real_data: boolean;
  };
  stats: {
    total_invoiced: number;
    total_paid: number;
    total_outstanding: number;
    total_overdue: number;
  };
  charts: {
    monthly_revenue: { month: string; amount: number }[];
    status_breakdown: {
      total: number;
      paid: number;
      partial: number;
      unpaid: number;
      draft: number;
    };
  };
}

const props = defineProps<Props>();
const page = usePage();
const planUsage = computed(() => (page.props.planUsage as Record<string, any> | null));

const formatRupiah = (val: number) =>
  new Intl.NumberFormat('id-ID', {
    style: 'currency',
    currency: 'IDR',
    maximumFractionDigits: 0,
  }).format(val);

// Dynamic status breakdown percentages
const totalInvCount = computed(() => props.charts.status_breakdown.total || 0);
const pct = (count: number) => (totalInvCount.value > 0 ? Math.round((count / totalInvCount.value) * 100) : 0);

// SVG Line Chart coordinates dynamically scaled from monthly_revenue
const chartPoints = computed(() => {
  const data = props.charts.monthly_revenue || [];
  const maxVal = Math.max(...data.map(d => d.amount), 1);
  const xs = [30, 95, 160, 225, 290, 355];

  return data.map((item, i) => {
    const ratio = item.amount > 0 ? item.amount / maxVal : 0;
    const y = Math.round(180 - ratio * 130);
    return {
      x: xs[i] ?? 30 + i * 65,
      y,
      label: item.month,
      amount: item.amount,
    };
  });
});

const pathD = computed(() => {
  if (chartPoints.value.length === 0) return '';
  return `M ${chartPoints.value[0].x} ${chartPoints.value[0].y} ` +
    chartPoints.value.slice(1).map(p => `L ${p.x} ${p.y}`).join(' ');
});

const areaD = computed(() => {
  if (chartPoints.value.length === 0) return '';
  const last = chartPoints.value[chartPoints.value.length - 1];
  const first = chartPoints.value[0];
  return `${pathD.value} L ${last.x} 190 L ${first.x} 190 Z`;
});

// Dynamic feature list reflecting real-time plan entitlements
const dynamicFeatures = computed(() => {
  const f = planUsage.value?.features || {};
  return [
    { name: 'Unduh Invoice PDF', active: true, tier: 'Semua Paket' },
    { name: 'Atur DP & Multi-Termin', active: Boolean(f.dp_termin), tier: 'Pro / Bisnis' },
    { name: 'Pengingat Jatuh Tempo', active: Boolean(f.auto_reminder), tier: 'Pro / Bisnis' },
    { name: 'Laporan Piutang Lengkap', active: Boolean(f.receivables_report), tier: 'Pro / Bisnis' },
    { name: 'Ekspor CSV & Audit Log', active: Boolean(f.csv_export_audit), tier: 'Bisnis' },
  ];
});
</script>

<template>
  <WorkspaceLayout>
    <Head title="Dashboard Member — Synbarra InvoiceRapi" />

    <div class="space-y-6">
      <!-- Top Title & Quick Actions -->
      <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
          <h1 class="text-2xl font-black text-slate-900 tracking-tight">Dashboard Workspace</h1>
          <p class="text-xs sm:text-sm text-slate-500 mt-0.5">
            Selamat datang kembali, <span class="font-bold text-slate-800">{{ tenant.name }}</span>
          </p>
        </div>

        <div class="flex flex-wrap items-center gap-2.5">
          <Link
            :href="route('clients.index')"
            class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl border border-violet-200 bg-white hover:bg-violet-50 text-xs font-bold text-violet-700 transition"
          >
            <Users class="w-3.5 h-3.5" />
            <span>Kelola Klien</span>
          </Link>

          <Link
            v-if="!planUsage?.is_subscription_locked"
            :href="route('invoices.create')"
            class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-bold text-white bg-violet-700 hover:bg-violet-800 shadow-invoicerapi-sm transition"
          >
            <PlusCircle class="w-3.5 h-3.5" />
            <span>Buat Invoice</span>
          </Link>
          <Link
            v-else
            :href="route('billing.index')"
            class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-bold text-white bg-amber-500 hover:bg-amber-600 shadow-invoicerapi-sm transition"
          >
            <Lock class="w-3.5 h-3.5" />
            <span>Aktifkan Paket</span>
          </Link>
        </div>
      </div>

      <!-- Dynamic Plan & Quota Status Banner -->
      <div
        v-if="planUsage"
        :class="[
          'rounded-2xl border p-5 flex flex-col lg:flex-row lg:items-center justify-between gap-4 shadow-invoicerapi-sm',
          planUsage.any_limit_reached || planUsage.is_trial_expired
            ? 'bg-amber-50/70 border-amber-200'
            : 'bg-white border-violet-100'
        ]"
      >
        <div class="flex items-start gap-3.5">
          <div
            :class="[
              'w-11 h-11 rounded-xl flex items-center justify-center shrink-0',
              planUsage.plan === 'gratis' ? 'bg-amber-100 text-amber-700' : 'bg-violet-100 text-violet-700'
            ]"
          >
            <Crown class="w-5 h-5" />
          </div>
          <div class="space-y-1">
            <div class="flex flex-wrap items-center gap-2">
              <span class="text-sm font-extrabold text-slate-900">
                Paket {{ planUsage.plan_name }}
                <template v-if="planUsage.is_trial">(Uji Coba 14 Hari)</template>
              </span>
              <span
                :class="[
                  'px-2.5 py-0.5 rounded-full text-[11px] font-bold',
                  planUsage.is_trial_expired
                    ? 'bg-red-100 text-red-700'
                    : (planUsage.plan === 'gratis' ? 'bg-slate-100 text-slate-700' : 'bg-emerald-100 text-emerald-700')
                ]"
              >
                <template v-if="planUsage.days_remaining !== null && planUsage.plan !== 'gratis'">
                  Aktif hingga {{ planUsage.expires_at_formatted }} (Sisa {{ planUsage.days_remaining }} hari)
                </template>
                <template v-else-if="planUsage.is_trial_expired">
                  Trial {{ planUsage.expires_at_formatted }} &bull; Kuota Paket Gratis
                </template>
                <template v-else>
                  Gratis Selamanya
                </template>
              </span>
            </div>

            <p class="text-xs text-slate-600">
              Sisa kuota bulan ini:
              <strong class="text-slate-900">
                {{ planUsage.invoices_remaining ?? 0 }} dari {{ planUsage.max_invoices_per_month }} invoice
              </strong>
              &bull;
              Klien terdaftar:
              <strong class="text-slate-900">
                <template v-if="planUsage.max_clients">
                  {{ planUsage.clients_count }}/{{ planUsage.max_clients }} (Sisa {{ planUsage.clients_remaining }})
                </template>
                <template v-else>
                  {{ planUsage.clients_count }} (Unlimited)
                </template>
              </strong>
            </p>
          </div>
        </div>

        <Link
          :href="route('billing.index')"
          class="inline-flex items-center justify-center gap-1.5 px-4 py-2.5 rounded-xl bg-violet-700 hover:bg-violet-800 text-white text-xs font-bold shadow-invoicerapi-sm transition shrink-0"
        >
          <span>{{ planUsage.plan === 'gratis' || planUsage.is_trial ? 'Upgrade Paket Sekarang' : 'Kelola Langganan' }}</span>
          <ArrowRight class="w-3.5 h-3.5" />
        </Link>
      </div>

      <!-- 4 Stat Cards (Clickable -> Filtered Invoices) -->
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <Link
          :href="route('invoices.index', { status: 'all' })"
          class="group p-5 rounded-2xl bg-white border border-violet-100/80 hover:border-violet-300 hover:shadow-md shadow-invoicerapi-sm flex items-center justify-between transition"
        >
          <div class="space-y-1">
            <p class="text-xs font-semibold text-slate-500">Total Tagihan</p>
            <p class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">
              {{ formatRupiah(stats.total_invoiced) }}
            </p>
            <p class="text-[11px] font-semibold text-violet-700 group-hover:underline">Lihat semua tagihan &rarr;</p>
          </div>
          <div class="w-12 h-12 rounded-xl bg-violet-50 text-violet-700 flex items-center justify-center shrink-0">
            <FileText class="w-6 h-6" />
          </div>
        </Link>

        <Link
          :href="route('invoices.index', { status: 'paid' })"
          class="group p-5 rounded-2xl bg-white border border-violet-100/80 hover:border-emerald-300 hover:shadow-md shadow-invoicerapi-sm flex items-center justify-between transition"
        >
          <div class="space-y-1">
            <p class="text-xs font-semibold text-slate-500">Sudah Dibayar</p>
            <p class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">
              {{ formatRupiah(stats.total_paid) }}
            </p>
            <p class="text-[11px] font-semibold text-emerald-700 group-hover:underline">Lihat invoice lunas &rarr;</p>
          </div>
          <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
            <CheckCircle2 class="w-6 h-6" />
          </div>
        </Link>

        <Link
          :href="route('invoices.index', { status: 'unpaid' })"
          class="group p-5 rounded-2xl bg-white border border-violet-100/80 hover:border-amber-300 hover:shadow-md shadow-invoicerapi-sm flex items-center justify-between transition"
        >
          <div class="space-y-1">
            <p class="text-xs font-semibold text-slate-500">Piutang</p>
            <p class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">
              {{ formatRupiah(stats.total_outstanding) }}
            </p>
            <p class="text-[11px] font-semibold text-amber-700 group-hover:underline">Lihat tagihan belum lunas &rarr;</p>
          </div>
          <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center shrink-0">
            <Clock class="w-6 h-6" />
          </div>
        </Link>

        <Link
          :href="route('invoices.index', { status: 'overdue' })"
          class="group p-5 rounded-2xl bg-white border border-violet-100/80 hover:border-red-300 hover:shadow-md shadow-invoicerapi-sm flex items-center justify-between transition"
        >
          <div class="space-y-1">
            <p class="text-xs font-semibold text-slate-500">Terlambat</p>
            <p class="text-xl sm:text-2xl font-black text-red-600 tracking-tight">
              {{ formatRupiah(stats.total_overdue) }}
            </p>
            <p class="text-[11px] font-semibold text-red-600 group-hover:underline">Lihat tagihan terlambat &rarr;</p>
          </div>
          <div class="w-12 h-12 rounded-xl bg-red-50 text-red-600 flex items-center justify-center shrink-0">
            <AlertCircle class="w-6 h-6" />
          </div>
        </Link>
      </div>

      <!-- Charts & Dynamic Feature Matrix -->
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left: Monthly Revenue -->
        <div class="lg:col-span-2 bg-white p-6 rounded-2xl border border-violet-100/80 shadow-invoicerapi-sm space-y-4">
          <div class="flex items-start justify-between">
            <div>
              <p class="text-xs font-semibold text-slate-500">Pendapatan Bulanan (6 Bulan Terakhir)</p>
              <h3 class="text-2xl font-black text-slate-900 tracking-tight mt-0.5">
                {{ formatRupiah(stats.total_paid) }}
              </h3>
            </div>
            <span class="inline-flex items-center gap-1 text-xs font-bold text-violet-700 bg-violet-50 px-2.5 py-1 rounded-full border border-violet-100">
              <TrendingUp class="w-3.5 h-3.5" />
              <span>Real-Time</span>
            </span>
          </div>

          <div class="w-full h-56 pt-2">
            <svg class="w-full h-full" viewBox="0 0 390 220" preserveAspectRatio="none">
              <defs>
                <linearGradient id="violetGradient" x1="0" y1="0" x2="0" y2="1">
                  <stop offset="0%" stop-color="#7C3AED" stop-opacity="0.3" />
                  <stop offset="100%" stop-color="#7C3AED" stop-opacity="0.0" />
                </linearGradient>
              </defs>
              <line x1="20" y1="50" x2="370" y2="50" stroke="#F1F5F9" stroke-width="1" />
              <line x1="20" y1="95" x2="370" y2="95" stroke="#F1F5F9" stroke-width="1" />
              <line x1="20" y1="140" x2="370" y2="140" stroke="#F1F5F9" stroke-width="1" />
              <line x1="20" y1="190" x2="370" y2="190" stroke="#E2E8F0" stroke-width="1" />
              <path v-if="areaD" :d="areaD" fill="url(#violetGradient)" />
              <path v-if="pathD" :d="pathD" fill="none" stroke="#7C3AED" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" />
              <circle
                v-for="(p, idx) in chartPoints"
                :key="idx"
                :cx="p.x"
                :cy="p.y"
                r="4.5"
                fill="#7C3AED"
                stroke="#FFFFFF"
                stroke-width="2"
              />
              <text
                v-for="(p, idx) in chartPoints"
                :key="'t-' + idx"
                :x="p.x"
                y="212"
                text-anchor="middle"
                font-size="10"
                font-family="sans-serif"
                fill="#94A3B8"
              >
                {{ p.label }}
              </text>
            </svg>
          </div>
        </div>

        <!-- Right: Dynamic Feature Entitlements + Invoice Status -->
        <div class="bg-white p-6 rounded-2xl border border-violet-100/80 shadow-invoicerapi-sm flex flex-col justify-between space-y-5">
          <div>
            <div class="flex items-center justify-between mb-3">
              <h3 class="text-sm font-bold text-slate-900 flex items-center gap-1.5">
                <Sparkles class="w-4 h-4 text-violet-700" />
                <span>Status Fitur Paket Anda</span>
              </h3>
              <span class="text-[11px] font-bold text-violet-700">{{ planUsage?.plan_name || 'Pro' }}</span>
            </div>

            <div class="space-y-2">
              <div
                v-for="feat in dynamicFeatures"
                :key="feat.name"
                class="flex items-center justify-between p-2.5 rounded-xl text-xs"
                :class="feat.active ? 'bg-emerald-50/60 text-slate-800' : 'bg-slate-50 text-slate-400'"
              >
                <div class="flex items-center gap-2">
                  <CheckCircle2 v-if="feat.active" class="w-4 h-4 text-emerald-600 shrink-0" />
                  <Lock v-else class="w-4 h-4 text-slate-400 shrink-0" />
                  <span class="font-semibold">{{ feat.name }}</span>
                </div>
                <span class="text-[10px] font-bold" :class="feat.active ? 'text-emerald-700' : 'text-amber-600'">
                  {{ feat.active ? 'Aktif' : feat.tier }}
                </span>
              </div>
            </div>
          </div>

          <div class="space-y-2 text-xs pt-3 border-t border-slate-100">
            <div class="flex items-center justify-between font-bold text-slate-800 mb-1">
              <span>Komposisi Invoice ({{ totalInvCount }})</span>
            </div>
            <div class="flex items-center justify-between">
              <span class="flex items-center gap-2 text-slate-600"><span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>Lunas</span>
              <span class="font-bold text-slate-800">{{ charts.status_breakdown.paid }} ({{ pct(charts.status_breakdown.paid) }}%)</span>
            </div>
            <div class="flex items-center justify-between">
              <span class="flex items-center gap-2 text-slate-600"><span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span>Sebagian</span>
              <span class="font-bold text-slate-800">{{ charts.status_breakdown.partial }} ({{ pct(charts.status_breakdown.partial) }}%)</span>
            </div>
            <div class="flex items-center justify-between">
              <span class="flex items-center gap-2 text-slate-600"><span class="w-2.5 h-2.5 rounded-full bg-red-500"></span>Belum Bayar</span>
              <span class="font-bold text-slate-800">{{ charts.status_breakdown.unpaid }} ({{ pct(charts.status_breakdown.unpaid) }}%)</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </WorkspaceLayout>
</template>
