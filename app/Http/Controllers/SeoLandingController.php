<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;

class SeoLandingController extends Controller
{
    public function feature(string $slug): Response
    {
        $pages = $this->featurePages();
        abort_unless(isset($pages[$slug]), 404);

        $page = $pages[$slug];
        $canonical = "https://invoicerapi.synbarra.com/fitur/{$slug}";

        return $this->renderLanding($page, $canonical, 'Fitur', '/#fitur');
    }

    public function solution(string $slug): Response
    {
        $pages = $this->solutionPages();
        abort_unless(isset($pages[$slug]), 404);

        $page = $pages[$slug];
        $canonical = "https://invoicerapi.synbarra.com/solusi/{$slug}";

        return $this->renderLanding($page, $canonical, 'Solusi Bisnis', '/#solusi');
    }

    /**
     * Public indexable URLs for sitemap.xml.
     *
     * @return array<int, array{loc: string, priority: string, changefreq: string}>
     */
    public static function sitemapUrls(string $baseUrl): array
    {
        $urls = [];
        foreach (array_keys((new self())->featurePages()) as $slug) {
            $urls[] = [
                'loc' => "{$baseUrl}/fitur/{$slug}",
                'priority' => '0.9',
                'changefreq' => 'weekly',
            ];
        }
        foreach (array_keys((new self())->solutionPages()) as $slug) {
            $urls[] = [
                'loc' => "{$baseUrl}/solusi/{$slug}",
                'priority' => '0.9',
                'changefreq' => 'weekly',
            ];
        }

        return $urls;
    }

    private function renderLanding(array $page, string $canonical, string $sectionLabel, string $sectionHref): Response
    {
        $meta = [
            'title' => $page['meta_title'],
            'description' => $page['meta_description'],
            'keywords' => $page['keywords'],
            'canonical' => $canonical,
            'type' => 'website',
            'breadcrumbs' => [
                ['name' => 'Beranda', 'url' => 'https://invoicerapi.synbarra.com/'],
                ['name' => $sectionLabel, 'url' => 'https://invoicerapi.synbarra.com' . $sectionHref],
                ['name' => $page['h1'], 'url' => $canonical],
            ],
            'faqs' => $page['faqs'],
        ];

        return Inertia::render('SeoLanding', [
            'pageData' => $page,
            'sectionLabel' => $sectionLabel,
            'relatedLinks' => $this->relatedLinks($canonical),
            'meta' => $meta,
        ])->withViewData(['meta' => $meta]);
    }

    private function relatedLinks(string $excludeUrl): array
    {
        $all = [
            ['title' => 'Aplikasi Invoice Online', 'href' => '/fitur/invoice-online'],
            ['title' => 'Fitur Pencatatan DP', 'href' => '/fitur/pencatatan-dp'],
            ['title' => 'Aplikasi Pembayaran Termin', 'href' => '/fitur/pembayaran-termin'],
            ['title' => 'Monitoring Piutang Pelanggan', 'href' => '/fitur/monitoring-piutang'],
            ['title' => 'Invoice Wedding Organizer', 'href' => '/solusi/wedding-organizer'],
            ['title' => 'Invoice Event Organizer', 'href' => '/solusi/event-organizer'],
            ['title' => 'Aplikasi Invoice Kontraktor', 'href' => '/solusi/kontraktor'],
            ['title' => 'Invoice Fotografer', 'href' => '/solusi/fotografer'],
            ['title' => 'Invoice Freelancer', 'href' => '/solusi/freelancer'],
        ];

        return array_values(array_filter(
            $all,
            fn (array $item) => ! str_ends_with($excludeUrl, $item['href'])
        ));
    }

    private function featurePages(): array
    {
        return [
            'invoice-online' => [
                'badge' => 'Fitur Utama',
                'h1' => 'Aplikasi Invoice Online untuk Buat Tagihan Rapi & Resmi',
                'meta_title' => 'Aplikasi Invoice Online Rapi & Resmi | InvoiceRapi',
                'meta_description' => 'Buat invoice online profesional lengkap dengan logo usaha, rekening transfer, pajak PPN 11% & PPh 21/23, serta PDF 1 halaman siap kirim via WhatsApp.',
                'keywords' => 'aplikasi invoice online, buat invoice online, invoice rapi, buat invoice pdf gratis',
                'lead' => 'Buat invoice resmi dalam hitungan detik dari HP maupun laptop tanpa perlu edit file Word atau Excel yang sering berantakan.',
                'problem_title' => 'Kendala Membuat Tagihan Secara Manual',
                'problem' => 'Banyak pemilik usaha jasa masih menyalin template Word atau Excel setiap kali menagih klien. Akibatnya nomor invoice sering ganda, salah hitung subtotal, format PDF pecah menjadi dua halaman saat dibuka di HP, dan rekening pembayaran harus diketik ulang.',
                'solution_title' => 'Bagaimana InvoiceRapi Menyelesaikannya',
                'solution' => 'InvoiceRapi mengotomatiskan penomoran tagihan, kalkulasi subtotal hingga pajak PPN (11%) dan PPh (21/23), melampirkan logo serta rekening bank utama Anda secara otomatis, dan menghasilkan tautan publik maupun file PDF ukuran A4 1 halaman.',
                'highlights' => [
                    'Penomoran invoice otomatis (format INV/YYYYMM/0001) tanpa duplikasi.',
                    'Logo usaha & tabel rekening transfer terpasang otomatis di setiap invoice.',
                    'Opsi centang pajak PPN 11% (+11%) dan PPh 21 (2,5%) / PPh 23 (2%).',
                    'Ekspor PDF A4 1 halaman melalui mPDF dan tombol kirim instan ke WhatsApp klien.',
                ],
                'example' => [
                    'title' => 'Contoh Penggunaan Nyata: Jasa Makeup & Catering',
                    'scenario' => 'Sebuah usaha katering menerima pesanan 100 kotak nasi @ Rp30.000 dengan jatuh tempo 7 hari. Pemilik usaha memilih klien, mengisi 1 baris item, dan langsung menerbitkan invoice Rp3.000.000 dengan instruksi transfer BCA.',
                    'rows' => [
                        ['label' => 'Item', 'value' => 'Nasi Kotak Ayam Bakar (100 x Rp30.000)'],
                        ['label' => 'PPN / PPh', 'value' => 'Opsional (otomatis hitung jika dicentang)'],
                        ['label' => 'Output', 'value' => 'Tautan publik /i/{token} + PDF A4 1 halaman'],
                    ],
                ],
                'faqs' => [
                    ['q' => 'Apakah saya bisa buat invoice online gratis di InvoiceRapi?', 'a' => 'Ya, tersedia paket Gratis selamanya untuk membuat hingga 5 invoice per bulan dan menyimpan 10 klien terdaftar tanpa biaya langganan.'],
                    ['q' => 'Apakah invoice bisa langsung diunduh dalam format PDF?', 'a' => 'Ya, setiap invoice memiliki tombol Unduh PDF yang menghasilkan dokumen A4 1 halaman rapi lengkap dengan logo dan rekening pembayaran Anda.'],
                    ['q' => 'Apakah bisa digunakan dari browser HP?', 'a' => 'Ya, InvoiceRapi dirancang responsif dengan navigasi gaya aplikasi mobile sehingga nyaman dipakai langsung dari browser HP Android maupun iPhone.'],
                ],
            ],
            'pencatatan-dp' => [
                'badge' => 'Fitur Uang Muka',
                'h1' => 'Aplikasi Pencatatan DP (Down Payment) yang Akurat',
                'meta_title' => 'Aplikasi Pencatatan DP & Uang Muka Klien | InvoiceRapi',
                'meta_description' => 'Catat uang muka (DP) klien, hitung sisa tagihan otomatis, dan tampilkan riwayat pembayaran DP langsung di invoice publik.',
                'keywords' => 'aplikasi pencatatan dp, buat invoice dp, catatan uang muka klien, sisa tagihan invoice',
                'lead' => 'Hindari selisih hitung uang muka. Setiap DP yang masuk langsung mengurangi sisa piutang dan tercatat dengan bukti tanggal serta metode transfer.',
                'problem_title' => 'Risiko Salah Catat Uang Muka di Chat',
                'problem' => 'Bukti transfer DP yang hanya tersimpan di chat WhatsApp sering tenggelam. Saat hari pelunasan tiba, tim dan klien kerap berbeda catatan mengenai berapa DP yang sudah dibayar dan berapa sisa tagihannya.',
                'solution_title' => 'Pencatatan DP Terstruktur dalam Satu Invoice',
                'solution' => 'Saat membuat invoice, aktifkan mode DP / Cicilan Termin (atau klik Bagi DP 50% / 50%). Ketika klien mentransfer DP, Anda cukup klik Catat Bayar dan sisa tagihan otomatis terhitung.',
                'highlights' => [
                    'Preset instan DP 50% + Pelunasan 50% atau nominal DP bebas sesuai kesepakatan.',
                    'Riwayat pembayaran mencatat tanggal bayar, metode (Transfer/QRIS/Tunai), dan nomor referensi.',
                    'Tautan invoice klien langsung menampilkan "Sudah Dibayar" dan "Sisa Tagihan" real-time.',
                ],
                'example' => [
                    'title' => 'Contoh Penggunaan: Booking Fee Layanan Jasa',
                    'scenario' => 'Klien memesan paket layanan senilai Rp10.000.000 dengan DP 30% (Rp3.000.000) saat booking dan pelunasan Rp7.000.000 sebelum acara.',
                    'rows' => [
                        ['label' => 'Termin 1 (DP 30%)', 'value' => 'Rp3.000.000 — Status: Lunas'],
                        ['label' => 'Termin 2 (Pelunasan)', 'value' => 'Rp7.000.000 — Sisa Tagihan'],
                        ['label' => 'Total Terbayar', 'value' => 'Rp3.000.000 tercatat di riwayat pembayaran'],
                    ],
                ],
                'faqs' => [
                    ['q' => 'Apakah nominal DP harus selalu 50%?', 'a' => 'Tidak. Anda bebas mengatur nominal atau persentase DP (misalnya 20%, 30%, atau nominal tetap) sesuai perjanjian dengan klien.'],
                    ['q' => 'Apakah klien bisa melihat status DP yang sudah dibayar?', 'a' => 'Ya, melalui tautan invoice publik dan PDF terbaru, klien dapat melihat rincian Sudah Dibayar dan Sisa Tagihan secara transparan.'],
                ],
            ],
            'pembayaran-termin' => [
                'badge' => 'Fitur Multi-Termin',
                'h1' => 'Aplikasi Pembayaran Termin Proyek & Cicilan Bertahap',
                'meta_title' => 'Aplikasi Pembayaran Termin Proyek & Cicilan | InvoiceRapi',
                'meta_description' => 'Kelola tagihan proyek bertahap (DP, Termin 1, Termin 2, hingga Pelunasan) dengan jadwal jatuh tempo per termin yang jelas.',
                'keywords' => 'aplikasi pembayaran termin, invoice termin proyek, tagihan bertahap, jadwal termin pembayaran',
                'lead' => 'Satu invoice untuk seluruh tahapan proyek. Atur jadwal DP, Termin 1, Termin 2, hingga Pelunasan beserta tanggal jatuh temponya masing-masing.',
                'problem_title' => 'Tagihan Proyek Bertahap Sulit Dipantau',
                'problem' => 'Proyek bernilai besar jarang dibayar sekali lunas. Membuat invoice terpisah untuk setiap termin membuat riwayat kontrak terpecah dan menyulitkan audit total nilai proyek.',
                'solution_title' => 'Jadwal Termin Terpadu di Satu Dokumen',
                'solution' => 'InvoiceRapi memungkinkan Anda memecah satu nilai kontrak menjadi beberapa termin pembayaran dengan jatuh tempo berbeda, dan mencatat pelunasan per termin secara independen.',
                'highlights' => [
                    'Tambah termin pembayaran sesuai milestone proyek (DP, Progres 50%, BAST/Pelunasan).',
                    'Setiap termin memiliki tanggal jatuh tempo dan status lunas masing-masing.',
                    'Total tagihan terbayar dan sisa piutang selalu sinkron dengan pembayaran tiap termin.',
                ],
                'example' => [
                    'title' => 'Contoh Penggunaan: Proyek Renovasi / Website 3 Termin',
                    'scenario' => 'Proyek senilai Rp30.000.000 dibagi menjadi Termin 1 (DP Rp10.000.000), Termin 2 (Progres Rp10.000.000), dan Termin 3 (Serah Terima Rp10.000.000).',
                    'rows' => [
                        ['label' => 'Termin 1 (DP)', 'value' => 'Rp10.000.000 — Tempo Hari ke-1'],
                        ['label' => 'Termin 2 (Progres)', 'value' => 'Rp10.000.000 — Tempo Hari ke-14'],
                        ['label' => 'Termin 3 (Pelunasan)', 'value' => 'Rp10.000.000 — Tempo Hari ke-30'],
                    ],
                ],
                'faqs' => [
                    ['q' => 'Berapa banyak termin yang bisa ditambahkan dalam satu invoice?', 'a' => 'Pada paket Pro dan Bisnis, Anda dapat menambahkan termin pembayaran sesuai kebutuhan tahapan proyek Anda.'],
                    ['q' => 'Bagaimana jika klien membayar sebagian dari satu termin?', 'a' => 'Sistem mencatat pembayaran parsial dan menandai status termin sebagai Sebagian hingga nominal termin terpenuhi.'],
                ],
            ],
            'monitoring-piutang' => [
                'badge' => 'Fitur Kontrol Piutang',
                'h1' => 'Aplikasi Monitoring Piutang Pelanggan Secara Real-Time',
                'meta_title' => 'Aplikasi Monitoring Piutang Pelanggan & Jatuh Tempo | InvoiceRapi',
                'meta_description' => 'Pantau total tagihan, pembayaran masuk, sisa piutang aktif, dan invoice terlambat jatuh tempo dari satu dashboard interaktif.',
                'keywords' => 'aplikasi monitoring piutang, pantau piutang pelanggan, dashboard tagihan jatuh tempo',
                'lead' => 'Lihat kesehatan arus kas bisnis jasa Anda dalam sekali lihat: mana yang sudah lunas, mana yang masih piutang, dan mana yang melewati jatuh tempo.',
                'problem_title' => 'Lupa Menagih Membuat Cashflow Tersendat',
                'problem' => 'Tanpa monitoring piutang terpusat, tagihan yang melewati tanggal jatuh tempo sering terlewat karena tidak ada notifikasi otomatis dan rekap piutang per klien.',
                'solution_title' => 'Dashboard Piutang + Notifikasi Jatuh Tempo Otomatis',
                'solution' => 'Dashboard InvoiceRapi menampilkan 4 metrik utama yang dapat diklik langsung menuju daftar invoice terkait, dilengkapi lonceng notifikasi di pojok kanan atas untuk tagihan mendekati atau melewati jatuh tempo.',
                'highlights' => [
                    'Kartu KPI interaktif: Total Tagihan, Sudah Dibayar, Piutang, dan Terlambat.',
                    'Notifikasi otomatis untuk invoice yang jatuh tempo dalam 3 hari atau sudah overdue.',
                    'Grafik pendapatan bulanan 6 bulan terakhir untuk memantau tren pemasukan.',
                ],
                'example' => [
                    'title' => 'Contoh Penggunaan: Evaluasi Tagihan Mingguan',
                    'scenario' => 'Setiap Senin pagi, pemilik bisnis membuka Dashboard, mengklik kartu Piutang untuk melihat daftar invoice belum lunas, lalu mengirim tautan tagihan ke WhatsApp klien dalam 1 klik.',
                    'rows' => [
                        ['label' => 'Filter Sekali Klik', 'value' => 'Belum Lunas / Terlambat / Lunas'],
                        ['label' => 'Tindak Lanjut', 'value' => 'Tombol Kirim ke WA langsung dari detail invoice'],
                    ],
                ],
                'faqs' => [
                    ['q' => 'Apakah ada pengingat untuk tagihan yang hampir jatuh tempo?', 'a' => 'Ya, lonceng notifikasi di panel member secara otomatis menampilkan tagihan yang jatuh tempo dalam 3 hari ke depan maupun yang sudah terlambat.'],
                    ['q' => 'Apakah piutang dihitung otomatis saat pembayaran dicatat?', 'a' => 'Ya, setiap pencatatan pembayaran langsung memperbarui saldo piutang di dashboard dan status invoice.'],
                ],
            ],
        ];
    }

    private function solutionPages(): array
    {
        return [
            'wedding-organizer' => [
                'badge' => 'Solusi Wedding Organizer',
                'h1' => 'Invoice Wedding Organizer untuk Booking Fee, Termin & Pelunasan',
                'meta_title' => 'Aplikasi Invoice Wedding Organizer (WO) | InvoiceRapi',
                'meta_description' => 'Kelola tagihan paket pernikahan, booking fee, DP, termin vendor, dan pelunasan H-14 klien Wedding Organizer secara rapi dan profesional.',
                'keywords' => 'invoice wedding organizer, aplikasi invoice wo, contoh invoice paket pernikahan, tagihan dp wedding',
                'lead' => 'Buat pasangan pengantin semakin percaya sejak booking fee pertama. Semua rincian paket dekorasi, MUA, dokumentasi, dan jadwal pelunasan tercatat rapi.',
                'problem_title' => 'Tantangan Penagihan Bisnis Wedding Organizer',
                'problem' => 'Satu acara pernikahan bisa melibatkan pembayaran bertahap selama 3–12 bulan dengan tambahan item ad-hoc di tengah persiapan. Tanpa invoice terstruktur, WO sering kesulitan melacak sisa pelunasan H-14.',
                'solution_title' => 'Standar Penagihan Profesional untuk Tim WO',
                'solution' => 'InvoiceRapi membantu WO membuat proyek per pasangan pengantin, menyusun jadwal termin (Booking Fee, DP 50%, Pelunasan H-14), serta mengirimkan tautan invoice berlogo WO resmi lewat WhatsApp.',
                'highlights' => [
                    'Kelompokkan tagihan per proyek acara pengantin agar riwayat tidak tercampur.',
                    'Kop invoice resmi lengkap dengan logo WO dan nomor rekening usaha.',
                    'Notifikasi jatuh tempo membantu tim menagih pelunasan sebelum hari H.',
                ],
                'example' => [
                    'title' => 'Contoh Tagihan Paket Wedding Lengkap',
                    'scenario' => 'Paket Intimate Wedding Rp45.000.000 dibagi menjadi Booking Fee Rp5.000.000, DP 50% Rp17.500.000, dan Pelunasan Rp22.500.000.',
                    'rows' => [
                        ['label' => 'Booking Fee', 'value' => 'Rp5.000.000 (Mengunci tanggal acara)'],
                        ['label' => 'Termin 2 (H-60)', 'value' => 'Rp17.500.000'],
                        ['label' => 'Pelunasan (H-14)', 'value' => 'Rp22.500.000'],
                    ],
                ],
                'faqs' => [
                    ['q' => 'Apakah logo Wedding Organizer saya bisa tampil di invoice?', 'a' => 'Ya, cukup unggah logo usaha Anda di halaman Profil Akun dan logo otomatis muncul di panel, tautan web, serta PDF invoice.'],
                    ['q' => 'Apakah klien harus login untuk melihat invoice?', 'a' => 'Tidak. Klien cukup membuka tautan publik yang dikirim via WhatsApp dan dapat langsung mengunduh PDF resminya.'],
                ],
            ],
            'event-organizer' => [
                'badge' => 'Solusi Event Organizer',
                'h1' => 'Invoice Event Organizer untuk Klien Korporat & Acara Resmi',
                'meta_title' => 'Aplikasi Invoice Event Organizer (EO) & PPN/PPh | InvoiceRapi',
                'meta_description' => 'Terbitkan invoice resmi Event Organizer dengan perhitungan otomatis PPN 11%, PPh 23 (2%), rincian produksi acara, dan termin DP.',
                'keywords' => 'invoice event organizer, aplikasi invoice eo, invoice korporat pph 23, buat invoice acara',
                'lead' => 'Menagih klien perusahaan kini lebih cepat karena InvoiceRapi mendukung perhitungan PPN 11% dan potong PPh 23 (2%) langsung di dalam dokumen tagihan.',
                'problem_title' => 'Syarat Administrasi Klien Korporat yang Ketat',
                'problem' => 'Divisi finance perusahaan mensyaratkan invoice resmi dengan rincian DPP, PPN 11%, potongan PPh 23, nomor rekening perusahaan, dan format PDF 1 halaman yang rapi.',
                'solution_title' => 'Siap Memenuhi Standar Finance Perusahaan',
                'solution' => 'Cukup centang PPN (11%) dan PPh 23 (2%) saat membuat invoice, pilih rekening perusahaan Anda, dan unduh PDF A4 siap kirim ke bagian pengadaan atau finance klien.',
                'highlights' => [
                    'Kalkulasi DPP, +PPN 11%, dan -PPh 23 (2%) otomatis hingga Total Nett.',
                    'Mendukung multi-item untuk rincian sewa venue, talent, sound system, dan produksi.',
                    'Nomor rekening dan berita transfer otomatis terformat rapi pada kotak pembayaran.',
                ],
                'example' => [
                    'title' => 'Contoh Invoice Gathering Perusahaan (PPN + PPh 23)',
                    'scenario' => 'Jasa penyelenggaraan gathering senilai DPP Rp20.000.000 dengan PPN 11% dan potongan PPh 23 sebesar 2%.',
                    'rows' => [
                        ['label' => 'DPP', 'value' => 'Rp20.000.000'],
                        ['label' => 'PPN (11%)', 'value' => '+Rp2.200.000'],
                        ['label' => 'PPh 23 (2%)', 'value' => '(Rp400.000)'],
                        ['label' => 'Total Nett Diterima', 'value' => 'Rp21.800.000'],
                    ],
                ],
                'faqs' => [
                    ['q' => 'Bagaimana tampilan potongan PPh 23 di PDF invoice?', 'a' => 'Potongan PPh 23 ditampilkan jelas dalam tanda kurung (Rp ...) dan otomatis mengurangi total nett yang harus ditransfer klien.'],
                    ['q' => 'Apakah bisa menambahkan lebih dari satu rekening bank?', 'a' => 'Ya, Anda dapat menyimpan beberapa rekening bank di Profil Akun dan memilih rekening tujuan pada tiap invoice.'],
                ],
            ],
            'kontraktor' => [
                'badge' => 'Solusi Kontraktor & Interior',
                'h1' => 'Aplikasi Invoice Kontraktor untuk Tagihan Progres Proyek',
                'meta_title' => 'Aplikasi Invoice Kontraktor & Desain Interior | InvoiceRapi',
                'meta_description' => 'Kelola tagihan progres proyek konstruksi, renovasi, dan desain interior dengan multi-termin, nilai kontrak, dan monitoring piutang.',
                'keywords' => 'aplikasi invoice kontraktor, invoice progres proyek, invoice desain interior, tagihan termin renovasi',
                'lead' => 'Pantau nilai kontrak proyek konstruksi atau interior Anda mulai dari termin awal material hingga serah terima akhir (retensi/pelunasan).',
                'problem_title' => 'Nilai Kontrak Besar Butuh Kontrol Termin Ketat',
                'problem' => 'Proyek renovasi atau desain interior memiliki tahapan pembayaran (30% - 40% - 25% - 5%) yang sering molor jika tidak dipantau berdasarkan nilai kontrak proyek.',
                'solution_title' => 'Hubungkan Proyek, Nilai Kontrak, dan Termin Invoice',
                'solution' => 'Catat proyek beserta nilai kontraknya terlebih dahulu, lalu terbitkan invoice dengan jadwal termin bertahap yang sesuai progres pekerjaan di lapangan.',
                'highlights' => [
                    'Manajemen proyek terhubung langsung ke daftar klien dan invoice.',
                    'Multi-termin fleksibel untuk skema pembayaran progres lapangan.',
                    'Mendukung potongan pajak PPh Jasa Konstruksi / PPh 23 (2%).',
                ],
                'example' => [
                    'title' => 'Contoh Tagihan Proyek Desain & Build Interior',
                    'scenario' => 'Kontrak pengerjaan kitchen set & ruang tamu senilai Rp50.000.000 dengan skema 3 termin pembayaran.',
                    'rows' => [
                        ['label' => 'Termin 1 (DP Material 40%)', 'value' => 'Rp20.000.000'],
                        ['label' => 'Termin 2 (Instalasi 40%)', 'value' => 'Rp20.000.000'],
                        ['label' => 'Termin 3 (BAST 20%)', 'value' => 'Rp10.000.000'],
                    ],
                ],
                'faqs' => [
                    ['q' => 'Apakah setiap invoice bisa dikaitkan dengan nama proyek tertentu?', 'a' => 'Ya, Anda dapat memilih proyek yang terkait saat membuat invoice sehingga nama proyek tampil di invoice dan PDF.'],
                    ['q' => 'Bagaimana jika proyek dibatalkan atau diarsipkan?', 'a' => 'Data klien, proyek, dan invoice menggunakan sistem soft delete sehingga riwayat arsip tetap aman untuk audit internal.'],
                ],
            ],
            'fotografer' => [
                'badge' => 'Solusi Fotografer & Studio',
                'h1' => 'Invoice Fotografer & Videografer Praktis dari HP',
                'meta_title' => 'Aplikasi Invoice Fotografer, Videografer & MUA | InvoiceRapi',
                'meta_description' => 'Buat invoice jasa foto wedding, prewedding, wisuda, dan MUA langsung dari HP. Atur DP booking tanggal dan kirim PDF resmi ke WhatsApp klien.',
                'keywords' => 'invoice fotografer, buat invoice jasa foto, invoice mua wisuda, aplikasi tagihan studio foto',
                'lead' => 'Habis survei lokasi atau chat klien di Instagram? Langsung buat invoice DP booking tanggal dari HP dan kirim ke WhatsApp dalam 1 menit.',
                'problem_title' => 'Klien Sering Ragu Jika Penagihan Tidak Resmi',
                'problem' => 'Mengirim total harga hanya lewat teks chat WhatsApp terlihat kurang meyakinkan bagi klien wedding atau wisuda, apalagi saat meminta transfer DP terlebih dahulu.',
                'solution_title' => 'Tampilan Invoice Studio yang Elegan & Terpercaya',
                'solution' => 'Dengan tampilan minimalis berlogo studio Anda, rincian paket foto/MUA, dan rekening transfer resmi, klien lebih cepat percaya dan segera melakukan transfer DP.',
                'highlights' => [
                    'Navigasi mobile-app bottom bar memudahkan pembuatan invoice dari browser HP.',
                    'Tambah klien baru langsung di form buat invoice tanpa perlu pindah halaman.',
                    'Tombol Kirim ke WA otomatis menyusun pesan tagihan beserta tautan invoice.',
                ],
                'example' => [
                    'title' => 'Contoh Tagihan Jasa Foto & MUA Wisuda',
                    'scenario' => 'Layanan MUA + Dokumentasi Wisuda senilai Rp1.500.000 dengan DP Rp500.000 untuk mengunci jadwal dan pelunasan Rp1.000.000 di hari H.',
                    'rows' => [
                        ['label' => 'Layanan', 'value' => 'Paket Foto & MUA Wisuda (1x)'],
                        ['label' => 'DP Booking', 'value' => 'Rp500.000'],
                        ['label' => 'Pelunasan Hari H', 'value' => 'Rp1.000.000'],
                    ],
                ],
                'faqs' => [
                    ['q' => 'Apakah email klien wajib diisi saat membuat invoice cepat?', 'a' => 'Tidak wajib. Kolom email dan perusahaan bersifat opsional sehingga cukup isi nama klien dan nomor WhatsApp saja.'],
                    ['q' => 'Apakah format PDF-nya pas 1 halaman jika dibuka di HP?', 'a' => 'Ya, PDF dibuat di server menggunakan mPDF dengan ukuran standar A4 1 halaman agar tidak terpotong di perangkat mana pun.'],
                ],
            ],
            'freelancer' => [
                'badge' => 'Solusi Freelancer & Konsultan',
                'h1' => 'Invoice Freelancer Profesional Lengkap dengan PPh 21 & PDF',
                'meta_title' => 'Aplikasi Invoice Freelancer & Konsultan Indonesia | InvoiceRapi',
                'meta_description' => 'Buat invoice jasa freelance desain, programming, penulis, dan konsultan lengkap dengan opsi PPh 21 (2,5%), rekening bank, dan ekspor PDF.',
                'keywords' => 'invoice freelancer, buat invoice freelancer indonesia, invoice jasa konsultasi pph 21, aplikasi tagihan freelance',
                'lead' => 'Fokus mengerjakan proyek klien, biarkan InvoiceRapi mengurus penomoran tagihan, hitungan pajak PPh 21, dan rekap penghasilan bulanan Anda.',
                'problem_title' => 'Tagihan Freelancer Sering Terhambat Salah Hitung Pajak',
                'problem' => 'Saat menagih klien perusahaan sebagai tenaga ahli/freelancer, sering terjadi perbedaan hitungan antara tagihan bruto dan nilai bersih setelah dipotong PPh 21 sebesar 2,5%.',
                'solution_title' => 'Hitung Bruto hingga Total Nett Otomatis',
                'solution' => 'Cukup centang opsi PPh 21 (2,5%) di form invoice, maka sistem langsung menampilkan pengurangan pajak dalam tanda kurung dan menghitung total nett yang Anda terima.',
                'highlights' => [
                    'Opsi centang PPh 21 (2,5%) atau PPh 23 (2%) serta PPN (11%) otomatis.',
                    'Paket Gratis permanen cocok untuk freelancer yang baru mulai membangun portofolio.',
                    'Grafik pendapatan 6 bulan di dashboard membantu memantau pemasukan freelance.',
                ],
                'example' => [
                    'title' => 'Contoh Invoice Jasa Pengembangan Website (PPh 21)',
                    'scenario' => 'Freelancer web developer menagih jasa pembuatan landing page senilai Rp4.000.000 ke klien perusahaan dengan potongan PPh 21 (2,5%).',
                    'rows' => [
                        ['label' => 'Nilai Jasa (DPP)', 'value' => 'Rp4.000.000'],
                        ['label' => 'Potongan PPh 21 (2,5%)', 'value' => '(Rp100.000)'],
                        ['label' => 'Total Nett Diterima', 'value' => 'Rp3.900.000'],
                    ],
                ],
                'faqs' => [
                    ['q' => 'Apakah freelancer bisa menggunakan InvoiceRapi secara gratis?', 'a' => 'Ya, paket Gratis tersedia tanpa batas waktu untuk hingga 5 invoice/bulan dan 10 klien, ideal untuk kebutuhan freelance rutin.'],
                    ['q' => 'Apakah PPh 21 dan PPh 23 bisa dicentang bersamaan?', 'a' => 'Tidak. Sesuai ketentuan perpajakan, sistem membatasi hanya satu jenis PPh (PPh 21 atau PPh 23) yang dapat dipilih pada satu invoice.'],
                ],
            ],
        ];
    }
}
