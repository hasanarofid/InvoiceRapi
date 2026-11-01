<script setup lang="ts">
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';

interface Props {
  variant?: 'primary' | 'secondary' | 'outline' | 'ghost' | 'dark';
  size?: 'sm' | 'md' | 'lg';
  href?: string;
  type?: 'button' | 'submit' | 'reset';
  disabled?: boolean;
  loading?: boolean;
}

const props = withDefaults(defineProps<Props>(), {
  variant: 'primary',
  size: 'md',
  type: 'button',
  disabled: false,
  loading: false,
});

const baseClasses = 'inline-flex items-center justify-center font-medium rounded-lg transition-all duration-200 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed select-none';

const sizeClasses = computed(() => {
  switch (props.size) {
    case 'sm':
      return 'text-xs px-3.5 py-1.5 gap-1.5';
    case 'lg':
      return 'text-base px-6 py-3.5 gap-2.5 font-semibold';
    case 'md':
    default:
      return 'text-sm px-5 py-2.5 gap-2';
  }
});

const variantClasses = computed(() => {
  switch (props.variant) {
    case 'secondary':
      // Turquoise button with dark teal text
      return 'bg-[#00F1DE] hover:bg-[#04D2C5] text-[#01232B] font-semibold shadow-synbarra-glow-subtle hover:shadow-synbarra-glow focus-visible:ring-[#00F1DE]';
    case 'outline':
      // Clean border button
      return 'border border-slate-300 dark:border-[#0A3B43] text-[#01232B] dark:text-white hover:bg-slate-50 dark:hover:bg-[#022B31] hover:border-[#04D2C5] focus-visible:ring-[#04D2C5]';
    case 'ghost':
      return 'text-slate-700 dark:text-slate-300 hover:text-[#01232B] dark:hover:text-white hover:bg-slate-100 dark:hover:bg-[#022B31]/50 focus-visible:ring-[#04D2C5]';
    case 'dark':
      // Solid dark button with turquoise hover border
      return 'bg-[#011E26] text-white hover:bg-[#01232B] border border-[#0A3B43] hover:border-[#00F1DE]/50 shadow-sm focus-visible:ring-[#00F1DE]';
    case 'primary':
    default:
      // Dark Teal signature brand button with turquoise accent glow
      return 'bg-[#01232B] hover:bg-[#022B31] text-white hover:text-[#00F1DE] border border-transparent hover:border-[#00F1DE]/40 shadow-synbarra-sm hover:shadow-synbarra-glow-subtle focus-visible:ring-[#00F1DE]';
  }
});
</script>

<template>
  <Link
    v-if="href"
    :href="href"
    :class="[baseClasses, sizeClasses, variantClasses]"
  >
    <slot name="prefix" />
    <slot />
    <slot name="suffix" />
  </Link>
  <button
    v-else
    :type="type"
    :disabled="disabled || loading"
    :class="[baseClasses, sizeClasses, variantClasses]"
  >
    <svg 
      v-if="loading" 
      class="animate-spin -ml-1 mr-2 h-4 w-4 text-current" 
      fill="none" 
      viewBox="0 0 24 24"
    >
      <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
      <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
    </svg>
    <slot name="prefix" />
    <slot />
    <slot name="suffix" />
  </button>
</template>
