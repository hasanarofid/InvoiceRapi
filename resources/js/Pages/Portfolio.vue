<script setup lang="ts">
import { ref, computed } from 'vue';
import { Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import Container from '@/Components/UI/Container.vue';
import { BRAND } from '@/config/brand';
import { PORTFOLIO_PROJECTS, type PortfolioProject } from '@/config/portfolio';
import { 
  ExternalLink, 
  CheckCircle2, 
  Layers, 
  Globe, 
  Sparkles, 
  ArrowRight, 
  Maximize2, 
  X, 
  ChevronLeft, 
  ChevronRight,
  ShieldCheck,
  Cpu,
  MessageCircle
} from '@lucide/vue';

type CategoryFilter = 'all' | 'internal' | 'public';

const activeFilter = ref<CategoryFilter>('all');

// Image selector index for each project card
const activeImageIndex = ref<Record<string, number>>({});

const getActiveImage = (project: PortfolioProject): string => {
  const index = activeImageIndex.value[project.id] ?? 0;
  return project.images[index] || project.images[0];
};

const setActiveImage = (projectId: string, index: number) => {
  activeImageIndex.value[projectId] = index;
};

// Filtered projects
const filteredProjects = computed(() => {
  if (activeFilter.value === 'all') {
    return PORTFOLIO_PROJECTS;
  }
  return PORTFOLIO_PROJECTS.filter(p => p.category === activeFilter.value);
});

// Category counts
const counts = computed(() => ({
  all: PORTFOLIO_PROJECTS.length,
  internal: PORTFOLIO_PROJECTS.filter(p => p.category === 'internal').length,
  public: PORTFOLIO_PROJECTS.filter(p => p.category === 'public').length,
}));

// Modal Lightbox state
const lightboxOpen = ref(false);
const activeModalProject = ref<PortfolioProject | null>(null);
const currentModalImageIdx = ref(0);

const openLightbox = (project: PortfolioProject, imageIdx = 0) => {
  activeModalProject.value = project;
  currentModalImageIdx.value = imageIdx;
  lightboxOpen.value = true;
  document.body.style.overflow = 'hidden';
};

const closeLightbox = () => {
  lightboxOpen.value = false;
  activeModalProject.value = null;
  document.body.style.overflow = '';
};

const nextModalImage = () => {
  if (!activeModalProject.value) return;
  currentModalImageIdx.value = (currentModalImageIdx.value + 1) % activeModalProject.value.images.length;
};

const prevModalImage = () => {
  if (!activeModalProject.value) return;
  currentModalImageIdx.value = (currentModalImageIdx.value - 1 + activeModalProject.value.images.length) % activeModalProject.value.images.length;
};

const getWaInquiryUrl = (projectTitle: string) => {
  const message = `Halo Synbarra Technologies, saya tertarik dengan portofolio "${projectTitle}" dan ingin konsultasi mengenai pengembangan sistem serupa untuk bisnis saya.`;
  return `https://wa.me/${BRAND.contact.whatsapp}?text=${encodeURIComponent(message)}`;
};
</script>

<template>
  <AppLayout 
    title="Portofolio Proyek & Solusi Digital | Synbarra Technologies"
    description="Kumpulan proyek sukses yang telah dibangun Synbarra Technologies: Sistem ERP Mr. Lux, Portal Akademik Sekolah, Infrastruktur EV Amtech, Platform Umroh Mitra Syiar, dan CleanTech Gringgo."
    image="/images/portfolio/mrluxindonesia.png"
  >
    <!-- ========================================================================= -->
    <!-- 1. HERO BANNER                                                            -->
    <!-- ========================================================================= -->
    <section class="bg-gradient-to-b from-[#EBF9F7] via-[#F4FCFA] to-white py-16 lg:py-24 relative overflow-hidden">
      <!-- Ambient Mint Glows -->
      <div class="absolute top-0 right-1/4 w-[450px] h-[450px] bg-[#00F1DE]/15 rounded-full blur-[140px] pointer-events-none"></div>
      <div class="absolute bottom-0 left-10 w-96 h-96 bg-[#00BFA5]/10 rounded-full blur-[130px] pointer-events-none"></div>

      <Container class="relative z-10 text-center max-w-4xl mx-auto">
        <!-- Top Pill Badge -->
        <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-white border border-[#A7EADF] text-[#008F7A] text-xs font-semibold tracking-wider uppercase mb-5 shadow-xs">
          <span class="w-2 h-2 rounded-full bg-[#00BFA5] animate-pulse"></span>
          Rekam Jejak Solusi & Rekayasa Sistem
        </div>

        <h1 class="text-3xl sm:text-5xl lg:text-6xl font-black text-[#0D2329] tracking-tight leading-tight uppercase">
          PORTOFOLIO PROYEK
        </h1>

        <div class="w-20 h-1.5 bg-[#00BFA5] rounded-full mx-auto my-5"></div>

        <p class="text-base sm:text-lg text-slate-600 leading-relaxed max-w-2xl mx-auto">
          Setiap sistem dibangun dengan arsitektur bersih, keamanan tinggi, dan logika bisnis yang disesuaikan secara presisi dengan kebutuhan unik setiap klien.
        </p>

        <!-- Stats / Key Metrics -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-10 max-w-3xl mx-auto">
          <div class="bg-white/90 backdrop-blur-sm p-4 rounded-2xl border border-[#D5F5EE] shadow-xs">
            <div class="text-2xl lg:text-3xl font-black text-[#00BFA5]">8+</div>
            <div class="text-xs font-semibold text-slate-600 uppercase tracking-wide mt-1">Sistem Produksi</div>
          </div>
          <div class="bg-white/90 backdrop-blur-sm p-4 rounded-2xl border border-[#D5F5EE] shadow-xs">
            <div class="text-2xl lg:text-3xl font-black text-[#0D2329]">100%</div>
            <div class="text-xs font-semibold text-slate-600 uppercase tracking-wide mt-1">Custom Built</div>
          </div>
          <div class="bg-white/90 backdrop-blur-sm p-4 rounded-2xl border border-[#D5F5EE] shadow-xs">
            <div class="text-2xl lg:text-3xl font-black text-[#00BFA5]">Multi</div>
            <div class="text-xs font-semibold text-slate-600 uppercase tracking-wide mt-1">Sektor Industri</div>
          </div>
          <div class="bg-white/90 backdrop-blur-sm p-4 rounded-2xl border border-[#D5F5EE] shadow-xs">
            <div class="text-2xl lg:text-3xl font-black text-[#0D2329]">Enterprise</div>
            <div class="text-xs font-semibold text-slate-600 uppercase tracking-wide mt-1">High Reliability</div>
          </div>
        </div>
      </Container>
    </section>

    <!-- ========================================================================= -->
    <!-- 2. FILTER TABS                                                            -->
    <!-- ========================================================================= -->
    <section class="py-6 bg-white border-b border-slate-100 sticky top-[72px] z-30 backdrop-blur-md bg-white/95">
      <Container>
        <div class="flex flex-wrap items-center justify-center gap-3">
          <button
            type="button"
            @click="activeFilter = 'all'"
            :class="[
              'inline-flex items-center gap-2 px-5 py-2.5 rounded-full text-xs sm:text-sm font-bold transition-all duration-200 cursor-pointer',
              activeFilter === 'all'
                ? 'bg-[#00BFA5] text-white shadow-md shadow-[#00BFA5]/20'
                : 'bg-slate-100 hover:bg-slate-200 text-slate-700'
            ]"
          >
            <span>Semua Proyek</span>
            <span 
              :class="[
                'px-2 py-0.5 rounded-full text-[10px] font-mono',
                activeFilter === 'all' ? 'bg-white/25 text-white' : 'bg-white text-slate-600'
              ]"
            >
              {{ counts.all }}
            </span>
          </button>

          <button
            type="button"
            @click="activeFilter = 'internal'"
            :class="[
              'inline-flex items-center gap-2 px-5 py-2.5 rounded-full text-xs sm:text-sm font-bold transition-all duration-200 cursor-pointer',
              activeFilter === 'internal'
                ? 'bg-[#00BFA5] text-white shadow-md shadow-[#00BFA5]/20'
                : 'bg-slate-100 hover:bg-slate-200 text-slate-700'
            ]"
          >
            <Layers class="w-4 h-4" />
            <span>Sistem Internal & ERP</span>
            <span 
              :class="[
                'px-2 py-0.5 rounded-full text-[10px] font-mono',
                activeFilter === 'internal' ? 'bg-white/25 text-white' : 'bg-white text-slate-600'
              ]"
            >
              {{ counts.internal }}
            </span>
          </button>

          <button
            type="button"
            @click="activeFilter = 'public'"
            :class="[
              'inline-flex items-center gap-2 px-5 py-2.5 rounded-full text-xs sm:text-sm font-bold transition-all duration-200 cursor-pointer',
              activeFilter === 'public'
                ? 'bg-[#00BFA5] text-white shadow-md shadow-[#00BFA5]/20'
                : 'bg-slate-100 hover:bg-slate-200 text-slate-700'
            ]"
          >
            <Globe class="w-4 h-4" />
            <span>Sistem Publik & Web Platform</span>
            <span 
              :class="[
                'px-2 py-0.5 rounded-full text-[10px] font-mono',
                activeFilter === 'public' ? 'bg-white/25 text-white' : 'bg-white text-slate-600'
              ]"
            >
              {{ counts.public }}
            </span>
          </button>
        </div>
      </Container>
    </section>

    <!-- ========================================================================= -->
    <!-- 3. PROJECTS GRID                                                          -->
    <!-- ========================================================================= -->
    <section class="py-16 lg:py-24 bg-[#FAFDFF]">
      <Container>
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-10">
          <article
            v-for="project in filteredProjects"
            :key="project.id"
            :id="project.slug"
            class="flex flex-col justify-between bg-white rounded-3xl border border-[#D5F5EE] shadow-[0_10px_30px_-5px_rgba(0,191,165,0.06)] hover:border-[#00BFA5] hover:shadow-[0_20px_40px_-15px_rgba(0,191,165,0.15)] transition-all duration-300 overflow-hidden group"
          >
            <!-- Top Image Showcase Area -->
            <div class="relative bg-slate-900 overflow-hidden border-b border-[#E2F7F2]">
              <!-- Main Active Image -->
              <div 
                class="relative aspect-[16/10] sm:aspect-[16/9] w-full overflow-hidden cursor-pointer group/img"
                @click="openLightbox(project, activeImageIndex[project.id] ?? 0)"
              >
                <img
                  :src="getActiveImage(project)"
                  :alt="project.title"
                  class="w-full h-full object-cover object-top transition-transform duration-500 group-hover/img:scale-105"
                  loading="lazy"
                />

                <!-- Dark gradient overlay on hover -->
                <div class="absolute inset-0 bg-gradient-to-t from-[#011E26]/80 via-transparent to-transparent opacity-0 group-hover/img:opacity-100 transition-opacity duration-300 flex items-end justify-between p-4">
                  <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-white/90 backdrop-blur-sm text-[#011E26] text-xs font-semibold shadow-sm">
                    <Maximize2 class="w-3.5 h-3.5 text-[#00BFA5]" />
                    Klik untuk Memperbesar
                  </span>
                  <span v-if="project.images.length > 1" class="text-white text-xs font-mono bg-black/60 px-2.5 py-1 rounded-full">
                    {{ (activeImageIndex[project.id] ?? 0) + 1 }} / {{ project.images.length }}
                  </span>
                </div>
              </div>

              <!-- Top Floating Badge -->
              <div class="absolute top-4 left-4 z-10 flex flex-wrap gap-2">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold tracking-wide bg-white/95 backdrop-blur-md text-[#0D2329] shadow-sm border border-slate-200/80">
                  <span class="w-2 h-2 rounded-full" :class="project.category === 'internal' ? 'bg-[#00BFA5]' : 'bg-[#00F1DE]'"></span>
                  {{ project.tag }}
                </span>
                <span v-if="project.featured" class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-extrabold uppercase tracking-wider bg-[#00BFA5] text-white shadow-sm">
                  <Sparkles class="w-3 h-3" />
                  Unggulan
                </span>
              </div>

              <!-- Multi-image Thumbnails Switcher (if project has more than 1 image) -->
              <div 
                v-if="project.images.length > 1" 
                class="absolute bottom-3 left-4 right-4 z-10 flex items-center justify-center gap-2 bg-[#011E26]/80 backdrop-blur-sm py-1.5 px-3 rounded-full max-w-fit mx-auto border border-white/20"
              >
                <button
                  v-for="(img, idx) in project.images"
                  :key="idx"
                  type="button"
                  @click.stop="setActiveImage(project.id, idx)"
                  :class="[
                    'h-2 rounded-full transition-all duration-200 cursor-pointer',
                    (activeImageIndex[project.id] ?? 0) === idx 
                      ? 'w-6 bg-[#00F1DE]' 
                      : 'w-2 bg-white/40 hover:bg-white/80'
                  ]"
                  :aria-label="`Pilih gambar ${idx + 1}`"
                />
              </div>
            </div>

            <!-- Card Content -->
            <div class="p-6 sm:p-8 flex flex-col flex-grow justify-between">
              <div>
                <!-- Category Overline -->
                <div class="flex items-center justify-between text-xs font-bold uppercase tracking-wider text-[#008F7A] mb-2">
                  <span>{{ project.categoryLabel }}</span>
                  <span v-if="project.link" class="text-[#00BFA5] flex items-center gap-1 font-semibold normal-case">
                    Live Platform
                  </span>
                </div>

                <!-- Title & Subtitle -->
                <h2 class="text-xl sm:text-2xl font-black text-[#0D2329] tracking-tight group-hover:text-[#00BFA5] transition-colors">
                  {{ project.title }}
                </h2>
                <p v-if="project.subtitle" class="text-xs font-medium text-slate-500 mt-0.5 mb-3">
                  {{ project.subtitle }}
                </p>

                <!-- Description -->
                <p class="text-sm text-slate-600 leading-relaxed mt-2 mb-5">
                  {{ project.description }}
                </p>

                <!-- Highlight / Result Box -->
                <div class="bg-[#F2FCFA] rounded-2xl p-3.5 border border-[#D0F3EB] mb-5 flex items-start gap-3">
                  <CheckCircle2 class="w-4 h-4 text-[#00BFA5] shrink-0 mt-0.5" />
                  <div class="text-xs text-slate-700 leading-relaxed">
                    <strong class="font-bold text-[#0D2329]">Hasil & Solusi:</strong> {{ project.highlight }}
                  </div>
                </div>

                <!-- Tech Stack Tags -->
                <div class="mb-6">
                  <div class="text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-2 flex items-center gap-1.5">
                    <Cpu class="w-3.5 h-3.5 text-[#00BFA5]" />
                    Teknologi & Fitur Utama:
                  </div>
                  <div class="flex flex-wrap gap-1.5">
                    <span
                      v-for="(tech, tIdx) in project.techStack"
                      :key="tIdx"
                      class="px-2.5 py-1 rounded-lg text-xs font-medium bg-slate-100 text-slate-700 border border-slate-200/60"
                    >
                      {{ tech }}
                    </span>
                  </div>
                </div>
              </div>

              <!-- Card Action Buttons -->
              <div class="pt-5 border-t border-slate-100 flex flex-wrap items-center justify-between gap-3">
                <!-- Left: Live URL or Detail Trigger -->
                <div class="flex items-center gap-2">
                  <a
                    v-if="project.link"
                    :href="project.link"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="inline-flex items-center gap-1.5 px-4 py-2 rounded-full text-xs font-bold text-[#0D2329] bg-[#EBF9F7] hover:bg-[#D7F5EF] border border-[#A7EADF] transition-colors"
                  >
                    <span>Kunjungi Website</span>
                    <ExternalLink class="w-3.5 h-3.5 text-[#00BFA5]" />
                  </a>
                  <button
                    type="button"
                    @click="openLightbox(project, 0)"
                    class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-full text-xs font-medium text-slate-600 hover:text-[#0D2329] hover:bg-slate-100 transition-colors cursor-pointer"
                  >
                    <Maximize2 class="w-3.5 h-3.5" />
                    <span>Lihat Preview</span>
                  </button>
                </div>

                <!-- Right: WhatsApp Inquiry for Similar Solution -->
                <a
                  :href="getWaInquiryUrl(project.title)"
                  target="_blank"
                  rel="noopener noreferrer"
                  class="inline-flex items-center gap-2 px-4 py-2 rounded-full text-xs font-bold bg-[#00BFA5] hover:bg-[#00A892] text-white shadow-xs hover:shadow-md transition-all group/btn"
                >
                  <MessageCircle class="w-3.5 h-3.5 text-white" />
                  <span>Konsultasi Serupa</span>
                  <ArrowRight class="w-3 h-3 group-hover/btn:translate-x-0.5 transition-transform" />
                </a>
              </div>
            </div>
          </article>
        </div>
      </Container>
    </section>

    <!-- ========================================================================= -->
    <!-- 4. BOTTOM CONSULTATION CALL-TO-ACTION                                     -->
    <!-- ========================================================================= -->
    <section class="py-16 lg:py-20 bg-white relative overflow-hidden">
      <Container>
        <div class="relative bg-gradient-to-br from-[#011E26] via-[#052A33] to-[#011E26] rounded-3xl p-8 sm:p-12 lg:p-16 text-white overflow-hidden shadow-xl border border-[#0D3841]">
          <!-- Glow Decorative -->
          <div class="absolute -right-16 -top-16 w-80 h-80 bg-[#00F1DE]/15 rounded-full blur-3xl pointer-events-none"></div>
          <div class="absolute -left-16 -bottom-16 w-80 h-80 bg-[#00BFA5]/15 rounded-full blur-3xl pointer-events-none"></div>

          <div class="relative z-10 max-w-3xl mx-auto text-center space-y-6">
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-[#00F1DE]/10 border border-[#00F1DE]/30 text-[#00F1DE] text-xs font-mono">
              <span class="w-1.5 h-1.5 rounded-full bg-[#00F1DE] animate-pulse"></span>
              Konsultasi & Perancangan Sistem Kustom
            </div>

            <h2 class="text-2xl sm:text-4xl lg:text-5xl font-black tracking-tight leading-tight">
              Punya Rencana Membangun Sistem atau Aplikasi Bisnis?
            </h2>

            <p class="text-slate-300 text-sm sm:text-base leading-relaxed max-w-2xl mx-auto">
              Diskusikan kebutuhan spesifik Anda dengan tim engineer Synbarra Technologies. Kami siap merancang arsitektur sistem yang tepat, efisien, dan dapat berkembang seiring pertumbuhan bisnis Anda.
            </p>

            <div class="flex flex-col sm:flex-row items-center justify-center gap-4 pt-4">
              <a
                :href="BRAND.contact.whatsappUrl"
                target="_blank"
                rel="noopener noreferrer"
                class="w-full sm:w-auto inline-flex items-center justify-center gap-2.5 px-8 py-3.5 rounded-full bg-[#00BFA5] hover:bg-[#00CBA9] text-[#011E26] font-bold text-sm shadow-lg shadow-[#00BFA5]/25 hover:shadow-xl hover:shadow-[#00BFA5]/40 transition-all transform hover:-translate-y-0.5"
              >
                <MessageCircle class="w-4 h-4" />
                <span>Konsultasi Gratis via WhatsApp</span>
              </a>

              <Link
                href="/contact"
                class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-7 py-3.5 rounded-full bg-white/10 hover:bg-white/15 text-white font-semibold text-sm border border-white/20 transition-all"
              >
                <span>Kirim Formulir Brief Proyek</span>
                <ArrowRight class="w-4 h-4" />
              </Link>
            </div>
          </div>
        </div>
      </Container>
    </section>

    <!-- ========================================================================= -->
    <!-- 5. LIGHTBOX MODAL                                                         -->
    <!-- ========================================================================= -->
    <Teleport to="body">
      <div 
        v-if="lightboxOpen && activeModalProject" 
        class="fixed inset-0 z-50 flex items-center justify-center bg-[#011E26]/90 backdrop-blur-md p-4 sm:p-6"
        @click="closeLightbox"
        role="dialog"
        aria-modal="true"
      >
        <div 
          class="relative max-w-5xl w-full bg-[#0D2329] rounded-3xl overflow-hidden border border-[#00BFA5]/30 shadow-2xl flex flex-col"
          @click.stop
        >
          <!-- Modal Header -->
          <div class="p-4 sm:p-5 border-b border-slate-700/80 flex items-center justify-between text-white bg-[#0A1D22]">
            <div>
              <div class="flex items-center gap-2">
                <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-[#00BFA5]/20 text-[#00F1DE] border border-[#00BFA5]/30">
                  {{ activeModalProject.tag }}
                </span>
                <span v-if="activeModalProject.images.length > 1" class="text-xs font-mono text-slate-400">
                  {{ currentModalImageIdx + 1 }} dari {{ activeModalProject.images.length }} foto
                </span>
              </div>
              <h3 class="text-base sm:text-lg font-bold text-white mt-1">
                {{ activeModalProject.title }}
              </h3>
            </div>

            <!-- Close Button -->
            <button
              type="button"
              @click="closeLightbox"
              class="p-2 rounded-full bg-white/10 hover:bg-white/20 text-white transition-colors cursor-pointer"
              aria-label="Tutup preview"
            >
              <X class="w-5 h-5" />
            </button>
          </div>

          <!-- Modal Body: Image Preview with Prev/Next Controls -->
          <div class="relative flex items-center justify-center bg-black/50 min-h-[300px] max-h-[70vh] overflow-hidden p-2 sm:p-4">
            <img
              :src="activeModalProject.images[currentModalImageIdx]"
              :alt="`${activeModalProject.title} ${currentModalImageIdx + 1}`"
              class="max-h-[65vh] w-auto max-w-full object-contain rounded-xl shadow-lg"
            />

            <!-- Prev Button -->
            <button
              v-if="activeModalProject.images.length > 1"
              type="button"
              @click.stop="prevModalImage"
              class="absolute left-4 p-3 rounded-full bg-black/60 hover:bg-[#00BFA5] text-white transition-all cursor-pointer shadow-lg backdrop-blur-sm"
              aria-label="Gambar sebelumnya"
            >
              <ChevronLeft class="w-6 h-6" />
            </button>

            <!-- Next Button -->
            <button
              v-if="activeModalProject.images.length > 1"
              type="button"
              @click.stop="nextModalImage"
              class="absolute right-4 p-3 rounded-full bg-black/60 hover:bg-[#00BFA5] text-white transition-all cursor-pointer shadow-lg backdrop-blur-sm"
              aria-label="Gambar berikutnya"
            >
              <ChevronRight class="w-6 h-6" />
            </button>
          </div>

          <!-- Modal Footer: Thumbnails & Action -->
          <div class="p-4 sm:p-5 bg-[#0A1D22] border-t border-slate-700/80 flex flex-wrap items-center justify-between gap-4">
            <!-- Thumbnail preview strip -->
            <div class="flex items-center gap-2 overflow-x-auto py-1">
              <button
                v-for="(img, idx) in activeModalProject.images"
                :key="idx"
                type="button"
                @click="currentModalImageIdx = idx"
                :class="[
                  'relative w-14 h-10 rounded-lg overflow-hidden border-2 transition-all cursor-pointer shrink-0',
                  currentModalImageIdx === idx 
                    ? 'border-[#00F1DE] scale-105' 
                    : 'border-transparent opacity-50 hover:opacity-100'
                ]"
              >
                <img :src="img" :alt="`Thumbnail ${idx + 1}`" class="w-full h-full object-cover" />
              </button>
            </div>

            <div class="flex items-center gap-3">
              <a
                v-if="activeModalProject.link"
                :href="activeModalProject.link"
                target="_blank"
                rel="noopener noreferrer"
                class="inline-flex items-center gap-1.5 px-4 py-2 rounded-full text-xs font-bold text-white bg-white/10 hover:bg-white/20 border border-white/20 transition-colors"
              >
                <span>Buka Link Proyek</span>
                <ExternalLink class="w-3.5 h-3.5 text-[#00F1DE]" />
              </a>

              <a
                :href="getWaInquiryUrl(activeModalProject.title)"
                target="_blank"
                rel="noopener noreferrer"
                class="inline-flex items-center gap-2 px-4 py-2 rounded-full text-xs font-bold bg-[#00BFA5] hover:bg-[#00CBA9] text-[#011E26] transition-all"
              >
                <MessageCircle class="w-3.5 h-3.5" />
                <span>Konsultasi Sistem Ini</span>
              </a>
            </div>
          </div>
        </div>
      </div>
    </Teleport>
  </AppLayout>
</template>
