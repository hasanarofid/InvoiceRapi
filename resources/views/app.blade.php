<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="bg-white">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="theme-color" content="#6D28D9">

        <!-- Google AdSense -->
        <meta name="google-adsense-account" content="ca-pub-7190047001129861">
        <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-7190047001129861" crossorigin="anonymous"></script>

        <!-- Google Search Console Verification -->
        <meta name="google-site-verification" content="google587f0f47640fdb34">

        @php
            $defaultSiteName = 'Synbarra InvoiceRapi';
            $defaultTitle = 'Synbarra InvoiceRapi | Urus Tagihan, Jadi Lebih Rapi';
            $defaultDescription = 'Aplikasi SaaS manajemen invoice, DP, termin, dan pelunasan klien dalam satu aplikasi yang mudah untuk bisnis jasa di Indonesia.';
            $defaultKeywords = 'InvoiceRapi, Synbarra InvoiceRapi, Aplikasi Invoice Indonesia, Invoice Wedding Organizer, Invoice Fotografer, Termin Pembayaran, Manajemen Piutang, DP dan Pelunasan';

            $pageProps = $page['props'] ?? [];
            $meta = $meta ?? ($pageProps['meta'] ?? []);

            $seoTitle = (!empty($meta['title'])) ? $meta['title'] : $defaultTitle;
            $seoDesc = (!empty($meta['description'])) ? $meta['description'] : $defaultDescription;
            $seoKeywords = $meta['keywords'] ?? $defaultKeywords;

            $rawCanonical = !empty($meta['canonical']) ? $meta['canonical'] : request()->url();
            $cleanCanonical = preg_replace('#^https?://(www\.)?invoicerapi\.synbarra\.com#i', 'https://invoicerapi.synbarra.com', $rawCanonical);
            $parsedUrl = parse_url($cleanCanonical);
            $path = $parsedUrl['path'] ?? '/';
            $canonicalUrl = 'https://invoicerapi.synbarra.com' . (rtrim($path, '/') ?: '/');

            $ogTitle = !empty($meta['og_title']) ? $meta['og_title'] : $seoTitle;
            $ogDesc = !empty($meta['og_description']) ? $meta['og_description'] : $seoDesc;
            
            $rawImage = !empty($meta['og_image']) ? $meta['og_image'] : (!empty($meta['image']) ? $meta['image'] : 'images/mockups/design-system-board.png');
            $seoImage = url($rawImage);
            $seoType = $meta['type'] ?? 'website';
            $seoTwitter = $meta['twitter_card'] ?? 'summary_large_image';

            // Robots directive resolution
            $isPrivatePath = request()->is('admin*') || request()->is('dashboard*') || request()->is('profile*');
            $robotsContent = !empty($meta['robots']) 
                ? $meta['robots'] 
                : ($isPrivatePath ? 'noindex, nofollow' : 'index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1');
        @endphp

        <!-- Title for Crawlers & Inertia -->
        <title inertia>{{ $seoTitle }}</title>

        <!-- Canonical URL -->
        <link rel="canonical" href="{{ $canonicalUrl }}">

        <!-- Robots Meta Tag -->
        <meta name="robots" content="{{ $robotsContent }}">

        <!-- GEO & Local Targeting for Indonesia -->
        <meta name="geo.region" content="ID">
        <meta name="geo.placename" content="Indonesia">
        <meta name="language" content="id-ID">
        <meta http-equiv="content-language" content="id-ID">

        <!-- Primary Meta Tags -->
        <meta name="title" content="{{ $seoTitle }}">
        <meta name="description" content="{{ $seoDesc }}">
        <meta name="keywords" content="{{ $seoKeywords }}">
        <meta name="author" content="Synbarra Technologies">

        <!-- Open Graph / Facebook / WhatsApp -->
        <meta property="og:site_name" content="{{ $defaultSiteName }}">
        <meta property="og:type" content="{{ $seoType }}">
        <meta property="og:url" content="{{ $canonicalUrl }}">
        <meta property="og:title" content="{{ $ogTitle }}">
        <meta property="og:description" content="{{ $ogDesc }}">
        <meta property="og:image" content="{{ $seoImage }}">
        <meta property="og:image:secure_url" content="{{ $seoImage }}">
        <meta property="og:image:type" content="image/jpeg">
        <meta property="og:image:width" content="1200">
        <meta property="og:image:height" content="630">

        <!-- Twitter Card -->
        <meta name="twitter:card" content="{{ $seoTwitter }}">
        <meta name="twitter:url" content="{{ $canonicalUrl }}">
        <meta name="twitter:title" content="{{ $ogTitle }}">
        <meta name="twitter:description" content="{{ $ogDesc }}">
        <meta name="twitter:image" content="{{ $seoImage }}">

        @php
            $jsonLd = [
                '@context' => 'https://schema.org',
                '@type' => 'SoftwareApplication',
                'name' => 'Synbarra InvoiceRapi',
                'applicationCategory' => 'BusinessApplication',
                'operatingSystem' => 'Web Browser',
                'url' => 'https://invoicerapi.synbarra.com',
                'image' => $seoImage,
                'description' => $defaultDescription,
                'creator' => [
                    '@type' => 'Organization',
                    'name' => 'Synbarra Technologies',
                    'url' => 'https://synbarra.com',
                ],
            ];
        @endphp

        <!-- Schema.org JSON-LD (Search Console Structured Data) -->
        <script type="application/ld+json">
        {!! json_encode($jsonLd, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
        </script>

        <!-- Favicons -->
        <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png?v=3">
        <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png?v=3">
        <link rel="icon" type="image/x-icon" href="/favicon.ico?v=3">
        <link rel="icon" type="image/png" sizes="192x192" href="/icons/icon-192.png?v=3">
        <link rel="icon" type="image/png" sizes="512x512" href="/icons/icon-512.png?v=3">
        <link rel="apple-touch-icon" href="/apple-touch-icon.png?v=3">

        <!-- Google Fonts: Inter & Plus Jakarta Sans -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @routes
        @vite(['resources/js/app.js', "resources/js/Pages/{$page['component']}.vue"])
        @inertiaHead
    </head>
    <body class="font-sans antialiased text-slate-900 bg-white selection:bg-[#00F1DE]/30 selection:text-[#01232B]">
        @inertia

        <!-- SEO / Crawler Fallback for Search Engines & Social Scrapers -->
        <noscript>
            <div style="padding: 24px; font-family: system-ui, -apple-system, sans-serif; color: #0D2329; background: #fff;">
                <h1>{{ $seoTitle }}</h1>
                <p>{{ $seoDesc }}</p>
                <h2>Navigasi Synbarra Technologies</h2>
                <ul>
                    <li><a href="/">Beranda</a></li>
                    <li><a href="/portofolio">Portofolio Solusi Digital</a></li>
                    <li><a href="/services">Layanan Pengembangan Sistem</a></li>
                    <li><a href="/approach">Metodologi &amp; Alur Kerja</a></li>
                    <li><a href="/about">Tentang Kami</a></li>
                    <li><a href="/contact">Kontak &amp; Konsultasi</a></li>
                </ul>
            </div>
        </noscript>
    </body>
</html>
