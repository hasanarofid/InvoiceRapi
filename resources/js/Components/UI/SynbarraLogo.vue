<script setup lang="ts">
import { computed } from 'vue';

interface Props {
  variant?: 'full' | 'symbol' | 'badge';
  theme?: 'dark' | 'light';
  size?: 'sm' | 'md' | 'lg' | 'xl';
  showTagline?: boolean;
}

const props = withDefaults(defineProps<Props>(), {
  variant: 'full',
  theme: 'light',
  size: 'md',
  showTagline: false,
});

const sizeClasses = computed(() => {
  switch (props.size) {
    case 'sm':
      return { icon: 'w-7 h-7', text: 'text-base', subtext: 'text-[9px] tracking-[0.25em]' };
    case 'lg':
      return { icon: 'w-12 h-12', text: 'text-2xl', subtext: 'text-xs tracking-[0.3em]' };
    case 'xl':
      return { icon: 'w-16 h-16', text: 'text-3xl', subtext: 'text-sm tracking-[0.35em]' };
    case 'md':
    default:
      return { icon: 'w-9 h-9', text: 'text-lg', subtext: 'text-[10px] tracking-[0.28em]' };
  }
});
</script>

<template>
  <div class="inline-flex items-center gap-3 select-none">
    <!-- Geometric S Ribbon Symbol -->
    <div 
      :class="[
        sizeClasses.icon, 
        'relative flex-shrink-0 rounded-xl overflow-hidden p-1 flex items-center justify-center transition-transform duration-300 hover:scale-105',
        props.theme === 'dark' ? 'bg-[#01232B] ring-1 ring-[#00F1DE]/40 shadow-synbarra-glow-subtle' : 'bg-[#01232B] shadow-sm'
      ]"
    >
      <svg viewBox="0 0 100 100" fill="none" class="w-full h-full">
        <defs>
          <linearGradient id="logo-ribbon-teal" x1="0%" y1="100%" x2="100%" y2="0%">
            <stop offset="0%" stop-color="#022B31" />
            <stop offset="40%" stop-color="#04D2C5" />
            <stop offset="100%" stop-color="#00F1DE" />
          </linearGradient>
          <linearGradient id="logo-ribbon-light" x1="0%" y1="0%" x2="100%" y2="100%">
            <stop offset="0%" stop-color="#FFFFFF" />
            <stop offset="60%" stop-color="#E0FFFC" />
            <stop offset="100%" stop-color="#04D2C5" />
          </linearGradient>
        </defs>

        <!-- S Geometric Upper Fold -->
        <path d="M 58 16 L 36 38 C 32 42 32 49 38 53 L 53 64 L 64 53 L 47 40 C 44 37 44 33 47 30 L 59 18 C 61 16 60 14 58 16 Z" 
              fill="url(#logo-ribbon-light)" />

        <!-- S Geometric Lower Fold -->
        <path d="M 42 84 L 64 62 C 68 58 68 51 62 47 L 47 36 L 36 47 L 53 60 C 56 63 56 67 53 70 L 41 82 C 39 84 40 86 42 84 Z" 
              fill="url(#logo-ribbon-teal)" />

        <!-- Fold intersection shadow -->
        <path d="M 36 47 L 64 53 L 53 64 L 36 47 Z" fill="#022B31" opacity="0.35" />
      </svg>
    </div>

    <!-- Wordmark SYNBARRA TECHNOLOGIES -->
    <div v-if="variant === 'full'" class="flex flex-col">
      <div class="flex items-center tracking-tight font-extrabold leading-none">
        <span 
          :class="[
            sizeClasses.text, 
            'font-black tracking-wider uppercase',
            theme === 'dark' ? 'text-white' : 'text-[#01232B]'
          ]"
        >
          SYNBAR
        </span>
        <!-- Stylized A with cyan accent triangle -->
        <span class="relative inline-block font-black tracking-wider uppercase" :class="[sizeClasses.text, theme === 'dark' ? 'text-white' : 'text-[#01232B]']">
          RA
          <span class="absolute bottom-0 right-[4px] w-1.5 h-1.5 bg-[#00F1DE] rounded-full"></span>
        </span>
      </div>

      <!-- Subtitle: TECHNOLOGIES -->
      <span 
        :class="[
          sizeClasses.subtext,
          'font-semibold uppercase transition-colors',
          theme === 'dark' ? 'text-[#04D2C5]' : 'text-[#04D2C5]'
        ]"
      >
        TECHNOLOGIES
      </span>
    </div>
  </div>
</template>
