<script setup>
import { Link } from '@inertiajs/vue3';
import SynbarraLogo from '@/Components/UI/SynbarraLogo.vue';
</script>

<template>
  <div class="min-h-screen bg-[#F8FAFA] text-slate-900 font-sans antialiased relative overflow-hidden flex flex-col items-center justify-center p-4 sm:p-6 selection:bg-[#00F1DE]/30 selection:text-[#01232B]">
    <!-- Ambient Background Radial Light -->
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[600px] h-[350px] bg-gradient-to-b from-[#00F1DE]/10 to-transparent blur-3xl pointer-events-none"></div>

    <!-- Login Card Container -->
    <div class="w-full sm:max-w-md bg-white border border-slate-200 shadow-xl shadow-slate-200/60 rounded-2xl p-6 sm:p-8 relative z-10 space-y-6">
      <!-- Synbarra Brand Identity -->
      <div class="flex flex-col items-center text-center gap-3">
        <Link href="/" class="group flex items-center justify-center p-2 rounded-2xl bg-white transition focus-visible:outline-none">
          <SynbarraLogo size="lg" />
        </Link>
        <div>
          <p class="text-xs text-slate-500 font-medium mt-1">
            Corporate Administration Portal
          </p>
        </div>
      </div>

      <!-- Form Slot -->
      <div class="space-y-4">
        <slot />
      </div>
    </div>

    <!-- Footer Back Link -->
    <div class="mt-6 text-center text-xs text-slate-500">
      <Link href="/" class="hover:text-[#01232B] font-medium transition flex items-center gap-1 justify-center">
        &larr; Kembali ke Beranda Synbarra
      </Link>
    </div>
  </div>
</template>
