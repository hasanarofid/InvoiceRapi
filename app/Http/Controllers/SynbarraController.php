<?php

namespace App\Http\Controllers;

use App\Mail\ContactInquiryMail;
use App\Models\Lead;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;
use Inertia\Response;

class SynbarraController extends Controller
{
    /**
     * Homepage: Primary conversion page.
     */
    public function home(): Response
    {
        $meta = [
            'title' => 'Synbarra Technologies | Software House & Custom Development',
            'subtitle' => 'Mitra Teknologi Solusi Digital & Pengembangan Sistem Bisnis Kustom',
            'description' => 'SYNBARRA TECHNOLOGIES adalah software house yang berfokus pada pengembangan sistem ERP bisnis, website, dan aplikasi mobile custom sesuai kebutuhan unik bisnis Anda.',
            'keywords' => 'Synbarra Technologies, Software House Indonesia, Jasa Pembuatan Website, Jasa Pembuatan Aplikasi Mobile, Custom ERP, HRIS Payroll, Integrasi API, Custom Software Development',
            'og_image' => 'images/posts/Synbarra_Post_01.png',
            'type' => 'website',
        ];

        return Inertia::render('Home', [
            'meta' => $meta,
        ])->withViewData(['meta' => $meta]);
    }

    /**
     * Portfolio: Showcase of built digital solutions and systems.
     */
    public function portfolio(): Response
    {
        $meta = [
            'title' => 'Portofolio Proyek & Solusi Digital | Synbarra Technologies',
            'subtitle' => 'Rekam Jejak Sistem ERP, Aplikasi Web, dan Platform Digital Terpercaya',
            'description' => 'Kumpulan proyek sukses yang telah dibangun Synbarra Technologies: Sistem ERP Mr. Lux, Portal Akademik Sekolah, Infrastruktur EV Amtech, Platform Umroh Mitra Syiar, dan CleanTech Gringgo.',
            'keywords' => 'portofolio software house, portofolio sistem erp, aplikasi web kustom, sistem enterprise, proyek synbarra technologies',
            'og_image' => 'images/portfolio/mrluxindonesia.png',
            'type' => 'website',
        ];

        return Inertia::render('Portfolio', [
            'meta' => $meta,
        ])->withViewData(['meta' => $meta]);
    }

    /**
     * Services: Dedicated service overview and business outcomes.
     */
    public function services(): Response
    {
        $meta = [
            'title' => 'Layanan Pengembangan Sistem & Solusi Digital | Synbarra Technologies',
            'subtitle' => '8 Solusi Teknologi Kustom untuk Efisiensi & Skalabilitas Bisnis Anda',
            'description' => '8 Solusi digital unggulan Synbarra Technologies: Website Company Profile, Aplikasi Mobile iOS/Android, Sistem Bisnis ERP, CRM, HRIS & Payroll, Integrasi API, UI/UX, dan Maintenance.',
            'keywords' => 'layanan software house, jasa pembuatan website, jasa aplikasi mobile flutter, jasa erp kustom, sistem crm, sistem hris, integrasi payment gateway',
            'og_image' => 'images/posts/Synbarra_Post_06.png',
            'type' => 'website',
        ];

        return Inertia::render('Services', [
            'meta' => $meta,
        ])->withViewData(['meta' => $meta]);
    }

    /**
     * Our Approach: Structured engineering methodology.
     */
    public function approach(): Response
    {
        $meta = [
            'title' => 'Metodologi & Alur Kerja 4 Tahap | Synbarra Technologies',
            'subtitle' => 'Proses Pengembangan Terstruktur: Analisis, Perancangan, Pengembangan & Delivery',
            'description' => 'Metodologi 4 tahap Synbarra Technologies: 01 Analisis Kebutuhan, 02 Perancangan Arsitektur & Prototype, 03 Pengembangan Clean Code & Strict Typing, 04 Implementasi & Garansi Support.',
            'keywords' => 'metodologi pengembangan software, alur kerja software house, arsitektur sistem, clean code, uat deployment',
            'og_image' => 'images/posts/Synbarra_Post_07.png',
            'type' => 'website',
        ];

        return Inertia::render('Approach', [
            'meta' => $meta,
        ])->withViewData(['meta' => $meta]);
    }

    /**
     * About Us: Company identity and credibility.
     */
    public function about(): Response
    {
        $meta = [
            'title' => 'Tentang Kami & Profil Perusahaan | Synbarra Technologies',
            'subtitle' => 'Software House Berdedikasi untuk Transformasi Digital Bisnis Anda',
            'description' => 'Kenalan dengan Synbarra Technologies. Kami membantu bisnis berkembang melalui rekayasa teknologi praktis, arsitektur andal, transparansi sprint, dan dukungan pemeliharaan jangka panjang.',
            'keywords' => 'tentang synbarra technologies, profil software house indonesia, visi misi synbarra, software engineer indonesia',
            'og_image' => 'images/posts/Synbarra_Post_02.png',
            'type' => 'website',
        ];

        return Inertia::render('About', [
            'meta' => $meta,
        ])->withViewData(['meta' => $meta]);
    }

    /**
     * Contact: Inquiries and project discussion.
     */
    public function contact(): Response
    {
        $meta = [
            'title' => 'Hubungi Kami & Konsultasi Sistem | Synbarra Technologies',
            'subtitle' => 'Mulai Konsultasi Gratis untuk Perancangan Sistem & Aplikasi Bisnis Anda',
            'description' => 'Diskusikan ide proyek atau tantangan operasional bisnis Anda bersama tim engineer Synbarra Technologies. Tersedia konsultasi langsung via WhatsApp atau formulir brief proyek.',
            'keywords' => 'kontak software house, konsultasi pembuatan aplikasi, tanya biaya pembuatan web, hubungi synbarra technologies',
            'og_image' => 'images/posts/Synbarra_Post_09.png',
            'type' => 'website',
        ];

        return Inertia::render('Contact', [
            'meta' => $meta,
        ])->withViewData(['meta' => $meta]);
    }

    /**
     * Handle contact form submission.
     */
    public function submitContact(Request $request)
    {
        $validated = $request->validate([
            'fullName' => 'required|string|max:120',
            'companyName' => 'nullable|string|max:150',
            'email' => 'required|email|max:150',
            'whatsappNumber' => 'required|string|max:30',
            'serviceRequired' => 'required|string|max:100',
            'estimatedBudget' => 'nullable|string|max:100',
            'projectDescription' => 'required|string|min:10|max:2000',
        ]);

        // 1. Simpan ke Database Leads agar data prospek aman dan terpantau
        try {
            Lead::create([
                'full_name' => $validated['fullName'],
                'company_name' => $validated['companyName'] ?? null,
                'email' => $validated['email'],
                'whatsapp_number' => $validated['whatsappNumber'],
                'service_required' => $validated['serviceRequired'],
                'estimated_budget' => $validated['estimatedBudget'] ?? null,
                'project_description' => $validated['projectDescription'],
                'source' => 'website_contact',
                'status' => 'new',
            ]);
        } catch (\Throwable $e) {
            Log::error('Failed to save lead into database: ' . $e->getMessage());
        }

        // 2. Kirim email notifikasi ke tim Synbarra & CC ke hasanarofid@gmail.com
        try {
            Mail::to('synbarr.techno@gmail.com')
                ->cc('hasanarofid@gmail.com')
                ->send(new ContactInquiryMail($validated));
        } catch (\Throwable $e) {
            Log::error('Failed to send contact inquiry email: ' . $e->getMessage());
        }

        Log::info('New Synbarra Inquiry captured & saved:', $validated);

        return back()->with('success', 'Terima kasih! Permintaan konsultasi Anda telah kami terima. Tim kami akan segera menghubungi Anda.');
    }

    /**
     * Generate dynamic sitemap for Google Search Console and SEO indexing.
     */
    public function sitemap(): HttpResponse
    {
        $baseUrl = config('app.url', 'https://synbarra.com');
        $baseUrl = rtrim($baseUrl, '/');
        if (app()->environment('production')) {
            $baseUrl = 'https://synbarra.com';
        }

        $staticPages = [
            ['loc' => $baseUrl . '/', 'priority' => '1.0', 'changefreq' => 'weekly'],
            ['loc' => $baseUrl . '/services', 'priority' => '0.9', 'changefreq' => 'weekly'],
            ['loc' => $baseUrl . '/portofolio', 'priority' => '0.9', 'changefreq' => 'weekly'],
            ['loc' => $baseUrl . '/approach', 'priority' => '0.8', 'changefreq' => 'monthly'],
            ['loc' => $baseUrl . '/about', 'priority' => '0.8', 'changefreq' => 'monthly'],
            ['loc' => $baseUrl . '/contact', 'priority' => '0.8', 'changefreq' => 'monthly'],
        ];

        $today = now()->format('Y-m-d');

        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

        foreach ($staticPages as $page) {
            $xml .= "  <url>\n";
            $xml .= "    <loc>{$page['loc']}</loc>\n";
            $xml .= "    <lastmod>{$today}</lastmod>\n";
            $xml .= "    <changefreq>{$page['changefreq']}</changefreq>\n";
            $xml .= "    <priority>{$page['priority']}</priority>\n";
            $xml .= "  </url>\n";
        }

        $xml .= '</urlset>';

        return response($xml, 200)
            ->header('Content-Type', 'application/xml; charset=utf-8');
    }
}
