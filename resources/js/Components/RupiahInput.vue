<script setup lang="ts">
import { computed } from 'vue';

const props = withDefaults(
  defineProps<{
    modelValue: number | string | null | undefined;
    placeholder?: string;
    required?: boolean;
    disabled?: boolean;
    inputClass?: string;
  }>(),
  {
    placeholder: '0',
    required: false,
    disabled: false,
    inputClass: '',
  }
);

const emit = defineEmits<{
  (e: 'update:modelValue', value: number): void;
}>();

const formattedValue = computed(() => {
  const num = Number(String(props.modelValue ?? '').replace(/[^\d]/g, '')) || 0;
  if (num === 0 && (props.modelValue === '' || props.modelValue === null || props.modelValue === undefined)) {
    return '';
  }
  return new Intl.NumberFormat('id-ID').format(num);
});

const onInput = (event: Event) => {
  const target = event.target as HTMLInputElement;
  const rawDigits = target.value.replace(/[^\d]/g, '');
  const numericValue = rawDigits ? parseInt(rawDigits, 10) : 0;

  // Format immediately on keystroke so cursor/display stays in Rupiah thousand format
  target.value = rawDigits ? new Intl.NumberFormat('id-ID').format(numericValue) : '';
  emit('update:modelValue', numericValue);
};
</script>

<template>
  <div class="relative">
    <span class="pointer-events-none absolute left-3.5 top-1/2 -translate-y-1/2 text-xs font-bold text-slate-400">
      Rp
    </span>
    <input
      type="text"
      inputmode="numeric"
      :value="formattedValue"
      :placeholder="placeholder"
      :required="required"
      :disabled="disabled"
      @input="onInput"
      :class="[
        'w-full pl-10 pr-3.5 py-2.5 rounded-xl border border-slate-200 text-sm font-semibold text-slate-900 bg-white focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-transparent disabled:opacity-50',
        inputClass,
      ]"
    />
  </div>
</template>

