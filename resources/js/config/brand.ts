/**
 * SYNBARRA TECHNOLOGIES — Brand Configuration & Design Tokens
 * 
 * Sesuai PRD Version 1.0 (synbarra.com)
 * Warna diekstrak langsung dari logo resmi (single source of truth).
 */

export const BRAND = {
  name: 'SYNBARRA TECHNOLOGIES',
  shortName: 'Synbarra',
  legalName: 'Synbarra Technologies',
  domain: 'synbarra.com',
  url: 'https://synbarra.com',
  tagline: 'Engineering the Digital Future.',
  businessType: 'Custom Software Development / Software House',
  
  description: 'SYNBARRA TECHNOLOGIES adalah penyedia solusi teknologi digital yang membantu bisnis dan organisasi mengembangkan sistem, website, serta aplikasi sesuai kebutuhan. Kami berfokus pada pengembangan teknologi yang praktis, efisien, dan dapat mendukung pertumbuhan bisnis.',

  coreServicesText: 'Website • Mobile Apps • Custom Software • Automation • System Integration • AI Solutions',
  coreServices: [
    'Website',
    'Mobile Apps',
    'Custom Software',
    'Automation',
    'System Integration',
    'AI Solutions'
  ],

  // Design Tokens (Derived from approved logo)
  colors: {
    primary: '#01232B',       // Dark Teal Signature Brand
    primaryDeep: '#011E26',   // Deep Midnight Teal
    secondary: '#00F1DE',     // Neon Turquoise Accent
    accent: '#04D2C5',        // Medium Cyan / Turquoise
    surface: '#F8FAFA',       // Light Neutral Surface
    surfaceDark: '#022B31',   // Dark Surface / Card
    background: '#FFFFFF',    // White
    border: '#E2E8F0',        // Border Neutral
    borderDark: '#0A3B43',    // Dark Border
    textPrimary: '#0F172A',   // Dark Charcoal
    textSecondary: '#475569', // Muted Slate
    textLight: '#F1F5F9',     // Light Text on Dark
    textMutedDark: '#8DA5A8', // Secondary Text on Dark
  },

  // Contact Details
  contact: {
    email: 'synbarr.techno@gmail.com',
    whatsapp: '6288804958476',
    whatsappRaw: '088804958476',
    whatsappDisplay: '0888-0495-8476',
    whatsappUrl: 'https://wa.me/6288804958476?text=Halo%20Synbarra%20Technologies,%20saya%20ingin%20konsultasi%20pengembangan%20sistem%20/%20aplikasi.',
    location: 'Indonesia',
  },

  // Navigation Links
  navigation: [
    { label: 'Home', href: '/' },
    { label: 'About Us', href: '/about' },
    { label: 'Services', href: '/services' },
    { label: 'Our Approach', href: '/approach' },
    { label: 'Contact', href: '/contact' },
  ],

  // Services as specified in Section 4.1-C
  services: [
    {
      id: 'custom-software',
      title: 'Custom Software Development',
      description: 'Development of tailored software applications based on specific business requirements.',
      icon: 'Code2',
      href: '/services#custom-software',
    },
    {
      id: 'web-application',
      title: 'Web Application Development',
      description: 'Responsive and scalable web-based applications for business operations and customer experiences.',
      icon: 'Globe',
      href: '/services#web-application',
    },
    {
      id: 'mobile-application',
      title: 'Mobile Application Development',
      description: 'Mobile solutions designed to support modern business processes and user needs.',
      icon: 'Smartphone',
      href: '/services#mobile-application',
    },
    {
      id: 'process-automation',
      title: 'Business Process Automation',
      description: 'Digitalization and automation of repetitive workflows to improve operational efficiency.',
      icon: 'Cpu',
      href: '/services#process-automation',
    },
    {
      id: 'system-integration',
      title: 'System Integration',
      description: 'Connecting existing business systems, applications, and data into a more efficient ecosystem.',
      icon: 'Network',
      href: '/services#system-integration',
    },
    {
      id: 'ui-ux-design',
      title: 'UI/UX Design',
      description: 'Designing intuitive digital interfaces focused on usability and user experience.',
      icon: 'Palette',
      href: '/services#ui-ux-design',
    },
  ],

  // 5-Step Process (Section 4.1-E)
  process: [
    { step: '01', title: 'Discover', description: 'Understand business needs and operational challenges thoroughly.' },
    { step: '02', title: 'Define', description: 'Establish clear requirements, tech architecture, and project scope.' },
    { step: '03', title: 'Design', description: 'Create intuitive UI/UX journeys and scalable system architecture.' },
    { step: '04', title: 'Develop', description: 'Build with clean, maintainable code backed by rigorous testing.' },
    { step: '05', title: 'Deliver', description: 'Deploy seamlessly, provide support, and optimize continuously.' },
  ],
} as const;
