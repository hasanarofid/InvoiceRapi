<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ShieldCheck, Lock, Mail, ArrowRight } from 'lucide-vue-next';

defineProps<{
  status?: string;
}>();

const form = useForm({
  email: '',
  password: '',
  remember: false,
});

const submit = () => {
  form.post(route('admin.login.store'), {
    onFinish: () => form.reset('password'),
  });
};
</script>

<template>
  <Head title="Admin Portal Login — InvoiceRapi" />

  <div class="min-h-screen bg-slate-950 flex flex-col justify-center items-center p-6 relative overflow-hidden">
    <!-- Subtle background glow -->
    <div class="absolute inset-0 pointer-events-none">
      <div class="absolute top-1/4 left-1/2 -translate-x-1/2 w-[500px] h-[500px] bg-violet-600/15 rounded-full blur-3xl"></div>
    </div>

    <div class="w-full max-w-md relative z-10">
      <div class="text-center mb-8">
        <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-violet-600/20 border border-violet-500/30 text-violet-400 mb-4">
          <ShieldCheck class="w-7 h-7" />
        </div>
        <h1 class="text-2xl font-black text-white tracking-tight">Admin Portal InvoiceRapi</h1>
        <p class="text-sm text-slate-400 mt-1">Akses khusus Administrator Synbarra (dilindungi verifikasi OTP)</p>
      </div>

      <div class="bg-slate-900/90 backdrop-blur-xl border border-slate-800 rounded-3xl p-8 shadow-2xl">
        <div v-if="status" class="mb-4 p-3 rounded-xl bg-emerald-500/10 border border-emerald-500/30 text-xs font-medium text-emerald-300">
          {{ status }}
        </div>

        <form @submit.prevent="submit" class="space-y-5">
          <div>
            <label for="email" class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">
              Email Administrator
            </label>
            <div class="relative">
              <Mail class="w-4 h-4 text-slate-500 absolute left-3.5 top-1/2 -translate-y-1/2" />
              <input
                id="email"
                v-model="form.email"
                type="email"
                required
                autofocus
                autocomplete="username"
                placeholder="admin@synbarra.com"
                class="w-full pl-10 pr-4 py-3 bg-slate-950/80 border border-slate-800 rounded-xl text-sm text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-transparent"
              />
            </div>
            <p v-if="form.errors.email" class="mt-1.5 text-xs text-red-400 font-medium">{{ form.errors.email }}</p>
          </div>

          <div>
            <label for="password" class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">
              Password
            </label>
            <div class="relative">
              <Lock class="w-4 h-4 text-slate-500 absolute left-3.5 top-1/2 -translate-y-1/2" />
              <input
                id="password"
                v-model="form.password"
                type="password"
                required
                autocomplete="current-password"
                placeholder="••••••••"
                class="w-full pl-10 pr-4 py-3 bg-slate-950/80 border border-slate-800 rounded-xl text-sm text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-transparent"
              />
            </div>
            <p v-if="form.errors.password" class="mt-1.5 text-xs text-red-400 font-medium">{{ form.errors.password }}</p>
          </div>

          <div class="flex items-center justify-between text-xs">
            <label class="inline-flex items-center gap-2 text-slate-400 cursor-pointer">
              <input
                v-model="form.remember"
                type="checkbox"
                class="rounded bg-slate-950 border-slate-700 text-violet-600 focus:ring-violet-500"
              />
              <span>Ingat perangkat ini</span>
            </label>
          </div>

          <button
            type="submit"
            :disabled="form.processing"
            class="w-full py-3 px-4 rounded-xl bg-violet-600 hover:bg-violet-500 disabled:opacity-50 text-white font-bold text-sm transition flex items-center justify-center gap-2 shadow-lg shadow-violet-600/25"
          >
            <span>{{ form.processing ? 'Memverifikasi...' : 'Masuk ke Panel Admin' }}</span>
            <ArrowRight class="w-4 h-4" />
          </button>
        </form>
      </div>

      <div class="text-center mt-6">
        <Link :href="route('login')" class="text-xs text-slate-400 hover:text-violet-400 transition">
          Bukan administrator? Masuk melalui <span class="font-semibold underline">Portal Member</span>
        </Link>
      </div>
    </div>
  </div>
</template>

