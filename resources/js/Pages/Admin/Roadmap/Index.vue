<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, router, useForm } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import { 
  CheckCircle2, 
  Clock, 
  AlertCircle, 
  Plus, 
  Filter, 
  CheckSquare, 
  Square, 
  Trash2, 
  Edit3, 
  ChevronRight, 
  ChevronDown,
  Code,
  Palette,
  Briefcase,
  Users,
  Target,
  Sparkles,
  ExternalLink,
  MessageSquare
} from '@lucide/vue';

const props = defineProps({
  tasks: {
    type: Array,
    default: () => [],
  },
  metrics: {
    type: Object,
    default: () => ({
      total: 0,
      completed: 0,
      in_progress: 0,
      pending: 0,
      percentage: 0,
      role_stats: {},
    }),
  },
  filters: {
    type: Object,
    default: () => ({}),
  },
});

// Filters
const selectedRole = ref(props.filters.role || 'all');
const selectedStatus = ref(props.filters.status || 'all');
const selectedPhase = ref(props.filters.phase || 'all');

const applyFilter = () => {
  router.get(route('admin.roadmap.index'), {
    role: selectedRole.value,
    status: selectedStatus.value,
    phase: selectedPhase.value,
  }, {
    preserveState: true,
    preserveScroll: true,
  });
};

// Toggle status quick action
const toggleTaskStatus = (task) => {
  const newStatus = task.status === 'completed' ? 'pending' : 'completed';
  router.patch(route('admin.roadmap.update-status', task.id), {
    status: newStatus,
  }, {
    preserveScroll: true,
  });
};

const changeStatus = (task, newStatus) => {
  router.patch(route('admin.roadmap.update-status', task.id), {
    status: newStatus,
  }, {
    preserveScroll: true,
  });
};

// Modal Add/Edit
const isModalOpen = ref(false);
const isEditing = ref(false);
const editingTaskId = ref(null);

const form = useForm({
  phase_number: 1,
  phase_title: 'Tahap 1: Fondasi Konversi & Materi Presentasi',
  target_days: 'Hari 1 – 5',
  task_code: '',
  role: 'programmer',
  title: '',
  description: '',
  deliverable: '',
  status: 'pending',
  notes: '',
});

const openCreateModal = () => {
  isEditing.value = false;
  editingTaskId.value = null;
  form.reset();
  form.phase_number = 1;
  form.phase_title = 'Tahap 1: Fondasi Konversi & Materi Presentasi';
  form.target_days = 'Hari 1 – 5';
  form.role = 'programmer';
  form.status = 'pending';
  isModalOpen.value = true;
};

const openEditModal = (task) => {
  isEditing.value = true;
  editingTaskId.value = task.id;
  form.phase_number = task.phase_number;
  form.phase_title = task.phase_title;
  form.target_days = task.target_days;
  form.task_code = task.task_code;
  form.role = task.role;
  form.title = task.title;
  form.description = task.description || '';
  form.deliverable = task.deliverable || '';
  form.status = task.status;
  form.notes = task.notes || '';
  isModalOpen.value = true;
};

const onPhaseChange = () => {
  const phaseMap = {
    1: { title: 'Tahap 1: Fondasi Konversi & Materi Presentasi', days: 'Hari 1 – 5' },
    2: { title: 'Tahap 2: Reposisi Layanan & Portfolio Berbobot', days: 'Hari 6 – 10' },
    3: { title: 'Tahap 3: Lead Management & 50 Database Prospek', days: 'Hari 11 – 15' },
    4: { title: 'Tahap 4: Eksekusi Outreach & Discovery Meeting', days: 'Hari 16 – 22' },
    5: { title: 'Tahap 5: Proposal, Kontrak & Closing Klien Pertama', days: 'Hari 23 – 30' },
  };
  const selected = phaseMap[form.phase_number];
  if (selected) {
    form.phase_title = selected.title;
    form.target_days = selected.days;
  }
};

const submitForm = () => {
  if (isEditing.value) {
    form.put(route('admin.roadmap.update', editingTaskId.value), {
      onSuccess: () => {
        isModalOpen.value = false;
        form.reset();
      },
    });
  } else {
    form.post(route('admin.roadmap.store'), {
      onSuccess: () => {
        isModalOpen.value = false;
        form.reset();
      },
    });
  }
};

const deleteTask = (task) => {
  if (confirm(`Yakin ingin menghapus ${task.task_code} - ${task.title}?`)) {
    router.delete(route('admin.roadmap.destroy', task.id), {
      preserveScroll: true,
    });
  }
};

// Grouping tasks by Phase
const groupedPhases = computed(() => {
  const groups = {};
  props.tasks.forEach(task => {
    if (!groups[task.phase_number]) {
      groups[task.phase_number] = {
        number: task.phase_number,
        title: task.phase_title,
        target_days: task.target_days,
        tasks: [],
      };
    }
    groups[task.phase_number].tasks.push(task);
  });
  return Object.values(groups).sort((a, b) => a.number - b.number);
});

// Role Helpers
const getRoleBadge = (role) => {
  switch (role) {
    case 'programmer':
      return { label: 'Programmer (Hasan)', icon: Code, class: 'bg-blue-50 text-blue-700 border-blue-200' };
    case 'desainer':
      return { label: 'Desainer UI/UX', icon: Palette, class: 'bg-purple-50 text-purple-700 border-purple-200' };
    case 'analis':
      return { label: 'Analis & Promosi', icon: Briefcase, class: 'bg-amber-50 text-amber-700 border-amber-200' };
    case 'tim':
    default:
      return { label: 'Kolaborasi Tim', icon: Users, class: 'bg-teal-50 text-teal-700 border-teal-200' };
  }
};
</script>

<template>
  <Head title="Roadmap Pengembangan & Akuisisi 30 Hari — Synbarra Technologies" />

  <AdminLayout>
    <div class="space-y-6 sm:space-y-8">
      <!-- Header Banner -->
      <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 bg-white p-6 sm:p-8 rounded-2xl border border-slate-200/80 shadow-xs">
        <div>
          <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold bg-[#00F1DE]/10 text-[#01232B] border border-[#00F1DE]/30">
            <span class="w-2 h-2 rounded-full bg-[#00F1DE] animate-pulse"></span>
            ROADMAP EKSEKUSI 30 HARI
          </div>
          <h2 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-[#01232B] mt-2">
            Sprint Pengembangan & Akuisisi Klien
          </h2>
          <p class="text-xs sm:text-sm text-slate-500 mt-1 max-w-2xl">
            Checklist modular terbagi untuk <strong>Programmer</strong>, <strong>Desainer</strong>, dan <strong>Analis & Promosi</strong> menuju akuisisi 1–2 klien pertama.
          </p>
        </div>
        <div class="flex items-center gap-3">
          <button
            @click="openCreateModal"
            class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-[#01232B] hover:bg-[#011E26] text-white font-medium text-sm transition-all duration-200 shadow-sm"
          >
            <Plus class="w-4 h-4 text-[#00F1DE]" />
            Tambah Task Baru
          </button>
        </div>
      </div>

      <!-- Overall Progress & Metrics Card -->
      <div class="grid grid-cols-1 md:grid-cols-4 gap-4 sm:gap-6">
        <!-- Main Progress Bar -->
        <div class="md:col-span-2 bg-gradient-to-br from-[#01232B] to-[#0D2329] p-6 rounded-2xl text-white shadow-sm flex flex-col justify-between">
          <div>
            <div class="flex items-center justify-between text-xs text-slate-300">
              <span class="font-semibold uppercase tracking-wider text-[#00F1DE]">Progres Keseluruhan</span>
              <span class="font-bold text-lg text-white">{{ metrics.percentage }}%</span>
            </div>
            <div class="w-full bg-white/10 rounded-full h-3 mt-3 overflow-hidden">
              <div 
                class="bg-gradient-to-r from-[#00F1DE] to-[#04D2C5] h-3 rounded-full transition-all duration-500"
                :style="{ width: `${metrics.percentage}%` }"
              ></div>
            </div>
          </div>
          <div class="mt-4 pt-4 border-t border-white/10 flex items-center justify-between text-xs text-slate-300">
            <div><strong>{{ metrics.completed }}</strong> dari <strong>{{ metrics.total }}</strong> task selesai</div>
            <div class="flex items-center gap-3">
              <span class="inline-flex items-center gap-1 text-emerald-400">
                <CheckCircle2 class="w-3.5 h-3.5" /> {{ metrics.completed }} Done
              </span>
              <span class="inline-flex items-center gap-1 text-amber-400">
                <Clock class="w-3.5 h-3.5" /> {{ metrics.pending }} Pending
              </span>
            </div>
          </div>
        </div>

        <!-- Role Stats 1: Programmer & Desainer -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex flex-col justify-between">
          <div class="space-y-4">
            <div>
              <div class="flex items-center justify-between text-xs mb-1">
                <span class="font-bold text-slate-700 flex items-center gap-1.5">
                  <Code class="w-3.5 h-3.5 text-blue-600" /> Programmer
                </span>
                <span class="font-semibold text-slate-500">
                  {{ metrics.role_stats?.programmer?.completed || 0 }} / {{ metrics.role_stats?.programmer?.total || 0 }}
                </span>
              </div>
              <div class="w-full bg-slate-100 rounded-full h-2 overflow-hidden">
                <div 
                  class="bg-blue-600 h-2 rounded-full transition-all"
                  :style="{ width: `${metrics.role_stats?.programmer?.total ? (metrics.role_stats.programmer.completed / metrics.role_stats.programmer.total) * 100 : 0}%` }"
                ></div>
              </div>
            </div>

            <div>
              <div class="flex items-center justify-between text-xs mb-1">
                <span class="font-bold text-slate-700 flex items-center gap-1.5">
                  <Palette class="w-3.5 h-3.5 text-purple-600" /> Desainer
                </span>
                <span class="font-semibold text-slate-500">
                  {{ metrics.role_stats?.desainer?.completed || 0 }} / {{ metrics.role_stats?.desainer?.total || 0 }}
                </span>
              </div>
              <div class="w-full bg-slate-100 rounded-full h-2 overflow-hidden">
                <div 
                  class="bg-purple-600 h-2 rounded-full transition-all"
                  :style="{ width: `${metrics.role_stats?.desainer?.total ? (metrics.role_stats.desainer.completed / metrics.role_stats.desainer.total) * 100 : 0}%` }"
                ></div>
              </div>
            </div>
          </div>
          <div class="text-[11px] text-slate-400 mt-2">Teknis & Aset Visual</div>
        </div>

        <!-- Role Stats 2: Analis & Tim -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex flex-col justify-between">
          <div class="space-y-4">
            <div>
              <div class="flex items-center justify-between text-xs mb-1">
                <span class="font-bold text-slate-700 flex items-center gap-1.5">
                  <Briefcase class="w-3.5 h-3.5 text-amber-600" /> Analis & Promosi
                </span>
                <span class="font-semibold text-slate-500">
                  {{ metrics.role_stats?.analis?.completed || 0 }} / {{ metrics.role_stats?.analis?.total || 0 }}
                </span>
              </div>
              <div class="w-full bg-slate-100 rounded-full h-2 overflow-hidden">
                <div 
                  class="bg-amber-600 h-2 rounded-full transition-all"
                  :style="{ width: `${metrics.role_stats?.analis?.total ? (metrics.role_stats.analis.completed / metrics.role_stats.analis.total) * 100 : 0}%` }"
                ></div>
              </div>
            </div>

            <div>
              <div class="flex items-center justify-between text-xs mb-1">
                <span class="font-bold text-slate-700 flex items-center gap-1.5">
                  <Users class="w-3.5 h-3.5 text-teal-600" /> Kolaborasi Tim
                </span>
                <span class="font-semibold text-slate-500">
                  {{ metrics.role_stats?.tim?.completed || 0 }} / {{ metrics.role_stats?.tim?.total || 0 }}
                </span>
              </div>
              <div class="w-full bg-slate-100 rounded-full h-2 overflow-hidden">
                <div 
                  class="bg-teal-600 h-2 rounded-full transition-all"
                  :style="{ width: `${metrics.role_stats?.tim?.total ? (metrics.role_stats.tim.completed / metrics.role_stats.tim.total) * 100 : 0}%` }"
                ></div>
              </div>
            </div>
          </div>
          <div class="text-[11px] text-slate-400 mt-2">Akuisisi Klien & Pitching</div>
        </div>
      </div>

      <!-- Filter Bar -->
      <div class="bg-white p-4 rounded-xl border border-slate-200/80 shadow-xs flex flex-wrap items-center justify-between gap-3">
        <div class="flex flex-wrap items-center gap-2">
          <span class="text-xs font-bold text-slate-400 uppercase tracking-wider mr-1">Filter Peran:</span>
          <button
            v-for="role in [
              { id: 'all', label: 'Semua' },
              { id: 'programmer', label: 'Programmer' },
              { id: 'desainer', label: 'Desainer' },
              { id: 'analis', label: 'Analis & Promosi' },
              { id: 'tim', label: 'Kolaborasi Tim' }
            ]"
            :key="role.id"
            @click="selectedRole = role.id; applyFilter()"
            :class="[
              selectedRole === role.id 
                ? 'bg-[#01232B] text-[#00F1DE] font-bold shadow-xs' 
                : 'bg-slate-100 text-slate-600 hover:bg-slate-200 font-medium',
              'px-3 py-1.5 rounded-lg text-xs transition-colors'
            ]"
          >
            {{ role.label }}
          </button>
        </div>

        <div class="flex items-center gap-2">
          <select
            v-model="selectedStatus"
            @change="applyFilter"
            class="text-xs bg-slate-50 border border-slate-200 rounded-lg px-3 py-1.5 text-slate-700 focus:outline-none focus:ring-1 focus:ring-[#00F1DE]"
          >
            <option value="all">Semua Status</option>
            <option value="pending">Belum Selesai</option>
            <option value="in_progress">Sedang Dikerjakan</option>
            <option value="completed">Sudah Selesai</option>
          </select>
        </div>
      </div>

      <!-- Task List by Phase -->
      <div class="space-y-6">
        <div 
          v-for="phase in groupedPhases" 
          :key="phase.number"
          class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden"
        >
          <!-- Phase Header -->
          <div class="bg-slate-50/80 px-6 py-4 border-b border-slate-200/80 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
            <div>
              <div class="inline-flex items-center gap-2 text-xs font-bold text-[#04D2C5] uppercase tracking-wider">
                <span class="px-2 py-0.5 bg-[#00F1DE]/20 text-[#01232B] rounded-md font-mono">{{ phase.target_days }}</span>
              </div>
              <h3 class="text-base sm:text-lg font-extrabold text-[#01232B] mt-1">
                {{ phase.title }}
              </h3>
            </div>
            <div class="text-xs text-slate-500 font-medium">
              {{ phase.tasks.filter(t => t.status === 'completed').length }} / {{ phase.tasks.length }} Selesai
            </div>
          </div>

          <!-- Tasks List Table / Cards -->
          <div class="divide-y divide-slate-100">
            <div 
              v-for="task in phase.tasks" 
              :key="task.id"
              :class="[
                task.status === 'completed' ? 'bg-slate-50/40' : 'bg-white',
                'p-5 sm:p-6 transition-colors hover:bg-slate-50/80 flex flex-col md:flex-row md:items-start gap-4'
              ]"
            >
              <!-- Checkbox Toggle -->
              <button 
                @click="toggleTaskStatus(task)" 
                class="mt-1 text-slate-400 hover:text-emerald-600 transition-colors shrink-0"
                title="Tandai Selesai / Belum Selesai"
              >
                <CheckCircle2 v-if="task.status === 'completed'" class="w-6 h-6 text-emerald-500 fill-emerald-100" />
                <Square v-else class="w-6 h-6 text-slate-300 hover:text-slate-500" />
              </button>

              <!-- Main Task Content -->
              <div class="flex-1 min-w-0">
                <div class="flex flex-wrap items-center gap-2">
                  <span class="font-mono text-xs font-bold px-2 py-0.5 rounded-md bg-slate-100 text-slate-700">
                    {{ task.task_code }}
                  </span>
                  
                  <!-- Role Badge -->
                  <span 
                    :class="[
                      getRoleBadge(task.role).class,
                      'inline-flex items-center gap-1 text-[11px] font-semibold px-2.5 py-0.5 rounded-full border'
                    ]"
                  >
                    <component :is="getRoleBadge(task.role).icon" class="w-3 h-3" />
                    {{ getRoleBadge(task.role).label }}
                  </span>

                  <!-- Status Selector -->
                  <select
                    :value="task.status"
                    @change="changeStatus(task, $event.target.value)"
                    :class="[
                      task.status === 'completed' ? 'bg-emerald-50 text-emerald-700 border-emerald-200' :
                      task.status === 'in_progress' ? 'bg-amber-50 text-amber-700 border-amber-200' :
                      'bg-slate-100 text-slate-600 border-slate-200',
                      'text-xs font-semibold rounded-md px-2 py-0.5 border focus:outline-none'
                    ]"
                  >
                    <option value="pending">⏳ Pending</option>
                    <option value="in_progress">⚡ Sedang Dikerjakan</option>
                    <option value="completed">✅ Selesai</option>
                  </select>
                </div>

                <h4 
                  :class="[
                    task.status === 'completed' ? 'line-through text-slate-400' : 'text-slate-900',
                    'font-bold text-base mt-2'
                  ]"
                >
                  {{ task.title }}
                </h4>

                <p v-if="task.description" class="text-xs sm:text-sm text-slate-600 mt-1.5 leading-relaxed">
                  {{ task.description }}
                </p>

                <!-- Output Deliverable -->
                <div v-if="task.deliverable" class="mt-3 inline-flex items-center gap-1.5 text-xs text-[#01232B] bg-[#00F1DE]/10 px-3 py-1 rounded-lg border border-[#00F1DE]/20 font-medium">
                  <Sparkles class="w-3.5 h-3.5 text-[#04D2C5]" />
                  <span><strong>Output:</strong> {{ task.deliverable }}</span>
                </div>

                <!-- Notes / Update info -->
                <div v-if="task.notes" class="mt-2 text-xs text-slate-500 bg-slate-50 p-2.5 rounded-lg border border-slate-200 flex items-start gap-2">
                  <MessageSquare class="w-3.5 h-3.5 text-slate-400 mt-0.5 shrink-0" />
                  <div><strong>Catatan Tim:</strong> {{ task.notes }}</div>
                </div>
              </div>

              <!-- Action buttons -->
              <div class="flex items-center gap-1 self-end md:self-start shrink-0">
                <button
                  @click="openEditModal(task)"
                  class="p-1.5 text-slate-400 hover:text-slate-700 rounded-lg hover:bg-slate-100 transition-colors"
                  title="Edit Task"
                >
                  <Edit3 class="w-4 h-4" />
                </button>
                <button
                  @click="deleteTask(task)"
                  class="p-1.5 text-slate-400 hover:text-red-600 rounded-lg hover:bg-red-50 transition-colors"
                  title="Hapus Task"
                >
                  <Trash2 class="w-4 h-4" />
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal Form (Tambah / Edit Task) -->
    <div v-if="isModalOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-xs">
      <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-xl border border-slate-200 max-h-[90vh] overflow-y-auto">
        <h3 class="text-lg font-bold text-[#01232B]">
          {{ isEditing ? 'Edit Task Roadmap' : 'Tambah Task Roadmap Baru' }}
        </h3>
        
        <form @submit.prevent="submitForm" class="space-y-4 mt-4">
          <div class="grid grid-cols-2 gap-3">
            <div>
              <label class="block text-xs font-semibold text-slate-700 mb-1">Tahap (Sprint)</label>
              <select
                v-model="form.phase_number"
                @change="onPhaseChange"
                class="w-full text-xs rounded-xl border-slate-200 focus:border-[#00F1DE] focus:ring-[#00F1DE]"
              >
                <option :value="1">Tahap 1 (Hari 1 – 5)</option>
                <option :value="2">Tahap 2 (Hari 6 – 10)</option>
                <option :value="3">Tahap 3 (Hari 11 – 15)</option>
                <option :value="4">Tahap 4 (Hari 16 – 22)</option>
                <option :value="5">Tahap 5 (Hari 23 – 30)</option>
              </select>
            </div>
            <div>
              <label class="block text-xs font-semibold text-slate-700 mb-1">Kode Task</label>
              <input
                v-model="form.task_code"
                placeholder="Contoh: Task 1.6"
                required
                class="w-full text-xs rounded-xl border-slate-200 focus:border-[#00F1DE] focus:ring-[#00F1DE]"
              />
            </div>
          </div>

          <div class="grid grid-cols-2 gap-3">
            <div>
              <label class="block text-xs font-semibold text-slate-700 mb-1">Penanggung Jawab (Role)</label>
              <select
                v-model="form.role"
                class="w-full text-xs rounded-xl border-slate-200 focus:border-[#00F1DE] focus:ring-[#00F1DE]"
              >
                <option value="programmer">Programmer (Hasan)</option>
                <option value="desainer">Desainer UI/UX</option>
                <option value="analis">Analis & Promosi</option>
                <option value="tim">Kolaborasi Tim</option>
              </select>
            </div>
            <div>
              <label class="block text-xs font-semibold text-slate-700 mb-1">Status</label>
              <select
                v-model="form.status"
                class="w-full text-xs rounded-xl border-slate-200 focus:border-[#00F1DE] focus:ring-[#00F1DE]"
              >
                <option value="pending">Pending</option>
                <option value="in_progress">Sedang Dikerjakan</option>
                <option value="completed">Selesai</option>
              </select>
            </div>
          </div>

          <div>
            <label class="block text-xs font-semibold text-slate-700 mb-1">Judul Task</label>
            <input
              v-model="form.title"
              placeholder="Judul aktivitas"
              required
              class="w-full text-xs rounded-xl border-slate-200 focus:border-[#00F1DE] focus:ring-[#00F1DE]"
            />
          </div>

          <div>
            <label class="block text-xs font-semibold text-slate-700 mb-1">Deskripsi & Alur Kerja</label>
            <textarea
              v-model="form.description"
              rows="2"
              placeholder="Penjelasan detail apa yang perlu dilakukan..."
              class="w-full text-xs rounded-xl border-slate-200 focus:border-[#00F1DE] focus:ring-[#00F1DE]"
            ></textarea>
          </div>

          <div>
            <label class="block text-xs font-semibold text-slate-700 mb-1">Output / Deliverable</label>
            <input
              v-model="form.deliverable"
              placeholder="Contoh: File PDF 1 Lembar / Skema DB"
              class="w-full text-xs rounded-xl border-slate-200 focus:border-[#00F1DE] focus:ring-[#00F1DE]"
            />
          </div>

          <div>
            <label class="block text-xs font-semibold text-slate-700 mb-1">Catatan Tambahan (Opsional)</label>
            <textarea
              v-model="form.notes"
              rows="2"
              placeholder="Link dokumen, catatan progres tim, kendala..."
              class="w-full text-xs rounded-xl border-slate-200 focus:border-[#00F1DE] focus:ring-[#00F1DE]"
            ></textarea>
          </div>

          <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-100">
            <button
              type="button"
              @click="isModalOpen = false"
              class="px-4 py-2 text-xs font-medium text-slate-600 hover:bg-slate-100 rounded-xl"
            >
              Batal
            </button>
            <button
              type="submit"
              :disabled="form.processing"
              class="px-4 py-2 text-xs font-semibold text-white bg-[#01232B] hover:bg-[#011E26] rounded-xl shadow-xs"
            >
              {{ isEditing ? 'Simpan Perubahan' : 'Buat Task' }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </AdminLayout>
</template>
