/**
 * SYNBARRA TECHNOLOGIES — Brand Configuration & Single Source of Truth
 * 
 * Selaras dengan Panduan Visual Resmi (9 Postingan Synbarra Technologies)
 */

export const BRAND = {
  name: 'SYNBARRA TECHNOLOGIES',
  shortName: 'Synbarra',
  legalName: 'Synbarra Technologies',
  domain: 'synbarra.com',
  url: 'https://synbarra.com',
  tagline: 'Software House & Custom Development',
  subtagline: 'Mitra teknologi yang menghadirkan solusi digital dan pengembangan sistem sesuai kebutuhan unik bisnis Anda.',
  
  description: 'SYNBARRA TECHNOLOGIES adalah software house yang berfokus pada pengembangan sistem, website, dan aplikasi custom sesuai kebutuhan unik bisnis Anda. Kami membantu bisnis berkembang melalui teknologi yang praktis, terstruktur, andal, dan scalable.',

  coreServicesText: 'Website • Mobile Apps • Sistem ERP / CRM • HRIS & Payroll • Integrasi API • Custom Software',

  // 8 Solusi Digital Utama (Sesuai Postingan 06 & 03)
  services: [
    {
      id: 'website',
      title: 'Website Company Profile',
      category: 'Web Presence',
      description: 'Menampilkan kredibilitas dan profil bisnis profesional secara elegan, responsif, dan siap konversi.',
      icon: 'Globe',
      href: '/services#website',
    },
    {
      id: 'mobile-app',
      title: 'Aplikasi Mobile',
      category: 'Mobile Platform',
      description: 'Aplikasi iOS & Android performa tinggi berbasis Flutter atau native untuk kenyamanan pengguna.',
      icon: 'Smartphone',
      href: '/services#mobile-app',
    },
    {
      id: 'erp-bisnis',
      title: 'Sistem Bisnis / ERP',
      category: 'Enterprise Solution',
      description: 'Sentralisasi operasional, manajemen stok/inventaris, rantai pasok, dan pelaporan keuangan terpadu.',
      icon: 'Layers',
      href: '/services#erp-bisnis',
    },
    {
      id: 'crm',
      title: 'Sistem CRM',
      category: 'Customer Management',
      description: 'Pengelolaan prospek, otomatisasi sales pipeline, dan retensi interaksi pelanggan yang efektif.',
      icon: 'Users',
      href: '/services#crm',
    },
    {
      id: 'hris-payroll',
      title: 'Sistem HRIS & Payroll',
      category: 'Internal Operation',
      description: 'Absensi GPS, pengajuan cuti, perhitungan gaji otomatis, BPJS, dan master data karyawan terstruktur.',
      icon: 'CalendarCheck',
      href: '/services#hris-payroll',
    },
    {
      id: 'api-integration',
      title: 'Integrasi Sistem (API)',
      category: 'Integration',
      description: 'Menghubungkan ragam software, payment gateway, WhatsApp API, logistik, dan sistem legacy tanpa friksi.',
      icon: 'Network',
      href: '/services#api-integration',
    },
    {
      id: 'ui-ux',
      title: 'UI/UX Design',
      category: 'Product Design',
      description: 'Riset pengguna, pembuatan wireframe interaktif, dan desain interface modern yang memikat pengguna.',
      icon: 'Palette',
      href: '/services#ui-ux',
    },
    {
      id: 'maintenance-support',
      title: 'Maintenance & Support',
      category: 'Care & Scalability',
      description: 'Pemeliharaan rutin, monitoring performa server, update keamanan, dan technical assistance berkelanjutan.',
      icon: 'Headset',
      href: '/services#maintenance-support',
    },
  ],

  // 4 Pilar Mengapa Memilih Synbarra (Sesuai Postingan 02)
  advantages: [
    {
      title: 'Solusi Sesuai Kebutuhan (Custom)',
      description: 'Sistem dirancang dan dibangun menyesuaikan alur kerja, aturan operasional, serta kebutuhan unik bisnis Anda tanpa batasan template kaku.',
      icon: 'Sparkles',
    },
    {
      title: 'Tim Berpengalaman dan Profesional',
      description: 'Didukung oleh tim engineer, arsitek sistem, dan UI/UX designer berpengalaman dengan standar industri modern dan best practices.',
      icon: 'Users',
    },
    {
      title: 'Proses Terstruktur dan Transparan',
      description: 'Pengerjaan transparan dengan roadmap sprint yang jelas, pelaporan progress berkala, dan komunikasi intensif di setiap tahapan.',
      icon: 'Layers',
    },
    {
      title: 'Dukungan & Pemeliharaan Jangka Panjang',
      description: 'Komitmen pendampingan pasca-peluncuran, garansi bebas bug, monitoring performa server, dan peningkatan fitur berkala.',
      icon: 'Headset',
    },
  ],

  // Masalah Bisnis yang Sering Dihadapi Klien (Sesuai Postingan 04)
  painPoints: [
    {
      title: 'Proses kerja masih manual dan memakan waktu',
      description: 'Input data berulang secara manual meningkatkan risiko human error dan menghambat efisiensi operasional tim.',
    },
    {
      title: 'Data tersebar di banyak tempat dan sulit dipantau',
      description: 'Laporan tersimpan di berbagai spreadsheet terpisah membuat manajemen kesulitan mengambil keputusan cepat berbasis data akurat.',
    },
    {
      title: 'Sistem yang ada tidak sesuai dengan alur bisnis',
      description: 'Aplikasi siap pakai (off-the-shelf) sering kali kaku, memaksa alur kerja bisnis beradaptasi dengan sistem bukan sebaliknya.',
    },
    {
      title: 'Sulit mengintegrasikan berbagai tools',
      description: 'Sistem kasir, inventaris, WhatsApp, dan akuntansi berjalan terisolasi tanpa sinkronisasi otomatis.',
    },
  ],

  // Alur Kerja 4 Langkah (Sesuai Postingan 07)
  workflow: [
    {
      step: '01',
      title: 'Analisis',
      description: 'Memahami kebutuhan, tantangan, dan tujuan bisnis Anda secara menyeluruh untuk merumuskan spesifikasi sistem.',
      icon: 'Search',
    },
    {
      step: '02',
      title: 'Perancangan',
      description: 'Menyusun solusi arsitektur, skema database, user journey, dan desain antarmuka (UI/UX prototype).',
      icon: 'PenTool',
    },
    {
      step: '03',
      title: 'Pengembangan',
      description: 'Membangun sistem dengan clean code, strict typing, integrasi database, dan pengujian fitur ketat per modul.',
      icon: 'Code2',
    },
    {
      step: '04',
      title: 'Implementasi & Support',
      description: 'Deployment ke cloud server, User Acceptance Testing (UAT), pelatihan staf, dan pemeliharaan jangka panjang.',
      icon: 'Rocket',
    },
  ],

  // Keahlian Teknologi (Sesuai Postingan 08)
  techStack: [
    { name: 'Laravel', role: 'Enterprise PHP Framework', color: '#FF2D20', logo: '/images/tech/laravel.svg' },
    { name: 'React', role: 'Interactive Frontend', color: '#61DAFB', logo: '/images/tech/react.svg' },
    { name: 'Next.js', role: 'SSR & High Performance Web', color: '#000000', logo: '/images/tech/nextjs.svg' },
    { name: 'Flutter', role: 'Cross-Platform Mobile Apps', color: '#02569B', logo: '/images/tech/flutter.svg' },
    { name: 'Node.js', role: 'Scalable Microservices Backend', color: '#339933', logo: '/images/tech/nodejs.svg' },
    { name: 'PHP', role: 'Reliable Server Language', color: '#777BB4', logo: '/images/tech/php.svg' },
    { name: 'MySQL', role: 'High-Integrity Relational DB', color: '#4479A1', logo: '/images/tech/mysql.svg' },
    { name: 'AWS', role: 'Cloud Infrastructure & Scalability', color: '#FF9900', logo: '/images/tech/aws.svg' },
  ],

  // Kontak & Media Sosial (Sesuai Postingan 09)
  contact: {
    email: 'synbarr.techno@gmail.com',
    whatsapp: '6288804958476',
    whatsappRaw: '088804958476',
    whatsappDisplay: '0888-0495-8476',
    whatsappUrl: 'https://wa.me/6288804958476?text=Halo%20Synbarra%20Technologies,%20saya%20ingin%20konsultasi%20pengembangan%20sistem%20/%20aplikasi.',
    instagram: '@synbarra.technologies',
    instagramUrl: 'https://instagram.com/synbarra.technologies',
    location: 'Indonesia',
  },

  // Navigasi Utama
  navigation: [
    { label: 'Home', href: '/' },
    { label: 'Produk', href: '/produk' },
    { label: 'Layanan', href: '/services' },
    { label: 'Portofolio', href: '/portofolio' },
    { label: 'Alur Kerja', href: '/approach' },
    { label: 'Tentang Kami', href: '/about' },
    { label: 'Kontak', href: '/contact' },
  ],
};
