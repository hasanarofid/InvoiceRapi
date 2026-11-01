<script setup lang="ts">
interface Props {
  variant?: 'teal' | 'turquoise' | 'neutral' | 'outline';
  size?: 'sm' | 'md';
}

withDefaults(defineProps<Props>(), {
  variant: 'teal',
  size: 'md',
});
</script>

<template>
  <span
    :class="[
      'inline-flex items-center font-medium rounded-full select-none',
      size === 'sm' ? 'px-2.5 py-0.5 text-xs' : 'px-3 py-1 text-xs sm:text-sm',
      variant === 'teal' ? 'bg-[#01232B] text-white border border-[#0A3B43]' : '',
      variant === 'turquoise' ? 'bg-[#00F1DE]/15 text-[#01232B] dark:text-[#00F1DE] border border-[#00F1DE]/30' : '',
      variant === 'neutral' ? 'bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-300' : '',
      variant === 'outline' ? 'border border-slate-300 text-slate-600 dark:border-slate-700 dark:text-slate-400' : ''
    ]"
  >
    <slot />
  </span>
</template>
