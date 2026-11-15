<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import {
  FileText,
  Layers,
  MessageSquare,
  ShieldCheck,
  ExternalLink,
  ArrowRight,
  Cpu,
  CheckCircle2,
  Users,
  ListTodo,
  Sparkles
} from '@lucide/vue';
import { BRAND } from '@/config/brand';

const props = defineProps({
  tenant: Object,
  stats: {
    type: Object,
    default: () => ({}),
  },
  recent_orders: {
    type: Array,
    default: () => [],
  },
});
</script>

<template>
  <Head title="Admin Control Center — Synbarra InvoiceRapi" />

  <AdminLayout>
    <div class="space-y-6 sm:space-y-8">
      <!-- Header Banner -->
      <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-6 sm:p-8 rounded-2xl border border-violet-100 shadow-invoicerapi-sm">
        <div>
          <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-bold bg-violet-50 text-violet-700 border border-violet-200/70">
            <span class="w-2 h-2 rounded-full bg-violet-600 animate-pulse"></span>
            INVOICERAPI ADMIN PORTAL
          </div>
          <h2 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-slate-900 mt-2.5">
            Control Center &bull; {{ BRAND.name }}
          </h2>
          <p class="text-xs sm:text-sm text-slate-500 mt-1 max-w-2xl">
            {{ BRAND.tagline }} Pusat kendali SaaS untuk memantau tenant uji coba, prospek klien, katalog layanan, dan roadmap sprint.
          </p>
        </div>

        <div class="flex flex-wrap items-center gap-3">
          <Link
            :href="route('admin.trial-tracker.index')"
            class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-violet-700 hover:bg-violet-800 text-white text-xs sm:text-sm font-semibold shadow-invoicerapi-sm transition-all"
          >
            <Users class="w-4 h-4" />
            <span>Trial Tracker</span>
          </Link>
          <a
            href="/"
            target="_blank"
            class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white hover:bg-violet-50 text-violet-700 border border-violet-200 text-xs sm:text-sm font-semibold transition-all"
          >
            <span>Lihat Halaman Depan</span>
            <ExternalLink class="w-4 h-4" />
          </a>
        </div>
      </div>

      <!-- Key Metric Cards Grid -->
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
        <!-- Card 1: Core Modules -->
        <div class="p-6 rounded-2xl bg-white border border-violet-100/80 shadow-invoicerapi-sm hover:border-violet-300 transition duration-200">
          <div class="flex items-center justify-between">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Modul SaaS</span>
            <div class="w-10 h-10 rounded-xl bg-violet-50 text-violet-700 flex items-center justify-center">
              <Layers class="w-5 h-5" />
            </div>
          </div>
          <div class="mt-4">
            <div class="text-2xl sm:text-3xl font-black text-slate-900">
              4 Pilar Aktif
            </div>
            <p class="text-xs text-slate-500 mt-1">
              Invoice, DP &amp; Termin, Reminder, Piutang
            </p>
          </div>
        </div>

        <!-- Card 2: Inquiries / Project Pipeline -->
        <div class="p-6 rounded-2xl bg-white border border-violet-100/80 shadow-invoicerapi-sm hover:border-violet-300 transition duration-200">
          <div class="flex items-center justify-between">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Prospek &amp; Order</span>
            <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
              <MessageSquare class="w-5 h-5" />
            </div>
          </div>
          <div class="mt-4">
            <div class="text-2xl sm:text-3xl font-black text-slate-900">
              {{ stats.orders_count ?? 0 }} Masuk
            </div>
            <p class="text-xs text-slate-500 mt-1">
              Permintaan konsultasi &amp; aktivasi
            </p>
          </div>
        </div>

        <!-- Card 3: Architecture Status -->
        <div class="p-6 rounded-2xl bg-white border border-violet-100/80 shadow-invoicerapi-sm hover:border-violet-300 transition duration-200">
          <div class="flex items-center justify-between">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Uptime Layanan</span>
            <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
              <ShieldCheck class="w-5 h-5" />
            </div>
          </div>
          <div class="mt-4">
            <div class="text-2xl sm:text-3xl font-black text-emerald-600">
              99.98%
            </div>
            <p class="text-xs text-slate-500 mt-1">
              Multi-tenant isolation aktif
            </p>
          </div>
        </div>

        <!-- Card 4: Official Domain & HTTPS -->
        <div class="p-6 rounded-2xl bg-white border border-violet-100/80 shadow-invoicerapi-sm hover:border-violet-300 transition duration-200">
          <div class="flex items-center justify-between">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Domain Resmi</span>
            <div class="w-10 h-10 rounded-xl bg-violet-700 text-white flex items-center justify-center">
              <Cpu class="w-5 h-5" />
            </div>
          </div>
          <div class="mt-4">
            <div class="text-base sm:text-lg font-bold font-mono text-slate-900 truncate">
              {{ BRAND.domain }}
            </div>
            <p class="text-xs text-emerald-600 font-semibold mt-1 flex items-center gap-1">
              <CheckCircle2 class="w-3.5 h-3.5" /> HTTPS / SSL Aktif
            </p>
          </div>
        </div>
      </div>

      <!-- Operational Sections -->
      <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 sm:gap-8">
        <!-- Left: Core Features & Quick Modules -->
        <div class="lg:col-span-7 bg-white p-6 sm:p-7 rounded-2xl border border-violet-100/80 shadow-invoicerapi-sm">
          <div class="flex items-center justify-between pb-4 border-b border-slate-100">
            <h3 class="text-base font-bold text-slate-900 flex items-center gap-2">
              <Sparkles class="w-4 h-4 text-violet-700" />
              Pilar Fitur Utama InvoiceRapi
            </h3>
            <Link :href="route('admin.roadmap.index')" class="text-xs font-semibold text-violet-700 hover:underline flex items-center gap-1">
              <span>Lihat Roadmap</span>
              <ArrowRight class="w-3.5 h-3.5" />
            </Link>
          </div>

          <div class="mt-4 divide-y divide-slate-100">
            <div
              v-for="feat in BRAND.coreFeatures"
              :key="feat.title"
              class="py-3.5 flex items-center justify-between hover:bg-violet-50/40 px-2.5 rounded-xl transition"
            >
              <div>
                <h4 class="text-sm font-bold text-slate-900">
                  {{ feat.title }}
                </h4>
                <p class="text-xs text-slate-500 mt-0.5 max-w-md line-clamp-1">
                  {{ feat.description }}
                </p>
              </div>
              <span class="px-2.5 py-1 text-[11px] font-semibold rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200/70 shrink-0">
                Aktif
              </span>
            </div>
          </div>

          <!-- Quick Navigation Grid -->
          <div class="mt-6 pt-5 border-t border-slate-100 grid grid-cols-2 sm:grid-cols-3 gap-3">
            <Link
              :href="route('admin.leads.index')"
              class="p-3 rounded-xl bg-[#FAF8FF] hover:bg-violet-50 border border-violet-100 text-xs font-bold text-slate-800 hover:text-violet-700 flex items-center justify-between transition"
            >
              <span>Leads &amp; Prospek</span>
              <ArrowRight class="w-3.5 h-3.5 text-violet-600" />
            </Link>
            <Link
              :href="route('admin.roadmap.index')"
              class="p-3 rounded-xl bg-[#FAF8FF] hover:bg-violet-50 border border-violet-100 text-xs font-bold text-slate-800 hover:text-violet-700 flex items-center justify-between transition"
            >
              <span>Roadmap Sprint</span>
              <ListTodo class="w-3.5 h-3.5 text-violet-600" />
            </Link>
            <Link
              :href="route('admin.settings.index')"
              class="p-3 rounded-xl bg-[#FAF8FF] hover:bg-violet-50 border border-violet-100 text-xs font-bold text-slate-800 hover:text-violet-700 flex items-center justify-between transition col-span-2 sm:col-span-1"
            >
              <span>Pengaturan</span>
              <FileText class="w-3.5 h-3.5 text-violet-600" />
            </Link>
          </div>
        </div>

        <!-- Right: Brand Design System Tokens -->
        <div class="lg:col-span-5 bg-white p-6 sm:p-7 rounded-2xl border border-violet-100/80 shadow-invoicerapi-sm flex flex-col justify-between">
          <div>
            <div class="flex items-center gap-2 pb-4 border-b border-slate-100">
              <h3 class="text-base font-bold text-slate-900">
                Identitas &amp; Token Desain
              </h3>
            </div>

            <div class="mt-4 space-y-3 text-xs text-slate-600">
              <div class="flex items-center justify-between p-3 rounded-xl bg-[#FAF8FF] border border-violet-100/80">
                <span class="font-semibold text-slate-700">Nama Produk:</span>
                <span class="font-bold text-slate-900">{{ BRAND.name }}</span>
              </div>
              <div class="flex items-center justify-between p-3 rounded-xl bg-[#FAF8FF] border border-violet-100/80">
                <span class="font-semibold text-slate-700">Tagline:</span>
                <span class="text-violet-700 font-bold">{{ BRAND.tagline }}</span>
              </div>
              <div class="flex items-center justify-between p-3 rounded-xl bg-[#FAF8FF] border border-violet-100/80">
                <span class="font-semibold text-slate-700">Primary Purple:</span>
                <span class="font-mono px-2.5 py-0.5 rounded-lg bg-[#6D28D9] text-white font-bold">#6D28D9</span>
              </div>
              <div class="flex items-center justify-between p-3 rounded-xl bg-[#FAF8FF] border border-violet-100/80">
                <span class="font-semibold text-slate-700">Secondary Violet:</span>
                <span class="font-mono px-2.5 py-0.5 rounded-lg bg-[#8B5CF6] text-white font-bold">#8B5CF6</span>
              </div>
              <div class="flex items-center justify-between p-3 rounded-xl bg-[#FAF8FF] border border-violet-100/80">
                <span class="font-semibold text-slate-700">Status Lunas / Pending:</span>
                <div class="flex items-center gap-1.5">
                  <span class="font-mono px-2 py-0.5 rounded-lg bg-[#22C55E] text-white font-bold">#22C55E</span>
                  <span class="font-mono px-2 py-0.5 rounded-lg bg-[#F59E0B] text-white font-bold">#F59E0B</span>
                </div>
              </div>
            </div>
          </div>

          <div class="pt-6 mt-6 border-t border-slate-100">
            <Link
              :href="route('dashboard')"
              class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-violet-700 hover:bg-violet-800 text-white text-xs font-bold shadow-invoicerapi-sm transition"
            >
              <span>Buka Workspace Dashboard</span>
              <ArrowRight class="w-3.5 h-3.5" />
            </Link>
          </div>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>
