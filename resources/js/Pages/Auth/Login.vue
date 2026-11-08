<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Masuk ke Akun - Synbarra InvoiceRapi" />

        <div class="mb-6 text-center">
            <h2 class="text-xl font-extrabold text-slate-900 tracking-tight">
                Selamat Datang Kembali
            </h2>
            <p class="text-xs text-slate-500 mt-1">
                Masuk untuk mengelola invoice, termin, dan piutang klien Anda
            </p>
        </div>

        <div v-if="status" class="mb-4 text-sm font-medium text-emerald-600 bg-emerald-50 p-3 rounded-lg border border-emerald-200 text-center">
            {{ status }}
        </div>

        <!-- Google OAuth Button -->
        <div class="mb-6">
            <a
                href="/auth/google"
                class="w-full flex items-center justify-center gap-3 px-4 py-2.5 border border-slate-300 rounded-xl bg-white hover:bg-slate-50 text-slate-700 text-sm font-semibold shadow-sm hover:shadow transition transform hover:-translate-y-0.5"
            >
                <svg class="w-5 h-5" viewBox="0 0 24 24">
                    <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" />
                    <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" />
                    <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z" />
                    <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z" />
                </svg>
                <span>Masuk dengan Google</span>
            </a>
        </div>

        <!-- Divider -->
        <div class="relative flex items-center justify-center mb-6">
            <div class="border-t border-slate-200 w-full"></div>
            <span class="bg-white px-3 text-xs font-medium text-slate-400 uppercase tracking-wider absolute">atau email</span>
        </div>

        <form @submit.prevent="submit">
            <div>
                <InputLabel for="email" value="Alamat Email" class="text-xs font-semibold text-slate-700" />
                <TextInput
                    id="email"
                    type="email"
                    class="mt-1 block w-full rounded-xl border-slate-200 focus:border-violet-600 focus:ring-violet-600"
                    v-model="form.email"
                    required
                    autofocus
                    autocomplete="username"
                    placeholder="nama@bisnisanda.com"
                />
                <InputError class="mt-2 text-xs" :message="form.errors.email" />
            </div>

            <div class="mt-4">
                <div class="flex items-center justify-between">
                    <InputLabel for="password" value="Password" class="text-xs font-semibold text-slate-700" />
                    <Link
                        v-if="canResetPassword"
                        :href="route('password.request')"
                        class="text-xs font-medium text-violet-600 hover:text-violet-700 hover:underline"
                    >
                        Lupa password?
                    </Link>
                </div>

                <TextInput
                    id="password"
                    type="password"
                    class="mt-1 block w-full rounded-xl border-slate-200 focus:border-violet-600 focus:ring-violet-600"
                    v-model="form.password"
                    required
                    autocomplete="current-password"
                    placeholder="••••••••"
                />
                <InputError class="mt-2 text-xs" :message="form.errors.password" />
            </div>

            <div class="mt-4 flex items-center justify-between">
                <label class="flex items-center cursor-pointer">
                    <Checkbox name="remember" v-model:checked="form.remember" class="text-violet-600 rounded border-slate-300 focus:ring-violet-500" />
                    <span class="ms-2 text-xs font-medium text-slate-600">Ingat saya</span>
                </label>
            </div>

            <div class="mt-6">
                <button
                    type="submit"
                    class="w-full py-3 px-4 rounded-xl text-sm font-semibold text-white bg-violet-700 hover:bg-violet-800 shadow-md hover:shadow-lg transition duration-150 disabled:opacity-50"
                    :disabled="form.processing"
                >
                    {{ form.processing ? 'Memproses...' : 'Masuk ke InvoiceRapi' }}
                </button>
            </div>

            <div class="mt-6 text-center text-xs text-slate-500">
                Belum punya akun?
                <Link :href="route('register')" class="font-bold text-violet-700 hover:underline ml-1">
                    Daftar & Coba Gratis 14 Hari
                </Link>
            </div>
        </form>
    </GuestLayout>
</template>
