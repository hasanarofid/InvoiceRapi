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
    <!-- Geometric S Ribbon Symbol (From Master Asset) -->
    <div 
      :class="[
        sizeClasses.icon, 
        'relative flex-shrink-0 rounded-xl overflow-hidden p-1 flex items-center justify-center transition-transform duration-300 hover:scale-105',
        props.theme === 'dark' ? 'bg-[#01232B] ring-1 ring-[#00F1DE]/40 shadow-synbarra-glow-subtle' : 'bg-[#01232B] shadow-sm'
      ]"
    >
      <img 
        src="/images/logo/synbarra-symbol.png" 
        alt="Synbarra Symbol" 
        class="w-full h-full object-contain filter drop-shadow-[0_2px_8px_rgba(0,241,222,0.25)]"
      />
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
