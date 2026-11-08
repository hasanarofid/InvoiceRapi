<script setup>
import { Link } from '@inertiajs/vue3';
import SynbarraLogo from '@/Components/UI/SynbarraLogo.vue';
</script>

<template>
  <div class="min-h-screen bg-slate-50/80 text-slate-900 font-sans antialiased relative overflow-hidden flex flex-col items-center justify-center p-4 sm:p-6 selection:bg-violet-100 selection:text-violet-900">
    <!-- Ambient Background Radial Light -->
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[600px] h-[350px] bg-gradient-to-b from-violet-200/40 to-transparent blur-3xl pointer-events-none"></div>

    <!-- Auth Card Container -->
    <div class="w-full sm:max-w-md bg-white border border-slate-200/80 shadow-xl shadow-violet-900/5 rounded-2xl p-6 sm:p-8 relative z-10 space-y-6">
      <!-- Synbarra InvoiceRapi Brand Identity -->
      <div class="flex flex-col items-center text-center gap-1.5">
        <Link href="/" class="group flex items-center justify-center p-1 rounded-xl bg-white transition focus-visible:outline-none">
          <SynbarraLogo size="lg" variant="horizontal" />
        </Link>
        <span class="text-[11px] font-semibold text-violet-700 bg-violet-50 border border-violet-100 px-3 py-0.5 rounded-full mt-1">
          Urus Tagihan, Jadi Lebih Rapi
        </span>
      </div>

      <!-- Form Slot -->
      <div>
        <slot />
      </div>
    </div>

    <!-- Footer Back Link -->
    <div class="mt-6 text-center text-xs text-slate-500">
      <Link href="/" class="hover:text-violet-700 font-medium transition flex items-center gap-1 justify-center">
        &larr; Kembali ke Beranda InvoiceRapi
      </Link>
    </div>
  </div>
</template>
