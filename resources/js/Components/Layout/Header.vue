<script setup lang="ts">
import { ref, onMounted, onUnmounted } from 'vue';
import { Link } from '@inertiajs/vue3';
import { BRAND } from '@/config/brand';
import SynbarraLogo from '@/Components/UI/SynbarraLogo.vue';
import Container from '@/Components/UI/Container.vue';

const isScrolled = ref(false);
const mobileMenuOpen = ref(false);

const handleScroll = () => {
  isScrolled.value = window.scrollY > 20;
};

const toggleMobileMenu = () => {
  mobileMenuOpen.value = !mobileMenuOpen.value;
  if (mobileMenuOpen.value) {
    document.body.style.overflow = 'hidden';
  } else {
    document.body.style.overflow = '';
  }
};

const closeMobileMenu = () => {
  mobileMenuOpen.value = false;
  document.body.style.overflow = '';
};

onMounted(() => {
  window.addEventListener('scroll', handleScroll, { passive: true });
});

onUnmounted(() => {
  window.removeEventListener('scroll', handleScroll);
  document.body.style.overflow = '';
});
</script>

<template>
  <header
    :class="[
      'sticky top-0 z-50 w-full transition-all duration-300',
      isScrolled 
        ? 'bg-white/95 backdrop-blur-md border-b border-purple-100 shadow-sm py-3' 
        : 'bg-white/80 backdrop-blur-sm border-b border-transparent py-4 sm:py-5'
    ]"
  >
    <Container>
      <div class="flex items-center justify-between">
        <!-- Brand Logo -->
        <Link href="/" class="flex items-center group focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-violet-500 rounded-lg p-1">
          <SynbarraLogo size="md" variant="horizontal" />
        </Link>

        <!-- Desktop Navigation -->
        <nav class="hidden md:flex items-center space-x-1 lg:space-x-2" aria-label="Main Navigation">
          <a
            v-for="item in BRAND.navigation"
            :key="item.href"
            :href="item.href"
            class="px-3.5 py-2 text-sm font-medium text-slate-700 hover:text-violet-700 hover:bg-violet-50/60 rounded-full transition-colors duration-150"
          >
            {{ item.name }}
          </a>
        </nav>

        <!-- Action Buttons (Masuk & Coba Gratis sesuai Mockup) -->
        <div class="hidden md:flex items-center gap-3">
          <Link
            href="/login"
            class="px-5 py-2 text-sm font-semibold text-slate-700 hover:text-violet-700 hover:bg-slate-50 border border-slate-200 rounded-full transition-all duration-150"
          >
            Masuk
          </Link>
          <Link
            href="/register"
            class="px-5 py-2 text-sm font-semibold text-white bg-violet-700 hover:bg-violet-800 rounded-full shadow-sm hover:shadow transition-all duration-150 transform hover:-translate-y-0.5"
          >
            Coba Gratis
          </Link>
        </div>

        <!-- Mobile Menu Toggle Button -->
        <div class="flex items-center md:hidden">
          <button
            type="button"
            @click="toggleMobileMenu"
            class="p-2 text-slate-700 hover:text-violet-700 hover:bg-violet-50 rounded-lg focus-visible:outline-none"
            :aria-expanded="mobileMenuOpen"
            aria-label="Toggle navigation menu"
          >
            <svg v-if="!mobileMenuOpen" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
            <svg v-else class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
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
          class="fixed inset-0 z-[999] bg-white md:hidden flex flex-col justify-between overflow-y-auto"
        >
          <div class="flex items-center justify-between px-5 py-4 border-b border-slate-200 bg-white sticky top-0 z-10">
            <Link href="/" @click="closeMobileMenu" class="flex items-center">
              <SynbarraLogo size="md" variant="horizontal" />
            </Link>
            <button
              type="button"
              @click="closeMobileMenu"
              class="p-2 text-slate-700 hover:text-violet-700 hover:bg-violet-50 rounded-lg focus:outline-none"
              aria-label="Tutup menu navigasi"
            >
              <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
          </div>

          <nav class="flex flex-col p-6 space-y-2 flex-grow" aria-label="Mobile Navigation">
            <a
              v-for="item in BRAND.navigation"
              :key="item.href"
              :href="item.href"
              @click="closeMobileMenu"
              class="px-4 py-3 text-base font-medium text-slate-800 hover:text-violet-700 hover:bg-violet-50 rounded-xl transition"
            >
              {{ item.name }}
            </a>
          </nav>

          <div class="p-6 border-t border-slate-100 bg-slate-50 space-y-3">
            <Link
              href="/login"
              @click="closeMobileMenu"
              class="block w-full text-center py-3 text-sm font-semibold text-slate-800 bg-white border border-slate-200 rounded-full shadow-sm"
            >
              Masuk
            </Link>
            <Link
              href="/register"
              @click="closeMobileMenu"
              class="block w-full text-center py-3 text-sm font-semibold text-white bg-violet-700 hover:bg-violet-800 rounded-full shadow-md"
            >
              Coba Gratis 14 Hari
            </Link>
          </div>
        </div>
      </Transition>
    </Teleport>
  </header>
</template>
