<script setup lang="ts">
import { computed } from 'vue';

const props = defineProps<{
  status: string;
}>();

interface StatusStyle {
  label: string;
  pill: string;
  dot: string;
}

const STATUS_MAP: Record<string, StatusStyle> = {
  // Positive / Active / Paid / Completed
  active: {
    label: 'Aktif',
    pill: 'bg-emerald-50 text-emerald-700 border-emerald-200/80',
    dot: 'bg-emerald-500',
  },
  paid: {
    label: 'Lunas',
    pill: 'bg-emerald-50 text-emerald-700 border-emerald-200/80',
    dot: 'bg-emerald-500',
  },
  completed: {
    label: 'Selesai',
    pill: 'bg-emerald-50 text-emerald-700 border-emerald-200/80',
    dot: 'bg-emerald-500',
  },

  // Issued / Sent
  issued: {
    label: 'Terkirim',
    pill: 'bg-sky-50 text-sky-700 border-sky-200/80',
    dot: 'bg-sky-500',
  },

  // Warning / Partial / On Hold
  partial: {
    label: 'Sebagian',
    pill: 'bg-amber-50 text-amber-700 border-amber-200/80',
    dot: 'bg-amber-500',
  },
  on_hold: {
    label: 'Ditunda',
    pill: 'bg-amber-50 text-amber-700 border-amber-200/80',
    dot: 'bg-amber-500',
  },

  // Danger / Overdue / Cancelled
  overdue: {
    label: 'Terlambat',
    pill: 'bg-rose-50 text-rose-700 border-rose-200/80',
    dot: 'bg-rose-500',
  },
  cancelled: {
    label: 'Dibatalkan',
    pill: 'bg-rose-50 text-rose-700 border-rose-200/80',
    dot: 'bg-rose-500',
  },

  // Neutral / Inactive / Draft
  inactive: {
    label: 'Nonaktif',
    pill: 'bg-slate-100 text-slate-600 border-slate-200/80',
    dot: 'bg-slate-400',
  },
  archived: {
    label: 'Nonaktif',
    pill: 'bg-slate-100 text-slate-600 border-slate-200/80',
    dot: 'bg-slate-400',
  },
  draft: {
    label: 'Draft',
    pill: 'bg-slate-100 text-slate-600 border-slate-200/80',
    dot: 'bg-slate-400',
  },
};

const resolved = computed<StatusStyle>(() => {
  const key = (props.status || '').toLowerCase();
  return (
    STATUS_MAP[key] ?? {
      label: props.status,
      pill: 'bg-slate-100 text-slate-600 border-slate-200/80',
      dot: 'bg-slate-400',
    }
  );
});
</script>

<template>
  <span
    class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold border whitespace-nowrap"
    :class="resolved.pill"
  >
    <span class="w-1.5 h-1.5 rounded-full shrink-0" :class="resolved.dot" aria-hidden="true" />
    <span>{{ resolved.label }}</span>
  </span>
</template>

