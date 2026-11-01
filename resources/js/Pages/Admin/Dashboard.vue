<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { 
  Code2, 
  Layers, 
  MessageSquare, 
  ShieldCheck, 
  ExternalLink, 
  ArrowRight,
  TrendingUp,
  Cpu,
  Clock,
  CheckCircle2
} from '@lucide/vue';
import { BRAND } from '@/config/brand';

const props = defineProps({
    tenant: Object,
    stats: {
        type: Object,
        default: () => ({}),
    },
    recent_orders: {
        type: Array,
        default: () => [],
    },
});

const formatDate = (dateStr) => {
    if (!dateStr) return '-';
    return new Date(dateStr).toLocaleDateString('id-ID', {
        day: '2-digit',
        month: 'short',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
    });
};
</script>

<template>
    <Head title="Management Dashboard — Synbarra Technologies" />

    <AdminLayout>
        <div class="space-y-6 sm:space-y-8">
            <!-- Header Banner -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-6 sm:p-8 rounded-2xl border border-slate-200/80 shadow-synbarra-sm">
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold bg-[#00F1DE]/10 text-[#01232B] border border-[#00F1DE]/30">
                        <span class="w-2 h-2 rounded-full bg-[#00F1DE] animate-pulse"></span>
                        SYNBARRA MANAGEMENT PORTAL
                    </div>
                    <h2 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-[#01232B] mt-2">
                        Control Center &bull; {{ BRAND.name }}
                    </h2>
                    <p class="text-xs sm:text-sm text-slate-500 mt-1 max-w-2xl">
                        {{ BRAND.tagline }} Centralized administrative hub for software development operations, project inquiries, and brand infrastructure.
                    </p>
                </div>

                <div class="flex items-center gap-3">
                    <a
                        href="/"
                        target="_blank"
                        class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-[#01232B] hover:bg-[#022B31] text-white hover:text-[#00F1DE] border border-transparent hover:border-[#00F1DE]/40 text-xs sm:text-sm font-semibold shadow-synbarra-sm transition-all"
                    >
                        <span>View Live Website</span>
                        <ExternalLink class="w-4 h-4 text-[#00F1DE]" />
                    </a>
                </div>
            </div>

            <!-- Key Metric Cards Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
                <!-- Card 1: Active Services -->
                <div class="p-6 rounded-2xl bg-white border border-slate-200/80 shadow-synbarra-sm hover:border-[#04D2C5]/50 transition duration-200">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Corporate Services</span>
                        <div class="w-10 h-10 rounded-xl bg-[#01232B]/5 text-[#01232B] flex items-center justify-center">
                            <Code2 class="w-5 h-5 text-[#04D2C5]" />
                        </div>
                    </div>
                    <div class="mt-4">
                        <div class="text-3xl font-black text-[#01232B]">
                            6 Active
                        </div>
                        <p class="text-xs text-slate-500 mt-1">
                            Custom Software, Web, Mobile, Automation, Integration, UI/UX
                        </p>
                    </div>
                </div>

                <!-- Card 2: Inquiries / Project Pipeline -->
                <div class="p-6 rounded-2xl bg-white border border-slate-200/80 shadow-synbarra-sm hover:border-[#04D2C5]/50 transition duration-200">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Project Leads</span>
                        <div class="w-10 h-10 rounded-xl bg-[#00F1DE]/15 text-[#01232B] flex items-center justify-center">
                            <MessageSquare class="w-5 h-5 text-[#01232B]" />
                        </div>
                    </div>
                    <div class="mt-4">
                        <div class="text-3xl font-black text-[#01232B]">
                            {{ stats.orders_count ?? 0 }} Inquiries
                        </div>
                        <p class="text-xs text-slate-500 mt-1">
                            Direct consultation requests
                        </p>
                    </div>
                </div>

                <!-- Card 3: Engineering Architecture Status -->
                <div class="p-6 rounded-2xl bg-white border border-slate-200/80 shadow-synbarra-sm hover:border-[#04D2C5]/50 transition duration-200">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Architecture Health</span>
                        <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                            <ShieldCheck class="w-5 h-5 text-emerald-600" />
                        </div>
                    </div>
                    <div class="mt-4">
                        <div class="text-3xl font-black text-emerald-600">
                            99.98%
                        </div>
                        <p class="text-xs text-slate-500 mt-1">
                            Production VPS Telemetry Active
                        </p>
                    </div>
                </div>

                <!-- Card 4: Official Domain & HTTPS -->
                <div class="p-6 rounded-2xl bg-white border border-slate-200/80 shadow-synbarra-sm hover:border-[#04D2C5]/50 transition duration-200">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Domain & SSL</span>
                        <div class="w-10 h-10 rounded-xl bg-[#01232B] text-[#00F1DE] flex items-center justify-center">
                            <Cpu class="w-5 h-5 text-[#00F1DE]" />
                        </div>
                    </div>
                    <div class="mt-4">
                        <div class="text-lg font-bold font-mono text-[#01232B] truncate">
                            synbarra.com
                        </div>
                        <p class="text-xs text-emerald-600 font-semibold mt-1 flex items-center gap-1">
                            <CheckCircle2 class="w-3.5 h-3.5" /> HTTPS / Let's Encrypt Active
                        </p>
                    </div>
                </div>
            </div>

            <!-- Operational Sections: Company Scope & Capabilities -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 sm:gap-8">
                <!-- Left: Published Services -->
                <div class="lg:col-span-7 bg-white p-6 sm:p-7 rounded-2xl border border-slate-200/80 shadow-synbarra-sm">
                    <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                        <h3 class="text-base font-bold text-[#01232B]">
                            Registered Service Categories
                        </h3>
                        <Link href="/services" class="text-xs font-semibold text-[#04D2C5] hover:underline flex items-center gap-1">
                            <span>Browse Public Page</span>
                            <ArrowRight class="w-3.5 h-3.5" />
                        </Link>
                    </div>

                    <div class="mt-4 divide-y divide-slate-100">
                        <div 
                            v-for="service in BRAND.services" 
                            :key="service.id" 
                            class="py-3.5 flex items-center justify-between hover:bg-slate-50/80 px-2 rounded-xl transition"
                        >
                            <div>
                                <h4 class="text-sm font-bold text-[#01232B]">
                                    {{ service.title }}
                                </h4>
                                <p class="text-xs text-slate-500 mt-0.5 max-w-md line-clamp-1">
                                    {{ service.description }}
                                </p>
                            </div>
                            <span class="px-2.5 py-1 text-[11px] font-mono rounded-full bg-[#00F1DE]/15 text-[#01232B] border border-[#00F1DE]/30">
                                Live
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Right: Brand Guidelines & Quick Links -->
                <div class="lg:col-span-5 bg-white p-6 sm:p-7 rounded-2xl border border-slate-200/80 shadow-synbarra-sm flex flex-col justify-between">
                    <div>
                        <div class="flex items-center gap-2 pb-4 border-b border-slate-100">
                            <h3 class="text-base font-bold text-[#01232B]">
                                Brand Information & Tokens
                            </h3>
                        </div>

                        <div class="mt-4 space-y-3.5 text-xs text-slate-600">
                            <div class="flex items-center justify-between p-2.5 rounded-lg bg-slate-50 border border-slate-200/60">
                                <span class="font-semibold text-slate-700">Brand Name:</span>
                                <span class="font-bold text-[#01232B]">{{ BRAND.name }}</span>
                            </div>
                            <div class="flex items-center justify-between p-2.5 rounded-lg bg-slate-50 border border-slate-200/60">
                                <span class="font-semibold text-slate-700">Official Tagline:</span>
                                <span class="text-[#04D2C5] font-semibold">{{ BRAND.tagline }}</span>
                            </div>
                            <div class="flex items-center justify-between p-2.5 rounded-lg bg-slate-50 border border-slate-200/60">
                                <span class="font-semibold text-slate-700">Primary Color Token:</span>
                                <span class="font-mono px-2 py-0.5 rounded bg-[#01232B] text-white">#01232B</span>
                            </div>
                            <div class="flex items-center justify-between p-2.5 rounded-lg bg-slate-50 border border-slate-200/60">
                                <span class="font-semibold text-slate-700">Turquoise Token:</span>
                                <span class="font-mono px-2 py-0.5 rounded bg-[#00F1DE] text-[#01232B] font-bold">#00F1DE</span>
                            </div>
                        </div>
                    </div>

                    <div class="pt-6 mt-6 border-t border-slate-100">
                        <Link 
                            href="/contact" 
                            class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-[#01232B] text-xs font-semibold transition"
                        >
                            <span>Preview Client Contact Form</span>
                            <ArrowRight class="w-3.5 h-3.5" />
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
