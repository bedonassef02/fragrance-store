<nav id="main-nav" class="fixed w-full z-50 bg-cream/95 backdrop-blur-sm border-b border-neutral-200 transition-all duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-20">
            <!-- Mobile Menu Button -->
            <div class="flex items-center lg:hidden">
                <button id="mobile-menu-btn" class="text-charcoal hover:text-accent transition-colors focus:outline-none p-2">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>

            <!-- Desktop Navigation (Left) -->
            <div class="hidden lg:flex lg:items-center lg:space-x-8">
                <a href="{{ route('shop') }}" class="nav-link text-xs uppercase tracking-[0.2em] {{ request()->routeIs('shop') ? 'text-charcoal' : 'text-neutral-600' }} hover:text-charcoal transition-colors duration-300">Shop</a>
                <a href="{{ route('collections') }}" class="nav-link text-xs uppercase tracking-[0.2em] {{ request()->routeIs('collections') ? 'text-charcoal' : 'text-neutral-600' }} hover:text-charcoal transition-colors duration-300">Collections</a>
                <a href="{{ route('about') }}" class="nav-link text-xs uppercase tracking-[0.2em] {{ request()->routeIs('about') ? 'text-charcoal' : 'text-neutral-600' }} hover:text-charcoal transition-colors duration-300">Our Story</a>
            </div>

            <!-- Logo (Center) -->
            <div class="flex-shrink-0 flex items-center justify-center absolute left-1/2 transform -translate-x-1/2 lg:static lg:transform-none">
                <a href="{{ route('home') }}" class="group flex flex-col items-center">
                    <span class="text-2xl md:text-3xl font-serif tracking-[0.25em] text-charcoal font-normal">MOON</span>
                    <span class="text-[9px] uppercase tracking-[0.3em] text-neutral-500 mt-0.5">Fragrances</span>
                </a>
            </div>

            <!-- Actions (Right) -->
            <div class="flex items-center space-x-5">
                <!-- Search -->
                <a href="{{ route('shop') }}" class="text-neutral-600 hover:text-charcoal transition-colors hidden sm:block">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </a>
                
                <!-- Wishlist -->
                <a href="{{ route('wishlist.index') }}" class="text-neutral-600 hover:text-charcoal transition-colors relative">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                    </svg>
                    <span id="wishlist-count" class="absolute -top-1.5 -right-1.5 bg-charcoal text-cream text-[9px] font-medium w-4 h-4 rounded-full flex items-center justify-center hidden">0</span>
                </a>

                <!-- Cart -->
                <a href="{{ route('cart.index') }}" class="text-neutral-600 hover:text-charcoal transition-colors relative">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>
                    <span class="cart-count-badge absolute -top-1.5 -right-1.5 bg-charcoal text-cream text-[9px] font-medium w-4 h-4 rounded-full flex items-center justify-center {{ $cartCount > 0 ? '' : 'hidden' }}">{{ $cartCount }}</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Mobile Menu Panel -->
    <div id="mobile-menu" class="hidden lg:hidden bg-cream border-t border-neutral-200 absolute w-full left-0 top-full shadow-lg">
        <div class="px-6 py-8 space-y-1">
            <a href="{{ route('home') }}" class="block py-3 text-lg font-serif text-charcoal border-b border-neutral-100">Home</a>
            <a href="{{ route('shop') }}" class="block py-3 text-lg font-serif text-charcoal border-b border-neutral-100">Shop</a>
            <a href="{{ route('collections') }}" class="block py-3 text-lg font-serif text-charcoal border-b border-neutral-100">Collections</a>
            <a href="{{ route('about') }}" class="block py-3 text-lg font-serif text-charcoal border-b border-neutral-100">Our Story</a>
            <a href="{{ route('wishlist.index') }}" class="block py-3 text-lg font-serif text-charcoal border-b border-neutral-100">Wishlist</a>
            <a href="{{ route('cart.index') }}" class="block py-3 text-lg font-serif text-charcoal">Cart</a>
        </div>
    </div>
</nav>
