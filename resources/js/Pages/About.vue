<script setup lang="ts">
import AppLayout from '@/Layouts/AppLayout.vue';
import Container from '@/Components/UI/Container.vue';
import Button from '@/Components/UI/Button.vue';
import SectionHeading from '@/Components/UI/SectionHeading.vue';
import Card from '@/Components/UI/Card.vue';
import { BRAND } from '@/config/brand';
import { Target, Compass, Award, CheckCircle, ArrowRight } from '@lucide/vue';

const values = [
  {
    title: 'Precision Engineering',
    desc: 'We treat software as an engineering discipline. Every architecture decision is vetted for performance, maintainability, and clean code principles.',
  },
  {
    title: 'Business Pragmatism',
    desc: 'Technology is an enabler, not an end in itself. We prioritize solutions that deliver tangible operational value and measurable return on investment.',
  },
  {
    title: 'Transparent Collaboration',
    desc: 'No hidden complexities or opaque jargon. We communicate progress clearly, provide open codebases, and maintain full stakeholder visibility.',
  },
  {
    title: 'Continuous Improvement',
    desc: 'Software development does not end at deployment. We engineer scalable systems designed for ongoing optimization and iterative improvement.',
  },
];
</script>

<template>
  <AppLayout title="About Us | Synbarra Technologies">
    <!-- Hero Header -->
    <section class="bg-[#011E26] text-white py-20 lg:py-28 relative overflow-hidden">
      <div class="absolute inset-0 bg-[radial-gradient(#00F1DE_1px,transparent_1px)] [background-size:28px_28px] opacity-[0.05]"></div>
      
      <Container class="relative z-10 text-center max-w-4xl mx-auto">
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-[#00F1DE]/10 border border-[#00F1DE]/25 text-[#00F1DE] text-xs font-semibold tracking-wider uppercase mb-6">
          About Synbarra Technologies
        </div>

        <h1 class="text-4xl sm:text-5xl lg:text-6xl font-black text-white tracking-tight leading-tight">
          A Technology Partner That Understands Business.
        </h1>

        <p class="mt-6 text-lg sm:text-xl text-slate-300 leading-relaxed max-w-2xl mx-auto">
          SYNBARRA TECHNOLOGIES is a software development company focused on building digital products, business applications, and custom technology solutions that drive lasting business growth.
        </p>
      </Container>
    </section>

    <!-- Company Introduction & Philosophy -->
    <section class="py-20 bg-white border-b border-slate-200">
      <Container>
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
          <div class="lg:col-span-6 space-y-6">
            <span class="text-xs font-mono font-bold tracking-widest uppercase text-[#04D2C5]">
              Company Introduction
            </span>
            <h2 class="text-3xl sm:text-4xl font-extrabold text-[#01232B] tracking-tight">
              Engineering with Purpose, Designed Around Business.
            </h2>
            <p class="text-base text-slate-600 leading-relaxed">
              In an era where technology choices can define market leadership, organizations cannot afford generic off-the-shelf software that forces them into rigid constraints.
            </p>
            <p class="text-base text-slate-600 leading-relaxed">
              Synbarra was founded to deliver custom digital solutions that mirror your operational requirements. We work closely with business leaders, product managers, and engineering teams to transform complex challenges into clean, scalable software architecture.
            </p>
          </div>

          <div class="lg:col-span-6 grid grid-cols-1 sm:grid-cols-2 gap-6">
            <!-- Vision -->
            <Card class="p-6 bg-[#F8FAFA] border border-slate-200">
              <div class="w-10 h-10 rounded-lg bg-[#01232B] text-[#00F1DE] flex items-center justify-center mb-4">
                <Target class="w-5 h-5" />
              </div>
              <h3 class="text-lg font-bold text-[#01232B] mb-2">Our Vision</h3>
              <p class="text-sm text-slate-600 leading-relaxed">
                To become a trusted technology partner that enables businesses to grow through meaningful digital innovation and reliable software engineering.
              </p>
            </Card>

            <!-- Mission -->
            <Card class="p-6 bg-[#F8FAFA] border border-slate-200">
              <div class="w-10 h-10 rounded-lg bg-[#01232B] text-[#00F1DE] flex items-center justify-center mb-4">
                <Compass class="w-5 h-5" />
              </div>
              <h3 class="text-lg font-bold text-[#01232B] mb-2">Our Mission</h3>
              <ul class="text-xs sm:text-sm text-slate-600 space-y-2">
                <li class="flex items-start gap-2">
                  <CheckCircle class="w-4 h-4 text-[#04D2C5] flex-shrink-0 mt-0.5" />
                  <span>Deliver software solving real operational problems.</span>
                </li>
                <li class="flex items-start gap-2">
                  <CheckCircle class="w-4 h-4 text-[#04D2C5] flex-shrink-0 mt-0.5" />
                  <span>Combine business understanding with technical rigor.</span>
                </li>
              </ul>
            </Card>
          </div>
        </div>
      </Container>
    </section>

    <!-- Company Values -->
    <section class="py-20 bg-[#F8FAFA] border-b border-slate-200">
      <Container>
        <SectionHeading
          badge="Our Values"
          title="The Standards We Build By"
          description="These core principles guide our engineering architecture, team collaboration, and client partnerships."
        />

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
          <Card 
            v-for="val in values" 
            :key="val.title"
            class="p-6 bg-white border border-slate-200 flex flex-col justify-between"
          >
            <div>
              <h3 class="text-base font-bold text-[#01232B] mb-2">
                {{ val.title }}
              </h3>
              <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                {{ val.desc }}
              </p>
            </div>
          </Card>
        </div>
      </Container>
    </section>

    <!-- CTA -->
    <section class="py-16 bg-[#01232B] text-white text-center">
      <Container class="max-w-2xl">
        <h2 class="text-2xl sm:text-3xl font-extrabold text-white">
          Looking for a Reliable Technology Partner?
        </h2>
        <p class="mt-3 text-slate-300 text-sm">
          Discuss your project requirements directly with our technical team.
        </p>
        <div class="mt-6">
          <Button href="/contact" variant="secondary" size="md">
            Start a Conversation
            <template #suffix>
              <ArrowRight class="w-4 h-4 ml-1" />
            </template>
          </Button>
        </div>
      </Container>
    </section>
  </AppLayout>
</template>
