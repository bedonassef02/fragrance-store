<nav class="fixed w-full z-50 bg-black border-b border-white/5 transition-all duration-300 py-4" id="main-nav">
    <x-notification-bar />
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <!-- Mobile Menu Button -->
            <div class="flex items-center md:hidden">
                <button id="mobile-menu-btn" class="text-white hover:text-moon-gold transition-colors focus:outline-none p-2">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>

            <!-- Logo -->
            <div class="flex-shrink-0 flex items-center justify-center md:justify-start w-full md:w-auto absolute md:relative left-0 right-0 pointer-events-none md:pointer-events-auto">
                <a href="{{ route('home') }}" class="group flex items-center gap-3 text-3xl font-serif tracking-[0.3em] font-bold pointer-events-auto">
                    <!-- Moon Icon -->
                    <div class="relative w-8 h-8">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-full h-full text-moon-gold drop-shadow-lg transform -rotate-12 group-hover:rotate-0 transition-transform duration-500">
                            <path fill-rule="evenodd" d="M9.528 1.718a.75.75 0 01.162.819A8.97 8.97 0 009 6a9 9 0 009 9 8.97 8.97 0 003.463-.69.75.75 0 01.981.98 10.503 10.503 0 01-9.694 6.46c-5.799 0-10.5-4.701-10.5-10.5 0-4.368 2.667-8.112 6.46-9.694a.75.75 0 01.818.162z" clip-rule="evenodd" />
                        </svg>
                        <!-- Glow effect -->
                        <div class="absolute inset-0 bg-moon-gold/20 blur-md rounded-full -z-10 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    </div>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-white via-moon-gold to-white drop-shadow-sm">MÒÓN</span>
                </a>
            </div>

            <!-- Desktop Links -->
            <div class="hidden md:flex md:items-center md:space-x-8">
                <a href="{{ route('collections') }}" class="nav-link text-sm uppercase tracking-widest {{ request()->routeIs('collections') ? 'text-moon-gold' : 'text-gray-300' }} hover:text-moon-gold transition-colors duration-300">Collections</a>
                <a href="{{ route('shop') }}" class="nav-link text-sm uppercase tracking-widest {{ request()->routeIs('shop') ? 'text-moon-gold' : 'text-gray-300' }} hover:text-moon-gold transition-colors duration-300">Shop</a>
                <a href="{{ route('about') }}" class="nav-link text-sm uppercase tracking-widest {{ request()->routeIs('about') ? 'text-moon-gold' : 'text-gray-300' }} hover:text-moon-gold transition-colors duration-300">Our Story</a>
                <a href="{{ route('contact') }}" class="nav-link text-sm uppercase tracking-widest {{ request()->routeIs('contact') ? 'text-moon-gold' : 'text-gray-300' }} hover:text-moon-gold transition-colors duration-300">Contact</a>
            </div>

            <!-- Actions -->
            <div class="flex items-center space-x-4">
              <a href="{{ route('shop') }}" class="text-gray-300 hover:text-white transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </a>
            <a href="{{ route('cart.index') }}" class="text-gray-300 hover:text-white transition-colors relative">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                <span class="cart-count-badge absolute -top-1 -right-1 bg-moon-gold text-moon-dark text-[10px] font-bold w-4 h-4 rounded-full flex items-center justify-center {{ $cartCount > 0 ? '' : 'hidden' }}">{{ $cartCount }}</span>
            </a>
            </div>
        </div>
    </div>

    <!-- Mobile Menu Panel -->
    <div id="mobile-menu" class="hidden md:hidden bg-moon-dark border-t border-white/5 absolute w-full left-0 top-full">
        <div class="px-4 pt-2 pb-8 space-y-1">
            <a href="{{ route('home') }}" class="block px-3 py-4 border-b border-white/5 text-base font-serif text-white uppercase tracking-wider hover:text-moon-gold">Home</a>
            <a href="{{ route('collections') }}" class="block px-3 py-4 border-b border-white/5 text-base font-serif text-white uppercase tracking-wider hover:text-moon-gold">Collections</a>
            <a href="{{ route('shop') }}" class="block px-3 py-4 border-b border-white/5 text-base font-serif text-white uppercase tracking-wider hover:text-moon-gold">Shop</a>
            <a href="{{ route('about') }}" class="block px-3 py-4 border-b border-white/5 text-base font-serif text-white uppercase tracking-wider hover:text-moon-gold">About</a>
            <a href="{{ route('contact') }}" class="block px-3 py-4 text-base font-serif text-white uppercase tracking-wider hover:text-moon-gold">Contact</a>
        </div>
    </div>
</nav>
