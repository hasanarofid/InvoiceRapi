<script setup lang="ts">
import AppLayout from '@/Layouts/AppLayout.vue';
import Container from '@/Components/UI/Container.vue';
import Button from '@/Components/UI/Button.vue';
import SectionHeading from '@/Components/UI/SectionHeading.vue';
import Card from '@/Components/UI/Card.vue';
import { BRAND } from '@/config/brand';
import { 
  Code2, 
  Globe, 
  Smartphone, 
  Cpu, 
  Network, 
  Palette, 
  CheckCircle2, 
  ArrowRight,
  TrendingUp,
  ShieldCheck
} from '@lucide/vue';

const servicesDetail = [
  {
    id: 'custom-software',
    title: 'Custom Software Development',
    icon: Code2,
    overview: 'Development of tailored software applications engineered to match your proprietary workflows and distinct operational requirements.',
    problems: ['Rigid off-the-shelf software failing to match specific internal processes', 'High licensing fees for bloated, unused third-party features', 'Data silos and vendor lock-in restricting organizational agility'],
    solutions: 'We build proprietary applications with clean modular architecture, full source code ownership, and high data security tailored specifically to your business logic.',
    capabilities: ['Bespoke business core applications', 'High-throughput transactional engines', 'Role-based access & enterprise security', 'Custom reporting & analytics pipelines'],
  },
  {
    id: 'web-application',
    title: 'Web Application Development',
    icon: Globe,
    overview: 'Responsive, highly scalable web-based platforms delivering rapid performance across desktop, tablet, and mobile environments.',
    problems: ['Slow, outdated internal web portals dragging down employee productivity', 'Inconsistent cross-browser experiences causing user churn', 'Poor architectural foundations failing under traffic surges'],
    solutions: 'Modern Single Page Applications (SPA) and Server-Driven web apps built with robust backend frameworks, responsive UI systems, and optimized database queries.',
    capabilities: ['B2B Client Portals & Dashboards', 'SaaS Platform Development', 'E-Commerce & Billing Systems', 'Real-time collaborative web tools'],
  },
  {
    id: 'mobile-application',
    title: 'Mobile Application Development',
    icon: Smartphone,
    overview: 'Native and cross-platform mobile solutions designed to support modern on-the-go business processes, field teams, and consumer experiences.',
    problems: ['Disconnected field operations unable to sync data in real time', 'High development costs maintaining separate iOS and Android codebases', 'Poor offline capability causing operations to stall in low-connectivity areas'],
    solutions: 'Fluid cross-platform and hybrid mobile apps delivering native-feel responsiveness, seamless offline caching, and automated cloud synchronization.',
    capabilities: ['iOS & Android cross-platform solutions', 'Field team operations & logistics apps', 'Push notifications & background sync', 'Secure biometric and mobile authentication'],
  },
  {
    id: 'process-automation',
    title: 'Business Process Automation',
    icon: Cpu,
    overview: 'Digitalization and automation of repetitive manual workflows to eliminate human error and dramatically accelerate operational turnaround.',
    problems: ['Manual repetitive data entry causing costly operational mistakes', 'Bottlenecks in approval chains and invoice processing', 'Delayed customer responses due to fragmented manual tracking'],
    solutions: 'Automated background workers, intelligent webhook routers, and scheduled event-driven pipelines that connect your departments automatically.',
    capabilities: ['Automated order & invoice processing', 'WhatsApp & Email automated notification bots', 'Data reconciliation & ETL pipelines', 'Document generation & automated reporting'],
  },
  {
    id: 'system-integration',
    title: 'System Integration',
    icon: Network,
    overview: 'Connecting disparate business systems, legacy software, ERPs, and external partner APIs into a unified, secure digital ecosystem.',
    problems: ['Isolated software stacks forcing employees into duplicate data entry', 'Lack of real-time visibility across inventory, sales, and accounting', 'Fragile custom integrations that break frequently with API changes'],
    solutions: 'Standardized API gateways, message queues, and robust webhook listeners with automatic retry mechanisms and error-handling telemetry.',
    capabilities: ['RESTful & GraphQL API gateway development', 'Payment gateway & banking integrations', 'ERP, CRM, and accounting system synchronizations', 'Third-party logistics & tracking connectivity'],
  },
  {
    id: 'ui-ux-design',
    title: 'UI/UX Design',
    icon: Palette,
    overview: 'User interface and experience design focused on functional clarity, cognitive ease, and frictionless digital interactions.',
    problems: ['Complex, unintuitive software interfaces requiring lengthy staff training', 'High user drop-off rates on key conversion steps', 'Inconsistent visual branding and typography across digital touchpoints'],
    solutions: 'Clean design systems, interactive prototypes, and usability-tested design tokens engineered for developer handoff and pixel-perfect implementation.',
    capabilities: ['Design token & comprehensive design systems', 'Interactive wireframing & high-fidelity prototyping', 'User journey mapping & friction audits', 'Responsive component libraries'],
  },
];
</script>

<template>
  <AppLayout title="Services | Synbarra Technologies">
    <!-- Header -->
    <section class="bg-[#011E26] text-white py-20 lg:py-28 relative overflow-hidden">
      <div class="absolute inset-0 bg-[radial-gradient(#00F1DE_1px,transparent_1px)] [background-size:28px_28px] opacity-[0.05]"></div>
      
      <Container class="relative z-10 text-center max-w-4xl mx-auto">
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-[#00F1DE]/10 border border-[#00F1DE]/25 text-[#00F1DE] text-xs font-semibold tracking-wider uppercase mb-6">
          Engineering Capabilities
        </div>

        <h1 class="text-4xl sm:text-5xl lg:text-6xl font-black text-white tracking-tight leading-tight">
          Comprehensive Software Services for Growing Businesses.
        </h1>

        <p class="mt-6 text-lg sm:text-xl text-slate-300 leading-relaxed max-w-2xl mx-auto">
          We combine robust technical stacks with deep business understanding to deliver software that scales reliably with your organization.
        </p>
      </Container>
    </section>

    <!-- Services Grid Detailed -->
    <section class="py-20 bg-white">
      <Container>
        <div class="space-y-16">
          <div 
            v-for="(service, idx) in servicesDetail" 
            :key="service.id" 
            :id="service.id"
            class="scroll-mt-28 p-8 sm:p-10 rounded-3xl bg-[#F8FAFA] border border-slate-200/90 shadow-sm"
          >
            <div class="flex flex-col lg:flex-row gap-8 lg:gap-12">
              <!-- Left: Title & Overview -->
              <div class="lg:w-5/12 flex flex-col justify-between">
                <div>
                  <div class="w-12 h-12 rounded-xl bg-[#01232B] text-[#00F1DE] flex items-center justify-center mb-6 shadow-sm">
                    <component :is="service.icon" class="w-6 h-6" />
                  </div>
                  <span class="text-xs font-mono font-semibold text-[#04D2C5]">
                    0{{ idx + 1 }} // Service Module
                  </span>
                  <h2 class="text-2xl sm:text-3xl font-extrabold text-[#01232B] mt-2 mb-4">
                    {{ service.title }}
                  </h2>
                  <p class="text-sm text-slate-600 leading-relaxed">
                    {{ service.overview }}
                  </p>
                </div>

                <div class="mt-8 pt-6 border-t border-slate-200">
                  <Button href="/contact" variant="primary" size="sm">
                    Discuss This Service
                    <template #suffix>
                      <ArrowRight class="w-4 h-4 ml-1" />
                    </template>
                  </Button>
                </div>
              </div>

              <!-- Right: Problems, Solutions, Capabilities -->
              <div class="lg:w-7/12 grid grid-cols-1 sm:grid-cols-2 gap-6 bg-white p-6 sm:p-8 rounded-2xl border border-slate-200">
                <!-- Challenges Addressed -->
                <div class="space-y-3">
                  <h4 class="text-xs font-bold uppercase tracking-wider text-slate-500">
                    Business Problems Addressed
                  </h4>
                  <ul class="space-y-2 text-xs text-slate-600">
                    <li v-for="prob in service.problems" :key="prob" class="flex items-start gap-2">
                      <span class="text-rose-500 font-bold">&times;</span>
                      <span>{{ prob }}</span>
                    </li>
                  </ul>
                </div>

                <!-- Key Capabilities -->
                <div class="space-y-3">
                  <h4 class="text-xs font-bold uppercase tracking-wider text-[#04D2C5]">
                    Key Capabilities
                  </h4>
                  <ul class="space-y-2 text-xs text-slate-700">
                    <li v-for="cap in service.capabilities" :key="cap" class="flex items-start gap-2">
                      <CheckCircle2 class="w-3.5 h-3.5 text-[#00F1DE] flex-shrink-0 mt-0.5" />
                      <span>{{ cap }}</span>
                    </li>
                  </ul>
                </div>

                <!-- Proposed Solution Full Width -->
                <div class="sm:col-span-2 pt-4 border-t border-slate-100">
                  <h4 class="text-xs font-bold uppercase tracking-wider text-[#01232B] mb-1">
                    Our Solution Approach
                  </h4>
                  <p class="text-xs text-slate-600 leading-relaxed">
                    {{ service.solutions }}
                  </p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </Container>
    </section>

    <!-- Bottom CTA -->
    <section class="py-16 bg-[#01232B] text-white text-center">
      <Container class="max-w-2xl">
        <h2 class="text-2xl sm:text-3xl font-extrabold text-white">
          Need a Custom Technology Solution?
        </h2>
        <p class="mt-3 text-slate-300 text-sm">
          Tell us about your project requirements and operational goals.
        </p>
        <div class="mt-6">
          <Button href="/contact" variant="secondary" size="md">
            Start a Conversation
          </Button>
        </div>
      </Container>
    </section>
  </AppLayout>
</template>
