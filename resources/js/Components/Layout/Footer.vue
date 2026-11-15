<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { BRAND } from '@/config/brand';
import Container from '@/Components/UI/Container.vue';

const currentYear = new Date().getFullYear();
</script>

<template>
  <footer class="bg-slate-900 text-white border-t border-slate-800 relative overflow-hidden">
    <!-- Subtle purple glow in background -->
    <div class="absolute top-0 right-1/4 w-96 h-96 bg-violet-600/10 rounded-full blur-3xl pointer-events-none"></div>

    <Container class="pt-16 pb-12 relative z-10">
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-10 lg:gap-12 pb-12 border-b border-slate-800">
        <!-- Brand Column -->
        <div class="lg:col-span-5 flex flex-col space-y-4">
          <Link href="/" class="inline-flex items-center gap-3 rounded-lg w-fit">
            <span class="w-10 h-10 rounded-xl bg-gradient-to-br from-violet-500 to-violet-700 text-white flex items-center justify-center shadow-md">
              <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                <polyline points="14 2 14 8 20 8" />
                <line x1="8" y1="13" x2="16" y2="13" />
                <line x1="8" y1="17" x2="13" y2="17" />
              </svg>
            </span>
            <span class="flex flex-col leading-none">
              <span class="text-xs font-bold tracking-wide text-violet-400">Synbarra</span>
              <span class="text-xl font-extrabold tracking-tight text-white">InvoiceRapi</span>
            </span>
          </Link>
          <p class="text-slate-300 text-sm leading-relaxed max-w-sm mt-2">
            {{ BRAND.description }}
          </p>
          <div class="pt-2">
            <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full text-xs font-medium text-violet-300 bg-violet-950/60 border border-violet-800">
              <span class="w-2 h-2 rounded-full bg-violet-400 animate-pulse"></span>
              {{ BRAND.tagline }}
            </span>
          </div>
        </div>

        <!-- Quick Links -->
        <div class="lg:col-span-3 sm:col-span-1">
          <h3 class="text-xs font-semibold uppercase tracking-wider text-violet-400 mb-4">
            Menu Navigasi
          </h3>
          <ul class="space-y-2.5">
            <li v-for="item in BRAND.navigation" :key="item.href">
              <a 
                :href="item.href"
                class="text-sm text-slate-300 hover:text-white transition-colors"
              >
                {{ item.name }}
              </a>
            </li>
          </ul>
        </div>

        <!-- Solusi Industri Jasa -->
        <div class="lg:col-span-4 sm:col-span-1">
          <h3 class="text-xs font-semibold uppercase tracking-wider text-violet-400 mb-4">
            Solusi Bisnis Jasa
          </h3>
          <ul class="space-y-2.5">
            <li v-for="persona in BRAND.personas" :key="persona.title">
              <span class="text-sm text-slate-300 block">
                {{ persona.title }}
              </span>
            </li>
          </ul>
          <div class="mt-6 pt-4 border-t border-slate-800">
            <p class="text-xs text-slate-400">
              Produk SaaS resmi dikembangkan oleh <a href="https://synbarra.com" target="_blank" class="text-violet-400 hover:underline">Synbarra Technologies</a>.
            </p>
          </div>
        </div>
      </div>

      <!-- Copyright -->
      <div class="pt-8 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-slate-400">
        <p>&copy; {{ currentYear }} {{ BRAND.name }}. All rights reserved.</p>
        <div class="flex items-center gap-6">
          <a :href="'https://wa.me/' + BRAND.contact.whatsapp" target="_blank" class="hover:text-white transition">WhatsApp Support</a>
          <a :href="'mailto:' + BRAND.contact.email" class="hover:text-white transition">Email</a>
          <a href="https://synbarra.com" target="_blank" class="hover:text-white transition">synbarra.com</a>
        </div>
      </div>
    </Container>
  </footer>
</template>
