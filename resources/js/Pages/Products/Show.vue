<script setup lang="ts">
import { ref, computed } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import Container from '@/Components/UI/Container.vue';
import { BRAND } from '@/config/brand';
import {
  Sparkles,
  ArrowRight,
  CheckCircle2,
  ExternalLink,
  Laptop,
  Check,
  ShieldCheck,
  Code2,
  FileText,
  Layers,
  ChevronRight,
  Headset,
  HelpCircle
} from '@lucide/vue';

interface Tier {
  name: string;
  price: number;
  price_formatted: string;
  compare_at_price?: number;
  badge?: string;
  features: string[];
}

interface ProductDetail {
  id: number;
  name: string;
  slug: string;
  sku?: string;
  material?: string;
  dimensions?: string;
  price: string | number;
  compare_at_price?: string | number;
  price_range?: string;
  short_description?: string;
  description?: string;
  thumbnail?: string;
  images?: string[];
  specifications?: {
    product_name?: string;
    product_type?: string;
    version?: string;
    release_status?: string;
    primary_persona?: string;
    tech_stack?: string[];
    key_modules?: string[];
    tiers?: Record<string, Tier>;
    demo_urls?: {
      live_demo?: string;
      documentation?: string;
      github_preview?: string;
    };
    license_summary?: string;
  };
  category?: {
    id: number;
    name: string;
    slug: string;
  };
}

const props = defineProps<{
  product: ProductDetail;
  relatedProducts?: ProductDetail[];
  meta?: {
    title: string;
    subtitle?: string;
    description: string;
    og_image: string;
  };
}>();

// Tiers Management
const tiers = computed(() => {
  return props.product.specifications?.tiers || null;
});

const activeTierKey = ref<string>(
  tiers.value && tiers.value['pro'] ? 'pro' : (tiers.value ? Object.keys(tiers.value)[0] : '')
);

const selectedTier = computed(() => {
  if (!tiers.value || !activeTierKey.value) return null;
  return tiers.value[activeTierKey.value] || null;
});

const activeDisplayPrice = computed(() => {
  if (selectedTier.value) {
    return selectedTier.value.price_formatted || (selectedTier.value.price === 0 ? 'Gratis' : 'Rp ' + selectedTier.value.price.toLocaleString('id-ID'));
  }
  const num = typeof props.product.price === 'string' ? parseFloat(props.product.price) : props.product.price;
  return isNaN(num) || num === 0 ? 'Gratis' : 'Rp ' + num.toLocaleString('id-ID');
});

// WA Order Link with pre-filled context
const getOrderWaUrl = computed(() => {
  const tierName = selectedTier.value ? selectedTier.value.name : 'Lisensi Standar';
  const text = encodeURIComponent(
    `Halo Synbarra Technologies, saya tertarik dan ingin memesan produk software:\n\n*${props.product.name}*\nPaket: *${tierName}*\nHarga: *${activeDisplayPrice.value}*\n\nMohon info langkah selanjutnya dan opsi pembayarannya.`
  );
  return `https://wa.me/${BRAND.contact.whatsapp}?text=${text}`;
});

const getConsultWaUrl = computed(() => {
  const text = encodeURIComponent(
    `Halo tim engineer Synbarra, saya ingin konsultasi mengenai kustomisasi / penambahan modul khusus untuk produk *${props.product.name}*.`
  );
  return `https://wa.me/${BRAND.contact.whatsapp}?text=${text}`;
});
</script>

<template>
  <AppLayout
    :title="meta?.title || `${product.name} | Synbarra Technologies`"
    :description="meta?.description || product.short_description || 'Produk software siap pakai dari Synbarra Technologies.'"
    :image="meta?.og_image || product.thumbnail || '/images/posts/Synbarra_Post_08.png'"
  >
    <!-- Breadcrumb & Top Bar -->
    <div class="bg-[#F8FCFB] border-b border-[#D5F5EE] py-3.5">
      <Container>
        <nav class="flex items-center gap-2 text-xs text-slate-500 overflow-x-auto whitespace-nowrap">
          <Link href="/" class="hover:text-[#00BFA5] transition-colors">Home</Link>
          <ChevronRight class="w-3.5 h-3.5 text-slate-400 shrink-0" />
          <Link href="/produk" class="hover:text-[#00BFA5] transition-colors">Produk</Link>
          <ChevronRight class="w-3.5 h-3.5 text-slate-400 shrink-0" />
          <span v-if="product.category" class="text-slate-600">
            {{ product.category.name }}
          </span>
          <ChevronRight v-if="product.category" class="w-3.5 h-3.5 text-slate-400 shrink-0" />
          <span class="text-[#0D2329] font-bold truncate max-w-xs">{{ product.name }}</span>
        </nav>
      </Container>
    </div>

    <!-- Product Header & Showcase Section -->
    <section class="py-10 lg:py-16 bg-white relative overflow-hidden">
      <!-- Ambient Glow -->
      <div class="absolute top-10 right-1/4 w-[400px] h-[400px] bg-[#00F1DE]/10 rounded-full blur-[140px] pointer-events-none"></div>

      <Container class="relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-12 items-start">
          <!-- Left Column: Visual Mockup & Screenshots -->
          <div class="lg:col-span-6 flex flex-col space-y-4">
            <div class="relative w-full rounded-3xl overflow-hidden border border-[#D5F5EE] shadow-md bg-slate-50 group">
              <img
                :src="product.thumbnail || '/images/posts/Synbarra_Post_08.png'"
                :alt="product.name"
                class="w-full h-auto object-cover group-hover:scale-102 transition-transform duration-300"
              />
              <div class="absolute top-4 left-4 flex flex-wrap gap-2">
                <span v-if="product.category" class="px-3 py-1 rounded-full bg-white/95 backdrop-blur-md text-[#008F7A] text-xs font-bold border border-[#A7EADF] shadow-2xs">
                  {{ product.category.name }}
                </span>
                <span v-if="product.sku" class="px-2.5 py-1 rounded-full bg-[#0D2329]/90 backdrop-blur-md text-[#00F1DE] text-[11px] font-mono border border-[#00F1DE]/30">
                  SKU: {{ product.sku }}
                </span>
              </div>
            </div>

            <!-- Quick Action Links -->
            <div class="flex flex-wrap gap-3 pt-2">
              <a
                v-if="product.specifications?.demo_urls?.live_demo"
                :href="product.specifications.demo_urls.live_demo"
                target="_blank"
                rel="noopener noreferrer"
                class="flex-1 min-w-[140px] inline-flex items-center justify-center gap-2 px-5 py-3 rounded-2xl bg-[#E6FAF7] border border-[#A7EADF] text-[#008F7A] text-xs font-bold hover:bg-[#00BFA5] hover:text-white transition-all shadow-xs"
              >
                <Laptop class="w-4 h-4" />
                <span>Buka Live Demo</span>
                <ExternalLink class="w-3.5 h-3.5" />
              </a>

              <a
                v-if="product.specifications?.demo_urls?.documentation"
                :href="product.specifications.demo_urls.documentation"
                target="_blank"
                rel="noopener noreferrer"
                class="flex-1 min-w-[140px] inline-flex items-center justify-center gap-2 px-5 py-3 rounded-2xl bg-white border border-slate-200 text-slate-700 text-xs font-bold hover:bg-slate-50 transition-all shadow-xs"
              >
                <FileText class="w-4 h-4 text-slate-500" />
                <span>Dokumentasi</span>
              </a>
            </div>

            <!-- Trust Highlights -->
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 pt-4 border-t border-slate-100 text-slate-600 text-xs">
              <div class="flex items-center gap-2">
                <ShieldCheck class="w-4 h-4 text-[#00BFA5] shrink-0" />
                <span>Strict Typing</span>
              </div>
              <div class="flex items-center gap-2">
                <Code2 class="w-4 h-4 text-[#00BFA5] shrink-0" />
                <span>Clean Architecture</span>
              </div>
              <div class="flex items-center gap-2">
                <Headset class="w-4 h-4 text-[#00BFA5] shrink-0" />
                <span>Garansi Bug Fix</span>
              </div>
            </div>
          </div>

          <!-- Right Column: Product Info & Interactive License Pricing -->
          <div class="lg:col-span-6 flex flex-col items-start">
            <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-[#E6FAF7] text-[#008F7A] text-xs font-bold uppercase tracking-wider mb-3 border border-[#BCEEE5]">
              <span class="w-2 h-2 rounded-full bg-[#00BFA5] animate-pulse"></span>
              {{ product.specifications?.release_status || 'Production-Ready Software' }}
            </span>

            <h1 class="text-2xl sm:text-4xl font-black text-[#0D2329] leading-tight mb-3">
              {{ product.name }}
            </h1>

            <p class="text-sm sm:text-base text-slate-600 leading-relaxed mb-6">
              {{ product.short_description }}
            </p>

            <!-- Tech Stack Pills -->
            <div v-if="product.specifications?.tech_stack" class="mb-6 w-full">
              <div class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Technology Stack</div>
              <div class="flex flex-wrap gap-1.5">
                <span
                  v-for="(tech, idx) in product.specifications.tech_stack"
                  :key="idx"
                  class="px-2.5 py-1 rounded-lg bg-[#F4FCFA] border border-[#D5F5EE] text-[#008F7A] text-xs font-mono"
                >
                  {{ tech }}
                </span>
              </div>
            </div>

            <!-- Interactive Tier License Box -->
            <div class="w-full bg-[#F8FCFB] p-6 rounded-3xl border border-[#C6F2EB] shadow-xs mb-6">
              <div class="text-xs font-bold uppercase tracking-wider text-slate-500 mb-3">Pilih Opsi Lisensi:</div>

              <!-- Tier Tabs / Radio Buttons -->
              <div v-if="tiers" class="grid grid-cols-1 sm:grid-cols-3 gap-2.5 mb-5">
                <button
                  v-for="(tier, key) in tiers"
                  :key="key"
                  type="button"
                  @click="activeTierKey = key"
                  :class="[
                    'p-3.5 rounded-2xl text-left border transition-all duration-200 relative',
                    activeTierKey === key
                      ? 'bg-white border-[#00BFA5] shadow-sm ring-2 ring-[#00BFA5]/20'
                      : 'bg-white/60 border-slate-200 hover:bg-white'
                  ]"
                >
                  <div class="text-xs font-extrabold text-[#0D2329]">{{ tier.name }}</div>
                  <div class="text-sm font-black text-[#00BFA5] mt-1">
                    {{ tier.price_formatted }}
                  </div>
                  <span
                    v-if="tier.badge"
                    class="inline-block mt-1 px-2 py-0.5 rounded-md bg-[#E6FAF7] text-[#008F7A] text-[10px] font-bold"
                  >
                    {{ tier.badge }}
                  </span>
                </button>
              </div>

              <!-- Selected Tier Features Checklist -->
              <div v-if="selectedTier" class="bg-white p-4 rounded-2xl border border-slate-100 mb-5">
                <div class="text-xs font-bold text-[#0D2329] mb-2.5 flex items-center justify-between">
                  <span>Fitur yang Termasuk:</span>
                  <span class="text-[#00BFA5] font-bold">{{ selectedTier.name }}</span>
                </div>
                <ul class="space-y-2">
                  <li
                    v-for="(feat, fIdx) in selectedTier.features"
                    :key="fIdx"
                    class="flex items-start gap-2 text-xs text-slate-700"
                  >
                    <Check class="w-3.5 h-3.5 text-[#00BFA5] shrink-0 mt-0.5" />
                    <span>{{ feat }}</span>
                  </li>
                </ul>
              </div>

              <!-- Pricing Total & CTA Button -->
              <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-2">
                <div>
                  <div class="text-xs text-slate-400">Total Harga:</div>
                  <div class="text-2xl font-black text-[#0D2329]">
                    {{ activeDisplayPrice }}
                  </div>
                </div>

                <a
                  :href="getOrderWaUrl"
                  target="_blank"
                  rel="noopener noreferrer"
                  class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-7 py-3.5 rounded-full text-xs sm:text-sm font-bold text-white bg-gradient-to-r from-[#0D2329] via-[#0A4D4F] to-[#00BFA5] hover:opacity-95 shadow-md hover:shadow-lg transition-all"
                >
                  <span>Dapatkan Sekarang via WhatsApp</span>
                  <ArrowRight class="w-4 h-4" />
                </a>
              </div>
            </div>

            <!-- Customization Notice -->
            <div class="w-full p-4 rounded-2xl bg-white border border-[#BCEEE5] flex items-center justify-between gap-4">
              <div class="text-xs text-slate-600">
                <span class="font-bold text-[#0D2329]">Butuh Modul Khusus?</span>
                Kami melayani kustomisasi fitur, integrasi database, dan setup server.
              </div>
              <a
                :href="getConsultWaUrl"
                target="_blank"
                rel="noopener noreferrer"
                class="px-4 py-2 rounded-xl bg-[#E6FAF7] text-[#008F7A] text-xs font-bold hover:bg-[#00BFA5] hover:text-white transition-colors shrink-0"
              >
                Konsultasi Custom
              </a>
            </div>
          </div>
        </div>
      </Container>
    </section>

    <!-- Detailed Content & Walkthrough -->
    <section class="py-12 lg:py-16 bg-[#F8FCFB] border-t border-[#D5F5EE]">
      <Container>
        <div class="max-w-4xl mx-auto space-y-12">
          <!-- Key Modules Grid -->
          <div v-if="product.specifications?.key_modules">
            <h2 class="text-xl sm:text-2xl font-bold text-[#0D2329] mb-6 flex items-center gap-2">
              <Layers class="w-5 h-5 text-[#00BFA5]" />
              <span>Modul & Fungsionalitas Utama</span>
            </h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
              <div
                v-for="(mod, mIdx) in product.specifications.key_modules"
                :key="mIdx"
                class="flex items-center gap-3 p-4 rounded-2xl bg-white border border-[#D5F5EE] shadow-2xs"
              >
                <div class="w-8 h-8 rounded-xl bg-[#E6FAF7] text-[#00BFA5] flex items-center justify-center shrink-0">
                  <CheckCircle2 class="w-4 h-4 stroke-[2.5]" />
                </div>
                <span class="text-xs sm:text-sm font-bold text-[#0D2329]">{{ mod }}</span>
              </div>
            </div>
          </div>

          <!-- Rich HTML Description -->
          <div v-if="product.description" class="bg-white p-6 sm:p-10 rounded-3xl border border-[#D5F5EE] shadow-sm">
            <h2 class="text-xl font-bold text-[#0D2329] mb-4 pb-3 border-b border-slate-100">
              Dokumentasi & Penjelasan Produk
            </h2>
            <div
              class="prose prose-slate max-w-none text-xs sm:text-sm leading-relaxed prose-headings:text-[#0D2329] prose-headings:font-bold prose-a:text-[#00BFA5]"
              v-html="product.description"
            />
          </div>

          <!-- License Terms Notice -->
          <div v-if="product.specifications?.license_summary" class="p-5 rounded-2xl bg-[#EBF9F7] border border-[#BCEEE5] text-xs text-slate-700 flex items-start gap-3">
            <HelpCircle class="w-5 h-5 text-[#008F7A] shrink-0 mt-0.5" />
            <div>
              <span class="font-bold text-[#0D2329]">Ketentuan Lisensi:</span>
              <p class="mt-0.5 text-slate-600">{{ product.specifications.license_summary }}</p>
            </div>
          </div>
        </div>
      </Container>
    </section>

    <!-- Related Products -->
    <section v-if="relatedProducts && relatedProducts.length > 0" class="py-16 bg-white border-t border-[#D5F5EE]">
      <Container>
        <div class="flex items-center justify-between mb-8">
          <div>
            <h3 class="text-xl font-bold text-[#0D2329]">Produk Terkait Lainnya</h3>
            <p class="text-xs text-slate-500 mt-1">Eksplorasi solusi digital lain yang relevan untuk proyek Anda.</p>
          </div>
          <Link href="/produk" class="text-xs font-bold text-[#00BFA5] hover:underline flex items-center gap-1">
            <span>Lihat Semua</span>
            <ArrowRight class="w-3.5 h-3.5" />
          </Link>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
          <div
            v-for="rel in relatedProducts"
            :key="rel.id"
            class="p-5 rounded-3xl bg-white border border-[#D5F5EE] shadow-xs hover:border-[#00BFA5] transition-all"
          >
            <h4 class="font-bold text-sm text-[#0D2329] mb-1">
              <Link :href="`/produk/${rel.slug}`" class="hover:text-[#008F7A]">
                {{ rel.name }}
              </Link>
            </h4>
            <p class="text-xs text-slate-500 line-clamp-2 mb-3">{{ rel.short_description }}</p>
            <div class="text-xs font-bold text-[#0D2329]">{{ rel.price_range || ('Rp ' + rel.price) }}</div>
          </div>
        </div>
      </Container>
    </section>
  </AppLayout>
</template>

