export interface PortfolioProject {
  id: string;
  slug: string;
  category: 'internal' | 'public';
  categoryLabel: string;
  tag: string;
  title: string;
  subtitle?: string;
  description: string;
  highlight: string;
  images: string[];
  techStack: string[];
  link?: string | null;
  featured?: boolean;
}

export const PORTFOLIO_PROJECTS: PortfolioProject[] = [
  {
    id: 'mr-lux',
    slug: 'mr-lux',
    category: 'internal',
    categoryLabel: 'Sistem Internal & ERP',
    tag: 'Enterprise ERP',
    title: 'Mr. Lux Indonesia ERP',
    subtitle: 'Supply Chain & Wholesale Distribution System',
    description: 'Perangkat lunak enterprise resource planning (ERP) komprehensif yang dilengkapi penerbitan surat jalan digital, pelacakan inventaris gudang real-time, pemrosesan penjualan B2B, dan logika bisnis multi-cabang untuk distribusi grosir berskala besar.',
    highlight: 'Akurasi stok & pelacakan surat jalan real-time tanpa selisih fisik.',
    images: [
      '/images/portfolio/mrluxindonesia.png',
      '/images/portfolio/mrluxindonesia1.png',
      '/images/portfolio/mrluxindonesia2.png',
      '/images/portfolio/mrluxindonesia3.png',
    ],
    techStack: ['Laravel', 'PostgreSQL', 'Inventory Tracking', 'Surat Jalan Otomatis', 'Multi-tenant'],
    link: null,
    featured: true,
  },
  {
    id: 'school-system',
    slug: 'school-system',
    category: 'internal',
    categoryLabel: 'Sistem Internal & Operasional',
    tag: 'Education Tech',
    title: 'School Management & Monitoring System',
    subtitle: 'Integrated Operational & Academic Portal',
    description: 'Sistem pemantauan dan manajemen operasional terpadu untuk institusi pendidikan. Menyederhanakan pencatatan presensi, administrasi nilai, alur audit berkas, dan pelaporan berkala dengan tingkat keamanan data yang tinggi.',
    highlight: 'Mengurangi beban kerja administrasi manual sekolah hingga 70%.',
    images: [
      '/images/portfolio/point-sekolah.png',
      '/images/portfolio/point-sekolah2.png',
    ],
    techStack: ['Laravel', 'Vue.js', 'MySQL', 'Audit Trail', 'Role & Permission'],
    link: null,
    featured: true,
  },
  {
    id: 'nitajaya',
    slug: 'nitajaya',
    category: 'internal',
    categoryLabel: 'Sistem Internal & Operasional',
    tag: 'POS & F&B Operations',
    title: 'Nitajaya Catering & POS System',
    subtitle: 'Catering Management & Point-of-Sale Engine',
    description: 'Sistem manajemen katering dan point-of-sale (POS) terintegrasi. Mempermudah penjadwalan katering acara, pemesanan kustom, kalkulasi bahan baku otomatis, serta pelacakan transaksi harian secara akurat.',
    highlight: 'Sinkronisasi pesanan katering dan kalkulasi bahan baku secara tepat.',
    images: [
      '/images/portfolio/nitajaya.png',
    ],
    techStack: ['PHP', 'MySQL', 'POS Engine', 'Order Processing', 'Inventory Calc'],
    link: null,
    featured: false,
  },
  {
    id: 'amtech-ev',
    slug: 'amtech-ev',
    category: 'public',
    categoryLabel: 'Sistem Publik & SaaS',
    tag: 'Enterprise SaaS & IoT',
    title: 'Amtech EV Infrastructure',
    subtitle: 'Electric Vehicle Charging Network Management',
    description: 'Platform manajemen infrastruktur stasiun pengisian daya kendaraan listrik (EV) terkemuka di Malaysia. Mengintegrasikan telemetri hardware secara real-time, monitoring status charger, penagihan multi-tenant, dan dashboard analitik terpusat.',
    highlight: 'Telemetri hardware real-time & arsitektur multi-tenant scalable.',
    images: [
      '/images/portfolio/amtechev.png',
    ],
    techStack: ['IoT Telemetry', 'Cloud Infrastructure', 'Billing Gateway', 'Hardware API', 'Real-time WebSocket'],
    link: 'https://amtechev.com/',
    featured: true,
  },
  {
    id: 'mitrasyiar-baitullah',
    slug: 'mitrasyiar-baitullah',
    category: 'public',
    categoryLabel: 'Sistem Publik & Platform Web',
    tag: 'Travel & Hajj Tech',
    title: 'Mitra Syiar Baitullah',
    subtitle: 'Digital Umrah & Hajj Travel Platform',
    description: 'Platform digital pelayanan Umroh & Haji terpercaya dengan katalog paket tur dinamis, informasi jadwal keberangkatan real-time, sistem pemesanan online, serta pendaftaran kemitraan keagenan yang terintegrasi rapi.',
    highlight: 'Pemesanan paket tur terintegrasi dengan jaringan keagenan nasional.',
    images: [
      '/images/portfolio/mitrasyiarbaitullah.png',
    ],
    techStack: ['Web Platform', 'Travel Booking', 'Payment Gateway', 'Affiliate Management', 'SEO Optimized'],
    link: 'https://www.mitrasyiarbaitullah.com/',
    featured: true,
  },
  {
    id: 'gringgo',
    slug: 'gringgo',
    category: 'public',
    categoryLabel: 'Sistem Publik & Platform Web',
    tag: 'CleanTech Solution',
    title: 'Gringgo Environment Solutions',
    subtitle: 'Waste Management & Tech for Good Platform',
    description: 'Platform teknologi yang berfokus pada digitalisasi pengelolaan sampah dan pelestarian lingkungan. Memberdayakan komunitas pengumpul sampah dan industri daur ulang melalui data pelacakan yang akurat dan transparan.',
    highlight: 'Digitalisasi rantai pasok daur ulang sampah berbasis data komunitas.',
    images: [
      '/images/portfolio/gringgo.png',
    ],
    techStack: ['CleanTech', 'Community Tracking', 'Data Analytics', 'Modern Web', 'Impact Metrics'],
    link: 'https://gringgo.org/',
    featured: false,
  },
  {
    id: 'afpro-aquarium',
    slug: 'afpro-aquarium',
    category: 'public',
    categoryLabel: 'Sistem Publik & Platform Web',
    tag: 'E-Commerce & Catalog',
    title: 'Afpro Aquarium',
    subtitle: 'Interactive Aquascape Showcase & Storefront',
    description: 'Aplikasi web publik modern untuk showcase produk dan perlengkapan aquascape. Menampilkan katalog visual yang intuitif, informasi spesifikasi detail, serta kemudahan komunikasi pesanan langsung bagi para pecinta aquascape.',
    highlight: 'Antarmuka visual modern yang meningkatkan engagement katalog produk.',
    images: [
      '/images/portfolio/afpro1.png',
      '/images/portfolio/afpro2.png',
    ],
    techStack: ['Interactive Web App', 'Product Catalog', 'Responsive UI', 'WhatsApp Checkout'],
    link: 'https://afproaquarium.com/',
    featured: false,
  },
  {
    id: 'nolimits-training',
    slug: 'nolimits-training',
    category: 'public',
    categoryLabel: 'Sistem Publik & Platform Web',
    tag: 'Public Platform',
    title: 'No Limits Training',
    subtitle: 'High-Converting Professional Training Portal',
    description: 'Platform publik yang dinamis dan interaktif untuk program pelatihan profesional dan sertifikasi. Dirancang dengan pengalaman pengguna yang mulus, alur registrasi peserta otomatis, dan struktur konversi yang dioptimalkan.',
    highlight: 'Funnel pendaftaran kelas terotomasi dengan konversi tinggi.',
    images: [
      '/images/portfolio/nolimitstraining.png',
    ],
    techStack: ['High-Performance Landing', 'Lead Funnel', 'Event Registration', 'Mobile Responsive'],
    link: 'https://nolimitstraining.id/',
    featured: false,
  },
];
