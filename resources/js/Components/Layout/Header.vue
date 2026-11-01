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
          <a
            :href="BRAND.contact.whatsappUrl"
            target="_blank"
            rel="noopener noreferrer"
            class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full text-xs font-semibold text-white bg-gradient-to-r from-[#0D2329] to-[#0A4D4F] hover:from-[#00BFA5] hover:to-[#00D9C5] shadow-sm hover:shadow-md transition-all duration-200 transform hover:-translate-y-0.5"
          >
            <span class="w-2 h-2 rounded-full bg-[#00F1DE] animate-pulse"></span>
            Konsultasi Sekarang
          </a>
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

    <!-- Mobile Navigation Drawer Teleported Directly to Body (Prevents backdrop-filter clipping) -->
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
          <!-- Mobile Drawer Top Bar -->
          <div class="flex items-center justify-between px-5 py-4 border-b border-slate-200 bg-white sticky top-0 z-10">
            <Link href="/" @click="closeMobileMenu" class="flex items-center">
              <SynbarraLogo size="md" />
            </Link>
            <button
              type="button"
              @click="closeMobileMenu"
              class="p-2 text-slate-700 hover:text-[#01232B] hover:bg-slate-100 rounded-lg focus:outline-none"
              aria-label="Tutup menu navigasi"
            >
              <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
          </div>

          <!-- Navigation Links -->
          <nav class="flex flex-col p-6 space-y-1.5 flex-grow" aria-label="Mobile Navigation">
            <Link
              v-for="item in BRAND.navigation"
              :key="item.href"
              :href="item.href"
              @click="closeMobileMenu"
              class="px-4 py-3 text-base font-semibold text-slate-800 hover:text-[#01232B] hover:bg-slate-100 rounded-xl transition-colors flex items-center justify-between"
            >
              <span>{{ item.label }}</span>
              <span class="text-slate-400 text-sm font-normal">&rarr;</span>
            </Link>
          </nav>

          <!-- Drawer Footer with CTAs & Official Contacts -->
          <div class="p-6 border-t border-slate-200 bg-slate-50 flex flex-col gap-3">
            <Button href="/contact" variant="primary" size="lg" @click="closeMobileMenu" class="w-full text-center">
              Let's Discuss Your Project
            </Button>
            <a 
              :href="BRAND.contact.whatsappUrl" 
              target="_blank" 
              rel="noopener noreferrer"
              class="w-full py-3 px-4 rounded-xl bg-[#25D366] text-white font-semibold text-sm text-center flex items-center justify-center gap-2 shadow-sm active:scale-98 transition-transform"
            >
              <span>Chat WhatsApp: {{ BRAND.contact.whatsappDisplay }}</span>
            </a>
            <p class="text-[11px] text-center text-slate-500 pt-1">
              {{ BRAND.contact.email }} &bull; {{ BRAND.domain }}
            </p>
          </div>
        </div>
      </Transition>
    </Teleport>
  </header>
</template>
