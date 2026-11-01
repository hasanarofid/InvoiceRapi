<script setup lang="ts">
import { ref, onMounted, onUnmounted } from 'vue';
import { Link } from '@inertiajs/vue3';
import { BRAND } from '@/config/brand';
import SynbarraLogo from '@/Components/UI/SynbarraLogo.vue';
import Button from '@/Components/UI/Button.vue';
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
        ? 'bg-white/95 backdrop-blur-md border-b border-slate-200/80 shadow-synbarra-sm py-3' 
        : 'bg-white/80 backdrop-blur-sm border-b border-transparent py-4 sm:py-5'
    ]"
  >
    <Container>
      <div class="flex items-center justify-between">
        <!-- Brand Logo -->
        <Link href="/" class="flex items-center group focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#00F1DE] rounded-lg p-1">
          <SynbarraLogo size="md" />
        </Link>

        <!-- Desktop Navigation -->
        <nav class="hidden md:flex items-center space-x-1 lg:space-x-2" aria-label="Main Navigation">
          <Link
            v-for="item in BRAND.navigation"
            :key="item.href"
            :href="item.href"
            class="px-3.5 py-2 text-sm font-medium text-slate-700 hover:text-[#01232B] hover:bg-slate-50 rounded-lg transition-colors duration-150 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#00F1DE]"
          >
            {{ item.label }}
          </Link>
        </nav>

        <!-- Primary CTA Desktop -->
        <div class="hidden md:flex items-center gap-3">
          <Button href="/contact" variant="primary" size="sm">
            Let's Discuss Your Project
          </Button>
        </div>

        <!-- Mobile Menu Toggle Button -->
        <div class="flex items-center md:hidden">
          <button
            type="button"
            @click="toggleMobileMenu"
            class="p-2 text-slate-700 hover:text-[#01232B] hover:bg-slate-100 rounded-lg focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#00F1DE]"
            :aria-expanded="mobileMenuOpen"
            aria-label="Toggle navigation menu"
          >
            <!-- Hamburger icon -->
            <svg v-if="!mobileMenuOpen" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
            <!-- Close icon -->
            <svg v-else class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>
      </div>
    </Container>

    <!-- Mobile Navigation Drawer -->
    <div
      v-if="mobileMenuOpen"
      class="fixed inset-0 top-[65px] z-40 bg-white/95 backdrop-blur-lg md:hidden flex flex-col justify-between p-6 overflow-y-auto"
    >
      <nav class="flex flex-col space-y-3">
        <Link
          v-for="item in BRAND.navigation"
          :key="item.href"
          :href="item.href"
          @click="closeMobileMenu"
          class="px-4 py-3 text-lg font-semibold text-slate-800 hover:text-[#01232B] hover:bg-slate-100 rounded-xl transition-colors"
        >
          {{ item.label }}
        </Link>
      </nav>

      <div class="pt-6 border-t border-slate-200 flex flex-col gap-4">
        <Button href="/contact" variant="primary" size="lg" @click="closeMobileMenu" class="w-full text-center">
          Let's Discuss Your Project
        </Button>
        <p class="text-xs text-center text-slate-500">
          {{ BRAND.tagline }}
        </p>
      </div>
    </div>
  </header>
</template>
