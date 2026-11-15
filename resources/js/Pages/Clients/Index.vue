<script setup lang="ts">
import { ref, computed } from 'vue';
import { Head, Link, router, useForm, usePage } from '@inertiajs/vue3';
import WorkspaceLayout from '@/Layouts/WorkspaceLayout.vue';
import { swalConfirm, swalError } from '@/utils/swal';
import { Users, Plus, Search, Pencil, Trash2, Building2, Mail, Phone, Lock, Crown } from 'lucide-vue-next';

interface Client {
  id: number;
  name: string;
  company_name?: string | null;
  email?: string | null;
  phone?: string | null;
  address?: string | null;
  notes?: string | null;
  status: string;
  invoices_count?: number;
}

const props = defineProps<{
  clients: {
    data: Client[];
    links: Array<{ url: string | null; label: string; active: boolean }>;
    total: number;
  };
  filters: {
    search?: string;
    status?: string;
  };
}>();

const page = usePage();
const planUsage = computed(() => (page.props.planUsage as Record<string, any> | null) ?? null);
const clientLimitReached = computed(() => Boolean(planUsage.value?.client_limit_reached));

const search = ref(props.filters.search || '');
const statusFilter = ref(props.filters.status || '');
const showModal = ref(false);
const editingClient = ref<Client | null>(null);

const form = useForm({
  name: '',
  company_name: '',
  email: '',
  phone: '',
  address: '',
  notes: '',
  status: 'active',
});

const applyFilter = () => {
  router.get(route('clients.index'), {
    search: search.value || undefined,
    status: statusFilter.value || undefined,
  }, { preserveState: true, replace: true });
};

const openCreate = () => {
  if (clientLimitReached.value) return;
  editingClient.value = null;
  form.reset();
  form.status = 'active';
  showModal.value = true;
};

const openEdit = (client: Client) => {
  editingClient.value = client;
  form.name = client.name;
  form.company_name = client.company_name || '';
  form.email = client.email || '';
  form.phone = client.phone || '';
  form.address = client.address || '';
  form.notes = client.notes || '';
  form.status = client.status || 'active';
  showModal.value = true;
};

const submit = () => {
  const onError = () => swalError('Gagal Menyimpan', 'Periksa kembali data klien yang Anda masukkan.');
  if (editingClient.value) {
    form.put(route('clients.update', editingClient.value.id), {
      onSuccess: () => { showModal.value = false; },
      onError,
    });
  } else {
    form.post(route('clients.store'), {
      onSuccess: () => { showModal.value = false; form.reset(); },
      onError,
    });
  }
};

const deleteClient = async (client: Client) => {
  const ok = await swalConfirm('Hapus Klien?', `Data klien "${client.name}" akan diarsipkan (soft delete).`, 'Ya, Hapus');
  if (!ok) return;
  router.delete(route('clients.destroy', client.id));
};
</script>

<template>
  <Head title="Klien — InvoiceRapi" />

  <WorkspaceLayout title="Klien">
    <div class="space-y-6">
      <!-- Header -->
      <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
          <h1 class="text-2xl font-black text-slate-900 tracking-tight">Daftar Klien</h1>
          <p class="text-sm text-slate-500 mt-0.5">
            Kelola data klien &amp; kontak penagihan bisnis Anda
            <span v-if="planUsage?.max_clients" class="ml-1 font-semibold text-violet-700">
              ({{ planUsage.clients_count }}/{{ planUsage.max_clients }} kuota terpakai)
            </span>
          </p>
        </div>

        <button
          v-if="!clientLimitReached"
          @click="openCreate"
          class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-violet-600 hover:bg-violet-700 text-white text-sm font-bold shadow-sm transition"
        >
          <Plus class="w-4 h-4" />
          Tambah Klien
        </button>
        <Link
          v-else
          :href="route('billing.index')"
          class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-amber-500 hover:bg-amber-600 text-white text-sm font-bold shadow-sm transition"
        >
          <Crown class="w-4 h-4" />
          Kuota Klien Penuh — Upgrade Paket
        </Link>
      </div>

      <!-- Limit Alert -->
      <div v-if="clientLimitReached" class="p-4 rounded-2xl bg-amber-50 border border-amber-200 flex items-center justify-between gap-4">
        <div class="flex items-center gap-3">
          <Lock class="w-5 h-5 text-amber-600 shrink-0" />
          <p class="text-sm text-amber-900">
            Batas maksimal <strong>{{ planUsage?.max_clients }} klien</strong> pada paket {{ planUsage?.plan_name }} telah tercapai. Upgrade ke <strong>Pro</strong> untuk klien tanpa batas.
          </p>
        </div>
        <Link :href="route('billing.index')" class="text-xs font-bold text-amber-800 underline shrink-0">Upgrade Sekarang</Link>
      </div>

      <!-- Filter Bar -->
      <div class="bg-white rounded-2xl border border-slate-100 p-4 flex flex-col sm:flex-row gap-3">
        <div class="relative flex-1">
          <Search class="w-4 h-4 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2" />
          <input
            v-model="search"
            @keyup.enter="applyFilter"
            type="text"
            placeholder="Cari nama klien, perusahaan, atau email..."
            class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-violet-500"
          />
        </div>
        <select
          v-model="statusFilter"
          @change="applyFilter"
          class="px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-violet-500"
        >
          <option value="">Semua Status</option>
          <option value="active">Aktif</option>
          <option value="inactive">Nonaktif</option>
        </select>
        <button @click="applyFilter" class="px-4 py-2.5 rounded-xl bg-slate-900 text-white text-sm font-bold hover:bg-slate-800 transition">
          Cari
        </button>
      </div>

      <!-- Table -->
      <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden shadow-sm">
        <div v-if="clients.data.length === 0" class="p-12 text-center">
          <Users class="w-10 h-10 text-slate-300 mx-auto mb-3" />
          <p class="text-sm font-bold text-slate-700">Belum ada klien terdaftar</p>
          <p class="text-xs text-slate-500 mt-1">Tambahkan klien pertama Anda untuk mulai membuat invoice.</p>
        </div>

        <div v-else class="overflow-x-auto">
          <table class="w-full text-left border-collapse">
            <thead>
              <tr class="border-b border-slate-100 bg-slate-50/70 text-[11px] font-bold uppercase tracking-wider text-slate-500">
                <th class="px-5 py-3.5">Klien</th>
                <th class="px-5 py-3.5">Kontak</th>
                <th class="px-5 py-3.5">Invoice</th>
                <th class="px-5 py-3.5">Status</th>
                <th class="px-5 py-3.5 text-right">Aksi</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-sm">
              <tr v-for="client in clients.data" :key="client.id" class="hover:bg-slate-50/60 transition">
                <td class="px-5 py-4">
                  <p class="font-bold text-slate-900">{{ client.name }}</p>
                  <p v-if="client.company_name" class="text-xs text-slate-500 flex items-center gap-1 mt-0.5">
                    <Building2 class="w-3.5 h-3.5" /> {{ client.company_name }}
                  </p>
                </td>
                <td class="px-5 py-4 space-y-0.5 text-xs text-slate-600">
                  <p v-if="client.email" class="flex items-center gap-1.5"><Mail class="w-3.5 h-3.5 text-slate-400" /> {{ client.email }}</p>
                  <p v-if="client.phone" class="flex items-center gap-1.5"><Phone class="w-3.5 h-3.5 text-slate-400" /> {{ client.phone }}</p>
                  <span v-if="!client.email && !client.phone" class="text-slate-400">-</span>
                </td>
                <td class="px-5 py-4 font-semibold text-slate-700">
                  {{ client.invoices_count ?? 0 }} invoice
                </td>
                <td class="px-5 py-4">
                  <span
                    class="inline-flex px-2.5 py-1 rounded-full text-xs font-bold"
                    :class="client.status === 'active' ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-600'"
                  >
                    {{ client.status === 'active' ? 'Aktif' : 'Nonaktif' }}
                  </span>
                </td>
                <td class="px-5 py-4 text-right space-x-2">
                  <button @click="openEdit(client)" class="p-2 rounded-lg text-slate-500 hover:text-violet-600 hover:bg-violet-50 transition" title="Edit">
                    <Pencil class="w-4 h-4" />
                  </button>
                  <button @click="deleteClient(client)" class="p-2 rounded-lg text-slate-500 hover:text-red-600 hover:bg-red-50 transition" title="Hapus">
                    <Trash2 class="w-4 h-4" />
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- Modal Create / Edit -->
    <div v-if="showModal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-sm p-4">
      <div class="bg-white rounded-3xl max-w-lg w-full p-6 shadow-2xl space-y-5">
        <div class="flex items-center justify-between">
          <h2 class="text-lg font-black text-slate-900">{{ editingClient ? 'Edit Klien' : 'Tambah Klien Baru' }}</h2>
          <button @click="showModal = false" class="text-slate-400 hover:text-slate-700 text-sm font-bold">Tutup</button>
        </div>

        <form @submit.prevent="submit" class="space-y-4">
          <div>
            <label class="block text-xs font-bold text-slate-700 mb-1">Nama Klien *</label>
            <input v-model="form.name" type="text" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm" />
            <p v-if="form.errors.name" class="text-xs text-red-600 mt-1">{{ form.errors.name }}</p>
          </div>
          <div>
            <label class="block text-xs font-bold text-slate-700 mb-1">Nama Perusahaan / Instansi</label>
            <input v-model="form.company_name" type="text" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm" />
          </div>
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            <div>
              <label class="block text-xs font-bold text-slate-700 mb-1">Email</label>
              <input v-model="form.email" type="email" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm" />
            </div>
            <div>
              <label class="block text-xs font-bold text-slate-700 mb-1">No. WhatsApp / Telepon</label>
              <input v-model="form.phone" type="text" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm" />
            </div>
          </div>
          <div>
            <label class="block text-xs font-bold text-slate-700 mb-1">Alamat</label>
            <textarea v-model="form.address" rows="2" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm"></textarea>
          </div>
          <div v-if="editingClient">
            <label class="block text-xs font-bold text-slate-700 mb-1">Status</label>
            <select v-model="form.status" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm bg-white">
              <option value="active">Aktif</option>
              <option value="inactive">Nonaktif</option>
            </select>
          </div>

          <div class="flex justify-end gap-2 pt-2">
            <button type="button" @click="showModal = false" class="px-4 py-2.5 rounded-xl border border-slate-200 text-sm font-bold text-slate-600">Batal</button>
            <button type="submit" :disabled="form.processing" class="px-5 py-2.5 rounded-xl bg-violet-600 hover:bg-violet-700 text-white text-sm font-bold">
              {{ form.processing ? 'Menyimpan...' : 'Simpan Klien' }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </WorkspaceLayout>
</template>

