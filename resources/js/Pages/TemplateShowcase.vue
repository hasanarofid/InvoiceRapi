<script setup lang="ts">
import { ref } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import { 
  Sparkles, 
  ArrowRight, 
  CheckCircle2, 
  ShieldCheck, 
  Zap, 
  Layers, 
  BarChart3, 
  Code2, 
  Layout, 
  Smartphone, 
  ChevronRight, 
  Star, 
  Headphones, 
  Building2, 
  ArrowUpRight,
  Monitor,
  Check,
  Send
} from '@lucide/vue';

const activeBilling = ref<'monthly' | 'yearly'>('yearly');

const services = [
  {
    icon: Code2,
    badge: 'High Performance',
    title: 'Custom Web & Enterprise App',
    desc: 'Pengembangan web app modern berskala tinggi dengan arsitektur scalable, arsitektur microservices/monolith clean code, dan integrasi API lengkap.',
    tags: ['Vue 3 / React', 'Laravel / Go', 'PostgreSQL', 'Docker'],
  },
  {
    icon: Layout,
    badge: 'Conversion Focused',
    title: 'UI/UX & Design System',
    desc: 'Perancangan antarmuka pengguna berbasis riset mendalam, sistem komponen konsisten, dan prototipe interaktif untuk meningkatkan conversion rate.',
    tags: ['Figma Pro', 'Design Tokens', 'Micro-interactions', 'WCAG 2.1'],
  },
  {
    icon: Smartphone,
    badge: 'Cross-Platform',
    title: 'Mobile App Development',
    desc: 'Aplikasi Android & iOS native-performance dengan siklus rilis terstandarisasi, offline-first sync, dan integrasi payment gateway terverifikasi.',
    tags: ['Flutter', 'React Native', 'Push Notification', 'Offline Cache'],
  },
  {
    icon: BarChart3,
    badge: 'Automation',
    title: 'ERP & Business Automation',
    desc: 'Digitalisasi alur operasional, manajemen stok, invoice multi-cabang, serta dashboard analitik realtime untuk efisiensi bisnis optimal.',
    tags: ['CRM / ERP', 'Real-time Analytics', 'Workflow Engine', 'Audit Logs'],
  },
];

const packages = [
  {
    name: 'Starter Business',
    badge: 'Untuk Bisnis Berkembang',
    price: 'Rp 6.500.000',
    originalPrice: 'Rp 8.500.000',
    desc: 'Paket esensial untuk presence digital profesional, perkenalan produk, dan lead generation terukur.',
    popular: false,
    features: [
      'Desain eksklusif (Hingga 5 Halaman responsif)',
      'Teknologi ultra-cepat (Vue/Inertia + Tailwind)',
      'CMS / Admin Panel ramah pengguna',
      'Optimasi SEO Basic & Google Analytics',
      'Integrasi WhatsApp & Form Kontak otomatis',
      'Free Domain .com & Cloud Hosting 1 Tahun',
      'Garansi bug fix & Maintenance 3 Bulan'
    ],
    ctaText: 'Pilih Paket Starter',
    ctaLink: '#konsultasi'
  },
  {
    name: 'Professional Pro',
    badge: 'Paling Diminati Klien',
    price: 'Rp 14.500.000',
    originalPrice: 'Rp 18.500.000',
    desc: 'Solusi lengkap untuk perusahaan atau brand yang butuh sistem dinamis, interaktif, dan kustomisasi tinggi.',
    popular: true,
    features: [
      'Semua fitur pada Paket Starter',
      'Desain kustom tanpa batas halaman (hingga 12)',
      'Integrasi Payment Gateway (Midtrans / Xendit)',
      'Database arsitektur tinggi & Real-time Dashboard',
      'Autentikasi Multi-role (Admin, Staff, Customer)',
      'Keamanan tingkat lanjut (SSL, CSRF, Rate Limiting)',
      'Prioritas Cloud Server NVMe High Speed',
      'Garansi & Maintenance Dedicated 6 Bulan'
    ],
    ctaText: 'Konsultasi Paket Pro',
    ctaLink: '#konsultasi'
  },
  {
    name: 'Enterprise Custom',
    badge: 'Solusi Skala Besar',
    price: 'Custom Quote',
    originalPrice: null,
    desc: 'Dirancang khusus untuk arsitektur kompleks, ERP perusahaan, sistem internal, dan integrasi omnichannel.',
    popular: false,
    features: [
      'Analisis kebutuhan sistem & blueprint mendalam',
      'SLA Uptime 99.9% dengan server enterprise dedicated',
      'API Integration (ERP eksisting, SAP, Core Banking)',
      'Multi-tenant & White-label Ready',
      'Penetration Testing & Security Audit Berkala',
      'Dedicated Tech Lead & Full Dev Team Support',
      'On-premise / Multi-Cloud Setup (AWS/GCP/Alibaba)',
      'Support 24/7 & Tim Darurat Khusus'
    ],
    ctaText: 'Jadwalkan Konsultasi VIP',
    ctaLink: '#konsultasi'
  }
];

const portfolioShowcase = [
  {
    title: 'Fintech Analytics Platform',
    category: 'Enterprise SaaS',
    metrics: '+240% Transaksi Bulanan',
    desc: 'Platform perbankan cerdas untuk monitoring cashflow multi-bank dengan visualisasi realtime dan enkripsi tingkat militer.',
    tech: ['Vue 3', 'Tailwind', 'Go', 'Redis'],
    image: 'linear-gradient(135deg, #01232B 0%, #023B44 100%)'
  },
  {
    title: 'Omnichannel ERP Retail',
    category: 'ERP & Supply Chain',
    metrics: '99.98% Akurasi Stok',
    desc: 'Sistem sentralisasi 45+ cabang gerai retail di Indonesia dengan sinkronisasi inventori offline-ke-online tanpa jeda.',
    tech: ['Laravel', 'PostgreSQL', 'Inertia', 'Docker'],
    image: 'linear-gradient(135deg, #022B31 0%, #011E26 100%)'
  },
  {
    title: 'Healthtech Telemedicine Web & Mobile',
    category: 'Medical Platform',
    metrics: '50.000+ Konsultasi Aktif',
    desc: 'Ekosistem rekam medis digital terenkripsi (HIPAA compliant) dengan modul booking dokter instan dan telemedicine.',
    tech: ['TypeScript', 'Flutter', 'Tailwind', 'WebRTC'],
    image: 'linear-gradient(135deg, #0A3B43 0%, #01232B 100%)'
  }
];

const testimonials = [
  {
    quote: "Desain dan performa website yang dibangun tim Synbarra benar-benar kelas dunia. Kecepatan loading meningkat drastis dan konversi prospek kami naik 180% dalam 2 bulan pertama.",
    author: "Budi Santoso",
    role: "CEO & Founder",
    company: "Nusantara Logistics Group",
    rating: 5
  },
  {
    quote: "Sistem ERP custom ini memangkas waktu kerja divisi finansial dan gudang kami hingga 70%. Kode yang rapi, transparan, dan tim support yang selalu proaktif membantu.",
    author: "Dian Paramita",
    role: "Director of Operations",
    company: "Apexindo Retail Prima",
    rating: 5
  },
  {
    quote: "Palet warna Synbarra (deep emerald & cyber cyan) memberikan kesan tech company yang sangat prestisius saat kami mempresentasikan platform ini ke jajaran investor.",
    author: "Farhan Hidayat",
    role: "Chief Product Officer",
    company: "PaySynch Indonesia",
    rating: 5
  }
];
</script>

<template>
  <Head>
    <title>Enterprise Agency & Tech Template | Synbarra Showcase</title>
    <meta name="description" content="Template website premium modern dengan aksen warna Synbarra (Deep Navy Teal & Cyber Mint) siap dipresentasikan dan dijual ke calon klien." />
  </Head>

  <div class="min-h-screen bg-[#011E26] text-slate-100 font-sans selection:bg-[#00F1DE] selection:text-[#011E26]">
    
    <!-- ========================================================================= -->
    <!-- 1. NAVIGATION BAR (MODERN FLOATING GLASS)                                -->
    <!-- ========================================================================= -->
    <header class="fixed top-0 inset-x-0 z-50 transition-all duration-300 backdrop-blur-md bg-[#01232B]/80 border-b border-[#0A3B43]/70">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
        <!-- Logo -->
        <a href="#hero" class="flex items-center gap-3 group">
          <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-[#00F1DE] to-[#04D2C5] flex items-center justify-center shadow-synbarra-glow group-hover:scale-105 transition-transform">
            <span class="text-[#011E26] font-black text-xl tracking-tight">S</span>
          </div>
          <div>
            <span class="font-extrabold text-xl tracking-tight text-white flex items-center gap-1">
              SYNBARRA <span class="text-[#00F1DE] text-sm font-semibold tracking-wider uppercase bg-[#00F1DE]/10 px-2 py-0.5 rounded-full border border-[#00F1DE]/20">Agency</span>
            </span>
            <p class="text-[11px] text-slate-400 font-mono tracking-wider">NEXT-GEN SOFTWARE HOUSE</p>
          </div>
        </a>

        <!-- Menu Desktop -->
        <nav class="hidden md:flex items-center gap-8 text-sm font-medium text-slate-300">
          <a href="#services" class="hover:text-[#00F1DE] transition-colors">Layanan</a>
          <a href="#showcase" class="hover:text-[#00F1DE] transition-colors">Portofolio</a>
          <a href="#pricing" class="hover:text-[#00F1DE] transition-colors">Paket Harga</a>
          <a href="#why-us" class="hover:text-[#00F1DE] transition-colors">Keunggulan</a>
          <a href="#testimonials" class="hover:text-[#00F1DE] transition-colors">Testimoni</a>
        </nav>

        <!-- CTA Action -->
        <div class="flex items-center gap-4">
          <a 
            href="#konsultasi" 
            class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full text-xs font-bold tracking-wide uppercase bg-gradient-to-r from-[#00F1DE] to-[#04D2C5] text-[#011E26] shadow-synbarra-glow hover:shadow-synbarra-glow-subtle hover:scale-[1.02] active:scale-[0.98] transition-all"
          >
            <span>Mulai Konsultasi</span>
            <ArrowRight class="w-4 h-4" />
          </a>
        </div>
      </div>
    </header>

    <!-- ========================================================================= -->
    <!-- 2. HERO SECTION                                                          -->
    <!-- ========================================================================= -->
    <section id="hero" class="relative pt-32 pb-20 lg:pt-44 lg:pb-32 overflow-hidden">
      <!-- Glow ambient background -->
      <div class="absolute -top-40 left-1/2 -translate-x-1/2 w-[700px] h-[700px] bg-[#00F1DE]/10 rounded-full blur-[160px] pointer-events-none"></div>
      <div class="absolute top-1/3 -right-20 w-[400px] h-[400px] bg-[#04D2C5]/10 rounded-full blur-[140px] pointer-events-none"></div>
      
      <!-- Tech Grid Lines -->
      <div class="absolute inset-0 bg-[linear-gradient(to_right,#0A3B43_1px,transparent_1px),linear-gradient(to_bottom,#0A3B43_1px,transparent_1px)] bg-[size:4rem_4rem] [mask-image:radial-gradient(ellipse_60%_50%_at_50%_0%,#000_70%,transparent_100%)] opacity-25"></div>

      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
        <!-- Badge -->
        <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-[#022B31] border border-[#00F1DE]/30 text-xs font-semibold text-[#00F1DE] shadow-synbarra-sm mb-8 animate-fade-in">
          <Sparkles class="w-3.5 h-3.5" />
          <span>Website & Custom App Khusus Kebutuhan Skalabilitas Bisnis</span>
        </div>

        <!-- Headline -->
        <h1 class="text-4xl sm:text-6xl lg:text-7xl font-extrabold tracking-tight text-white max-w-4xl mx-auto leading-tight sm:leading-none mb-6">
          Bangun Ekosistem Digital <br class="hidden sm:inline" />
          <span class="text-transparent bg-clip-text bg-gradient-to-r from-[#00F1DE] via-[#04D2C5] to-emerald-400">
            Cepat, Elegan, & Scalable.
          </span>
        </h1>

        <!-- Subtitle -->
        <p class="max-w-2xl mx-auto text-base sm:text-lg text-slate-300 font-normal leading-relaxed mb-10">
          Kami merancang dan mengembangkan platform website enterprise, sistem manajemen internal, serta mobile apps dengan standar teknologi modern untuk mengakselerasi omset dan kepercayaan klien Anda.
        </p>

        <!-- CTA Buttons -->
        <div class="flex flex-col sm:flex-row items-center justify-center gap-4 mb-16">
          <a 
            href="#pricing" 
            class="w-full sm:w-auto inline-flex items-center justify-center gap-3 px-8 py-4 rounded-xl text-sm font-bold bg-[#00F1DE] text-[#011E26] shadow-synbarra-glow hover:bg-[#04D2C5] hover:scale-105 transition-all"
          >
            <span>Eksplorasi Paket & Harga</span>
            <ArrowRight class="w-4 h-4" />
          </a>
          <a 
            href="#showcase" 
            class="w-full sm:w-auto inline-flex items-center justify-center gap-3 px-8 py-4 rounded-xl text-sm font-semibold bg-[#01232B] hover:bg-[#022B31] border border-[#0A3B43] text-white transition-all hover:border-[#00F1DE]/40"
          >
            <Monitor class="w-4 h-4 text-[#00F1DE]" />
            <span>Lihat Contoh Hasil Proyek</span>
          </a>
        </div>

        <!-- Live Metric Badges -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 max-w-4xl mx-auto pt-8 border-t border-[#0A3B43]/60 text-left">
          <div class="p-4 rounded-xl bg-[#01232B]/60 border border-[#0A3B43]/50">
            <p class="text-2xl sm:text-3xl font-extrabold text-[#00F1DE] font-mono">99.9%</p>
            <p class="text-xs text-slate-400 mt-1">Uptime SLA Guaranteed</p>
          </div>
          <div class="p-4 rounded-xl bg-[#01232B]/60 border border-[#0A3B43]/50">
            <p class="text-2xl sm:text-3xl font-extrabold text-white font-mono">&lt; 0.8s</p>
            <p class="text-xs text-slate-400 mt-1">Ultra Fast Core Web Vitals</p>
          </div>
          <div class="p-4 rounded-xl bg-[#01232B]/60 border border-[#0A3B43]/50">
            <p class="text-2xl sm:text-3xl font-extrabold text-[#00F1DE] font-mono">50+</p>
            <p class="text-xs text-slate-400 mt-1">Enterprise Clients Handled</p>
          </div>
          <div class="p-4 rounded-xl bg-[#01232B]/60 border border-[#0A3B43]/50">
            <p class="text-2xl sm:text-3xl font-extrabold text-white font-mono">100%</p>
            <p class="text-xs text-slate-400 mt-1">Custom Clean Architecture</p>
          </div>
        </div>
      </div>
    </section>

    <!-- ========================================================================= -->
    <!-- 3. SERVICES SECTION                                                      -->
    <!-- ========================================================================= -->
    <section id="services" class="py-24 bg-[#01232B] relative border-t border-[#0A3B43]">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto mb-16">
          <span class="text-xs font-mono uppercase tracking-widest text-[#00F1DE] bg-[#022B31] px-3 py-1 rounded-full border border-[#00F1DE]/20">
            Layanan Unggulan
          </span>
          <h2 class="text-3xl sm:text-5xl font-extrabold text-white mt-4 tracking-tight">
            Keahlian Teknis untuk Seluruh Siklus Produk Digital
          </h2>
          <p class="text-slate-300 mt-4 text-base">
            Mulai dari ideasi, rancangan visual pixel-perfect, arsitektur backend, hingga maintenance berkesinambungan.
          </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
          <div 
            v-for="(srv, idx) in services" 
            :key="idx" 
            class="p-8 rounded-2xl bg-[#022B31]/70 border border-[#0A3B43] hover:border-[#00F1DE]/50 transition-all duration-300 hover:-translate-y-1 group relative overflow-hidden"
          >
            <div class="absolute top-0 right-0 w-32 h-32 bg-[#00F1DE]/5 rounded-bl-full pointer-events-none group-hover:bg-[#00F1DE]/10 transition-colors"></div>
            
            <div class="flex items-center justify-between mb-6">
              <div class="w-14 h-14 rounded-xl bg-[#011E26] border border-[#0A3B43] flex items-center justify-center text-[#00F1DE] group-hover:border-[#00F1DE] transition-colors">
                <component :is="srv.icon" class="w-7 h-7" />
              </div>
              <span class="text-xs font-semibold px-3 py-1 rounded-full bg-[#011E26] text-[#00F1DE] border border-[#0A3B43]">
                {{ srv.badge }}
              </span>
            </div>

            <h3 class="text-xl font-bold text-white group-hover:text-[#00F1DE] transition-colors mb-3">
              {{ srv.title }}
            </h3>
            <p class="text-slate-300 text-sm leading-relaxed mb-6">
              {{ srv.desc }}
            </p>

            <div class="flex flex-wrap gap-2 pt-4 border-t border-[#0A3B43]/50">
              <span 
                v-for="tg in srv.tags" 
                :key="tg"
                class="text-[11px] font-mono px-2.5 py-1 rounded-md bg-[#011E26]/80 text-slate-300 border border-[#0A3B43]"
              >
                {{ tg }}
              </span>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- ========================================================================= -->
    <!-- 4. PORTFOLIO SHOWCASE SECTION                                            -->
    <!-- ========================================================================= -->
    <section id="showcase" class="py-24 bg-[#011E26] relative">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-16 gap-6">
          <div>
            <span class="text-xs font-mono uppercase tracking-widest text-[#00F1DE] bg-[#022B31] px-3 py-1 rounded-full border border-[#00F1DE]/20">
              Studi Kasus & Portofolio
            </span>
            <h2 class="text-3xl sm:text-5xl font-extrabold text-white mt-4 tracking-tight">
              Karya yang Membawa Dampak Nyata
            </h2>
          </div>
          <p class="text-slate-300 max-w-md text-sm">
            Setiap solusi dibangun untuk menyelesaikan problem bisnis spesifik dengan skalabilitas jangka panjang.
          </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
          <div 
            v-for="(item, idx) in portfolioShowcase" 
            :key="idx"
            class="rounded-2xl bg-[#01232B] border border-[#0A3B43] overflow-hidden flex flex-col group hover:border-[#00F1DE]/40 transition-all"
          >
            <!-- Visual mock card -->
            <div 
              class="h-52 w-full p-6 flex flex-col justify-between relative overflow-hidden" 
              :style="{ background: item.image }"
            >
              <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-white px-2.5 py-1 rounded-full bg-black/40 backdrop-blur-sm border border-white/10">
                  {{ item.category }}
                </span>
                <span class="text-xs font-mono font-bold text-[#00F1DE] bg-[#011E26]/80 px-2.5 py-1 rounded-full border border-[#00F1DE]/30">
                  {{ item.metrics }}
                </span>
              </div>
              <div class="flex items-center gap-2">
                <div class="w-3 h-3 rounded-full bg-[#00F1DE]"></div>
                <div class="w-3 h-3 rounded-full bg-emerald-400"></div>
                <div class="w-3 h-3 rounded-full bg-slate-600"></div>
              </div>
            </div>

            <div class="p-6 flex-1 flex flex-col justify-between">
              <div>
                <h3 class="text-lg font-bold text-white group-hover:text-[#00F1DE] transition-colors mb-2">
                  {{ item.title }}
                </h3>
                <p class="text-slate-300 text-xs leading-relaxed mb-6">
                  {{ item.desc }}
                </p>
              </div>

              <div class="flex items-center justify-between pt-4 border-t border-[#0A3B43]/50">
                <div class="flex gap-2">
                  <span v-for="t in item.tech" :key="t" class="text-[10px] font-mono text-slate-400">
                    #{{ t }}
                  </span>
                </div>
                <span class="text-xs font-bold text-[#00F1DE] flex items-center gap-1 group-hover:translate-x-1 transition-transform">
                  Detail <ChevronRight class="w-3.5 h-3.5" />
                </span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- ========================================================================= -->
    <!-- 5. PRICING & COMMERCIAL PACKAGES (SIAP JUAL KE KLIEN)                    -->
    <!-- ========================================================================= -->
    <section id="pricing" class="py-24 bg-[#01232B] relative border-t border-[#0A3B43]">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto mb-16">
          <span class="text-xs font-mono uppercase tracking-widest text-[#00F1DE] bg-[#022B31] px-3 py-1 rounded-full border border-[#00F1DE]/20">
            Penawaran Transparan
          </span>
          <h2 class="text-3xl sm:text-5xl font-extrabold text-white mt-4 tracking-tight">
            Pilihan Paket Investasi Digital
          </h2>
          <p class="text-slate-300 mt-4 text-base">
            Tanpa biaya tersembunyi. Dapatkan source code lengkap, full hak kepemilikan, dan garansi pasca rilis.
          </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-stretch">
          <div 
            v-for="(pkg, idx) in packages" 
            :key="idx"
            :class="[
              'rounded-3xl p-8 flex flex-col justify-between transition-all duration-300 relative',
              pkg.popular 
                ? 'bg-[#022B31] border-2 border-[#00F1DE] shadow-synbarra-glow lg:-translate-y-3' 
                : 'bg-[#011E26] border border-[#0A3B43] hover:border-[#00F1DE]/40'
            ]"
          >
            <!-- Popular Badge -->
            <div 
              v-if="pkg.popular" 
              class="absolute -top-4 left-1/2 -translate-x-1/2 bg-gradient-to-r from-[#00F1DE] to-[#04D2C5] text-[#011E26] text-xs font-black uppercase tracking-wider px-4 py-1.5 rounded-full shadow-lg"
            >
              Recommended Choice
            </div>

            <div>
              <div class="mb-6">
                <span class="text-xs font-semibold text-[#00F1DE] uppercase tracking-wider">{{ pkg.badge }}</span>
                <h3 class="text-2xl font-bold text-white mt-1">{{ pkg.name }}</h3>
                <p class="text-slate-300 text-xs mt-2 leading-relaxed">{{ pkg.desc }}</p>
              </div>

              <!-- Price -->
              <div class="mb-8 p-4 rounded-xl bg-[#01232B] border border-[#0A3B43]/80">
                <div class="flex items-baseline gap-2">
                  <span class="text-3xl font-extrabold text-white font-mono">{{ pkg.price }}</span>
                  <span v-if="pkg.originalPrice" class="text-xs text-slate-400 line-through font-mono">
                    {{ pkg.originalPrice }}
                  </span>
                </div>
                <p class="text-[11px] text-[#00F1DE] mt-1 font-mono">
                  {{ pkg.popular ? 'Termasuk Setup Server & Deployment' : 'All-in-one Solution' }}
                </p>
              </div>

              <!-- Feature List -->
              <ul class="space-y-3 mb-8">
                <li 
                  v-for="(ft, fIdx) in pkg.features" 
                  :key="fIdx"
                  class="flex items-start gap-3 text-xs text-slate-300 leading-normal"
                >
                  <CheckCircle2 class="w-4 h-4 text-[#00F1DE] shrink-0 mt-0.5" />
                  <span>{{ ft }}</span>
                </li>
              </ul>
            </div>

            <!-- CTA -->
            <a 
              :href="pkg.ctaLink"
              :class="[
                'w-full py-3.5 rounded-xl text-xs font-bold uppercase tracking-wider text-center transition-all flex items-center justify-center gap-2',
                pkg.popular
                  ? 'bg-[#00F1DE] text-[#011E26] hover:bg-[#04D2C5] shadow-synbarra-glow'
                  : 'bg-[#022B31] text-white hover:bg-[#00F1DE] hover:text-[#011E26] border border-[#0A3B43]'
              ]"
            >
              <span>{{ pkg.ctaText }}</span>
              <ArrowRight class="w-4 h-4" />
            </a>
          </div>
        </div>
      </div>
    </section>

    <!-- ========================================================================= -->
    <!-- 6. TESTIMONIALS SECTION                                                  -->
    <!-- ========================================================================= -->
    <section id="testimonials" class="py-24 bg-[#011E26] relative">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-2xl mx-auto mb-16">
          <span class="text-xs font-mono uppercase tracking-widest text-[#00F1DE] bg-[#022B31] px-3 py-1 rounded-full border border-[#00F1DE]/20">
            Bukti Kepercayaan
          </span>
          <h2 class="text-3xl sm:text-5xl font-extrabold text-white mt-4 tracking-tight">
            Apa Kata Mereka yang Telah Bermitra?
          </h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
          <div 
            v-for="(t, idx) in testimonials" 
            :key="idx"
            class="p-8 rounded-2xl bg-[#01232B] border border-[#0A3B43] flex flex-col justify-between"
          >
            <div>
              <div class="flex gap-1 text-[#00F1DE] mb-6">
                <Star v-for="i in t.rating" :key="i" class="w-4 h-4 fill-current" />
              </div>
              <p class="text-slate-300 text-sm leading-relaxed italic mb-8">
                "{{ t.quote }}"
              </p>
            </div>

            <div class="flex items-center gap-4 pt-4 border-t border-[#0A3B43]/60">
              <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-[#00F1DE] to-[#04D2C5] flex items-center justify-center font-bold text-[#011E26] text-sm">
                {{ t.author.charAt(0) }}
              </div>
              <div>
                <h4 class="text-sm font-bold text-white">{{ t.author }}</h4>
                <p class="text-xs text-slate-400">{{ t.role }} &bull; {{ t.company }}</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- ========================================================================= -->
    <!-- 7. LEAD / CONTACT CONSULTATION FORM                                      -->
    <!-- ========================================================================= -->
    <section id="konsultasi" class="py-24 bg-[#01232B] relative border-t border-[#0A3B43]">
      <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-gradient-to-b from-[#022B31] to-[#011E26] rounded-3xl p-8 sm:p-12 border border-[#00F1DE]/30 shadow-synbarra-lg">
          <div class="text-center max-w-2xl mx-auto mb-10">
            <span class="text-xs font-mono uppercase tracking-widest text-[#00F1DE] bg-[#01232B] px-3 py-1 rounded-full border border-[#00F1DE]/20">
              Mulai Proyek Anda
            </span>
            <h2 class="text-3xl sm:text-4xl font-extrabold text-white mt-4">
              Diskusikan Kebutuhan Bisnis Anda Bersama Tim Expert
            </h2>
            <p class="text-slate-300 text-sm mt-3">
              Konsultasi gratis 30 menit untuk mendiagnosa arsitektur sistem, estimasi budget, dan timeline delivery.
            </p>
          </div>

          <form @submit.prevent class="space-y-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
              <div>
                <label class="block text-xs font-semibold text-slate-300 mb-2">Nama Lengkap</label>
                <input 
                  type="text" 
                  placeholder="Contoh: Pratama Wijaya"
                  class="w-full bg-[#011E26] border border-[#0A3B43] rounded-xl px-4 py-3 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-[#00F1DE] transition-colors"
                />
              </div>
              <div>
                <label class="block text-xs font-semibold text-slate-300 mb-2">Email Bisnis / Instansi</label>
                <input 
                  type="email" 
                  placeholder="pratama@perusahaan.com"
                  class="w-full bg-[#011E26] border border-[#0A3B43] rounded-xl px-4 py-3 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-[#00F1DE] transition-colors"
                />
              </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
              <div>
                <label class="block text-xs font-semibold text-slate-300 mb-2">Nomor WhatsApp</label>
                <input 
                  type="text" 
                  placeholder="+62 812-xxxx-xxxx"
                  class="w-full bg-[#011E26] border border-[#0A3B43] rounded-xl px-4 py-3 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-[#00F1DE] transition-colors"
                />
              </div>
              <div>
                <label class="block text-xs font-semibold text-slate-300 mb-2">Estimasi Skala Anggaran</label>
                <select 
                  class="w-full bg-[#011E26] border border-[#0A3B43] rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-[#00F1DE] transition-colors"
                >
                  <option>&lt; Rp 10.000.000 (Starter)</option>
                  <option selected>Rp 10.000.000 - Rp 30.000.000 (Pro)</option>
                  <option>&gt; Rp 30.000.000 (Enterprise Custom)</option>
                </select>
              </div>
            </div>

            <div>
              <label class="block text-xs font-semibold text-slate-300 mb-2">Ringkasan Kebutuhan / Detail Proyek</label>
              <textarea 
                rows="4" 
                placeholder="Ceritakan gambaran sistem atau website yang ingin dibangun..."
                class="w-full bg-[#011E26] border border-[#0A3B43] rounded-xl px-4 py-3 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-[#00F1DE] transition-colors"
              ></textarea>
            </div>

            <button 
              type="submit" 
              class="w-full py-4 rounded-xl bg-gradient-to-r from-[#00F1DE] to-[#04D2C5] text-[#011E26] font-bold text-sm tracking-wide uppercase shadow-synbarra-glow hover:scale-[1.01] transition-transform flex items-center justify-center gap-2"
            >
              <Send class="w-4 h-4" />
              <span>Kirimkan Request Konsultasi Sekarang</span>
            </button>
          </form>
        </div>
      </div>
    </section>

    <!-- ========================================================================= -->
    <!-- 8. FOOTER                                                                -->
    <!-- ========================================================================= -->
    <footer class="bg-[#011E26] border-t border-[#0A3B43] py-12 text-slate-400 text-xs">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-between gap-6">
        <div class="flex items-center gap-3">
          <div class="w-8 h-8 rounded-lg bg-[#00F1DE] flex items-center justify-center text-[#011E26] font-bold">
            S
          </div>
          <span class="text-white font-bold tracking-tight text-sm">SYNBARRA TECHNOLOGIES</span>
        </div>

        <p>&copy; 2026 Synbarra Technologies. All rights reserved. High-Conversion Client Template.</p>

        <div class="flex gap-6">
          <a href="#" class="hover:text-[#00F1DE] transition-colors">Privacy Policy</a>
          <a href="#" class="hover:text-[#00F1DE] transition-colors">Terms of Service</a>
          <a href="#" class="hover:text-[#00F1DE] transition-colors">Security</a>
        </div>
      </div>
    </footer>

  </div>
</template>

