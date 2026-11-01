<script setup lang="ts">
import { useForm, usePage } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import Container from '@/Components/UI/Container.vue';
import Button from '@/Components/UI/Button.vue';
import Card from '@/Components/UI/Card.vue';
import { BRAND } from '@/config/brand';
import { Mail, Globe, Clock, CheckCircle2, AlertCircle } from '@lucide/vue';

const page = usePage();

const form = useForm({
  fullName: '',
  companyName: '',
  email: '',
  whatsappNumber: '',
  serviceRequired: 'Custom Software Development',
  projectDescription: '',
});

const serviceOptions = [
  'Custom Software Development',
  'Web Application Development',
  'Mobile Application Development',
  'Business Process Automation',
  'System Integration',
  'UI/UX Design',
  'Other',
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
  <AppLayout title="Contact Us | Synbarra Technologies">
    <!-- Header -->
    <section class="bg-[#011E26] text-white py-16 lg:py-24 relative overflow-hidden">
      <div class="absolute inset-0 bg-[radial-gradient(#00F1DE_1px,transparent_1px)] [background-size:28px_28px] opacity-[0.05]"></div>
      
      <Container class="relative z-10 text-center max-w-3xl mx-auto">
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-[#00F1DE]/10 border border-[#00F1DE]/25 text-[#00F1DE] text-xs font-semibold tracking-wider uppercase mb-6">
          Direct Inquiries
        </div>

        <h1 class="text-3xl sm:text-4xl lg:text-5xl font-black text-white tracking-tight leading-tight">
          Let's Build Something That Moves Your Business Forward.
        </h1>

        <p class="mt-4 text-base sm:text-lg text-slate-300 leading-relaxed">
          Have an idea, an operational challenge, or a business process that needs a custom digital solution? Fill out the form below to start a conversation.
        </p>
      </Container>
    </section>

    <!-- Main Form Section -->
    <section class="py-16 sm:py-20 bg-white">
      <Container>
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 max-w-6xl mx-auto items-start">
          <!-- Left Column: Contact Channels & Scope Advice -->
          <div class="lg:col-span-5 space-y-8">
            <div>
              <h2 class="text-2xl font-extrabold text-[#01232B] tracking-tight">
                Direct Engineering Consultation
              </h2>
              <p class="mt-3 text-sm text-slate-600 leading-relaxed">
                We respect your time. When you reach out, our technical leads review your requirements directly to provide clear feasibility and architecture recommendations.
              </p>
            </div>

            <div class="space-y-4 pt-4 border-t border-slate-200">
              <div class="flex items-start gap-4">
                <div class="w-10 h-10 rounded-lg bg-[#01232B] text-[#00F1DE] flex items-center justify-center flex-shrink-0">
                  <Mail class="w-5 h-5" />
                </div>
                <div>
                  <span class="block text-xs font-semibold text-slate-500 uppercase tracking-wider">Official Email</span>
                  <a :href="'mailto:' + BRAND.contact.email" class="text-sm font-medium text-[#01232B] hover:text-[#04D2C5]">
                    {{ BRAND.contact.email }}
                  </a>
                </div>
              </div>

              <div class="flex items-start gap-4">
                <div class="w-10 h-10 rounded-lg bg-[#01232B] text-[#00F1DE] flex items-center justify-center flex-shrink-0">
                  <Globe class="w-5 h-5" />
                </div>
                <div>
                  <span class="block text-xs font-semibold text-slate-500 uppercase tracking-wider">Domain</span>
                  <span class="text-sm font-medium text-[#01232B]">
                    {{ BRAND.domain }}
                  </span>
                </div>
              </div>

              <div class="flex items-start gap-4">
                <div class="w-10 h-10 rounded-lg bg-[#01232B] text-[#00F1DE] flex items-center justify-center flex-shrink-0">
                  <Clock class="w-5 h-5" />
                </div>
                <div>
                  <span class="block text-xs font-semibold text-slate-500 uppercase tracking-wider">Response Window</span>
                  <span class="text-sm font-medium text-[#01232B]">
                    Within 1 Business Day
                  </span>
                </div>
              </div>
            </div>

            <!-- Privacy & Anti-Spam Notice -->
            <div class="p-4 rounded-xl bg-[#F8FAFA] border border-slate-200 text-xs text-slate-500 leading-relaxed">
              <strong>Confidentiality First:</strong> We treat all project inquiries and technical briefs with strict confidentiality. Your information is never shared or used for spam.
            </div>
          </div>

          <!-- Right Column: Form -->
          <div class="lg:col-span-7">
            <Card class="p-8 sm:p-10 bg-white border border-slate-200 shadow-synbarra-sm">
              <!-- Success Alert -->
              <div 
                v-if="page.props.flash?.success" 
                class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 flex items-start gap-3"
              >
                <CheckCircle2 class="w-5 h-5 text-emerald-600 flex-shrink-0 mt-0.5" />
                <div class="text-sm">
                  {{ page.props.flash.success }}
                </div>
              </div>

              <form @submit.prevent="submit" class="space-y-6">
                <!-- Full Name -->
                <div>
                  <label for="fullName" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                    Full Name <span class="text-rose-500">*</span>
                  </label>
                  <input
                    id="fullName"
                    v-model="form.fullName"
                    type="text"
                    required
                    placeholder="e.g. Budi Santoso"
                    class="w-full px-4 py-2.5 rounded-lg border border-slate-300 text-slate-900 text-sm focus:border-[#04D2C5] focus:ring-2 focus:ring-[#00F1DE]/30 outline-none transition-colors"
                    :class="{ 'border-rose-400': form.errors.fullName }"
                  />
                  <p v-if="form.errors.fullName" class="text-xs text-rose-500 mt-1">
                    {{ form.errors.fullName }}
                  </p>
                </div>

                <!-- Company Name (Optional) -->
                <div>
                  <label for="companyName" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                    Company Name <span class="text-xs font-normal text-slate-400 lowercase">(optional)</span>
                  </label>
                  <input
                    id="companyName"
                    v-model="form.companyName"
                    type="text"
                    placeholder="e.g. PT Maju Bersama Digital"
                    class="w-full px-4 py-2.5 rounded-lg border border-slate-300 text-slate-900 text-sm focus:border-[#04D2C5] focus:ring-2 focus:ring-[#00F1DE]/30 outline-none transition-colors"
                  />
                </div>

                <!-- Email & WhatsApp Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                  <div>
                    <label for="email" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                      Email Address <span class="text-rose-500">*</span>
                    </label>
                    <input
                      id="email"
                      v-model="form.email"
                      type="email"
                      required
                      placeholder="budi@company.com"
                      class="w-full px-4 py-2.5 rounded-lg border border-slate-300 text-slate-900 text-sm focus:border-[#04D2C5] focus:ring-2 focus:ring-[#00F1DE]/30 outline-none transition-colors"
                      :class="{ 'border-rose-400': form.errors.email }"
                    />
                    <p v-if="form.errors.email" class="text-xs text-rose-500 mt-1">
                      {{ form.errors.email }}
                    </p>
                  </div>

                  <div>
                    <label for="whatsappNumber" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                      WhatsApp Number <span class="text-rose-500">*</span>
                    </label>
                    <input
                      id="whatsappNumber"
                      v-model="form.whatsappNumber"
                      type="tel"
                      required
                      placeholder="+62 812-xxxx-xxxx"
                      class="w-full px-4 py-2.5 rounded-lg border border-slate-300 text-slate-900 text-sm focus:border-[#04D2C5] focus:ring-2 focus:ring-[#00F1DE]/30 outline-none transition-colors"
                      :class="{ 'border-rose-400': form.errors.whatsappNumber }"
                    />
                    <p v-if="form.errors.whatsappNumber" class="text-xs text-rose-500 mt-1">
                      {{ form.errors.whatsappNumber }}
                    </p>
                  </div>
                </div>

                <!-- Service Required -->
                <div>
                  <label for="serviceRequired" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                    Service Required <span class="text-rose-500">*</span>
                  </label>
                  <select
                    id="serviceRequired"
                    v-model="form.serviceRequired"
                    required
                    class="w-full px-4 py-2.5 rounded-lg border border-slate-300 text-slate-900 text-sm focus:border-[#04D2C5] focus:ring-2 focus:ring-[#00F1DE]/30 outline-none bg-white transition-colors"
                  >
                    <option v-for="srv in serviceOptions" :key="srv" :value="srv">
                      {{ srv }}
                    </option>
                  </select>
                </div>

                <!-- Project Description -->
                <div>
                  <label for="projectDescription" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                    Project Description <span class="text-rose-500">*</span>
                  </label>
                  <textarea
                    id="projectDescription"
                    v-model="form.projectDescription"
                    required
                    rows="4"
                    placeholder="Briefly describe your business goals, target timeline, or current technical challenges..."
                    class="w-full px-4 py-2.5 rounded-lg border border-slate-300 text-slate-900 text-sm focus:border-[#04D2C5] focus:ring-2 focus:ring-[#00F1DE]/30 outline-none transition-colors"
                    :class="{ 'border-rose-400': form.errors.projectDescription }"
                  ></textarea>
                  <p v-if="form.errors.projectDescription" class="text-xs text-rose-500 mt-1">
                    {{ form.errors.projectDescription }}
                  </p>
                </div>

                <!-- Submit Button -->
                <div>
                  <Button
                    type="submit"
                    variant="primary"
                    size="lg"
                    :loading="form.processing"
                    class="w-full text-center"
                  >
                    Submit Project Inquiry
                  </Button>
                </div>
              </form>
            </Card>
          </div>
        </div>
      </Container>
    </section>
  </AppLayout>
</template>
