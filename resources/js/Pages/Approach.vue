<script setup lang="ts">
import AppLayout from '@/Layouts/AppLayout.vue';
import Container from '@/Components/UI/Container.vue';
import Button from '@/Components/UI/Button.vue';
import SectionHeading from '@/Components/UI/SectionHeading.vue';
import Card from '@/Components/UI/Card.vue';
import { 
  Search, 
  FileCheck, 
  Cpu, 
  Layout, 
  Terminal, 
  Rocket, 
  RefreshCw,
  ArrowRight
} from '@lucide/vue';

const stages = [
  {
    step: '01',
    title: 'Understanding the Business',
    icon: Search,
    desc: 'Before writing any code, we study your business model, customer workflows, revenue mechanics, and the specific operational bottlenecks you need to solve.',
    deliverable: 'Business Context & Problem Statement',
  },
  {
    step: '02',
    title: 'Requirement Gathering',
    icon: FileCheck,
    desc: 'We define functional scopes, user personas, third-party system dependencies, and technical boundary conditions to eliminate scope creep and surprises.',
    deliverable: 'Scope of Work & Acceptance Criteria',
  },
  {
    step: '03',
    title: 'Technical Planning',
    icon: Cpu,
    desc: 'Choosing the right database schema, API communication protocols, hosting infrastructure, and state management architecture suited for long-term scalability.',
    deliverable: 'Architecture Blueprint & Tech Stack Matrix',
  },
  {
    step: '04',
    title: 'UI/UX & Architecture',
    icon: Layout,
    desc: 'Crafting responsive user interfaces, structured component libraries, and interactive prototypes tested for cognitive clarity and frictionless task completion.',
    deliverable: 'High-Fidelity Wireframes & Design System',
  },
  {
    step: '05',
    title: 'Development & Testing',
    icon: Terminal,
    desc: 'Iterative sprint development with clean, strictly typed code, continuous integration, automated unit testing, and rigorous QA verification across devices.',
    deliverable: 'Sprint Builds & Verified Test Coverage',
  },
  {
    step: '06',
    title: 'Deployment',
    icon: Rocket,
    desc: 'Zero-downtime production deployments, HTTPS security hardening, database migration automation, and web performance audits before public access.',
    deliverable: 'Production Rollout & Runbook Documentation',
  },
  {
    step: '07',
    title: 'Maintenance & Continuous Improvement',
    icon: RefreshCw,
    desc: 'Proactive server telemetry, security patch management, bug resolution, and agile feature increments as your user base and transaction volumes expand.',
    deliverable: 'SLA Support & Performance Monitoring',
  },
];
</script>

<template>
  <AppLayout title="Our Approach | Synbarra Technologies">
    <!-- Header -->
    <section class="bg-[#011E26] text-white py-20 lg:py-28 relative overflow-hidden">
      <div class="absolute inset-0 bg-[radial-gradient(#00F1DE_1px,transparent_1px)] [background-size:28px_28px] opacity-[0.05]"></div>
      
      <Container class="relative z-10 text-center max-w-4xl mx-auto">
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-[#00F1DE]/10 border border-[#00F1DE]/25 text-[#00F1DE] text-xs font-semibold tracking-wider uppercase mb-6">
          Engineering Methodology
        </div>

        <h1 class="text-4xl sm:text-5xl lg:text-6xl font-black text-white tracking-tight leading-tight">
          How We Build: Clarity, Accountability, and Execution.
        </h1>

        <p class="mt-6 text-lg sm:text-xl text-slate-300 leading-relaxed max-w-2xl mx-auto">
          A disciplined, seven-phase development approach engineered to eliminate ambiguity and deliver predictable, high-performance software outcomes.
        </p>
      </Container>
    </section>

    <!-- Detailed 7-Stage Process -->
    <section class="py-20 bg-white">
      <Container>
        <SectionHeading
          badge="End-to-End Delivery"
          title="Our Structured Engineering Lifecycle"
          description="Every software project follows a defined engineering rhythm designed to maintain quality, reduce technical debt, and ensure continuous stakeholder visibility."
        />

        <div class="mt-16 space-y-6 max-w-4xl mx-auto">
          <div 
            v-for="(stage, idx) in stages" 
            :key="stage.step"
            class="p-6 sm:p-8 rounded-2xl bg-[#F8FAFA] border border-slate-200/80 hover:border-[#04D2C5]/60 transition-colors duration-200 flex flex-col sm:flex-row gap-6 items-start"
          >
            <!-- Step Badge & Icon -->
            <div class="flex sm:flex-col items-center gap-4 sm:gap-2 flex-shrink-0">
              <span class="w-12 h-12 rounded-xl bg-[#01232B] text-[#00F1DE] flex items-center justify-center font-mono font-bold text-sm">
                {{ stage.step }}
              </span>
              <span class="text-xs font-mono text-slate-400 hidden sm:block">
                Phase {{ idx + 1 }}
              </span>
            </div>

            <!-- Content -->
            <div class="flex-grow">
              <h3 class="text-xl font-bold text-[#01232B] mb-2">
                {{ stage.title }}
              </h3>
              <p class="text-sm text-slate-600 leading-relaxed">
                {{ stage.desc }}
              </p>
              
              <div class="mt-4 pt-3 border-t border-slate-200/60 flex items-center gap-2 text-xs">
                <span class="font-semibold text-slate-500">Key Deliverable:</span>
                <span class="font-mono text-[#01232B] font-medium bg-white px-2 py-0.5 rounded border border-slate-200">
                  {{ stage.deliverable }}
                </span>
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
          Ready to Start Your Project with a Proven Process?
        </h2>
        <p class="mt-3 text-slate-300 text-sm">
          Let us discuss your technical scope and establish a clear development roadmap.
        </p>
        <div class="mt-6">
          <Button href="/contact" variant="secondary" size="md">
            Discuss Your Project Scope
          </Button>
        </div>
      </Container>
    </section>
  </AppLayout>
</template>
