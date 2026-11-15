<script setup lang="ts">
import { computed, watch } from 'vue';
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import WorkspaceLayout from '@/Layouts/WorkspaceLayout.vue';
import RupiahInput from '@/Components/RupiahInput.vue';
import { swalError } from '@/utils/swal';
import { Plus, Trash2, ArrowLeft, Lock, Crown } from 'lucide-vue-next';

interface ClientOption {
  id: number;
  name: string;
  company_name?: string | null;
}

interface ProjectOption {
  id: number;
  client_id: number;
  name: string;
}

const props = defineProps<{
  clients: ClientOption[];
  projects: ProjectOption[];
  suggestedNumber: string;
}>();

const page = usePage();
const planUsage = computed(() => (page.props.planUsage as Record<string, any> | null) ?? null);
const canUseDpTermin = computed(() => Boolean(planUsage.value?.features?.dp_termin ?? true));

const today = new Date().toISOString().slice(0, 10);
const nextWeek = new Date(Date.now() + 7 * 86400000).toISOString().slice(0, 10);

const form = useForm({
  client_id: props.clients[0]?.id ?? ('' as string | number),
  project_id: '' as string | number,
  invoice_number: props.suggestedNumber,
  invoice_date: today,
  due_date: nextWeek,
  description: '',
  notes: '',
  payment_instructions: 'Transfer ke rekening resmi perusahaan kami.',
  discount: 0,
  tax: 0,
  items: [
    { name: '', description: '', quantity: 1, unit_price: 0, discount: 0 },
  ],
  installments: [
    { name: 'Pembayaran Penuh', percentage: 100, amount: 0, due_date: nextWeek },
  ],
});

const filteredProjects = computed(() =>
  props.projects.filter((p) => String(p.client_id) === String(form.client_id))
);

const subtotal = computed(() =>
  form.items.reduce((sum, item) => sum + Math.max(0, Number(item.quantity || 0) * Number(item.unit_price || 0) - Number(item.discount || 0)), 0)
);

const grandTotal = computed(() =>
  Math.max(0, subtotal.value - Number(form.discount || 0) + Number(form.tax || 0))
);

// Keep single installment synced automatically when only 1 termin exists
watch(grandTotal, (val) => {
  if (form.installments.length === 1) {
    form.installments[0].amount = val;
    form.installments[0].percentage = 100;
  }
}, { immediate: true });

const formatIDR = (val: number) =>
  new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(val || 0);

const addItem = () => {
  form.items.push({ name: '', description: '', quantity: 1, unit_price: 0, discount: 0 });
};

const removeItem = (idx: number) => {
  if (form.items.length > 1) form.items.splice(idx, 1);
};

const addInstallment = () => {
  if (!canUseDpTermin.value) return;
  form.installments.push({
    name: `Termin ${form.installments.length + 1}`,
    percentage: 0,
    amount: 0,
    due_date: form.due_date,
  });
};

const removeInstallment = (idx: number) => {
  if (form.installments.length > 1) form.installments.splice(idx, 1);
};

const splitDp5050 = () => {
  if (!canUseDpTermin.value) return;
  const half = Math.round(grandTotal.value / 2);
  const rest = Math.max(0, grandTotal.value - half);
  form.installments = [
    { name: 'DP 50%', percentage: 50, amount: half, due_date: form.invoice_date },
    { name: 'Pelunasan 50%', percentage: 50, amount: rest, due_date: form.due_date },
  ];
};

const submit = () => {
  form.post(route('invoices.store'), {
    onError: () => swalError('Gagal Menyimpan Invoice', 'Periksa kembali rincian item, tanggal, atau termin pembayaran.'),
  });
};
</script>

<template>
  <Head title="Buat Invoice — InvoiceRapi" />

  <WorkspaceLayout title="Buat Invoice">
    <div class="max-w-4xl mx-auto space-y-6">
      <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
          <Link :href="route('invoices.index')" class="p-2 rounded-xl bg-white border border-slate-200 text-slate-600 hover:text-slate-900">
            <ArrowLeft class="w-4 h-4" />
          </Link>
          <div>
            <h1 class="text-2xl font-black text-slate-900">Buat Invoice Baru</h1>
            <p class="text-sm text-slate-500">Lengkapi rincian klien, item tagihan, dan jadwal termin pembayaran.</p>
          </div>
        </div>
      </div>

      <div v-if="clients.length === 0" class="p-6 rounded-2xl bg-amber-50 border border-amber-200 text-amber-900 text-sm">
        Anda belum memiliki klien. <Link :href="route('clients.index')" class="font-bold underline">Tambahkan klien terlebih dahulu</Link> sebelum membuat invoice.
      </div>

      <form v-else @submit.prevent="submit" class="space-y-6">
        <!-- Info Utama -->
        <div class="bg-white rounded-2xl border border-slate-100 p-6 space-y-4">
          <h2 class="text-sm font-black uppercase tracking-wider text-slate-500">Informasi Tagihan</h2>
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label class="block text-xs font-bold text-slate-700 mb-1">Klien *</label>
              <select v-model="form.client_id" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm bg-white">
                <option v-for="c in clients" :key="c.id" :value="c.id">{{ c.name }}</option>
              </select>
              <p v-if="form.errors.client_id" class="text-xs text-red-600 mt-1">{{ form.errors.client_id }}</p>
            </div>
            <div>
              <label class="block text-xs font-bold text-slate-700 mb-1">Proyek (Opsional)</label>
              <select v-model="form.project_id" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm bg-white">
                <option value="">-- Tanpa Proyek --</option>
                <option v-for="p in filteredProjects" :key="p.id" :value="p.id">{{ p.name }}</option>
              </select>
            </div>
            <div>
              <label class="block text-xs font-bold text-slate-700 mb-1">Nomor Invoice *</label>
              <input v-model="form.invoice_number" type="text" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm font-mono" />
              <p v-if="form.errors.invoice_number" class="text-xs text-red-600 mt-1">{{ form.errors.invoice_number }}</p>
            </div>
            <div class="grid grid-cols-2 gap-3">
              <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Tgl Invoice *</label>
                <input v-model="form.invoice_date" type="date" required class="w-full px-3 py-2.5 rounded-xl border border-slate-200 text-sm" />
              </div>
              <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Jatuh Tempo *</label>
                <input v-model="form.due_date" type="date" required class="w-full px-3 py-2.5 rounded-xl border border-slate-200 text-sm" />
              </div>
            </div>
          </div>
        </div>

        <!-- Line Items -->
        <div class="bg-white rounded-2xl border border-slate-100 p-6 space-y-4">
          <div class="flex items-center justify-between">
            <h2 class="text-sm font-black uppercase tracking-wider text-slate-500">Rincian Item / Jasa</h2>
            <button type="button" @click="addItem" class="inline-flex items-center gap-1.5 text-xs font-bold text-violet-600 hover:text-violet-800">
              <Plus class="w-4 h-4" /> Tambah Baris
            </button>
          </div>

          <div v-for="(item, idx) in form.items" :key="idx" class="grid grid-cols-1 sm:grid-cols-12 gap-3 items-end p-3 rounded-xl bg-slate-50 border border-slate-100">
            <div class="sm:col-span-5">
              <label class="block text-[11px] font-bold text-slate-600 mb-1">Nama Item / Jasa *</label>
              <input v-model="item.name" type="text" required placeholder="Contoh: Jasa Pembuatan Website" class="w-full px-3 py-2 rounded-lg border border-slate-200 text-sm bg-white" />
            </div>
            <div class="sm:col-span-2">
              <label class="block text-[11px] font-bold text-slate-600 mb-1">Qty *</label>
              <input v-model.number="item.quantity" type="number" step="0.01" min="0.01" required class="w-full px-3 py-2 rounded-lg border border-slate-200 text-sm bg-white" />
            </div>
            <div class="sm:col-span-4">
              <label class="block text-[11px] font-bold text-slate-600 mb-1">Harga Satuan (Rp) *</label>
              <RupiahInput v-model="item.unit_price" required input-class="py-2 rounded-lg" />
            </div>
            <div class="sm:col-span-1 flex justify-end">
              <button type="button" @click="removeItem(idx)" :disabled="form.items.length === 1" class="p-2 text-slate-400 hover:text-red-600 disabled:opacity-30">
                <Trash2 class="w-4 h-4" />
              </button>
            </div>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-3 border-t border-slate-100">
            <div>
              <label class="block text-xs font-bold text-slate-700 mb-1">Diskon Tambahan (Rp)</label>
              <RupiahInput v-model="form.discount" input-class="py-2" />
            </div>
            <div>
              <label class="block text-xs font-bold text-slate-700 mb-1">Pajak / PPN (Rp)</label>
              <RupiahInput v-model="form.tax" input-class="py-2" />
            </div>
            <div class="flex flex-col justify-end text-right">
              <span class="text-xs text-slate-500">Grand Total</span>
              <span class="text-xl font-black text-violet-700">{{ formatIDR(grandTotal) }}</span>
            </div>
          </div>
        </div>

        <!-- Installments / Termin -->
        <div class="bg-white rounded-2xl border border-slate-100 p-6 space-y-4">
          <div class="flex flex-wrap items-center justify-between gap-2">
            <div>
              <h2 class="text-sm font-black uppercase tracking-wider text-slate-500">Jadwal Pembayaran &amp; Termin DP</h2>
              <p class="text-xs text-slate-500">Atur apakah dibayar penuh sekaligus atau bertahap (DP + Pelunasan).</p>
            </div>
            <div v-if="canUseDpTermin" class="flex items-center gap-2">
              <button type="button" @click="splitDp5050" class="px-3 py-1.5 rounded-lg bg-violet-50 text-violet-700 text-xs font-bold hover:bg-violet-100">
                Bagi DP 50% / 50%
              </button>
              <button type="button" @click="addInstallment" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-slate-100 text-slate-700 text-xs font-bold hover:bg-slate-200">
                <Plus class="w-3.5 h-3.5" /> Tambah Termin
              </button>
            </div>
            <Link v-else :href="route('billing.index')" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-amber-50 border border-amber-200 text-amber-800 text-xs font-bold">
              <Lock class="w-3.5 h-3.5" /> Multi-Termin DP khusus Pro/Bisnis
            </Link>
          </div>

          <div v-for="(inst, idx) in form.installments" :key="idx" class="grid grid-cols-1 sm:grid-cols-12 gap-3 items-end p-3 rounded-xl bg-slate-50 border border-slate-100">
            <div class="sm:col-span-5">
              <label class="block text-[11px] font-bold text-slate-600 mb-1">Nama Termin *</label>
              <input v-model="inst.name" type="text" required class="w-full px-3 py-2 rounded-lg border border-slate-200 text-sm bg-white" />
            </div>
            <div class="sm:col-span-4">
              <label class="block text-[11px] font-bold text-slate-600 mb-1">Nominal (Rp) *</label>
              <RupiahInput v-model="inst.amount" required input-class="py-2 rounded-lg" />
            </div>
            <div class="sm:col-span-2">
              <label class="block text-[11px] font-bold text-slate-600 mb-1">Jatuh Tempo *</label>
              <input v-model="inst.due_date" type="date" required class="w-full px-2.5 py-2 rounded-lg border border-slate-200 text-sm bg-white" />
            </div>
            <div class="sm:col-span-1 flex justify-end">
              <button type="button" @click="removeInstallment(idx)" :disabled="form.installments.length === 1" class="p-2 text-slate-400 hover:text-red-600 disabled:opacity-30">
                <Trash2 class="w-4 h-4" />
              </button>
            </div>
          </div>
        </div>

        <!-- Notes & Submit -->
        <div class="bg-white rounded-2xl border border-slate-100 p-6 space-y-4">
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label class="block text-xs font-bold text-slate-700 mb-1">Instruksi Pembayaran</label>
              <textarea v-model="form.payment_instructions" rows="2" class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-sm"></textarea>
            </div>
            <div>
              <label class="block text-xs font-bold text-slate-700 mb-1">Catatan Tambahan</label>
              <textarea v-model="form.notes" rows="2" class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-sm"></textarea>
            </div>
          </div>

          <div class="flex justify-end gap-3 pt-2">
            <Link :href="route('invoices.index')" class="px-5 py-2.5 rounded-xl border border-slate-200 text-sm font-bold text-slate-600">Batal</Link>
            <button type="submit" :disabled="form.processing" class="px-6 py-2.5 rounded-xl bg-violet-600 hover:bg-violet-700 text-white text-sm font-bold shadow-sm">
              {{ form.processing ? 'Menyimpan...' : 'Simpan & Terbitkan Invoice' }}
            </button>
          </div>
        </div>
      </form>
    </div>
  </WorkspaceLayout>
</template>

