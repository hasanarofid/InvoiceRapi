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
            <div class="pt-2">
              <Link 
                href="/contact" 
                class="inline-flex items-center gap-1.5 text-xs font-medium text-[#00F1DE] hover:underline"
              >
                Start a Conversation &rarr;
              </Link>
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
