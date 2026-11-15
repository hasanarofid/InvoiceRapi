<script setup>
import { ref, watch } from 'vue';
import { Link, router, usePage } from '@inertiajs/vue3';
import NotificationBell from '@/Components/NotificationBell.vue';
import { swalSuccess, swalError } from '@/utils/swal';
import {
  LayoutDashboard,
  Settings as SettingsIcon,
  ShoppingBag,
  ShoppingCart,
  ExternalLink,
  Menu,
  X,
  LogOut,
  ChevronDown,
  ChevronLeft,
  ChevronRight,
  ListTodo,
  Users,
  CreditCard,
  UserCheck
} from '@lucide/vue';

const page = usePage();
const user = page.props.auth.user;

watch(
  () => page.props.flash,
  (flash) => {
    if (flash?.success) swalSuccess(flash.success);
    if (flash?.error) swalError('Terjadi Kesalahan', flash.error);
  },
  { immediate: true, deep: true }
);

const isSidebarOpen = ref(false);
const isSidebarCollapsed = ref(false);
const isUserMenuOpen = ref(false);

const navigation = [
  { name: 'Dashboard', href: route('admin.dashboard'), icon: LayoutDashboard, current: route().current('admin.dashboard') },
  { name: 'Verifikasi Pembayaran', href: route('admin.payments.index'), icon: CreditCard, current: route().current('admin.payments.*') },
  { name: 'Trial Tracker (CRM WA)', href: route('admin.trial-tracker.index'), icon: Users, current: route().current('admin.trial-tracker.*') },
  { name: 'Roadmap Sprint 30 Hari', href: route('admin.roadmap.index'), icon: ListTodo, current: route().current('admin.roadmap.*') },
  { name: 'Leads & Prospek', href: route('admin.leads.index'), icon: Users, current: route().current('admin.leads.*') },
  { name: 'Katalog Produk', href: route('admin.products.index'), icon: ShoppingBag, current: route().current('admin.products.*') },
  { name: 'Pesanan Masuk (OMS)', href: route('admin.orders.index'), icon: ShoppingCart, current: route().current('admin.orders.*') },
  { name: 'Pengaturan Toko', href: route('admin.settings.index'), icon: SettingsIcon, current: route().current('admin.settings.*') },
  { name: 'Profil Akun', href: route('profile.edit'), icon: UserCheck, current: route().current('profile.*') },
];

const logout = () => {
  router.post(route('logout'));
};
</script>

<template>
  <div class="min-h-screen bg-[#FAF8FF] text-slate-800 font-sans antialiased relative selection:bg-violet-600 selection:text-white">
    <!-- Mobile Sidebar Backdrop -->
    <div
      v-if="isSidebarOpen"
      @click="isSidebarOpen = false"
      class="fixed inset-0 z-40 bg-slate-900/40 backdrop-blur-xs lg:hidden transition-opacity"
    ></div>

    <!-- Sidebar for Mobile and Desktop -->
    <aside
      :class="[
        isSidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0',
        isSidebarCollapsed ? 'lg:w-20' : 'lg:w-72',
        'fixed top-0 bottom-0 left-0 z-50 w-72 bg-white border-r border-violet-100/80 transition-all duration-300 ease-in-out lg:fixed flex flex-col justify-between shadow-invoicerapi-sm'
      ]"
    >
      <div>
        <!-- Sidebar Header / Logo -->
        <div :class="[isSidebarCollapsed ? 'lg:px-0 lg:justify-center' : 'px-5 justify-between', 'flex items-center h-16 border-b border-violet-100/80']">
          <Link :href="route('admin.dashboard')" class="flex items-center gap-2.5 min-w-0">
            <span class="w-9 h-9 rounded-xl bg-gradient-to-br from-violet-600 to-violet-800 text-white flex items-center justify-center shadow-invoicerapi-sm shrink-0">
              <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                <polyline points="14 2 14 8 20 8" />
                <line x1="8" y1="13" x2="16" y2="13" />
                <line x1="8" y1="17" x2="13" y2="17" />
              </svg>
            </span>
            <div :class="[isSidebarCollapsed ? 'lg:hidden' : 'block', 'transition-opacity duration-300 min-w-0']">
              <h1 class="text-sm font-extrabold tracking-tight text-slate-900 leading-none">
                InvoiceRapi <span class="text-violet-700">Admin</span>
              </h1>
              <p class="text-[10px] text-slate-400 font-semibold tracking-wide truncate mt-1">Synbarra Control Center</p>
            </div>
          </Link>
          <button
            @click="isSidebarOpen = false"
            class="p-2 text-slate-400 rounded-lg hover:bg-violet-50 hover:text-violet-700 lg:hidden"
            aria-label="Tutup Sidebar"
          >
            <X class="w-5 h-5" />
          </button>
        </div>

        <!-- Navigation Links -->
        <nav class="px-3 py-5 space-y-1.5 overflow-y-auto" aria-label="Navigasi Admin">
          <Link
            v-for="item in navigation"
            :key="item.name"
            :href="item.href"
            :class="[
              item.current
                ? 'bg-violet-700 text-white font-bold shadow-invoicerapi-sm'
                : 'text-slate-600 hover:bg-violet-50/70 hover:text-violet-700 font-semibold',
              isSidebarCollapsed ? 'lg:justify-center lg:px-0' : 'px-3.5',
              'group flex items-center py-2.5 text-sm rounded-xl transition-all duration-150'
            ]"
            :title="isSidebarCollapsed ? item.name : ''"
          >
            <component
              :is="item.icon"
              :class="[
                item.current ? 'text-white' : 'text-slate-400 group-hover:text-violet-700',
                isSidebarCollapsed ? 'lg:mr-0' : 'mr-3',
                'h-4 w-4 flex-shrink-0 transition-transform duration-150 group-hover:scale-105'
              ]"
            />
            <span :class="[isSidebarCollapsed ? 'lg:hidden' : 'block', 'whitespace-nowrap']">{{ item.name }}</span>
          </Link>
        </nav>
      </div>

      <!-- Sidebar Footer -->
      <div :class="[isSidebarCollapsed ? 'lg:p-2' : 'p-4', 'border-t border-violet-100/80 bg-[#FAF8FF]/60']">
        <!-- Website Quick Link -->
        <div v-if="!isSidebarCollapsed" class="mb-3">
          <a
            href="/"
            target="_blank"
            class="w-full flex items-center justify-center gap-2 py-2.5 px-3 text-xs font-bold rounded-xl bg-violet-50 text-violet-700 hover:bg-violet-100 border border-violet-200/70 transition"
          >
            <ExternalLink class="w-3.5 h-3.5" />
            <span>Kunjungi Halaman Depan</span>
          </a>
        </div>

        <div :class="[isSidebarCollapsed ? 'lg:px-0 lg:justify-center' : 'px-1 justify-between', 'mb-3 flex items-center text-[10px] text-slate-400 font-semibold tracking-wider']">
          <div v-if="!isSidebarCollapsed" class="flex items-center gap-1">
            <a href="https://synbarra.com" target="_blank" class="text-slate-500 hover:text-violet-700 transition-colors">
              Synbarra Technologies &bull; 2026
            </a>
          </div>
          <!-- Collapse Toggle for Desktop -->
          <div class="hidden lg:block">
            <button
              @click="isSidebarCollapsed = !isSidebarCollapsed"
              class="p-1.5 bg-white hover:bg-violet-50 border border-slate-200 rounded-lg text-slate-400 hover:text-violet-700 transition cursor-pointer"
              title="Toggle Sidebar"
              aria-label="Perkecil atau perbesar sidebar"
            >
              <ChevronLeft v-if="!isSidebarCollapsed" class="w-3.5 h-3.5" />
              <ChevronRight v-else class="w-3.5 h-3.5" />
            </button>
          </div>
        </div>

        <!-- User Profile -->
        <div :class="[isSidebarCollapsed ? 'lg:px-0 lg:justify-center' : 'px-1 gap-2.5', 'flex items-center pt-2.5 border-t border-violet-100/80']">
          <div class="w-8 h-8 rounded-full bg-violet-700 flex items-center justify-center font-bold text-white shrink-0 text-xs shadow-xs">
            {{ user.name.charAt(0).toUpperCase() }}
          </div>
          <div :class="[isSidebarCollapsed ? 'lg:hidden' : 'block', 'flex-1 min-w-0']">
            <p class="text-xs font-bold text-slate-900 truncate">{{ user.name }}</p>
            <p class="text-[10px] text-slate-500 truncate">{{ user.email }}</p>
          </div>
          <button
            :class="[isSidebarCollapsed ? 'lg:hidden' : 'block']"
            @click="logout"
            title="Keluar"
            class="p-1.5 text-slate-400 rounded-lg hover:bg-red-50 hover:text-red-600 transition-colors cursor-pointer"
          >
            <LogOut class="w-4 h-4" />
          </button>
        </div>
      </div>
    </aside>

    <!-- Main Content Wrapper -->
    <div
      :class="[
        isSidebarCollapsed ? 'lg:pl-20' : 'lg:pl-72',
        'flex flex-col min-h-screen transition-all duration-300 ease-in-out'
      ]"
    >
      <!-- Top header bar -->
      <header class="flex items-center justify-between h-16 px-4 md:px-8 border-b border-violet-100/80 bg-white/90 backdrop-blur-md sticky top-0 z-30">
        <div class="flex items-center gap-4 flex-1 max-w-md">
          <button
            @click="isSidebarOpen = true"
            class="p-2 text-slate-500 rounded-xl hover:bg-violet-50 hover:text-violet-700 lg:hidden"
            aria-label="Toggle Menu"
          >
            <Menu class="w-5 h-5" />
          </button>

          <div class="hidden sm:flex items-center gap-2">
            <span class="text-xs font-semibold text-slate-400">Admin:</span>
            <span class="text-xs font-bold text-slate-900">{{ user.name }}</span>
            <span class="text-[10px] px-2.5 py-0.5 rounded-full bg-violet-50 text-violet-700 font-bold border border-violet-200/70">
              Superadmin
            </span>
          </div>
        </div>

        <!-- Right Header Elements -->
        <div class="flex items-center gap-3 sm:gap-4">
          <NotificationBell />

          <div class="hidden xs:flex items-center gap-1.5 px-3 py-1 bg-emerald-50 border border-emerald-200/80 text-emerald-700 rounded-full text-[10px] font-bold uppercase tracking-wider">
            <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-pulse"></span>
            System Live
          </div>

          <a
            href="/"
            target="_blank"
            class="inline-flex items-center gap-1.5 px-3.5 py-1.5 text-xs font-semibold rounded-xl bg-violet-700 text-white hover:bg-violet-800 transition shadow-invoicerapi-sm"
          >
            <span>Lihat Website</span>
            <ExternalLink class="w-3.5 h-3.5" />
          </a>

          <!-- Profile dropdown -->
          <div class="relative">
            <button
              @click="isUserMenuOpen = !isUserMenuOpen"
              class="flex items-center gap-2 p-1 sm:px-2 sm:py-1 rounded-xl hover:bg-violet-50 border border-transparent hover:border-violet-100 transition-all text-slate-700"
            >
              <div class="w-7 h-7 rounded-lg bg-violet-100 border border-violet-200 text-violet-700 font-bold flex items-center justify-center text-xs">
                {{ user.name.charAt(0).toUpperCase() }}
              </div>
              <ChevronDown class="w-3.5 h-3.5 text-slate-400 transition-transform duration-200" :class="{ 'rotate-180': isUserMenuOpen }" />
            </button>
            <div
              v-if="isUserMenuOpen"
              @click="isUserMenuOpen = false"
              class="fixed inset-0 z-10"
            ></div>
            <div
              v-if="isUserMenuOpen"
              class="absolute right-0 mt-2 w-48 rounded-2xl bg-white border border-violet-100 shadow-invoicerapi-lg py-1.5 z-20 overflow-hidden"
            >
              <Link
                :href="route('profile.edit')"
                class="block px-4 py-2 text-xs font-semibold text-slate-700 hover:bg-violet-50 hover:text-violet-700 transition-colors"
              >
                Pengaturan Akun
              </Link>
              <button
                @click="logout"
                class="w-full text-left block px-4 py-2 text-xs font-bold text-red-600 hover:bg-red-50 transition-colors border-t border-slate-100"
              >
                Keluar
              </button>
            </div>
          </div>
        </div>
      </header>

      <!-- Main Panel Page -->
      <main class="flex-1 p-4 sm:p-6 md:p-8 pb-24 lg:pb-8">
        <!-- Toast Notification Alert -->
        <div v-if="$page.props.flash?.success" class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl flex items-center justify-between shadow-xs">
          <div class="flex items-center gap-2.5">
            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
            <span class="text-xs sm:text-sm font-semibold">{{ $page.props.flash.success }}</span>
          </div>
        </div>
        <div v-if="$page.props.flash?.error" class="mb-6 p-4 bg-red-50 border border-red-200 text-red-800 rounded-2xl flex items-center justify-between shadow-xs">
          <div class="flex items-center gap-2.5">
            <span class="w-2 h-2 rounded-full bg-red-500"></span>
            <span class="text-xs sm:text-sm font-semibold">{{ $page.props.flash.error }}</span>
          </div>
        </div>

        <slot />
      </main>
    </div>

    <!-- Mobile App Bottom Tab Bar (< lg) -->
    <nav
      class="lg:hidden fixed bottom-0 inset-x-0 z-40 bg-white/95 backdrop-blur-lg border-t border-violet-100 shadow-[0_-8px_30px_rgba(15,23,42,0.08)] pb-[env(safe-area-inset-bottom)]"
      aria-label="Navigasi Admin Mobile"
    >
      <div class="grid grid-cols-5 h-16 max-w-md mx-auto">
        <Link
          :href="route('admin.dashboard')"
          class="flex flex-col items-center justify-center gap-1 text-[10px] font-bold transition"
          :class="route().current('admin.dashboard') ? 'text-violet-700' : 'text-slate-500'"
        >
          <LayoutDashboard class="w-5 h-5" />
          <span>Home</span>
        </Link>
        <Link
          :href="route('admin.payments.index')"
          class="flex flex-col items-center justify-center gap-1 text-[10px] font-bold transition"
          :class="route().current('admin.payments.*') ? 'text-violet-700' : 'text-slate-500'"
        >
          <CreditCard class="w-5 h-5" />
          <span>Bayar</span>
        </Link>
        <Link
          :href="route('admin.trial-tracker.index')"
          class="flex flex-col items-center justify-center gap-1 text-[10px] font-bold transition"
          :class="route().current('admin.trial-tracker.*') ? 'text-violet-700' : 'text-slate-500'"
        >
          <Users class="w-5 h-5" />
          <span>Trial</span>
        </Link>
        <Link
          :href="route('admin.leads.index')"
          class="flex flex-col items-center justify-center gap-1 text-[10px] font-bold transition"
          :class="route().current('admin.leads.*') ? 'text-violet-700' : 'text-slate-500'"
        >
          <ListTodo class="w-5 h-5" />
          <span>Leads</span>
        </Link>
        <button
          type="button"
          @click="isSidebarOpen = true"
          class="flex flex-col items-center justify-center gap-1 text-[10px] font-bold text-slate-500 hover:text-violet-700 transition"
        >
          <Menu class="w-5 h-5" />
          <span>Menu</span>
        </button>
      </div>
    </nav>
  </div>
</template>
