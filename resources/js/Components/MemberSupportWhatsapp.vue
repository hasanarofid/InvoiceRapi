<script setup lang="ts">
import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';

const page = usePage();
const user = computed(() => (page.props.auth as any)?.user);
const tenant = computed(() => user.value?.tenant);
const planUsage = computed(() => (page.props as any)?.planUsage);

const ADMIN_WHATSAPP = '6288804958476';

const supportUrl = computed(() => {
  const userName = user.value?.name || '-';
  const userEmail = user.value?.email || '-';
  const bizName = tenant.value?.name || '-';
  const bizPhone = tenant.value?.phone_whatsapp || '-';
  const planName = planUsage.value?.plan_name || 'Gratis';
  const subStatus =
    planUsage.value?.subscription_status === 'active'
      ? 'Aktif'
      : planUsage.value?.is_trial_expired
        ? 'Trial Berakhir'
        : 'Masa Coba';
  const currentUrl = typeof window !== 'undefined' ? window.location.href : page.url;

  const message = [
    'Halo Tim Support InvoiceRapi (Synbarra),',
    '',
    'Saya ingin menyampaikan keluhan / saran terkait penggunaan aplikasi:',
    '',
    '*Informasi Pengguna:*',
    `• Nama : ${userName}`,
    `• Email : ${userEmail}`,
    `• Bisnis / Workspace : ${bizName}`,
    `• No. WA Bisnis : ${bizPhone}`,
    `• Paket : ${planName} (${subStatus})`,
    `• Halaman : ${currentUrl}`,
    '',
    '*Detail Keluhan / Saran:*',
    '...',
  ].join('\n');

  return `https://wa.me/${ADMIN_WHATSAPP}?text=${encodeURIComponent(message)}`;
});
</script>

<template>
  <a
    :href="supportUrl"
    target="_blank"
    rel="noopener noreferrer"
    aria-label="Hubungi Admin via WhatsApp untuk Keluhan & Saran"
    title="Keluhan & Saran via WhatsApp Admin"
    class="fixed bottom-20 right-4 md:bottom-6 md:right-6 z-40 group flex items-center gap-2.5"
  >
    <!-- Desktop Pill Label -->
    <span class="hidden sm:inline-flex items-center gap-2 px-3.5 py-2 rounded-full bg-slate-900/95 text-white text-xs font-bold shadow-lg border border-slate-700/80 opacity-90 group-hover:opacity-100 group-hover:scale-105 transition">
      <span class="w-2 h-2 rounded-full bg-[#25D366] animate-pulse"></span>
      Keluhan &amp; Saran
    </span>

    <!-- WhatsApp Icon Button -->
    <span class="relative w-12 h-12 rounded-full bg-[#25D366] hover:bg-[#1ebe5d] text-white flex items-center justify-center shadow-xl shadow-emerald-600/30 transition transform group-hover:scale-105">
      <span class="absolute -inset-1 rounded-full bg-[#25D366]/35 animate-ping pointer-events-none"></span>
      <svg class="w-6 h-6 relative z-10 fill-current" viewBox="0 0 24 24" aria-hidden="true">
        <path d="M12.031 2c-5.516 0-9.969 4.453-9.969 9.969 0 1.766.469 3.484 1.344 5l-1.406 5.156 5.281-1.375c1.469.797 3.109 1.219 4.75 1.219 5.516 0 9.969-4.453 9.969-9.969S17.547 2 12.031 2zm5.813 14.125c-.25.703-1.438 1.344-2 1.422-.531.078-1.203.109-1.938-.125-.453-.141-1.031-.328-1.766-.641-3.109-1.344-5.141-4.484-5.297-4.688-.156-.203-1.266-1.688-1.266-3.219s.797-2.281 1.078-2.594c.281-.313.609-.391.813-.391.203 0 .406.016.578.016.188 0 .438-.078.688.531.25.609.859 2.094.938 2.25.078.156.125.344.031.547-.094.203-.141.328-.297.5-.156.172-.328.391-.469.516-.156.156-.313.328-.141.641.172.313.781 1.297 1.672 2.094 1.156 1.031 2.125 1.359 2.438 1.516.313.156.484.125.672-.078.188-.203.766-.891.969-1.203.203-.313.406-.266.688-.156.281.109 1.781.844 2.094 1 .313.156.516.234.594.359.078.125.078.734-.172 1.438z" />
      </svg>
    </span>
  </a>
</template>

