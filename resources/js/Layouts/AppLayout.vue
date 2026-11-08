<script setup lang="ts">
import { computed } from 'vue';
import { Head, usePage } from '@inertiajs/vue3';
import Header from '@/Components/Layout/Header.vue';
import Footer from '@/Components/Layout/Footer.vue';
import WhatsAppFloat from '@/Components/UI/WhatsAppFloat.vue';

interface Props {
  title?: string;
  description?: string;
  keywords?: string;
  image?: string;
  type?: string;
}

const props = defineProps<Props>();
const page = usePage();

const meta = computed(() => (page.props.meta as Record<string, string> | undefined) || {});

const pageTitle = computed(() => {
  return props.title || meta.value.title || 'Synbarra Technologies | Software House & Custom Development';
});

const pageDescription = computed(() => {
  return props.description || meta.value.description || 'SYNBARRA TECHNOLOGIES adalah software house yang berfokus pada pengembangan sistem ERP bisnis, website, dan aplikasi mobile custom sesuai kebutuhan unik bisnis Anda.';
});

const pageKeywords = computed(() => {
  return props.keywords || meta.value.keywords || 'Synbarra Technologies, Software House Indonesia, Jasa Pembuatan Website, Jasa Pembuatan Aplikasi Mobile, Custom ERP, HRIS Payroll, Integrasi API';
});

const pageImage = computed(() => {
  return props.image || meta.value.og_image || 'https://synbarra.com/images/posts/Synbarra_Post_01.png';
});

const currentUrl = computed(() => {
  if (typeof window !== 'undefined') {
    return window.location.origin + window.location.pathname;
  }
  return 'https://invoicerapi.synbarra.com' + (page.url || '');
});
</script>

<template>
  <div class="min-h-screen flex flex-col bg-white text-slate-900 antialiased selection:bg-violet-100 selection:text-violet-900">
    <Head>
      <title>{{ pageTitle }}</title>
      <meta name="description" :content="pageDescription" />
      <meta name="keywords" :content="pageKeywords" />

      <!-- Open Graph / WhatsApp / Facebook -->
      <meta property="og:site_name" content="Synbarra InvoiceRapi" />
      <meta property="og:type" :content="props.type || 'website'" />
      <meta property="og:url" :content="currentUrl" />
      <meta property="og:title" :content="pageTitle" />
      <meta property="og:description" :content="pageDescription" />
      <meta property="og:image" :content="pageImage" />

      <!-- Twitter Card -->
      <meta name="twitter:card" content="summary_large_image" />
      <meta name="twitter:url" :content="currentUrl" />
      <meta name="twitter:title" :content="pageTitle" />
      <meta name="twitter:description" :content="pageDescription" />
      <meta name="twitter:image" :content="pageImage" />
    </Head>

    <!-- Accessibility Skip Link -->
    <a
      href="#main-content"
      class="sr-only focus:not-sr-only focus:absolute focus:top-4 focus:left-4 z-50 px-4 py-2 bg-[#01232B] text-white rounded-md shadow-lg outline-none ring-2 ring-[#00F1DE]"
    >
      Skip to content
    </a>

    <!-- Header Navigation -->
    <Header />

    <!-- Main Page Content -->
    <main id="main-content" class="flex-grow">
      <slot />
    </main>

    <!-- Floating WhatsApp Action -->
    <WhatsAppFloat />

    <!-- Footer -->
    <Footer />
  </div>
</template>
