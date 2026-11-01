<script setup lang="ts">
import { ref } from 'vue';
import { Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import Container from '@/Components/UI/Container.vue';
import Button from '@/Components/UI/Button.vue';
import SectionHeading from '@/Components/UI/SectionHeading.vue';
import Card from '@/Components/UI/Card.vue';
import Badge from '@/Components/UI/Badge.vue';
import SynbarraLogo from '@/Components/UI/SynbarraLogo.vue';
import { BRAND } from '@/config/brand';
import { 
  Code2, 
  Globe, 
  Smartphone, 
  Cpu, 
  Network, 
  Palette, 
  ArrowRight, 
  CheckCircle2, 
  ShieldCheck, 
  Layers, 
  Workflow, 
  Sparkles
} from '@lucide/vue';

const serviceIcons: Record<string, any> = {
  Code2,
  Globe,
  Smartphone,
  Cpu,
  Network,
  Palette,
};

const principles = [
  {
    title: 'Business-Driven Development',
    description: 'We do not build technology for the sake of technology. Every architecture decision and line of code is structured to solve operational bottlenecks and deliver clear commercial value.',
    icon: Layers,
  },
  {
    title: 'Practical Technology Solutions',
    description: 'We eliminate unnecessary complexity. Our engineers choose stable, maintainable stacks that fit your team capability and operational reality without unnecessary technical debt.',
    icon: Workflow,
  },
  {
    title: 'Long-Term Scalability',
    description: 'Your software should grow with your business. We engineer modular, high-performance systems ready to support expanding transaction volumes, multi-location operations, and evolving markets.',
    icon: ShieldCheck,
  },
];

const differentiators = [
  {
    title: 'Solutions Aligned with Business Objectives',
    description: 'Technology tailored around actual business logic, ensuring direct alignment with your revenue, efficiency, and organizational goals.',
  },
  {
    title: 'Structured Development Process',
    description: 'Transparent milestones, clear sprint deliverables, and accountable technical communication at every phase of the project.',
  },
  {
    title: 'User-Focused Design',
    description: 'Intuitive digital experiences crafted to minimize user friction, accelerate staff adoption, and optimize conversion.',
  },
  {
    title: 'Scalable Architecture',
    description: 'Engineered with modern architectural standards, ensuring smooth performance as data and operational complexity scale.',
  },
  {
    title: 'Reliable Technical Implementation',
    description: 'Clean code, strict typing, automated test coverage, and enterprise-grade security protocols built in by default.',
  },
];
</script>

<template>
  <AppLayout>
    <!-- ========================================================================= -->
    <!-- SECTION A — HERO SECTION                                                  -->
    <!-- ========================================================================= -->
    <section class="relative overflow-hidden bg-gradient-to-b from-[#011E26] via-[#01232B] to-[#011E26] text-white pt-20 pb-28 lg:pt-32 lg:pb-36">
      <!-- Background Ambient Glow & Geometric Grid -->
      <div class="absolute inset-0 bg-[radial-gradient(#00F1DE_1px,transparent_1px)] [background-size:32px_32px] opacity-[0.04] pointer-events-none"></div>
      <div class="absolute top-1/4 -right-32 w-96 h-96 bg-[#00F1DE]/10 rounded-full blur-[120px] pointer-events-none"></div>
      <div class="absolute bottom-10 -left-32 w-96 h-96 bg-[#04D2C5]/10 rounded-full blur-[120px] pointer-events-none"></div>

      <Container class="relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-8 items-center">
          <!-- Left Column: Copy & CTAs -->
          <div class="lg:col-span-7 flex flex-col items-start text-left">
            <!-- Overline Badge -->
            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-[#00F1DE]/10 border border-[#00F1DE]/25 text-[#00F1DE] text-xs font-semibold tracking-wide uppercase mb-6">
              <span class="w-2 h-2 rounded-full bg-[#00F1DE] animate-pulse"></span>
              Official Corporate Presence &bull; synbarra.com
            </div>

            <!-- Primary Headline -->
            <h1 class="text-4xl sm:text-5xl lg:text-6xl font-black tracking-tight leading-[1.1] text-white">
              Engineering the <br class="hidden sm:inline" />
              <span class="text-transparent bg-clip-text bg-gradient-to-r from-white via-[#E0FFFC] to-[#00F1DE]">
                Digital Future.
              </span>
            </h1>

            <!-- Supporting Text (Official Description) -->
            <p class="mt-6 text-base sm:text-lg text-slate-200 leading-relaxed max-w-2xl font-normal">
              <strong class="font-semibold text-white">SYNBARRA TECHNOLOGIES</strong> adalah penyedia solusi teknologi digital yang membantu bisnis dan organisasi mengembangkan sistem, website, serta aplikasi sesuai kebutuhan. Kami berfokus pada pengembangan teknologi yang praktis, efisien, dan dapat mendukung pertumbuhan bisnis.
            </p>

            <!-- Core Services Highlight -->
            <div class="mt-6 p-4 rounded-2xl bg-white/[0.05] border border-[#00F1DE]/25 backdrop-blur-sm max-w-2xl w-full">
              <div class="text-[11px] font-mono tracking-widest uppercase font-semibold text-[#00F1DE] mb-2.5 flex items-center gap-2">
                <span class="w-1.5 h-1.5 rounded-full bg-[#00F1DE] animate-pulse"></span>
                OUR CORE SERVICES
              </div>
              <div class="flex flex-wrap items-center gap-x-2.5 gap-y-2 text-xs sm:text-sm font-medium text-slate-200">
                <span class="inline-flex items-center text-white">Website</span>
                <span class="text-[#00F1DE]/60 font-bold">•</span>
                <span class="inline-flex items-center text-white">Mobile Apps</span>
                <span class="text-[#00F1DE]/60 font-bold">•</span>
                <span class="inline-flex items-center text-white">Custom Software</span>
                <span class="text-[#00F1DE]/60 font-bold">•</span>
                <span class="inline-flex items-center text-white">Automation</span>
                <span class="text-[#00F1DE]/60 font-bold">•</span>
                <span class="inline-flex items-center text-white">System Integration</span>
                <span class="text-[#00F1DE]/60 font-bold">•</span>
                <span class="inline-flex items-center text-[#00F1DE] font-semibold">AI Solutions</span>
              </div>
            </div>

            <!-- Action CTAs -->
            <div class="mt-8 sm:mt-10 flex flex-wrap items-center gap-4 w-full sm:w-auto">
              <Button href="/contact" variant="secondary" size="lg" class="w-full sm:w-auto">
                Discuss Your Project
                <template #suffix>
                  <ArrowRight class="w-4 h-4 ml-1" />
                </template>
              </Button>

              <Button href="/services" variant="outline" size="lg" class="w-full sm:w-auto !border-[#0A3B43] !text-white hover:!bg-[#022B31] hover:!border-[#00F1DE]/40">
                Explore Our Services
              </Button>
            </div>

            <!-- Tech Trust Indicators -->
            <div class="mt-12 pt-8 border-t border-[#0A3B43]/60 flex flex-wrap items-center gap-6 text-xs text-slate-400">
              <span class="font-mono text-[#00F1DE]">01 / Precision Architecture</span>
              <span class="text-slate-600">&bull;</span>
              <span class="font-mono text-[#00F1DE]">02 / Clean Code Standards</span>
              <span class="text-slate-600">&bull;</span>
              <span class="font-mono text-[#00F1DE]">03 / Business Alignment</span>
            </div>
          </div>

          <!-- Right Column: Geometric S Technology Visual -->
          <div class="lg:col-span-5 flex justify-center lg:justify-end">
            <div class="relative w-72 sm:w-88 lg:w-96 aspect-square rounded-3xl bg-gradient-to-br from-[#022B31] to-[#011E26] p-4 ring-1 ring-[#00F1DE]/30 shadow-synbarra-lg flex items-center justify-center group">
              <!-- Animated outer glow ring -->
              <div class="absolute inset-2 rounded-2xl border border-[#00F1DE]/20 group-hover:border-[#00F1DE]/50 transition-colors duration-500"></div>
              
              <!-- Official Logo Badge inside geometric frame -->
              <div class="relative w-48 h-48 sm:w-56 sm:h-56 p-4 flex items-center justify-center">
                <img 
                  src="/images/logo/synbarra-badge.png" 
                  alt="Synbarra Technologies Geometric Symbol" 
                  class="w-full h-full object-contain filter drop-shadow-[0_0_25px_rgba(0,241,222,0.3)] transition-transform duration-500 group-hover:scale-105"
                />
              </div>

              <!-- Floating Tech Badges -->
              <div class="absolute -bottom-4 -left-4 bg-[#011E26]/90 backdrop-blur-md px-4 py-2 rounded-xl border border-[#0A3B43] shadow-lg flex items-center gap-2">
                <div class="w-2 h-2 rounded-full bg-[#00F1DE]"></div>
                <span class="text-xs font-mono text-slate-200">Architecture: Modern & Modular</span>
              </div>

              <div class="absolute -top-4 -right-4 bg-[#011E26]/90 backdrop-blur-md px-4 py-2 rounded-xl border border-[#0A3B43] shadow-lg flex items-center gap-2">
                <Sparkles class="w-3.5 h-3.5 text-[#00F1DE]" />
                <span class="text-xs font-mono text-slate-200">Full-Stack Scalability</span>
              </div>
            </div>
          </div>
        </div>
      </Container>
    </section>

    <!-- ========================================================================= -->
    <!-- SECTION B — INTRODUCTION & PRINCIPLES                                     -->
    <!-- ========================================================================= -->
    <section class="py-20 lg:py-28 bg-[#F8FAFA] border-b border-slate-200/80">
      <Container>
        <SectionHeading
          badge="Core Principles"
          title="Technology Built Around Your Business."
          description="Every business has different challenges. We build digital solutions designed around your operational needs, business goals, and long-term growth."
        />

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
          <Card 
            v-for="(item, index) in principles" 
            :key="item.title"
            class="p-8 bg-white border border-slate-200 flex flex-col justify-between"
          >
            <div>
              <div class="w-12 h-12 rounded-xl bg-[#01232B] text-[#00F1DE] flex items-center justify-center mb-6 shadow-sm">
                <component :is="item.icon" class="w-6 h-6" />
              </div>
              <h3 class="text-xl font-bold text-[#01232B] mb-3">
                {{ item.title }}
              </h3>
              <p class="text-sm text-slate-600 leading-relaxed">
                {{ item.description }}
              </p>
            </div>
            <div class="mt-6 pt-4 border-t border-slate-100 flex items-center text-xs font-mono font-semibold text-[#04D2C5]">
              0{{ index + 1 }} // Principle
            </div>
          </Card>
        </div>
      </Container>
    </section>

    <!-- ========================================================================= -->
    <!-- SECTION C — SERVICES OVERVIEW                                             -->
    <!-- ========================================================================= -->
    <section class="py-20 lg:py-28 bg-white border-b border-slate-200/80">
      <Container>
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-16">
          <div class="max-w-2xl">
            <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold tracking-wider uppercase mb-3 bg-[#01232B]/5 text-[#01232B] border border-[#01232B]/10">
              <span class="w-1.5 h-1.5 rounded-full bg-[#00F1DE]"></span>
              Capabilities
            </div>
            <h2 class="text-3xl sm:text-4xl font-extrabold text-[#01232B] tracking-tight">
              Software Solutions Designed for Impact.
            </h2>
            <p class="mt-4 text-slate-600 text-base leading-relaxed">
              From enterprise web apps to streamlined automated workflows, we provide end-to-end software development tailored to your specific demands.
            </p>
          </div>
          <div class="mt-6 md:mt-0">
            <Button href="/services" variant="outline" size="md">
              View Detailed Services
              <template #suffix>
                <ArrowRight class="w-4 h-4 ml-1" />
              </template>
            </Button>
          </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
          <Card 
            v-for="service in BRAND.services" 
            :key="service.id"
            class="p-7 bg-white hover:border-[#04D2C5] flex flex-col justify-between group"
          >
            <div>
              <div class="w-11 h-11 rounded-lg bg-[#01232B]/5 text-[#01232B] group-hover:bg-[#01232B] group-hover:text-[#00F1DE] transition-colors duration-200 flex items-center justify-center mb-5">
                <component :is="serviceIcons[service.icon]" class="w-5 h-5" />
              </div>
              <h3 class="text-lg font-bold text-[#01232B] mb-2 group-hover:text-[#04D2C5] transition-colors">
                {{ service.title }}
              </h3>
              <p class="text-sm text-slate-600 leading-relaxed">
                {{ service.description }}
              </p>
            </div>
            <div class="mt-6 pt-4 border-t border-slate-100 flex items-center justify-between text-xs font-medium text-[#01232B]">
              <span class="text-slate-400 font-mono">Learn more</span>
              <ArrowRight class="w-3.5 h-3.5 text-[#04D2C5] transform group-hover:translate-x-1 transition-transform" />
            </div>
          </Card>
        </div>
      </Container>
    </section>

    <!-- ========================================================================= -->
    <!-- SECTION D — WHY SYNBARRA                                                  -->
    <!-- ========================================================================= -->
    <section class="py-20 lg:py-28 bg-[#011E26] text-white relative overflow-hidden">
      <div class="absolute -top-24 right-0 w-80 h-80 bg-[#00F1DE]/5 rounded-full blur-3xl pointer-events-none"></div>

      <Container class="relative z-10">
        <SectionHeading
          badge="Why Synbarra"
          title="A Technology Partner That Understands Business."
          description="We combine engineering rigor with commercial perspective. No generic templates, no unverified claims — just reliable, high-performance software."
          theme="dark"
        />

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8 mt-12">
          <div 
            v-for="(diff, index) in differentiators" 
            :key="diff.title"
            class="p-6 rounded-2xl bg-[#022B31]/70 border border-[#0A3B43] hover:border-[#00F1DE]/50 transition-colors duration-200 flex flex-col"
          >
            <div class="flex items-center gap-3 mb-4">
              <span class="w-8 h-8 rounded-lg bg-[#00F1DE]/15 text-[#00F1DE] flex items-center justify-center font-mono text-xs font-bold">
                0{{ index + 1 }}
              </span>
              <h3 class="text-base font-bold text-white">
                {{ diff.title }}
              </h3>
            </div>
            <p class="text-sm text-slate-300 leading-relaxed">
              {{ diff.description }}
            </p>
          </div>
        </div>
      </Container>
    </section>

    <!-- ========================================================================= -->
    <!-- SECTION E — OUR DEVELOPMENT PROCESS                                      -->
    <!-- ========================================================================= -->
    <section class="py-20 lg:py-28 bg-white border-b border-slate-200/80">
      <Container>
        <SectionHeading
          badge="Structured Methodology"
          title="Our Development Process"
          description="A structured five-step lifecycle designed to reduce risks, maintain transparency, and deliver high-quality solutions on time."
        />

        <div class="grid grid-cols-1 md:grid-cols-5 gap-6 mt-12">
          <div 
            v-for="(step, idx) in BRAND.process" 
            :key="step.step"
            class="relative p-6 rounded-xl bg-[#F8FAFA] border border-slate-200 flex flex-col justify-between"
          >
            <div>
              <span class="text-2xl font-black font-mono text-[#00F1DE] block mb-3">
                {{ step.step }}
              </span>
              <h3 class="text-base font-bold text-[#01232B] mb-2">
                {{ step.title }}
              </h3>
              <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                {{ step.description }}
              </p>
            </div>
            <div class="mt-6 pt-3 border-t border-slate-200/60 text-[11px] font-mono text-slate-400">
              Stage {{ idx + 1 }}/5
            </div>
          </div>
        </div>
      </Container>
    </section>

    <!-- ========================================================================= -->
    <!-- SECTION F — CLOSING CTA                                                   -->
    <!-- ========================================================================= -->
    <section class="py-20 lg:py-24 bg-gradient-to-r from-[#01232B] via-[#022B31] to-[#011E26] text-white relative overflow-hidden">
      <div class="absolute inset-0 bg-[radial-gradient(#00F1DE_1px,transparent_1px)] [background-size:24px_24px] opacity-10"></div>
      
      <Container class="relative z-10 text-center max-w-3xl mx-auto">
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-[#00F1DE]/10 border border-[#00F1DE]/30 text-[#00F1DE] text-xs font-semibold uppercase tracking-wider mb-6">
          Ready to Collaborate
        </div>

        <h2 class="text-3xl sm:text-4xl lg:text-5xl font-black text-white tracking-tight leading-tight">
          Let's Build Something That Moves Your Business Forward.
        </h2>

        <p class="mt-5 text-base sm:text-lg text-slate-300 leading-relaxed max-w-xl mx-auto">
          Have an idea, a challenge, or a business process that needs a better digital solution? Let's talk.
        </p>

        <div class="mt-8 flex flex-wrap justify-center gap-4">
          <Button href="/contact" variant="secondary" size="lg">
            Start a Conversation
            <template #suffix>
              <ArrowRight class="w-4 h-4 ml-1" />
            </template>
          </Button>
          <Button href="/approach" variant="outline" size="lg" class="!border-[#0A3B43] !text-white hover:!bg-[#022B31]">
            Our Engineering Approach
          </Button>
        </div>
      </Container>
    </section>
  </AppLayout>
</template>
