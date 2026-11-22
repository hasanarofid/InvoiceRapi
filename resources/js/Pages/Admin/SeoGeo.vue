<script setup lang="ts">
import { computed } from 'vue';
import { Head, router, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { formatDateTimeID } from '@/utils/format';
import {
  Globe,
  Search,
  Bot,
  Share2,
  TrendingUp,
  CheckCircle2,
  AlertCircle,
  ExternalLink,
  Save,
  FileText,
} from '@lucide/vue';

const props = defineProps<{
  days: number;
  stats: {
    total_visits: number;
    human_visits: number;
    google_traffic: number;
    meta_traffic: number;
    ai_search_traffic: number;
    indexed_pages: number;
    feature_pages: number;
    solution_pages: number;
  };
  sourceBreakdown: Array<{ traffic_source: string; total: number }>;
  dailyTrend: Array<{ date: string; humans: number; bots: number }>;
  topPages: Array<{ path: string; hits: number; human_hits: number; ai_hits: number }>;
  recentBots: Array<{ id: number; path: string; visitor_type: string; bot_name: string | null; traffic_source: string; created_at: string }>;
  readiness: Array<{ label: string; status: boolean; detail: string }>;
  trackingConfig: {
    ga_measurement_id: string;
    meta_pixel_id: string;
    gsc_verification: string;
  };
}>();

const form = useForm({
  ga_measurement_id: props.trackingConfig?.ga_measurement_id || '',
  meta_pixel_id: props.trackingConfig?.meta_pixel_id || '',
  gsc_verification: props.trackingConfig?.gsc_verification || '',
});

const changePeriod = (d: number) => {
  router.get(route('admin.seo-geo.index'), { days: d }, { preserveState: true });
};

const saveConfig = () => {
  form.post(route('admin.seo-geo.config'), { preserveScroll: true });
};

const sourceLabels: Record<string, string> = {
  google: 'Google Search / Googlebot',
  meta: 'Meta (FB / IG / Threads)',
  ai_search: 'AI Search & GEO Bots',
  direct: 'Direct / Langsung',
  referral: 'Referral Website Lain',
};

const maxTrendValue = computed(() =>
  Math.max(1, ...(props.dailyTrend || []).map((d) => Number(d.humans || 0) + Number(d.bots || 0)))
);
</script>

<template>
  <AdminLayout>
    <Head title="GEO & SEO Trafik — Admin InvoiceRapi" />

    <div class="space-y-6">
      <!-- Header -->
      <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
          <h1 class="text-2xl font-black text-slate-900 tracking-tight">GEO &amp; SEO Trafik Analytics</h1>
          <p class="text-sm text-slate-500 mt-0.5">
            Pantau trafik organik Google, Meta (FB/IG), AI Search Bots (GEO), serta konfigurasi pixel &amp; analytics.
          </p>
        </div>

        <div class="inline-flex items-center gap-1.5 bg-white border border-violet-100 rounded-xl p-1 shadow-2xs self-start">
          <button
            v-for="d in [7, 30, 90]"
            :key="d"
            type="button"
            @click="changePeriod(d)"
            :class="[
              'px-3.5 py-1.5 rounded-lg text-xs font-bold transition-all',
              days === d ? 'bg-violet-700 text-white shadow-xs' : 'text-slate-600 hover:bg-violet-50 hover:text-violet-700'
            ]"
          >
            {{ d }} Hari
          </button>
        </div>
      </div>

      <!-- KPI Cards -->
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
        <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-2xs">
          <div class="flex items-center justify-between text-slate-500 text-xs font-semibold">
            <span>Total Kunjungan</span>
            <Globe class="w-4 h-4 text-emerald-600" />
          </div>
          <p class="text-2xl font-black text-slate-900 mt-2">{{ stats.total_visits }}</p>
          <p class="text-[11px] text-slate-500 mt-1">{{ stats.human_visits }} pengunjung manusia</p>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-2xs">
          <div class="flex items-center justify-between text-slate-500 text-xs font-semibold">
            <span>Trafik Google</span>
            <Search class="w-4 h-4 text-blue-600" />
          </div>
          <p class="text-2xl font-black text-slate-900 mt-2">{{ stats.google_traffic }}</p>
          <p class="text-[11px] text-slate-500 mt-1">Search, Ads &amp; Googlebot</p>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-2xs">
          <div class="flex items-center justify-between text-slate-500 text-xs font-semibold">
            <span>Trafik Meta</span>
            <Share2 class="w-4 h-4 text-indigo-600" />
          </div>
          <p class="text-2xl font-black text-slate-900 mt-2">{{ stats.meta_traffic }}</p>
          <p class="text-[11px] text-slate-500 mt-1">Facebook, Instagram &amp; Crawler</p>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-2xs">
          <div class="flex items-center justify-between text-slate-500 text-xs font-semibold">
            <span>AI &amp; GEO Bots</span>
            <Bot class="w-4 h-4 text-violet-600" />
          </div>
          <p class="text-2xl font-black text-slate-900 mt-2">{{ stats.ai_search_traffic }}</p>
          <p class="text-[11px] text-slate-500 mt-1">GPTBot, Perplexity, Claude, Gemini</p>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-2xs">
          <div class="flex items-center justify-between text-slate-500 text-xs font-semibold">
            <span>Konten Terindeks</span>
            <FileText class="w-4 h-4 text-emerald-600" />
          </div>
          <p class="text-2xl font-black text-slate-900 mt-2">{{ stats.indexed_pages }}</p>
          <p class="text-[11px] text-slate-500 mt-1">
            1 Beranda &bull; {{ stats.feature_pages }} Fitur &bull; {{ stats.solution_pages }} Solusi
          </p>
        </div>
      </div>

      <!-- Daily Trend & Source Breakdown -->
      <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <div class="lg:col-span-7 bg-white rounded-2xl border border-slate-200/80 p-6 shadow-2xs">
          <div class="flex items-center justify-between mb-5">
            <h2 class="text-base font-bold text-slate-900 flex items-center gap-2">
              <TrendingUp class="w-4 h-4 text-violet-600" />
              Tren Trafik Harian (14 Hari Terakhir)
            </h2>
            <div class="flex items-center gap-4 text-[11px] font-semibold text-slate-500">
              <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-xs bg-emerald-500"></span> Manusia</span>
              <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-xs bg-violet-500"></span> Crawler/Bot</span>
            </div>
          </div>

          <div v-if="dailyTrend.length > 0" class="flex items-end gap-2 h-44 pt-4 border-b border-slate-100">
            <div
              v-for="item in dailyTrend"
              :key="item.date"
              class="flex-1 flex flex-col items-center gap-1 group"
              :title="`${item.date}: ${item.humans} manusia, ${item.bots} bot`"
            >
              <div class="w-full flex flex-col justify-end h-32 gap-0.5">
                <div
                  class="w-full bg-violet-500/85 rounded-t-xs transition-all"
                  :style="{ height: `${Math.round((Number(item.bots) / maxTrendValue) * 100)}%` }"
                ></div>
                <div
                  class="w-full bg-emerald-500 rounded-t-xs transition-all"
                  :style="{ height: `${Math.round((Number(item.humans) / maxTrendValue) * 100)}%` }"
                ></div>
              </div>
              <span class="text-[10px] text-slate-400 truncate max-w-full">
                {{ String(item.date).slice(5) }}
              </span>
            </div>
          </div>
          <p v-else class="text-sm text-slate-400 py-12 text-center">
            Belum ada data kunjungan terekam. Trafik akan otomatis tercatat saat ada pengunjung atau crawler.
          </p>
        </div>

        <div class="lg:col-span-5 bg-white rounded-2xl border border-slate-200/80 p-6 shadow-2xs">
          <h2 class="text-base font-bold text-slate-900 mb-4">Sumber Trafik ({{ days }} Hari)</h2>
          <div v-if="sourceBreakdown.length > 0" class="space-y-3.5">
            <div v-for="src in sourceBreakdown" :key="src.traffic_source" class="space-y-1">
              <div class="flex justify-between text-xs font-semibold text-slate-700">
                <span>{{ sourceLabels[src.traffic_source] || src.traffic_source }}</span>
                <span>{{ src.total }} hits</span>
              </div>
              <div class="w-full h-2 bg-slate-100 rounded-full overflow-hidden">
                <div
                  class="h-full bg-violet-600 rounded-full"
                  :style="{ width: `${Math.min(100, Math.round((src.total / Math.max(1, stats.total_visits)) * 100))}%` }"
                ></div>
              </div>
            </div>
          </div>
          <p v-else class="text-sm text-slate-400 py-8 text-center">Belum ada data sumber trafik.</p>
        </div>
      </div>

      <!-- Top Pages & Crawler Logs -->
      <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <div class="lg:col-span-6 bg-white rounded-2xl border border-slate-200/80 p-6 shadow-2xs">
          <h2 class="text-base font-bold text-slate-900 mb-4">Halaman Terpopuler</h2>
          <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
              <thead>
                <tr class="border-b border-slate-200 text-slate-500">
                  <th class="pb-2.5 font-semibold">Path URL</th>
                  <th class="pb-2.5 font-semibold text-right">Total</th>
                  <th class="pb-2.5 font-semibold text-right">Manusia</th>
                  <th class="pb-2.5 font-semibold text-right">AI Bot</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-slate-100">
                <tr v-for="pg in topPages" :key="pg.path" class="hover:bg-slate-50">
                  <td class="py-2.5 font-mono text-slate-800 truncate max-w-[220px]">{{ pg.path }}</td>
                  <td class="py-2.5 text-right font-bold text-slate-900">{{ pg.hits }}</td>
                  <td class="py-2.5 text-right text-emerald-700 font-semibold">{{ pg.human_hits }}</td>
                  <td class="py-2.5 text-right text-violet-700 font-semibold">{{ pg.ai_hits }}</td>
                </tr>
                <tr v-if="topPages.length === 0">
                  <td colspan="4" class="py-6 text-center text-slate-400">Belum ada data halaman.</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <div class="lg:col-span-6 bg-white rounded-2xl border border-slate-200/80 p-6 shadow-2xs">
          <h2 class="text-base font-bold text-slate-900 mb-4">Aktivitas Search &amp; AI Crawler Terbaru</h2>
          <div class="space-y-2.5 max-h-72 overflow-y-auto pr-1">
            <div
              v-for="bot in recentBots"
              :key="bot.id"
              class="flex items-center justify-between p-2.5 rounded-xl bg-slate-50 border border-slate-100 text-xs"
            >
              <div class="min-w-0">
                <span class="font-bold text-slate-800">{{ bot.bot_name || bot.visitor_type }}</span>
                <p class="font-mono text-[11px] text-slate-500 truncate">{{ bot.path }}</p>
              </div>
              <span class="text-[11px] text-slate-400 shrink-0 ml-2">
                {{ formatDateTimeID(bot.created_at) }}
              </span>
            </div>
            <p v-if="recentBots.length === 0" class="text-sm text-slate-400 py-8 text-center">
              Belum ada kunjungan bot Google / Meta / AI yang tercatat.
            </p>
          </div>
        </div>
      </div>

      <!-- Readiness Audit & Tracking Config -->
      <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <div class="lg:col-span-6 bg-white rounded-2xl border border-slate-200/80 p-6 shadow-2xs">
          <h2 class="text-base font-bold text-slate-900 mb-4">Checklist Kesiapan SEO &amp; GEO</h2>
          <div class="space-y-3">
            <div
              v-for="item in readiness"
              :key="item.label"
              class="flex items-start gap-3 p-3 rounded-xl border border-slate-100 bg-slate-50/70"
            >
              <CheckCircle2 v-if="item.status" class="w-5 h-5 text-emerald-600 shrink-0 mt-0.5" />
              <AlertCircle v-else class="w-5 h-5 text-amber-500 shrink-0 mt-0.5" />
              <div>
                <p class="text-xs font-bold text-slate-900">{{ item.label }}</p>
                <p class="text-[11px] text-slate-600 mt-0.5">{{ item.detail }}</p>
              </div>
            </div>
          </div>

          <div class="mt-4 flex flex-wrap gap-2 pt-3 border-t border-slate-100 text-xs">
            <a
              href="/llms.txt"
              target="_blank"
              class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold"
            >
              <span>Buka /llms.txt</span>
              <ExternalLink class="w-3.5 h-3.5" />
            </a>
            <a
              href="/sitemap.xml"
              target="_blank"
              class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold"
            >
              <span>Buka /sitemap.xml</span>
              <ExternalLink class="w-3.5 h-3.5" />
            </a>
          </div>
        </div>

        <div class="lg:col-span-6 bg-white rounded-2xl border border-slate-200/80 p-6 shadow-2xs">
          <h2 class="text-base font-bold text-slate-900 mb-1">Integrasi Google &amp; Meta Tracking</h2>
          <p class="text-xs text-slate-500 mb-5">
            Isi ID di bawah untuk menyuntikkan script Google Analytics 4 &amp; Meta Pixel secara otomatis ke seluruh halaman publik.
          </p>

          <form @submit.prevent="saveConfig" class="space-y-4">
            <div>
              <label class="block text-xs font-bold text-slate-700 mb-1">Google Tag / Analytics 4 (G- / GTM- ID)</label>
              <input
                v-model="form.ga_measurement_id"
                type="text"
                placeholder="Contoh: G-XXXXXXXXXX atau GTM-XXXXXXXX"
                class="w-full rounded-xl border-slate-300 text-sm focus:border-violet-500 focus:ring-violet-500"
              />
            </div>

            <div>
              <label class="block text-xs font-bold text-slate-700 mb-1">Meta Pixel ID (Facebook / Instagram Ads)</label>
              <input
                v-model="form.meta_pixel_id"
                type="text"
                placeholder="Contoh: 123456789012345"
                class="w-full rounded-xl border-slate-300 text-sm focus:border-violet-500 focus:ring-violet-500"
              />
            </div>

            <div>
              <label class="block text-xs font-bold text-slate-700 mb-1">Google Search Console Verification Code</label>
              <input
                v-model="form.gsc_verification"
                type="text"
                placeholder="google587f0f47640fdb34"
                class="w-full rounded-xl border-slate-300 text-sm focus:border-violet-500 focus:ring-violet-500"
              />
            </div>

            <button
              type="submit"
              :disabled="form.processing"
              class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-violet-700 hover:bg-violet-800 text-white text-xs font-bold shadow-sm transition-all disabled:opacity-60"
            >
              <Save class="w-4 h-4" />
              <span>Simpan Konfigurasi Tracking</span>
            </button>
          </form>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>
