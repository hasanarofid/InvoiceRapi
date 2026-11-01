<script setup lang="ts">
interface Props {
  badge?: string;
  title: string;
  description?: string;
  align?: 'left' | 'center';
  theme?: 'light' | 'dark';
}

withDefaults(defineProps<Props>(), {
  align: 'center',
  theme: 'light',
});
</script>

<template>
  <div 
    :class="[
      'max-w-3xl mb-12 sm:mb-16',
      align === 'center' ? 'mx-auto text-center' : 'text-left'
    ]"
  >
    <!-- Overline Badge -->
    <div v-if="badge" class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold tracking-wider uppercase mb-4"
         :class="theme === 'dark' ? 'bg-[#00F1DE]/10 text-[#00F1DE] border border-[#00F1DE]/20' : 'bg-[#01232B]/5 text-[#01232B] border border-[#01232B]/10'">
      <span class="w-1.5 h-1.5 rounded-full bg-[#00F1DE]"></span>
      {{ badge }}
    </div>

    <!-- Title -->
    <h2 
      :class="[
        'text-3xl sm:text-4xl lg:text-5xl font-extrabold tracking-tight leading-tight',
        theme === 'dark' ? 'text-white' : 'text-[#01232B]'
      ]"
    >
      {{ title }}
    </h2>

    <!-- Description -->
    <p 
      v-if="description" 
      :class="[
        'mt-4 text-base sm:text-lg leading-relaxed',
        theme === 'dark' ? 'text-slate-300' : 'text-slate-600'
      ]"
    >
      {{ description }}
    </p>
  </div>
</template>
