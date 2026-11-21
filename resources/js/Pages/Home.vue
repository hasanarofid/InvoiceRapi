<script setup lang="ts">
import { ref } from 'vue';
import { Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import Container from '@/Components/UI/Container.vue';
import { BRAND } from '@/config/brand';
import {
  FileText,
  Layers,
  Bell,
  BarChart3,
  CheckCircle2,
  ArrowRight,
  Clock,
  Check,
  ChevronRight,
  Smartphone,
  Play,
  LayoutDashboard,
  Users,
  FolderKanban,
  CreditCard,
  TrendingUp,
  Download,
  Quote,
  Sparkles,
  AlertCircle,
} from 'lucide-vue-next';

// Toggle billing period: monthly or annual (save 30%)
const isAnnual = ref(false);

// Interactive feature preview tabs
const activeFeatureTab = ref<'dashboard' | 'termin' | 'public'>('dashboard');

// Interactive mobile filter pill
const activeMobileFilter = ref<'Semua' | 'Lunas' | 'Sebagian' | 'Belum Bayar'>('Semua');

const mobileInvoices = [
  { id: 'INV-2025-0012', client: 'Mulia Wedding', amount: 'Rp 50.000.000', status: 'Lunas', color: 'emerald' },
  { id: 'INV-2025-0013', client: 'Lensa Studio', amount: 'Rp 18.500.000', status: 'Sebagian', color: 'amber' },
  { id: 'INV-2025-0014', client: 'Karya Interior', amount: 'Rp 32.000.000', status: 'Belum Bayar', color: 'red' },
];

// FAQ accordion state
const activeFaq = ref<number | null>(0);
const toggleFaq = (index: number) => {
  activeFaq.value = activeFaq.value === index ? null : index;
};

const faqs = [
  {
    q: 'Apakah saya perlu menginstal aplikasi atau menyiapkan server sendiri?',
    a: 'Tidak perlu sama sekali. InvoiceRapi adalah platform SaaS berbasis cloud. Anda cukup mendaftar dan langsung dapat menggunakannya lewat browser di laptop, tablet, atau smartphone tanpa instalasi teknis apa pun.',
  },
  {
    q: 'Bagaimana cara kerja pembayaran bertahap (DP, termin, pelunasan)?',
    a: 'Saat membuat invoice, Anda dapat membagi jadwal pembayaran menjadi beberapa termin, misalnya DP 30%, Termin 1 40%, dan Pelunasan 30%. Setiap termin memiliki batas tanggal jatuh tempo dan nominal masing-masing, sehingga klien dan Anda memiliki acuan pembayaran yang jelas.',
  },
  {
    q: 'Apakah klien saya harus login untuk melihat invoice?',
    a: 'Klien Anda tidak perlu login atau membuat akun. InvoiceRapi menyediakan tautan publik aman berbasis token unik. Klien cukup membuka tautan tersebut untuk melihat detail invoice, jadwal termin, instruksi pembayaran rekening Anda, serta mengunduh PDF secara instan.',
  },
  {
    q: 'Apakah saya bisa mencatat pembayaran sebagian (parsial)?',
    a: 'Ya, tentu saja. Anda dapat mencatat pembayaran secara fleksibel, baik itu pembayaran penuh maupun sebagian. Saldo piutang dan status invoice akan otomatis terkalkulasi secara akurat.',
  },
  {
    q: 'Apakah ada masa percobaan gratis?',
    a: 'Ya! Anda dapat mencoba Paket Pro gratis selama 14 hari tanpa memerlukan kartu kredit. Kami juga menyediakan Paket Gratis permanen (5 invoice/bulan) untuk bisnis yang baru memulai.',
  },
];
</script>

<template>
  <AppLayout
    title="Synbarra InvoiceRapi | Urus Tagihan, Jadi Lebih Rapi."
    description="Kelola invoice, DP, termin, dan pelunasan klien dalam satu aplikasi yang mudah untuk bisnis jasa seperti WO, Fotografer, EO, dan Kontraktor."
  >
    <!-- 1. HERO SECTION (2-Column Split Matching Inspiration UI/UX) -->
    <section
      id="beranda"
      class="relative pt-8 pb-14 md:pt-14 md:pb-20 overflow-hidden bg-gradient-to-br from-[#FAF8FF] via-white to-violet-50/70"
    >
      <!-- Soft Lavender Ambient Glows -->
      <div class="absolute -top-24 -left-24 w-[420px] h-[420px] bg-violet-200/30 rounded-full blur-3xl pointer-events-none"></div>
      <div class="absolute top-12 right-0 w-[520px] h-[520px] bg-violet-200/35 rounded-full blur-3xl pointer-events-none"></div>

      <Container class="relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-8 items-center">
          <!-- Left Column: Copy + Dual CTA -->
          <div class="lg:col-span-6 text-left">
            <h1 class="text-3xl sm:text-4xl lg:text-[2.95rem] xl:text-[3.25rem] font-extrabold text-slate-900 tracking-tight leading-[1.14] mb-5">
              Kelola Invoice, DP,<br class="hidden sm:inline" />
              Termin, dan Pelunasan<br class="hidden sm:inline" />
              Klien dalam
              <span class="relative inline-block text-violet-700 whitespace-nowrap">
                Satu Aplikasi
                <!-- Hand-drawn Amber Underline Accent -->
                <svg
                  class="absolute -bottom-2 left-0 w-full h-3 text-amber-400 pointer-events-none"
                  viewBox="0 0 220 14"
                  fill="none"
                  preserveAspectRatio="none"
                  aria-hidden="true"
                >
                  <path
                    d="M3 10.5C58 3.5 152 2.5 217 8.5"
                    stroke="currentColor"
                    stroke-width="4.5"
                    stroke-linecap="round"
                  />
                </svg>
              </span>
            </h1>

            <p class="text-sm sm:text-base lg:text-[17px] text-slate-600 max-w-xl leading-relaxed mb-8">
              Solusi praktis untuk bisnis jasa seperti
              <span class="font-semibold text-slate-800">Wedding Organizer, Fotografer, Event Organizer, Kontraktor</span>,
              dan lainnya. Semua tagihan, pembayaran, dan status proyek jadi lebih rapi, terpantau, dan tepat waktu.
            </p>

            <!-- Dual CTA Buttons -->
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3.5">
              <Link
                href="/register"
                class="inline-flex items-center justify-center gap-2 px-6 py-3.5 rounded-xl text-sm sm:text-base font-semibold text-white bg-violet-700 hover:bg-violet-800 shadow-invoicerapi-md hover:shadow-invoicerapi-lg transition-all duration-200 transform hover:-translate-y-0.5"
              >
                <span>Coba Gratis 14 Hari</span>
                <ArrowRight class="w-4 h-4" aria-hidden="true" />
              </Link>
              <a
                href="#fitur"
                class="inline-flex items-center justify-center gap-2.5 px-6 py-3.5 rounded-xl text-sm sm:text-base font-semibold text-slate-800 bg-white hover:bg-violet-50/70 border border-slate-200 hover:border-violet-200 shadow-sm transition-all duration-200"
              >
                <span>Lihat Demo</span>
                <span class="w-6 h-6 rounded-full border border-slate-300 flex items-center justify-center text-violet-700">
                  <Play class="w-3 h-3 fill-violet-700 ml-0.5" aria-hidden="true" />
                </span>
              </a>
            </div>
          </div>

          <!-- Right Column: Interactive Code-Rendered Laptop + Smartphone UI Preview -->
          <div class="lg:col-span-6 relative flex items-center justify-center pt-4 pb-6 lg:py-0">
            <!-- Soft Lavender Circle Backdrop -->
            <div class="absolute w-72 h-72 sm:w-96 sm:h-96 rounded-full bg-violet-200/55 -top-4 right-4 sm:right-8 pointer-events-none"></div>

            <div class="relative w-full max-w-[540px] pr-4 sm:pr-10">
              <!-- Laptop Screen Frame -->
              <div class="relative bg-slate-900 rounded-t-2xl p-2 sm:p-2.5 shadow-2xl border border-slate-700/80">
                <!-- Camera Dot -->
                <div class="w-1.5 h-1.5 rounded-full bg-slate-700 mx-auto mb-1.5"></div>

                <!-- Laptop Inner App UI -->
                <div class="bg-[#FAF8FF] rounded-lg overflow-hidden border border-slate-200/40">
                  <!-- Mini App Topbar -->
                  <div class="bg-white border-b border-slate-100 px-3 py-2 flex items-center justify-between">
                    <div class="flex items-center gap-1.5">
                      <span class="w-4 h-4 rounded bg-violet-700 text-white flex items-center justify-center text-[8px] font-black">IR</span>
                      <span class="text-[10px] font-extrabold text-slate-900">Synbarra <span class="text-violet-700">InvoiceRapi</span></span>
                    </div>
                    <div class="hidden sm:flex items-center gap-2.5 text-[8px] font-medium text-slate-400">
                      <span>Beranda</span>
                      <span>Fitur</span>
                      <span>Solusi</span>
                      <span>Harga</span>
                    </div>
                    <div class="flex items-center gap-1">
                      <span class="px-2 py-0.5 rounded bg-violet-700 text-white text-[8px] font-bold">Coba Gratis</span>
                    </div>
                  </div>

                  <!-- Mini App Body -->
                  <div class="grid grid-cols-12 min-h-[215px] sm:min-h-[245px]">
                    <!-- Mini Sidebar -->
                    <div class="col-span-3 bg-white border-r border-slate-100 p-2 space-y-1">
                      <div class="flex items-center gap-1.5 px-2 py-1.5 rounded-md bg-violet-700 text-white text-[8px] font-bold shadow-xs">
                        <LayoutDashboard class="w-2.5 h-2.5 shrink-0" />
                        <span>Dashboard</span>
                      </div>
                      <div class="flex items-center gap-1.5 px-2 py-1 rounded-md text-slate-500 text-[8px] font-medium">
                        <Users class="w-2.5 h-2.5 shrink-0" />
                        <span>Klien</span>
                      </div>
                      <div class="flex items-center gap-1.5 px-2 py-1 rounded-md text-slate-500 text-[8px] font-medium">
                        <FolderKanban class="w-2.5 h-2.5 shrink-0" />
                        <span>Proyek</span>
                      </div>
                      <div class="flex items-center gap-1.5 px-2 py-1 rounded-md text-slate-500 text-[8px] font-medium">
                        <FileText class="w-2.5 h-2.5 shrink-0" />
                        <span>Invoice</span>
                      </div>
                      <div class="flex items-center gap-1.5 px-2 py-1 rounded-md text-slate-500 text-[8px] font-medium">
                        <CreditCard class="w-2.5 h-2.5 shrink-0" />
                        <span>Pembayaran</span>
                      </div>
                      <div class="flex items-center gap-1.5 px-2 py-1 rounded-md text-slate-500 text-[8px] font-medium">
                        <BarChart3 class="w-2.5 h-2.5 shrink-0" />
                        <span>Laporan</span>
                      </div>
                    </div>

                    <!-- Mini Dashboard Content -->
                    <div class="col-span-9 p-2.5 sm:p-3 space-y-2.5">
                      <!-- 3 Mini Stat Cards -->
                      <div class="grid grid-cols-3 gap-2">
                        <div class="bg-white rounded-lg p-2 border border-slate-100 shadow-2xs">
                          <div class="flex items-center gap-1.5">
                            <span class="w-4 h-4 rounded bg-violet-100 text-violet-700 flex items-center justify-center shrink-0">
                              <FileText class="w-2.5 h-2.5" />
                            </span>
                            <span class="text-[7px] font-semibold text-slate-400 truncate">Total Tagihan</span>
                          </div>
                          <p class="text-[9px] sm:text-[10px] font-black text-slate-900 mt-1">Rp 125.000.000</p>
                        </div>

                        <div class="bg-white rounded-lg p-2 border border-slate-100 shadow-2xs">
                          <div class="flex items-center gap-1.5">
                            <span class="w-4 h-4 rounded bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0">
                              <CheckCircle2 class="w-2.5 h-2.5" />
                            </span>
                            <span class="text-[7px] font-semibold text-slate-400 truncate">Sudah Dibayar</span>
                          </div>
                          <p class="text-[9px] sm:text-[10px] font-black text-slate-900 mt-1">Rp 78.000.000</p>
                        </div>

                        <div class="bg-white rounded-lg p-2 border border-slate-100 shadow-2xs">
                          <div class="flex items-center gap-1.5">
                            <span class="w-4 h-4 rounded bg-amber-100 text-amber-600 flex items-center justify-center shrink-0">
                              <Clock class="w-2.5 h-2.5" />
                            </span>
                            <span class="text-[7px] font-semibold text-slate-400 truncate">Sisa Piutang</span>
                          </div>
                          <p class="text-[9px] sm:text-[10px] font-black text-slate-900 mt-1">Rp 35.000.000</p>
                        </div>
                      </div>

                      <!-- Mini Chart Card -->
                      <div class="bg-white rounded-lg p-2.5 border border-slate-100 shadow-2xs">
                        <div class="flex items-center justify-between mb-1.5">
                          <div>
                            <p class="text-[8px] font-bold text-slate-800">Pendapatan Bulanan</p>
                            <p class="text-[7px] text-slate-400">6 bulan terakhir</p>
                          </div>
                          <span class="px-1.5 py-0.5 rounded bg-violet-50 text-violet-700 text-[7px] font-bold">+24%</span>
                        </div>

                        <!-- Mini SVG Line Chart -->
                        <svg class="w-full h-24" viewBox="0 0 260 90" preserveAspectRatio="none" aria-hidden="true">
                          <defs>
                            <linearGradient id="heroMiniArea" x1="0" y1="0" x2="0" y2="1">
                              <stop offset="0%" stop-color="#8B5CF6" stop-opacity="0.28" />
                              <stop offset="100%" stop-color="#8B5CF6" stop-opacity="0.0" />
                            </linearGradient>
                          </defs>
                          <line x1="10" y1="20" x2="250" y2="20" stroke="#F1F5F9" stroke-width="1" />
                          <line x1="10" y1="45" x2="250" y2="45" stroke="#F1F5F9" stroke-width="1" />
                          <line x1="10" y1="70" x2="250" y2="70" stroke="#E2E8F0" stroke-width="1" />
                          <path d="M 15 62 L 60 54 L 105 42 L 150 40 L 195 30 L 245 14 L 245 75 L 15 75 Z" fill="url(#heroMiniArea)" />
                          <path d="M 15 62 L 60 54 L 105 42 L 150 40 L 195 30 L 245 14" fill="none" stroke="#6D28D9" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" />
                          <circle cx="15" cy="62" r="2.8" fill="#6D28D9" stroke="#FFF" stroke-width="1" />
                          <circle cx="60" cy="54" r="2.8" fill="#6D28D9" stroke="#FFF" stroke-width="1" />
                          <circle cx="105" cy="42" r="2.8" fill="#6D28D9" stroke="#FFF" stroke-width="1" />
                          <circle cx="150" cy="40" r="2.8" fill="#6D28D9" stroke="#FFF" stroke-width="1" />
                          <circle cx="195" cy="30" r="2.8" fill="#6D28D9" stroke="#FFF" stroke-width="1" />
                          <circle cx="245" cy="14" r="3.2" fill="#6D28D9" stroke="#FFF" stroke-width="1.2" />
                          <text x="15" y="86" font-size="6.5" fill="#94A3B8" text-anchor="middle">Agu</text>
                          <text x="60" y="86" font-size="6.5" fill="#94A3B8" text-anchor="middle">Sep</text>
                          <text x="105" y="86" font-size="6.5" fill="#94A3B8" text-anchor="middle">Okt</text>
                          <text x="150" y="86" font-size="6.5" fill="#94A3B8" text-anchor="middle">Nov</text>
                          <text x="195" y="86" font-size="6.5" fill="#94A3B8" text-anchor="middle">Des</text>
                          <text x="245" y="86" font-size="6.5" fill="#94A3B8" text-anchor="middle">Jan</text>
                        </svg>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Laptop Base Lip -->
              <div class="h-3 bg-gradient-to-b from-slate-200 to-slate-300 rounded-b-xl w-[106%] -ml-[3%] shadow-md flex items-center justify-center">
                <div class="w-16 h-1 rounded-full bg-slate-400/60"></div>
              </div>

              <!-- Overlapping Smartphone Frame (Bottom Right) -->
              <div class="absolute -bottom-5 right-0 sm:right-1 w-36 sm:w-44 bg-slate-900 rounded-[1.6rem] p-1.5 shadow-2xl border-2 border-slate-700 z-20">
                <!-- Phone Notch -->
                <div class="w-12 h-2.5 bg-slate-900 rounded-b-lg mx-auto mb-0.5"></div>
                <div class="bg-white rounded-[1.2rem] px-3 py-3.5 text-center space-y-2">
                  <!-- Top icons -->
                  <div class="flex items-center justify-between text-[9px] text-slate-400 px-0.5">
                    <span>&larr;</span>
                    <span class="w-1.5 h-1.5 rounded-full bg-violet-200"></span>
                  </div>

                  <!-- Green Check Badge -->
                  <div class="w-10 h-10 rounded-full bg-emerald-500 text-white flex items-center justify-center mx-auto shadow-sm">
                    <Check class="w-5 h-5 stroke-[3]" aria-hidden="true" />
                  </div>

                  <div>
                    <p class="text-[10px] font-semibold text-slate-500">Invoice</p>
                    <p class="text-xs font-extrabold text-slate-900 tracking-tight">INV-2025-0012</p>
                  </div>

                  <p class="text-sm font-black text-slate-900">Rp 50.000.000</p>

                  <div class="pt-0.5">
                    <span class="inline-block w-full py-1.5 rounded-full bg-emerald-400/25 text-emerald-700 text-[11px] font-extrabold">
                      Lunas
                    </span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Bottom Hero 4-Pillar Unified Feature Card (Matching Inspiration Image) -->
        <div class="mt-14 lg:mt-16 bg-white rounded-2xl border border-violet-100/80 shadow-invoicerapi-md p-2 sm:p-3">
          <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 divide-y sm:divide-y-0 sm:divide-x divide-slate-100">
            <div class="flex items-center gap-3.5 p-3.5 sm:p-4">
              <div class="w-10 h-10 rounded-xl bg-violet-50 text-violet-700 flex items-center justify-center shrink-0">
                <FileText class="w-5 h-5" aria-hidden="true" />
              </div>
              <span class="text-sm font-bold text-slate-900 leading-snug">Buat Invoice Profesional</span>
            </div>

            <div class="flex items-center gap-3.5 p-3.5 sm:p-4">
              <div class="w-10 h-10 rounded-xl bg-violet-50 text-violet-700 flex items-center justify-center shrink-0">
                <Layers class="w-5 h-5" aria-hidden="true" />
              </div>
              <span class="text-sm font-bold text-slate-900 leading-snug">Atur DP &amp; Termin Pembayaran</span>
            </div>

            <div class="flex items-center gap-3.5 p-3.5 sm:p-4">
              <div class="w-10 h-10 rounded-xl bg-violet-50 text-violet-700 flex items-center justify-center shrink-0">
                <Bell class="w-5 h-5" aria-hidden="true" />
              </div>
              <span class="text-sm font-bold text-slate-900 leading-snug">Pengingat Jatuh Tempo</span>
            </div>

            <div class="flex items-center gap-3.5 p-3.5 sm:p-4">
              <div class="w-10 h-10 rounded-xl bg-violet-50 text-violet-700 flex items-center justify-center shrink-0">
                <BarChart3 class="w-5 h-5" aria-hidden="true" />
              </div>
              <span class="text-sm font-bold text-slate-900 leading-snug">Pantau Piutang dan Pelunasan</span>
            </div>
          </div>
        </div>
      </Container>
    </section>

    <!-- 2. SOLUSI UNTUK INDUSTRI JASA SECTION -->
    <section id="solusi" class="py-16 md:py-24 bg-white border-t border-slate-100">
      <Container>
        <div class="text-center max-w-2xl mx-auto mb-14">
          <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider text-violet-700 bg-violet-50 border border-violet-100">
            <Sparkles class="w-3.5 h-3.5" aria-hidden="true" />
            Didesain Khusus untuk Bisnis Anda
          </span>
          <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 mt-3">
            Solusi Tepat untuk Bisnis Berbasis Proyek
          </h2>
          <p class="text-sm sm:text-base text-slate-600 mt-3">
            Tinggalkan pencatatan uang muka manual di catatan kertas atau chat WhatsApp yang rawan terlupakan.
          </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
          <div
            v-for="persona in BRAND.personas"
            :key="persona.title"
            class="bg-[#FAF8FF]/60 hover:bg-white rounded-2xl p-6 border border-violet-100/70 hover:border-violet-200 shadow-xs hover:shadow-invoicerapi-md transition-all flex flex-col justify-between"
          >
            <div>
              <div class="inline-block px-3 py-1 rounded-full text-xs font-semibold text-violet-700 bg-violet-100/70 mb-4">
                {{ persona.badge }}
              </div>
              <h3 class="text-lg font-bold text-slate-900 mb-1.5">{{ persona.title }}</h3>
              <p class="text-xs font-semibold text-violet-600 mb-3">{{ persona.tag }}</p>
              <p class="text-sm text-slate-600 leading-relaxed">
                {{ persona.description }}
              </p>
            </div>
            <div class="mt-6 pt-4 border-t border-violet-100/60 flex items-center text-xs font-semibold text-violet-700">
              <span>Siap digunakan langsung</span>
              <CheckCircle2 class="w-4 h-4 ml-1.5 text-emerald-500" aria-hidden="true" />
            </div>
          </div>
        </div>
      </Container>
    </section>

    <!-- 3. FITUR UTAMA & INTERACTIVE LIVE PREVIEW (No Embedded Static PNGs) -->
    <section id="fitur" class="py-16 md:py-24 bg-[#FAF8FF] border-t border-violet-100/60">
      <Container>
        <div class="text-center max-w-2xl mx-auto mb-10">
          <span class="text-xs font-bold uppercase tracking-wider text-violet-700">Preview Aplikasi Interaktif</span>
          <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 mt-2">
            Segala Kebutuhan Tagihan dalam Satu Dashboard
          </h2>
          <p class="text-sm sm:text-base text-slate-600 mt-3">
            Coba klik tab di bawah untuk melihat bagaimana InvoiceRapi merapikan arus kas, termin DP, dan tautan invoice klien Anda.
          </p>

          <!-- Interactive Feature Tabs -->
          <div class="inline-flex flex-wrap items-center justify-center gap-2 p-1.5 rounded-2xl bg-white border border-violet-100 shadow-xs mt-7">
            <button
              type="button"
              @click="activeFeatureTab = 'dashboard'"
              :class="[
                'px-4 py-2 rounded-xl text-xs sm:text-sm font-semibold transition',
                activeFeatureTab === 'dashboard' ? 'bg-violet-700 text-white shadow-sm' : 'text-slate-600 hover:text-violet-700'
              ]"
            >
              Ringkasan Dashboard
            </button>
            <button
              type="button"
              @click="activeFeatureTab = 'termin'"
              :class="[
                'px-4 py-2 rounded-xl text-xs sm:text-sm font-semibold transition',
                activeFeatureTab === 'termin' ? 'bg-violet-700 text-white shadow-sm' : 'text-slate-600 hover:text-violet-700'
              ]"
            >
              Atur DP &amp; Termin
            </button>
            <button
              type="button"
              @click="activeFeatureTab = 'public'"
              :class="[
                'px-4 py-2 rounded-xl text-xs sm:text-sm font-semibold transition',
                activeFeatureTab === 'public' ? 'bg-violet-700 text-white shadow-sm' : 'text-slate-600 hover:text-violet-700'
              ]"
            >
              Link Publik Klien
            </button>
          </div>
        </div>

        <!-- Interactive Preview Frame -->
        <div class="bg-white rounded-3xl border border-violet-100 shadow-invoicerapi-lg p-4 sm:p-7 mb-16">
          <!-- TAB 1: Dashboard -->
          <div v-if="activeFeatureTab === 'dashboard'" class="space-y-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-4 border-b border-slate-100">
              <div>
                <p class="text-xs font-semibold text-violet-700">Workspace Aktif: Mulia Event Organizer</p>
                <h3 class="text-lg font-extrabold text-slate-900">Ringkasan Keuangan &amp; Piutang</h3>
              </div>
              <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-emerald-50 text-emerald-700 text-xs font-bold border border-emerald-200/70">
                <TrendingUp class="w-3.5 h-3.5" aria-hidden="true" />
                Arus Kas Sehat (+24% bulan ini)
              </span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
              <div class="p-4 rounded-2xl bg-[#FAF8FF] border border-violet-100 flex items-center justify-between">
                <div>
                  <p class="text-xs font-medium text-slate-500">Total Tagihan</p>
                  <p class="text-lg font-black text-slate-900 mt-1">Rp 125.000.000</p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-violet-100 text-violet-700 flex items-center justify-center">
                  <FileText class="w-5 h-5" />
                </div>
              </div>
              <div class="p-4 rounded-2xl bg-emerald-50/50 border border-emerald-100 flex items-center justify-between">
                <div>
                  <p class="text-xs font-medium text-slate-500">Sudah Dibayar</p>
                  <p class="text-lg font-black text-slate-900 mt-1">Rp 78.000.000</p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center">
                  <CheckCircle2 class="w-5 h-5" />
                </div>
              </div>
              <div class="p-4 rounded-2xl bg-amber-50/50 border border-amber-100 flex items-center justify-between">
                <div>
                  <p class="text-xs font-medium text-slate-500">Sisa Piutang</p>
                  <p class="text-lg font-black text-slate-900 mt-1">Rp 35.000.000</p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-amber-100 text-amber-600 flex items-center justify-center">
                  <Clock class="w-5 h-5" />
                </div>
              </div>
              <div class="p-4 rounded-2xl bg-red-50/50 border border-red-100 flex items-center justify-between">
                <div>
                  <p class="text-xs font-medium text-slate-500">Terlambat</p>
                  <p class="text-lg font-black text-red-600 mt-1">Rp 12.000.000</p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-red-100 text-red-600 flex items-center justify-center">
                  <AlertCircle class="w-5 h-5" />
                </div>
              </div>
            </div>
          </div>

          <!-- TAB 2: Termin & DP -->
          <div v-else-if="activeFeatureTab === 'termin'" class="space-y-5">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-4 border-b border-slate-100">
              <div>
                <p class="text-xs font-semibold text-violet-700">INV-2025-0018 &bull; Pernikahan Aditya &amp; Rina</p>
                <h3 class="text-lg font-extrabold text-slate-900">Jadwal Pembayaran Bertahap (3 Termin)</h3>
              </div>
              <span class="px-3 py-1 rounded-full bg-amber-100 text-amber-800 text-xs font-bold">Dibayar Sebagian (70%)</span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
              <div class="p-4 rounded-2xl border border-emerald-200 bg-emerald-50/40 space-y-2">
                <div class="flex items-center justify-between">
                  <span class="text-xs font-bold text-emerald-700">Termin 1 &bull; DP 30%</span>
                  <span class="px-2 py-0.5 rounded-full bg-emerald-500 text-white text-[10px] font-bold">Lunas</span>
                </div>
                <p class="text-lg font-black text-slate-900">Rp 15.000.000</p>
                <p class="text-xs text-slate-500">Dibayar 12 Jan 2026 via Transfer BCA</p>
              </div>

              <div class="p-4 rounded-2xl border border-emerald-200 bg-emerald-50/40 space-y-2">
                <div class="flex items-center justify-between">
                  <span class="text-xs font-bold text-emerald-700">Termin 2 &bull; Progress 40%</span>
                  <span class="px-2 py-0.5 rounded-full bg-emerald-500 text-white text-[10px] font-bold">Lunas</span>
                </div>
                <p class="text-lg font-black text-slate-900">Rp 20.000.000</p>
                <p class="text-xs text-slate-500">Dibayar 02 Feb 2026 via Transfer Mandiri</p>
              </div>

              <div class="p-4 rounded-2xl border border-amber-200 bg-amber-50/40 space-y-2">
                <div class="flex items-center justify-between">
                  <span class="text-xs font-bold text-amber-700">Termin 3 &bull; Pelunasan 30%</span>
                  <span class="px-2 py-0.5 rounded-full bg-amber-500 text-white text-[10px] font-bold">Menunggu</span>
                </div>
                <p class="text-lg font-black text-slate-900">Rp 15.000.000</p>
                <p class="text-xs text-slate-500">Jatuh tempo 20 Feb 2026</p>
              </div>
            </div>
          </div>

          <!-- TAB 3: Public Client Link -->
          <div v-else class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-center">
            <div class="lg:col-span-7 space-y-3">
              <span class="inline-block px-3 py-1 rounded-full bg-violet-100 text-violet-700 text-xs font-bold">
                Tanpa Login Klien (/i/token-unik)
              </span>
              <h3 class="text-xl font-extrabold text-slate-900">Klien Cukup Klik Tautan untuk Melihat Tagihan</h3>
              <p class="text-sm text-slate-600 leading-relaxed">
                Kirimkan tautan invoice resmi via WhatsApp. Klien langsung melihat rincian item, progres DP yang sudah masuk, nomor rekening tujuan, dan tombol unduh PDF.
              </p>
            </div>
            <div class="lg:col-span-5 bg-[#FAF8FF] border border-violet-200 rounded-2xl p-5 space-y-3">
              <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-500">INV-2025-0012</span>
                <span class="px-2.5 py-0.5 rounded-full bg-emerald-100 text-emerald-700 text-xs font-bold">Lunas</span>
              </div>
              <p class="text-2xl font-black text-slate-900">Rp 50.000.000</p>
              <p class="text-xs text-slate-500">BCA 1234-567-890 a.n. Mulia Event Organizer</p>
              <button
                type="button"
                class="w-full inline-flex items-center justify-center gap-2 py-2.5 rounded-xl bg-violet-700 text-white text-xs font-bold"
              >
                <Download class="w-4 h-4" />
                <span>Unduh Invoice PDF</span>
              </button>
            </div>
          </div>
        </div>

        <!-- Mobile-First Interactive Showcase -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
          <div class="lg:col-span-5 space-y-5">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold text-violet-700 bg-violet-100/70">
              <Smartphone class="w-4 h-4" aria-hidden="true" />
              <span>Mobile First &amp; Responsif</span>
            </div>
            <h3 class="text-2xl sm:text-3xl font-extrabold text-slate-900">
              Kelola Tagihan Kapan Saja Langsung dari Smartphone
            </h3>
            <p class="text-slate-600 text-sm sm:text-base leading-relaxed">
              Sedang di lokasi pemotretan atau meeting dengan klien? Buka InvoiceRapi dari ponsel, cek status pembayaran, dan kirim pengingat dalam hitungan detik.
            </p>
            <ul class="space-y-3 text-sm text-slate-700">
              <li class="flex items-center gap-2.5">
                <Check class="w-4 h-4 text-emerald-500 flex-shrink-0" aria-hidden="true" />
                <span>Filter status cepat: Lunas, Sebagian, Belum Bayar</span>
              </li>
              <li class="flex items-center gap-2.5">
                <Check class="w-4 h-4 text-emerald-500 flex-shrink-0" aria-hidden="true" />
                <span>Tracking checklist jadwal termin bertahap</span>
              </li>
              <li class="flex items-center gap-2.5">
                <Check class="w-4 h-4 text-emerald-500 flex-shrink-0" aria-hidden="true" />
                <span>Daftar proyek klien dengan status aktif &amp; selesai</span>
              </li>
            </ul>
          </div>

          <!-- Interactive Mobile Card List Preview -->
          <div class="lg:col-span-7">
            <div class="bg-white rounded-3xl border border-violet-100 shadow-invoicerapi-md p-5 sm:p-6 space-y-4">
              <div class="flex flex-wrap items-center justify-between gap-2 pb-3 border-b border-slate-100">
                <span class="text-sm font-extrabold text-slate-900">Daftar Invoice Klien</span>
                <div class="flex flex-wrap gap-1.5">
                  <button
                    v-for="filter in (['Semua', 'Lunas', 'Sebagian', 'Belum Bayar'] as const)"
                    :key="filter"
                    type="button"
                    @click="activeMobileFilter = filter"
                    :class="[
                      'px-3 py-1 rounded-full text-xs font-semibold transition',
                      activeMobileFilter === filter
                        ? 'bg-violet-700 text-white'
                        : 'bg-slate-100 text-slate-600 hover:bg-violet-50 hover:text-violet-700'
                    ]"
                  >
                    {{ filter }}
                  </button>
                </div>
              </div>

              <div class="space-y-3">
                <div
                  v-for="inv in mobileInvoices.filter(i => activeMobileFilter === 'Semua' || i.status === activeMobileFilter)"
                  :key="inv.id"
                  class="flex items-center justify-between p-3.5 rounded-2xl bg-[#FAF8FF]/70 border border-slate-100 hover:border-violet-200 transition"
                >
                  <div>
                    <p class="text-xs font-bold text-violet-700">{{ inv.id }}</p>
                    <p class="text-sm font-bold text-slate-900">{{ inv.client }}</p>
                  </div>
                  <div class="text-right">
                    <p class="text-sm font-black text-slate-900">{{ inv.amount }}</p>
                    <span
                      :class="[
                        'inline-block mt-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold',
                        inv.color === 'emerald' ? 'bg-emerald-100 text-emerald-700' : '',
                        inv.color === 'amber' ? 'bg-amber-100 text-amber-700' : '',
                        inv.color === 'red' ? 'bg-red-100 text-red-700' : ''
                      ]"
                    >
                      {{ inv.status }}
                    </span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </Container>
    </section>

    <!-- 4. PAKET LANGGANAN (PRICING) -->
    <section id="harga" class="py-16 md:py-24 bg-white border-t border-slate-100">
      <Container>
        <div class="text-center max-w-2xl mx-auto mb-10">
          <span class="text-xs font-bold uppercase tracking-wider text-violet-700">Biaya Transparan &amp; Terjangkau</span>
          <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 mt-2">
            Pilih Paket yang Sesuai dengan Skala Bisnis Anda
          </h2>
          <p class="text-sm sm:text-base text-slate-600 mt-3">
            Mulai gratis, upgrade kapan pun bisnis Anda berkembang.
          </p>

          <!-- Billing Period Toggle -->
          <div class="inline-flex items-center gap-2 p-1.5 rounded-full bg-slate-100 border border-slate-200 mt-6 select-none">
            <button
              type="button"
              @click="isAnnual = false"
              :class="[
                'px-5 py-1.5 rounded-full text-xs font-semibold transition',
                !isAnnual ? 'bg-violet-700 text-white shadow-sm' : 'text-slate-600 hover:text-slate-900'
              ]"
            >
              Bulanan
            </button>
            <button
              type="button"
              @click="isAnnual = true"
              :class="[
                'px-5 py-1.5 rounded-full text-xs font-semibold flex items-center gap-1.5 transition',
                isAnnual ? 'bg-violet-700 text-white shadow-sm' : 'text-slate-600 hover:text-slate-900'
              ]"
            >
              <span>Tahunan</span>
              <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">Hemat 30%</span>
            </button>
          </div>
        </div>

        <!-- Pricing Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-6xl mx-auto items-stretch">
          <div
            v-for="plan in BRAND.pricing"
            :key="plan.id"
            :class="[
              'rounded-2xl p-7 flex flex-col justify-between transition-all duration-200 relative',
              plan.popular
                ? 'bg-white border-2 border-violet-600 shadow-invoicerapi-lg ring-4 ring-violet-100/60 md:-translate-y-2'
                : 'bg-white border border-slate-200 shadow-xs hover:shadow-invoicerapi-md'
            ]"
          >
            <div
              v-if="plan.popular"
              class="absolute -top-3.5 left-1/2 -translate-x-1/2 px-4 py-1 rounded-full text-xs font-bold text-white bg-violet-700 shadow-sm"
            >
              {{ plan.badge }}
            </div>

            <div>
              <h3 class="text-xl font-bold text-slate-900">{{ plan.name }}</h3>
              <div v-if="(plan as any).originalPrice && !isAnnual" class="mt-3 flex items-center gap-2">
                <span class="text-xs font-semibold text-slate-400 line-through">{{ (plan as any).originalPrice }}</span>
                <span class="px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700 text-[10px] font-bold border border-emerald-200">Harga Promo</span>
              </div>
              <div class="mt-2 flex items-baseline gap-1">
                <span class="text-3xl sm:text-4xl font-extrabold text-slate-900">
                  {{ isAnnual && plan.id === 'pro' ? 'Rp 39.000' : (isAnnual && plan.id === 'bisnis' ? 'Rp 83.000' : plan.price) }}
                </span>
                <span class="text-xs text-slate-500">{{ plan.period }}</span>
              </div>
              <p v-if="isAnnual && plan.id !== 'gratis'" class="text-[11px] text-emerald-600 font-medium mt-1">
                Ditagih tahunan (hemat 30%)
              </p>

              <ul class="mt-6 space-y-3 pt-6 border-t border-slate-100 text-sm text-slate-600">
                <li v-for="feat in plan.features" :key="feat" class="flex items-center gap-2.5">
                  <Check class="w-4 h-4 text-emerald-500 flex-shrink-0" aria-hidden="true" />
                  <span>{{ feat }}</span>
                </li>
              </ul>
            </div>

            <div class="mt-8 pt-6">
              <Link
                :href="plan.ctaHref"
                :class="[
                  'w-full block text-center py-3 rounded-xl text-sm font-semibold transition',
                  plan.popular
                    ? 'bg-violet-700 hover:bg-violet-800 text-white shadow-invoicerapi-sm'
                    : 'bg-slate-100 hover:bg-violet-50 text-slate-800 hover:text-violet-700'
                ]"
              >
                {{ plan.ctaText }}
              </Link>
            </div>
          </div>
        </div>
      </Container>
    </section>

    <!-- 5. INTERACTIVE DEMO CTA SECTION -->
    <section id="demo" class="py-16 md:py-24 bg-violet-700 text-white relative overflow-hidden">
      <div class="absolute -top-24 -left-24 w-96 h-96 bg-purple-400/20 rounded-full blur-3xl pointer-events-none"></div>
      <div class="absolute -bottom-24 -right-24 w-96 h-96 bg-violet-900/40 rounded-full blur-3xl pointer-events-none"></div>

      <Container class="relative z-10 text-center max-w-3xl mx-auto">
        <span class="inline-block px-3.5 py-1 rounded-full text-xs font-semibold bg-white/10 border border-white/20 mb-4">
          Demo Interaktif
        </span>
        <h2 class="text-3xl sm:text-4xl font-extrabold tracking-tight">
          Coba Alur Penagihan Rapi Sekarang
        </h2>
        <p class="text-violet-100 text-base sm:text-lg mt-4 max-w-xl mx-auto leading-relaxed">
          Lihat betapa mudahnya membuat invoice pertama, memecah termin pembayaran, hingga mencatat pelunasan dalam waktu kurang dari 2 menit.
        </p>

        <div class="mt-8 flex flex-col sm:flex-row items-center justify-center gap-4">
          <Link
            href="/register"
            class="w-full sm:w-auto px-8 py-3.5 rounded-xl text-base font-semibold text-violet-800 bg-white hover:bg-violet-50 shadow-lg transition transform hover:-translate-y-0.5"
          >
            Mulai Uji Coba Gratis
          </Link>
          <a
            :href="'https://wa.me/' + BRAND.contact.whatsapp + '?text=Halo%20Synbarra,%20saya%20ingin%20jadwalkan%20live%20demo%20InvoiceRapi'"
            target="_blank"
            class="w-full sm:w-auto px-8 py-3.5 rounded-xl text-base font-semibold text-white bg-violet-800/80 hover:bg-violet-800 border border-violet-400/30 transition"
          >
            Jadwalkan Demo via WhatsApp
          </a>
        </div>
      </Container>
    </section>

    <!-- 6. TESTIMONI BISNIS JASA -->
    <section id="testimoni" class="py-16 md:py-24 bg-[#FAF8FF] border-t border-violet-100/60">
      <Container>
        <div class="text-center max-w-2xl mx-auto mb-12">
          <span class="text-xs font-bold uppercase tracking-wider text-violet-700">Dipercaya Pebisnis Jasa</span>
          <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 mt-2">
            Cerita Pengguna Synbarra InvoiceRapi
          </h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
          <div
            v-for="item in BRAND.testimonials"
            :key="item.name"
            class="bg-white rounded-2xl p-6 border border-violet-100/80 shadow-xs flex flex-col justify-between space-y-5"
          >
            <div class="space-y-3">
              <div class="flex items-center justify-between">
                <Quote class="w-6 h-6 text-violet-300" aria-hidden="true" />
                <span class="px-2.5 py-0.5 rounded-full bg-emerald-50 text-emerald-700 text-[11px] font-bold border border-emerald-100">
                  {{ item.metric }}
                </span>
              </div>
              <p class="text-sm text-slate-600 leading-relaxed">
                "{{ item.quote }}"
              </p>
            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center gap-3">
              <div class="w-9 h-9 rounded-full bg-violet-100 text-violet-700 font-bold text-xs flex items-center justify-center shrink-0">
                {{ item.name.charAt(0) }}
              </div>
              <div>
                <p class="text-sm font-bold text-slate-900">{{ item.name }}</p>
                <p class="text-xs text-slate-500">{{ item.role }}</p>
              </div>
            </div>
          </div>
        </div>
      </Container>
    </section>

    <!-- 7. FAQ SECTION -->
    <section id="faq" class="py-16 md:py-24 bg-white border-t border-slate-100">
      <Container class="max-w-3xl">
        <div class="text-center mb-12">
          <span class="text-xs font-bold uppercase tracking-wider text-violet-700">Tanya Jawab</span>
          <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 mt-2">
            Pertanyaan yang Sering Diajukan
          </h2>
        </div>

        <div class="space-y-3.5">
          <div
            v-for="(faq, idx) in faqs"
            :key="idx"
            class="border border-slate-200 rounded-2xl overflow-hidden transition"
          >
            <button
              type="button"
              @click="toggleFaq(idx)"
              :aria-expanded="activeFaq === idx"
              class="w-full text-left px-6 py-4 flex items-center justify-between text-base font-bold text-slate-900 hover:text-violet-700 focus:outline-none"
            >
              <span>{{ faq.q }}</span>
              <ChevronRight
                :class="[
                  'w-5 h-5 text-slate-400 transition-transform duration-200 flex-shrink-0 ml-3',
                  activeFaq === idx ? 'rotate-90 text-violet-700' : ''
                ]"
                aria-hidden="true"
              />
            </button>
            <div
              v-show="activeFaq === idx"
              class="px-6 pb-5 text-sm text-slate-600 leading-relaxed border-t border-slate-100 pt-3"
            >
              {{ faq.a }}
            </div>
          </div>
        </div>
      </Container>
    </section>
  </AppLayout>
</template>
