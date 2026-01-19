<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MOON | Luxury Arabian Fashion</title>
    <meta name="description" content="Discover Moon's exclusive collection of handcrafted abayas, luxury bags, and modern Arabian fashion.">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&family=Lato:wght@300;400;700&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-moon-dark text-moon-light font-sans antialiased overflow-x-hidden">
    <!-- Navigation -->
    <nav class="fixed w-full z-50 bg-moon-dark/95 backdrop-blur-md border-b border-white/5 transition-all duration-300" id="main-nav">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
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
                    <a href="#" class="text-3xl font-serif text-white tracking-[0.2em] font-bold pointer-events-auto">MOON</a>
                </div>

                <!-- Desktop Links -->
                <div class="hidden md:flex md:items-center md:space-x-8">
                    <a href="#collections" class="nav-link text-sm uppercase tracking-widest text-gray-300 hover:text-moon-gold transition-colors duration-300">Collections</a>
                    <a href="#abayas" class="nav-link text-sm uppercase tracking-widest text-gray-300 hover:text-moon-gold transition-colors duration-300">Abayas</a>
                    <a href="#bags" class="nav-link text-sm uppercase tracking-widest text-gray-300 hover:text-moon-gold transition-colors duration-300">Bags</a>
                    <a href="#about" class="nav-link text-sm uppercase tracking-widest text-gray-300 hover:text-moon-gold transition-colors duration-300">Our Story</a>
                </div>

                <!-- Actions -->
                <div class="flex items-center space-x-4">
                    <button class="text-white hover:text-moon-gold transition-colors p-2">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </button>
                    <button class="text-white hover:text-moon-gold transition-colors p-2 relative group">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                        <span class="absolute top-1 right-0 inline-flex items-center justify-center px-1.5 py-0.5 text-[10px] font-bold leading-none text-black bg-moon-gold rounded-full transform scale-0 group-hover:scale-100 transition-transform duration-300">2</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu Panel -->
        <div id="mobile-menu" class="hidden md:hidden bg-moon-dark border-t border-white/5 absolute w-full left-0 top-full">
            <div class="px-4 pt-2 pb-8 space-y-1">
                <a href="#home" class="block px-3 py-4 border-b border-white/5 text-base font-serif text-white uppercase tracking-wider hover:text-moon-gold">Home</a>
                <a href="#collections" class="block px-3 py-4 border-b border-white/5 text-base font-serif text-white uppercase tracking-wider hover:text-moon-gold">Collections</a>
                <a href="#abayas" class="block px-3 py-4 border-b border-white/5 text-base font-serif text-white uppercase tracking-wider hover:text-moon-gold">Abayas</a>
                <a href="#bags" class="block px-3 py-4 border-b border-white/5 text-base font-serif text-white uppercase tracking-wider hover:text-moon-gold">Bags</a>
                <a href="#about" class="block px-3 py-4 border-b border-white/5 text-base font-serif text-white uppercase tracking-wider hover:text-moon-gold">About</a>
                <a href="#contact" class="block px-3 py-4 text-base font-serif text-white uppercase tracking-wider hover:text-moon-gold">Contact</a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <header id="home" class="relative h-screen min-h-[600px] flex items-center justify-center overflow-hidden">
        <!-- Background Overlay -->
        <div class="absolute inset-0 z-0">
            <div class="absolute inset-0 bg-gradient-to-b from-black/60 via-black/40 to-moon-dark"></div>
            <img src="https://images.unsplash.com/photo-1590735213920-68192a487bc2?q=80&w=2070&auto=format&fit=crop" 
                 alt="Luxury Abaya Background" 
                 class="w-full h-full object-cover object-center animate-kenburns opacity-60">
        </div>

        <!-- Content -->
        <div class="relative z-10 text-center px-4 max-w-4xl mx-auto mt-16 sm:mt-0">
            <p class="text-moon-gold uppercase tracking-[0.4em] text-xs sm:text-sm font-light mb-6 animate-fadeInUp">The New Collection</p>
            <h1 class="text-5xl sm:text-6xl md:text-8xl font-serif text-white mb-8 leading-tight font-medium animate-fadeInUp delay-200">
                Elegance <span class="italic font-light text-moon-gold">Redefined</span>
            </h1>
            <p class="text-gray-300 text-lg sm:text-xl font-light mb-12 max-w-lg mx-auto leading-relaxed animate-fadeInUp delay-300">
                Discover the finest Arabian fashion where timeless tradition meets modern luxury.
            </p>
            <div class="flex flex-col sm:flex-row gap-6 justify-center animate-fadeInUp delay-400">
                <a href="#abayas" class="group relative px-10 py-4 bg-moon-gold text-moon-dark font-serif tracking-widest uppercase hover:bg-white transition-all duration-300">
                    <span class="relative z-10 font-bold">Shop Abayas</span>
                </a>
                <a href="#collections" class="group relative px-10 py-4 border border-white/30 text-white font-serif tracking-widest uppercase hover:border-moon-gold hover:text-moon-gold transition-all duration-300 backdrop-blur-sm">
                    <span class="relative z-10">Discover Bags</span>
                </a>
            </div>
        </div>

        <!-- Scroll Indicator -->
        <div class="absolute bottom-10 left-1/2 transform -translate-x-1/2 hidden sm:block animate-bounce">
            <svg class="h-6 w-6 text-white/50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
            </svg>
        </div>
    </header>

    <!-- Featured Collections Grid -->
    <section id="collections" class="py-24 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
        <div class="text-center mb-16">
            <h2 class="text-3xl md:text-4xl font-serif mb-4">Curated Collections</h2>
            <div class="h-px w-24 bg-moon-gold mx-auto"></div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 h-auto md:h-[600px]">
            <!-- Item 1: Large Feature -->
            <div class="group relative md:col-span-2 md:row-span-2 overflow-hidden h-[400px] md:h-full">
                <img src="https://images.unsplash.com/photo-1628045620958-8671607590d9?q=80&w=1000&auto=format&fit=crop" 
                     class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" alt="Ramadan Collection">
                <div class="absolute inset-0 bg-black/30 group-hover:bg-black/40 transition-colors duration-300"></div>
                <div class="absolute bottom-0 left-0 p-8 sm:p-12 w-full">
                    <p class="text-white/80 uppercase tracking-widest text-sm mb-2 transform translate-y-4 opacity-0 group-hover:translate-y-0 group-hover:opacity-100 transition-all duration-500">Limited Edition</p>
                    <h3 class="text-white text-3xl sm:text-4xl font-serif font-bold mb-4">Ramadan 2026 Collection</h3>
                    <a href="#" class="inline-block text-white border-b border-moon-gold pb-1 hover:text-moon-gold transition-colors">Explore Now</a>
                </div>
            </div>

            <!-- Item 2: Bags -->
            <div class="group relative overflow-hidden h-[300px] md:h-auto">
                <img src="https://images.unsplash.com/photo-1548036328-c9fa89d128fa?q=80&w=800&auto=format&fit=crop" 
                     class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" alt="Luxury Bags">
                <div class="absolute inset-0 bg-black/20 group-hover:bg-black/30 transition-colors duration-300"></div>
                <div class="absolute bottom-8 left-8">
                    <h3 class="text-white text-2xl font-serif">Handcrafted Bags</h3>
                </div>
            </div>

            <!-- Item 3: Accessories -->
            <div class="group relative overflow-hidden h-[300px] md:h-auto">
                <img src="https://images.unsplash.com/photo-1615887110697-0819ec23cd75?q=80&w=800&auto=format&fit=crop" 
                     class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" alt="Silk Scarves">
                <div class="absolute inset-0 bg-black/20 group-hover:bg-black/30 transition-colors duration-300"></div>
                <div class="absolute bottom-8 left-8">
                    <h3 class="text-white text-2xl font-serif">Silk Accessories</h3>
                </div>
            </div>
        </div>
    </section>

    <!-- New Arrivals Carousel (Grid for now) -->
    <section id="abayas" class="bg-gray-900/30 py-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-end mb-12">
                <div>
                    <h2 class="text-3xl md:text-4xl font-serif mb-2">New Arrivals</h2>
                    <p class="text-gray-400 font-light">Latest additions to our exclusive abaya line</p>
                </div>
                <a href="#" class="hidden md:inline-flex items-center text-moon-gold hover:text-white transition-colors uppercase tracking-widest text-xs font-bold">
                    View All
                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                <!-- Product Card 1 -->
                <div class="group">
                    <div class="relative overflow-hidden aspect-[3/4] mb-4 bg-gray-800">
                        <img src="https://images.unsplash.com/photo-1583391733956-6c78276477e2?q=80&w=800&auto=format&fit=crop" class="w-full h-full object-cover object-top transition-transform duration-700 group-hover:scale-110" alt="Product">
                        <span class="absolute top-4 left-4 bg-white text-black text-[10px] font-bold px-2 py-1 uppercase tracking-widest">New</span>
                        <button class="absolute bottom-0 w-full bg-moon-gold text-moon-dark py-3 font-bold uppercase text-xs tracking-widest translate-y-full group-hover:translate-y-0 transition-transform duration-300">Add to Bag</button>
                    </div>
                    <div class="text-center">
                        <h3 class="text-lg font-serif mb-1 group-hover:text-moon-gold transition-colors cursor-pointer">Midnight Velvet Abaya</h3>
                        <p class="text-sm font-bold text-gray-400">$295.00</p>
                    </div>
                </div>

                <!-- Product Card 2 -->
                <div class="group">
                    <div class="relative overflow-hidden aspect-[3/4] mb-4 bg-gray-800">
                        <img src="https://images.unsplash.com/photo-1596755094514-f87e34085b2c?q=80&w=800&auto=format&fit=crop" class="w-full h-full object-cover object-top transition-transform duration-700 group-hover:scale-110" alt="Product">
                        <button class="absolute bottom-0 w-full bg-moon-gold text-moon-dark py-3 font-bold uppercase text-xs tracking-widest translate-y-full group-hover:translate-y-0 transition-transform duration-300">Add to Bag</button>
                    </div>
                    <div class="text-center">
                        <h3 class="text-lg font-serif mb-1 group-hover:text-moon-gold transition-colors cursor-pointer">Golden Thread Kaftan</h3>
                        <p class="text-sm font-bold text-gray-400">$450.00</p>
                    </div>
                </div>

                <!-- Product Card 3 -->
                <div class="group">
                    <div class="relative overflow-hidden aspect-[3/4] mb-4 bg-gray-800">
                        <img src="https://images.unsplash.com/photo-1609505848912-b7c3b8b4beda?q=80&w=800&auto=format&fit=crop" class="w-full h-full object-cover object-top transition-transform duration-700 group-hover:scale-110" alt="Product">
                        <button class="absolute bottom-0 w-full bg-moon-gold text-moon-dark py-3 font-bold uppercase text-xs tracking-widest translate-y-full group-hover:translate-y-0 transition-transform duration-300">Add to Bag</button>
                    </div>
                    <div class="text-center">
                        <h3 class="text-lg font-serif mb-1 group-hover:text-moon-gold transition-colors cursor-pointer">Royal Blue Silk</h3>
                        <p class="text-sm font-bold text-gray-400">$380.00</p>
                    </div>
                </div>

                <!-- Product Card 4 -->
                <div class="group">
                    <div class="relative overflow-hidden aspect-[3/4] mb-4 bg-gray-800">
                        <img src="https://images.unsplash.com/photo-1590736969955-71cc94801759?q=80&w=800&auto=format&fit=crop" class="w-full h-full object-cover object-top transition-transform duration-700 group-hover:scale-110" alt="Product">
                        <button class="absolute bottom-0 w-full bg-moon-gold text-moon-dark py-3 font-bold uppercase text-xs tracking-widest translate-y-full group-hover:translate-y-0 transition-transform duration-300">Add to Bag</button>
                    </div>
                    <div class="text-center">
                        <h3 class="text-lg font-serif mb-1 group-hover:text-moon-gold transition-colors cursor-pointer">Emerald Evening Set</h3>
                        <p class="text-sm font-bold text-gray-400">$325.00</p>
                    </div>
                </div>
            </div>
            
            <div class="mt-8 text-center md:hidden">
                <a href="#" class="inline-block border border-gray-600 px-8 py-3 uppercase text-xs tracking-widest hover:border-moon-gold hover:text-moon-gold transition-colors">View All Products</a>
            </div>
        </div>
    </section>

    <!-- Brand Story / Parallax -->
    <section id="about" class="relative py-32 bg-fixed bg-center bg-cover" style="background-image: url('https://images.unsplash.com/photo-1558171813-4c088753af8f?q=80&w=2000&auto=format&fit=crop');">
        <div class="absolute inset-0 bg-black/70"></div>
        <div class="relative z-10 max-w-4xl mx-auto px-4 text-center">
            <svg class="w-12 h-12 text-moon-gold mx-auto mb-8 opacity-80" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2L1 21h22L12 2zm0 3.8L19.2 19H4.8L12 5.8z"/></svg>
            <h2 class="text-4xl md:text-5xl font-serif text-white mb-8">Crafting Dreams Since 2014</h2>
            <p class="text-lg md:text-xl text-gray-300 font-light leading-relaxed mb-10">
                Moon represents the ethereal beauty of the Arabian night. Our designs are a tribute to the modern woman who embraces her heritage with grace and confidence. Handcrafted in Dubai, worn worldwide.
            </p>
            <a href="#about" class="inline-block bg-white text-black px-10 py-4 font-serif font-bold uppercase tracking-widest hover:bg-moon-gold hover:text-white transition-colors duration-300">Read Our Story</a>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-moon-dark border-t border-white/10 pt-20 pb-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-16">
                <!-- Brand -->
                <div class="md:col-span-1">
                    <span class="text-2xl font-serif font-bold tracking-widest text-white block mb-6">MOON</span>
                    <p class="text-gray-400 text-sm leading-relaxed mb-6">
                        Exquisite Arabian fashion for the sophisticated woman. Designed in Dubai.
                    </p>
                    <div class="flex space-x-4">
                        <a href="#" class="text-gray-400 hover:text-moon-gold transition-colors"><span class="sr-only">Instagram</span><svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></path></svg></a>
                    </div>
                </div>

                <!-- Links -->
                <div>
                    <h4 class="text-white font-serif mb-6 uppercase tracking-widest text-xs">Shop</h4>
                    <ul class="space-y-3 text-sm text-gray-400">
                        <li><a href="#" class="hover:text-moon-gold transition-colors">New Arrivals</a></li>
                        <li><a href="#" class="hover:text-moon-gold transition-colors">Bestsellers</a></li>
                        <li><a href="#" class="hover:text-moon-gold transition-colors">Abayas</a></li>
                        <li><a href="#" class="hover:text-moon-gold transition-colors">Bags & Accessories</a></li>
                    </ul>
                </div>

                <!-- Links -->
                <div>
                    <h4 class="text-white font-serif mb-6 uppercase tracking-widest text-xs">Client Services</h4>
                    <ul class="space-y-3 text-sm text-gray-400">
                        <li><a href="#" class="hover:text-moon-gold transition-colors">Contact Us</a></li>
                        <li><a href="#" class="hover:text-moon-gold transition-colors">Delivery & Returns</a></li>
                        <li><a href="#" class="hover:text-moon-gold transition-colors">Size Guide</a></li>
                        <li><a href="#" class="hover:text-moon-gold transition-colors">Book an Appointment</a></li>
                    </ul>
                </div>

                <!-- Newsletter -->
                <div>
                    <h4 class="text-white font-serif mb-6 uppercase tracking-widest text-xs">The Newsletter</h4>
                    <p class="text-gray-400 text-sm mb-4">Be the first to know about new collections and exclusive events.</p>
                    <form class="flex border-b border-gray-700 pb-2">
                        <input type="email" placeholder="Your email address" class="bg-transparent w-full text-white placeholder-gray-500 focus:outline-none text-sm">
                        <button type="submit" class="text-moon-gold text-sm uppercase tracking-wider font-bold hover:text-white transition-colors">Join</button>
                    </form>
                </div>
            </div>

            <div class="border-t border-white/10 pt-8 flex flex-col md:flex-row justify-between items-center text-xs text-gray-500">
                <p>&copy; 2026 Moon Fashion. All rights reserved.</p>
                <div class="flex space-x-6 mt-4 md:mt-0">
                    <a href="#" class="hover:text-gray-300">Privacy Policy</a>
                    <a href="#" class="hover:text-gray-300">Terms & Conditions</a>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>