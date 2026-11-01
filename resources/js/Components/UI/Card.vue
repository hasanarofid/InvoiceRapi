<script setup lang="ts">
interface Props {
  hover?: boolean;
  theme?: 'light' | 'dark' | 'glass';
  class?: string;
}

withDefaults(defineProps<Props>(), {
  hover: true,
  theme: 'light',
  class: '',
});
</script>

<template>
  <div
    :class="[
      'rounded-2xl transition-all duration-300 relative overflow-hidden',
      theme === 'light' ? 'bg-white border border-slate-200/80 shadow-synbarra-sm' : '',
      theme === 'dark' ? 'bg-[#011E26] border border-[#0A3B43] text-white shadow-synbarra-md' : '',
      theme === 'glass' ? 'bg-white/80 dark:bg-[#011E26]/80 backdrop-blur-md border border-slate-200/60 dark:border-[#0A3B43]' : '',
      hover ? 'hover:-translate-y-1 hover:shadow-synbarra-md hover:border-[#04D2C5]/50' : '',
      props.class
    ]"
  >
    <slot />
  </div>
</template>
