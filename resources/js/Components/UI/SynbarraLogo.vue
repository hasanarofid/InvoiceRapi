<script setup lang="ts">
import { computed } from 'vue';

interface Props {
  variant?: 'full' | 'symbol' | 'horizontal' | 'badge';
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
      return 'h-9 sm:h-10';
  }
});
</script>

<template>
  <div class="inline-flex items-center select-none">
    <!-- Icon Symbol only -->
    <template v-if="variant === 'symbol'">
      <img
        src="/images/logo/invoicerapi-icon.png"
        alt="Synbarra InvoiceRapi Icon"
        :class="[heightClass, 'w-auto object-contain']"
      />
    </template>

    <!-- Horizontal Logo on Purple Badge / Dark Mode -->
    <template v-else-if="theme === 'dark' || variant === 'badge'">
      <img
        src="/images/logo/invoicerapi-logo-purple.png"
        alt="Synbarra InvoiceRapi"
        :class="[heightClass, 'w-auto object-contain rounded-xl shadow-sm']"
      />
    </template>

    <!-- Horizontal Light Logo without tagline -->
    <template v-else-if="variant === 'horizontal'">
      <img
        src="/images/logo/invoicerapi-logo-horizontal.png"
        alt="Synbarra InvoiceRapi"
        :class="[heightClass, 'w-auto object-contain']"
      />
    </template>

    <!-- Full Official Logo (with tagline or primary) -->
    <template v-else>
      <img
        src="/images/logo/invoicerapi-logo-full.png"
        alt="Synbarra InvoiceRapi - Urus Tagihan, Jadi Lebih Rapi."
        :class="[heightClass, 'w-auto object-contain']"
      />
    </template>
  </div>
</template>
