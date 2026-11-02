<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, router, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import { 
  Users, 
  Search, 
  MessageCircle, 
  Mail, 
  Building2, 
  Calendar, 
  Plus, 
  Edit, 
  Trash2, 
  CheckCircle2, 
  Clock, 
  AlertCircle,
  FileText,
  DollarSign
} from '@lucide/vue';

const props = defineProps({
  leads: {
    type: Object,
    default: () => ({ data: [] }),
  },
  statusCounts: {
    type: Object,
    default: () => ({}),
  },
  filters: {
    type: Object,
    default: () => ({}),
  },
});

const searchQuery = ref(props.filters.search || '');
const selectedStatus = ref(props.filters.status || 'all');

const applyFilters = () => {
  router.get(route('admin.leads.index'), {
    search: searchQuery.value,
    status: selectedStatus.value,
  }, {
    preserveState: true,
    preserveScroll: true,
  });
};

const filterByStatus = (status) => {
  selectedStatus.value = status;
  applyFilters();
};

// Modal for manual lead / edit lead
const isModalOpen = ref(false);
const isEditing = ref(false);
const editingLeadId = ref(null);

const form = useForm({
  full_name: '',
  company_name: '',
  email: '',
  whatsapp_number: '',
  service_required: 'Custom Business System (ERP/CRM)',
  estimated_budget: '',
  project_description: '',
  source: 'website_contact',
  status: 'new',
  follow_up_date: '',
  notes: '',
});

const openCreateModal = () => {
  isEditing.value = false;
  editingLeadId.value = null;
  form.reset();
  form.source = 'direct_outreach';
  form.status = 'new';
  isModalOpen.value = true;
};

const openEditModal = (lead) => {
  isEditing.value = true;
  editingLeadId.value = lead.id;
  form.full_name = lead.full_name;
  form.company_name = lead.company_name || '';
  form.email = lead.email || '';
  form.whatsapp_number = lead.whatsapp_number;
  form.service_required = lead.service_required;
  form.estimated_budget = lead.estimated_budget || '';
  form.project_description = lead.project_description || '';
  form.source = lead.source;
  form.status = lead.status;
  form.follow_up_date = lead.follow_up_date || '';
  form.notes = lead.notes || '';
  isModalOpen.value = true;
};

const submitForm = () => {
  if (isEditing.value) {
    form.put(route('admin.leads.update', editingLeadId.value), {
      onSuccess: () => {
        isModalOpen.value = false;
        form.reset();
      },
    });
  } else {
    form.post(route('admin.leads.store'), {
      onSuccess: () => {
        isModalOpen.value = false;
        form.reset();
      },
    });
  }
};

const deleteLead = (lead) => {
  if (confirm(`Yakin ingin menghapus prospek ${lead.full_name}?`)) {
    router.delete(route('admin.leads.destroy', lead.id), {
      preserveScroll: true,
    });
  }
};

const formatWaLink = (phone, name) => {
  let clean = phone.replace(/\D/g, '');
  if (clean.startsWith('0')) {
    clean = '62' + clean.slice(1);
  }
  const text = encodeURIComponent(`Halo ${name}, perkenalkan kami dari Synbarra Technologies. Kami menindaklanjuti pesan konsultasi Anda...`);
  return `https://wa.me/${clean}?text=${text}`;
};

const getStatusBadge = (status) => {
  switch (status) {
    case 'new':
      return { label: 'Baru Masuk', class: 'bg-blue-50 text-blue-700 border-blue-200' };
    case 'contacted':
      return { label: 'Sudah Dihubungi', class: 'bg-purple-50 text-purple-700 border-purple-200' };
    case 'qualified':
      return { label: 'Qualified', class: 'bg-teal-50 text-teal-700 border-teal-200' };
    case 'proposal':
      return { label: 'Kirim Proposal', class: 'bg-amber-50 text-amber-700 border-amber-200' };
    case 'won':
      return { label: 'Closing (Won)', class: 'bg-emerald-50 text-emerald-700 border-emerald-200' };
    case 'lost':
      return { label: 'Lost / Batal', class: 'bg-rose-50 text-rose-700 border-rose-200' };
    default:
      return { label: status, class: 'bg-slate-50 text-slate-700 border-slate-200' };
  }
};
</script>

<template>
  <Head title="Lead Management & Inbox Prospek — Synbarra Technologies" />

  <AdminLayout>
    <div class="space-y-6 sm:space-y-8">
      <!-- Header Banner -->
      <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-6 sm:p-8 rounded-2xl border border-slate-200/80 shadow-xs">
        <div>
          <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold bg-[#00F1DE]/10 text-[#01232B] border border-[#00F1DE]/30">
            <span class="w-2 h-2 rounded-full bg-[#00F1DE] animate-pulse"></span>
            LEAD MANAGEMENT SYSTEM
          </div>
          <h2 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-[#01232B] mt-2">
            Inbox Prospek & Pipeline Klien
          </h2>
          <p class="text-xs sm:text-sm text-slate-500 mt-1 max-w-2xl">
            Semua inquiry dari website dan prospek direct outreach tersimpan rapi untuk ditindaklanjuti tim analis & programmer.
          </p>
        </div>
        <div>
          <button
            @click="openCreateModal"
            class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-[#01232B] hover:bg-[#011E26] text-white font-medium text-sm transition-all duration-200 shadow-sm"
          >
            <Plus class="w-4 h-4 text-[#00F1DE]" />
            Tambah Prospek Manual
          </button>
        </div>
      </div>

      <!-- Status Tabs -->
      <div class="flex flex-wrap items-center gap-2 border-b border-slate-200 pb-3">
        <button
          v-for="tab in [
            { id: 'all', label: 'Semua', count: statusCounts.all || 0 },
            { id: 'new', label: 'Baru Masuk', count: statusCounts.new || 0 },
            { id: 'contacted', label: 'Dihubungi', count: statusCounts.contacted || 0 },
            { id: 'qualified', label: 'Qualified', count: statusCounts.qualified || 0 },
            { id: 'proposal', label: 'Proposal', count: statusCounts.proposal || 0 },
            { id: 'won', label: 'Won', count: statusCounts.won || 0 },
            { id: 'lost', label: 'Lost', count: statusCounts.lost || 0 },
          ]"
          :key="tab.id"
          @click="filterByStatus(tab.id)"
          :class="[
            selectedStatus === tab.id
              ? 'bg-[#01232B] text-white font-bold shadow-xs'
              : 'bg-white text-slate-600 hover:bg-slate-100 font-medium border border-slate-200/80',
            'px-3.5 py-1.5 rounded-xl text-xs transition-colors flex items-center gap-2'
          ]"
        >
          <span>{{ tab.label }}</span>
          <span 
            :class="[
              selectedStatus === tab.id ? 'bg-[#00F1DE] text-[#01232B]' : 'bg-slate-100 text-slate-600',
              'px-1.5 py-0.5 rounded-full text-[10px] font-bold'
            ]"
          >
            {{ tab.count }}
          </span>
        </button>
      </div>

      <!-- Search & Filter Bar -->
      <div class="bg-white p-4 rounded-xl border border-slate-200/80 shadow-xs flex items-center gap-3">
        <div class="relative flex-1">
          <Search class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-slate-400" />
          <input
            v-model="searchQuery"
            @keyup.enter="applyFilters"
            placeholder="Cari nama klien, perusahaan, email, no WA, atau jenis layanan..."
            class="w-full text-xs pl-9 pr-4 py-2 rounded-xl border-slate-200 focus:border-[#00F1DE] focus:ring-[#00F1DE]"
          />
        </div>
        <button
          @click="applyFilters"
          class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-xl"
        >
          Cari
        </button>
      </div>

      <!-- Leads Table -->
      <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
          <table class="w-full text-left text-xs">
            <thead class="bg-slate-50 border-b border-slate-200/80 text-slate-500 font-bold uppercase tracking-wider">
              <tr>
                <th class="px-6 py-4">Calon Klien</th>
                <th class="px-6 py-4">Kontak & WA</th>
                <th class="px-6 py-4">Layanan & Kebutuhan</th>
                <th class="px-6 py-4">Status</th>
                <th class="px-6 py-4">Follow-up</th>
                <th class="px-6 py-4 text-right">Aksi</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
              <tr 
                v-for="lead in leads.data" 
                :key="lead.id"
                class="hover:bg-slate-50/70 transition-colors"
              >
                <!-- Client Info -->
                <td class="px-6 py-4">
                  <div class="font-bold text-slate-900 text-sm">{{ lead.full_name }}</div>
                  <div v-if="lead.company_name" class="text-slate-500 flex items-center gap-1 mt-0.5">
                    <Building2 class="w-3 h-3 text-slate-400" /> {{ lead.company_name }}
                  </div>
                  <div class="text-[10px] text-slate-400 mt-1">
                    Sumber: <span class="capitalize">{{ lead.source.replace('_', ' ') }}</span>
                  </div>
                </td>

                <!-- Contact & Direct WA -->
                <td class="px-6 py-4 space-y-1">
                  <a 
                    :href="formatWaLink(lead.whatsapp_number, lead.full_name)"
                    target="_blank"
                    class="inline-flex items-center gap-1 text-emerald-600 hover:text-emerald-700 font-bold bg-emerald-50 hover:bg-emerald-100 px-2.5 py-1 rounded-lg border border-emerald-200 transition-colors"
                  >
                    <MessageCircle class="w-3.5 h-3.5" />
                    {{ lead.whatsapp_number }}
                  </a>
                  <div v-if="lead.email" class="text-slate-500 text-[11px] flex items-center gap-1">
                    <Mail class="w-3 h-3 text-slate-400" /> {{ lead.email }}
                  </div>
                </td>

                <!-- Service & Description -->
                <td class="px-6 py-4 max-w-xs">
                  <div class="font-semibold text-slate-800">{{ lead.service_required }}</div>
                  <p class="text-slate-500 text-[11px] truncate mt-0.5" :title="lead.project_description">
                    {{ lead.project_description }}
                  </p>
                  <div v-if="lead.estimated_budget" class="text-[11px] text-amber-700 mt-1 font-medium">
                    Budget: {{ lead.estimated_budget }}
                  </div>
                </td>

                <!-- Status Badge -->
                <td class="px-6 py-4">
                  <span 
                    :class="[
                      getStatusBadge(lead.status).class,
                      'px-2.5 py-1 rounded-full text-[11px] font-bold border inline-block'
                    ]"
                  >
                    {{ getStatusBadge(lead.status).label }}
                  </span>
                </td>

                <!-- Follow up date / Notes -->
                <td class="px-6 py-4 text-[11px] text-slate-500">
                  <div v-if="lead.follow_up_date" class="font-medium text-slate-700 flex items-center gap-1">
                    <Calendar class="w-3 h-3 text-slate-400" /> {{ lead.follow_up_date }}
                  </div>
                  <div v-else class="text-slate-400">-</div>
                  <div v-if="lead.notes" class="text-[10px] text-slate-400 truncate max-w-[150px] mt-0.5">
                    {{ lead.notes }}
                  </div>
                </td>

                <!-- Actions -->
                <td class="px-6 py-4 text-right space-x-1">
                  <button
                    @click="openEditModal(lead)"
                    class="p-1.5 text-slate-400 hover:text-slate-700 rounded-lg hover:bg-slate-100"
                    title="Update Status / Catatan"
                  >
                    <Edit class="w-4 h-4" />
                  </button>
                  <button
                    @click="deleteLead(lead)"
                    class="p-1.5 text-slate-400 hover:text-red-600 rounded-lg hover:bg-red-50"
                    title="Hapus Prospek"
                  >
                    <Trash2 class="w-4 h-4" />
                  </button>
                </td>
              </tr>

              <tr v-if="!leads.data || leads.data.length === 0">
                <td colspan="6" class="px-6 py-12 text-center text-slate-400">
                  Belum ada data prospek pada filter ini.
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- Modal Form (Update / Tambah Lead) -->
    <div v-if="isModalOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-xs">
      <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-xl border border-slate-200 max-h-[90vh] overflow-y-auto">
        <h3 class="text-lg font-bold text-[#01232B]">
          {{ isEditing ? 'Update Prospek Klien' : 'Tambah Prospek Manual' }}
        </h3>

        <form @submit.prevent="submitForm" class="space-y-4 mt-4">
          <div class="grid grid-cols-2 gap-3">
            <div>
              <label class="block text-xs font-semibold text-slate-700 mb-1">Nama Lengkap</label>
              <input
                v-model="form.full_name"
                required
                class="w-full text-xs rounded-xl border-slate-200 focus:border-[#00F1DE] focus:ring-[#00F1DE]"
              />
            </div>
            <div>
              <label class="block text-xs font-semibold text-slate-700 mb-1">Nama Perusahaan / Usaha</label>
              <input
                v-model="form.company_name"
                class="w-full text-xs rounded-xl border-slate-200 focus:border-[#00F1DE] focus:ring-[#00F1DE]"
              />
            </div>
          </div>

          <div class="grid grid-cols-2 gap-3">
            <div>
              <label class="block text-xs font-semibold text-slate-700 mb-1">Nomor WhatsApp</label>
              <input
                v-model="form.whatsapp_number"
                placeholder="08xxxxxxxx"
                required
                class="w-full text-xs rounded-xl border-slate-200 focus:border-[#00F1DE] focus:ring-[#00F1DE]"
              />
            </div>
            <div>
              <label class="block text-xs font-semibold text-slate-700 mb-1">Email</label>
              <input
                v-model="form.email"
                type="email"
                class="w-full text-xs rounded-xl border-slate-200 focus:border-[#00F1DE] focus:ring-[#00F1DE]"
              />
            </div>
          </div>

          <div class="grid grid-cols-2 gap-3">
            <div>
              <label class="block text-xs font-semibold text-slate-700 mb-1">Status Prospek</label>
              <select
                v-model="form.status"
                class="w-full text-xs rounded-xl border-slate-200 focus:border-[#00F1DE] focus:ring-[#00F1DE]"
              >
                <option value="new">Baru Masuk (New)</option>
                <option value="contacted">Sudah Dihubungi (Contacted)</option>
                <option value="qualified">Qualified (Tertarik)</option>
                <option value="proposal">Kirim Proposal</option>
                <option value="won">Won (Deal Closing)</option>
                <option value="lost">Lost (Batal/Tidak Sesuai)</option>
              </select>
            </div>
            <div>
              <label class="block text-xs font-semibold text-slate-700 mb-1">Tanggal Follow-up</label>
              <input
                v-model="form.follow_up_date"
                type="date"
                class="w-full text-xs rounded-xl border-slate-200 focus:border-[#00F1DE] focus:ring-[#00F1DE]"
              />
            </div>
          </div>

          <div>
            <label class="block text-xs font-semibold text-slate-700 mb-1">Layanan yang Diminati</label>
            <input
              v-model="form.service_required"
              required
              class="w-full text-xs rounded-xl border-slate-200 focus:border-[#00F1DE] focus:ring-[#00F1DE]"
            />
          </div>

          <div>
            <label class="block text-xs font-semibold text-slate-700 mb-1">Deskripsi Kebutuhan</label>
            <textarea
              v-model="form.project_description"
              rows="2"
              class="w-full text-xs rounded-xl border-slate-200 focus:border-[#00F1DE] focus:ring-[#00F1DE]"
            ></textarea>
          </div>

          <div>
            <label class="block text-xs font-semibold text-slate-700 mb-1">Catatan Tindak Lanjut (Notes)</label>
            <textarea
              v-model="form.notes"
              rows="2"
              placeholder="Catatan hasil meeting, budget yang disepakati, kendala..."
              class="w-full text-xs rounded-xl border-slate-200 focus:border-[#00F1DE] focus:ring-[#00F1DE]"
            ></textarea>
          </div>

          <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-100">
            <button
              type="button"
              @click="isModalOpen = false"
              class="px-4 py-2 text-xs font-medium text-slate-600 hover:bg-slate-100 rounded-xl"
            >
              Batal
            </button>
            <button
              type="submit"
              :disabled="form.processing"
              class="px-4 py-2 text-xs font-semibold text-white bg-[#01232B] hover:bg-[#011E26] rounded-xl shadow-xs"
            >
              {{ isEditing ? 'Simpan Perubahan' : 'Tambah Prospek' }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </AdminLayout>
</template>
