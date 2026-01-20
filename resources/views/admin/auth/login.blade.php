<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Login | MOON</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="bg-[#0f172a] text-slate-300 font-sans antialiased min-h-screen flex items-center justify-center relative overflow-hidden">
    
    <!-- Cyber Background -->
    <div class="absolute inset-0 bg-[#0f172a] overflow-hidden pointer-events-none">
        <div class="absolute top-[-10%] left-0 w-full h-[500px] bg-gradient-to-b from-blue-900/20 to-transparent"></div>
        <div class="absolute bottom-[-10%] right-[-10%] w-[500px] h-[500px] bg-indigo-600/10 rounded-full blur-[100px]"></div>
    </div>

    <div class="w-full max-w-sm relative z-10 transition-all duration-500 ease-out transform scale-100">
        
        <div class="text-center mb-8">
            <div class="w-16 h-16 mx-auto bg-gradient-to-br from-blue-600 to-indigo-600 rounded-2xl flex items-center justify-center text-white font-bold text-3xl shadow-xl shadow-blue-500/20 mb-4">
                M
            </div>
            <h1 class="text-2xl font-bold text-white tracking-tight">Welcome Back</h1>
            <p class="text-slate-500 text-sm mt-2">Sign in to access your dashboard</p>
        </div>

        <div class="bg-[#1e293b]/50 backdrop-blur-xl border border-white/5 p-8 rounded-2xl shadow-2xl">
            <form method="POST" action="{{ route('admin.authenticate') }}" class="space-y-5">
                @csrf

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus 
                        class="w-full bg-[#0f172a] border border-slate-700 rounded-lg px-4 py-3 text-white text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 placeholder-slate-600 outline-none transition-all">
                    @error('email')
                        <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Password</label>
                    <input id="password" type="password" name="password" required 
                        class="w-full bg-[#0f172a] border border-slate-700 rounded-lg px-4 py-3 text-white text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 placeholder-slate-600 outline-none transition-all">
                    @error('password')
                        <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <div class="flex items-center justify-between">
                    <label class="flex items-center space-x-2 cursor-pointer">
                        <input type="checkbox" name="remember" class="form-checkbox bg-slate-800 border-slate-700 text-blue-600 rounded w-4 h-4 focus:ring-0 focus:ring-offset-0">
                        <span class="text-xs text-slate-400">Remember me</span>
                    </label>
                </div>

                <button type="submit" 
                    class="w-full bg-blue-600 hover:bg-blue-500 text-white py-3.5 rounded-xl font-medium transition-all shadow-lg shadow-blue-600/20 hover:shadow-blue-600/30">
                    Sign In
                </button>
            </form>
        </div>
        
        <p class="text-center mt-8 text-xs text-slate-600">
            &copy; {{ date('Y') }} MOON Store. All rights reserved.
        </p>
    </div>

</body>
</html>
