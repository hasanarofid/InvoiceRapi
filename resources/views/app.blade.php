<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        
        <!-- Primary Meta Tags -->
        <title inertia>Synbarra Technologies | Custom Software Development</title>
        <meta name="title" content="Synbarra Technologies | Custom Software Development">
        <meta name="description" content="SYNBARRA TECHNOLOGIES adalah penyedia solusi teknologi digital yang membantu bisnis dan organisasi mengembangkan sistem, website, serta aplikasi sesuai kebutuhan. Kami berfokus pada pengembangan teknologi yang praktis, efisien, dan dapat mendukung pertumbuhan bisnis.">
        <meta name="keywords" content="Synbarra Technologies, Software House Indonesia, Custom Software Development, Web Application Development, Mobile App Development, Automation, System Integration, AI Solutions">
        <meta name="author" content="Synbarra Technologies">
        <meta name="theme-color" content="#01232B">

        <!-- Open Graph / Facebook / WhatsApp -->
        <meta property="og:type" content="website">
        <meta property="og:url" content="https://synbarra.com/">
        <meta property="og:site_name" content="Synbarra Technologies">
        <meta property="og:title" content="Synbarra Technologies | Engineering the Digital Future">
        <meta property="og:description" content="SYNBARRA TECHNOLOGIES adalah penyedia solusi teknologi digital yang membantu bisnis dan organisasi mengembangkan sistem, website, serta aplikasi sesuai kebutuhan.">
        <meta property="og:image" content="https://synbarra.com/images/logo/synbarra-full.png">
        <meta property="og:image:width" content="1205">
        <meta property="og:image:height" content="316">
        <meta property="og:image:type" content="image/png">

        <!-- Twitter Card -->
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:url" content="https://synbarra.com/">
        <meta name="twitter:title" content="Synbarra Technologies | Engineering the Digital Future">
        <meta name="twitter:description" content="SYNBARRA TECHNOLOGIES adalah penyedia solusi teknologi digital yang membantu bisnis dan organisasi mengembangkan sistem, website, serta aplikasi sesuai kebutuhan.">
        <meta name="twitter:image" content="https://synbarra.com/images/logo/synbarra-full.png">

        <!-- Favicons (Crisp Geometric 'S' Ribbon Symbol) -->
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
    </body>
</html>
