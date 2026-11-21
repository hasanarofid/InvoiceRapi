<script setup lang="ts">
import { ref } from 'vue';
import { Head, router, useForm } from '@inertiajs/vue3';
import WorkspaceLayout from '@/Layouts/WorkspaceLayout.vue';
import RupiahInput from '@/Components/RupiahInput.vue';
import { swalConfirm, swalError } from '@/utils/swal';
import { formatDateID } from '@/utils/format';
import { FolderKanban, Plus, Search, Pencil, Trash2, Calendar } from 'lucide-vue-next';

interface ClientOption {
  id: number;
  name: string;
  company_name?: string | null;
}

interface Project {
  id: number;
  client_id: number;
  name: string;
  description?: string | null;
  status: string;
  contract_amount: number | string;
  start_date?: string | null;
  end_date?: string | null;
  client?: ClientOption;
  invoices_count?: number;
}

const props = defineProps<{
  projects: {
    data: Project[];
    links: Array<{ url: string | null; label: string; active: boolean }>;
  };
  clients: ClientOption[];
  filters: {
    search?: string;
    status?: string;
    client_id?: string | number;
  };
}>();

const search = ref(props.filters.search || '');
const statusFilter = ref(props.filters.status || '');
const clientFilter = ref(props.filters.client_id || '');
const showModal = ref(false);
const editingProject = ref<Project | null>(null);

const form = useForm({
  client_id: '' as string | number,
  name: '',
  description: '',
  status: 'active',
  contract_amount: 0 as number | string,
  start_date: '',
  end_date: '',
});

const formatIDR = (val: number | string) =>
  new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(Number(val || 0));

const applyFilter = () => {
  router.get(route('projects.index'), {
    search: search.value || undefined,
    status: statusFilter.value || undefined,
    client_id: clientFilter.value || undefined,
  }, { preserveState: true, replace: true });
};

const openCreate = () => {
  editingProject.value = null;
  form.reset();
  form.clearErrors();
  form.status = 'active';
  form.contract_amount = 0;
  if (props.clients.length > 0) {
    form.client_id = props.clients[0].id;
  }
  showModal.value = true;
};

const openEdit = (project: Project) => {
  editingProject.value = project;
  form.clearErrors();
  form.client_id = project.client_id;
  form.name = project.name;
  form.description = project.description || '';
  form.status = project.status;
  form.contract_amount = Number(project.contract_amount || 0);
  form.start_date = project.start_date ? String(project.start_date).slice(0, 10) : '';
  form.end_date = project.end_date ? String(project.end_date).slice(0, 10) : '';
  showModal.value = true;
};

const submit = () => {
  const onError = () => swalError('Gagal Menyimpan', 'Periksa kembali data proyek yang Anda masukkan.');
  if (editingProject.value) {
    form.put(route('projects.update', editingProject.value.id), {
      onSuccess: () => { showModal.value = false; },
      onError,
    });
  } else {
    form.post(route('projects.store'), {
      onSuccess: () => { showModal.value = false; form.reset(); },
      onError,
    });
  }
};

const deleteProject = async (project: Project) => {
  const ok = await swalConfirm('Hapus Proyek?', `Proyek "${project.name}" akan diarsipkan (soft delete).`, 'Ya, Hapus');
  if (!ok) return;
  router.delete(route('projects.destroy', project.id));
};

const statusBadge = (status: string) => {
  if (status === 'completed') return 'bg-emerald-50 text-emerald-700';
  if (status === 'on_hold') return 'bg-amber-50 text-amber-700';
  if (status === 'cancelled') return 'bg-red-50 text-red-700';
  return 'bg-violet-50 text-violet-700';
};
</script>

<template>
  <Head title="Proyek — InvoiceRapi" />

  <WorkspaceLayout title="Proyek">
    <div class="space-y-6">
      <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
          <h1 class="text-2xl font-black text-slate-900 tracking-tight">Daftar Proyek</h1>
          <p class="text-sm text-slate-500 mt-0.5">Kelola proyek, nilai kontrak, dan kaitkan langsung ke invoice klien.</p>
        </div>
        <button
          @click="openCreate"
          :disabled="clients.length === 0"
          class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-violet-600 hover:bg-violet-700 disabled:opacity-50 text-white text-sm font-bold shadow-sm transition"
        >
          <Plus class="w-4 h-4" />
          Tambah Proyek
        </button>
      </div>

      <!-- Filter Bar -->
      <div class="bg-white rounded-2xl border border-slate-100 p-4 flex flex-col sm:flex-row gap-3">
        <div class="relative flex-1">
          <Search class="w-4 h-4 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2" />
          <input
            v-model="search"
            @keyup.enter="applyFilter"
            type="text"
            placeholder="Cari nama proyek..."
            class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-violet-500"
          />
        </div>
        <select v-model="clientFilter" @change="applyFilter" class="px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm bg-white">
          <option value="">Semua Klien</option>
          <option v-for="c in clients" :key="c.id" :value="c.id">{{ c.name }}</option>
        </select>
        <select v-model="statusFilter" @change="applyFilter" class="px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm bg-white">
          <option value="">Semua Status</option>
          <option value="draft">Draft</option>
          <option value="active">Aktif</option>
          <option value="completed">Selesai</option>
          <option value="cancelled">Dibatalkan</option>
        </select>
      </div>

      <!-- List -->
      <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden shadow-sm">
        <div v-if="projects.data.length === 0" class="p-12 text-center">
          <FolderKanban class="w-10 h-10 text-slate-300 mx-auto mb-3" />
          <p class="text-sm font-bold text-slate-700">Belum ada proyek</p>
          <p class="text-xs text-slate-500 mt-1">
            {{ clients.length === 0 ? 'Tambahkan klien terlebih dahulu sebelum membuat proyek.' : 'Buat proyek pertama Anda untuk melacak nilai kontrak.' }}
          </p>
        </div>

        <div v-else class="overflow-x-auto">
          <table class="w-full text-left border-collapse">
            <thead>
              <tr class="border-b border-slate-100 bg-slate-50/70 text-[11px] font-bold uppercase tracking-wider text-slate-500">
                <th class="px-5 py-3.5">Proyek</th>
                <th class="px-5 py-3.5">Klien</th>
                <th class="px-5 py-3.5">Nilai Kontrak</th>
                <th class="px-5 py-3.5">Status</th>
                <th class="px-5 py-3.5 text-right">Aksi</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-sm">
              <tr v-for="project in projects.data" :key="project.id" class="hover:bg-slate-50/60 transition">
                <td class="px-5 py-4">
                  <p class="font-bold text-slate-900">{{ project.name }}</p>
                  <p v-if="project.start_date || project.end_date" class="text-xs text-slate-500 flex items-center gap-1 mt-0.5">
                    <Calendar class="w-3.5 h-3.5" /> {{ formatDateID(project.start_date) }} s/d {{ formatDateID(project.end_date) }}
                  </p>
                </td>
                <td class="px-5 py-4 text-slate-700 font-medium">{{ project.client?.name || '-' }}</td>
                <td class="px-5 py-4 font-bold text-slate-900">{{ formatIDR(project.contract_amount) }}</td>
                <td class="px-5 py-4">
                  <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-bold capitalize" :class="statusBadge(project.status)">
                    {{ project.status }}
                  </span>
                </td>
                <td class="px-5 py-4 text-right space-x-2">
                  <button @click="openEdit(project)" class="p-2 rounded-lg text-slate-500 hover:text-violet-600 hover:bg-violet-50 transition">
                    <Pencil class="w-4 h-4" />
                  </button>
                  <button @click="deleteProject(project)" class="p-2 rounded-lg text-slate-500 hover:text-red-600 hover:bg-red-50 transition">
                    <Trash2 class="w-4 h-4" />
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- Modal -->
    <div v-if="showModal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-sm p-4">
      <div class="bg-white rounded-3xl max-w-lg w-full p-6 shadow-2xl space-y-5">
        <div class="flex items-center justify-between">
          <h2 class="text-lg font-black text-slate-900">{{ editingProject ? 'Edit Proyek' : 'Tambah Proyek Baru' }}</h2>
          <button @click="showModal = false" class="text-slate-400 hover:text-slate-700 text-sm font-bold">Tutup</button>
        </div>

        <form @submit.prevent="submit" class="space-y-4">
          <div>
            <label class="block text-xs font-bold text-slate-700 mb-1">Klien *</label>
            <select v-model="form.client_id" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm bg-white">
              <option v-for="c in clients" :key="c.id" :value="c.id">{{ c.name }}</option>
            </select>
            <p v-if="form.errors.client_id" class="text-xs text-red-600 mt-1">{{ form.errors.client_id }}</p>
          </div>
          <div>
            <label class="block text-xs font-bold text-slate-700 mb-1">Nama Proyek *</label>
            <input v-model="form.name" type="text" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm" />
            <p v-if="form.errors.name" class="text-xs text-red-600 mt-1">{{ form.errors.name }}</p>
          </div>
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            <div>
              <label class="block text-xs font-bold text-slate-700 mb-1">Nilai Kontrak (Rp) *</label>
              <RupiahInput v-model="form.contract_amount" required />
              <p v-if="form.errors.contract_amount" class="text-xs text-red-600 mt-1">{{ form.errors.contract_amount }}</p>
            </div>
            <div>
              <label class="block text-xs font-bold text-slate-700 mb-1">Status</label>
              <select v-model="form.status" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm bg-white">
                <option value="draft">Draft</option>
                <option value="active">Aktif</option>
                <option value="completed">Selesai</option>
                <option value="cancelled">Dibatalkan</option>
              </select>
              <p v-if="form.errors.status" class="text-xs text-red-600 mt-1">{{ form.errors.status }}</p>
            </div>
          </div>
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            <div>
              <label class="block text-xs font-bold text-slate-700 mb-1">Tanggal Mulai</label>
              <input v-model="form.start_date" type="date" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm" />
              <p v-if="form.errors.start_date" class="text-xs text-red-600 mt-1">{{ form.errors.start_date }}</p>
            </div>
            <div>
              <label class="block text-xs font-bold text-slate-700 mb-1">Tanggal Selesai</label>
              <input v-model="form.end_date" type="date" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm" />
              <p v-if="form.errors.end_date" class="text-xs text-red-600 mt-1">{{ form.errors.end_date }}</p>
            </div>
          </div>
          <div>
            <label class="block text-xs font-bold text-slate-700 mb-1">Deskripsi</label>
            <textarea v-model="form.description" rows="2" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm"></textarea>
          </div>

          <div class="flex justify-end gap-2 pt-2">
            <button type="button" @click="showModal = false" class="px-4 py-2.5 rounded-xl border border-slate-200 text-sm font-bold text-slate-600">Batal</button>
            <button type="submit" :disabled="form.processing" class="px-5 py-2.5 rounded-xl bg-violet-600 hover:bg-violet-700 text-white text-sm font-bold">
              {{ form.processing ? 'Menyimpan...' : 'Simpan Proyek' }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </WorkspaceLayout>
</template>

