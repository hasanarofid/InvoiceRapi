<script setup lang="ts">
import { ref } from 'vue';
import { Link, router, usePage } from '@inertiajs/vue3';
import SynbarraLogo from '@/Components/UI/SynbarraLogo.vue';
import {
  LayoutDashboard,
  Users,
  FolderKanban,
  FileText,
  CreditCard,
  BarChart3,
  Settings,
  Bell,
  ChevronDown,
  Menu,
  X,
  Crown,
  ArrowRight,
  LogOut,
  UserCheck
} from 'lucide-vue-next';

interface Props {
  title?: string;
}

defineProps<Props>();

const page = usePage();
const user = page.props.auth?.user;
const tenant = user?.tenant;

const isMobileMenuOpen = ref(false);
const isUserDropdownOpen = ref(false);

const navigation = [
  { name: 'Dashboard', href: route('dashboard'), icon: LayoutDashboard, routeName: 'dashboard' },
  { name: 'Klien', href: route('clients.index'), icon: Users, routeName: 'clients.*' },
  { name: 'Proyek', href: route('projects.index'), icon: FolderKanban, routeName: 'projects.*' },
  { name: 'Invoice', href: route('invoices.index'), icon: FileText, routeName: 'invoices.*' },
  { name: 'Pembayaran', href: '#', icon: CreditCard, routeName: 'payments.*' },
  { name: 'Laporan', href: '#', icon: BarChart3, routeName: 'reports.*' },
  { name: 'Pengaturan', href: '#', icon: Settings, routeName: 'settings.*' },
];

const logout = () => {
  router.post(route('logout'));
};

const userInitials = (user?.name || 'User')
  .split(' ')
  .map((n: string) => n[0])
  .slice(0, 2)
  .join('')
  .toUpperCase();
</script>

<template>
  <div class="min-h-screen bg-[#F8FAFC] text-slate-900 font-sans antialiased flex flex-col md:flex-row">
    <!-- Desktop Sidebar -->
    <aside class="hidden md:flex flex-col w-64 bg-white border-r border-slate-200/80 p-5 shrink-0 justify-between min-h-screen sticky top-0">
      <div class="space-y-6">
        <!-- Logo -->
        <div class="px-2 pt-1">
          <Link :href="route('dashboard')" class="inline-block">
            <SynbarraLogo size="sm" variant="horizontal" />
          </Link>
        </div>

        <!-- Navigation Menu -->
        <nav class="space-y-1.5">
          <Link
            v-for="item in navigation"
            :key="item.name"
            :href="item.href"
            :class="[
              'flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-all duration-150',
              route().current(item.routeName)
                ? 'bg-violet-700 text-white shadow-sm shadow-violet-200 font-bold'
                : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100/70'
            ]"
          >
            <component :is="item.icon" class="w-4 h-4 shrink-0" />
            <span>{{ item.name }}</span>
          </Link>
        </nav>
      </div>

      <!-- Bottom Card: Subscription Plan -->
      <div class="p-4 rounded-2xl bg-gradient-to-br from-violet-50 to-purple-50 border border-violet-100/80 space-y-2.5">
        <div class="flex items-center gap-2">
          <div class="w-6 h-6 rounded-lg bg-violet-600 text-white flex items-center justify-center shrink-0 shadow-sm text-xs">
            👑
          </div>
          <div>
            <p class="text-xs font-black text-violet-950">Paket Pro</p>
            <p class="text-[11px] text-violet-700">Masa Coba 14 Hari</p>
          </div>
        </div>

        <Link
          href="#"
          class="flex items-center justify-between text-xs font-bold text-violet-700 hover:text-violet-900 pt-1 group"
        >
          <span>Kelola Langganan</span>
          <ArrowRight class="w-3.5 h-3.5 transform group-hover:translate-x-0.5 transition" />
        </Link>
      </div>
    </aside>

    <!-- Mobile Header -->
    <header class="md:hidden bg-white border-b border-slate-200 px-4 py-3 flex items-center justify-between sticky top-0 z-40">
      <SynbarraLogo size="sm" variant="horizontal" />
      <button
        type="button"
        @click="isMobileMenuOpen = !isMobileMenuOpen"
        class="p-2 text-slate-600 hover:text-slate-900 rounded-lg"
      >
        <Menu v-if="!isMobileMenuOpen" class="w-6 h-6" />
        <X v-else class="w-6 h-6" />
      </button>
    </header>

    <!-- Mobile Slideover Navigation -->
    <div
      v-if="isMobileMenuOpen"
      class="fixed inset-0 z-50 md:hidden bg-slate-900/40 backdrop-blur-xs flex"
    >
      <div class="w-64 bg-white h-full p-5 flex flex-col justify-between shadow-2xl">
        <div class="space-y-6">
          <div class="flex items-center justify-between">
            <SynbarraLogo size="sm" variant="horizontal" />
            <button @click="isMobileMenuOpen = false" class="p-1 text-slate-400">
              <X class="w-5 h-5" />
            </button>
          </div>

          <nav class="space-y-1">
            <Link
              v-for="item in navigation"
              :key="item.name"
              :href="item.href"
              @click="isMobileMenuOpen = false"
              :class="[
                'flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition',
                route().current(item.routeName)
                  ? 'bg-violet-700 text-white font-bold'
                  : 'text-slate-600 hover:bg-slate-100'
              ]"
            >
              <component :is="item.icon" class="w-4 h-4" />
              <span>{{ item.name }}</span>
            </Link>
          </nav>
        </div>

        <button
          type="button"
          @click="logout"
          class="flex items-center gap-2 text-xs font-bold text-red-600 p-2 hover:bg-red-50 rounded-lg"
        >
          <LogOut class="w-4 h-4" />
          <span>Keluar Akun</span>
        </button>
      </div>
    </div>

    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col min-w-0">
      <!-- Top Navbar (Workspace Switcher & Profile) -->
      <nav class="bg-white border-b border-slate-200/80 px-4 sm:px-8 py-3.5 flex items-center justify-between sticky top-0 z-30">
        <!-- Workspace Switcher -->
        <div class="flex items-center gap-2">
          <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-700 font-black flex items-center justify-center text-xs border border-emerald-200">
            🌿
          </div>
          <button
            type="button"
            class="flex items-center gap-1.5 text-xs sm:text-sm font-bold text-slate-900 hover:text-violet-700 transition"
          >
            <span>{{ tenant?.name || 'Mulia Event Organizer' }}</span>
            <ChevronDown class="w-3.5 h-3.5 text-slate-400" />
          </button>
        </div>

        <!-- Right Side: Notification & Profile -->
        <div class="flex items-center gap-3 sm:gap-4">
          <!-- Notification Bell -->
          <button
            type="button"
            class="relative p-2 rounded-xl text-slate-500 hover:text-slate-800 hover:bg-slate-100 transition"
            title="Notifikasi jatuh tempo"
          >
            <Bell class="w-4 h-4" />
            <span class="absolute top-1.5 right-1.5 w-2 h-2 rounded-full bg-red-500 ring-2 ring-white"></span>
          </button>

          <!-- User Profile Avatar Pill -->
          <div class="relative">
            <button
              type="button"
              @click="isUserDropdownOpen = !isUserDropdownOpen"
              class="flex items-center gap-2 p-1 rounded-full hover:bg-slate-100 transition"
            >
              <div class="w-8 h-8 rounded-full bg-violet-700 text-white font-bold text-xs flex items-center justify-center shadow-sm">
                {{ userInitials }}
              </div>
            </button>

            <!-- Dropdown -->
            <div
              v-if="isUserDropdownOpen"
              class="absolute right-0 mt-2 w-48 bg-white border border-slate-200 rounded-2xl shadow-xl py-2 z-50 text-xs"
            >
              <div class="px-4 py-2 border-b border-slate-100">
                <p class="font-bold text-slate-900">{{ user?.name }}</p>
                <p class="text-[11px] text-slate-500 truncate">{{ user?.email }}</p>
              </div>

              <Link
                :href="route('profile.edit')"
                class="flex items-center gap-2 px-4 py-2 text-slate-700 hover:bg-slate-50"
              >
                <UserCheck class="w-3.5 h-3.5" />
                <span>Profil Akun</span>
              </Link>

              <button
                type="button"
                @click="logout"
                class="w-full flex items-center gap-2 px-4 py-2 text-red-600 hover:bg-red-50 text-left font-semibold"
              >
                <LogOut class="w-3.5 h-3.5" />
                <span>Keluar</span>
              </button>
            </div>
          </div>
        </div>
      </nav>

      <!-- Page Slot -->
      <main class="flex-1 p-4 sm:p-8 max-w-7xl w-full mx-auto">
        <slot />
      </main>
    </div>
  </div>
</template>

