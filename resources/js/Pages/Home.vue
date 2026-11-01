<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import Container from '@/Components/UI/Container.vue';
import { BRAND } from '@/config/brand';
import { 
  Code2, 
  Globe, 
  Smartphone, 
  Layers, 
  Users, 
  CalendarCheck, 
  Network, 
  Palette, 
  Headset, 
  Sparkles, 
  CheckCircle2, 
  ArrowRight, 
  Search, 
  PenTool, 
  Rocket, 
  TrendingUp, 
  ChevronRight,
  ShieldCheck,
  Check
} from '@lucide/vue';

const iconMap: Record<string, any> = {
  Globe,
  Smartphone,
  Layers,
  Users,
  CalendarCheck,
  Network,
  Palette,
  Headset,
};

const getWaLink = (serviceTitle: string) => {
  const text = encodeURIComponent(`Halo Synbarra Technologies, saya tertarik dan ingin konsultasi mengenai layanan ${serviceTitle}.`);
  return `https://wa.me/${BRAND.contact.whatsapp}?text=${text}`;
};
</script>

<template>
  <AppLayout>
    <!-- ========================================================================= -->
    <!-- 1. HERO SECTION (Sesuai Postingan 01: Kenalan dengan Synbarra Teknologi)  -->
    <!-- ========================================================================= -->
    <section class="relative overflow-hidden bg-gradient-to-b from-[#EBF9F7] via-[#F4FCFA] to-white pt-12 pb-20 lg:pt-20 lg:pb-28">
      <!-- Ambient Mint Glows -->
      <div class="absolute top-0 right-1/4 w-[500px] h-[500px] bg-[#00F1DE]/15 rounded-full blur-[140px] pointer-events-none"></div>
      <div class="absolute bottom-0 left-10 w-[450px] h-[450px] bg-[#00BFA5]/10 rounded-full blur-[120px] pointer-events-none"></div>

      <Container class="relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
          <!-- Left Column: Copy & Highlights -->
          <div class="lg:col-span-7 flex flex-col items-start text-left">
            <!-- Overline Badge -->
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-white border border-[#A7EADF] text-[#008F7A] text-xs font-semibold uppercase tracking-wider shadow-xs mb-6">
              <span class="w-2 h-2 rounded-full bg-[#00BFA5] animate-pulse"></span>
              {{ BRAND.tagline }}
            </div>

            <!-- Primary Headline -->
            <h1 class="text-3xl sm:text-5xl lg:text-6xl font-black tracking-tight text-[#0D2329] leading-[1.15]">
              KENALAN DENGAN <br class="hidden sm:block" />
              <span class="text-transparent bg-clip-text bg-gradient-to-r from-[#0D2329] via-[#0A4D4F] to-[#00BFA5]">
                SYNBARRA TEKNOLOGI
              </span>
            </h1>

            <!-- Accent Teal Divider -->
            <div class="w-20 h-1.5 bg-gradient-to-r from-[#00BFA5] to-[#00F1DE] rounded-full my-5"></div>

            <!-- Subtitle Description -->
            <p class="text-base sm:text-lg text-slate-600 leading-relaxed max-w-2xl mb-8">
              {{ BRAND.subtagline }}
            </p>

            <!-- 3 Pill Feature Highlights (Sesuai Post 01) -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 w-full mb-8">
              <div class="flex items-center gap-3 bg-white/90 backdrop-blur-sm px-4 py-3 rounded-2xl border border-[#BCEEE5] shadow-xs">
                <div class="w-9 h-9 rounded-xl bg-[#E6FAF7] text-[#00BFA5] flex items-center justify-center shrink-0">
                  <Code2 class="w-5 h-5 stroke-[2.2]" />
                </div>
                <span class="text-xs font-bold text-[#0D2329] leading-tight">Pengembangan Sistem Custom</span>
              </div>

              <div class="flex items-center gap-3 bg-white/90 backdrop-blur-sm px-4 py-3 rounded-2xl border border-[#BCEEE5] shadow-xs">
                <div class="w-9 h-9 rounded-xl bg-[#E6FAF7] text-[#00BFA5] flex items-center justify-center shrink-0">
                  <Layers class="w-5 h-5 stroke-[2.2]" />
                </div>
                <span class="text-xs font-bold text-[#0D2329] leading-tight">Solusi Digital Bisnis</span>
              </div>

              <div class="flex items-center gap-3 bg-white/90 backdrop-blur-sm px-4 py-3 rounded-2xl border border-[#BCEEE5] shadow-xs">
                <div class="w-9 h-9 rounded-xl bg-[#E6FAF7] text-[#00BFA5] flex items-center justify-center shrink-0">
                  <TrendingUp class="w-5 h-5 stroke-[2.2]" />
                </div>
                <span class="text-xs font-bold text-[#0D2329] leading-tight">Dukung Pertumbuhan</span>
              </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-wrap items-center gap-4 w-full sm:w-auto">
              <a
                :href="BRAND.contact.whatsappUrl"
                target="_blank"
                rel="noopener"
                class="inline-flex items-center justify-center gap-3 px-7 py-3.5 rounded-full text-sm font-bold text-white bg-gradient-to-r from-[#0D2329] via-[#0A4D4F] to-[#00BFA5] hover:opacity-95 shadow-md hover:shadow-lg transition-all duration-200 transform hover:-translate-y-0.5"
              >
                <span>Konsultasi Sekarang</span>
                <span class="w-6 h-6 rounded-full bg-white/20 flex items-center justify-center">
                  <ArrowRight class="w-3.5 h-3.5" />
                </span>
              </a>

              <a
                href="#solusi"
                class="inline-flex items-center justify-center px-6 py-3.5 rounded-full text-sm font-bold text-[#0D2329] bg-white border border-[#BCEEE5] hover:bg-[#F0FDFB] hover:border-[#00BFA5] transition-all duration-200"
              >
                Lihat Solusi Kami
              </a>
            </div>
          </div>

          <!-- Right Column: Visual Showcase (Post 01 Graphic) -->
          <div class="lg:col-span-5 flex justify-center">
            <div class="relative w-full max-w-md lg:max-w-none">
              <!-- Background Decorative Layer -->
              <div class="absolute -inset-4 bg-gradient-to-tr from-[#00F1DE]/20 via-[#00BFA5]/15 to-transparent rounded-3xl blur-2xl pointer-events-none"></div>

              <!-- Main Post 01 Showcase Card -->
              <div class="relative bg-white p-3 rounded-3xl border border-[#C6F2EB] shadow-[0_20px_50px_rgba(0,191,165,0.12)] overflow-hidden">
                <img
                  src="/images/posts/Synbarra_Post_01.png"
                  alt="Kenalan Dengan Synbarra Teknologi"
                  class="w-full h-auto rounded-2xl object-cover"
                  loading="eager"
                />
              </div>
            </div>
          </div>
        </div>
      </Container>
    </section>

    <!-- ========================================================================= -->
    <!-- 2. MASALAH BISNIS (Sesuai Postingan 04)                                   -->
    <!-- ========================================================================= -->
    <section class="py-16 lg:py-24 bg-white relative">
      <Container>
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
          <!-- Left: Post 04 Graphic Card -->
          <div class="lg:col-span-5 order-2 lg:order-1 flex justify-center">
            <div class="relative w-full max-w-md rounded-3xl overflow-hidden border border-[#C6F2EB] shadow-lg">
              <img
                src="/images/posts/Synbarra_Post_04.png"
                alt="Masalah Bisnis Yang Sering Kami Temui"
                class="w-full h-auto object-cover rounded-3xl"
                loading="lazy"
              />
            </div>
          </div>

          <!-- Right: Problem Points & Solution Insight -->
          <div class="lg:col-span-7 order-1 lg:order-2 flex flex-col items-start">
            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-[#EBF9F7] text-[#008F7A] text-xs font-bold uppercase tracking-wider mb-4 border border-[#BCEEE5]">
              Tantangan Bisnis Nyata
            </div>
            
            <h2 class="text-2xl sm:text-4xl font-extrabold text-[#0D2329] leading-tight mb-4">
              MASALAH BISNIS YANG SERING KAMI TEMUI
            </h2>
            
            <div class="w-16 h-1.5 bg-[#00BFA5] rounded-full mb-6"></div>

            <p class="text-slate-600 mb-8 leading-relaxed">
              Banyak bisnis mengalami penurunan efisiensi dan kebocoran biaya operasional karena masih mengandalkan cara kerja konvensional dan sistem yang tidak terintegrasi.
            </p>

            <!-- 4 Pain Points List (Sesuai Post 04) -->
            <div class="space-y-4 w-full">
              <div 
                v-for="(point, idx) in BRAND.painPoints" 
                :key="idx"
                class="flex items-start gap-4 p-4 rounded-2xl bg-[#F7FCFB] border border-[#D5F5EE] transition-all hover:bg-white hover:shadow-sm"
              >
                <div class="w-8 h-8 rounded-full bg-gradient-to-tr from-[#00BFA5] to-[#00F1DE] text-white flex items-center justify-center shrink-0 mt-0.5 shadow-xs">
                  <Check class="w-4 h-4 stroke-[3]" />
                </div>
                <div>
                  <h3 class="text-sm sm:text-base font-bold text-[#0D2329]">
                    {{ point.title }}
                  </h3>
                  <p class="text-xs sm:text-sm text-slate-500 mt-1 leading-normal">
                    {{ point.description }}
                  </p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </Container>
    </section>

    <!-- ========================================================================= -->
    <!-- 3. MENGAPA MEMILIH SYNBARRA (Sesuai Postingan 02)                         -->
    <!-- ========================================================================= -->
    <section id="keunggulan" class="py-16 lg:py-24 bg-gradient-to-b from-[#F2FBF9] to-white relative">
      <Container>
        <div class="text-center max-w-3xl mx-auto mb-14">
          <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-white text-[#008F7A] text-xs font-bold uppercase tracking-wider mb-4 border border-[#BCEEE5] shadow-xs">
            Nilai & Keunggulan Kami
          </div>
          <h2 class="text-2xl sm:text-4xl font-extrabold text-[#0D2329] leading-tight mb-4">
            MENGAPA MEMILIH SYNBARRA?
          </h2>
          <div class="w-16 h-1.5 bg-[#00BFA5] rounded-full mx-auto mb-5"></div>
          <p class="text-slate-600 text-base leading-relaxed">
            Partner teknologi yang tepat untuk mewujudkan solusi digital bisnis Anda.
          </p>
        </div>

        <!-- 4 Pillars Grid (Sesuai Post 02) -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 max-w-5xl mx-auto">
          <div 
            v-for="(adv, idx) in BRAND.advantages" 
            :key="idx"
            class="flex items-start gap-5 p-6 rounded-3xl bg-white border border-[#D5F5EE] shadow-[0_10px_30px_-5px_rgba(0,191,165,0.06)] hover:border-[#00BFA5] transition-all duration-300"
          >
            <div class="w-13 h-13 rounded-2xl bg-[#E6FAF7] text-[#00BFA5] flex items-center justify-center shrink-0 shadow-xs">
              <Sparkles v-if="idx === 0" class="w-7 h-7 stroke-[2.2]" />
              <Users v-else-if="idx === 1" class="w-7 h-7 stroke-[2.2]" />
              <Layers v-else-if="idx === 2" class="w-7 h-7 stroke-[2.2]" />
              <Headset v-else class="w-7 h-7 stroke-[2.2]" />
            </div>
            <div>
              <h3 class="text-lg font-bold text-[#0D2329] mb-2">
                {{ adv.title }}
              </h3>
              <p class="text-sm text-slate-600 leading-relaxed">
                {{ adv.description }}
              </p>
            </div>
          </div>
        </div>
      </Container>
    </section>

    <!-- ========================================================================= -->
    <!-- 4. CUSTOM DEVELOPMENT SHOWCASE (Sesuai Postingan 05)                      -->
    <!-- ========================================================================= -->
    <section class="py-16 lg:py-20 bg-white relative">
      <Container>
        <div class="bg-gradient-to-br from-[#0D2329] via-[#0A3D42] to-[#01232B] rounded-3xl p-8 sm:p-12 lg:p-16 text-white shadow-xl relative overflow-hidden">
          <!-- Ambient Glows -->
          <div class="absolute top-0 right-0 w-96 h-96 bg-[#00F1DE]/15 rounded-full blur-3xl pointer-events-none"></div>

          <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-center relative z-10">
            <div class="lg:col-span-7 flex flex-col items-start">
              <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-[#00F1DE]/15 border border-[#00F1DE]/30 text-[#00F1DE] text-xs font-mono mb-4">
                Core Competence
              </span>
              <h2 class="text-3xl sm:text-5xl font-black tracking-tight text-white leading-tight mb-4">
                CUSTOM DEVELOPMENT
              </h2>
              <h3 class="text-lg sm:text-xl font-bold text-[#00F1DE] mb-5">
                Solusi Digital Sesuai Alur Bisnis Anda.
              </h3>
              <p class="text-slate-300 leading-relaxed text-sm sm:text-base mb-8 max-w-xl">
                Kami merancang dan membangun aplikasi atau sistem yang disesuaikan dengan proses, kebutuhan, dan tujuan bisnis Anda tanpa batasan template kaku.
              </p>
              <a
                :href="BRAND.contact.whatsappUrl"
                target="_blank"
                rel="noopener"
                class="inline-flex items-center gap-3 px-6 py-3 rounded-full text-xs sm:text-sm font-bold text-[#0D2329] bg-gradient-to-r from-[#00F1DE] to-[#00BFA5] hover:opacity-90 shadow-md transition-all"
              >
                <span>Konsultasikan Kebutuhan Custom</span>
                <ArrowRight class="w-4 h-4" />
              </a>
            </div>

            <!-- Post 05 Mockup Graphic -->
            <div class="lg:col-span-5 flex justify-center">
              <div class="relative w-full max-w-md rounded-2xl overflow-hidden border border-white/10 shadow-2xl">
                <img
                  src="/images/posts/Synbarra_Post_05.png"
                  alt="Custom Development Solusi Digital Sesuai Alur Bisnis"
                  class="w-full h-auto object-cover rounded-2xl"
                  loading="lazy"
                />
              </div>
            </div>
          </div>
        </div>
      </Container>
    </section>

    <!-- ========================================================================= -->
    <!-- 5. SOLUSI KAMI (8 Kategori Solusi Digital - Sesuai Postingan 06 & 03)      -->
    <!-- ========================================================================= -->
    <section id="solusi" class="py-16 lg:py-24 bg-gradient-to-b from-white via-[#F4FCFA] to-white relative">
      <Container>
        <div class="text-center max-w-3xl mx-auto mb-16">
          <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-[#EBF9F7] text-[#008F7A] text-xs font-bold uppercase tracking-wider mb-4 border border-[#BCEEE5]">
            Layanan Komprehensif
          </div>
          <h2 class="text-2xl sm:text-4xl font-extrabold text-[#0D2329] leading-tight mb-4">
            SOLUSI KAMI
          </h2>
          <div class="w-16 h-1.5 bg-[#00BFA5] rounded-full mx-auto mb-5"></div>
          <p class="text-slate-600 text-base leading-relaxed">
            Berbagai solusi digital yang dapat disesuaikan dengan kebutuhan bisnis Anda.
          </p>
        </div>

        <!-- 8 Solution Cards Grid (Sesuai Post 06) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
          <div 
            v-for="service in BRAND.services" 
            :key="service.id"
            class="flex flex-col justify-between p-6 rounded-3xl bg-white border border-[#D5F5EE] shadow-[0_8px_25px_-5px_rgba(0,191,165,0.06)] hover:border-[#00BFA5] hover:shadow-md transition-all duration-300 group"
          >
            <div>
              <div class="w-14 h-14 rounded-2xl bg-[#E6FAF7] group-hover:bg-[#00BFA5] text-[#00BFA5] group-hover:text-white flex items-center justify-center mb-5 transition-colors duration-200 shadow-xs">
                <component :is="iconMap[service.icon] || Globe" class="w-7 h-7 stroke-[2.2]" />
              </div>
              <span class="text-[11px] font-bold text-[#008F7A] uppercase tracking-wider block mb-1">
                {{ service.category }}
              </span>
              <h3 class="text-lg font-bold text-[#0D2329] mb-2 leading-snug">
                {{ service.title }}
              </h3>
              <p class="text-xs sm:text-sm text-slate-600 leading-relaxed mb-6">
                {{ service.description }}
              </p>
            </div>

            <a
              :href="getWaLink(service.title)"
              target="_blank"
              rel="noopener"
              class="inline-flex items-center gap-1.5 text-xs font-bold text-[#00BFA5] hover:text-[#0D2329] transition-colors pt-3 border-t border-slate-100"
            >
              <span>Konsultasi Solusi Ini</span>
              <ArrowRight class="w-3.5 h-3.5" />
            </a>
          </div>
        </div>
      </Container>
    </section>

    <!-- ========================================================================= -->
    <!-- 6. BAGAIMANA KAMI BEKERJA (Sesuai Postingan 07)                           -->
    <!-- ========================================================================= -->
    <section class="py-16 lg:py-24 bg-gradient-to-b from-[#F2FBF9] to-white relative">
      <Container>
        <div class="text-center max-w-3xl mx-auto mb-16">
          <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-white text-[#008F7A] text-xs font-bold uppercase tracking-wider mb-4 border border-[#BCEEE5] shadow-xs">
            Metodologi Kerja
          </div>
          <h2 class="text-2xl sm:text-4xl font-extrabold text-[#0D2329] leading-tight mb-4">
            BAGAIMANA KAMI BEKERJA?
          </h2>
          <div class="w-16 h-1.5 bg-[#00BFA5] rounded-full mx-auto mb-5"></div>
          <p class="text-slate-600 text-base leading-relaxed">
            Proses pengembangan yang terstruktur, kolaboratif, dan transparan.
          </p>
        </div>

        <!-- 4 Sequential Steps (Sesuai Post 07) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 relative">
          <div 
            v-for="(item, idx) in BRAND.workflow" 
            :key="item.step"
            class="relative flex flex-col p-6 rounded-3xl bg-white border border-[#D5F5EE] shadow-[0_8px_25px_-5px_rgba(0,191,165,0.06)] hover:border-[#00BFA5] transition-all"
          >
            <!-- Step Number Badge -->
            <div class="flex items-center justify-between mb-4">
              <span class="text-2xl font-black text-[#00BFA5] font-mono">
                {{ item.step }}
              </span>
              <div class="w-10 h-10 rounded-xl bg-[#E6FAF7] text-[#00BFA5] flex items-center justify-center">
                <Search v-if="idx === 0" class="w-5 h-5 stroke-[2.2]" />
                <PenTool v-else-if="idx === 1" class="w-5 h-5 stroke-[2.2]" />
                <Code2 v-else-if="idx === 2" class="w-5 h-5 stroke-[2.2]" />
                <Rocket v-else class="w-5 h-5 stroke-[2.2]" />
              </div>
            </div>

            <h3 class="text-lg font-bold text-[#0D2329] mb-2">
              {{ item.title }}
            </h3>
            <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
              {{ item.description }}
            </p>
          </div>
        </div>
      </Container>
    </section>

    <!-- ========================================================================= -->
    <!-- 7. KEAHLIAN KAMI (Tech Stack - Sesuai Postingan 08)                        -->
    <!-- ========================================================================= -->
    <section class="py-16 lg:py-24 bg-white relative">
      <Container>
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
          <div class="lg:col-span-5 flex flex-col items-start">
            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-[#EBF9F7] text-[#008F7A] text-xs font-bold uppercase tracking-wider mb-4 border border-[#BCEEE5]">
              Teknologi Modern
            </div>
            <h2 class="text-2xl sm:text-4xl font-extrabold text-[#0D2329] leading-tight mb-4">
              KEAHLIAN KAMI
            </h2>
            <div class="w-16 h-1.5 bg-[#00BFA5] rounded-full mb-6"></div>
            <p class="text-slate-600 leading-relaxed mb-6">
              Teknologi modern untuk membangun solusi yang andal, aman, dan scalable.
            </p>
            <div class="p-5 rounded-2xl bg-[#F0FDFB] border border-[#BCEEE5] text-xs sm:text-sm text-slate-700 leading-relaxed">
              <span class="font-bold text-[#0D2329]">Standar Kualitas Kode:</span> Kami menerapkan arsitektur bersih (*Clean Architecture*), *strict typing*, keamanan otentikasi ketat, serta pengujian berkala untuk menjamin reliabilitas jangka panjang.
            </div>
          </div>

          <!-- 8 Tech Badges Grid (Sesuai Post 08) -->
          <div class="lg:col-span-7 grid grid-cols-2 sm:grid-cols-4 gap-4">
            <div 
              v-for="tech in BRAND.techStack" 
              :key="tech.name"
              class="group flex flex-col items-center text-center p-5 rounded-2xl bg-white border border-[#D5F5EE] shadow-xs hover:border-[#00BFA5] hover:shadow-md transition-all duration-200"
            >
              <div class="w-14 h-14 rounded-2xl bg-[#F8FCFB] border border-[#D5F5EE] p-2.5 flex items-center justify-center mb-3 shadow-2xs group-hover:scale-110 transition-transform duration-200">
                <img 
                  :src="tech.logo" 
                  :alt="tech.name" 
                  class="w-full h-full object-contain"
                  loading="lazy"
                />
              </div>
              <h3 class="text-sm font-bold text-[#0D2329] group-hover:text-[#008F7A] transition-colors">
                {{ tech.name }}
              </h3>
              <span class="text-[11px] text-slate-500 mt-1 line-clamp-1">
                {{ tech.role }}
              </span>
            </div>
          </div>
        </div>
      </Container>
    </section>

    <!-- ========================================================================= -->
    <!-- 8. CALL TO ACTION (Sesuai Postingan 09)                                    -->
    <!-- ========================================================================= -->
    <section class="py-16 lg:py-24 bg-gradient-to-b from-white to-[#EBF9F7] relative">
      <Container>
        <div class="bg-gradient-to-br from-[#0D2329] via-[#0A4D4F] to-[#011E26] text-white rounded-3xl p-8 sm:p-14 lg:p-16 shadow-2xl relative overflow-hidden">
          <!-- Ambient Glows -->
          <div class="absolute -top-24 -right-24 w-96 h-96 bg-[#00F1DE]/20 rounded-full blur-3xl pointer-events-none"></div>
          <div class="absolute -bottom-24 -left-24 w-96 h-96 bg-[#00BFA5]/20 rounded-full blur-3xl pointer-events-none"></div>

          <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-center relative z-10">
            <div class="lg:col-span-8 flex flex-col items-start">
              <span class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-[#00F1DE]/15 border border-[#00F1DE]/30 text-[#00F1DE] text-xs font-semibold mb-5">
                <span class="w-2 h-2 rounded-full bg-[#00F1DE] animate-pulse"></span>
                Partner Teknologi Terpercaya
              </span>

              <h2 class="text-3xl sm:text-5xl font-black text-white tracking-tight leading-tight mb-5">
                YUK, DISKUSIKAN KEBUTUHAN ANDA!
              </h2>

              <p class="text-slate-200 text-sm sm:text-base leading-relaxed max-w-2xl mb-8">
                Punya ide proyek, kebutuhan sistem, atau tantangan digital di bisnis Anda? Kami siap menjadi partner teknologi untuk mewujudkannya.
              </p>

              <!-- CTA Button (Sesuai Post 09) -->
              <div class="flex flex-wrap items-center gap-4 mb-8">
                <a
                  :href="BRAND.contact.whatsappUrl"
                  target="_blank"
                  rel="noopener"
                  class="inline-flex items-center gap-3 px-8 py-4 rounded-full text-sm sm:text-base font-bold text-[#0D2329] bg-gradient-to-r from-[#00F1DE] to-[#00BFA5] hover:opacity-95 shadow-lg transition-all duration-200 transform hover:-translate-y-0.5"
                >
                  <ArrowRight class="w-5 h-5 text-[#0D2329]" />
                  <span>Konsultasi Sekarang</span>
                </a>

                <Link
                  href="/contact"
                  class="inline-flex items-center px-6 py-4 rounded-full text-sm font-semibold text-white bg-white/10 hover:bg-white/15 border border-white/20 transition-all"
                >
                  Kirim Detail Kebutuhan
                </Link>
              </div>

              <!-- Quick Contact Badges (Sesuai Post 09) -->
              <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 w-full pt-6 border-t border-white/10 text-xs text-slate-300">
                <div class="flex items-center gap-2">
                  <Globe class="w-4 h-4 text-[#00F1DE]" />
                  <span>{{ BRAND.domain }}</span>
                </div>
                <div class="flex items-center gap-2">
                  <span class="text-[#00F1DE] font-bold">@</span>
                  <span>{{ BRAND.contact.instagram }}</span>
                </div>
                <div class="flex items-center gap-2">
                  <Smartphone class="w-4 h-4 text-[#00F1DE]" />
                  <span>{{ BRAND.contact.whatsappDisplay }}</span>
                </div>
                <div class="flex items-center gap-2 truncate">
                  <span class="text-[#00F1DE]">✉</span>
                  <span class="truncate">{{ BRAND.contact.email }}</span>
                </div>
              </div>
            </div>

            <!-- Post 09 Mockup Graphic -->
            <div class="lg:col-span-4 flex justify-center">
              <div class="relative w-full max-w-sm rounded-3xl overflow-hidden border border-white/15 shadow-2xl">
                <img
                  src="/images/posts/Synbarra_Post_09.png"
                  alt="Yuk, Diskusikan Kebutuhan Anda!"
                  class="w-full h-auto object-cover rounded-3xl"
                  loading="lazy"
                />
              </div>
            </div>
          </div>
        </div>
      </Container>
    </section>
  </AppLayout>
</template>
