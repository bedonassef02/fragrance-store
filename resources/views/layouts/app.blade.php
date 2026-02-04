<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'MOON | Luxury Arabian Fashion')</title>
    <meta name="description" content="@yield('description', 'Discover Moon\'s exclusive collection of handcrafted abayas, luxury bags, and modern Arabian fashion.')">

    <!-- Canonical URL -->
    <link rel="canonical" href="@yield('canonical', url()->current())" />

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="@yield('og:type', 'website')" />
    <meta property="og:url" content="@yield('og:url', url()->current())" />
    <meta property="og:title" content="@yield('og:title', 'MOON | Luxury Arabian Fashion')" />
    <meta property="og:description" content="@yield('og:description', 'Discover Moon\'s exclusive collection of handcrafted abayas, luxury bags, and modern Arabian fashion.')" />
    <meta property="og:image" content="@yield('og:image', asset('moon-icon.svg'))" />
    <meta property="og:locale" content="ar_AR" />
    <meta property="og:site_name" content="MOON" />

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image" />
    <meta property="twitter:url" content="@yield('og:url', url()->current())" />
    <meta property="twitter:title" content="@yield('og:title', 'MOON | Luxury Arabian Fashion')" />
    <meta property="twitter:description" content="@yield('og:description', 'Discover Moon\'s exclusive collection of handcrafted abayas, luxury bags, and modern Arabian fashion.')" />
    <meta property="twitter:image" content="@yield('og:image', asset('moon-icon.svg'))" />
    
    <!-- Geo Tags for Egypt -->
    <meta name="geo.region" content="EG" />
    <meta name="geo.placename" content="Cairo, Egypt" />
    <meta name="geo.position" content="30.0444;31.2357" />
    <meta name="ICBM" content="30.0444, 31.2357" />
    
    <!-- Fonts - Modern Luxury -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;0,600;1,400&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    
    <link rel="icon" type="image/svg+xml" href="{{ asset('moon-icon.svg') }}">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="bg-cream text-charcoal font-sans antialiased overflow-x-hidden flex flex-col min-h-screen">
    <x-header />

    <main class="flex-grow">
        @yield('content')
    </main>

    <x-footer />
    
    <div id="backdrop" class="fixed inset-0 bg-charcoal/60 z-40 hidden backdrop-blur-sm transition-opacity opacity-0"></div>
    <x-quick-add-modal />
    <x-size-guide-modal />
    <x-whatsapp-button />
    
    @stack('scripts')
</body>
</html>
