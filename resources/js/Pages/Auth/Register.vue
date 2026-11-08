<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Daftar Akun Baru - Synbarra InvoiceRapi" />

        <div class="mb-6 text-center">
            <h2 class="text-xl font-extrabold text-slate-900 tracking-tight">
                Mulai Uji Coba Gratis 14 Hari
            </h2>
            <p class="text-xs text-slate-500 mt-1">
                Akses instan Paket Pro: invoice tanpa batas & fitur termin bertahap
            </p>
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
                <span>Daftar dengan Google</span>
            </a>
        </div>

        <!-- Divider -->
        <div class="relative flex items-center justify-center mb-6">
            <div class="border-t border-slate-200 w-full"></div>
            <span class="bg-white px-3 text-xs font-medium text-slate-400 uppercase tracking-wider absolute">atau email</span>
        </div>

        <form @submit.prevent="submit" class="space-y-4">
            <div>
                <InputLabel for="name" value="Nama Lengkap / Pemilik Bisnis" class="text-xs font-semibold text-slate-700" />
                <TextInput
                    id="name"
                    type="text"
                    class="mt-1 block w-full rounded-xl border-slate-200 focus:border-violet-600 focus:ring-violet-600"
                    v-model="form.name"
                    required
                    autofocus
                    autocomplete="name"
                    placeholder="Contoh: Rian Kurniawan"
                />
                <InputError class="mt-1 text-xs" :message="form.errors.name" />
            </div>

            <div>
                <InputLabel for="email" value="Alamat Email" class="text-xs font-semibold text-slate-700" />
                <TextInput
                    id="email"
                    type="email"
                    class="mt-1 block w-full rounded-xl border-slate-200 focus:border-violet-600 focus:ring-violet-600"
                    v-model="form.email"
                    required
                    autocomplete="username"
                    placeholder="nama@bisnisanda.com"
                />
                <InputError class="mt-1 text-xs" :message="form.errors.email" />
            </div>

            <div>
                <InputLabel for="password" value="Password" class="text-xs font-semibold text-slate-700" />
                <TextInput
                    id="password"
                    type="password"
                    class="mt-1 block w-full rounded-xl border-slate-200 focus:border-violet-600 focus:ring-violet-600"
                    v-model="form.password"
                    required
                    autocomplete="new-password"
                    placeholder="Minimal 8 karakter"
                />
                <InputError class="mt-1 text-xs" :message="form.errors.password" />
            </div>

            <div>
                <InputLabel for="password_confirmation" value="Konfirmasi Password" class="text-xs font-semibold text-slate-700" />
                <TextInput
                    id="password_confirmation"
                    type="password"
                    class="mt-1 block w-full rounded-xl border-slate-200 focus:border-violet-600 focus:ring-violet-600"
                    v-model="form.password_confirmation"
                    required
                    autocomplete="new-password"
                    placeholder="Ulangi password"
                />
                <InputError class="mt-1 text-xs" :message="form.errors.password_confirmation" />
            </div>

            <div class="pt-2">
                <button
                    type="submit"
                    class="w-full py-3 px-4 rounded-xl text-sm font-semibold text-white bg-violet-700 hover:bg-violet-800 shadow-md hover:shadow-lg transition duration-150 disabled:opacity-50"
                    :disabled="form.processing"
                >
                    {{ form.processing ? 'Mendaftarkan Akun...' : 'Lanjut ke Setup Bisnis' }}
                </button>
            </div>

            <div class="text-center text-xs text-slate-500 pt-2">
                Sudah memiliki akun?
                <Link :href="route('login')" class="font-bold text-violet-700 hover:underline ml-1">
                    Masuk di sini
                </Link>
            </div>
        </form>
    </GuestLayout>
</template>
