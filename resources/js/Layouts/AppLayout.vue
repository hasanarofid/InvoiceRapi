<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import Header from '@/Components/Layout/Header.vue';
import Footer from '@/Components/Layout/Footer.vue';
import WhatsAppFloat from '@/Components/UI/WhatsAppFloat.vue';

interface Props {
  title?: string;
  description?: string;
}

const props = withDefaults(defineProps<Props>(), {
  title: 'Synbarra Technologies | Custom Software Development',
  description: 'Synbarra Technologies builds custom software, web applications, mobile applications, and digital solutions designed around your business needs.',
});
</script>

<template>
  <div class="min-h-screen flex flex-col bg-white text-slate-900 antialiased selection:bg-[#00F1DE]/30 selection:text-[#01232B]">
    <Head>
      <title>{{ title }}</title>
      <meta name="description" :content="description" />
      <meta property="og:title" :content="title" />
      <meta property="og:description" :content="description" />
      <meta property="og:type" content="website" />
      <meta property="og:url" content="https://synbarra.com" />
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
