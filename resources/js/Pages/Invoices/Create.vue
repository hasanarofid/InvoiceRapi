<script setup lang="ts">
import { ref, computed, watch } from 'vue';
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import WorkspaceLayout from '@/Layouts/WorkspaceLayout.vue';
import RupiahInput from '@/Components/RupiahInput.vue';
import { swalError } from '@/utils/swal';
import { formatIDR } from '@/utils/format';
import { Plus, Trash2, ArrowLeft, Lock, UserPlus, Sparkles } from 'lucide-vue-next';

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

interface BankAccountOption {
  id: number;
  bank_name: string;
  account_number: string;
  account_holder: string;
  is_default: boolean;
}

const props = defineProps<{
  clients: ClientOption[];
  projects: ProjectOption[];
  bankAccounts?: BankAccountOption[];
  suggestedNumber: string;
}>();

const page = usePage();
const planUsage = computed(() => (page.props.planUsage as Record<string, any> | null) ?? null);
const canUseDpTermin = computed(() => Boolean(planUsage.value?.features?.dp_termin ?? true));
const clientLimitReached = computed(() => Boolean(planUsage.value?.client_limit_reached));

const today = new Date().toISOString().slice(0, 10);
const nextWeek = new Date(Date.now() + 7 * 86400000).toISOString().slice(0, 10);

const paymentMode = ref<'full' | 'termin'>('full');
const showQuickClientModal = ref(false);

const quickClientForm = useForm({
  name: '',
  company_name: '',
  phone: '',
  email: '',
});

const form = useForm({
  client_id: props.clients[0]?.id ?? ('' as string | number),
  project_id: '' as string | number,
  bank_account_id: (props.bankAccounts?.find((b) => b.is_default)?.id ?? props.bankAccounts?.[0]?.id ?? '') as string | number,
  invoice_number: props.suggestedNumber,
  invoice_date: today,
  due_date: nextWeek,
  description: '',
  notes: '',
  payment_instructions: 'Transfer ke rekening resmi perusahaan kami dan kirim bukti transfer.',
  discount: 0,
  tax: 0,
  items: [
    { name: '', description: '', quantity: 1, unit_price: 0, discount: 0 },
  ],
  installments: [
    { name: 'Pembayaran Penuh', percentage: 100, amount: 0, due_date: nextWeek },
  ],
});

// Auto-select newly added client when props.clients grows
watch(
  () => props.clients,
  (newList, oldList) => {
    if (newList.length > (oldList?.length ?? 0)) {
      const newest = newList[newList.length - 1];
      if (newest) form.client_id = newest.id;
    } else if (!form.client_id && newList.length > 0) {
      form.client_id = newList[0].id;
    }
  }
);

const filteredProjects = computed(() =>
  props.projects.filter((p) => String(p.client_id) === String(form.client_id))
);

const subtotal = computed(() =>
  form.items.reduce((sum, item) => sum + Math.max(0, Number(item.quantity || 0) * Number(item.unit_price || 0) - Number(item.discount || 0)), 0)
);

const grandTotal = computed(() =>
  Math.max(0, subtotal.value - Number(form.discount || 0) + Number(form.tax || 0))
);

// Keep single full-payment installment synced when in 'full' mode
watch([grandTotal, () => form.due_date, paymentMode], ([total, due, mode]) => {
  if (mode === 'full') {
    form.installments = [
      { name: 'Pembayaran Penuh', percentage: 100, amount: total, due_date: due },
    ];
  } else if (form.installments.length === 1) {
    form.installments[0].amount = total;
  }
}, { immediate: true });

const setPaymentMode = (mode: 'full' | 'termin') => {
  if (mode === 'termin' && !canUseDpTermin.value) return;
  paymentMode.value = mode;
  if (mode === 'termin' && form.installments.length === 1) {
    splitDp5050();
  }
};

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

const saveQuickClient = () => {
  quickClientForm.post(route('clients.store'), {
    preserveScroll: true,
    preserveState: true,
    onSuccess: () => {
      showQuickClientModal.value = false;
      quickClientForm.reset();
    },
    onError: () => swalError('Gagal Menambah Klien', 'Periksa nama atau kontak klien.'),
  });
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
    <div class="max-w-4xl mx-auto space-y-5">
      <!-- Header -->
      <div class="flex items-center gap-3">
        <Link :href="route('invoices.index')" class="p-2 rounded-xl bg-white border border-slate-200 text-slate-600 hover:text-slate-900">
          <ArrowLeft class="w-4 h-4" />
        </Link>
        <div>
          <h1 class="text-xl sm:text-2xl font-black text-slate-900">Buat Invoice Cepat</h1>
          <p class="text-xs sm:text-sm text-slate-500">Pilih klien, masukkan item barang/jasa, lalu langsung terbitkan.</p>
        </div>
      </div>

      <form @submit.prevent="submit" class="space-y-5">
        <!-- Step 1: Klien & Tanggal -->
        <div class="bg-white rounded-2xl border border-slate-100 p-5 sm:p-6 space-y-4">
          <div class="flex items-center justify-between">
            <h2 class="text-xs font-black uppercase tracking-wider text-violet-700">1. Klien &amp; Tanggal</h2>
            <button
              v-if="!clientLimitReached"
              type="button"
              @click="showQuickClientModal = true"
              class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-violet-50 hover:bg-violet-100 text-violet-700 text-xs font-bold transition"
            >
              <UserPlus class="w-3.5 h-3.5" /> + Klien Baru
            </button>
          </div>

          <div v-if="clients.length === 0" class="p-4 rounded-xl bg-amber-50 border border-amber-200 text-amber-900 text-xs flex items-center justify-between gap-3">
            <span>Belum ada klien. Tambahkan klien pertama langsung dari sini.</span>
            <button type="button" @click="showQuickClientModal = true" class="px-3 py-1.5 rounded-lg bg-amber-600 text-white font-bold shrink-0">
              Tambah Klien
            </button>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label class="block text-xs font-bold text-slate-700 mb-1">Klien *</label>
              <select v-model="form.client_id" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm bg-white">
                <option value="" disabled>-- Pilih Klien --</option>
                <option v-for="c in clients" :key="c.id" :value="c.id">{{ c.name }}{{ c.company_name ? ` (${c.company_name})` : '' }}</option>
              </select>
              <p v-if="form.errors.client_id" class="text-xs text-red-600 mt-1">{{ form.errors.client_id }}</p>
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
            <div v-if="filteredProjects.length > 0">
              <label class="block text-xs font-bold text-slate-700 mb-1">Proyek (Opsional)</label>
              <select v-model="form.project_id" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm bg-white">
                <option value="">-- Tanpa Proyek --</option>
                <option v-for="p in filteredProjects" :key="p.id" :value="p.id">{{ p.name }}</option>
              </select>
            </div>
          </div>
        </div>

        <!-- Step 2: Rincian Item -->
        <div class="bg-white rounded-2xl border border-slate-100 p-5 sm:p-6 space-y-4">
          <div class="flex items-center justify-between">
            <h2 class="text-xs font-black uppercase tracking-wider text-violet-700">2. Rincian Barang / Jasa</h2>
            <button type="button" @click="addItem" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-violet-50 text-xs font-bold text-violet-700 hover:bg-violet-100">
              <Plus class="w-3.5 h-3.5" /> Tambah Item
            </button>
          </div>

          <div v-for="(item, idx) in form.items" :key="idx" class="grid grid-cols-1 sm:grid-cols-12 gap-3 items-end p-3.5 rounded-xl bg-slate-50 border border-slate-100">
            <div class="sm:col-span-5">
              <label class="block text-[11px] font-bold text-slate-600 mb-1">Nama Item / Jasa *</label>
              <input v-model="item.name" type="text" required placeholder="Contoh: Nasi Kotak Ayam Bakar" class="w-full px-3 py-2 rounded-lg border border-slate-200 text-sm bg-white" />
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
            <div class="flex flex-col justify-end sm:text-right bg-violet-50/70 rounded-xl p-3 border border-violet-100">
              <span class="text-[11px] font-bold text-violet-700 uppercase">Total Tagihan</span>
              <span class="text-xl font-black text-violet-800">{{ formatIDR(grandTotal) }}</span>
            </div>
          </div>
        </div>

        <!-- Step 3: Cara Pembayaran (Simple Toggle) -->
        <div class="bg-white rounded-2xl border border-slate-100 p-5 sm:p-6 space-y-4">
          <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div>
              <h2 class="text-xs font-black uppercase tracking-wider text-violet-700">3. Cara Pembayaran &amp; Instruksi</h2>
              <p class="text-xs text-slate-500 mt-0.5">Pilih pembayaran penuh sekaligus atau bagi menjadi beberapa termin DP.</p>
            </div>

            <div class="inline-flex rounded-xl bg-slate-100 p-1 self-start">
              <button
                type="button"
                @click="setPaymentMode('full')"
                class="px-3.5 py-1.5 rounded-lg text-xs font-bold transition"
                :class="paymentMode === 'full' ? 'bg-white text-violet-700 shadow-xs' : 'text-slate-600'"
              >
                Bayar Penuh (1x)
              </button>
              <button
                type="button"
                @click="setPaymentMode('termin')"
                class="inline-flex items-center gap-1 px-3.5 py-1.5 rounded-lg text-xs font-bold transition"
                :class="paymentMode === 'termin' ? 'bg-white text-violet-700 shadow-xs' : 'text-slate-600'"
              >
                <Lock v-if="!canUseDpTermin" class="w-3 h-3 text-amber-600" />
                DP / Cicilan Termin
              </button>
            </div>
          </div>

          <div v-if="!canUseDpTermin" class="p-3 rounded-xl bg-amber-50 border border-amber-200 text-xs text-amber-900 flex items-center justify-between">
            <span>Fitur multi-termin DP tersedia di paket <strong>Pro / Bisnis</strong>.</span>
            <Link :href="route('billing.index')" class="font-bold underline">Upgrade</Link>
          </div>

          <!-- Termin Builder (Only shown when 'termin' mode selected) -->
          <div v-if="paymentMode === 'termin' && canUseDpTermin" class="space-y-3 pt-2">
            <div class="flex items-center gap-2">
              <button type="button" @click="splitDp5050" class="px-3 py-1.5 rounded-lg bg-violet-50 text-violet-700 text-xs font-bold hover:bg-violet-100">
                <Sparkles class="w-3.5 h-3.5 inline mr-1" /> Bagi DP 50% / 50%
              </button>
              <button type="button" @click="addInstallment" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-slate-100 text-slate-700 text-xs font-bold hover:bg-slate-200">
                <Plus class="w-3.5 h-3.5" /> Tambah Termin
              </button>
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

          <!-- Rekening Tujuan Pembayaran -->
          <div class="pt-2">
            <div class="flex items-center justify-between mb-1">
              <label class="block text-xs font-bold text-slate-700">Rekening Tujuan Pembayaran</label>
              <Link :href="route('profile.edit')" class="text-[11px] font-bold text-violet-700 hover:underline">
                Kelola Rekening
              </Link>
            </div>
            <select
              v-if="bankAccounts && bankAccounts.length > 0"
              v-model="form.bank_account_id"
              class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm bg-white"
            >
              <option v-for="acc in bankAccounts" :key="acc.id" :value="acc.id">
                {{ acc.bank_name }} — {{ acc.account_number }} (a.n. {{ acc.account_holder }}){{ acc.is_default ? ' [Utama]' : '' }}
              </option>
            </select>
            <div v-else class="p-3 rounded-xl bg-slate-50 border border-slate-200 text-xs text-slate-600 flex items-center justify-between gap-2">
              <span>Belum ada rekening tersimpan. Tambahkan di Profil Akun agar otomatis tampil di invoice.</span>
              <Link :href="route('profile.edit')" class="font-bold text-violet-700 underline shrink-0">Tambah Rekening</Link>
            </div>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
            <div>
              <label class="block text-xs font-bold text-slate-700 mb-1">Rekening / Instruksi Pembayaran</label>
              <textarea v-model="form.payment_instructions" rows="2" class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-sm" placeholder="Contoh: Transfer BCA 1234567890 a.n. Dapur Mama"></textarea>
            </div>
            <div>
              <label class="block text-xs font-bold text-slate-700 mb-1">Catatan (Opsional)</label>
              <textarea v-model="form.notes" rows="2" class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-sm" placeholder="Terima kasih atas pesanannya"></textarea>
            </div>
          </div>
        </div>

        <!-- Submit Bar -->
        <div class="bg-white rounded-2xl border border-slate-100 p-4 sm:p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-3 shadow-sm">
          <div>
            <p class="text-xs text-slate-500">Total yang akan diterbitkan:</p>
            <p class="text-lg font-black text-violet-700">{{ formatIDR(grandTotal) }}</p>
          </div>
          <div class="flex items-center gap-3">
            <Link :href="route('invoices.index')" class="px-5 py-2.5 rounded-xl border border-slate-200 text-sm font-bold text-slate-600">Batal</Link>
            <button
              type="submit"
              :disabled="form.processing || clients.length === 0"
              class="flex-1 sm:flex-none px-6 py-2.5 rounded-xl bg-violet-600 hover:bg-violet-700 disabled:opacity-50 text-white text-sm font-bold shadow-sm transition"
            >
              {{ form.processing ? 'Menyimpan...' : 'Terbitkan Invoice' }}
            </button>
          </div>
        </div>
      </form>
    </div>

    <!-- Quick-Add Client Modal -->
    <div v-if="showQuickClientModal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-sm p-4">
      <div class="bg-white rounded-3xl max-w-md w-full p-6 shadow-2xl space-y-4">
        <div class="flex items-center justify-between">
          <h3 class="text-lg font-black text-slate-900">Tambah Klien Cepat</h3>
          <button type="button" @click="showQuickClientModal = false" class="text-xs font-bold text-slate-500">Tutup</button>
        </div>

        <form @submit.prevent="saveQuickClient" class="space-y-3.5">
          <div>
            <label class="block text-xs font-bold text-slate-700 mb-1">Nama Klien *</label>
            <input v-model="quickClientForm.name" type="text" required placeholder="Contoh: Ibu Rose" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm" />
          </div>
          <div>
            <label class="block text-xs font-bold text-slate-700 mb-1">No. WhatsApp</label>
            <input v-model="quickClientForm.phone" type="text" placeholder="08123456789" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm" />
          </div>
          <div class="grid grid-cols-2 gap-3">
            <div>
              <label class="block text-xs font-bold text-slate-700 mb-1">Perusahaan</label>
              <input v-model="quickClientForm.company_name" type="text" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm" />
            </div>
            <div>
              <label class="block text-xs font-bold text-slate-700 mb-1">Email</label>
              <input v-model="quickClientForm.email" type="email" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm" />
            </div>
          </div>

          <div class="flex justify-end gap-2 pt-2">
            <button type="button" @click="showQuickClientModal = false" class="px-4 py-2 rounded-xl border border-slate-200 text-xs font-bold text-slate-600">Batal</button>
            <button type="submit" :disabled="quickClientForm.processing" class="px-5 py-2 rounded-xl bg-violet-600 hover:bg-violet-700 text-white text-xs font-bold">
              {{ quickClientForm.processing ? 'Menyimpan...' : 'Simpan & Pilih' }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </WorkspaceLayout>
</template>
