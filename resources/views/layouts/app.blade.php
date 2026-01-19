<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'MOON | Luxury Arabian Fashion')</title>
    <meta name="description" content="@yield('description', 'Discover Moon\'s exclusive collection of handcrafted abayas, luxury bags, and modern Arabian fashion.')">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&family=Lato:wght@300;400;700&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="bg-moon-dark text-moon-light font-sans antialiased overflow-x-hidden flex flex-col min-h-screen">
    <x-header />

    <main class="flex-grow">
        @yield('content')
    </main>

    <x-footer />
    
    @stack('scripts')
</body>
</html>
