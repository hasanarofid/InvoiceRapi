<script setup lang="ts">
import { ref } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import Container from '@/Components/UI/Container.vue';
import { BRAND } from '@/config/brand';
import {
  Search,
  Filter,
  Sparkles,
  ArrowRight,
  Code2,
  CheckCircle2,
  ChevronRight,
  ExternalLink,
  Laptop,
  Check
} from '@lucide/vue';

interface CategoryItem {
  id: number;
  name: string;
  slug: string;
  description?: string;
}

interface ProductItem {
  id: number;
  name: string;
  slug: string;
  sku?: string;
  price: string | number;
  compare_at_price?: string | number;
  price_range?: string;
  short_description?: string;
  thumbnail?: string;
  is_featured: boolean;
  specifications?: {
    product_type?: string;
    tech_stack?: string[];
    version?: string;
    demo_urls?: {
      live_demo?: string;
    };
  };
  category?: CategoryItem;
}

interface PaginatedProducts {
  data: ProductItem[];
  current_page: number;
  last_page: number;
  total: number;
  links: {
    url: string | null;
    label: string;
    active: boolean;
  }[];
}

const props = defineProps<{
  categories: CategoryItem[];
  products: PaginatedProducts;
  currentCategory?: string | null;
  searchQuery?: string;
  meta?: {
    title: string;
    description: string;
    og_image: string;
  };
}>();

const searchInput = ref(props.searchQuery || '');

const handleSearch = () => {
  router.get('/produk', {
    kategori: props.currentCategory || undefined,
    q: searchInput.value || undefined
  }, { preserveState: true });
};

const filterByCategory = (slug?: string | null) => {
  router.get('/produk', {
    kategori: slug || undefined,
    q: searchInput.value || undefined
  }, { preserveState: true });
};

const formatRupiah = (val: string | number) => {
  const num = typeof val === 'string' ? parseFloat(val) : val;
  if (isNaN(num) || num === 0) return 'Gratis';
  return 'Rp ' + num.toLocaleString('id-ID');
};
</script>

<template>
  <AppLayout
    :title="meta?.title || 'Produk Software & Starter Kit Siap Pakai | Synbarra Technologies'"
    :description="meta?.description || 'Koleksi produk software, starter kit, dan mini apps siap pakai dari Synbarra Technologies.'"
    :image="meta?.og_image || '/images/posts/Synbarra_Post_08.png'"
  >
    <!-- ========================================================================= -->
    <!-- 1. HERO HEADER                                                            -->
    <!-- ========================================================================= -->
    <section class="relative overflow-hidden bg-gradient-to-b from-[#EBF9F7] via-[#F4FCFA] to-white pt-12 pb-16 lg:pt-16 lg:pb-20">
      <!-- Ambient Glows -->
      <div class="absolute top-0 right-1/4 w-[450px] h-[450px] bg-[#00F1DE]/15 rounded-full blur-[140px] pointer-events-none"></div>
      <div class="absolute bottom-0 left-10 w-[400px] h-[400px] bg-[#00BFA5]/10 rounded-full blur-[120px] pointer-events-none"></div>

      <Container class="relative z-10">
        <div class="max-w-3xl mx-auto text-center flex flex-col items-center">
          <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-white border border-[#A7EADF] text-[#008F7A] text-xs font-semibold uppercase tracking-wider shadow-xs mb-5">
            <span class="w-2 h-2 rounded-full bg-[#00BFA5] animate-pulse"></span>
            Ready-to-Launch Solutions
          </div>

          <h1 class="text-3xl sm:text-5xl font-black tracking-tight text-[#0D2329] leading-tight mb-4">
            PRODUK SOFTWARE & <br class="hidden sm:block" />
            <span class="text-transparent bg-clip-text bg-gradient-to-r from-[#0D2329] via-[#0A4D4F] to-[#00BFA5]">
              STARTER KIT SIAP PAKAI
            </span>
          </h1>

          <div class="w-20 h-1.5 bg-gradient-to-r from-[#00BFA5] to-[#00F1DE] rounded-full my-3"></div>

          <p class="text-base sm:text-lg text-slate-600 leading-relaxed max-w-2xl mt-2 mb-8">
            Bangun aplikasi dan operasionalkan bisnis Anda lebih cepat. Fondasi software teruji dengan <em>Clean Code</em>, <em>Strict Typing</em>, dan siap dikustomisasi.
          </p>

          <!-- Search & Filter Bar -->
          <div class="w-full max-w-2xl bg-white p-2.5 rounded-2xl border border-[#BCEEE5] shadow-sm flex items-center gap-2">
            <div class="relative flex-grow">
              <Search class="w-4 h-4 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2" />
              <input
                v-model="searchInput"
                @keyup.enter="handleSearch"
                type="text"
                placeholder="Cari starter kit, POS kasir, atau nama modul..."
                class="w-full pl-10 pr-4 py-2.5 text-xs sm:text-sm text-[#0D2329] bg-transparent border-0 focus:ring-0 placeholder:text-slate-400 focus:outline-none"
              />
            </div>
            <button
              @click="handleSearch"
              class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-[#0D2329] to-[#00BFA5] text-white text-xs sm:text-sm font-bold hover:opacity-95 transition-all shadow-xs shrink-0"
            >
              Cari Produk
            </button>
          </div>
        </div>
      </Container>
    </section>

    <!-- ========================================================================= -->
    <!-- 2. PRODUCT CATALOG GRID & CATEGORY TABS                                   -->
    <!-- ========================================================================= -->
    <section class="py-12 lg:py-16 bg-white min-h-[500px]">
      <Container>
        <!-- Category Filter Pills -->
        <div class="flex flex-wrap items-center justify-center gap-2 mb-10">
          <button
            @click="filterByCategory(null)"
            :class="[
              'px-4 py-2 rounded-full text-xs font-bold transition-all duration-200',
              !currentCategory
                ? 'bg-[#0D2329] text-white shadow-sm'
                : 'bg-[#F2FBF9] text-slate-700 hover:bg-[#E6FAF7] border border-[#D5F5EE]'
            ]"
          >
            Semua Kategori ({{ products.total }})
          </button>

          <button
            v-for="cat in categories"
            :key="cat.id"
            @click="filterByCategory(cat.slug)"
            :class="[
              'px-4 py-2 rounded-full text-xs font-bold transition-all duration-200',
              currentCategory === cat.slug
                ? 'bg-[#00BFA5] text-white shadow-sm'
                : 'bg-[#F2FBF9] text-slate-700 hover:bg-[#E6FAF7] border border-[#D5F5EE]'
            ]"
          >
            {{ cat.name }}
          </button>
        </div>

        <!-- Products Grid -->
        <div v-if="products.data.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
          <div
            v-for="item in products.data"
            :key="item.id"
            class="group flex flex-col justify-between bg-white rounded-3xl border border-[#D5F5EE] shadow-[0_8px_30px_-6px_rgba(0,191,165,0.06)] hover:border-[#00BFA5] hover:shadow-lg transition-all duration-300 overflow-hidden"
          >
            <!-- Thumbnail Image -->
            <div class="relative w-full h-52 overflow-hidden bg-slate-100 border-b border-slate-100">
              <img
                :src="item.thumbnail || '/images/posts/Synbarra_Post_08.png'"
                :alt="item.name"
                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                loading="lazy"
              />
              <div class="absolute top-3 left-3 flex flex-wrap gap-1.5">
                <span v-if="item.is_featured" class="px-2.5 py-1 rounded-full bg-[#0D2329]/90 backdrop-blur-sm text-[#00F1DE] text-[11px] font-bold border border-[#00F1DE]/30">
                  ⚡ Unggulan
                </span>
                <span v-if="item.category" class="px-2.5 py-1 rounded-full bg-white/95 backdrop-blur-sm text-[#008F7A] text-[11px] font-bold border border-[#A7EADF]">
                  {{ item.category.name }}
                </span>
              </div>
            </div>

            <!-- Content Body -->
            <div class="p-6 flex-grow flex flex-col justify-between">
              <div>
                <h3 class="text-lg font-bold text-[#0D2329] group-hover:text-[#008F7A] transition-colors leading-snug mb-2">
                  <Link :href="`/produk/${item.slug}`">
                    {{ item.name }}
                  </Link>
                </h3>

                <p class="text-xs sm:text-sm text-slate-600 line-clamp-3 leading-relaxed mb-4">
                  {{ item.short_description }}
                </p>

                <!-- Tech Stack Badges -->
                <div v-if="item.specifications?.tech_stack" class="flex flex-wrap gap-1 mb-5">
                  <span
                    v-for="(tech, tIdx) in item.specifications.tech_stack.slice(0, 4)"
                    :key="tIdx"
                    class="px-2 py-0.5 rounded-md bg-[#F4FCFA] border border-[#D5F5EE] text-[#008F7A] text-[10px] font-mono"
                  >
                    {{ tech }}
                  </span>
                  <span v-if="item.specifications.tech_stack.length > 4" class="text-[10px] text-slate-400 py-0.5">
                    +{{ item.specifications.tech_stack.length - 4 }} lagi
                  </span>
                </div>
              </div>

              <!-- Price & CTA Footer -->
              <div class="pt-4 border-t border-slate-100 flex items-center justify-between mt-auto">
                <div>
                  <div class="text-[11px] text-slate-400">Harga:</div>
                  <div class="text-sm sm:text-base font-extrabold text-[#0D2329]">
                    {{ item.price_range || formatRupiah(item.price) }}
                  </div>
                </div>

                <Link
                  :href="`/produk/${item.slug}`"
                  class="inline-flex items-center gap-1.5 px-4 py-2 rounded-full text-xs font-bold text-[#0D2329] bg-[#E6FAF7] hover:bg-[#00BFA5] hover:text-white border border-[#A7EADF] transition-all"
                >
                  <span>Detail & Demo</span>
                  <ArrowRight class="w-3.5 h-3.5" />
                </Link>
              </div>
            </div>
          </div>
        </div>

        <!-- Empty State -->
        <div v-else class="text-center py-16 px-4 bg-[#F8FCFB] rounded-3xl border border-[#D5F5EE] max-w-xl mx-auto">
          <div class="w-14 h-14 rounded-2xl bg-[#E6FAF7] text-[#00BFA5] flex items-center justify-center mx-auto mb-4">
            <Search class="w-6 h-6 stroke-[2]" />
          </div>
          <h3 class="text-lg font-bold text-[#0D2329] mb-2">Produk Tidak Ditemukan</h3>
          <p class="text-xs sm:text-sm text-slate-500 mb-6">
            Tidak ada produk software yang cocok dengan pencarian kata kunci atau kategori yang Anda pilih.
          </p>
          <button
            @click="filterByCategory(null)"
            class="px-5 py-2.5 rounded-full bg-[#0D2329] text-white text-xs font-bold hover:bg-[#00BFA5] transition-colors"
          >
            Tampilkan Semua Produk
          </button>
        </div>

        <!-- Pagination -->
        <div v-if="products.last_page > 1" class="flex justify-center items-center gap-2 mt-12">
          <template v-for="(link, idx) in products.links" :key="idx">
            <Link
              v-if="link.url"
              :href="link.url"
              v-html="link.label"
              :class="[
                'px-4 py-2 rounded-xl text-xs font-bold transition-all',
                link.active
                  ? 'bg-[#00BFA5] text-white shadow-xs'
                  : 'bg-white border border-slate-200 text-slate-700 hover:bg-slate-50'
              ]"
            />
            <span
              v-else
              v-html="link.label"
              class="px-4 py-2 text-xs text-slate-400 cursor-not-allowed"
            />
          </template>
        </div>
      </Container>
    </section>

    <!-- ========================================================================= -->
    <!-- 3. UPSELL & CUSTOM DEVELOPMENT SECTION                                    -->
    <!-- ========================================================================= -->
    <section class="py-16 bg-gradient-to-b from-white to-[#EBF9F7]">
      <Container>
        <div class="bg-gradient-to-br from-[#0D2329] via-[#0A4D4F] to-[#011E26] rounded-3xl p-8 sm:p-12 text-white shadow-xl relative overflow-hidden">
          <div class="absolute top-0 right-0 w-80 h-80 bg-[#00F1DE]/15 rounded-full blur-3xl pointer-events-none"></div>

          <div class="relative z-10 max-w-2xl">
            <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-[#00F1DE]/15 border border-[#00F1DE]/30 text-[#00F1DE] text-xs font-mono mb-4">
              Jasa Kustomisasi & Integrasi
            </span>
            <h2 class="text-2xl sm:text-4xl font-extrabold tracking-tight text-white mb-4 leading-tight">
              Butuh Fitur Khusus atau Integrasi Sistem?
            </h2>
            <p class="text-slate-300 text-sm sm:text-base leading-relaxed mb-8">
              Seluruh produk software kami dirancang modular sehingga mudah disesuaikan dengan alur bisnis Anda. Tim engineer Synbarra siap membantu penambahan modul, instalasi server, atau integrasi API pihak ketiga.
            </p>

            <a
              :href="BRAND.contact.whatsappUrl"
              target="_blank"
              rel="noopener noreferrer"
              class="inline-flex items-center gap-3 px-7 py-3.5 rounded-full text-xs sm:text-sm font-bold text-[#0D2329] bg-gradient-to-r from-[#00F1DE] to-[#00BFA5] hover:opacity-95 shadow-md transition-all"
            >
              <span>Konsultasikan Kustomisasi Produk</span>
              <ArrowRight class="w-4 h-4" />
            </a>
          </div>
        </div>
      </Container>
    </section>
  </AppLayout>
</template>
