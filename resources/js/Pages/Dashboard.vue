<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import WorkspaceLayout from '@/Layouts/WorkspaceLayout.vue';
import {
  FileText,
  CheckCircle2,
  Clock,
  AlertCircle,
  Calendar,
  TrendingUp,
  ArrowRight,
  PlusCircle
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

// Format Rupiah
const formatRupiah = (val: number) => {
  return new Intl.NumberFormat('id-ID', {
    style: 'currency',
    currency: 'IDR',
    maximumFractionDigits: 0,
  }).format(val);
};

// Fallback values matching the mockup if fresh empty workspace
const displayStats = {
  total_invoiced: props.tenant.has_real_data ? props.stats.total_invoiced : 125000000,
  total_paid: props.tenant.has_real_data ? props.stats.total_paid : 78000000,
  total_outstanding: props.tenant.has_real_data ? props.stats.total_outstanding : 47000000,
  total_overdue: props.tenant.has_real_data ? props.stats.total_overdue : 12000000,
};

const displayStatus = {
  total: props.tenant.has_real_data && props.charts.status_breakdown.total > 0 ? props.charts.status_breakdown.total : 32,
  paid: props.tenant.has_real_data && props.charts.status_breakdown.total > 0 ? props.charts.status_breakdown.paid : 18,
  partial: props.tenant.has_real_data && props.charts.status_breakdown.total > 0 ? props.charts.status_breakdown.partial : 6,
  unpaid: props.tenant.has_real_data && props.charts.status_breakdown.total > 0 ? props.charts.status_breakdown.unpaid : 5,
  draft: props.tenant.has_real_data && props.charts.status_breakdown.total > 0 ? props.charts.status_breakdown.draft : 3,
};

// SVG Line Chart coordinates (normalized to 100 max)
const chartPoints = [
  { x: 30, y: 160, label: 'Agu', val: 'Rp 20jt' },
  { x: 95, y: 140, label: 'Sep', val: 'Rp 38jt' },
  { x: 160, y: 110, label: 'Okt', val: 'Rp 58jt' },
  { x: 225, y: 105, label: 'Nov', val: 'Rp 60jt' },
  { x: 290, y: 95, label: 'Des', val: 'Rp 68jt' },
  { x: 355, y: 50, label: 'Jan', val: 'Rp 78jt' },
];

const pathD = `M ${chartPoints[0].x} ${chartPoints[0].y} ` +
  chartPoints.slice(1).map(p => `L ${p.x} ${p.y}`).join(' ');

const areaD = `${pathD} L ${chartPoints[chartPoints.length - 1].x} 190 L ${chartPoints[0].x} 190 Z`;
</script>

<template>
  <WorkspaceLayout>
    <Head title="Dashboard - Synbarra InvoiceRapi" />

    <div class="space-y-6">
      <!-- Top Title & Date Range Filter -->
      <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
          <h1 class="text-2xl font-black text-slate-900 tracking-tight">Dashboard</h1>
          <p class="text-xs sm:text-sm text-slate-500 mt-0.5">
            Selamat datang kembali, <span class="font-bold text-slate-800">{{ tenant.name }}</span> 👋
          </p>
        </div>

        <div class="flex items-center gap-3">
          <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-xl border border-slate-200 bg-white shadow-xs text-xs font-semibold text-slate-700">
            <span>Jan 2025 – Jan 2026</span>
            <Calendar class="w-3.5 h-3.5 text-slate-400" />
          </div>

          <Link
            :href="route('invoices.create')"
            class="inline-flex items-center gap-1.5 px-4 py-1.5 rounded-xl text-xs font-bold text-white bg-violet-700 hover:bg-violet-800 shadow-sm transition transform hover:-translate-y-0.5"
          >
            <PlusCircle class="w-3.5 h-3.5" />
            <span>Buat Invoice</span>
          </Link>
        </div>
      </div>

      <!-- 4 Stat Cards Sesuai Mockup -->
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- 1. Total Tagihan -->
        <div class="p-5 rounded-2xl bg-white border border-slate-200/80 shadow-xs flex items-center justify-between">
          <div class="space-y-1">
            <p class="text-xs font-semibold text-slate-500">Total Tagihan</p>
            <p class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">
              {{ formatRupiah(displayStats.total_invoiced) }}
            </p>
          </div>
          <div class="w-12 h-12 rounded-xl bg-violet-50 text-violet-700 flex items-center justify-center shrink-0">
            <FileText class="w-6 h-6" />
          </div>
        </div>

        <!-- 2. Sudah Dibayar -->
        <div class="p-5 rounded-2xl bg-white border border-slate-200/80 shadow-xs flex items-center justify-between">
          <div class="space-y-1">
            <p class="text-xs font-semibold text-slate-500">Sudah Dibayar</p>
            <p class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">
              {{ formatRupiah(displayStats.total_paid) }}
            </p>
          </div>
          <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
            <CheckCircle2 class="w-6 h-6" />
          </div>
        </div>

        <!-- 3. Piutang (Outstanding) -->
        <div class="p-5 rounded-2xl bg-white border border-slate-200/80 shadow-xs flex items-center justify-between">
          <div class="space-y-1">
            <p class="text-xs font-semibold text-slate-500">Piutang</p>
            <p class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">
              {{ formatRupiah(displayStats.total_outstanding) }}
            </p>
          </div>
          <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center shrink-0">
            <Clock class="w-6 h-6" />
          </div>
        </div>

        <!-- 4. Terlambat (Overdue) -->
        <div class="p-5 rounded-2xl bg-white border border-slate-200/80 shadow-xs flex items-center justify-between">
          <div class="space-y-1">
            <p class="text-xs font-semibold text-slate-500">Terlambat</p>
            <p class="text-xl sm:text-2xl font-black text-red-600 tracking-tight">
              {{ formatRupiah(displayStats.total_overdue) }}
            </p>
          </div>
          <div class="w-12 h-12 rounded-xl bg-red-50 text-red-600 flex items-center justify-center shrink-0">
            <AlertCircle class="w-6 h-6" />
          </div>
        </div>
      </div>

      <!-- 2 Visual Charts Grid Sesuai Mockup -->
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Chart Kiri: Pendapatan Bulanan (2 Kolom) -->
        <div class="lg:col-span-2 bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs space-y-4">
          <div class="flex items-start justify-between">
            <div>
              <p class="text-xs font-semibold text-slate-500">Pendapatan Bulanan</p>
              <h3 class="text-2xl font-black text-slate-900 tracking-tight mt-0.5">
                {{ formatRupiah(displayStats.total_paid) }}
              </h3>
            </div>
            <span class="inline-flex items-center gap-1 text-xs font-bold text-emerald-600 bg-emerald-50 px-2.5 py-1 rounded-full border border-emerald-100">
              <TrendingUp class="w-3.5 h-3.5" />
              <span>+24% dari bulan lalu</span>
            </span>
          </div>

          <!-- SVG Responsive Line Chart -->
          <div class="w-full h-56 pt-2">
            <svg class="w-full h-full" viewBox="0 0 390 220" preserveAspectRatio="none">
              <defs>
                <linearGradient id="violetGradient" x1="0" y1="0" x2="0" y2="1">
                  <stop offset="0%" stop-color="#7C3AED" stop-opacity="0.3" />
                  <stop offset="100%" stop-color="#7C3AED" stop-opacity="0.0" />
                </linearGradient>
              </defs>

              <!-- Grid Horizontal Lines -->
              <line x1="20" y1="50" x2="370" y2="50" stroke="#F1F5F9" stroke-width="1" />
              <line x1="20" y1="95" x2="370" y2="95" stroke="#F1F5F9" stroke-width="1" />
              <line x1="20" y1="140" x2="370" y2="140" stroke="#F1F5F9" stroke-width="1" />
              <line x1="20" y1="190" x2="370" y2="190" stroke="#E2E8F0" stroke-width="1" />

              <!-- Area Fill -->
              <path :d="areaD" fill="url(#violetGradient)" />

              <!-- Line Stroke -->
              <path :d="pathD" fill="none" stroke="#7C3AED" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" />

              <!-- Dots -->
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

              <!-- Sumbu X Labels -->
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

        <!-- Chart Kanan: Donut Chart Status Invoice (1 Kolom) -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs flex flex-col justify-between space-y-4">
          <div>
            <h3 class="text-sm font-bold text-slate-900">Status Invoice</h3>
            <p class="text-xs text-slate-400 mt-0.5">Komposisi penagihan aktif</p>
          </div>

          <!-- Donut Graphic & Center Metric -->
          <div class="flex items-center justify-center my-2 relative">
            <svg class="w-36 h-36 -rotate-90" viewBox="0 0 100 100">
              <!-- Background Circle -->
              <circle cx="50" cy="50" r="38" fill="none" stroke="#F1F5F9" stroke-width="14" />
              <!-- Green (Lunas: 56%) -->
              <circle cx="50" cy="50" r="38" fill="none" stroke="#10B981" stroke-width="14"
                stroke-dasharray="133 238" stroke-dashoffset="0" />
              <!-- Yellow (Sebagian: 19%) -->
              <circle cx="50" cy="50" r="38" fill="none" stroke="#F59E0B" stroke-width="14"
                stroke-dasharray="45 238" stroke-dashoffset="-133" />
              <!-- Red (Belum Bayar: 16%) -->
              <circle cx="50" cy="50" r="38" fill="none" stroke="#EF4444" stroke-width="14"
                stroke-dasharray="38 238" stroke-dashoffset="-178" />
              <!-- Gray (Draft: 9%) -->
              <circle cx="50" cy="50" r="38" fill="none" stroke="#CBD5E1" stroke-width="14"
                stroke-dasharray="22 238" stroke-dashoffset="-216" />
            </svg>

            <!-- Center Metric -->
            <div class="absolute inset-0 flex flex-col items-center justify-center text-center">
              <span class="text-2xl font-black text-slate-900">{{ displayStatus.total }}</span>
              <span class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider">Invoice</span>
            </div>
          </div>

          <!-- Legend List -->
          <div class="space-y-2 text-xs pt-2 border-t border-slate-100">
            <div class="flex items-center justify-between">
              <div class="flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                <span class="text-slate-600">Lunas</span>
              </div>
              <span class="font-bold text-slate-800">{{ displayStatus.paid }} (56%)</span>
            </div>

            <div class="flex items-center justify-between">
              <div class="flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span>
                <span class="text-slate-600">Sebagian</span>
              </div>
              <span class="font-bold text-slate-800">{{ displayStatus.partial }} (19%)</span>
            </div>

            <div class="flex items-center justify-between">
              <div class="flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-red-500"></span>
                <span class="text-slate-600">Belum Bayar</span>
              </div>
              <span class="font-bold text-slate-800">{{ displayStatus.unpaid }} (16%)</span>
            </div>

            <div class="flex items-center justify-between">
              <div class="flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-slate-400"></span>
                <span class="text-slate-600">Draft</span>
              </div>
              <span class="font-bold text-slate-800">{{ displayStatus.draft }} (9%)</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </WorkspaceLayout>
</template>
