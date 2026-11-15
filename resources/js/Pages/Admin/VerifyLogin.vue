<script setup lang="ts">
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, router, useForm } from '@inertiajs/vue3';
import { ShieldCheck, RefreshCw, LogOut } from 'lucide-vue-next';

defineProps<{
  email: string;
  mailSent: boolean;
  status?: string | null;
}>();

const form = useForm({
  code: '',
});

const submit = () => {
  form.post(route('admin.verify.store'), {
    onFinish: () => form.reset('code'),
  });
};

const resendCode = () => {
  router.post(route('admin.verify.resend'), {}, { preserveScroll: true });
};

const logout = () => {
  router.post(route('logout'));
};
</script>

<template>
  <GuestLayout>
    <Head title="Verifikasi Login Admin — Synbarra InvoiceRapi" />

    <div class="text-center space-y-2 mb-5">
      <div class="w-12 h-12 rounded-2xl bg-violet-100 text-violet-700 flex items-center justify-center mx-auto">
        <ShieldCheck class="w-6 h-6" />
      </div>
      <h2 class="text-xl font-extrabold text-slate-900">Verifikasi Login Admin</h2>
      <p class="text-xs text-slate-500 leading-relaxed">
        Kami telah mengirimkan kode OTP 6-digit ke email admin
        <span class="font-bold text-slate-800">{{ email }}</span>
        melalui SMTP resmi.
      </p>
    </div>

    <div v-if="status" class="mb-4 text-xs font-semibold text-emerald-700 bg-emerald-50 border border-emerald-200 rounded-xl p-3 text-center">
      {{ status }}
    </div>

    <div v-if="!mailSent" class="mb-4 text-xs font-medium text-amber-800 bg-amber-50 border border-amber-200 rounded-xl p-3">
      Pengiriman email OTP tertunda. Pastikan konfigurasi SMTP Gmail (<code>MAIL_USERNAME</code> &amp; <code>MAIL_PASSWORD</code> App Password) di <code>.env</code> sudah aktif, lalu klik <strong>Kirim Ulang Kode</strong>.
    </div>

    <form @submit.prevent="submit" class="space-y-4">
      <div>
        <InputLabel for="code" value="Kode Verifikasi (6 Digit)" class="text-xs font-semibold text-slate-700" />
        <TextInput
          id="code"
          type="text"
          inputmode="numeric"
          maxlength="6"
          v-model="form.code"
          required
          autofocus
          placeholder="000000"
          class="mt-1 block w-full text-center text-2xl font-mono font-extrabold tracking-[0.4em] rounded-xl border-slate-200 focus:border-violet-600 focus:ring-violet-600"
        />
        <InputError class="mt-2 text-xs" :message="form.errors.code" />
      </div>

      <button
        type="submit"
        :disabled="form.processing"
        class="w-full py-3 px-4 rounded-xl text-sm font-bold text-white bg-violet-700 hover:bg-violet-800 shadow-invoicerapi-sm transition disabled:opacity-50"
      >
        {{ form.processing ? 'Memverifikasi...' : 'Verifikasi & Masuk Admin' }}
      </button>
    </form>

    <div class="mt-5 pt-4 border-t border-slate-100 flex items-center justify-between text-xs">
      <button
        type="button"
        @click="resendCode"
        class="inline-flex items-center gap-1.5 font-semibold text-violet-700 hover:text-violet-800"
      >
        <RefreshCw class="w-3.5 h-3.5" />
        <span>Kirim Ulang Kode</span>
      </button>

      <button
        type="button"
        @click="logout"
        class="inline-flex items-center gap-1.5 font-semibold text-slate-500 hover:text-red-600"
      >
        <LogOut class="w-3.5 h-3.5" />
        <span>Keluar</span>
      </button>
    </div>
  </GuestLayout>
</template>

