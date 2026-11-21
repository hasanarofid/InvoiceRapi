<script setup lang="ts">
import { ref, onMounted, onUnmounted } from 'vue';
import { Link } from '@inertiajs/vue3';
import { BRAND } from '@/config/brand';
import Container from '@/Components/UI/Container.vue';
import { ChevronDown, Menu, X, Home, Sparkles, ShoppingBag, LogIn } from 'lucide-vue-next';

const isScrolled = ref(false);
const mobileMenuOpen = ref(false);
const activeDropdown = ref<string | null>(null);
const mobileDropdown = ref<string | null>(null);

const getDropdownItems = (key?: string) => {
  if (key === 'fitur') return BRAND.featureMenus;
  if (key === 'solusi') return BRAND.solutionMenus;
  return [];
};

const handleScroll = () => {
  isScrolled.value = window.scrollY > 16;
};

const handleKeydown = (e: KeyboardEvent) => {
  if (e.key === 'Escape') {
    activeDropdown.value = null;
    if (mobileMenuOpen.value) {
      closeMobileMenu();
    }
  }
};

const toggleMobileMenu = () => {
  mobileMenuOpen.value = !mobileMenuOpen.value;
  document.body.style.overflow = mobileMenuOpen.value ? 'hidden' : '';
};

const closeMobileMenu = () => {
  mobileMenuOpen.value = false;
  document.body.style.overflow = '';
};

onMounted(() => {
  window.addEventListener('scroll', handleScroll, { passive: true });
  window.addEventListener('keydown', handleKeydown);
});

onUnmounted(() => {
  window.removeEventListener('scroll', handleScroll);
  window.removeEventListener('keydown', handleKeydown);
  document.body.style.overflow = '';
});
</script>

<template>
  <header
    :class="[
      'sticky top-0 z-50 w-full transition-all duration-300',
      isScrolled
        ? 'bg-white/95 backdrop-blur-md border-b border-violet-100/80 shadow-invoicerapi-sm py-3'
        : 'bg-[#FAF8FF]/85 backdrop-blur-sm border-b border-transparent py-4'
    ]"
  >
    <Container>
      <div class="flex items-center justify-between gap-4">
        <!-- Brand Logo Lockup (SVG + Typography, sharp on all displays) -->
        <Link
          href="/"
          class="flex items-center gap-2.5 group focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-violet-500 rounded-xl p-1 shrink-0"
        >
          <span class="w-9 h-9 rounded-xl bg-gradient-to-br from-violet-600 to-violet-800 text-white flex items-center justify-center shadow-invoicerapi-sm group-hover:scale-105 transition-transform">
            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
              <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
              <polyline points="14 2 14 8 20 8" />
              <line x1="8" y1="13" x2="16" y2="13" />
              <line x1="8" y1="17" x2="13" y2="17" />
            </svg>
          </span>
          <span class="flex flex-col leading-none">
            <span class="text-[11px] font-bold tracking-wide text-violet-700">Synbarra</span>
            <span class="text-base sm:text-lg font-extrabold tracking-tight text-slate-900">InvoiceRapi</span>
          </span>
        </Link>

        <!-- Desktop Navigation -->
        <nav class="hidden lg:flex items-center gap-1" aria-label="Navigasi Utama">
          <template v-for="item in BRAND.navigation" :key="item.name">
            <!-- Dropdown item for Fitur & Solusi -->
            <div
              v-if="item.dropdownKey"
              class="relative"
              @mouseenter="activeDropdown = item.dropdownKey"
              @mouseleave="activeDropdown = null"
            >
              <button
                type="button"
                @click="activeDropdown = activeDropdown === item.dropdownKey ? null : item.dropdownKey"
                :aria-expanded="activeDropdown === item.dropdownKey"
                class="inline-flex items-center gap-1 px-3.5 py-2 text-sm font-medium text-slate-700 hover:text-violet-700 hover:bg-violet-50/70 rounded-xl transition-colors"
              >
                <span>{{ item.name }}</span>
                <ChevronDown
                  class="w-3.5 h-3.5 text-slate-400 transition-transform duration-150"
                  :class="{ 'rotate-180 text-violet-700': activeDropdown === item.dropdownKey }"
                  aria-hidden="true"
                />
              </button>

              <Transition
                enter-active-class="transition duration-150 ease-out"
                enter-from-class="opacity-0 translate-y-1"
                enter-to-class="opacity-100 translate-y-0"
                leave-active-class="transition duration-100 ease-in"
                leave-from-class="opacity-100 translate-y-0"
                leave-to-class="opacity-0 translate-y-1"
              >
                <div
                  v-if="activeDropdown === item.dropdownKey"
                  class="absolute left-0 top-full pt-2 w-80 z-50"
                >
                  <div class="bg-white rounded-2xl border border-violet-100 shadow-invoicerapi-lg p-2 space-y-1">
                    <Link
                      v-for="sub in getDropdownItems(item.dropdownKey)"
                      :key="sub.href"
                      :href="sub.href"
                      @click="activeDropdown = null"
                      class="block px-3.5 py-2.5 rounded-xl hover:bg-violet-50/80 transition-colors group"
                    >
                      <p class="text-xs font-bold text-slate-900 group-hover:text-violet-700">{{ sub.title }}</p>
                      <p class="text-[11px] text-slate-500 mt-0.5 line-clamp-1">{{ sub.tag }}</p>
                    </Link>
                  </div>
                </div>
              </Transition>
            </div>

            <!-- Standard Nav Link -->
            <Link
              v-else-if="item.href === '/'"
              :href="item.href"
              class="px-3.5 py-2 text-sm font-medium text-slate-700 hover:text-violet-700 hover:bg-violet-50/70 rounded-xl transition-colors"
            >
              {{ item.name }}
            </Link>
            <a
              v-else
              :href="item.href"
              class="px-3.5 py-2 text-sm font-medium text-slate-700 hover:text-violet-700 hover:bg-violet-50/70 rounded-xl transition-colors"
            >
              {{ item.name }}
            </a>
          </template>
        </nav>

        <!-- Action Buttons (Masuk & Coba Gratis) -->
        <div class="hidden lg:flex items-center gap-2.5 shrink-0">
          <Link
            href="/login"
            class="px-5 py-2 text-sm font-semibold text-violet-700 hover:text-violet-800 bg-white hover:bg-violet-50/70 border border-violet-200 rounded-xl transition-all"
          >
            Masuk
          </Link>
          <Link
            href="/register"
            class="px-5 py-2 text-sm font-semibold text-white bg-violet-700 hover:bg-violet-800 rounded-xl shadow-invoicerapi-sm hover:shadow-invoicerapi-md transition-all transform hover:-translate-y-0.5"
          >
            Coba Gratis
          </Link>
        </div>

        <!-- Mobile Menu Toggle Button -->
        <div class="flex items-center lg:hidden">
          <button
            type="button"
            @click="toggleMobileMenu"
            class="p-2 text-slate-700 hover:text-violet-700 hover:bg-violet-50 rounded-xl focus-visible:outline-none"
            :aria-expanded="mobileMenuOpen"
            aria-controls="mobile-nav-drawer"
            aria-label="Toggle menu navigasi"
          >
            <Menu v-if="!mobileMenuOpen" class="w-6 h-6" aria-hidden="true" />
            <X v-else class="w-6 h-6" aria-hidden="true" />
          </button>
        </div>
      </div>
    </Container>

    <!-- Mobile Drawer -->
    <Teleport to="body">
      <Transition
        enter-active-class="transition duration-200 ease-out"
        enter-from-class="opacity-0 -translate-y-2"
        enter-to-class="opacity-100 translate-y-0"
        leave-active-class="transition duration-150 ease-in"
        leave-from-class="opacity-100 translate-y-0"
        leave-to-class="opacity-0 -translate-y-2"
      >
        <div
          v-if="mobileMenuOpen"
          id="mobile-nav-drawer"
          class="fixed inset-0 z-[999] bg-white lg:hidden flex flex-col justify-between overflow-y-auto"
        >
          <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100 bg-white sticky top-0 z-10">
            <Link href="/" @click="closeMobileMenu" class="flex items-center gap-2.5">
              <span class="w-8 h-8 rounded-xl bg-gradient-to-br from-violet-600 to-violet-800 text-white flex items-center justify-center shadow-sm">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                  <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                  <polyline points="14 2 14 8 20 8" />
                </svg>
              </span>
              <span class="flex flex-col leading-none">
                <span class="text-[10px] font-bold text-violet-700">Synbarra</span>
                <span class="text-base font-extrabold text-slate-900">InvoiceRapi</span>
              </span>
            </Link>
            <button
              type="button"
              @click="closeMobileMenu"
              class="p-2 text-slate-700 hover:text-violet-700 hover:bg-violet-50 rounded-xl"
              aria-label="Tutup menu navigasi"
            >
              <X class="w-6 h-6" aria-hidden="true" />
            </button>
          </div>

          <nav class="flex flex-col p-5 space-y-1.5 flex-grow" aria-label="Navigasi Mobile">
            <template v-for="item in BRAND.navigation" :key="item.name">
              <div v-if="item.dropdownKey" class="space-y-1">
                <button
                  type="button"
                  @click="mobileDropdown = mobileDropdown === item.dropdownKey ? null : item.dropdownKey"
                  :aria-expanded="mobileDropdown === item.dropdownKey"
                  class="w-full flex items-center justify-between px-4 py-3 text-base font-semibold text-slate-800 hover:text-violet-700 hover:bg-violet-50 rounded-xl transition"
                >
                  <span>{{ item.name }}</span>
                  <ChevronDown class="w-4 h-4 transition-transform" :class="{ 'rotate-180': mobileDropdown === item.dropdownKey }" />
                </button>
                <div v-show="mobileDropdown === item.dropdownKey" class="pl-4 space-y-1">
                  <Link
                    v-for="sub in getDropdownItems(item.dropdownKey)"
                    :key="sub.href"
                    :href="sub.href"
                    @click="closeMobileMenu"
                    class="block px-4 py-2 text-sm text-slate-600 hover:text-violet-700 hover:bg-violet-50/60 rounded-lg"
                  >
                    {{ sub.title }}
                  </Link>
                </div>
              </div>
              <a
                v-else
                :href="item.href"
                @click="closeMobileMenu"
                class="px-4 py-3 text-base font-semibold text-slate-800 hover:text-violet-700 hover:bg-violet-50 rounded-xl transition"
              >
                {{ item.name }}
              </a>
            </template>
          </nav>

          <div class="p-5 border-t border-slate-100 bg-[#FAF8FF] space-y-3">
            <Link
              href="/login"
              @click="closeMobileMenu"
              class="block w-full text-center py-3 text-sm font-semibold text-violet-700 bg-white border border-violet-200 rounded-xl shadow-sm"
            >
              Masuk
            </Link>
            <Link
              href="/register"
              @click="closeMobileMenu"
              class="block w-full text-center py-3 text-sm font-semibold text-white bg-violet-700 hover:bg-violet-800 rounded-xl shadow-md"
            >
              Coba Gratis 14 Hari
            </Link>
          </div>
        </div>
      </Transition>

      <!-- Mobile App Bottom Navigation Bar (< lg) -->
      <nav
        class="lg:hidden fixed bottom-0 inset-x-0 z-40 bg-white/95 backdrop-blur-lg border-t border-violet-100 shadow-[0_-8px_30px_rgba(15,23,42,0.08)] pb-[env(safe-area-inset-bottom)]"
        aria-label="Navigasi Aplikasi Mobile"
      >
        <div class="grid grid-cols-5 h-16 max-w-md mx-auto">
          <Link
            href="/"
            class="flex flex-col items-center justify-center gap-1 text-[10px] font-bold transition"
            :class="$page.url === '/' ? 'text-violet-700' : 'text-slate-500 hover:text-violet-600'"
          >
            <Home class="w-5 h-5" />
            <span>Beranda</span>
          </Link>
          <a
            href="/#fitur"
            class="flex flex-col items-center justify-center gap-1 text-[10px] font-bold text-slate-500 hover:text-violet-600 transition"
          >
            <Sparkles class="w-5 h-5" />
            <span>Fitur</span>
          </a>
          <Link
            href="/produk"
            class="flex flex-col items-center justify-center gap-1 text-[10px] font-bold transition"
            :class="$page.url.startsWith('/produk') ? 'text-violet-700' : 'text-slate-500 hover:text-violet-600'"
          >
            <ShoppingBag class="w-5 h-5" />
            <span>Produk</span>
          </Link>
          <Link
            href="/login"
            class="flex flex-col items-center justify-center gap-1 text-[10px] font-bold transition"
            :class="$page.url.startsWith('/login') ? 'text-violet-700' : 'text-slate-500 hover:text-violet-600'"
          >
            <LogIn class="w-5 h-5" />
            <span>Masuk</span>
          </Link>
          <button
            type="button"
            @click="toggleMobileMenu"
            class="flex flex-col items-center justify-center gap-1 text-[10px] font-bold transition"
            :class="mobileMenuOpen ? 'text-violet-700' : 'text-slate-500 hover:text-violet-600'"
          >
            <Menu class="w-5 h-5" />
            <span>Menu</span>
          </button>
        </div>
      </nav>
    </Teleport>
  </header>
</template>
