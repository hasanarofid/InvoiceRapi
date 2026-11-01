<script setup lang="ts">
import AppLayout from '@/Layouts/AppLayout.vue';
import Container from '@/Components/UI/Container.vue';
import { BRAND } from '@/config/brand';
import { 
  Globe, 
  Smartphone, 
  Layers, 
  Users, 
  CalendarCheck, 
  Network, 
  Palette, 
  Headset, 
  ArrowRight,
  CheckCircle2
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
  const text = encodeURIComponent(`Halo Synbarra Technologies, saya ingin konsultasi mengenai layanan ${serviceTitle}.`);
  return `https://wa.me/${BRAND.contact.whatsapp}?text=${text}`;
};
</script>

<template>
  <AppLayout 
    title="Layanan Pengembangan Sistem & Solusi Digital | Synbarra Technologies"
    description="8 Solusi digital unggulan Synbarra Technologies: Website Company Profile, Aplikasi Mobile iOS/Android, Sistem Bisnis ERP, CRM, HRIS & Payroll, Integrasi API, UI/UX, dan Maintenance."
  >
    <!-- Header Banner -->
    <section class="bg-gradient-to-b from-[#EBF9F7] via-[#F4FCFA] to-white py-16 lg:py-24 relative overflow-hidden">
      <!-- Ambient Mint Glow -->
      <div class="absolute top-0 right-1/4 w-96 h-96 bg-[#00F1DE]/15 rounded-full blur-[130px] pointer-events-none"></div>

      <Container class="relative z-10 text-center max-w-3xl mx-auto">
        <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-white border border-[#A7EADF] text-[#008F7A] text-xs font-semibold tracking-wider uppercase mb-5 shadow-xs">
          <span class="w-2 h-2 rounded-full bg-[#00BFA5] animate-pulse"></span>
          Software House & Custom Development
        </div>

        <h1 class="text-3xl sm:text-5xl font-black text-[#0D2329] tracking-tight leading-tight">
          LAYANAN KAMI
        </h1>

        <div class="w-16 h-1 bg-[#00BFA5] rounded-full mx-auto my-5"></div>

        <p class="text-base sm:text-lg text-slate-600 leading-relaxed max-w-2xl mx-auto">
          Beragam layanan pengembangan sistem untuk mendukung kebutuhan dan pertumbuhan bisnis Anda.
        </p>
      </Container>
    </section>

    <!-- 8 Services Grid Section -->
    <section class="py-16 bg-white relative">
      <Container>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
          <div 
            v-for="service in BRAND.services" 
            :key="service.id"
            :id="service.id"
            class="flex flex-col justify-between p-7 rounded-3xl bg-white border border-[#D5F5EE] shadow-[0_10px_30px_-5px_rgba(0,191,165,0.06)] hover:border-[#00BFA5] hover:shadow-lg transition-all duration-300 group"
          >
            <div>
              <div class="w-14 h-14 rounded-2xl bg-[#E6FAF7] group-hover:bg-[#00BFA5] text-[#00BFA5] group-hover:text-white flex items-center justify-center mb-5 transition-colors duration-200 shadow-xs">
                <component :is="iconMap[service.icon] || Globe" class="w-7 h-7 stroke-[2.2]" />
              </div>
              <span class="text-xs font-bold text-[#008F7A] uppercase tracking-wider block mb-1">
                {{ service.category }}
              </span>
              <h2 class="text-xl font-bold text-[#0D2329] mb-3 leading-snug">
                {{ service.title }}
              </h2>
              <p class="text-sm text-slate-600 leading-relaxed mb-6">
                {{ service.description }}
              </p>
            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
              <a
                :href="getWaLink(service.title)"
                target="_blank"
                rel="noopener"
                class="inline-flex items-center gap-2 text-xs font-bold text-[#00BFA5] group-hover:text-[#0D2329] transition-colors"
              >
                <span>Konsultasi Solusi</span>
                <ArrowRight class="w-3.5 h-3.5" />
              </a>
            </div>
          </div>
        </div>

        <!-- Custom Development Banner (Post 05) -->
        <div class="mt-16 bg-gradient-to-br from-[#0D2329] via-[#0A4D4F] to-[#011E26] rounded-3xl p-8 sm:p-12 text-white shadow-xl flex flex-col md:flex-row items-center justify-between gap-8">
          <div class="max-w-xl">
            <span class="text-xs font-mono text-[#00F1DE] uppercase tracking-wider">Custom Development</span>
            <h3 class="text-2xl sm:text-3xl font-extrabold text-white mt-1 mb-3">
              Solusi Digital Sesuai Alur Bisnis Anda.
            </h3>
            <p class="text-slate-300 text-sm leading-relaxed">
              Kami merancang dan membangun aplikasi atau sistem yang disesuaikan dengan proses, kebutuhan, dan tujuan bisnis Anda.
            </p>
          </div>
          <a
            :href="BRAND.contact.whatsappUrl"
            target="_blank"
            rel="noopener"
            class="px-7 py-3.5 rounded-full font-bold text-sm text-[#0D2329] bg-gradient-to-r from-[#00F1DE] to-[#00BFA5] hover:opacity-95 shadow-md shrink-0 transition-all"
          >
            Konsultasi Sekarang &rarr;
          </a>
        </div>
      </Container>
    </section>
  </AppLayout>
</template>
