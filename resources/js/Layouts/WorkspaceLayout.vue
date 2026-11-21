<script setup lang="ts">
import { ref, watch } from 'vue';
import { Link, router, usePage } from '@inertiajs/vue3';
import NotificationBell from '@/Components/NotificationBell.vue';
import MemberSupportWhatsapp from '@/Components/MemberSupportWhatsapp.vue';
import { swalSuccess, swalError } from '@/utils/swal';
import {
  LayoutDashboard,
  Users,
  FolderKanban,
  FileText,
  CreditCard,
  BarChart3,
  Settings,
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

watch(
  () => (page.props as any).flash,
  (flash) => {
    if (flash?.success) swalSuccess(flash.success);
    if (flash?.error) swalError('Terjadi Kesalahan', flash.error);
  },
  { immediate: true, deep: true }
);

const isMobileMenuOpen = ref(false);
const isUserDropdownOpen = ref(false);

const navigation = [
  { name: 'Dashboard', href: route('dashboard'), icon: LayoutDashboard, routeName: 'dashboard' },
  { name: 'Klien', href: route('clients.index'), icon: Users, routeName: 'clients.*' },
  { name: 'Proyek', href: route('projects.index'), icon: FolderKanban, routeName: 'projects.*' },
  { name: 'Invoice', href: route('invoices.index'), icon: FileText, routeName: 'invoices.*' },
  { name: 'Langganan & Kuota', href: route('billing.index'), icon: CreditCard, routeName: 'billing.*' },
  { name: 'Profil Akun', href: route('profile.edit'), icon: Settings, routeName: 'profile.*' },
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
  <div class="min-h-screen bg-[#FAF8FF] text-slate-900 font-sans antialiased flex flex-col md:flex-row">
    <!-- Desktop Sidebar -->
    <aside class="hidden md:flex flex-col w-64 bg-white border-r border-violet-100/80 p-5 shrink-0 justify-between min-h-screen sticky top-0">
      <div class="space-y-6">
        <!-- Logo -->
        <div class="px-1 pt-1">
          <Link :href="route('dashboard')" class="inline-flex items-center gap-2.5 group">
            <span class="w-8 h-8 rounded-xl bg-gradient-to-br from-violet-600 to-violet-800 text-white flex items-center justify-center shadow-invoicerapi-sm shrink-0">
              <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                <polyline points="14 2 14 8 20 8" />
                <line x1="8" y1="13" x2="16" y2="13" />
                <line x1="8" y1="17" x2="13" y2="17" />
              </svg>
            </span>
            <span class="flex flex-col leading-none">
              <span class="text-[10px] font-bold tracking-wide text-violet-700">Synbarra</span>
              <span class="text-base font-extrabold tracking-tight text-slate-900">InvoiceRapi</span>
            </span>
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
            <Crown class="w-3.5 h-3.5" />
          </div>
          <div class="min-w-0">
            <p class="text-xs font-black text-violet-950 truncate">Paket {{ page.props.planUsage?.plan_name || 'Gratis' }}</p>
            <p class="text-[11px] text-violet-700 truncate">
              <template v-if="page.props.planUsage?.subscription_status === 'active'">
                Aktif s/d {{ page.props.planUsage?.expires_at_formatted }}
              </template>
              <template v-else-if="page.props.planUsage?.is_trial && !page.props.planUsage?.is_trial_expired">
                Trial s/d {{ page.props.planUsage?.expires_at_formatted }} ({{ page.props.planUsage?.days_remaining }} hr)
              </template>
              <template v-else>
                {{ page.props.planUsage?.expires_at_formatted || 'Gratis Selamanya' }}
              </template>
            </p>
          </div>
        </div>

        <div v-if="page.props.planUsage" class="text-[11px] text-slate-600 space-y-1 pt-1">
          <div class="flex justify-between">
            <span>Invoice bln ini</span>
            <span class="font-bold" :class="page.props.planUsage.invoice_limit_reached ? 'text-red-600' : 'text-slate-900'">
              {{ page.props.planUsage.invoices_this_month }}/{{ page.props.planUsage.max_invoices_per_month ?? '∞' }}
              <span v-if="page.props.planUsage.invoices_remaining !== null" class="text-[10px] font-semibold text-violet-700">
                (Sisa {{ page.props.planUsage.invoices_remaining }})
              </span>
            </span>
          </div>
          <div v-if="page.props.planUsage.max_clients" class="flex justify-between">
            <span>Klien</span>
            <span class="font-bold" :class="page.props.planUsage.client_limit_reached ? 'text-red-600' : 'text-slate-900'">
              {{ page.props.planUsage.clients_count }}/{{ page.props.planUsage.max_clients }}
              <span v-if="page.props.planUsage.clients_remaining !== null" class="text-[10px] font-semibold text-violet-700">
                (Sisa {{ page.props.planUsage.clients_remaining }})
              </span>
            </span>
          </div>
        </div>

        <Link
          :href="route('billing.index')"
          class="flex items-center justify-between text-xs font-bold text-violet-700 hover:text-violet-900 pt-1 group"
        >
          <span>{{ planUsage?.any_limit_reached ? 'Upgrade Sekarang' : 'Kelola Langganan' }}</span>
          <ArrowRight class="w-3.5 h-3.5 transform group-hover:translate-x-0.5 transition" />
        </Link>
      </div>
    </aside>

    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col min-w-0">
      <!-- Top Navbar (Unified Mobile App Bar & Desktop Header) -->
      <nav class="bg-white/95 backdrop-blur-md border-b border-slate-200/80 px-4 sm:px-8 py-3 flex items-center justify-between sticky top-0 z-30">
        <!-- Left: Mobile Brand + Workspace Switcher -->
        <div class="flex items-center gap-2.5 min-w-0">
          <Link :href="route('dashboard')" class="md:hidden w-8 h-8 rounded-xl bg-gradient-to-br from-violet-600 to-violet-800 text-white flex items-center justify-center shadow-sm shrink-0 overflow-hidden">
            <img v-if="page.props.auth?.user?.tenant?.logo_url" :src="page.props.auth.user.tenant.logo_url" alt="Logo" class="w-full h-full object-cover bg-white" />
            <svg v-else class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
              <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
              <polyline points="14 2 14 8 20 8" />
            </svg>
          </Link>
          <div v-if="page.props.auth?.user?.tenant?.logo_url" class="hidden md:flex w-8 h-8 rounded-lg overflow-hidden border border-slate-200 bg-white shrink-0">
            <img :src="page.props.auth.user.tenant.logo_url" alt="Logo Usaha" class="w-full h-full object-contain p-0.5" />
          </div>
          <div v-else class="hidden md:flex w-8 h-8 rounded-lg bg-emerald-50 text-emerald-700 font-black items-center justify-center text-xs border border-emerald-200 shrink-0">
            🌿
          </div>
          <div class="min-w-0">
            <p class="text-xs sm:text-sm font-bold text-slate-900 truncate">{{ page.props.auth?.user?.tenant?.name || 'Workspace' }}</p>
            <p class="md:hidden text-[10px] font-semibold text-violet-700 truncate">Paket {{ page.props.planUsage?.plan_name || 'Gratis' }}</p>
          </div>
        </div>

        <!-- Right Side: Notification & Profile -->
        <div class="flex items-center gap-3 sm:gap-4">
          <NotificationBell />

          <!-- User Profile Avatar Pill -->
          <div class="relative">
            <button
              type="button"
              @click="isUserDropdownOpen = !isUserDropdownOpen"
              class="flex items-center gap-2 p-1 rounded-full hover:bg-slate-100 transition"
            >
              <div class="w-8 h-8 rounded-full bg-violet-700 text-white font-bold text-xs flex items-center justify-center shadow-sm overflow-hidden border border-violet-200">
                <img
                  v-if="page.props.auth?.user?.tenant?.logo_url"
                  :src="page.props.auth.user.tenant.logo_url"
                  alt="Avatar Usaha"
                  class="w-full h-full object-cover bg-white"
                />
                <span v-else>{{ userInitials }}</span>
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
      <main class="flex-1 p-4 sm:p-8 pb-24 md:pb-8 max-w-7xl w-full mx-auto">
        <slot />
      </main>
    </div>

    <!-- Mobile App Slideover Drawer -->
    <div
      v-if="isMobileMenuOpen"
      @click.self="isMobileMenuOpen = false"
      class="fixed inset-0 z-50 md:hidden bg-slate-900/40 backdrop-blur-xs flex"
    >
      <div class="w-72 bg-white h-full p-5 flex flex-col justify-between shadow-2xl overflow-y-auto">
        <div class="space-y-6">
          <div class="flex items-center justify-between">
            <span class="text-base font-extrabold tracking-tight text-slate-900">Synbarra <span class="text-violet-700">InvoiceRapi</span></span>
            <button @click="isMobileMenuOpen = false" class="p-1.5 rounded-lg text-slate-400 hover:bg-slate-100">
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
          class="flex items-center gap-2 text-xs font-bold text-red-600 p-2.5 hover:bg-red-50 rounded-xl mt-4"
        >
          <LogOut class="w-4 h-4" />
          <span>Keluar Akun</span>
        </button>
      </div>
    </div>

    <!-- Mobile App Bottom Tab Bar (< md) -->
    <nav
      class="md:hidden fixed bottom-0 inset-x-0 z-40 bg-white/95 backdrop-blur-lg border-t border-violet-100 shadow-[0_-8px_30px_rgba(15,23,42,0.08)] pb-[env(safe-area-inset-bottom)]"
      aria-label="Navigasi Member Mobile"
    >
      <div class="grid grid-cols-5 h-16 max-w-md mx-auto">
        <Link
          :href="route('dashboard')"
          class="flex flex-col items-center justify-center gap-1 text-[10px] font-bold transition"
          :class="route().current('dashboard') ? 'text-violet-700' : 'text-slate-500'"
        >
          <LayoutDashboard class="w-5 h-5" />
          <span>Home</span>
        </Link>
        <Link
          :href="route('clients.index')"
          class="flex flex-col items-center justify-center gap-1 text-[10px] font-bold transition"
          :class="route().current('clients.*') ? 'text-violet-700' : 'text-slate-500'"
        >
          <Users class="w-5 h-5" />
          <span>Klien</span>
        </Link>
        <Link
          :href="route('projects.index')"
          class="flex flex-col items-center justify-center gap-1 text-[10px] font-bold transition"
          :class="route().current('projects.*') ? 'text-violet-700' : 'text-slate-500'"
        >
          <FolderKanban class="w-5 h-5" />
          <span>Proyek</span>
        </Link>
        <Link
          :href="route('invoices.index')"
          class="flex flex-col items-center justify-center gap-1 text-[10px] font-bold transition"
          :class="route().current('invoices.*') ? 'text-violet-700' : 'text-slate-500'"
        >
          <FileText class="w-5 h-5" />
          <span>Invoice</span>
        </Link>
        <button
          type="button"
          @click="isMobileMenuOpen = true"
          class="flex flex-col items-center justify-center gap-1 text-[10px] font-bold text-slate-500 hover:text-violet-700 transition"
        >
          <Menu class="w-5 h-5" />
          <span>Menu</span>
        </button>
      </div>
    </nav>

    <!-- Floating Admin WhatsApp Support (Keluhan & Saran) -->
    <MemberSupportWhatsapp />
  </div>
</template>

