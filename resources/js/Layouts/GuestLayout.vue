<script setup>
import { Link } from '@inertiajs/vue3';
</script>

<template>
  <div class="min-h-screen bg-[#FAF8FF] text-slate-900 font-sans antialiased relative overflow-hidden flex flex-col items-center justify-center p-4 sm:p-6 selection:bg-violet-100 selection:text-violet-900">
    <!-- Ambient Background Radial Light -->
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[600px] h-[350px] bg-gradient-to-b from-violet-200/40 to-transparent blur-3xl pointer-events-none"></div>

    <!-- Auth Card Container -->
    <div class="w-full sm:max-w-md bg-white border border-violet-100 shadow-invoicerapi-lg rounded-2xl p-6 sm:p-8 relative z-10 space-y-6">
      <!-- Synbarra InvoiceRapi Brand Identity -->
      <div class="flex flex-col items-center text-center gap-2">
        <Link href="/" class="group inline-flex items-center gap-3 p-1 rounded-xl transition focus-visible:outline-none">
          <span class="w-10 h-10 rounded-xl bg-gradient-to-br from-violet-600 to-violet-800 text-white flex items-center justify-center shadow-invoicerapi-sm group-hover:scale-105 transition-transform">
            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
              <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
              <polyline points="14 2 14 8 20 8" />
              <line x1="8" y1="13" x2="16" y2="13" />
              <line x1="8" y1="17" x2="13" y2="17" />
            </svg>
          </span>
          <span class="flex flex-col text-left leading-none">
            <span class="text-xs font-bold tracking-wide text-violet-700">Synbarra</span>
            <span class="text-xl font-extrabold tracking-tight text-slate-900">InvoiceRapi</span>
          </span>
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
