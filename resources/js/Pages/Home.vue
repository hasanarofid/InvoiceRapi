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
  CreditCard,
  ShieldCheck,
  Check,
  ChevronRight,
  Sparkles,
  Smartphone,
  Play,
  HelpCircle,
} from 'lucide-vue-next';

// Toggle billing period: monthly or annual (save 30%)
const isAnnual = ref(false);

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
    image="/images/mockups/design-system-board.png"
  >
    <!-- 1. HERO SECTION -->
    <section id="beranda" class="relative pt-8 pb-16 md:pt-16 md:pb-24 overflow-hidden bg-gradient-to-b from-violet-50/50 via-white to-white">
      <!-- Decorative background blur -->
      <div class="absolute top-10 left-1/2 -translate-x-1/2 w-[650px] h-[350px] bg-gradient-to-tr from-violet-300/25 to-purple-200/20 rounded-full blur-3xl pointer-events-none -z-10"></div>

      <Container>
        <div class="max-w-4xl mx-auto text-center">
          <!-- Badge Pill -->
          <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full text-xs font-semibold text-violet-700 bg-violet-100/70 border border-violet-200/60 mb-6 shadow-sm">
            <Sparkles class="w-3.5 h-3.5 text-violet-600" />
            <span>SaaS Invoice & Manajemen Piutang Bisnis Jasa</span>
          </div>

          <!-- Main Headline -->
          <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-extrabold text-slate-900 tracking-tight leading-[1.18] mb-6">
            Kelola Invoice, DP,<br />
            Termin, dan Pelunasan<br class="hidden sm:inline" />
            Klien dalam <span class="text-transparent bg-clip-text bg-gradient-to-r from-violet-700 via-purple-600 to-violet-500">Satu Aplikasi</span>
          </h1>

          <!-- Subheadline -->
          <p class="text-base sm:text-lg text-slate-600 max-w-2xl mx-auto leading-relaxed mb-8">
            Solusi praktis untuk bisnis jasa seperti <span class="font-semibold text-slate-800">Wedding Organizer, Fotografer, Event Organizer, Kontraktor</span>, dan lainnya. Semua tagihan, pembayaran, dan status proyek jadi lebih rapi, terpantau, dan tepat waktu.
          </p>

          <!-- Action Buttons -->
          <div class="flex flex-col sm:flex-row items-center justify-center gap-3 sm:gap-4 mb-14">
            <Link
              href="/register"
              class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-8 py-3.5 rounded-full text-base font-semibold text-white bg-violet-700 hover:bg-violet-800 shadow-md hover:shadow-lg transition-all duration-200 transform hover:-translate-y-0.5"
            >
              <span>Coba Gratis 14 Hari</span>
              <ArrowRight class="w-4 h-4" />
            </Link>
            <a
              href="#demo"
              class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-7 py-3.5 rounded-full text-base font-semibold text-slate-700 bg-white hover:bg-slate-50 border border-slate-200 shadow-sm hover:shadow transition-all duration-200"
            >
              <Play class="w-4 h-4 text-violet-600 fill-violet-600" />
              <span>Lihat Demo</span>
            </a>
          </div>

          <!-- Hero Device Mockup Image (Official Cropped Graphic) -->
          <div class="relative mx-auto max-w-4xl rounded-2xl p-2 bg-gradient-to-b from-violet-200/60 to-purple-100/30 shadow-2xl border border-violet-100">
            <img
              src="/images/mockups/mockup-hero.png"
              alt="Synbarra InvoiceRapi Web & Mobile Preview"
              class="w-full h-auto rounded-xl shadow-inner object-cover"
              loading="eager"
            />
          </div>

          <!-- 4 Feature Pills Row (Directly from Mockup) -->
          <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mt-12 text-left">
            <div class="flex items-center gap-3.5 p-4 rounded-xl bg-white border border-slate-100 shadow-sm hover:shadow-md transition">
              <div class="w-11 h-11 rounded-lg bg-violet-50 text-violet-700 flex items-center justify-center flex-shrink-0">
                <FileText class="w-5 h-5" />
              </div>
              <div>
                <h4 class="text-sm font-bold text-slate-900 leading-snug">Buat Invoice Profesional</h4>
                <p class="text-xs text-slate-500 mt-0.5">Format otomatis & rapi</p>
              </div>
            </div>

            <div class="flex items-center gap-3.5 p-4 rounded-xl bg-white border border-slate-100 shadow-sm hover:shadow-md transition">
              <div class="w-11 h-11 rounded-lg bg-violet-50 text-violet-700 flex items-center justify-center flex-shrink-0">
                <Layers class="w-5 h-5" />
              </div>
              <div>
                <h4 class="text-sm font-bold text-slate-900 leading-snug">Atur DP & Termin</h4>
                <p class="text-xs text-slate-500 mt-0.5">Jadwal bayar bertahap</p>
              </div>
            </div>

            <div class="flex items-center gap-3.5 p-4 rounded-xl bg-white border border-slate-100 shadow-sm hover:shadow-md transition">
              <div class="w-11 h-11 rounded-lg bg-violet-50 text-violet-700 flex items-center justify-center flex-shrink-0">
                <Bell class="w-5 h-5" />
              </div>
              <div>
                <h4 class="text-sm font-bold text-slate-900 leading-snug">Pengingat Jatuh Tempo</h4>
                <p class="text-xs text-slate-500 mt-0.5">Kirim reminder tanpa sungkan</p>
              </div>
            </div>

            <div class="flex items-center gap-3.5 p-4 rounded-xl bg-white border border-slate-100 shadow-sm hover:shadow-md transition">
              <div class="w-11 h-11 rounded-lg bg-violet-50 text-violet-700 flex items-center justify-center flex-shrink-0">
                <BarChart3 class="w-5 h-5" />
              </div>
              <div>
                <h4 class="text-sm font-bold text-slate-900 leading-snug">Pantau Piutang</h4>
                <p class="text-xs text-slate-500 mt-0.5">Lacak saldo & pelunasan</p>
              </div>
            </div>
          </div>
        </div>
      </Container>
    </section>

    <!-- 2. SOLUSI UNTUK INDUSTRI JASA SECTION -->
    <section id="solusi" class="py-16 md:py-24 bg-slate-50/60 border-t border-slate-100">
      <Container>
        <div class="text-center max-w-2xl mx-auto mb-14">
          <span class="text-xs font-bold uppercase tracking-wider text-violet-700">Didesain Khusus untuk Bisnis Anda</span>
          <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 mt-2">
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
            class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm hover:shadow-md transition flex flex-col justify-between"
          >
            <div>
              <div class="inline-block px-3 py-1 rounded-full text-xs font-semibold text-violet-700 bg-violet-50 border border-violet-100 mb-4">
                {{ persona.badge }}
              </div>
              <h3 class="text-lg font-bold text-slate-900 mb-2">{{ persona.title }}</h3>
              <p class="text-xs font-medium text-violet-600 mb-3">{{ persona.tag }}</p>
              <p class="text-sm text-slate-600 leading-relaxed">
                {{ persona.description }}
              </p>
            </div>
            <div class="mt-6 pt-4 border-t border-slate-100 flex items-center text-xs font-semibold text-violet-700">
              <span>Siap digunakan langsung</span>
              <CheckCircle2 class="w-4 h-4 ml-1.5 text-emerald-500" />
            </div>
          </div>
        </div>
      </Container>
    </section>

    <!-- 3. FITUR UTAMA & PREVIEW DASHBOARD -->
    <section id="fitur" class="py-16 md:py-24 bg-white">
      <Container>
        <div class="text-center max-w-2xl mx-auto mb-14">
          <span class="text-xs font-bold uppercase tracking-wider text-violet-700">Fitur Lengkap MVP</span>
          <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 mt-2">
            Segala Kebutuhan Tagihan dalam Satu Dashboard
          </h2>
          <p class="text-sm sm:text-base text-slate-600 mt-3">
            Dirancang dengan antarmuka yang bersih, modern, dan sangat mudah digunakan oleh siapa saja.
          </p>
        </div>

        <!-- Desktop Dashboard Showcase -->
        <div class="mb-16">
          <div class="rounded-2xl border border-violet-100 p-3 bg-violet-50/40 shadow-xl overflow-hidden">
            <img
              src="/images/mockups/mockup-dashboard.png"
              alt="Dashboard Desktop InvoiceRapi"
              class="w-full h-auto rounded-xl shadow-sm object-cover"
              loading="lazy"
            />
          </div>
        </div>

        <!-- Mobile Screen Showcase (3 Layar) -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center mt-12">
          <div class="lg:col-span-5 space-y-6">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold text-violet-700 bg-violet-50">
              <Smartphone class="w-4 h-4" />
              <span>Mobile First & Responsif</span>
            </div>
            <h3 class="text-2xl sm:text-3xl font-bold text-slate-900">
              Kelola Tagihan Kapan Saja Langsung dari Smartphone
            </h3>
            <p class="text-slate-600 text-sm leading-relaxed">
              Sedang di lokasi pemotretan atau meeting dengan pengantin? Buka InvoiceRapi dari ponsel, catat DP yang baru masuk, dan bagikan invoice ke WhatsApp klien dalam hitungan detik.
            </p>
            <ul class="space-y-3 text-sm text-slate-700">
              <li class="flex items-center gap-2.5">
                <Check class="w-4 h-4 text-emerald-500 flex-shrink-0" />
                <span>Filter status cepat: Lunas, Sebagian, Belum Bayar</span>
              </li>
              <li class="flex items-center gap-2.5">
                <Check class="w-4 h-4 text-emerald-500 flex-shrink-0" />
                <span>Tracking checklist jadwal termin bertahap</span>
              </li>
              <li class="flex items-center gap-2.5">
                <Check class="w-4 h-4 text-emerald-500 flex-shrink-0" />
                <span>Daftar proyek klien dengan status aktif/selesai</span>
              </li>
            </ul>
          </div>
          <div class="lg:col-span-7">
            <div class="rounded-2xl border border-slate-100 p-2 bg-slate-50 shadow-lg">
              <img
                src="/images/mockups/mockup-mobile.png"
                alt="Mobile Screens Mockup InvoiceRapi"
                class="w-full h-auto rounded-xl object-contain"
                loading="lazy"
              />
            </div>
          </div>
        </div>
      </Container>
    </section>

    <!-- 4. PAKET LANGGANAN (PRICING) -->
    <section id="harga" class="py-16 md:py-24 bg-gradient-to-b from-slate-50/70 to-white border-t border-slate-100">
      <Container>
        <div class="text-center max-w-2xl mx-auto mb-10">
          <span class="text-xs font-bold uppercase tracking-wider text-violet-700">Biaya Transparan & Terjangkau</span>
          <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 mt-2">
            Pilih Paket yang Sesuai dengan Skala Bisnis Anda
          </h2>
          <p class="text-sm sm:text-base text-slate-600 mt-3">
            Mulai gratis, upgrade kapan pun bisnis Anda berkembang.
          </p>

          <!-- Billing Period Toggle -->
          <div class="inline-flex items-center gap-3 p-1.5 rounded-full bg-slate-100 border border-slate-200 mt-6 select-none">
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
                ? 'bg-white border-2 border-violet-600 shadow-xl ring-4 ring-violet-100/50 md:-translate-y-2' 
                : 'bg-white border border-slate-200 shadow-sm hover:shadow-md'
            ]"
          >
            <!-- Badge Paling Populer -->
            <div
              v-if="plan.popular"
              class="absolute -top-3.5 left-1/2 -translate-x-1/2 px-4 py-1 rounded-full text-xs font-bold text-white bg-violet-700 shadow-sm"
            >
              {{ plan.badge }}
            </div>

            <div>
              <h3 class="text-xl font-bold text-slate-900">{{ plan.name }}</h3>
              <div class="mt-4 flex items-baseline gap-1">
                <span class="text-3xl sm:text-4xl font-extrabold text-slate-900">
                  {{ isAnnual && plan.id === 'pro' ? 'Rp 69.000' : (isAnnual && plan.id === 'bisnis' ? 'Rp 139.000' : plan.price) }}
                </span>
                <span class="text-xs text-slate-500">{{ plan.period }}</span>
              </div>
              <p v-if="isAnnual && plan.id !== 'gratis'" class="text-[11px] text-emerald-600 font-medium mt-1">
                Ditagih tahunan (hemat 30%)
              </p>

              <!-- Feature List -->
              <ul class="mt-6 space-y-3 pt-6 border-t border-slate-100 text-sm text-slate-600">
                <li v-for="feat in plan.features" :key="feat" class="flex items-center gap-2.5">
                  <Check class="w-4 h-4 text-emerald-500 flex-shrink-0" />
                  <span>{{ feat }}</span>
                </li>
              </ul>
            </div>

            <div class="mt-8 pt-6">
              <Link
                :href="plan.ctaHref"
                :class="[
                  'w-full block text-center py-3 rounded-full text-sm font-semibold transition',
                  plan.popular
                    ? 'bg-violet-700 hover:bg-violet-800 text-white shadow-md'
                    : 'bg-slate-100 hover:bg-slate-200 text-slate-800'
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
      <!-- Glow effect -->
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
            class="w-full sm:w-auto px-8 py-3.5 rounded-full text-base font-semibold text-violet-800 bg-white hover:bg-violet-50 shadow-lg transition transform hover:-translate-y-0.5"
          >
            Mulai Uji Coba Gratis
          </Link>
          <a
            :href="'https://wa.me/' + BRAND.contact.whatsapp + '?text=Halo%20Synbarra,%20saya%20ingin%20jadwalkan%20live%20demo%20InvoiceRapi'"
            target="_blank"
            class="w-full sm:w-auto px-8 py-3.5 rounded-full text-base font-semibold text-white bg-violet-800/80 hover:bg-violet-800 border border-violet-400/30 transition"
          >
            Jadwalkan Demo via WhatsApp
          </a>
        </div>
      </Container>
    </section>

    <!-- 6. FAQ SECTION -->
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
              class="w-full text-left px-6 py-4.5 flex items-center justify-between text-base font-bold text-slate-900 hover:text-violet-700 focus:outline-none"
            >
              <span>{{ faq.q }}</span>
              <ChevronRight
                :class="[
                  'w-5 h-5 text-slate-400 transition-transform duration-200 flex-shrink-0 ml-3',
                  activeFaq === idx ? 'rotate-90 text-violet-700' : ''
                ]"
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
