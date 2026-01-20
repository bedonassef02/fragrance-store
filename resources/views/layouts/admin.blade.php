<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Dashboard') | MOON Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .glass-panel {
            background: rgba(30, 41, 59, 0.4);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }
        .sidebar-link {
            position: relative;
            transition: all 0.2s ease-in-out;
        }
        .sidebar-link.active {
            background: linear-gradient(90deg, rgba(59, 130, 246, 0.1) 0%, transparent 100%);
            border-left: 3px solid #3b82f6;
            color: #93c5fd;
        }
        .sidebar-link.active svg {
            color: #60a5fa;
        }
        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #0f172a; }
        ::-webkit-scrollbar-thumb { background: #334155; border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: #475569; }
    </style>
</head>
<body class="bg-[#0f172a] text-slate-300 antialiased min-h-screen flex selection:bg-blue-500 selection:text-white">

    <!-- Sidebar -->
    <aside class="fixed inset-y-0 left-0 w-72 bg-[#1e293b]/50 border-r border-[#334155] z-50 flex flex-col backdrop-blur-xl transition-transform duration-300 transform lg:translate-x-0 -translate-x-full" id="sidebar">
        <!-- Logo Area -->
        <div class="h-20 flex items-center px-8 border-b border-[#334155]">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 group">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-600 to-indigo-600 flex items-center justify-center text-white font-bold text-xl shadow-lg shadow-blue-500/20 group-hover:scale-105 transition-transform">
                    M
                </div>
                <div>
                    <h1 class="text-white font-bold text-lg tracking-tight group-hover:text-blue-400 transition-colors">MOON</h1>
                    <span class="text-[10px] text-slate-500 font-medium uppercase tracking-wider">Admin Space</span>
                </div>
            </a>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 overflow-y-auto py-6 px-4 space-y-1">
            <p class="px-4 text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Overview</p>
            
            <a href="{{ route('admin.dashboard') }}" class="sidebar-link flex items-center px-4 py-3 rounded-lg text-slate-400 hover:text-white hover:bg-white/5 {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <svg class="w-5 h-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>
                <span class="font-medium">Dashboard</span>
            </a>

            <p class="px-4 text-xs font-semibold text-slate-500 uppercase tracking-wider mt-8 mb-2">Store Management</p>

            <a href="#" class="sidebar-link flex items-center px-4 py-3 rounded-lg text-slate-400 hover:text-white hover:bg-white/5">
                <svg class="w-5 h-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg>
                <span class="font-medium">Orders</span>
                <span class="ml-auto text-[10px] font-bold bg-blue-500/20 text-blue-400 px-2 py-0.5 rounded-full">New</span>
            </a>

            <a href="#" class="sidebar-link flex items-center px-4 py-3 rounded-lg text-slate-400 hover:text-white hover:bg-white/5">
                 <svg class="w-5 h-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
                <span class="font-medium">Products</span>
            </a>

            <a href="#" class="sidebar-link flex items-center px-4 py-3 rounded-lg text-slate-400 hover:text-white hover:bg-white/5">
                <svg class="w-5 h-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                <span class="font-medium">Customers</span>
            </a>

            <a href="{{ route('admin.collections.index') }}" class="sidebar-link flex items-center px-4 py-3 rounded-lg text-slate-400 hover:text-white hover:bg-white/5 {{ request()->routeIs('admin.collections.*') ? 'active' : '' }}">
                <svg class="w-5 h-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
                <span class="font-medium">Collections</span>
            </a>

             <a href="{{ route('admin.categories.index') }}" class="sidebar-link flex items-center px-4 py-3 rounded-lg text-slate-400 hover:text-white hover:bg-white/5 {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                <svg class="w-5 h-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" /></svg>
                <span class="font-medium">Categories</span>
            </a>

            <a href="{{ route('admin.reviews.index') }}" class="sidebar-link flex items-center px-4 py-3 rounded-lg text-slate-400 hover:text-white hover:bg-white/5 {{ request()->routeIs('admin.reviews.index') ? 'active' : '' }}">
                <svg class="w-5 h-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" /></svg>
                <span class="font-medium">Reviews</span>
            </a>
            
            <p class="px-4 text-xs font-semibold text-slate-500 uppercase tracking-wider mt-8 mb-2">Settings</p>
            
            <a href="{{ route('admin.profile.edit') }}" class="sidebar-link flex items-center px-4 py-3 rounded-lg text-slate-400 hover:text-white hover:bg-white/5 {{ request()->routeIs('admin.profile.edit') ? 'active' : '' }}">
                <svg class="w-5 h-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                <span class="font-medium">Profile</span>
            </a>
        </nav>

        <!-- Use Profile Bar -->
        <div class="p-4 border-t border-[#334155] bg-[#1e293b]/30">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-slate-700 flex items-center justify-center text-white font-bold border border-slate-600">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-white truncate">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-slate-500 truncate">Administrator</p>
                </div>
                <form action="{{ route('admin.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="text-slate-500 hover:text-red-400 transition-colors p-2" title="Logout">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- Content Area -->
    <main class="flex-1 flex flex-col min-w-0 lg:ml-72 transition-all duration-300">
         <!-- Navbar (Mobile) -->
         <header class="h-16 lg:hidden flex items-center justify-between px-4 bg-[#1e293b]/80 border-b border-[#334155] backdrop-blur-md sticky top-0 z-40">
            <div class="font-bold text-white text-lg">MOON</div>
             <button onclick="document.getElementById('sidebar').classList.toggle('-translate-x-full')" class="text-white p-2">
                 <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"/></svg>
             </button>
         </header>

        <div class="p-6 md:p-10 max-w-7xl mx-auto w-full">
            <header class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h2 class="text-3xl font-bold text-white tracking-tight">@yield('header')</h2>
                    <p class="text-slate-400 mt-1">@yield('subheader', 'Welcome back, ' . Auth::user()->name)</p>
                </div>
                <div class="flex items-center gap-3">
                     @yield('actions')
                     <a href="{{ route('home') }}" target="_blank" class="px-4 py-2 rounded-lg bg-slate-800 text-slate-300 hover:bg-slate-700 hover:text-white transition-colors text-sm font-medium border border-slate-700 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" /></svg>
                        View Store
                     </a>
                </div>
            </header>

            @yield('content')
        </div>
    </main>

</body>
</html>
