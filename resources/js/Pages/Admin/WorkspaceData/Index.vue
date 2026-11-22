<script setup lang="ts">
import { ref } from 'vue';
import { Head, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { formatIDR, formatDateID } from '@/utils/format';
import { FileText, FolderKanban, Users, Search, ExternalLink } from 'lucide-vue-next';

const props = defineProps<{
  tab: string;
  filters: { search?: string; tenant_id?: string | number };
  tenants: Array<{ id: number; name: string }>;
  counts: { invoices: number; projects: number; clients: number };
  invoices: { data: any[] };
  projects: { data: any[] };
  clients: { data: any[] };
}>();

const activeTab = ref(props.tab || 'invoices');
const search = ref(props.filters.search || '');
const tenantId = ref(props.filters.tenant_id || '');

const applyFilter = (newTab?: string) => {
  if (newTab) activeTab.value = newTab;
  router.get(route('admin.workspace-data.index'), {
    tab: activeTab.value,
    search: search.value || undefined,
    tenant_id: tenantId.value || undefined,
  }, { preserveState: true, replace: true });
};
</script>

<template>
  <Head title="Data Klien, Proyek & Invoice — Admin InvoiceRapi" />

  <AdminLayout>
    <div class="space-y-6">
      <div>
        <h1 class="text-2xl font-black text-slate-900 tracking-tight">Monitoring Data Workspace</h1>
        <p class="text-sm text-slate-500 mt-0.5">Lihat seluruh data Invoice, Proyek, dan Klien dari semua bisnis/member terdaftar.</p>
      </div>

      <!-- Tabs -->
      <div class="flex flex-wrap gap-2">
        <button
          @click="applyFilter('invoices')"
          class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs font-bold transition border"
          :class="activeTab === 'invoices' ? 'bg-violet-600 text-white border-violet-600' : 'bg-white text-slate-700 border-slate-200 hover:bg-slate-50'"
        >
          <FileText class="w-4 h-4" />
          Invoice ({{ counts.invoices }})
        </button>
        <button
          @click="applyFilter('projects')"
          class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs font-bold transition border"
          :class="activeTab === 'projects' ? 'bg-violet-600 text-white border-violet-600' : 'bg-white text-slate-700 border-slate-200 hover:bg-slate-50'"
        >
          <FolderKanban class="w-4 h-4" />
          Proyek ({{ counts.projects }})
        </button>
        <button
          @click="applyFilter('clients')"
          class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs font-bold transition border"
          :class="activeTab === 'clients' ? 'bg-violet-600 text-white border-violet-600' : 'bg-white text-slate-700 border-slate-200 hover:bg-slate-50'"
        >
          <Users class="w-4 h-4" />
          Klien ({{ counts.clients }})
        </button>
      </div>

      <!-- Filter Bar -->
      <div class="bg-white rounded-2xl border border-slate-100 p-4 flex flex-col sm:flex-row gap-3">
        <div class="relative flex-1">
          <Search class="w-4 h-4 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2" />
          <input
            v-model="search"
            @keyup.enter="applyFilter()"
            type="text"
            placeholder="Cari nomor invoice, nama klien, proyek, atau bisnis..."
            class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-200 text-sm"
          />
        </div>
        <select v-model="tenantId" @change="applyFilter()" class="px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm bg-white">
          <option value="">Semua Bisnis / Member</option>
          <option v-for="t in tenants" :key="t.id" :value="t.id">{{ t.name }}</option>
        </select>
        <button @click="applyFilter()" class="px-4 py-2.5 rounded-xl bg-slate-900 text-white text-sm font-bold">
          Filter
        </button>
      </div>

      <!-- Tab: Invoices -->
      <div v-if="activeTab === 'invoices'" class="bg-white rounded-2xl border border-slate-100 overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
          <table class="w-full text-left border-collapse text-sm">
            <thead>
              <tr class="border-b border-slate-100 bg-slate-50 text-[11px] font-bold uppercase text-slate-500">
                <th class="px-5 py-3.5">No. Invoice</th>
                <th class="px-5 py-3.5">Bisnis / Member</th>
                <th class="px-5 py-3.5">Klien</th>
                <th class="px-5 py-3.5">Jatuh Tempo</th>
                <th class="px-5 py-3.5">Total</th>
                <th class="px-5 py-3.5">Status</th>
                <th class="px-5 py-3.5 text-right">Preview</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
              <tr v-for="inv in invoices.data" :key="inv.id" class="hover:bg-slate-50/60">
                <td class="px-5 py-3.5 font-bold text-violet-700">{{ inv.invoice_number }}</td>
                <td class="px-5 py-3.5">
                  <p class="font-semibold text-slate-800">{{ inv.tenant?.name || '-' }}</p>
                  <span v-if="inv.tenant?.users?.[0]?.kode_user" class="inline-block mt-0.5 px-2 py-0.5 rounded-md bg-violet-50 text-violet-700 border border-violet-200/70 font-mono text-[11px] font-bold">
                    {{ inv.tenant.users[0].kode_user }}
                  </span>
                </td>
                <td class="px-5 py-3.5 text-slate-700">{{ inv.client?.name || '-' }}</td>
                <td class="px-5 py-3.5 text-xs text-slate-600">{{ formatDateID(inv.due_date) }}</td>
                <td class="px-5 py-3.5 font-bold text-slate-900">{{ formatIDR(inv.total) }}</td>
                <td class="px-5 py-3.5">
                  <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-700 capitalize">{{ inv.status }}</span>
                </td>
                <td class="px-5 py-3.5 text-right">
                  <a :href="`/i/${inv.public_token}`" target="_blank" class="inline-flex items-center gap-1 text-xs font-bold text-violet-700 hover:underline">
                    <ExternalLink class="w-3.5 h-3.5" /> Lihat
                  </a>
                </td>
              </tr>
              <tr v-if="invoices.data.length === 0">
                <td colspan="7" class="px-5 py-10 text-center text-slate-500 text-xs">Tidak ada data invoice.</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Tab: Projects -->
      <div v-if="activeTab === 'projects'" class="bg-white rounded-2xl border border-slate-100 overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
          <table class="w-full text-left border-collapse text-sm">
            <thead>
              <tr class="border-b border-slate-100 bg-slate-50 text-[11px] font-bold uppercase text-slate-500">
                <th class="px-5 py-3.5">Nama Proyek</th>
                <th class="px-5 py-3.5">Bisnis / Member</th>
                <th class="px-5 py-3.5">Klien</th>
                <th class="px-5 py-3.5">Nilai Kontrak</th>
                <th class="px-5 py-3.5">Status</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
              <tr v-for="proj in projects.data" :key="proj.id" class="hover:bg-slate-50/60">
                <td class="px-5 py-3.5 font-bold text-slate-900">{{ proj.name }}</td>
                <td class="px-5 py-3.5">
                  <p class="font-semibold text-slate-800">{{ proj.tenant?.name || '-' }}</p>
                  <span v-if="proj.tenant?.users?.[0]?.kode_user" class="inline-block mt-0.5 px-2 py-0.5 rounded-md bg-violet-50 text-violet-700 border border-violet-200/70 font-mono text-[11px] font-bold">
                    {{ proj.tenant.users[0].kode_user }}
                  </span>
                </td>
                <td class="px-5 py-3.5 text-slate-700">{{ proj.client?.name || '-' }}</td>
                <td class="px-5 py-3.5 font-bold text-slate-900">{{ formatIDR(proj.contract_amount) }}</td>
                <td class="px-5 py-3.5">
                  <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-violet-50 text-violet-700 capitalize">{{ proj.status }}</span>
                </td>
              </tr>
              <tr v-if="projects.data.length === 0">
                <td colspan="5" class="px-5 py-10 text-center text-slate-500 text-xs">Tidak ada data proyek.</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Tab: Clients -->
      <div v-if="activeTab === 'clients'" class="bg-white rounded-2xl border border-slate-100 overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
          <table class="w-full text-left border-collapse text-sm">
            <thead>
              <tr class="border-b border-slate-100 bg-slate-50 text-[11px] font-bold uppercase text-slate-500">
                <th class="px-5 py-3.5">Nama Klien</th>
                <th class="px-5 py-3.5">Bisnis / Member</th>
                <th class="px-5 py-3.5">Kontak</th>
                <th class="px-5 py-3.5">Jumlah Invoice</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
              <tr v-for="cli in clients.data" :key="cli.id" class="hover:bg-slate-50/60">
                <td class="px-5 py-3.5">
                  <p class="font-bold text-slate-900">{{ cli.name }}</p>
                  <p v-if="cli.company_name" class="text-xs text-slate-500">{{ cli.company_name }}</p>
                </td>
                <td class="px-5 py-3.5">
                  <p class="font-semibold text-slate-800">{{ cli.tenant?.name || '-' }}</p>
                  <span v-if="cli.tenant?.users?.[0]?.kode_user" class="inline-block mt-0.5 px-2 py-0.5 rounded-md bg-violet-50 text-violet-700 border border-violet-200/70 font-mono text-[11px] font-bold">
                    {{ cli.tenant.users[0].kode_user }}
                  </span>
                </td>
                <td class="px-5 py-3.5 text-xs text-slate-600">
                  <p v-if="cli.phone">{{ cli.phone }}</p>
                  <p v-if="cli.email">{{ cli.email }}</p>
                </td>
                <td class="px-5 py-3.5 font-bold text-slate-900">{{ cli.invoices_count ?? 0 }}</td>
              </tr>
              <tr v-if="clients.data.length === 0">
                <td colspan="4" class="px-5 py-10 text-center text-slate-500 text-xs">Tidak ada data klien.</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

