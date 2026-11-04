<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\Tenant;
use Illuminate\Database\Seeder;

class DigitalProductSeeder extends Seeder
{
    /**
     * Run the database seeds for Synbarra Digital Product (Starter Kit).
     * Complies with PRD_Synbarra_CRUD_Starter_Kit.md.
     */
    public function run(): void
    {
        // 1. Dapatkan atau buat tenant aktif (Synbarra / default tenant)
        $tenant = Tenant::first() ?? Tenant::firstOrCreate(
            ['slug' => 'synbarra-technologies'],
            [
                'name' => 'Synbarra Technologies',
                'phone_whatsapp' => '6288804958476',
                'email' => 'synbarr.techno@gmail.com',
                'address' => 'Indonesia',
                'is_active' => true,
                'business_profile' => [
                    'tagline' => 'Engineering the Digital Future & Ready-to-Launch Solutions',
                    'category' => 'Software House & Digital Products',
                ],
            ]
        );

        // 2. Kategori Produk Digital: Developer Tools & Starter Kits
        $category = Category::updateOrCreate(
            ['slug' => 'developer-tools'],
            [
                'tenant_id' => $tenant->id,
                'name' => 'Developer Tools & Starter Kits',
                'type' => 'product',
                'description' => 'Fondasi aplikasi bisnis siap pakai, modular, dan teruji untuk developer freelance dan software agency.',
                'image' => '/images/posts/Synbarra_Post_08.png',
                'order' => 1,
                'is_active' => true,
            ]
        );

        // 3. Deskripsi kaya (HTML) dengan value proposition, problem solving, dan perbandingan lisensi
        $description = <<<'HTML'
<h3>Fondasi Aplikasi Bisnis Siap Dikembangkan</h3>
<p><strong>Synbarra CRUD Starter Kit</strong> adalah boilerplate aplikasi web modern yang dirancang khusus untuk membantu freelancer developer dan small software agency membangun aplikasi bisnis klien lebih cepat tanpa mengulang pekerjaan dari nol.</p>

<h4>Problem yang Diselesaikan</h4>
<p>Developer sering membuang 20–40 jam kerja awal di setiap proyek baru hanya untuk menyiapkan:</p>
<ul>
    <li>Setup autentikasi, session, dan proteksi rute server-side.</li>
    <li>Struktur layout dashboard yang responsif di mobile & desktop.</li>
    <li>Pola CRUD berulang, validasi form Zod, dan standardized error handling.</li>
    <li>Komponen Data Table (Search, Filter, Sorting, Pagination).</li>
    <li>Manajemen Role & Permission (RBAC) yang rapi.</li>
</ul>

<h4>Pilihan Paket & Lisensi</h4>
<table border="1" cellpadding="8" style="width: 100%; border-collapse: collapse; margin-top: 12px; margin-bottom: 16px;">
    <thead>
        <tr style="background-color: #f1f5f9;">
            <th>Fitur / Modul</th>
            <th>Lite (Free)</th>
            <th>Pro (Rp 349.000)</th>
            <th>Agency (Rp 1.499.000)</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><strong>Autentikasi & Session</strong></td>
            <td>Dasar</td>
            <td>Lengkap + Proteksi Rute</td>
            <td>Lengkap + Proteksi Rute</td>
        </tr>
        <tr>
            <td><strong>Role-Based Access Control (RBAC)</strong></td>
            <td>-</td>
            <td>Server-Side RBAC (Admin & User)</td>
            <td>Server-Side RBAC (Admin & User)</td>
        </tr>
        <tr>
            <td><strong>Modul CRUD</strong></td>
            <td>1 Modul Contoh</td>
            <td>Reusable CRUD Patterns</td>
            <td>Reusable CRUD Patterns</td>
        </tr>
        <tr>
            <td><strong>Data Table</strong></td>
            <td>Dasar</td>
            <td>Search, Filter, Sort, Pagination</td>
            <td>Search, Filter, Sort, Pagination</td>
        </tr>
        <tr>
            <td><strong>Automated Tests</strong></td>
            <td>-</td>
            <td>Playwright E2E & Unit Tests</td>
            <td>Playwright E2E & Unit Tests</td>
        </tr>
        <tr>
            <td><strong>Dokumentasi Lengkap</strong></td>
            <td>README Ringkas</td>
            <td>Panduan Lengkap (PDF/MD)</td>
            <td>Panduan Lengkap (PDF/MD)</td>
        </tr>
        <tr>
            <td><strong>Hak Penggunaan Klien</strong></td>
            <td>Proyek Personal</td>
            <td>1 Proyek Komersial</td>
            <td><strong>Unlimited Proyek Klien</strong></td>
        </tr>
        <tr>
            <td><strong>Dukungan Teknis</strong></td>
            <td>Komunitas</td>
            <td>Email Support 6 Bulan</td>
            <td><strong>Prioritas Support 12 Bulan</strong></td>
        </tr>
    </tbody>
</table>

<h4>Tech Stack Andal & Modern</h4>
<p>Dibangun dengan arsitektur clean code dan strict typing tanpa kompromi:</p>
<ul>
    <li><strong>Frontend:</strong> Next.js 14 (App Router), React 18, TypeScript (Strict Mode), Tailwind CSS, shadcn/ui, Lucide Icons.</li>
    <li><strong>Backend & Database:</strong> PostgreSQL, Prisma ORM, Server Actions, Zod Form Validation.</li>
    <li><strong>Quality Assurance:</strong> Playwright End-to-End Tests, ESLint, Prettier, Automated Typecheck.</li>
</ul>

<h4>Layanan Kustomisasi (Custom Service)</h4>
<p>Butuh implementasi modul khusus, migrasi database kompleks, atau integrasi API pihak ketiga? Tim engineer Synbarra siap membantu melalui layanan custom development dengan tarif terpisah.</p>
HTML;

        // 4. Struktur data JSON specifications untuk kemudahan integrasi UI frontend
        $specifications = [
            'product_name' => 'Synbarra CRUD Starter Kit',
            'product_type' => 'developer_starter_kit',
            'version' => '1.0.0',
            'release_status' => 'Production Ready MVP',
            'primary_persona' => 'Freelancer Developer & Small Software Agency',
            'tech_stack' => [
                'Next.js 14 (App Router)',
                'React 18',
                'TypeScript (Strict Mode)',
                'Tailwind CSS',
                'shadcn/ui & Lucide Icons',
                'PostgreSQL',
                'Prisma ORM',
                'Zod Schema Validation',
                'Playwright E2E Testing',
            ],
            'tiers' => [
                'lite' => [
                    'name' => 'Lite (Free)',
                    'price' => 0,
                    'price_formatted' => 'Gratis',
                    'badge' => 'Open Community',
                    'features' => [
                        'Basic Authentication (Login & Logout)',
                        '1 Modul CRUD Contoh (Projects)',
                        'Basic Form Validation & Error Handling',
                        'Pencarian & Pagination Sederhana',
                        'README Quick Start Guide',
                    ],
                ],
                'pro' => [
                    'name' => 'Pro License',
                    'price' => 349000,
                    'price_formatted' => 'Rp 349.000',
                    'compare_at_price' => 499000,
                    'badge' => 'Paling Diminati',
                    'features' => [
                        'Semua fitur versi Lite',
                        'Server-Side Role-Based Access Control (RBAC)',
                        'User Management & Role Assignment (Admin & User)',
                        'Reusable CRUD Patterns & Generic Data Table',
                        'Multi-column Search, Filter, & Sorting',
                        'Standardized Error, Loading, Success, & Empty States',
                        'Database Migration & Seed Demo Data',
                        'Automated Test Examples (Playwright E2E)',
                        'Dokumentasi Instalasi & Walkthrough Lengkap (PDF/MD)',
                        'Update Produk 6 Bulan',
                        'Email Support Terbatas',
                    ],
                ],
                'agency' => [
                    'name' => 'Agency / Unlimited',
                    'price' => 1499000,
                    'price_formatted' => 'Rp 1.499.000',
                    'badge' => 'Untuk Agency & Tim',
                    'features' => [
                        'Semua fitur versi Pro',
                        'Penggunaan Tak Terbatas untuk Semua Proyek Klien',
                        'Akses Private GitHub Repository & Branch Updates',
                        'Bebas Modifikasi & Re-branding untuk Klien',
                        'Prioritas Dukungan Teknis 12 Bulan',
                        'Konsultasi Arsitektur 1-on-1 dengan Tim Synbarra',
                    ],
                ],
            ],
            'key_modules' => [
                'Authentication & Session Management',
                'Server-Side Role-Based Access Control (RBAC)',
                'User & Role Management Dashboard',
                'Generic CRUD Engine & Data Table Component',
                'Form Handling & Zod Validation',
                'Automated Testing Suite (Playwright)',
            ],
            'demo_urls' => [
                'live_demo' => 'https://starter-kit.synbarra.com',
                'documentation' => 'https://docs.synbarra.com/crud-starter-kit',
                'github_preview' => 'https://github.com/synbarra/crud-starter-kit-lite',
            ],
            'license_summary' => 'Beli sekali, gunakan selamanya untuk proyek klien Anda. Dilarang mendistribusikan ulang atau menjual kembali source code mentah sebagai produk standalone.',
        ];

        // 5. Seed / Update Produk Tunggal Pertama (Idempotent)
        Product::updateOrCreate(
            ['slug' => 'synbarra-crud-starter-kit'],
            [
                'tenant_id' => $tenant->id,
                'category_id' => $category->id,
                'name' => 'Synbarra CRUD Starter Kit (Next.js & PostgreSQL)',
                'sku' => 'SYN-CRUD-001',
                'price' => 349000,
                'compare_at_price' => 499000,
                'price_range' => 'Mulai Rp 0 (Lite Free) — Rp 349.000 (Pro)',
                'stock' => 999,
                'weight_grams' => 0,
                'material' => 'Full Source Code & Boilerplate (ZIP / Private Git)',
                'dimensions' => 'v1.0.0 (Production-Ready MVP)',
                'short_description' => 'Fondasi aplikasi bisnis siap dikembangkan untuk developer & software agency. Dilengkapi Autentikasi, Role-Based Access Control (RBAC), Reusable CRUD Pattern, Data Table, dan Dokumentasi Lengkap.',
                'description' => $description,
                'specifications' => $specifications,
                'thumbnail' => '/images/posts/Synbarra_Post_08.png',
                'images' => [
                    '/images/posts/Synbarra_Post_08.png',
                    '/images/posts/Synbarra_Post_07.png',
                    '/images/posts/Synbarra_Post_03.png',
                ],
                'is_featured' => true,
                'status' => 'published',
                'order' => 1,
            ]
        );
    }
}

