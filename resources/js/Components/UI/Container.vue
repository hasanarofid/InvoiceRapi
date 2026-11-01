<script setup lang="ts">
interface Props {
  as?: string;
  size?: 'sm' | 'md' | 'lg' | 'full';
  class?: string;
}

const props = withDefaults(defineProps<Props>(), {
  as: 'div',
  size: 'lg',
  class: '',
});

const maxWidths = {
  sm: 'max-w-4xl',
  md: 'max-w-5xl',
  lg: 'max-w-7xl',
  full: 'max-w-full',
};
</script>

<template>
  <component
    :is="as"
    :class="[
      'mx-auto w-full px-4 sm:px-6 lg:px-8',
      maxWidths[props.size],
      props.class
    ]"
  >
    <slot />
  </component>
</template>
