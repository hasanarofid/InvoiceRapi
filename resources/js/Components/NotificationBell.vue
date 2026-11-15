<script setup lang="ts">
import { ref, computed, onMounted } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import { Bell, AlertTriangle, Clock, CreditCard, Crown, CheckCheck, Users, ShoppingCart } from 'lucide-vue-next';

interface NotificationItem {
  id: string;
  type: string;
  title: string;
  message: string;
  href: string;
  time: string;
}

const page = usePage();
const userId = computed(() => (page.props.auth as any)?.user?.id ?? 'guest');
const notifications = computed<NotificationItem[]>(() => ((page.props as any).notifications as NotificationItem[]) || []);

const isOpen = ref(false);
const readIds = ref<string[]>([]);

const storageKey = computed(() => `invoicerapi_read_notif_${userId.value}`);

onMounted(() => {
  try {
    const saved = localStorage.getItem(storageKey.value);
    if (saved) readIds.value = JSON.parse(saved);
  } catch {
    readIds.value = [];
  }
});

const unreadCount = computed(() =>
  notifications.value.filter((n) => !readIds.value.includes(n.id)).length
);

const markAllRead = () => {
  readIds.value = notifications.value.map((n) => n.id);
  try {
    localStorage.setItem(storageKey.value, JSON.stringify(readIds.value));
  } catch {
    // ignore storage errors
  }
};

const markRead = (id: string) => {
  if (!readIds.value.includes(id)) {
    readIds.value = [...readIds.value, id];
    try {
      localStorage.setItem(storageKey.value, JSON.stringify(readIds.value));
    } catch {
      // ignore storage errors
    }
  }
  isOpen.value = false;
};

const iconFor = (type: string) => {
  if (type === 'overdue') return AlertTriangle;
  if (type === 'due_soon') return Clock;
  if (type === 'quota') return Crown;
  if (type === 'lead') return Users;
  if (type === 'order') return ShoppingCart;
  return CreditCard;
};

const colorFor = (type: string) => {
  if (type === 'overdue') return 'bg-red-50 text-red-600';
  if (type === 'due_soon' || type === 'quota') return 'bg-amber-50 text-amber-600';
  if (type === 'payment_verify' || type === 'billing') return 'bg-violet-50 text-violet-600';
  return 'bg-emerald-50 text-emerald-600';
};
</script>

<template>
  <div class="relative">
    <button
      type="button"
      @click="isOpen = !isOpen"
      class="relative p-2 rounded-xl text-slate-500 hover:text-slate-900 hover:bg-slate-100 transition"
      title="Notifikasi"
      aria-label="Notifikasi"
    >
      <Bell class="w-4 h-4" />
      <span
        v-if="unreadCount > 0"
        class="absolute -top-0.5 -right-0.5 min-w-[16px] h-4 px-1 rounded-full bg-red-500 text-white text-[10px] font-black flex items-center justify-center ring-2 ring-white"
      >
        {{ unreadCount > 9 ? '9+' : unreadCount }}
      </span>
    </button>

    <!-- Backdrop -->
    <div v-if="isOpen" @click="isOpen = false" class="fixed inset-0 z-30"></div>

    <!-- Dropdown Panel -->
    <div
      v-if="isOpen"
      class="absolute right-0 mt-2 w-80 sm:w-96 bg-white border border-slate-200 rounded-2xl shadow-2xl z-40 overflow-hidden"
    >
      <div class="px-4 py-3 border-b border-slate-100 flex items-center justify-between bg-slate-50/70">
        <div>
          <p class="text-xs font-black text-slate-900">Notifikasi</p>
          <p class="text-[11px] text-slate-500">{{ unreadCount }} belum dibaca</p>
        </div>
        <button
          v-if="unreadCount > 0"
          type="button"
          @click="markAllRead"
          class="inline-flex items-center gap-1 text-[11px] font-bold text-violet-700 hover:text-violet-900"
        >
          <CheckCheck class="w-3.5 h-3.5" />
          Tandai dibaca
        </button>
      </div>

      <div v-if="notifications.length === 0" class="p-8 text-center">
        <Bell class="w-8 h-8 text-slate-300 mx-auto mb-2" />
        <p class="text-xs font-bold text-slate-700">Tidak ada notifikasi baru</p>
        <p class="text-[11px] text-slate-500 mt-0.5">Semua tagihan dan aktivitas Anda aman terkendali.</p>
      </div>

      <div v-else class="max-h-80 overflow-y-auto divide-y divide-slate-100">
        <Link
          v-for="item in notifications"
          :key="item.id"
          :href="item.href"
          @click="markRead(item.id)"
          class="flex items-start gap-3 px-4 py-3 hover:bg-violet-50/40 transition"
          :class="!readIds.includes(item.id) ? 'bg-violet-50/20' : ''"
        >
          <div class="w-8 h-8 rounded-xl flex items-center justify-center shrink-0 mt-0.5" :class="colorFor(item.type)">
            <component :is="iconFor(item.type)" class="w-4 h-4" />
          </div>
          <div class="min-w-0 flex-1">
            <div class="flex items-center justify-between gap-2">
              <p class="text-xs font-bold text-slate-900 truncate">{{ item.title }}</p>
              <span class="text-[10px] text-slate-400 shrink-0">{{ item.time }}</span>
            </div>
            <p class="text-[11px] text-slate-600 mt-0.5 line-clamp-2">{{ item.message }}</p>
          </div>
          <span v-if="!readIds.includes(item.id)" class="w-2 h-2 rounded-full bg-violet-600 shrink-0 mt-2"></span>
        </Link>
      </div>
    </div>
  </div>
</template>
