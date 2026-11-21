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
            'title' => 'InvoiceRapi — Buat Invoice Online Rapi, DP & PDF Resmi',
            'subtitle' => 'Cara Cepat Buat Invoice Online dengan DP, Termin, PPN/PPh, dan PDF 1 Halaman',
            'description' => 'Cara cepat buat invoice online yang rapi dan resmi. Kelola DP, termin, PPN 11%, PPh 21/23, logo & rekening usaha, lalu kirim PDF 1 halaman via WhatsApp. Coba gratis!',
            'keywords' => 'invoice rapi, buat invoice, buat invoice online, buat invoice gratis, aplikasi buat invoice, buat invoice pdf, buat invoice dp dan termin, buat invoice ppn 11 persen, InvoiceRapi, Synbarra InvoiceRapi',
            'og_image' => 'images/mockups/design-system-board.png',
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
        $baseUrl = config('app.url', 'https://invoicerapi.synbarra.com');
        $baseUrl = rtrim($baseUrl, '/');
        if (app()->environment('production')) {
            $baseUrl = 'https://invoicerapi.synbarra.com';
        }

        $staticPages = array_merge(
            [
                ['loc' => $baseUrl . '/', 'priority' => '1.0', 'changefreq' => 'weekly'],
            ],
            SeoLandingController::sitemapUrls($baseUrl)
        );

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

    /**
     * Serve root /llms.txt for Generative Engine Optimization (AI Search Crawlers).
     */
    public function llmsTxt(): HttpResponse
    {
        $content = <<<'MD'
# Synbarra InvoiceRapi
> "Urus Tagihan, Jadi Lebih Rapi." — Platform SaaS manajemen klien, proyek, invoice otomatis, termin pembayaran (DP, Termin, Pelunasan), dan pemantauan piutang untuk bisnis jasa di Indonesia.

## Identitas Produk
- Domain: https://invoicerapi.synbarra.com
- Pengembang: Synbarra Technologies (https://synbarra.com)
- Target Pengguna: Wedding Organizer (WO), Fotografer & Videografer, Event Organizer (EO), Kontraktor & Desain Interior, Konsultan Jasa, dan UMKM.

## Fitur Utama
- Dashboard Finansial: Ringkasan total tagihan, sudah dibayar, piutang aktif, invoice overdue, dan grafik pendapatan bulanan.
- Manajemen Klien & Proyek: Riwayat transaksi terhubung rapi per klien dan proyek.
- Invoice & Termin Pembayaran: Buat invoice online dengan nomor otomatis, jadwal DP, Termin 1/2, dan Pelunasan dengan kalkulasi saldo akurat.
- Pajak Indonesia Otomatis: Dukungan centangan PPN (11%) yang menambah tagihan serta PPh 21 (2,5%) atau PPh 23 (2%) yang memotong tagihan otomatis.
- Logo Usaha & Rekening Pembayaran: Tampilkan logo bisnis dan tabel rekening bank tujuan transfer resmi pada setiap invoice web dan PDF A4 1 halaman.
- Tautan Publik Invoice (/i/{token}): Klien dapat melihat rincian termin, instruksi rekening, dan mengunduh PDF 1 halaman tanpa login, serta kirim cepat via WhatsApp.
- Pengingat Jatuh Tempo: Pengingat otomatis via WhatsApp & Email.

## FAQ (Pertanyaan Umum)
- Bagaimana cara buat invoice online yang rapi dan resmi? Daftar gratis di https://invoicerapi.synbarra.com/register, isi profil & rekening usaha, lalu buat invoice dan kirim tautan atau PDF A4 1 halaman ke WhatsApp klien.
- Apakah bisa buat invoice gratis di InvoiceRapi? Ya, tersedia paket Gratis selamanya hingga 5 invoice/bulan dan 10 klien, serta uji coba Pro gratis 14 hari tanpa kartu kredit.
- Apakah InvoiceRapi mendukung PPN 11% dan PPh 21/23? Ya, tersedia opsi centang PPN (11%) dan PPh 21 (2,5%) atau PPh 23 (2%) dengan perhitungan total nett otomatis.

## Paket Harga
- Gratis: Rp 0/bulan (5 invoice/bulan, 10 klien, unduh PDF, 1 pengguna).
- Pro: Rp 55.000/bulan (promo dari Rp 99.000) atau Rp 39.000/bulan jika tahunan (100 invoice/bulan, unlimited klien & proyek, jadwal DP & multi-termin, laporan piutang lengkap, trial gratis 14 hari tanpa kartu kredit).
- Bisnis: Rp 119.000/bulan (promo dari Rp 199.000) atau Rp 83.000/bulan jika tahunan (500 invoice/bulan, 5 pengguna, analitik lanjutan & ekspor CSV, pengingat terjadwal Email & WA, audit log).

## Tautan Penting
- Halaman Utama: https://invoicerapi.synbarra.com/
- Daftar / Uji Coba Gratis: https://invoicerapi.synbarra.com/register
- Halaman Produk di Synbarra: https://synbarra.com/produk/invoicerapi
MD;

        return response($content, 200)
            ->header('Content-Type', 'text/plain; charset=utf-8');
    }
}
