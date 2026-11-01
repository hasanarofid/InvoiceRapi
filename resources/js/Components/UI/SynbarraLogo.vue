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

const heightClass = computed(() => {
  switch (props.size) {
    case 'sm':
      return 'h-7 sm:h-8';
    case 'lg':
      return 'h-11 sm:h-13';
    case 'xl':
      return 'h-14 sm:h-16';
    case 'md':
    default:
      return 'h-9 sm:h-11';
  }
});
</script>

<template>
  <div class="inline-flex items-center select-none">
    <!-- Full Official Horizontal Logo (High-Res 2x Crisp Master from WhatsApp Image 2026-11-01 at 16.02.11) -->
    <template v-if="variant === 'full'">
      <!-- Dark Theme (For Footer & Dark Backgrounds) -->
      <img
        v-if="theme === 'dark'"
        src="/images/logo/synbarra-horizontal-dark.png"
        alt="SYNBARRA TECHNOLOGIES"
        :class="[heightClass, 'w-auto object-contain transition-transform duration-200 hover:opacity-95']"
      />
      <!-- Light Theme (For Header & Light Backgrounds) - File Asli Langsung dengan CSS Transparency -->
      <img
        v-else
        src="/images/logo/synbarra-official.jpeg"
        alt="SYNBARRA TECHNOLOGIES"
        :class="[heightClass, 'w-auto object-contain mix-blend-multiply transition-transform duration-200 hover:opacity-95']"
        style="mix-blend-mode: multiply;"
      />
    </template>

    <!-- Symbol Only Variant -->
    <template v-else-if="variant === 'symbol' || variant === 'badge'">
      <img
        src="/images/logo/synbarra-symbol.png"
        alt="Synbarra Symbol"
        :class="[heightClass, 'w-auto object-contain']"
      />
    </template>
  </div>
</template>
