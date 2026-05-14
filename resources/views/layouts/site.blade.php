<!DOCTYPE html>
<html lang="tr" class="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- SEO meta — adım 13 SeoController'dan dinamik gelir, fallback default'lar --}}
    <title>@yield('seo-title', $seo->title ?? setting('site_name', 'Arsi Blue Beach Hotel'))</title>
    <meta name="description" content="@yield('seo-description', $seo->description ?? setting('site_tagline'))">
    <meta name="keywords" content="@yield('seo-keywords', $seo->keywords ?? '')">
    <meta name="robots" content="{{ $seo->robots ?? 'index,follow' }}">
    @if (! empty($seo?->canonical_url))
        <link rel="canonical" href="{{ $seo->canonical_url }}">
    @else
        <link rel="canonical" href="{{ url()->current() }}">
    @endif

    {{-- Open Graph --}}
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="{{ $seo->og_title ?? $seo->title ?? setting('site_name') }}">
    <meta property="og:description" content="{{ $seo->og_description ?? $seo->description ?? setting('site_tagline') }}">
    @if (! empty($seo?->og_image))
        <meta property="og:image" content="{{ Str::startsWith($seo->og_image, 'http') ? $seo->og_image : asset('storage/uploads/'.$seo->og_image) }}">
    @elseif (setting('seo_default_og_image'))
        <meta property="og:image" content="{{ asset('storage/uploads/'.setting('seo_default_og_image')) }}">
    @endif

    {{-- Search Console verification --}}
    @if (setting('google_search_console_verification'))
        <meta name="google-site-verification" content="{{ setting('google_search_console_verification') }}">
    @endif

    {{-- Favicon --}}
    <link rel="icon" href="{{ setting('site_favicon') ? asset('storage/uploads/'.setting('site_favicon')) : asset('favicon.ico') }}">

    {{-- Vite assets --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Tracking pixels (head) --}}
    <x-site.tracking-pixel position="head" />

    {{-- Schema.org JSON-LD --}}
    <x-site.schema-org :type="$schemaType ?? 'WebPage'" :data="$schemaData ?? []" />

    {{-- SEO custom head scripts --}}
    {!! setting('seo_custom_head_scripts') ?: '' !!}
</head>
<body class="bg-background text-on-background font-sans antialiased min-h-screen">

    {{-- Tracking pixels (body) --}}
    <x-site.tracking-pixel position="body" />

    <x-site.header />

    <main>
        @yield('content')
    </main>

    <x-site.footer />

    <x-site.sticky-mobile-cta />

    {{-- SEO custom body scripts --}}
    {!! setting('seo_custom_body_scripts') ?: '' !!}

</body>
</html>
