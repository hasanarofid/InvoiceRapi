<script setup lang="ts">
import AppLayout from '@/Layouts/AppLayout.vue';
import Container from '@/Components/UI/Container.vue';
import { BRAND } from '@/config/brand';
import { Sparkles, Users, Layers, Headset, ArrowRight, CheckCircle2 } from '@lucide/vue';
</script>

<template>
  <AppLayout title="Tentang Kami | Synbarra Technologies">
    <!-- Header Banner -->
    <section class="bg-gradient-to-b from-[#EBF9F7] via-[#F4FCFA] to-white py-16 lg:py-24 relative overflow-hidden">
      <!-- Ambient Mint Glow -->
      <div class="absolute top-0 right-1/4 w-96 h-96 bg-[#00F1DE]/15 rounded-full blur-[130px] pointer-events-none"></div>

      <Container class="relative z-10 text-center max-w-3xl mx-auto">
        <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-white border border-[#A7EADF] text-[#008F7A] text-xs font-semibold tracking-wider uppercase mb-5 shadow-xs">
          <span class="w-2 h-2 rounded-full bg-[#00BFA5] animate-pulse"></span>
          Profil & Nilai Perusahaan
        </div>

        <h1 class="text-3xl sm:text-5xl font-black text-[#0D2329] tracking-tight leading-tight">
          KENALAN DENGAN SYNBARRA
        </h1>

        <div class="w-16 h-1 bg-[#00BFA5] rounded-full mx-auto my-5"></div>

        <p class="text-base sm:text-lg text-slate-600 leading-relaxed max-w-2xl mx-auto">
          {{ BRAND.subtagline }}
        </p>
      </Container>
    </section>

    <!-- Company Bio & Mission Section -->
    <section class="py-16 bg-white relative">
      <Container>
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center mb-20">
          <div class="lg:col-span-7 space-y-6">
            <span class="text-xs font-bold uppercase tracking-wider text-[#008F7A]">
              Tentang Synbarra Technologies
            </span>
            <h2 class="text-2xl sm:text-4xl font-extrabold text-[#0D2329] leading-tight">
              Partner Teknologi yang Tepat untuk Mewujudkan Solusi Digital Bisnis Anda.
            </h2>
            <div class="w-16 h-1 bg-[#00BFA5] rounded-full"></div>
            <p class="text-slate-600 text-base leading-relaxed">
              {{ BRAND.description }}
            </p>
            <p class="text-slate-600 text-sm leading-relaxed">
              Kami percaya bahwa teknologi terbaik adalah teknologi yang benar-benar memecahkan kendala nyata di lapangan, mudah dioperasikan oleh tim Anda, dan fleksibel mengikuti perkembangan skala bisnis Anda di masa depan.
            </p>
          </div>

          <!-- Post 02 Graphic -->
          <div class="lg:col-span-5 flex justify-center">
            <div class="rounded-3xl overflow-hidden border border-[#D5F5EE] shadow-lg">
              <img
                src="/images/posts/Synbarra_Post_02.png"
                alt="Mengapa Memilih Synbarra?"
                class="w-full h-auto object-cover"
                loading="lazy"
              />
            </div>
          </div>
        </div>

        <!-- 4 Pillars Section -->
        <div class="text-center max-w-3xl mx-auto mb-12">
          <h2 class="text-2xl sm:text-3xl font-extrabold text-[#0D2329]">
            MENGAPA MEMILIH SYNBARRA?
          </h2>
          <div class="w-12 h-1 bg-[#00BFA5] rounded-full mx-auto my-4"></div>
          <p class="text-sm text-slate-600">
            Nilai dasar yang kami bawa dalam setiap baris kode dan arsitektur sistem.
          </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 max-w-5xl mx-auto mb-16">
          <div 
            v-for="(adv, idx) in BRAND.advantages" 
            :key="idx"
            class="flex items-start gap-5 p-6 rounded-3xl bg-white border border-[#D5F5EE] shadow-xs hover:border-[#00BFA5] transition-all"
          >
            <div class="w-13 h-13 rounded-2xl bg-[#E6FAF7] text-[#00BFA5] flex items-center justify-center shrink-0">
              <Sparkles v-if="idx === 0" class="w-6 h-6 stroke-[2.2]" />
              <Users v-else-if="idx === 1" class="w-6 h-6 stroke-[2.2]" />
              <Layers v-else-if="idx === 2" class="w-6 h-6 stroke-[2.2]" />
              <Headset v-else class="w-6 h-6 stroke-[2.2]" />
            </div>
            <div>
              <h3 class="text-base font-bold text-[#0D2329] mb-1.5">
                {{ adv.title }}
              </h3>
              <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                {{ adv.description }}
              </p>
            </div>
          </div>
        </div>

        <!-- Tech Stack Post 08 Graphic Showcase -->
        <div class="max-w-4xl mx-auto rounded-3xl overflow-hidden border border-[#D5F5EE] shadow-lg">
          <img
            src="/images/posts/Synbarra_Post_08.png"
            alt="Keahlian Kami - Synbarra Technologies"
            class="w-full h-auto object-cover"
            loading="lazy"
          />
        </div>
      </Container>
    </section>
  </AppLayout>
</template>
