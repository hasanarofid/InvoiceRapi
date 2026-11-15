<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { nextTick, ref } from 'vue';

const confirmingUserDeletion = ref(false);
const passwordInput = ref<HTMLInputElement | null>(null);

const form = useForm({
  password: '',
});

const confirmUserDeletion = () => {
  confirmingUserDeletion.value = true;
  nextTick(() => passwordInput.value?.focus());
};

const deleteUser = () => {
  form.delete(route('profile.destroy'), {
    preserveScroll: true,
    onSuccess: () => closeModal(),
    onError: () => passwordInput.value?.focus(),
    onFinish: () => form.reset(),
  });
};

const closeModal = () => {
  confirmingUserDeletion.value = false;
  form.clearErrors();
  form.reset();
};
</script>

<template>
  <section class="space-y-4">
    <header>
      <h2 class="text-base font-black text-red-700">Nonaktifkan / Hapus Akun</h2>
      <p class="mt-1 text-sm text-slate-500">
        Akun Anda akan dinonaktifkan (soft delete) dan sesi akan berakhir. Data transaksi tetap tersimpan secara aman untuk keperluan arsip &amp; audit.
      </p>
    </header>

    <button
      type="button"
      @click="confirmUserDeletion"
      class="px-5 py-2.5 rounded-xl bg-red-600 hover:bg-red-700 text-white text-sm font-bold shadow-sm transition"
    >
      Hapus Akun
    </button>

    <div v-if="confirmingUserDeletion" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-sm p-4">
      <div class="bg-white rounded-3xl max-w-md w-full p-6 shadow-2xl space-y-4">
        <h3 class="text-lg font-black text-slate-900">Konfirmasi Hapus Akun</h3>
        <p class="text-sm text-slate-600">
          Masukkan kata sandi Anda untuk mengonfirmasi penonaktifan akun ini.
        </p>

        <div>
          <input
            id="delete_password"
            ref="passwordInput"
            v-model="form.password"
            type="password"
            placeholder="Kata sandi Anda"
            @keyup.enter="deleteUser"
            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-red-500"
          />
          <p v-if="form.errors.password" class="mt-1.5 text-xs text-red-600 font-medium">{{ form.errors.password }}</p>
        </div>

        <div class="flex justify-end gap-2 pt-2">
          <button type="button" @click="closeModal" class="px-4 py-2 rounded-xl border border-slate-200 text-xs font-bold text-slate-600">
            Batal
          </button>
          <button
            type="button"
            :disabled="form.processing"
            @click="deleteUser"
            class="px-5 py-2 rounded-xl bg-red-600 hover:bg-red-700 disabled:opacity-50 text-white text-xs font-bold"
          >
            {{ form.processing ? 'Memproses...' : 'Ya, Hapus Akun' }}
          </button>
        </div>
      </div>
    </div>
  </section>
</template>
