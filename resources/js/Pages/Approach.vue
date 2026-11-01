<script setup lang="ts">
import AppLayout from '@/Layouts/AppLayout.vue';
import Container from '@/Components/UI/Container.vue';
import { BRAND } from '@/config/brand';
import { Search, PenTool, Code2, Rocket, ArrowRight, CheckCircle2 } from '@lucide/vue';
</script>

<template>
  <AppLayout title="Alur Kerja & Metodologi | Synbarra Technologies">
    <!-- Header Banner -->
    <section class="bg-gradient-to-b from-[#EBF9F7] via-[#F4FCFA] to-white py-16 lg:py-24 relative overflow-hidden">
      <!-- Ambient Mint Glow -->
      <div class="absolute top-0 right-1/4 w-96 h-96 bg-[#00F1DE]/15 rounded-full blur-[130px] pointer-events-none"></div>

      <Container class="relative z-10 text-center max-w-3xl mx-auto">
        <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-white border border-[#A7EADF] text-[#008F7A] text-xs font-semibold tracking-wider uppercase mb-5 shadow-xs">
          <span class="w-2 h-2 rounded-full bg-[#00BFA5] animate-pulse"></span>
          Metodologi Kerja Terstruktur
        </div>

        <h1 class="text-3xl sm:text-5xl font-black text-[#0D2329] tracking-tight leading-tight">
          BAGAIMANA KAMI BEKERJA?
        </h1>

        <div class="w-16 h-1 bg-[#00BFA5] rounded-full mx-auto my-5"></div>

        <p class="text-base sm:text-lg text-slate-600 leading-relaxed max-w-2xl mx-auto">
          Proses pengembangan yang terstruktur, kolaboratif, dan transparan untuk memastikan keberhasilan implementasi solusi digital Anda.
        </p>
      </Container>
    </section>

    <!-- 4 Sequential Stages Section (Sesuai Post 07) -->
    <section class="py-16 bg-white relative">
      <Container>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-16">
          <div 
            v-for="(item, idx) in BRAND.workflow" 
            :key="item.step"
            class="flex flex-col justify-between p-7 rounded-3xl bg-white border border-[#D5F5EE] shadow-[0_10px_30px_-5px_rgba(0,191,165,0.06)] hover:border-[#00BFA5] transition-all duration-300"
          >
            <div>
              <div class="flex items-center justify-between mb-5">
                <span class="text-3xl font-black text-[#00BFA5] font-mono">
                  {{ item.step }}
                </span>
                <div class="w-12 h-12 rounded-2xl bg-[#E6FAF7] text-[#00BFA5] flex items-center justify-center shadow-xs">
                  <Search v-if="idx === 0" class="w-6 h-6 stroke-[2.2]" />
                  <PenTool v-else-if="idx === 1" class="w-6 h-6 stroke-[2.2]" />
                  <Code2 v-else-if="idx === 2" class="w-6 h-6 stroke-[2.2]" />
                  <Rocket v-else class="w-6 h-6 stroke-[2.2]" />
                </div>
              </div>

              <h2 class="text-xl font-bold text-[#0D2329] mb-3">
                {{ item.title }}
              </h2>

              <p class="text-sm text-slate-600 leading-relaxed">
                {{ item.description }}
              </p>
            </div>

            <div class="mt-6 pt-4 border-t border-slate-100 text-xs font-semibold text-[#008F7A]">
              Tahap {{ idx + 1 }} dari 4
            </div>
          </div>
        </div>

        <!-- Post 07 Graphic Showcase -->
        <div class="max-w-4xl mx-auto rounded-3xl overflow-hidden border border-[#D5F5EE] shadow-lg">
          <img
            src="/images/posts/Synbarra_Post_07.png"
            alt="Bagaimana Kami Bekerja - Synbarra Technologies"
            class="w-full h-auto object-cover"
            loading="lazy"
          />
        </div>
      </Container>
    </section>
  </AppLayout>
</template>
