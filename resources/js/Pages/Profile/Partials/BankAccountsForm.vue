<script setup lang="ts">
import { ref } from 'vue';
import { router, useForm } from '@inertiajs/vue3';
import { Landmark, Plus, Pencil, Trash2, Star, CheckCircle2 } from 'lucide-vue-next';
import { swalConfirm, swalError } from '@/utils/swal';

export interface BankAccount {
  id: number;
  bank_name: string;
  account_number: string;
  account_holder: string;
  is_default: boolean;
}

defineProps<{
  bankAccounts: BankAccount[];
}>();

const showModal = ref(false);
const editingAccount = ref<BankAccount | null>(null);

const form = useForm({
  bank_name: '',
  account_number: '',
  account_holder: '',
  is_default: true,
});

const openCreate = () => {
  editingAccount.value = null;
  form.reset();
  form.clearErrors();
  form.is_default = true;
  showModal.value = true;
};

const openEdit = (acc: BankAccount) => {
  editingAccount.value = acc;
  form.clearErrors();
  form.bank_name = acc.bank_name;
  form.account_number = acc.account_number;
  form.account_holder = acc.account_holder;
  form.is_default = acc.is_default;
  showModal.value = true;
};

const submit = () => {
  const onError = () => swalError('Gagal Menyimpan Rekening', 'Periksa nama bank, nomor rekening, dan atas nama.');
  if (editingAccount.value) {
    form.put(route('bank-accounts.update', editingAccount.value.id), {
      preserveScroll: true,
      onSuccess: () => { showModal.value = false; },
      onError,
    });
  } else {
    form.post(route('bank-accounts.store'), {
      preserveScroll: true,
      onSuccess: () => { showModal.value = false; form.reset(); },
      onError,
    });
  }
};

const setDefault = (acc: BankAccount) => {
  router.patch(route('bank-accounts.default', acc.id), {}, { preserveScroll: true });
};

const deleteAccount = async (acc: BankAccount) => {
  const ok = await swalConfirm(
    'Hapus Rekening?',
    `Rekening ${acc.bank_name} (${acc.account_number}) akan diarsipkan (soft delete).`,
    'Ya, Hapus'
  );
  if (!ok) return;
  router.delete(route('bank-accounts.destroy', acc.id), { preserveScroll: true });
};
</script>

<template>
  <section class="space-y-5">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
      <div>
        <h2 class="text-base font-black text-slate-900 flex items-center gap-2">
          <Landmark class="w-4 h-4 text-violet-600" />
          Rekening Pembayaran Invoice
        </h2>
        <p class="mt-1 text-sm text-slate-500">
          Rekening utama otomatis ditampilkan pada kotak <strong>PEMBAYARAN</strong> di setiap invoice dan PDF Anda.
        </p>
      </div>
      <button
        type="button"
        @click="openCreate"
        class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-violet-600 hover:bg-violet-700 text-white text-xs font-bold shadow-sm transition self-start sm:self-center"
      >
        <Plus class="w-4 h-4" />
        Tambah Rekening
      </button>
    </div>

    <div v-if="bankAccounts.length === 0" class="p-6 rounded-2xl border border-dashed border-slate-200 bg-slate-50/70 text-center">
      <Landmark class="w-8 h-8 text-slate-300 mx-auto mb-2" />
      <p class="text-sm font-bold text-slate-700">Belum ada rekening pembayaran</p>
      <p class="text-xs text-slate-500 mt-0.5">Tambahkan rekening bank Anda agar klien tahu ke mana harus transfer.</p>
    </div>

    <div v-else class="grid grid-cols-1 md:grid-cols-2 gap-3.5">
      <div
        v-for="acc in bankAccounts"
        :key="acc.id"
        class="p-4 rounded-2xl border transition flex flex-col justify-between gap-3"
        :class="acc.is_default ? 'bg-violet-50/60 border-violet-200' : 'bg-white border-slate-200'"
      >
        <div class="flex items-start justify-between gap-2">
          <div>
            <div class="flex items-center gap-2">
              <span class="text-sm font-black text-slate-900">{{ acc.bank_name }}</span>
              <span
                v-if="acc.is_default"
                class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-violet-600 text-white text-[10px] font-bold"
              >
                <CheckCircle2 class="w-3 h-3" /> Utama
              </span>
            </div>
            <p class="text-base font-mono font-bold text-violet-800 mt-1">{{ acc.account_number }}</p>
            <p class="text-xs text-slate-600 mt-0.5">a.n. <strong>{{ acc.account_holder }}</strong></p>
          </div>

          <div class="flex items-center gap-1">
            <button
              type="button"
              @click="openEdit(acc)"
              class="p-1.5 rounded-lg text-slate-400 hover:text-violet-700 hover:bg-violet-100/60 transition"
              title="Edit"
            >
              <Pencil class="w-4 h-4" />
            </button>
            <button
              type="button"
              @click="deleteAccount(acc)"
              class="p-1.5 rounded-lg text-slate-400 hover:text-red-600 hover:bg-red-50 transition"
              title="Hapus"
            >
              <Trash2 class="w-4 h-4" />
            </button>
          </div>
        </div>

        <button
          v-if="!acc.is_default"
          type="button"
          @click="setDefault(acc)"
          class="inline-flex items-center gap-1 text-xs font-bold text-violet-700 hover:text-violet-900 self-start"
        >
          <Star class="w-3.5 h-3.5" /> Jadikan Rekening Utama
        </button>
      </div>
    </div>

    <!-- Modal Form -->
    <div v-if="showModal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-sm p-4">
      <div class="bg-white rounded-3xl max-w-md w-full p-6 shadow-2xl space-y-4">
        <div class="flex items-center justify-between">
          <h3 class="text-lg font-black text-slate-900">
            {{ editingAccount ? 'Edit Rekening Pembayaran' : 'Tambah Rekening Pembayaran' }}
          </h3>
          <button type="button" @click="showModal = false" class="text-xs font-bold text-slate-500">Tutup</button>
        </div>

        <form @submit.prevent="submit" class="space-y-4">
          <div>
            <label class="block text-xs font-bold text-slate-700 mb-1">Nama Bank / E-Wallet *</label>
            <input
              v-model="form.bank_name"
              type="text"
              required
              placeholder="Contoh: BCA, Mandiri, BRI, BNI"
              class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm"
            />
            <p v-if="form.errors.bank_name" class="text-xs text-red-600 mt-1">{{ form.errors.bank_name }}</p>
          </div>

          <div>
            <label class="block text-xs font-bold text-slate-700 mb-1">Nomor Rekening *</label>
            <input
              v-model="form.account_number"
              type="text"
              required
              placeholder="Contoh: 1234567890"
              class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm font-mono"
            />
            <p v-if="form.errors.account_number" class="text-xs text-red-600 mt-1">{{ form.errors.account_number }}</p>
          </div>

          <div>
            <label class="block text-xs font-bold text-slate-700 mb-1">Atas Nama *</label>
            <input
              v-model="form.account_holder"
              type="text"
              required
              placeholder="Contoh: Rofiq Wedding"
              class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm"
            />
            <p v-if="form.errors.account_holder" class="text-xs text-red-600 mt-1">{{ form.errors.account_holder }}</p>
          </div>

          <label class="inline-flex items-center gap-2 text-xs font-semibold text-slate-700 cursor-pointer">
            <input
              v-model="form.is_default"
              type="checkbox"
              class="rounded border-slate-300 text-violet-600 focus:ring-violet-500"
            />
            <span>Jadikan rekening utama untuk semua invoice baru</span>
          </label>

          <div class="flex justify-end gap-2 pt-2">
            <button type="button" @click="showModal = false" class="px-4 py-2 rounded-xl border border-slate-200 text-xs font-bold text-slate-600">
              Batal
            </button>
            <button type="submit" :disabled="form.processing" class="px-5 py-2 rounded-xl bg-violet-600 hover:bg-violet-700 text-white text-xs font-bold">
              {{ form.processing ? 'Menyimpan...' : 'Simpan Rekening' }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </section>
</template>
