/**
 * SYNBARRA INVOICERAPI — Brand Configuration & Single Source of Truth
 * 
 * Mengacu langsung pada PRD Synbarra InvoiceRapi & Brand Board Mockup Resmi
 */

export const BRAND = {
  name: 'Synbarra InvoiceRapi',
  shortName: 'InvoiceRapi',
  legalName: 'Synbarra Technologies',
  domain: 'invoicerapi.synbarra.com',
  url: 'https://invoicerapi.synbarra.com',
  tagline: 'Urus Tagihan, Jadi Lebih Rapi.',
  subtagline: 'Kelola Invoice, DP, Termin, dan Pelunasan Klien dalam Satu Aplikasi.',
  
  description: 'Solusi praktis untuk bisnis jasa seperti Wedding Organizer, Fotografer, Event Organizer, Kontraktor, dan lainnya. Semua tagihan, pembayaran, dan status proyek jadi lebih rapi, terpantau, dan tepat waktu.',

  // Navigasi Publik Sesuai Mockup
  navigation: [
    { name: 'Beranda', href: '#beranda' },
    { name: 'Fitur', href: '#fitur' },
    { name: 'Solusi', href: '#solusi', hasDropdown: true },
    { name: 'Harga', href: '#harga' },
    { name: 'Demo', href: '#demo' },
    { name: 'Testimoni', href: '#testimoni' },
    { name: 'FAQ', href: '#faq' },
  ],

  // Testimoni Bisnis Jasa
  testimonials: [
    {
      name: 'Nadia Kusuma',
      role: 'Founder, Mulia Wedding Organizer',
      quote: 'Dulu sering lupa jadwal tagih termin ke pengantin karena semua masih di spreadsheet. Sejak pakai InvoiceRapi, DP sampai pelunasan hari H terpantau rapi.',
      metric: '40+ Acara / Tahun',
    },
    {
      name: 'Rizky Pratama',
      role: 'Lead Photographer, Lensa Studio',
      quote: 'Klien korporat dan wedding jadi lebih percaya karena link invoice-nya profesional, ada rincian termin booking fee, dan bisa langsung download PDF.',
      metric: 'Piutang Turun 65%',
    },
    {
      name: 'Hendra Wijaya',
      role: 'Direktur, Ruang Karya Interior',
      quote: 'Proyek interior punya 4 tahap termin. Di InvoiceRapi kami bisa pecah jadwal bayar per progres fisik dan pantau mana yang sudah jatuh tempo.',
      metric: '100% Terpantau',
    },
  ],

  // 4 Pilar Fitur Utama (Feature Pills di Hero Mockup)
  coreFeatures: [
    {
      title: 'Buat Invoice Profesional',
      description: 'Format rapi otomatis dengan nomor invoice unik, rincian item, dan logo bisnis Anda.',
      icon: 'FileText',
    },
    {
      title: 'Atur DP & Termin Pembayaran',
      description: 'Pecah invoice jadi DP, Termin 1, 2, hingga Pelunasan dengan tanggal jatuh tempo jelas.',
      icon: 'Layers',
    },
    {
      title: 'Pengingat Jatuh Tempo',
      description: 'Kirim reminder otomatis ke klien via WhatsApp atau Email tanpa canggung menagih.',
      icon: 'Bell',
    },
    {
      title: 'Pantau Piutang dan Pelunasan',
      description: 'Lacak saldo outstanding, invoice overdue, dan arus kas masuk secara real-time.',
      icon: 'BarChart3',
    },
  ],

  // Persona Target Bisnis Jasa (Sesuai PRD)
  personas: [
    {
      title: 'Wedding Organizer (WO)',
      tag: 'Spesialis WO',
      description: 'Kelola uang muka (DP) sewa gedung, katering, MUA, hingga pelunasan saat hari H tanpa spreadsheet manual yang berceceran.',
      badge: 'Solusi Favorit',
    },
    {
      title: 'Fotografer & Videografer',
      tag: 'Creative Agency',
      description: 'Atur jadwal termin pemotretan: Booking fee 30%, Hari pemotretan 40%, dan Pelunasan 30% saat album selesai dicetak.',
      badge: 'Booking & Termin',
    },
    {
      title: 'Event Organizer (EO)',
      tag: 'Corporate & Public Event',
      description: 'Penagihan bertahap ke klien korporat dengan nomor invoice resmi, bukti penerimaan pembayaran, dan laporan piutang.',
      badge: 'B2B Invoicing',
    },
    {
      title: 'Kontraktor & Desain Interior',
      tag: 'Proyek Bertahap',
      description: 'Pantau progres pembayaran sesuai persentase fisik proyek konstruksi dan interior dari DP hingga serah terima kunci.',
      badge: 'Termin Proyek',
    },
  ],

  // Paket Langganan Resmi Sesuai PRD & Mockup
  pricing: [
    {
      id: 'gratis',
      name: 'Gratis',
      price: 'Rp 0',
      period: '/bulan',
      popular: false,
      ctaText: 'Mulai Gratis',
      ctaHref: '/register',
      features: [
        '5 invoice / bulan',
        '10 klien terdaftar',
        'Unduh invoice PDF',
        'Dashboard ringkasan dasar',
        '1 akun pengguna',
      ],
    },
    {
      id: 'pro',
      name: 'Pro',
      price: 'Rp 55.000',
      originalPrice: 'Rp 99.000',
      period: '/bulan',
      popular: true,
      badge: 'Promo Terpopuler',
      ctaText: 'Pilih Paket Pro',
      ctaHref: '/register?plan=pro',
      features: [
        '100 invoice / bulan',
        'Unlimited klien & proyek',
        'Atur jadwal DP & multi-termin',
        'Pengingat jatuh tempo otomatis',
        'Laporan piutang lengkap',
        '2 akun pengguna',
        'Tautan invoice publik kustom',
      ],
    },
    {
      id: 'bisnis',
      name: 'Bisnis',
      price: 'Rp 119.000',
      originalPrice: 'Rp 199.000',
      period: '/bulan',
      popular: false,
      ctaText: 'Pilih Paket Bisnis',
      ctaHref: '/register?plan=bisnis',
      features: [
        '500 invoice / bulan',
        '5 akun pengguna (Owner, Admin, Staff)',
        'Laporan analitik lanjutan & ekspor CSV',
        'Pengingat terjadwal via email & WA',
        'Dukungan prioritas dari tim Synbarra',
        'Audit log transaksi finansial',
      ],
    },
  ],

  // Kontak Resmi Synbarra Technologies
  contact: {
    whatsapp: '6288804958476',
    email: 'synbarr.techno@gmail.com',
    website: 'https://synbarra.com',
    instagram: '@synbarra.technologies',
  },
};
