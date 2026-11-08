<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { BRAND } from '@/config/brand';
import SynbarraLogo from '@/Components/UI/SynbarraLogo.vue';
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
          <Link href="/" class="inline-block rounded-lg">
            <SynbarraLogo size="lg" theme="dark" />
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
