<script setup lang="ts">
import { ref, computed } from 'vue';
import { Head, useForm } from '@inertiajs/vue3';
import WorkspaceLayout from '@/Layouts/WorkspaceLayout.vue';
import InputError from '@/Components/InputError.vue';
import { formatDateTimeID } from '@/utils/format';
import {
  Crown,
  Copy,
  Check,
  Upload,
  AlertTriangle,
  CheckCircle2,
  Clock,
  XCircle,
  Building2,
  FileText,
  Users,
  ExternalLink
} from 'lucide-vue-next';

interface UsageSummary {
  plan: string;
  plan_name: string;
  subscription_status: string;
  is_trial_expired: boolean;
  trial_ends_at: string | null;
  invoices_this_month: number;
  max_invoices_per_month: number | null;
  invoice_limit_reached: boolean;
  clients_count: number;
  max_clients: number | null;
  client_limit_reached: boolean;
  any_limit_reached: boolean;
}

interface PaymentItem {
  id: number;
  plan: string;
  billing_cycle: string;
  amount: number;
  sender_name: string;
  sender_bank: string | null;
  transfer_date: string;
  proof_url: string;
  status: 'pending' | 'approved' | 'rejected';
  admin_notes: string | null;
  created_at: string;
}

const props = defineProps<{
  usage: UsageSummary | null;
  bank: {
    bank_name: string;
    account_number: string;
    account_holder: string;
  };
  plans: Record<string, {
    name: string;
    monthly_price: number;
    annual_price: number;
    max_invoices_per_month: number;
    max_clients: number | null;
  }>;
  payments: PaymentItem[];
}>();

const copied = ref(false);
const copyAccount = async () => {
  try {
    await navigator.clipboard.writeText(props.bank.account_number);
    copied.value = true;
    setTimeout(() => { copied.value = false; }, 2000);
  } catch {
    copied.value = false;
  }
};

const form = useForm({
  plan: 'pro' as 'pro' | 'bisnis',
  billing_cycle: 'monthly' as 'monthly' | 'annual',
  sender_name: '',
  sender_bank: '',
  transfer_date: new Date().toISOString().slice(0, 10),
  proof: null as File | null,
});

const selectedAmount = computed(() => {
  const planObj = props.plans[form.plan];
  if (!planObj) return 0;
  return form.billing_cycle === 'annual' ? planObj.annual_price : planObj.monthly_price;
});

const formatRupiah = (val: number) =>
  new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(val);

const handleFileChange = (e: Event) => {
  const target = e.target as HTMLInputElement;
  form.proof = target.files?.[0] ?? null;
};

const submit = () => {
  form.post(route('billing.store'), {
    forceFormData: true,
    onSuccess: () => {
      form.reset('sender_name', 'sender_bank', 'proof');
    },
  });
};

const invoicePercent = computed(() => {
  if (!props.usage?.max_invoices_per_month) return 0;
  return Math.min(100, Math.round((props.usage.invoices_this_month / props.usage.max_invoices_per_month) * 100));
});

const clientPercent = computed(() => {
  if (!props.usage?.max_clients) return 0;
  return Math.min(100, Math.round((props.usage.clients_count / props.usage.max_clients) * 100));
});
</script>

<template>
  <WorkspaceLayout>
    <Head title="Langganan &amp; Upgrade Paket — Synbarra InvoiceRapi" />

    <div class="space-y-6">
      <!-- Page Title -->
      <div>
        <h1 class="text-2xl font-black text-slate-900 tracking-tight">Langganan &amp; Kuota Paket</h1>
        <p class="text-xs sm:text-sm text-slate-500 mt-0.5">
          Pantau pemakaian kuota Anda dan lakukan upgrade via transfer bank resmi.
        </p>
      </div>

      <!-- Alert if limit reached or trial expired -->
      <div
        v-if="usage && (usage.any_limit_reached || usage.is_trial_expired)"
        class="p-4 rounded-2xl bg-amber-50 border border-amber-200 flex items-start gap-3"
      >
        <AlertTriangle class="w-5 h-5 text-amber-600 shrink-0 mt-0.5" />
        <div class="text-xs sm:text-sm text-amber-900 space-y-1">
          <p class="font-bold">
            {{ usage.is_trial_expired ? 'Masa Uji Coba 14 Hari Telah Berakhir — Akun Anda Dialihkan ke Paket Gratis.' : 'Batas Kuota Paket Gratis Telah Tercapai.' }}
          </p>
          <p class="text-amber-800">
            Silakan lakukan transfer ke rekening BCA resmi di bawah dan unggah bukti pembayaran untuk membuka akses penuh Paket Pro / Bisnis.
          </p>
        </div>
      </div>

      <!-- Current Plan & Usage Tracking -->
      <div v-if="usage" class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white rounded-2xl border border-violet-100 p-5 shadow-invoicerapi-sm space-y-2">
          <div class="flex items-center justify-between">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Paket Aktif</span>
            <Crown class="w-5 h-5 text-violet-700" />
          </div>
          <p class="text-2xl font-black text-slate-900">Paket {{ usage.plan_name }}</p>
          <span
            :class="[
              'inline-block px-2.5 py-0.5 rounded-full text-[11px] font-bold',
              usage.subscription_status === 'active' ? 'bg-emerald-100 text-emerald-700' : 'bg-violet-100 text-violet-700'
            ]"
          >
            {{ usage.subscription_status === 'trial' ? 'Masa Trial' : (usage.subscription_status === 'active' ? 'Aktif' : usage.subscription_status) }}
          </span>
        </div>

        <!-- Invoice Quota -->
        <div class="bg-white rounded-2xl border border-violet-100 p-5 shadow-invoicerapi-sm space-y-3">
          <div class="flex items-center justify-between">
            <span class="text-xs font-bold text-slate-600 flex items-center gap-1.5">
              <FileText class="w-4 h-4 text-violet-700" />
              Invoice Bulan Ini
            </span>
            <span class="text-xs font-extrabold text-slate-900">
              {{ usage.invoices_this_month }} / {{ usage.max_invoices_per_month ?? '∞' }}
            </span>
          </div>
          <div class="w-full h-2.5 bg-slate-100 rounded-full overflow-hidden">
            <div
              class="h-full rounded-full transition-all duration-300"
              :class="usage.invoice_limit_reached ? 'bg-red-500' : 'bg-violet-700'"
              :style="{ width: `${invoicePercent}%` }"
            ></div>
          </div>
          <p class="text-[11px]" :class="usage.invoice_limit_reached ? 'text-red-600 font-bold' : 'text-slate-400'">
            {{ usage.invoice_limit_reached ? 'Kuota penuh! Upgrade untuk membuat invoice lagi.' : 'Kuota masih tersedia bulan ini.' }}
          </p>
        </div>

        <!-- Client Quota -->
        <div class="bg-white rounded-2xl border border-violet-100 p-5 shadow-invoicerapi-sm space-y-3">
          <div class="flex items-center justify-between">
            <span class="text-xs font-bold text-slate-600 flex items-center gap-1.5">
              <Users class="w-4 h-4 text-violet-700" />
              Klien Terdaftar
            </span>
            <span class="text-xs font-extrabold text-slate-900">
              {{ usage.clients_count }} / {{ usage.max_clients ?? 'Unlimited' }}
            </span>
          </div>
          <div class="w-full h-2.5 bg-slate-100 rounded-full overflow-hidden">
            <div
              class="h-full rounded-full transition-all duration-300"
              :class="usage.client_limit_reached ? 'bg-red-500' : 'bg-emerald-500'"
              :style="{ width: usage.max_clients ? `${clientPercent}%` : '25%' }"
            ></div>
          </div>
          <p class="text-[11px]" :class="usage.client_limit_reached ? 'text-red-600 font-bold' : 'text-slate-400'">
            {{ usage.max_clients ? (usage.client_limit_reached ? 'Batas klien tercapai!' : `Maksimal ${usage.max_clients} klien untuk Paket Gratis.`) : 'Klien tanpa batas aktif.' }}
          </p>
        </div>
      </div>

      <!-- Bank Transfer Info + Upload Proof Form -->
      <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <!-- Left: Bank Account Card -->
        <div class="lg:col-span-5 space-y-4">
          <div class="bg-gradient-to-br from-violet-700 to-violet-900 text-white rounded-2xl p-6 shadow-invoicerapi-md space-y-5">
            <div class="flex items-center justify-between">
              <span class="text-xs font-bold uppercase tracking-wider text-violet-200">Rekening Transfer Resmi</span>
              <Building2 class="w-5 h-5 text-violet-200" />
            </div>

            <div>
              <p class="text-xs text-violet-200">Bank Tujuan</p>
              <p class="text-lg font-extrabold">{{ bank.bank_name }}</p>
            </div>

            <div class="bg-white/10 border border-white/20 rounded-xl p-4 flex items-center justify-between gap-3">
              <div>
                <p class="text-[11px] text-violet-200">Nomor Rekening</p>
                <p class="text-2xl font-mono font-black tracking-wider">{{ bank.account_number }}</p>
              </div>
              <button
                type="button"
                @click="copyAccount"
                class="px-3 py-2 rounded-lg bg-white text-violet-800 text-xs font-bold flex items-center gap-1.5 hover:bg-violet-50 transition shrink-0"
              >
                <Check v-if="copied" class="w-3.5 h-3.5 text-emerald-600" />
                <Copy v-else class="w-3.5 h-3.5" />
                <span>{{ copied ? 'Tersalin' : 'Salin' }}</span>
              </button>
            </div>

            <div>
              <p class="text-xs text-violet-200">Atas Nama</p>
              <p class="text-base font-bold">{{ bank.account_holder }}</p>
            </div>

            <p class="text-[11px] text-violet-200 leading-relaxed pt-2 border-t border-white/15">
              Setelah melakukan transfer sesuai nominal paket, unggah bukti pembayaran di formulir sebelah. Notifikasi otomatis dikirim ke admin untuk aktivasi cepat.
            </p>
          </div>
        </div>

        <!-- Right: Upload Form -->
        <div class="lg:col-span-7 bg-white rounded-2xl border border-violet-100 p-6 shadow-invoicerapi-sm">
          <h2 class="text-base font-extrabold text-slate-900 mb-4">Konfirmasi &amp; Upload Bukti Pembayaran</h2>

          <form @submit.prevent="submit" class="space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Pilih Paket</label>
                <select
                  v-model="form.plan"
                  class="w-full rounded-xl border-slate-200 text-sm focus:border-violet-600 focus:ring-violet-600"
                >
                  <option value="pro">Paket Pro (100 Invoice/bln, Unlimited Klien)</option>
                  <option value="bisnis">Paket Bisnis (500 Invoice/bln, 5 Pengguna)</option>
                </select>
                <InputError :message="form.errors.plan" class="mt-1 text-xs" />
              </div>

              <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Periode Tagihan</label>
                <select
                  v-model="form.billing_cycle"
                  class="w-full rounded-xl border-slate-200 text-sm focus:border-violet-600 focus:ring-violet-600"
                >
                  <option value="monthly">Bulanan</option>
                  <option value="annual">Tahunan (Hemat 30%)</option>
                </select>
                <InputError :message="form.errors.billing_cycle" class="mt-1 text-xs" />
              </div>
            </div>

            <div class="p-3.5 rounded-xl bg-[#FAF8FF] border border-violet-100 flex items-center justify-between">
              <span class="text-xs font-semibold text-slate-600">Total yang Harus Ditransfer:</span>
              <span class="text-lg font-black text-violet-700">{{ formatRupiah(selectedAmount) }}</span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Nama Pemilik Rekening Pengirim</label>
                <input
                  v-model="form.sender_name"
                  type="text"
                  required
                  placeholder="Contoh: Budi Santoso"
                  class="w-full rounded-xl border-slate-200 text-sm focus:border-violet-600 focus:ring-violet-600"
                />
                <InputError :message="form.errors.sender_name" class="mt-1 text-xs" />
              </div>

              <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Bank Pengirim</label>
                <input
                  v-model="form.sender_bank"
                  type="text"
                  placeholder="Contoh: BCA / Mandiri / BRI"
                  class="w-full rounded-xl border-slate-200 text-sm focus:border-violet-600 focus:ring-violet-600"
                />
                <InputError :message="form.errors.sender_bank" class="mt-1 text-xs" />
              </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Tanggal Transfer</label>
                <input
                  v-model="form.transfer_date"
                  type="date"
                  required
                  class="w-full rounded-xl border-slate-200 text-sm focus:border-violet-600 focus:ring-violet-600"
                />
                <InputError :message="form.errors.transfer_date" class="mt-1 text-xs" />
              </div>

              <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Upload Bukti Transfer (JPG/PNG/PDF, max 4MB)</label>
                <input
                  type="file"
                  accept=".jpg,.jpeg,.png,.webp,.pdf"
                  required
                  @change="handleFileChange"
                  class="w-full text-xs text-slate-600 file:mr-3 file:py-2 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-violet-50 file:text-violet-700 hover:file:bg-violet-100"
                />
                <InputError :message="form.errors.proof" class="mt-1 text-xs" />
              </div>
            </div>

            <button
              type="submit"
              :disabled="form.processing"
              class="w-full inline-flex items-center justify-center gap-2 py-3 px-4 rounded-xl bg-violet-700 hover:bg-violet-800 text-white text-sm font-bold shadow-invoicerapi-sm transition disabled:opacity-50"
            >
              <Upload class="w-4 h-4" />
              <span>{{ form.processing ? 'Mengunggah...' : 'Kirim Bukti Pembayaran' }}</span>
            </button>
          </form>
        </div>
      </div>

      <!-- Payment History -->
      <div class="bg-white rounded-2xl border border-violet-100 p-6 shadow-invoicerapi-sm">
        <h3 class="text-base font-extrabold text-slate-900 mb-4">Riwayat Konfirmasi Pembayaran</h3>

        <div v-if="payments.length === 0" class="text-xs sm:text-sm text-slate-500 py-6 text-center">
          Belum ada riwayat pengajuan pembayaran.
        </div>

        <div v-else class="overflow-x-auto">
          <table class="w-full text-left text-xs sm:text-sm">
            <thead>
              <tr class="border-b border-slate-100 text-slate-400 text-xs uppercase">
                <th class="py-3 pr-4">Tanggal</th>
                <th class="py-3 pr-4">Paket</th>
                <th class="py-3 pr-4">Nominal</th>
                <th class="py-3 pr-4">Pengirim</th>
                <th class="py-3 pr-4">Bukti</th>
                <th class="py-3">Status</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
              <tr v-for="item in payments" :key="item.id">
                <td class="py-3 pr-4 text-slate-600">{{ formatDateTimeID(item.created_at) }}</td>
                <td class="py-3 pr-4 font-bold text-slate-900 uppercase">{{ item.plan }} ({{ item.billing_cycle === 'annual' ? 'Tahunan' : 'Bulanan' }})</td>
                <td class="py-3 pr-4 font-bold text-violet-700">{{ formatRupiah(item.amount) }}</td>
                <td class="py-3 pr-4 text-slate-600">{{ item.sender_name }} ({{ item.sender_bank || '-' }})</td>
                <td class="py-3 pr-4">
                  <a :href="item.proof_url" target="_blank" class="inline-flex items-center gap-1 text-violet-700 font-semibold hover:underline">
                    <span>Lihat</span>
                    <ExternalLink class="w-3 h-3" />
                  </a>
                </td>
                <td class="py-3">
                  <span
                    v-if="item.status === 'approved'"
                    class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-emerald-100 text-emerald-700 text-xs font-bold"
                  >
                    <CheckCircle2 class="w-3.5 h-3.5" /> Disetujui
                  </span>
                  <span
                    v-else-if="item.status === 'rejected'"
                    class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-red-100 text-red-700 text-xs font-bold"
                  >
                    <XCircle class="w-3.5 h-3.5" /> Ditolak
                  </span>
                  <span
                    v-else
                    class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-amber-100 text-amber-800 text-xs font-bold"
                  >
                    <Clock class="w-3.5 h-3.5" /> Menunggu Verifikasi
                  </span>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </WorkspaceLayout>
</template>

