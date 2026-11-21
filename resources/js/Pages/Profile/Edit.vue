<script setup lang="ts">
import { computed } from 'vue';
import { Head, usePage } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import WorkspaceLayout from '@/Layouts/WorkspaceLayout.vue';
import DeleteUserForm from './Partials/DeleteUserForm.vue';
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';
import BankAccountsForm, { type BankAccount } from './Partials/BankAccountsForm.vue';
import { UserCheck, ShieldCheck } from 'lucide-vue-next';

defineProps<{
  mustVerifyEmail?: boolean;
  status?: string;
  bankAccounts?: BankAccount[];
}>();

const page = usePage();
const user = computed(() => (page.props.auth as any)?.user);
const isAdmin = computed(() => user.value?.role === 'superadmin');
const layoutComponent = computed(() => (isAdmin.value ? AdminLayout : WorkspaceLayout));
</script>

<template>
  <Head title="Profil Akun — InvoiceRapi" />

  <component :is="layoutComponent" title="Profil Akun">
    <div class="max-w-4xl mx-auto space-y-6">
      <!-- Header -->
      <div class="bg-white rounded-2xl border border-slate-100 p-6 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-center gap-4">
          <div class="w-12 h-12 rounded-2xl bg-violet-100 text-violet-700 flex items-center justify-center font-black text-lg shrink-0">
            {{ user?.name?.charAt(0)?.toUpperCase() || 'U' }}
          </div>
          <div>
            <h1 class="text-xl font-black text-slate-900 tracking-tight">Profil &amp; Keamanan Akun</h1>
            <p class="text-sm text-slate-500 mt-0.5">Kelola informasi akun, kata sandi, dan preferensi keamanan Anda.</p>
          </div>
        </div>
        <span
          class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold border self-start sm:self-center"
          :class="isAdmin ? 'bg-violet-50 text-violet-700 border-violet-200' : 'bg-emerald-50 text-emerald-700 border-emerald-200'"
        >
          <ShieldCheck v-if="isAdmin" class="w-3.5 h-3.5" />
          <UserCheck v-else class="w-3.5 h-3.5" />
          {{ isAdmin ? 'Administrator' : 'Member Workspace' }}
        </span>
      </div>

      <!-- Bank Accounts (Member Workspace Only) -->
      <div v-if="!isAdmin" class="bg-white rounded-2xl border border-slate-100 p-6 sm:p-8 shadow-sm">
        <BankAccountsForm :bank-accounts="bankAccounts || []" />
      </div>

      <!-- Profile Info -->
      <div class="bg-white rounded-2xl border border-slate-100 p-6 sm:p-8 shadow-sm">
        <UpdateProfileInformationForm
          :must-verify-email="mustVerifyEmail"
          :status="status"
          class="max-w-xl"
        />
      </div>

      <!-- Password -->
      <div class="bg-white rounded-2xl border border-slate-100 p-6 sm:p-8 shadow-sm">
        <UpdatePasswordForm class="max-w-xl" />
      </div>

      <!-- Deactivate / Soft Delete Account -->
      <div class="bg-white rounded-2xl border border-red-100 p-6 sm:p-8 shadow-sm">
        <DeleteUserForm class="max-w-xl" />
      </div>
    </div>
  </component>
</template>
