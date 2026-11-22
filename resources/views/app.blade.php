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

        @php
            $gscVerification = \App\Models\Setting::getValue('gsc_verification', 'google587f0f47640fdb34');
            $gaMeasurementId = \App\Models\Setting::getValue('ga_measurement_id', env('GA4_MEASUREMENT_ID', ''));
            $metaPixelId = \App\Models\Setting::getValue('meta_pixel_id', '');
        @endphp
        @if(!empty($gscVerification))
        <!-- Google Search Console Verification -->
        <meta name="google-site-verification" content="{{ $gscVerification }}">
        @endif

        @if(!empty($gaMeasurementId) && !request()->is('admin*'))
            @if(str_starts_with(strtoupper(trim($gaMeasurementId)), 'GTM-'))
            <!-- Google Tag Manager -->
            <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
            new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
            j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
            'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
            })(window,document,'script','dataLayer','{{ trim($gaMeasurementId) }}');</script>
            @else
            <!-- Google Analytics 4 (Privacy-Safe, No PII) -->
            <script async src="https://www.googletagmanager.com/gtag/js?id={{ trim($gaMeasurementId) }}"></script>
            <script>
                window.dataLayer = window.dataLayer || [];
                function gtag(){dataLayer.push(arguments);}
                gtag('js', new Date());
                gtag('config', '{{ trim($gaMeasurementId) }}', { anonymize_ip: true });
            </script>
            @endif
        @endif

        @if(!empty($metaPixelId) && !request()->is('admin*'))
        <!-- Meta Pixel Code -->
        <script>
            !function(f,b,e,v,n,t,s)
            {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
            n.callMethod.apply(n,arguments):n.queue.push(arguments)};
            if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
            n.queue=[];t=b.createElement(e);t.async=!0;
            t.src=v;s=b.getElementsByTagName(e)[0];
            s.parentNode.insertBefore(t,s)}(window, document,'script',
            'https://connect.facebook.net/en_US/fbevents.js');
            fbq('init', '{{ $metaPixelId }}');
            fbq('track', 'PageView');
        </script>
        @endif

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

            // Robots directive resolution (strictly noindex all private, auth, and client-token paths)
            $isPrivatePath = request()->is(
                'admin*', 'dashboard*', 'profile*', 'invoices*', 'clients*',
                'projects*', 'billing*', 'onboarding*', 'login', 'register',
                'forgot-password', 'reset-password*', 'verify-email*', 'i/*'
            );
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
                'offers' => [
                    '@type' => 'AggregateOffer',
                    'priceCurrency' => 'IDR',
                    'lowPrice' => '0',
                    'highPrice' => '119000',
                    'offerCount' => '3',
                ],
                'creator' => [
                    '@type' => 'Organization',
                    'name' => 'Synbarra Technologies',
                    'url' => 'https://synbarra.com',
                ],
            ];

            $orgJsonLd = [
                '@context' => 'https://schema.org',
                '@type' => 'Organization',
                'name' => 'Synbarra',
                'url' => 'https://synbarra.com/',
                'sameAs' => ['https://invoicerapi.synbarra.com/'],
            ];

            $websiteJsonLd = [
                '@context' => 'https://schema.org',
                '@type' => 'WebSite',
                'name' => 'InvoiceRapi',
                'url' => 'https://invoicerapi.synbarra.com/',
            ];

            $breadcrumbItems = $meta['breadcrumbs'] ?? null;
            $breadcrumbJsonLd = $breadcrumbItems ? [
                '@context' => 'https://schema.org',
                '@type' => 'BreadcrumbList',
                'itemListElement' => collect($breadcrumbItems)->values()->map(fn ($item, $idx) => [
                    '@type' => 'ListItem',
                    'position' => $idx + 1,
                    'name' => $item['name'],
                    'item' => $item['url'],
                ])->all(),
            ] : null;

            $dynamicFaqs = $meta['faqs'] ?? null;
            if (is_array($dynamicFaqs) && count($dynamicFaqs) > 0) {
                $faqJsonLd = [
                    '@context' => 'https://schema.org',
                    '@type' => 'FAQPage',
                    'mainEntity' => collect($dynamicFaqs)->map(fn ($f) => [
                        '@type' => 'Question',
                        'name' => $f['q'],
                        'acceptedAnswer' => ['@type' => 'Answer', 'text' => $f['a']],
                    ])->all(),
                ];
            } elseif (request()->is('/')) {
                $faqJsonLd = [
                    '@context' => 'https://schema.org',
                    '@type' => 'FAQPage',
                    'mainEntity' => [
                        [
                            '@type' => 'Question',
                            'name' => 'Bagaimana cara buat invoice online yang rapi dan resmi?',
                            'acceptedAnswer' => [
                                '@type' => 'Answer',
                                'text' => 'Daftar gratis di InvoiceRapi, tambahkan logo usaha dan rekening bank di Profil, lalu buat invoice dalam 1 menit dan unduh PDF A4 1 halaman atau bagikan langsung ke WhatsApp klien.',
                            ],
                        ],
                        [
                            '@type' => 'Question',
                            'name' => 'Apakah bisa buat invoice gratis di InvoiceRapi?',
                            'acceptedAnswer' => [
                                '@type' => 'Answer',
                                'text' => 'Ya, InvoiceRapi menyediakan paket Gratis selamanya (5 invoice/bulan dan 10 klien) serta uji coba gratis paket Pro selama 14 hari tanpa kartu kredit.',
                            ],
                        ],
                        [
                            '@type' => 'Question',
                            'name' => 'Apakah InvoiceRapi mendukung perhitungan PPN 11% dan PPh 21/23 serta termin DP?',
                            'acceptedAnswer' => [
                                '@type' => 'Answer',
                                'text' => 'Ya, pada form buat invoice tersedia opsi centang PPN 11% (+11%), PPh 21 (2,5%), atau PPh 23 (2%) serta pengaturan jadwal DP dan cicilan termin pembayaran.',
                            ],
                        ],
                    ],
                ];
            } else {
                $faqJsonLd = null;
            }
        @endphp

        <!-- Schema.org JSON-LD (Search Console & GEO Structured Data) -->
        @if(!$isPrivatePath)
        <script type="application/ld+json">
        {!! json_encode($jsonLd, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
        </script>
        <script type="application/ld+json">
        {!! json_encode($orgJsonLd, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
        </script>
        <script type="application/ld+json">
        {!! json_encode($websiteJsonLd, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
        </script>
        @if($breadcrumbJsonLd)
        <script type="application/ld+json">
        {!! json_encode($breadcrumbJsonLd, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
        </script>
        @endif
        @if($faqJsonLd)
        <script type="application/ld+json">
        {!! json_encode($faqJsonLd, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
        </script>
        @endif
        @endif

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
            <div style="padding: 24px; font-family: system-ui, -apple-system, sans-serif; color: #0F172A; background: #fff;">
                <h1>{{ $seoTitle }}</h1>
                <p>{{ $seoDesc }}</p>
                <h2>Fitur Utama Synbarra InvoiceRapi</h2>
                <ul>
                    <li><a href="/">Beranda — Aplikasi Invoice Online, DP, Termin &amp; Pelunasan Klien</a></li>
                    <li><a href="/fitur/invoice-online">Aplikasi Invoice Online</a></li>
                    <li><a href="/fitur/pencatatan-dp">Fitur Pencatatan DP</a></li>
                    <li><a href="/fitur/pembayaran-termin">Aplikasi Pembayaran Termin</a></li>
                    <li><a href="/fitur/monitoring-piutang">Monitoring Piutang Pelanggan</a></li>
                    <li><a href="/solusi/wedding-organizer">Invoice Wedding Organizer</a></li>
                    <li><a href="/solusi/event-organizer">Invoice Event Organizer</a></li>
                    <li><a href="/solusi/kontraktor">Aplikasi Invoice Kontraktor</a></li>
                    <li><a href="/solusi/fotografer">Invoice Fotografer</a></li>
                    <li><a href="/solusi/freelancer">Invoice Freelancer</a></li>
                    <li><a href="https://synbarra.com/produk/invoicerapi">Tentang Produk di Synbarra Technologies</a></li>
                </ul>
            </div>
        </noscript>
    </body>
</html>
