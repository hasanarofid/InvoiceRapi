# Synbarra InvoiceRapi

> **"Urus Tagihan, Jadi Lebih Rapi."**  
> Solusi SaaS manajemen klien, proyek, invoice, termin pembayaran (DP, Termin, Pelunasan), dan pemantauan piutang untuk bisnis jasa di Indonesia.

- **Produk:** Synbarra InvoiceRapi (Produk SaaS resmi dari Synbarra Technologies)
- **Domain:** `https://invoicerapi.synbarra.com`
- **Target Pasar:** Wedding Organizer, Fotografer/Videografer, Event Organizer, Kontraktor/Desain Interior, dan Konsultan Jasa Profesional.
- **Tech Stack:** Laravel 13, Inertia.js v2 + Vue 3, Tailwind CSS, TypeScript, MySQL, Redis, Nginx.

---

## 🎨 Brand Identity & Design Tokens

Mengacu langsung pada Brand Board & Mockup resmi (`public/images/mockups/design-system-board.png`):

| Token | HEX Code | Tailwind Class | Penggunaan |
|---|---|---|---|
| **Primary** | `#6D28D9` | `bg-violet-700` / `text-violet-700` | Warna brand utama, tombol CTA, navigasi aktif, badge Pro |
| **Secondary** | `#8B5CF6` | `bg-violet-500` / `text-violet-500` | Aksen sekunder, hover glow, garis grafik pendapatan |
| **Background Tint** | `#EDE9FE` | `bg-violet-100` / `border-violet-100` | Latar badge, hover ringan, kontur card |
| **Success** | `#22C55E` | `bg-emerald-500` / `text-emerald-500` | Status **Lunas**, icon checklist termin pembayaran |
| **Warning** | `#F59E0B` | `bg-amber-500` / `text-amber-500` | Status **Dibayar Sebagian**, piutang aktif |
| **Danger** | `#EF4444` | `bg-red-500` / `text-red-500` | Status **Terlambat (Overdue)**, alert piutang jatuh tempo |
| **Dark Heading** | `#0F172A` | `text-slate-900` | Heading dan nominal rupiah utama |
| **Body Text** | `#475569` | `text-slate-600` | Teks keterangan, deskripsi tabel, informasi invoice |

---

## 📱 Aset Brand & Mockup (Cropped)

- **Logo Utama:** `public/images/logo/invoicerapi-logo-full.png`
- **Logo Horizontal:** `public/images/logo/invoicerapi-logo-horizontal.png`
- **Logo Purple Badge:** `public/images/logo/invoicerapi-logo-purple.png`
- **Favicon & Icon:** `public/images/logo/invoicerapi-icon.png` & `public/favicon.png`
- **Mockup Hero (Laptop & Mobile):** `public/images/mockups/mockup-hero.png`
- **Mockup Dashboard Desktop:** `public/images/mockups/mockup-dashboard.png`
- **Mockup Mobile Screens:** `public/images/mockups/mockup-mobile.png`
- **Mockup Paket Langganan:** `public/images/mockups/mockup-pricing.png`

---

## 🚀 Fitur Utama MVP (Prioritas P0)

1. **Authentication & Multi-Tenant Onboarding:** Registrasi, verifikasi email, login, dan setup workspace bisnis.
2. **Dashboard Finansial:** Kartu ringkasan total tagihan, sudah dibayar, piutang, dan invoice overdue, grafik pendapatan bulanan, dan komposisi status invoice.
3. **Manajemen Klien & Proyek:** Hubungkan invoice ke klien dan proyek dengan riwayat transaksi yang rapi.
4. **Invoice & Termin Pembayaran:** Nomor invoice otomatis, pembuatan termin (DP, Termin 1, Pelunasan), kalkulasi saldo akurat (`DECIMAL`).
5. **Pencatatan Pembayaran:** Catat pembayaran penuh / sebagian secara atomik (`DB::transaction`) dan unduh receipt.
6. **Halaman Publik Invoice Klien (`/i/{token}`):** Klien dapat melihat status, rincian termin, instruksi pembayaran, dan unduh PDF tanpa login.

---

## 🛠️ Panduan Pengembangan Lokal

```bash
# 1. Install dependencies
composer install
npm install

# 2. Salin environment dan generate app key
cp .env.example .env
php artisan key:generate

# 3. Jalankan migrasi database
php artisan migrate

# 4. Jalankan development server
npm run dev
# di terminal lain:
php artisan serve
```

---

## 🌐 Deployment & CI/CD

- **Server VPS:** `root@201.18.218.42`
- **Target Folder:** `/var/www/invoicerapi.synbarra.com`
- **GitHub Repository:** `https://github.com/hasanarofid/InvoiceRapi.git`
- **CI/CD Workflow:** Otomatis deploy saat push ke branch `main` via `.github/workflows/deploy.yml`.
