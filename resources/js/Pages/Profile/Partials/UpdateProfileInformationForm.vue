<script setup lang="ts">
import { Link, useForm, usePage } from '@inertiajs/vue3';
import { swalSuccess, swalError } from '@/utils/swal';

defineProps<{
  mustVerifyEmail?: boolean;
  status?: string;
}>();

const user = (usePage().props.auth as any).user;

const form = useForm({
  name: user.name,
  email: user.email,
});
</script>

<template>
  <section>
    <header>
      <h2 class="text-base font-black text-slate-900">Informasi Profil</h2>
      <p class="mt-1 text-sm text-slate-500">
        Perbarui nama tampilan dan alamat email akun Anda.
      </p>
    </header>

    <form
      @submit.prevent="form.patch(route('profile.update'), {
        onSuccess: () => swalSuccess('Profil berhasil diperbarui.'),
        onError: () => swalError('Gagal Memperbarui Profil', 'Periksa nama atau email Anda.'),
      })"
      class="mt-6 space-y-5"
    >
      <div>
        <label for="name" class="block text-xs font-bold text-slate-700 mb-1.5">Nama Lengkap</label>
        <input
          id="name"
          v-model="form.name"
          type="text"
          required
          autofocus
          autocomplete="name"
          class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-transparent"
        />
        <p v-if="form.errors.name" class="mt-1.5 text-xs text-red-600 font-medium">{{ form.errors.name }}</p>
      </div>

      <div>
        <label for="email" class="block text-xs font-bold text-slate-700 mb-1.5">Alamat Email</label>
        <input
          id="email"
          v-model="form.email"
          type="email"
          required
          autocomplete="username"
          class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-transparent"
        />
        <p v-if="form.errors.email" class="mt-1.5 text-xs text-red-600 font-medium">{{ form.errors.email }}</p>
      </div>

      <div v-if="mustVerifyEmail && user.email_verified_at === null" class="p-3.5 rounded-xl bg-amber-50 border border-amber-200 text-xs text-amber-900">
        Email Anda belum terverifikasi.
        <Link
          :href="route('verification.send')"
          method="post"
          as="button"
          class="font-bold underline hover:text-amber-700 ml-1"
        >
          Kirim ulang email verifikasi.
        </Link>
        <p v-show="status === 'verification-link-sent'" class="mt-1.5 font-semibold text-emerald-700">
          Tautan verifikasi baru telah dikirim ke email Anda.
        </p>
      </div>

      <div class="flex items-center gap-4 pt-1">
        <button
          type="submit"
          :disabled="form.processing"
          class="px-5 py-2.5 rounded-xl bg-violet-600 hover:bg-violet-700 disabled:opacity-50 text-white text-sm font-bold shadow-sm transition"
        >
          {{ form.processing ? 'Menyimpan...' : 'Simpan Perubahan' }}
        </button>

        <Transition
          enter-active-class="transition ease-in-out"
          enter-from-class="opacity-0"
          leave-active-class="transition ease-in-out"
          leave-to-class="opacity-0"
        >
          <p v-if="form.recentlySuccessful" class="text-xs font-bold text-emerald-600">
            Tersimpan.
          </p>
        </Transition>
      </div>
    </form>
  </section>
</template>
