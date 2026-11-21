# Strategi Teknis SEO & GEO: "Invoice Rapi" & "Buat Invoice"

Dokumen ini adalah panduan teknis **Search Engine Optimization (SEO)** dan **Generative Engine Optimization (GEO)** untuk menaikkan peringkat **`https://invoicerapi.synbarra.com/`** dan **`https://synbarra.com/`** pada Google Search maupun mesin pencari berbasis AI (Google AI Overviews, ChatGPT Search, Perplexity, dan Gemini).

---

## 1. Target Keyword & Pemetaan Intent

| Tipe Keyword | Kata Kunci Target | Search Intent | Halaman Tujuan |
| :--- | :--- | :--- | :--- |
| **Primary Brand + Core** | `invoice rapi`, `invoicerapi` | Navigational / Commercial | `https://invoicerapi.synbarra.com/` |
| **High-Volume Action** | `buat invoice`, `buat invoice online`, `buat invoice gratis` | Transactional / Tool | `https://invoicerapi.synbarra.com/` & `/register` |
| **Feature Long-Tail** | `buat invoice dp dan termin`, `aplikasi invoice pdf`, `format invoice resmi` | Commercial Investigation | Landing section + Artikel Edukasi di `synbarra.com` |
| **Niche Bisnis Jasa** | `aplikasi invoice wedding organizer`, `buat invoice fotografer`, `invoice freelancer indonesia` | Niche Transactional | Halaman Solusi / Blog Cluster |

---

## 1B. Saran Bank Keyword Relevan (SEO & GEO)

Berikut daftar keyword yang relevan dengan fitur nyata **InvoiceRapi** dan perilaku pencarian pelaku usaha jasa/UMKM di Indonesia:

### A. Keyword Transactional & Tool (Fokus untuk `invoicerapi.synbarra.com`)
Cocok untuk Title, H1, H2, dan Meta Description halaman utama & daftar:
- `buat invoice` / `buat invoice online` / `buat invoice online gratis`
- `aplikasi buat invoice` / `website buat invoice` / `bikin invoice di hp`
- `buat invoice pdf` / `buat invoice pdf gratis` / `generator invoice indonesia`
- `invoice rapi` / `aplikasi invoice rapi` / `aplikasi penagihan bisnis jasa`
- `kirim invoice lewat whatsapp` / `link invoice online`

### B. Keyword Fitur Spesifik (Kompetisi Rendah, Konversi Tinggi)
Cocok untuk sub-section fitur di `Home.vue` dan artikel tutorial di `synbarra.com`:
- **DP & Termin**: `buat invoice dp dan termin`, `contoh invoice dp 50 persen`, `aplikasi tagihan bertahap proyek`, `cara mencatat pembayaran dp dan pelunasan`
- **Pajak Indonesia**: `buat invoice ppn 11 persen`, `cara hitung ppn dan pph 21 di invoice`, `contoh invoice potong pph 23 jasa`, `kalkulator invoice ppn pph`
- **Rekening & Logo Usaha**: `invoice resmi dengan logo perusahaan`, `template invoice rekening bank transfer`

### C. Keyword Niche Industri Jasa (Fokus Konten Pillar di `synbarra.com`)
Target langsung segmen usaha yang paling sering mengirim tagihan DP/pelunasan:
- **Wedding, MUA & Fotografi**: `contoh invoice wedding organizer`, `buat invoice jasa foto wedding`, `format invoice makeup artist (mua)`, `kwitansi dp booking wedding`
- **Katering & UMKM Event**: `buat invoice catering nasi kotak`, `contoh nota tagihan catering dp`, `invoice pesanan acara resmi`
- **Kontraktor, Interior & Renovasi**: `format invoice termin proyek kontraktor`, `contoh tagihan progres proyek interior`
- **Freelancer & Digital Agency**: `buat invoice freelancer indonesia`, `aplikasi invoice jasa desain website`, `contoh invoice jasa konsultasi`

### D. Keyword Prompt GEO (Pertanyaan Natural di ChatGPT / Perplexity / Gemini)
Gunakan kalimat tanya ini persis sebagai heading **FAQ** dan **H2/H3** agar LLM mengutip *InvoiceRapi* sebagai jawaban:
1. *"Aplikasi buat invoice online yang rapi dan gratis di Indonesia apa?"*
2. *"Bagaimana cara buat invoice resmi dari HP lalu kirim PDF ke WhatsApp?"*
3. *"Rekomendasi aplikasi invoice yang bisa atur DP, termin, dan pajak PPN 11% / PPh 21?"*
4. *"Software invoice ringan untuk UMKM, wedding organizer, dan freelancer Indonesia?"*

---

## 2. Arsitektur Authority: `synbarra.com` ↔ `invoicerapi.synbarra.com`

Agar kedua domain saling menguatkan tanpa kanibalisasi keyword:

1. **`synbarra.com` (Parent Brand & Content Authority Hub)**
   - Fokus sebagai perusahaan pengembang (**Synbarra Technologies**) dan pusat artikel edukasi/studi kasus (*"Cara Membuat Invoice Resmi"*, *"Perbedaan DP, Termin, dan Pelunasan"*, *"Contoh Invoice Jasa Profesional"*).
   - Setiap artikel & halaman produk di `synbarra.com` wajib menautkan **contextual backlink** (`dofollow`) dengan anchor text relevan seperti `buat invoice online di InvoiceRapi` atau `aplikasi Invoice Rapi` menuju `https://invoicerapi.synbarra.com/`.
2. **`invoicerapi.synbarra.com` (Product & Transactional App)**
   - Fokus pada konversi: halaman utama (`/`), halaman daftar (`/register`), serta landing page spesifik (*"Buat Invoice Online Gratis & Resmi"*).
   - Halaman privat member (`/dashboard`, `/invoices/*`, `/i/{token}`) **wajib** `noindex, nofollow` agar crawl budget Google fokus ke halaman publik yang bernilai SEO.

---

## 3. Teknik SEO (Google Search)

### A. Technical SEO & Rendering (Kritis untuk Laravel + Inertia/Vue)
Karena kedua repo menggunakan **Laravel + Inertia.js (Vue)**, crawler Google kadang lambat merender halaman yang 100% client-side JS. Terapkan langkah berikut:
1. **Aktifkan Inertia SSR atau Server-Rendered HTML Fallback** pada halaman publik (`/`):
   - Pastikan `<title>`, `<meta name="description">`, `<link rel="canonical">`, OpenGraph, dan JSON-LD dirender langsung di Blade root (`resources/views/app.blade.php`) oleh server, bukan hanya disuntikkan JS.
   - Tambahkan konten ringkasan HTML terstruktur (atau `<noscript>` / SSR) agar bot yang tidak menjalankan JS tetap membaca teks utama `"Invoice Rapi"` dan `"Buat Invoice"`.
2. **Optimasi Meta Tag Halaman Utama (`invoicerapi.synbarra.com`)**:
   - **Title (55–60 karakter)**: `InvoiceRapi — Buat Invoice Online Rapi, DP & PDF Resmi`
   - **Meta Description (145–155 karakter)**: `Cara cepat buat invoice online yang rapi dan resmi. Kelola DP, termin, PPN/PPh, rekening pembayaran, dan kirim PDF 1 halaman via WhatsApp. Coba gratis!`
   - **Canonical URL**: `<link rel="canonical" href="https://invoicerapi.synbarra.com/" />`
3. **Struktur Heading Semantik**:
   - Hanya **1 tag `<h1>`** per halaman, memuat keyword utama (contoh: `Buat Invoice Online Lebih Rapi, Resmi, dan Siap Kirim PDF`).
   - Gunakan `<h2>` untuk bagian utama: *Cara Buat Invoice dalam 3 Langkah*, *Fitur Pajak PPN/PPh & Termin DP*, *Harga Paket Promosi*, *FAQ*.
4. **`robots.txt` & `sitemap.xml`**:
   - Pastikan `public/robots.txt` mengizinkan crawl `/` dan memblokir `/admin`, `/dashboard`, `/invoices`, `/profile`, `/i/` (tautan invoice klien privat), serta mencantumkan `Sitemap: https://invoicerapi.synbarra.com/sitemap.xml`.
   - Submit `sitemap.xml` kedua domain ke **Google Search Console** dan **Bing Webmaster Tools** (Bing penting karena dipakai oleh ChatGPT Search).
5. **Core Web Vitals**:
   - Gunakan `loading="lazy"` pada gambar non-above-the-fold, format `.webp`, dan kompresi Vite (`npm run build` sudah menghasilkan asset ter-hash).

---

## 4. Teknik GEO (Generative Engine Optimization — AI Search)

Agar **ChatGPT, Perplexity, Gemini, dan Google AI Overview** merekomendasikan *InvoiceRapi* saat pengguna bertanya *"rekomendasi aplikasi buat invoice yang rapi di Indonesia"* atau *"cara buat invoice online gratis"*:

### A. Optimasi `/llms.txt` (Standar Crawler AI)
- File `/llms.txt` sudah tersedia di `invoiceRapi` (`routes/web.php` -> `SynbarraController@llmsTxt`).
- Pastikan isinya selalu sinkron dengan fakta terbaru:
  - Definisi singkat 1 kalimat (*entity clarity*): `InvoiceRapi adalah aplikasi SaaS Indonesia untuk buat invoice online yang rapi, PDF 1 halaman resmi, dukungan DP/termin, PPN 11% & PPh 21/23, serta kirim tagihan via WhatsApp.`
  - Daftar harga promo terbaru (`Gratis Rp 0`, `Pro Rp 55.000/bln`, `Bisnis Rp 119.000/bln`).
  - Terapkan hal yang sama di `synbarra.com/llms.txt` dengan menyebut **InvoiceRapi** sebagai produk unggulan Synbarra Technologies beserta tautan langsungnya.

### B. Structured Data (JSON-LD) di `resources/views/app.blade.php`
Pasang 3 schema berikut secara bersamaan pada halaman publik:
1. **`SoftwareApplication`**:
   - `name`: `"InvoiceRapi"`
   - `applicationCategory`: `"BusinessApplication"`
   - `operatingSystem`: `"Web, Android, iOS Browser"`
   - `offers`: cantumkan `price: "0"` (Gratis) dan `price: "55000"` (`priceCurrency: "IDR"`).
2. **`FAQPage`**:
   - Mesin AI sangat sering mengutip blok FAQ verbatim. Masukkan 5–7 pertanyaan persis seperti prompt user:
     - *"Bagaimana cara buat invoice online yang rapi dan resmi?"*
     - *"Apakah bisa buat invoice gratis di InvoiceRapi?"*
     - *"Apakah InvoiceRapi mendukung perhitungan PPN 11% dan PPh 21/23?"*
     - *"Bisakah mengatur pembayaran DP dan termin bertahap?"*
3. **`Organization` + `sameAs`**:
   - Di `synbarra.com`, hubungkan entitas `Synbarra Technologies` ke `https://invoicerapi.synbarra.com/` melalui properti `owns` / `makesOffer` agar Knowledge Graph mengenali hubungan brand induk dan produk.

### C. Gaya Penulisan "Citability" (Disukai LLM)
1. **Paragraf Jawaban Langsung (40–60 kata)** di awal section:
   - Contoh: *"Untuk buat invoice online yang rapi di Indonesia, InvoiceRapi menyediakan form cepat berisi data klien, rincian jasa, opsi pajak PPN 11% dan PPh 21/23 otomatis, rekening tujuan transfer, serta ekspor PDF 1 halaman ukuran A4 yang siap dikirim lewat WhatsApp."*
2. **Tabel Perbandingan Eksplisit**:
   - AI sangat suka mengekstrak tabel Markdown/HTML (misal: *Invoice Manual Word/Excel vs InvoiceRapi*, atau *Tabel Paket Gratis vs Pro Rp 55.000 vs Bisnis Rp 119.000*).
3. **Fakta Angka Konkret**:
   - Selalu sebutkan angka spesifik (*14 hari uji coba gratis*, *5 invoice/bulan gratis*, *PDF A4 1 halaman*, *PPN 11%*, *PPh 21 2,5%*, *PPh 23 2%*) — model AI memprioritaskan sumber dengan fakta terverifikasi dibanding klaim pemasaran umum.

---

## 5. Rencana Konten Cluster (Anti-Duplikat, High Impact)

### Di `synbarra.com` (Repo `/home/hasanarofid/Documents/synbarra/synbarra`)
Buat 4 artikel pilar di blog `synbarra.com` yang semuanya mengarah ke `https://invoicerapi.synbarra.com/`:
1. *"Cara Buat Invoice Online Gratis & Resmi dalam 1 Menit (Panduan UMKM)"*
2. *"Contoh Format Invoice Pembayaran DP & Termin untuk Wedding Organizer & Kontraktor"*
3. *"Cara Menghitung PPN 11% dan PPh 21/23 di Invoice Jasa secara Otomatis"*
4. *"7 Kesalahan Membuat Tagihan Klien yang Membuat Pembayaran Telat (dan Solusinya)"*

### Di `invoicerapi.synbarra.com` (Repo `invoiceRapi`)
1. Pastikan `Home.vue` memiliki section **Cara Buat Invoice** (3 langkah bernomor `HowTo`), **Tabel Perbandingan**, dan **FAQ** dengan heading jelas.
2. Tambahkan meta & JSON-LD `FAQPage` + `SoftwareApplication` terbaru (dengan harga promo Rp 55.000) di `resources/views/app.blade.php`.

---

## 6. Checklist Eksekusi Mingguan

- [ ] Verifikasi `https://synbarra.com/` dan `https://invoicerapi.synbarra.com/` di **Google Search Console** & **Bing Webmaster Tools**.
- [ ] Submit `sitemap.xml` dari kedua domain dan uji *URL Inspection -> Request Indexing*.
- [ ] Pastikan `public/robots.txt` tidak memblokir bot AI utama (`OAI-SearchBot`, `PerplexityBot`, `Google-Extended`, `GPTBot`) pada halaman publik `/`, `/llms.txt`, dan `/produk`.
- [ ] Cek skor **PageSpeed Insights** (target Mobile >= 85, Desktop >= 95) & **Rich Results Test** Google untuk memvalidasi JSON-LD tanpa error.
- [ ] Uji visibilitas GEO setiap 2 minggu dengan menanyakan ke ChatGPT / Perplexity / Gemini: *"aplikasi buat invoice online yang rapi untuk bisnis jasa Indonesia"*.

