<script setup lang="ts">
import { ref, computed } from 'vue';
import { Head, Link, router, useForm, usePage } from '@inertiajs/vue3';
import WorkspaceLayout from '@/Layouts/WorkspaceLayout.vue';
import { swalConfirm, swalError } from '@/utils/swal';
import { formatDateID } from '@/utils/format';
import { ShieldCheck, UserPlus, Pencil, Trash2, Lock, Crown, CheckCircle2, XCircle } from 'lucide-vue-next';

interface TeamMember {
  id: number;
  kode_user: string | null;
  name: string;
  email: string;
  phone: string | null;
  role: 'owner' | 'tenant_admin' | 'admin' | 'staff';
  created_at: string;
}

const props = defineProps<{
  members: TeamMember[];
  usage: {
    plan_name: string;
    users_count: number;
    max_users: number;
    is_subscription_locked?: boolean;
  } | null;
}>();

const page = usePage();
const currentUser = computed(() => (page.props.auth as any)?.user);
const isSubscriptionLocked = computed(() => Boolean(props.usage?.is_subscription_locked));
const userLimitReached = computed(() => (props.members?.length ?? 0) >= Number(props.usage?.max_users ?? 1));

const showModal = ref(false);
const editingMember = ref<TeamMember | null>(null);

const form = useForm({
  name: '',
  email: '',
  phone: '',
  role: 'staff' as 'admin' | 'staff',
  password: '',
});

const roleBadge = (role: string) => {
  if (role === 'owner' || role === 'tenant_admin') {
    return { label: 'Owner', cls: 'bg-violet-50 text-violet-700 border-violet-200' };
  }
  if (role === 'admin') {
    return { label: 'Admin', cls: 'bg-sky-50 text-sky-700 border-sky-200' };
  }
  return { label: 'Staff', cls: 'bg-emerald-50 text-emerald-700 border-emerald-200' };
};

const openCreate = () => {
  if (isSubscriptionLocked.value || userLimitReached.value) return;
  editingMember.value = null;
  form.reset();
  form.clearErrors();
  form.role = 'staff';
  showModal.value = true;
};

const openEdit = (member: TeamMember) => {
  if (isSubscriptionLocked.value) return;
  editingMember.value = member;
  form.clearErrors();
  form.name = member.name;
  form.email = member.email;
  form.phone = member.phone || '';
  form.role = member.role === 'admin' ? 'admin' : 'staff';
  form.password = '';
  showModal.value = true;
};

const submit = () => {
  const onError = () => swalError('Gagal Menyimpan', 'Periksa kembali data anggota tim.');
  if (editingMember.value) {
    form.put(route('team.update', editingMember.value.id), {
      onSuccess: () => { showModal.value = false; },
      onError,
    });
  } else {
    form.post(route('team.store'), {
      onSuccess: () => { showModal.value = false; form.reset(); },
      onError,
    });
  }
};

const deleteMember = async (member: TeamMember) => {
  const ok = await swalConfirm('Hapus Anggota Tim?', `Akun "${member.name}" (${member.email}) tidak akan bisa mengakses workspace ini lagi.`, 'Ya, Hapus');
  if (!ok) return;
  router.delete(route('team.destroy', member.id));
};

const isOwnerRow = (m: TeamMember) => m.role === 'owner' || m.role === 'tenant_admin' || m.id === currentUser.value?.id;
</script>

<template>
  <Head title="Tim & Role RBAC — InvoiceRapi" />

  <WorkspaceLayout title="Tim & Role">
    <div class="space-y-6">
      <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
          <h1 class="text-2xl font-black text-slate-900 tracking-tight flex items-center gap-2">
            <ShieldCheck class="w-6 h-6 text-violet-600" />
            Manajemen Tim &amp; Role (RBAC)
          </h1>
          <p class="text-sm text-slate-500 mt-0.5">
            Kelola akun Admin &amp; Staff untuk operasional bisnis Anda
            <span v-if="usage" class="ml-1 font-semibold text-violet-700">
              ({{ members.length }}/{{ usage.max_users }} akun terpakai pada Paket {{ usage.plan_name }})
            </span>
          </p>
        </div>

        <Link
          v-if="isSubscriptionLocked"
          :href="route('billing.index')"
          class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-amber-500 hover:bg-amber-600 text-white text-sm font-bold shadow-sm transition self-start"
        >
          <Lock class="w-4 h-4" />
          Aktifkan Paket
        </Link>
        <Link
          v-else-if="userLimitReached"
          :href="route('billing.index')"
          class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-amber-500 hover:bg-amber-600 text-white text-sm font-bold shadow-sm transition self-start"
        >
          <Crown class="w-4 h-4" />
          Kuota Pengguna Penuh — Upgrade Bisnis
        </Link>
        <button
          v-else
          @click="openCreate"
          class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-violet-600 hover:bg-violet-700 text-white text-sm font-bold shadow-sm transition self-start"
        >
          <UserPlus class="w-4 h-4" />
          Tambah Anggota Tim
        </button>
      </div>

      <!-- RBAC Role Permission Matrix -->
      <div class="bg-white rounded-2xl border border-violet-100 p-5 shadow-xs">
        <h2 class="text-xs font-black uppercase tracking-wider text-violet-700 mb-3">Hak Akses Role (RBAC)</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-xs">
          <div class="p-3.5 rounded-xl bg-violet-50/60 border border-violet-100 space-y-1.5">
            <p class="font-black text-violet-900">Owner</p>
            <p class="flex items-center gap-1.5 text-slate-700"><CheckCircle2 class="w-3.5 h-3.5 text-emerald-600 shrink-0" /> Akses penuh seluruh operasional</p>
            <p class="flex items-center gap-1.5 text-slate-700"><CheckCircle2 class="w-3.5 h-3.5 text-emerald-600 shrink-0" /> Kelola Tim, Role &amp; Langganan</p>
            <p class="flex items-center gap-1.5 text-slate-700"><CheckCircle2 class="w-3.5 h-3.5 text-emerald-600 shrink-0" /> Hapus Klien, Proyek &amp; Invoice</p>
          </div>
          <div class="p-3.5 rounded-xl bg-sky-50/60 border border-sky-100 space-y-1.5">
            <p class="font-black text-sky-900">Admin</p>
            <p class="flex items-center gap-1.5 text-slate-700"><CheckCircle2 class="w-3.5 h-3.5 text-emerald-600 shrink-0" /> Kelola Klien, Proyek, Item &amp; Invoice</p>
            <p class="flex items-center gap-1.5 text-slate-700"><CheckCircle2 class="w-3.5 h-3.5 text-emerald-600 shrink-0" /> Hapus data operasional</p>
            <p class="flex items-center gap-1.5 text-slate-500"><XCircle class="w-3.5 h-3.5 text-rose-500 shrink-0" /> Tidak kelola Tim &amp; Billing</p>
          </div>
          <div class="p-3.5 rounded-xl bg-emerald-50/60 border border-emerald-100 space-y-1.5">
            <p class="font-black text-emerald-900">Staff</p>
            <p class="flex items-center gap-1.5 text-slate-700"><CheckCircle2 class="w-3.5 h-3.5 text-emerald-600 shrink-0" /> Buat Invoice &amp; Catat Pembayaran</p>
            <p class="flex items-center gap-1.5 text-slate-700"><CheckCircle2 class="w-3.5 h-3.5 text-emerald-600 shrink-0" /> Tambah &amp; Edit Klien / Jasa</p>
            <p class="flex items-center gap-1.5 text-slate-500"><XCircle class="w-3.5 h-3.5 text-rose-500 shrink-0" /> Tidak bisa hapus data / akses Billing</p>
          </div>
        </div>
      </div>

      <!-- Members Table -->
      <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
          <table class="w-full text-left border-collapse">
            <thead>
              <tr class="border-b border-slate-100 bg-slate-50/70 text-[11px] font-bold uppercase tracking-wider text-slate-500">
                <th class="px-5 py-3.5">Kode &amp; Nama</th>
                <th class="px-5 py-3.5">Email</th>
                <th class="px-5 py-3.5">Role</th>
                <th class="px-5 py-3.5">Bergabung</th>
                <th class="px-5 py-3.5 text-right">Aksi</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-sm">
              <tr v-for="member in members" :key="member.id" class="hover:bg-slate-50/60 transition">
                <td class="px-5 py-4">
                  <div class="flex items-center gap-2">
                    <span class="font-bold text-slate-900">{{ member.name }}</span>
                    <span v-if="member.kode_user" class="px-2 py-0.5 rounded-md bg-violet-50 text-violet-700 font-mono text-[11px] font-bold">
                      {{ member.kode_user }}
                    </span>
                  </div>
                </td>
                <td class="px-5 py-4 text-xs text-slate-600">{{ member.email }}</td>
                <td class="px-5 py-4">
                  <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-bold border" :class="roleBadge(member.role).cls">
                    {{ roleBadge(member.role).label }}
                  </span>
                </td>
                <td class="px-5 py-4 text-xs text-slate-500">{{ formatDateID(member.created_at) }}</td>
                <td class="px-5 py-4 text-right space-x-2">
                  <template v-if="!isOwnerRow(member)">
                    <button
                      @click="openEdit(member)"
                      :disabled="isSubscriptionLocked"
                      class="p-2 rounded-lg text-slate-500 hover:text-violet-600 hover:bg-violet-50 disabled:opacity-40 transition"
                      title="Edit Role"
                    >
                      <Pencil class="w-4 h-4" />
                    </button>
                    <button
                      @click="deleteMember(member)"
                      :disabled="isSubscriptionLocked"
                      class="p-2 rounded-lg text-slate-500 hover:text-red-600 hover:bg-red-50 disabled:opacity-40 transition"
                      title="Hapus Anggota"
                    >
                      <Trash2 class="w-4 h-4" />
                    </button>
                  </template>
                  <span v-else class="text-[11px] font-semibold text-slate-400">Akun Utama</span>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- Modal Create / Edit Member -->
    <div v-if="showModal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-sm p-4">
      <div class="bg-white rounded-3xl max-w-md w-full p-6 shadow-2xl space-y-5">
        <div class="flex items-center justify-between">
          <h2 class="text-lg font-black text-slate-900">{{ editingMember ? 'Edit Anggota Tim' : 'Tambah Anggota Tim' }}</h2>
          <button @click="showModal = false" class="text-slate-400 hover:text-slate-700 text-sm font-bold">Tutup</button>
        </div>

        <form @submit.prevent="submit" class="space-y-4">
          <div>
            <label class="block text-xs font-bold text-slate-700 mb-1">Nama Lengkap *</label>
            <input v-model="form.name" type="text" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm" />
            <p v-if="form.errors.name" class="text-xs text-red-600 mt-1">{{ form.errors.name }}</p>
          </div>

          <div v-if="!editingMember">
            <label class="block text-xs font-bold text-slate-700 mb-1">Email Login *</label>
            <input v-model="form.email" type="email" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm" />
            <p v-if="form.errors.email" class="text-xs text-red-600 mt-1">{{ form.errors.email }}</p>
          </div>

          <div>
            <label class="block text-xs font-bold text-slate-700 mb-1">Role Akses *</label>
            <select v-model="form.role" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm bg-white">
              <option value="staff">Staff (Buat invoice, klien &amp; catat bayar — tidak bisa hapus data)</option>
              <option value="admin">Admin (Kelola &amp; hapus operasional — tidak akses tim/billing)</option>
            </select>
            <p v-if="form.errors.role" class="text-xs text-red-600 mt-1">{{ form.errors.role }}</p>
          </div>

          <div v-if="!editingMember">
            <label class="block text-xs font-bold text-slate-700 mb-1">Password Awal * (min. 8 karakter)</label>
            <input v-model="form.password" type="password" required minlength="8" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm" />
            <p v-if="form.errors.password" class="text-xs text-red-600 mt-1">{{ form.errors.password }}</p>
          </div>

          <div class="flex justify-end gap-2 pt-2">
            <button type="button" @click="showModal = false" class="px-4 py-2.5 rounded-xl border border-slate-200 text-sm font-bold text-slate-600">Batal</button>
            <button type="submit" :disabled="form.processing" class="px-5 py-2.5 rounded-xl bg-violet-600 hover:bg-violet-700 text-white text-sm font-bold">
              {{ form.processing ? 'Menyimpan...' : 'Simpan Anggota' }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </WorkspaceLayout>
</template>
