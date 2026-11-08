<script setup lang="ts">
import { ref } from 'vue';
import { Head, router, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import {
  Users,
  Clock,
  AlertTriangle,
  CheckCircle2,
  Search,
  MessageCircle,
  Calendar,
  Building2,
  ExternalLink,
  Edit3,
  X,
  Send,
  Phone
} from 'lucide-vue-next';

interface TenantItem {
  id: number;
  name: string;
  business_type: string;
  phone_whatsapp: string;
  email: string;
  owner_name: string;
  subscription_plan: string;
  subscription_status: string;
  trial_starts_at: string;
  trial_ends_at: string;
  days_remaining: number | null;
  urgency: 'normal' | 'warning' | 'critical' | 'expired' | 'paid';
  last_follow_up_at: string;
  follow_up_notes: string | null;
  wa_link: string;
}

interface Props {
  tenants: {
    data: TenantItem[];
    links: any[];
  };
  filters: {
    status: string;
    search: string;
  };
  metrics: {
    total_trials: number;
    expiring_soon: number;
    expired: number;
    paid: number;
  };
}

const props = defineProps<Props>();

const searchQuery = ref(props.filters.search || '');
const activeStatus = ref(props.filters.status || 'all');

// Note Modal State
const selectedTenant = ref<TenantItem | null>(null);
const isNoteModalOpen = ref(false);

const noteForm = useForm({
  notes: '',
});

const openNoteModal = (tenant: TenantItem) => {
  selectedTenant.value = tenant;
  noteForm.notes = tenant.follow_up_notes || '';
  isNoteModalOpen.value = true;
};

const closeNoteModal = () => {
  selectedTenant.value = null;
  isNoteModalOpen.value = false;
};

const submitNote = () => {
  if (!selectedTenant.value) return;
  noteForm.post(route('admin.trial-tracker.follow-up', selectedTenant.value.id), {
    onSuccess: () => {
      closeNoteModal();
    },
  });
};

const handleSearch = () => {
  router.get(
    route('admin.trial-tracker.index'),
    {
      search: searchQuery.value,
      status: activeStatus.value,
    },
    { preserveState: true, replace: true }
  );
};

const changeStatusFilter = (status: string) => {
  activeStatus.value = status;
  router.get(
    route('admin.trial-tracker.index'),
    {
      search: searchQuery.value,
      status: status,
    },
    { preserveState: true, replace: true }
  );
};
</script>

<template>
  <AdminLayout>
    <Head title="Trial & Lead Tracker - Synbarra InvoiceRapi" />

    <div class="space-y-6">
      <!-- Page Header -->
      <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
          <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">
            Trial & WhatsApp Follow-up Tracker
          </h1>
          <p class="text-xs sm:text-sm text-slate-500 mt-1">
            Pantau sisa masa uji coba 14 hari pengguna dan lakukan follow-up konversi langsung via WhatsApp.
          </p>
        </div>
      </div>

      <!-- 4 Summary Metric Cards -->
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- 1. Total User Trial -->
        <div class="p-5 rounded-2xl bg-white border border-slate-200/80 shadow-sm flex items-center justify-between">
          <div>
            <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Total User Trial</p>
            <p class="text-2xl font-black text-violet-700 mt-1">{{ metrics.total_trials }}</p>
            <p class="text-[11px] text-slate-400 mt-0.5">Workspace masa uji coba</p>
          </div>
          <div class="w-12 h-12 rounded-xl bg-violet-50 text-violet-600 flex items-center justify-center">
            <Users class="w-6 h-6" />
          </div>
        </div>

        <!-- 2. Akan Habis (H-3) -->
        <div class="p-5 rounded-2xl bg-white border border-amber-200 shadow-sm flex items-center justify-between">
          <div>
            <p class="text-xs font-bold text-amber-700 uppercase tracking-wider">Akan Habis (≤ 3 Hari)</p>
            <p class="text-2xl font-black text-amber-600 mt-1">{{ metrics.expiring_soon }}</p>
            <p class="text-[11px] text-amber-600/80 mt-0.5">Prioritas follow-up hari ini</p>
          </div>
          <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
            <AlertTriangle class="w-6 h-6" />
          </div>
        </div>

        <!-- 3. Expired -->
        <div class="p-5 rounded-2xl bg-white border border-red-200 shadow-sm flex items-center justify-between">
          <div>
            <p class="text-xs font-bold text-red-700 uppercase tracking-wider">Trial Berakhir</p>
            <p class="text-2xl font-black text-red-600 mt-1">{{ metrics.expired }}</p>
            <p class="text-[11px] text-red-500 mt-0.5">Perlu penawaran aktivasi</p>
          </div>
          <div class="w-12 h-12 rounded-xl bg-red-50 text-red-600 flex items-center justify-center">
            <Clock class="w-6 h-6" />
          </div>
        </div>

        <!-- 4. Berlangganan (Paid) -->
        <div class="p-5 rounded-2xl bg-white border border-emerald-200 shadow-sm flex items-center justify-between">
          <div>
            <p class="text-xs font-bold text-emerald-700 uppercase tracking-wider">Berlangganan Aktif</p>
            <p class="text-2xl font-black text-emerald-600 mt-1">{{ metrics.paid }}</p>
            <p class="text-[11px] text-emerald-600/80 mt-0.5">Pelanggan berbayar resmi</p>
          </div>
          <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
            <CheckCircle2 class="w-6 h-6" />
          </div>
        </div>
      </div>

      <!-- Filter Tabs & Search Bar -->
      <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
        <!-- Tabs -->
        <div class="flex items-center gap-1.5 overflow-x-auto pb-1 md:pb-0">
          <button
            type="button"
            @click="changeStatusFilter('all')"
            :class="[
              'px-3.5 py-1.5 rounded-full text-xs font-bold transition flex-shrink-0',
              activeStatus === 'all'
                ? 'bg-violet-700 text-white shadow-sm'
                : 'text-slate-600 hover:bg-slate-100'
            ]"
          >
            Semua ({{ metrics.total_trials + metrics.paid }})
          </button>
          <button
            type="button"
            @click="changeStatusFilter('expiring_soon')"
            :class="[
              'px-3.5 py-1.5 rounded-full text-xs font-bold transition flex-shrink-0',
              activeStatus === 'expiring_soon'
                ? 'bg-amber-500 text-white shadow-sm'
                : 'text-slate-600 hover:bg-slate-100'
            ]"
          >
            Akan Habis H-3 ({{ metrics.expiring_soon }})
          </button>
          <button
            type="button"
            @click="changeStatusFilter('expired')"
            :class="[
              'px-3.5 py-1.5 rounded-full text-xs font-bold transition flex-shrink-0',
              activeStatus === 'expired'
                ? 'bg-red-600 text-white shadow-sm'
                : 'text-slate-600 hover:bg-slate-100'
            ]"
          >
            Expired ({{ metrics.expired }})
          </button>
          <button
            type="button"
            @click="changeStatusFilter('paid')"
            :class="[
              'px-3.5 py-1.5 rounded-full text-xs font-bold transition flex-shrink-0',
              activeStatus === 'paid'
                ? 'bg-emerald-600 text-white shadow-sm'
                : 'text-slate-600 hover:bg-slate-100'
            ]"
          >
            Berbayar ({{ metrics.paid }})
          </button>
        </div>

        <!-- Search Input -->
        <form @submit.prevent="handleSearch" class="relative w-full md:w-72">
          <input
            type="text"
            v-model="searchQuery"
            placeholder="Cari bisnis, WA, atau email..."
            class="w-full pl-9 pr-4 py-1.5 text-xs rounded-full border border-slate-200 focus:border-violet-600 focus:ring-violet-600"
          />
          <Search class="w-4 h-4 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2" />
        </form>
      </div>

      <!-- Tenants Table -->
      <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
          <table class="w-full text-left text-xs">
            <thead class="bg-slate-50 border-b border-slate-200 text-slate-600 uppercase font-semibold text-[11px] tracking-wider">
              <tr>
                <th class="px-5 py-3.5">Bisnis & Pemilik</th>
                <th class="px-5 py-3.5">Kontak WhatsApp</th>
                <th class="px-5 py-3.5">Kategori</th>
                <th class="px-5 py-3.5">Sisa Waktu Trial</th>
                <th class="px-5 py-3.5">Status Follow-up</th>
                <th class="px-5 py-3.5 text-right">Aksi Follow-Up</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
              <tr v-if="tenants.data.length === 0">
                <td colspan="6" class="px-5 py-12 text-center text-slate-400">
                  Belum ada data user trial yang sesuai filter.
                </td>
              </tr>
              <tr
                v-for="item in tenants.data"
                :key="item.id"
                class="hover:bg-slate-50/70 transition"
              >
                <!-- 1. Bisnis & Pemilik -->
                <td class="px-5 py-4">
                  <div class="font-bold text-slate-900 text-sm flex items-center gap-1.5">
                    <Building2 class="w-4 h-4 text-violet-600" />
                    <span>{{ item.name }}</span>
                  </div>
                  <div class="text-[11px] text-slate-500 mt-0.5">
                    Owner: <span class="font-medium text-slate-700">{{ item.owner_name }}</span> ({{ item.email }})
                  </div>
                </td>

                <!-- 2. WhatsApp -->
                <td class="px-5 py-4">
                  <div class="inline-flex items-center gap-1.5 font-mono text-xs font-semibold text-slate-800 bg-slate-100 px-2.5 py-1 rounded-lg">
                    <Phone class="w-3.5 h-3.5 text-emerald-600" />
                    <span>+{{ item.phone_whatsapp }}</span>
                  </div>
                </td>

                <!-- 3. Kategori -->
                <td class="px-5 py-4">
                  <span class="inline-block px-2.5 py-1 rounded-full text-[11px] font-medium bg-slate-100 text-slate-700">
                    {{ item.business_type }}
                  </span>
                </td>

                <!-- 4. Sisa Waktu Trial -->
                <td class="px-5 py-4">
                  <!-- Paid Status -->
                  <span
                    v-if="item.urgency === 'paid'"
                    class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800"
                  >
                    <CheckCircle2 class="w-3.5 h-3.5" />
                    Berlangganan Aktif
                  </span>

                  <!-- Critical: <= 3 Days -->
                  <span
                    v-else-if="item.urgency === 'critical'"
                    class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-900 animate-pulse border border-amber-300"
                  >
                    <AlertTriangle class="w-3.5 h-3.5 text-amber-600" />
                    Sisa {{ item.days_remaining }} Hari (Kritis)
                  </span>

                  <!-- Expired -->
                  <span
                    v-else-if="item.urgency === 'expired'"
                    class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-bold bg-red-100 text-red-800"
                  >
                    <Clock class="w-3.5 h-3.5" />
                    Trial Berakhir
                  </span>

                  <!-- Normal > 3 Days -->
                  <span
                    v-else
                    class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-bold bg-violet-100 text-violet-800"
                  >
                    <Clock class="w-3.5 h-3.5 text-violet-600" />
                    Sisa {{ item.days_remaining }} Hari
                  </span>

                  <div class="text-[10px] text-slate-400 mt-1">
                    Hingga: {{ item.trial_ends_at }}
                  </div>
                </td>

                <!-- 5. Status Follow-up -->
                <td class="px-5 py-4">
                  <div class="text-[11px] font-medium text-slate-700">
                    {{ item.last_follow_up_at }}
                  </div>
                  <p v-if="item.follow_up_notes" class="text-[10px] text-slate-500 italic mt-0.5 line-clamp-1 max-w-xs">
                    "{{ item.follow_up_notes }}"
                  </p>
                  <p v-else class="text-[10px] text-slate-400 mt-0.5">
                    Belum ada catatan
                  </p>
                </td>

                <!-- 6. Aksi Follow-up WhatsApp -->
                <td class="px-5 py-4 text-right">
                  <div class="flex items-center justify-end gap-2">
                    <!-- WhatsApp Direct Button -->
                    <a
                      :href="item.wa_link"
                      target="_blank"
                      rel="noopener noreferrer"
                      class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-700 shadow-sm transition transform hover:-translate-y-0.5"
                      title="Kirim pesan follow-up otomatis ke WhatsApp"
                    >
                      <MessageCircle class="w-3.5 h-3.5" />
                      <span>Chat WA</span>
                    </a>

                    <!-- Catatan Button -->
                    <button
                      type="button"
                      @click="openNoteModal(item)"
                      class="p-1.5 rounded-lg text-slate-500 hover:text-slate-800 hover:bg-slate-100 transition"
                      title="Update catatan follow-up"
                    >
                      <Edit3 class="w-4 h-4" />
                    </button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- Note & Progress Follow-up Modal -->
    <Teleport to="body">
      <div
        v-if="isNoteModalOpen"
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-xs"
      >
        <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl space-y-4">
          <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <div>
              <h3 class="text-base font-bold text-slate-900">Catatan Follow-Up Klien</h3>
              <p class="text-xs text-slate-500 mt-0.5">{{ selectedTenant?.name }} (+{{ selectedTenant?.phone_whatsapp }})</p>
            </div>
            <button
              type="button"
              @click="closeNoteModal"
              class="p-1.5 text-slate-400 hover:text-slate-700 rounded-lg"
            >
              <X class="w-5 h-5" />
            </button>
          </div>

          <form @submit.prevent="submitNote" class="space-y-4">
            <div>
              <label class="block text-xs font-semibold text-slate-700 mb-1">
                Catatan Hasil Chat / Rencana Penawaran
              </label>
              <textarea
                v-model="noteForm.notes"
                rows="4"
                placeholder="Contoh: Klien merespons positif, minta diskon paket tahunan Pro, janji transfer besok..."
                class="w-full text-xs rounded-xl border-slate-200 focus:border-violet-600 focus:ring-violet-600"
              ></textarea>
            </div>

            <div class="flex items-center justify-end gap-2 pt-2">
              <button
                type="button"
                @click="closeNoteModal"
                class="px-4 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-100 rounded-full"
              >
                Batal
              </button>
              <button
                type="submit"
                class="px-5 py-2 text-xs font-bold text-white bg-violet-700 hover:bg-violet-800 rounded-full shadow-sm disabled:opacity-50 flex items-center gap-1.5"
                :disabled="noteForm.processing"
              >
                <Send class="w-3.5 h-3.5" />
                <span>{{ noteForm.processing ? 'Menyimpan...' : 'Simpan Catatan' }}</span>
              </button>
            </div>
          </form>
        </div>
      </div>
    </Teleport>
  </AdminLayout>
</template>

