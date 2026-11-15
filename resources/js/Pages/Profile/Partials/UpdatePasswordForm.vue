<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const passwordInput = ref<HTMLInputElement | null>(null);
const currentPasswordInput = ref<HTMLInputElement | null>(null);

const form = useForm({
  current_password: '',
  password: '',
  password_confirmation: '',
});

const updatePassword = () => {
  form.put(route('password.update'), {
    preserveScroll: true,
    onSuccess: () => form.reset(),
    onError: () => {
      if (form.errors.password) {
        form.reset('password', 'password_confirmation');
        passwordInput.value?.focus();
      }
      if (form.errors.current_password) {
        form.reset('current_password');
        currentPasswordInput.value?.focus();
      }
    },
  });
};
</script>

<template>
  <section>
    <header>
      <h2 class="text-base font-black text-slate-900">Ubah Kata Sandi</h2>
      <p class="mt-1 text-sm text-slate-500">
        Pastikan akun Anda menggunakan kata sandi yang panjang dan acak agar tetap aman.
      </p>
    </header>

    <form @submit.prevent="updatePassword" class="mt-6 space-y-5">
      <div>
        <label for="current_password" class="block text-xs font-bold text-slate-700 mb-1.5">Kata Sandi Saat Ini</label>
        <input
          id="current_password"
          ref="currentPasswordInput"
          v-model="form.current_password"
          type="password"
          autocomplete="current-password"
          class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-transparent"
        />
        <p v-if="form.errors.current_password" class="mt-1.5 text-xs text-red-600 font-medium">{{ form.errors.current_password }}</p>
      </div>

      <div>
        <label for="password" class="block text-xs font-bold text-slate-700 mb-1.5">Kata Sandi Baru</label>
        <input
          id="password"
          ref="passwordInput"
          v-model="form.password"
          type="password"
          autocomplete="new-password"
          class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-transparent"
        />
        <p v-if="form.errors.password" class="mt-1.5 text-xs text-red-600 font-medium">{{ form.errors.password }}</p>
      </div>

      <div>
        <label for="password_confirmation" class="block text-xs font-bold text-slate-700 mb-1.5">Konfirmasi Kata Sandi Baru</label>
        <input
          id="password_confirmation"
          v-model="form.password_confirmation"
          type="password"
          autocomplete="new-password"
          class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-transparent"
        />
        <p v-if="form.errors.password_confirmation" class="mt-1.5 text-xs text-red-600 font-medium">{{ form.errors.password_confirmation }}</p>
      </div>

      <div class="flex items-center gap-4 pt-1">
        <button
          type="submit"
          :disabled="form.processing"
          class="px-5 py-2.5 rounded-xl bg-violet-600 hover:bg-violet-700 disabled:opacity-50 text-white text-sm font-bold shadow-sm transition"
        >
          {{ form.processing ? 'Memperbarui...' : 'Perbarui Kata Sandi' }}
        </button>

        <Transition
          enter-active-class="transition ease-in-out"
          enter-from-class="opacity-0"
          leave-active-class="transition ease-in-out"
          leave-to-class="opacity-0"
        >
          <p v-if="form.recentlySuccessful" class="text-xs font-bold text-emerald-600">
            Kata sandi diperbarui.
          </p>
        </Transition>
      </div>
    </form>
  </section>
</template>
