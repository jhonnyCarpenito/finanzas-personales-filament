<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth overflow-x-hidden">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <title>{{ __('landing.meta.title') }}</title>
    <meta name="description" content="{{ __('landing.meta.description') }}">
    
    <!-- Open Graph -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="{{ __('landing.meta.title') }}">
    <meta property="og:description" content="{{ __('landing.meta.description') }}">
    <meta property="og:image" content="{{ asset('images/og-image.png') }}">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ url()->current() }}">
    <meta property="twitter:title" content="{{ __('landing.meta.title') }}">
    <meta property="twitter:description" content="{{ __('landing.meta.description') }}">
    <meta property="twitter:image" content="{{ asset('images/og-image.png') }}">

    <!-- Hreflang -->
    <link rel="alternate" hreflang="es" href="{{ route('landing') }}" />
    <link rel="alternate" hreflang="en" href="{{ route('landing.en') }}" />
    <link rel="alternate" hreflang="x-default" href="{{ route('landing') }}" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}?v=2">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}?v=2">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}?v=2">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}?v=2">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}?v=2">

    @vite(['resources/css/landing.css', 'resources/js/landing.js'])
</head>
<body class="antialiased bg-zinc-950 text-zinc-50 overflow-x-hidden selection:bg-amber-500/30">
    
    <!-- Subtle Background Grid & Ambience -->
    <div class="fixed inset-0 z-[-1] bg-[linear-gradient(to_right,#8080800a_1px,transparent_1px),linear-gradient(to_bottom,#8080800a_1px,transparent_1px)] bg-[size:32px_32px]"></div>
    <div class="fixed inset-0 overflow-hidden pointer-events-none z-[-1]">
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[800px] h-[400px] bg-gradient-to-b from-amber-500/10 via-amber-600/5 to-transparent blur-[120px]"></div>
    </div>

    @include('landing.partials.nav')

    <main>
        @include('landing.partials.hero')
        @include('landing.partials.comparison')
        @include('landing.partials.features')
        @include('landing.partials.audience')
        @include('landing.partials.trust')
        @include('landing.partials.security')
        @include('landing.partials.faq')
        @include('landing.partials.cta')
    </main>

    @include('landing.partials.footer')

</body>
</html>
