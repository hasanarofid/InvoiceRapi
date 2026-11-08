<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import SynbarraLogo from '@/Components/UI/SynbarraLogo.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import { 
  Building2, 
  Sparkles, 
  ShieldCheck, 
  ArrowRight,
  Camera,
  HeartHandshake,
  CalendarDays,
  Hammer,
  Briefcase
} from 'lucide-vue-next';

interface Props {
  user: {
    name: string;
    email: string;
    phone?: string;
  };
  existingTenant?: {
    name?: string;
    business_type?: string;
    phone_whatsapp?: string;
  };
}

const props = defineProps<Props>();

const form = useForm({
  business_name: props.existingTenant?.name || '',
  business_type: props.existingTenant?.business_type || 'wedding_organizer',
  phone_whatsapp: props.user.phone || props.existingTenant?.phone_whatsapp || '',
  city: '',
});

const businessCategories = [
  { id: 'wedding_organizer', name: 'Wedding Organizer (WO)', icon: HeartHandshake, desc: 'Kelola DP gedung, MUA, pelunasan hari H' },
  { id: 'photographer', name: 'Fotografi / Videografi', icon: Camera, desc: 'Booking fee, termin photoshoot, cetak album' },
  { id: 'event_organizer', name: 'Event Organizer (EO)', icon: CalendarDays, desc: 'Termin bertahap korporat & public event' },
  { id: 'contractor', name: 'Kontraktor & Interior', icon: Hammer, desc: 'Termin progres fisik 30%-40%-30%' },
  { id: 'other', name: 'Jasa Profesional Lainnya', icon: Briefcase, desc: 'Konsultan, freelancer, agensi digital' },
];

const submit = () => {
  form.post(route('onboarding.store'));
};
</script>

<template>
  <div class="min-h-screen bg-slate-50 text-slate-900 font-sans antialiased py-10 px-4 sm:px-6 flex flex-col justify-center items-center relative overflow-hidden">
    <!-- Ambient background light -->
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[700px] h-[350px] bg-gradient-to-b from-violet-200/50 to-transparent blur-3xl pointer-events-none"></div>

    <Head title="Setup Profil Bisnis - Synbarra InvoiceRapi" />

    <div class="w-full max-w-xl bg-white border border-slate-200 shadow-xl rounded-2xl p-6 sm:p-10 relative z-10 space-y-8">
      <!-- Header -->
      <div class="text-center space-y-2">
        <div class="flex justify-center mb-2">
          <SynbarraLogo size="md" variant="horizontal" />
        </div>
        <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">
          Lengkapi Data Bisnis Anda
        </h1>
        <p class="text-xs sm:text-sm text-slate-500 max-w-md mx-auto">
          Tinggal satu langkah lagi untuk mengaktifkan workspace dan masa uji coba 14 hari Paket Pro.
        </p>
      </div>

      <!-- Trial Offer Callout -->
      <div class="p-4 rounded-xl bg-gradient-to-r from-violet-50 to-purple-50 border border-violet-100 flex items-start gap-3">
        <div class="w-8 h-8 rounded-lg bg-violet-600 text-white flex items-center justify-center flex-shrink-0 mt-0.5 shadow-sm">
          <Sparkles class="w-4 h-4" />
        </div>
        <div class="text-xs">
          <p class="font-bold text-violet-900">Uji Coba Gratis 14 Hari Paket Pro Otomatis Aktif</p>
          <p class="text-violet-700 mt-0.5">Nikmati invoice unlimited, multi-termin pembayaran (DP & pelunasan), dan reminder otomatis tanpa biaya sepeser pun.</p>
        </div>
      </div>

      <!-- Onboarding Form -->
      <form @submit.prevent="submit" class="space-y-6">
        <!-- 1. Nama Bisnis -->
        <div>
          <InputLabel for="business_name" value="Nama Bisnis / Brand Jasa Anda *" class="text-xs font-bold text-slate-700" />
          <div class="relative mt-1">
            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
              <Building2 class="w-4 h-4" />
            </div>
            <TextInput
              id="business_name"
              type="text"
              class="pl-10 block w-full rounded-xl border-slate-200 focus:border-violet-600 focus:ring-violet-600"
              v-model="form.business_name"
              required
              autofocus
              placeholder="Contoh: Mulia Event Organizer / Artha Studio"
            />
          </div>
          <InputError class="mt-1.5 text-xs" :message="form.errors.business_name" />
          <p class="text-[11px] text-slate-400 mt-1">Nama ini akan dicetak di header invoice dan link publik klien Anda.</p>
        </div>

        <!-- 2. Kategori Bisnis -->
        <div>
          <InputLabel value="Kategori Industri Jasa *" class="text-xs font-bold text-slate-700 mb-2" />
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
            <label
              v-for="cat in businessCategories"
              :key="cat.id"
              :class="[
                'flex items-start gap-3 p-3 rounded-xl border cursor-pointer transition select-none',
                form.business_type === cat.id
                  ? 'border-violet-600 bg-violet-50/70 ring-1 ring-violet-600'
                  : 'border-slate-200 hover:border-slate-300 hover:bg-slate-50/50'
              ]"
            >
              <input
                type="radio"
                name="business_type"
                :value="cat.id"
                v-model="form.business_type"
                class="mt-1 text-violet-600 focus:ring-violet-500 border-slate-300"
              />
              <div class="flex-grow">
                <p class="text-xs font-bold text-slate-800">{{ cat.name }}</p>
                <p class="text-[10px] text-slate-500 mt-0.5 leading-tight">{{ cat.desc }}</p>
              </div>
            </label>
          </div>
          <InputError class="mt-1.5 text-xs" :message="form.errors.business_type" />
        </div>

        <!-- 3. Nomor WhatsApp (WAJIB untuk follow up) -->
        <div>
          <div class="flex items-center justify-between">
            <InputLabel for="phone_whatsapp" value="Nomor WhatsApp Aktif *" class="text-xs font-bold text-slate-700" />
            <span class="text-[11px] font-semibold text-emerald-600 flex items-center gap-1">
              <ShieldCheck class="w-3.5 h-3.5" />
              Verifikasi & Dukungan
            </span>
          </div>
          <div class="relative mt-1">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-xs font-bold text-slate-500 border-r border-slate-200 pr-2.5 my-2">
              🇮🇩 +62
            </div>
            <TextInput
              id="phone_whatsapp"
              type="tel"
              class="pl-20 block w-full rounded-xl border-slate-200 focus:border-violet-600 focus:ring-violet-600"
              v-model="form.phone_whatsapp"
              required
              placeholder="812-3456-7890"
            />
          </div>
          <InputError class="mt-1.5 text-xs" :message="form.errors.phone_whatsapp" />
          <p class="text-[11px] text-slate-500 mt-1">
            Wajib nomor WhatsApp aktif. Digunakan oleh tim support Synbarra untuk konfirmasi bantuan panduan awal dan reminder perpanjangan trial.
          </p>
        </div>

        <!-- 4. Kota Domisili -->
        <div>
          <InputLabel for="city" value="Kota / Domisili Usaha (Opsional)" class="text-xs font-semibold text-slate-700" />
          <TextInput
            id="city"
            type="text"
            class="mt-1 block w-full rounded-xl border-slate-200 focus:border-violet-600 focus:ring-violet-600"
            v-model="form.city"
            placeholder="Contoh: Surabaya, Jakarta Selatan, Bandung"
          />
          <InputError class="mt-1.5 text-xs" :message="form.errors.city" />
        </div>

        <!-- Submit Button -->
        <div class="pt-4">
          <button
            type="submit"
            class="w-full py-3.5 px-6 rounded-full text-sm font-bold text-white bg-violet-700 hover:bg-violet-800 shadow-md hover:shadow-lg transition-all duration-150 transform hover:-translate-y-0.5 flex items-center justify-center gap-2 disabled:opacity-50"
            :disabled="form.processing"
          >
            <span>{{ form.processing ? 'Menyimpan Workspace...' : 'Mulai Gunakan InvoiceRapi Sekarang' }}</span>
            <ArrowRight class="w-4 h-4" />
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

