<script setup lang="ts">
import { useForm, usePage } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import Container from '@/Components/UI/Container.vue';
import { BRAND } from '@/config/brand';
import { Mail, Globe, Clock, CheckCircle2, AlertCircle, Smartphone, Send } from '@lucide/vue';

const page = usePage();

const form = useForm({
  fullName: '',
  companyName: '',
  email: '',
  whatsappNumber: '',
  serviceRequired: 'Website Company Profile',
  projectDescription: '',
});

const serviceOptions = [
  'Website Company Profile',
  'Aplikasi Mobile (Android & iOS)',
  'Sistem Bisnis / ERP',
  'Sistem CRM',
  'Sistem HRIS & Payroll',
  'Integrasi Sistem (API)',
  'UI/UX Design',
  'Maintenance & Support',
  'Custom Software & Pengembangan Khusus',
];

const submit = () => {
  form.post('/contact', {
    preserveScroll: true,
    onSuccess: () => {
      form.reset();
    },
  });
};
</script>

<template>
  <AppLayout 
    title="Hubungi Kami & Konsultasi Sistem | Synbarra Technologies"
    description="Diskusikan ide proyek atau tantangan operasional bisnis Anda bersama tim engineer Synbarra Technologies. Tersedia konsultasi langsung via WhatsApp atau formulir brief proyek."
  >
    <!-- Header Banner -->
    <section class="bg-gradient-to-b from-[#EBF9F7] via-[#F4FCFA] to-white py-16 lg:py-24 relative overflow-hidden">
      <!-- Ambient Mint Glow -->
      <div class="absolute top-0 right-1/4 w-96 h-96 bg-[#00F1DE]/15 rounded-full blur-[130px] pointer-events-none"></div>

      <Container class="relative z-10 text-center max-w-3xl mx-auto">
        <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-white border border-[#A7EADF] text-[#008F7A] text-xs font-semibold tracking-wider uppercase mb-5 shadow-xs">
          <span class="w-2 h-2 rounded-full bg-[#00BFA5] animate-pulse"></span>
          Konsultasi Proyek & Sistem
        </div>

        <h1 class="text-3xl sm:text-5xl font-black text-[#0D2329] tracking-tight leading-tight">
          YUK, DISKUSIKAN KEBUTUHAN ANDA!
        </h1>

        <div class="w-16 h-1 bg-[#00BFA5] rounded-full mx-auto my-5"></div>

        <p class="text-base sm:text-lg text-slate-600 leading-relaxed max-w-2xl mx-auto">
          Punya ide proyek, kebutuhan sistem, atau tantangan digital di bisnis Anda? Kami siap menjadi partner teknologi untuk mewujudkannya.
        </p>
      </Container>
    </section>

    <!-- Main Form Section -->
    <section class="py-16 bg-white relative">
      <Container>
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 max-w-6xl mx-auto">
          <!-- Left: Information Cards & Direct WhatsApp -->
          <div class="lg:col-span-5 space-y-6">
            <div>
              <h2 class="text-xl sm:text-2xl font-bold text-[#0D2329] mb-2">
                Konsultasi Langsung dengan Tim Kami
              </h2>
              <p class="text-sm text-slate-600 leading-relaxed">
                Tim teknis kami siap memberikan estimasi awal, saran arsitektur, dan rekomendasi solusi yang efisien untuk bisnis Anda.
              </p>
            </div>

            <!-- Direct Channels List -->
            <div class="space-y-4 pt-2">
              <a 
                :href="BRAND.contact.whatsappUrl"
                target="_blank"
                rel="noopener"
                class="flex items-center gap-4 p-4 rounded-2xl bg-[#F0FDFB] border border-[#BCEEE5] hover:border-[#00BFA5] hover:shadow-sm transition-all group"
              >
                <div class="w-11 h-11 rounded-xl bg-[#00BFA5] text-white flex items-center justify-center shrink-0 shadow-xs">
                  <Smartphone class="w-5 h-5 stroke-[2.2]" />
                </div>
                <div>
                  <span class="block text-xs font-semibold text-[#008F7A] uppercase tracking-wider">WhatsApp Langsung</span>
                  <span class="text-sm font-bold text-[#0D2329] group-hover:text-[#00BFA5] transition-colors">
                    {{ BRAND.contact.whatsappDisplay }}
                  </span>
                </div>
              </a>

              <a 
                :href="'mailto:' + BRAND.contact.email"
                class="flex items-center gap-4 p-4 rounded-2xl bg-white border border-slate-200 hover:border-[#00BFA5] hover:shadow-sm transition-all group"
              >
                <div class="w-11 h-11 rounded-xl bg-[#E6FAF7] text-[#00BFA5] flex items-center justify-center shrink-0">
                  <Mail class="w-5 h-5 stroke-[2.2]" />
                </div>
                <div>
                  <span class="block text-xs font-semibold text-slate-400 uppercase tracking-wider">Email Resmi</span>
                  <span class="text-sm font-bold text-[#0D2329] group-hover:text-[#00BFA5] transition-colors">
                    {{ BRAND.contact.email }}
                  </span>
                </div>
              </a>

              <a 
                :href="BRAND.contact.instagramUrl"
                target="_blank"
                rel="noopener"
                class="flex items-center gap-4 p-4 rounded-2xl bg-white border border-slate-200 hover:border-[#00BFA5] hover:shadow-sm transition-all group"
              >
                <div class="w-11 h-11 rounded-xl bg-[#E6FAF7] text-[#00BFA5] flex items-center justify-center shrink-0">
                  <span class="font-bold text-base">@</span>
                </div>
                <div>
                  <span class="block text-xs font-semibold text-slate-400 uppercase tracking-wider">Instagram</span>
                  <span class="text-sm font-bold text-[#0D2329] group-hover:text-[#00BFA5] transition-colors">
                    {{ BRAND.contact.instagram }}
                  </span>
                </div>
              </a>
            </div>

            <!-- Post 09 Graphic Card -->
            <div class="pt-4">
              <div class="rounded-3xl overflow-hidden border border-[#D5F5EE] shadow-md">
                <img
                  src="/images/posts/Synbarra_Post_09.png"
                  alt="Yuk Diskusikan Kebutuhan Anda"
                  class="w-full h-auto object-cover"
                  loading="lazy"
                />
              </div>
            </div>
          </div>

          <!-- Right: Contact Form -->
          <div class="lg:col-span-7">
            <div class="bg-white p-6 sm:p-10 rounded-3xl border border-[#D5F5EE] shadow-[0_10px_35px_-5px_rgba(0,191,165,0.08)]">
              <!-- Success Banner -->
              <div 
                v-if="page.props.flash?.success" 
                class="mb-6 p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 flex items-start gap-3"
              >
                <CheckCircle2 class="w-5 h-5 text-emerald-600 shrink-0 mt-0.5" />
                <div>
                  <p class="font-bold text-sm">Pesan Berhasil Terkirim!</p>
                  <p class="text-xs mt-1 text-emerald-700">
                    {{ page.props.flash.success }}
                  </p>
                </div>
              </div>

              <!-- Form Element -->
              <form @submit.prevent="submit" class="space-y-5">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                  <div>
                    <label class="block text-xs font-bold text-[#0D2329] uppercase tracking-wider mb-2">
                      Nama Lengkap *
                    </label>
                    <input 
                      v-model="form.fullName" 
                      type="text" 
                      required 
                      placeholder="e.g. Budi Santoso"
                      class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-[#00BFA5] focus:ring-2 focus:ring-[#00BFA5]/20 text-sm text-[#0D2329] transition-all outline-none"
                    />
                  </div>

                  <div>
                    <label class="block text-xs font-bold text-[#0D2329] uppercase tracking-wider mb-2">
                      Perusahaan / Organisasi (Opsional)
                    </label>
                    <input 
                      v-model="form.companyName" 
                      type="text" 
                      placeholder="e.g. PT Maju Bersama"
                      class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-[#00BFA5] focus:ring-2 focus:ring-[#00BFA5]/20 text-sm text-[#0D2329] transition-all outline-none"
                    />
                  </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                  <div>
                    <label class="block text-xs font-bold text-[#0D2329] uppercase tracking-wider mb-2">
                      Alamat Email *
                    </label>
                    <input 
                      v-model="form.email" 
                      type="email" 
                      required 
                      placeholder="budi@company.com"
                      class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-[#00BFA5] focus:ring-2 focus:ring-[#00BFA5]/20 text-sm text-[#0D2329] transition-all outline-none"
                    />
                  </div>

                  <div>
                    <label class="block text-xs font-bold text-[#0D2329] uppercase tracking-wider mb-2">
                      Nomor WhatsApp *
                    </label>
                    <input 
                      v-model="form.whatsappNumber" 
                      type="tel" 
                      required 
                      placeholder="+62 812-xxxx-xxxx"
                      class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-[#00BFA5] focus:ring-2 focus:ring-[#00BFA5]/20 text-sm text-[#0D2329] transition-all outline-none"
                    />
                  </div>
                </div>

                <div>
                  <label class="block text-xs font-bold text-[#0D2329] uppercase tracking-wider mb-2">
                    Layanan yang Dibutuhkan *
                  </label>
                  <select 
                    v-model="form.serviceRequired"
                    class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-[#00BFA5] focus:ring-2 focus:ring-[#00BFA5]/20 text-sm text-[#0D2329] transition-all outline-none bg-white"
                  >
                    <option v-for="opt in serviceOptions" :key="opt" :value="opt">
                      {{ opt }}
                    </option>
                  </select>
                </div>

                <div>
                  <label class="block text-xs font-bold text-[#0D2329] uppercase tracking-wider mb-2">
                    Deskripsi Kebutuhan Proyek *
                  </label>
                  <textarea 
                    v-model="form.projectDescription" 
                    rows="4" 
                    required
                    placeholder="Ceritakan gambaran alur bisnis, target fitur, atau kendala operasional yang ingin diselesaikan..."
                    class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-[#00BFA5] focus:ring-2 focus:ring-[#00BFA5]/20 text-sm text-[#0D2329] transition-all outline-none resize-none"
                  ></textarea>
                </div>

                <div class="pt-2">
                  <button 
                    type="submit" 
                    :disabled="form.processing"
                    class="w-full py-4 rounded-full font-bold text-sm text-white bg-gradient-to-r from-[#0D2329] via-[#0A4D4F] to-[#00BFA5] hover:opacity-95 shadow-md hover:shadow-lg transition-all duration-200 flex items-center justify-center gap-2 cursor-pointer disabled:opacity-50"
                  >
                    <Send class="w-4 h-4" />
                    <span>{{ form.processing ? 'Mengirim Pesan...' : 'Kirim Permintaan Konsultasi' }}</span>
                  </button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </Container>
    </section>
  </AppLayout>
</template>
