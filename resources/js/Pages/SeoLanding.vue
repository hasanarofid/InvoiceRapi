<script setup lang="ts">
import { ref } from 'vue';
import { Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import Container from '@/Components/UI/Container.vue';
import { trackEvent } from '@/utils/analytics';
import { CheckCircle2, ArrowRight, ChevronDown, ChevronRight, Sparkles, ShieldCheck } from 'lucide-vue-next';

defineProps<{
  pageData: {
    badge: string;
    h1: string;
    meta_title: string;
    meta_description: string;
    lead: string;
    problem_title: string;
    problem: string;
    solution_title: string;
    solution: string;
    highlights: string[];
    example: {
      title: string;
      scenario: string;
      rows: Array<{ label: string; value: string }>;
    };
    faqs: Array<{ q: string; a: string }>;
  };
  sectionLabel: string;
  relatedLinks: Array<{ title: string; href: string }>;
}>();

const openFaq = ref<number | null>(0);
const toggleFaq = (idx: number) => {
  openFaq.value = openFaq.value === idx ? null : idx;
};
</script>

<template>
  <AppLayout :title="pageData.meta_title" :description="pageData.meta_description">
    <!-- Hero + Breadcrumb -->
    <section class="bg-gradient-to-b from-[#FAF8FF] via-white to-white pt-10 pb-14 border-b border-violet-100/70">
      <Container>
        <nav aria-label="Breadcrumb" class="flex items-center gap-1.5 text-xs text-slate-500 mb-6 flex-wrap">
          <Link href="/" class="hover:text-violet-700 font-medium">Beranda</Link>
          <ChevronRight class="w-3.5 h-3.5 text-slate-400" />
          <span class="text-slate-600 font-medium">{{ sectionLabel }}</span>
          <ChevronRight class="w-3.5 h-3.5 text-slate-400" />
          <span class="text-violet-700 font-bold truncate max-w-xs sm:max-w-md">{{ pageData.badge }}</span>
        </nav>

        <div class="max-w-3xl space-y-5">
          <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-violet-100/80 text-violet-700 text-xs font-extrabold">
            <Sparkles class="w-3.5 h-3.5" />
            {{ pageData.badge }}
          </span>

          <h1 class="text-3xl sm:text-4xl lg:text-5xl font-black text-slate-900 tracking-tight leading-[1.15]">
            {{ pageData.h1 }}
          </h1>

          <p class="text-base sm:text-lg text-slate-600 leading-relaxed">
            {{ pageData.lead }}
          </p>

          <div class="flex flex-wrap items-center gap-3 pt-2">
            <Link
              href="/register"
              @click="trackEvent('click_cta', { cta_location: 'seo_landing_hero', page_badge: pageData.badge })"
              class="inline-flex items-center gap-2 px-6 py-3.5 rounded-xl bg-violet-700 hover:bg-violet-800 text-white text-sm font-bold shadow-invoicerapi-md transition"
            >
              <span>Mulai Gratis 14 Hari</span>
              <ArrowRight class="w-4 h-4" />
            </Link>
            <Link
              href="/#harga"
              @click="trackEvent('view_pricing', { source: 'seo_landing_hero' })"
              class="inline-flex items-center gap-2 px-5 py-3.5 rounded-xl bg-white border border-slate-200 hover:border-violet-300 text-slate-700 text-sm font-bold transition"
            >
              Lihat Harga Promo (Mulai Rp55rb)
            </Link>
          </div>
        </div>
      </Container>
    </section>

    <!-- Problem & Solution -->
    <section class="py-14 bg-white">
      <Container>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <article class="p-6 sm:p-8 rounded-3xl bg-slate-50 border border-slate-200/80 space-y-3">
            <span class="text-[11px] font-black uppercase tracking-wider text-red-600">Masalah Umum</span>
            <h2 class="text-xl font-black text-slate-900">{{ pageData.problem_title }}</h2>
            <p class="text-sm text-slate-600 leading-relaxed">{{ pageData.problem }}</p>
          </article>

          <article class="p-6 sm:p-8 rounded-3xl bg-violet-50/70 border border-violet-200/70 space-y-3">
            <span class="text-[11px] font-black uppercase tracking-wider text-violet-700">Solusi InvoiceRapi</span>
            <h2 class="text-xl font-black text-slate-900">{{ pageData.solution_title }}</h2>
            <p class="text-sm text-slate-700 leading-relaxed">{{ pageData.solution }}</p>
          </article>
        </div>
      </Container>
    </section>

    <!-- Relevant Features & Real Example -->
    <section class="py-14 bg-[#FAF8FF] border-y border-violet-100/70">
      <Container>
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
          <div class="lg:col-span-6 space-y-4">
            <h2 class="text-2xl font-black text-slate-900">Fitur yang Membantu Operasional Anda</h2>
            <ul class="space-y-3">
              <li
                v-for="item in pageData.highlights"
                :key="item"
                class="flex items-start gap-3 p-4 rounded-2xl bg-white border border-slate-100 shadow-xs"
              >
                <CheckCircle2 class="w-5 h-5 text-emerald-600 shrink-0 mt-0.5" />
                <span class="text-sm font-medium text-slate-700">{{ item }}</span>
              </li>
            </ul>
          </div>

          <div class="lg:col-span-6 bg-white rounded-3xl border border-violet-200/80 p-6 sm:p-8 shadow-invoicerapi-sm space-y-4">
            <div class="flex items-center gap-2 text-xs font-extrabold text-violet-700 uppercase tracking-wider">
              <ShieldCheck class="w-4 h-4" />
              Contoh Skenario Nyata
            </div>
            <h3 class="text-lg font-black text-slate-900">{{ pageData.example.title }}</h3>
            <p class="text-sm text-slate-600 leading-relaxed">{{ pageData.example.scenario }}</p>

            <div class="rounded-2xl border border-slate-200 overflow-hidden">
              <table class="w-full text-left text-xs sm:text-sm border-collapse">
                <tbody class="divide-y divide-slate-100">
                  <tr v-for="row in pageData.example.rows" :key="row.label" class="bg-slate-50/50">
                    <td class="py-3 px-4 font-bold text-slate-600 w-40">{{ row.label }}</td>
                    <td class="py-3 px-4 font-bold text-slate-900">{{ row.value }}</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </Container>
    </section>

    <!-- FAQ -->
    <section class="py-14 bg-white">
      <Container>
        <div class="max-w-3xl mx-auto space-y-6">
          <div class="text-center space-y-2">
            <h2 class="text-2xl sm:text-3xl font-black text-slate-900">Pertanyaan yang Sering Diajukan</h2>
            <p class="text-sm text-slate-500">Jawaban ringkas mengenai fitur, pajak, dan paket InvoiceRapi.</p>
          </div>

          <div class="space-y-3">
            <div
              v-for="(faq, idx) in pageData.faqs"
              :key="faq.q"
              class="rounded-2xl border border-slate-200 bg-white overflow-hidden"
            >
              <button
                type="button"
                @click="toggleFaq(idx)"
                class="w-full px-5 py-4 text-left flex items-center justify-between gap-4 font-bold text-sm text-slate-900 hover:bg-slate-50 transition"
              >
                <span>{{ faq.q }}</span>
                <ChevronDown class="w-4 h-4 text-slate-400 shrink-0 transition-transform" :class="{ 'rotate-180 text-violet-700': openFaq === idx }" />
              </button>
              <div v-show="openFaq === idx" class="px-5 pb-4 text-sm text-slate-600 leading-relaxed border-t border-slate-100 pt-3">
                {{ faq.a }}
              </div>
            </div>
          </div>
        </div>
      </Container>
    </section>

    <!-- Related Internal Links + CTA -->
    <section class="py-14 bg-slate-50 border-t border-slate-100">
      <Container>
        <div class="space-y-8">
          <div class="space-y-3">
            <h2 class="text-lg font-black text-slate-900">Jelajahi Fitur &amp; Solusi Lainnya</h2>
            <div class="flex flex-wrap gap-2.5">
              <Link
                v-for="link in relatedLinks"
                :key="link.href"
                :href="link.href"
                class="px-3.5 py-2 rounded-xl bg-white border border-slate-200 hover:border-violet-400 hover:text-violet-700 text-xs font-bold text-slate-700 transition"
              >
                {{ link.title }}
              </Link>
            </div>
          </div>

          <div class="rounded-3xl bg-gradient-to-r from-violet-700 to-indigo-800 p-8 sm:p-10 text-white flex flex-col sm:flex-row sm:items-center justify-between gap-6">
            <div class="space-y-1.5">
              <h2 class="text-2xl font-black">Siap Merapikan Penagihan Bisnis Anda?</h2>
              <p class="text-sm text-violet-100">Daftar gratis dalam 30 detik, tanpa kartu kredit, langsung bisa buat invoice PDF resmi.</p>
            </div>
            <Link
              href="/register"
              @click="trackEvent('click_cta', { cta_location: 'seo_landing_bottom', page_badge: pageData.badge })"
              class="inline-flex items-center justify-center gap-2 px-6 py-3.5 rounded-xl bg-white text-violet-800 hover:bg-violet-50 text-sm font-black shadow-md transition shrink-0"
            >
              <span>Coba Gratis Sekarang</span>
              <ArrowRight class="w-4 h-4" />
            </Link>
          </div>
        </div>
      </Container>
    </section>
  </AppLayout>
</template>

