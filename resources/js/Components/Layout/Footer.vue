<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { BRAND } from '@/config/brand';
import SynbarraLogo from '@/Components/UI/SynbarraLogo.vue';
import Container from '@/Components/UI/Container.vue';

const currentYear = new Date().getFullYear();
</script>

<template>
  <footer class="bg-[#011E26] text-white border-t border-[#0A3B43] relative overflow-hidden">
    <!-- Subtle background turquoise glow -->
    <div class="absolute top-0 right-1/4 w-96 h-96 bg-[#00F1DE]/5 rounded-full blur-3xl pointer-events-none"></div>

    <Container class="pt-16 pb-12 relative z-10">
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-10 lg:gap-12 pb-12 border-b border-[#0A3B43]/80">
        <!-- Brand Column -->
        <div class="lg:col-span-5 flex flex-col space-y-4">
          <Link href="/" class="inline-block focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#00F1DE] rounded-lg">
            <SynbarraLogo size="lg" theme="dark" />
          </Link>
          <p class="text-slate-300 text-sm leading-relaxed max-w-sm mt-2">
            {{ BRAND.description }}
          </p>
          <div class="pt-2">
            <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-mono text-[#00F1DE] bg-[#00F1DE]/10 border border-[#00F1DE]/20">
              <span class="w-1.5 h-1.5 rounded-full bg-[#00F1DE] animate-pulse"></span>
              {{ BRAND.tagline }}
            </span>
          </div>
        </div>

        <!-- Quick Links -->
        <div class="lg:col-span-2 sm:col-span-1">
          <h3 class="text-xs font-semibold uppercase tracking-wider text-[#04D2C5] mb-4">
            Navigation
          </h3>
          <ul class="space-y-2.5">
            <li v-for="item in BRAND.navigation" :key="item.href">
              <Link 
                :href="item.href"
                class="text-sm text-slate-300 hover:text-[#00F1DE] transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-[#00F1DE]"
              >
                {{ item.label }}
              </Link>
            </li>
          </ul>
        </div>

        <!-- Services -->
        <div class="lg:col-span-3 sm:col-span-1">
          <h3 class="text-xs font-semibold uppercase tracking-wider text-[#04D2C5] mb-4">
            Services
          </h3>
          <ul class="space-y-2.5">
            <li v-for="service in BRAND.services" :key="service.id">
              <Link 
                :href="service.href"
                class="text-sm text-slate-300 hover:text-[#00F1DE] transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-[#00F1DE]"
              >
                {{ service.title }}
              </Link>
            </li>
          </ul>
        </div>

        <!-- Direct Inquiries / Contact Column -->
        <div class="lg:col-span-2">
          <h3 class="text-xs font-semibold uppercase tracking-wider text-[#04D2C5] mb-4">
            Direct Inquiries
          </h3>
          <div class="space-y-3 text-sm text-slate-300">
            <div>
              <span class="block text-xs text-slate-400">Official Domain</span>
              <a 
                :href="BRAND.url" 
                class="text-white font-medium hover:text-[#00F1DE] transition-colors"
                target="_blank"
                rel="noopener"
              >
                {{ BRAND.domain }}
              </a>
            </div>
            <div>
              <span class="block text-xs text-slate-400">Email</span>
              <a 
                :href="'mailto:' + BRAND.contact.email" 
                class="text-slate-200 hover:text-[#00F1DE] transition-colors break-all"
              >
                {{ BRAND.contact.email }}
              </a>
            </div>
            <div>
              <span class="block text-xs text-slate-400">WhatsApp</span>
              <a 
                :href="BRAND.contact.whatsappUrl" 
                target="_blank"
                rel="noopener"
                class="text-slate-200 hover:text-[#00F1DE] transition-colors"
              >
                {{ BRAND.contact.whatsappDisplay }}
              </a>
            </div>
            <div>
              <span class="block text-xs text-slate-400">Instagram</span>
              <a 
                :href="BRAND.contact.instagramUrl" 
                target="_blank"
                rel="noopener"
                class="text-[#00F1DE] hover:underline transition-colors flex items-center gap-1.5"
              >
                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                {{ BRAND.contact.instagram }}
              </a>
            </div>
            <div class="pt-2">
              <a 
                :href="BRAND.contact.whatsappUrl" 
                target="_blank"
                rel="noopener"
                class="inline-flex items-center gap-1.5 text-xs font-semibold text-[#00F1DE] hover:underline"
              >
                Konsultasi WhatsApp &rarr;
              </a>
            </div>
          </div>
        </div>
      </div>

      <!-- Bottom Bar -->
      <div class="pt-8 flex flex-col sm:flex-row items-center justify-between text-xs text-slate-400 gap-4">
        <p>
          &copy; {{ currentYear }} {{ BRAND.legalName }}. All rights reserved.
        </p>
        <p class="text-slate-400 font-mono text-[11px]">
          {{ BRAND.tagline }}
        </p>
      </div>
    </Container>
  </footer>
</template>
