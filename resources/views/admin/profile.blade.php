@extends('layouts.admin')

@section('title', 'Profile')
@section('header', 'Profile Settings')
@section('subheader', 'Manage your account security and details.')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    
    <!-- Update Profile Info -->
    <div class="glass-panel p-8 rounded-2xl relative overflow-hidden group">
        <div class="absolute top-0 right-0 w-32 h-32 bg-blue-500/10 rounded-full blur-3xl -mr-16 -mt-16 pointer-events-none"></div>
        
        <h3 class="text-xl font-bold text-white mb-1">Profile Information</h3>
        <p class="text-slate-400 text-sm mb-6">Update your account's profile information and email address.</p>

        @if (session('status') === 'profile-updated')
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" class="mb-4 text-sm text-green-400 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                Saved successfully.
            </div>
        @endif

        <form method="post" action="{{ route('admin.profile.update') }}" class="space-y-6 relative z-10">
            @csrf
            @method('patch')

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Name</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="w-full bg-[#0f172a] border border-slate-700 rounded-lg px-4 py-3 text-slate-200 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition-all">
                @error('name') <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Email</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" required class="w-full bg-[#0f172a] border border-slate-700 rounded-lg px-4 py-3 text-slate-200 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition-all">
                @error('email') <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div class="flex items-center gap-4 pt-4">
                <button type="submit" class="bg-blue-600 hover:bg-blue-500 text-white px-6 py-2.5 rounded-lg font-medium transition-colors shadow-lg shadow-blue-500/25">Save Changes</button>
            </div>
        </form>
    </div>

    <!-- Update Password -->
    <div class="glass-panel p-8 rounded-2xl relative overflow-hidden">
        <div class="absolute bottom-0 left-0 w-32 h-32 bg-indigo-500/10 rounded-full blur-3xl -ml-16 -mb-16 pointer-events-none"></div>

        <h3 class="text-xl font-bold text-white mb-1">Update Password</h3>
        <p class="text-slate-400 text-sm mb-6">Ensure your account is using a long, random password to stay secure.</p>

        @if (session('status') === 'password-updated')
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" class="mb-4 text-sm text-green-400 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                Password updated.
            </div>
        @endif

        <form method="post" action="{{ route('admin.password.update') }}" class="space-y-6 relative z-10">
            @csrf
            @method('put')

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Current Password</label>
                <input type="password" name="current_password" required class="w-full bg-[#0f172a] border border-slate-700 rounded-lg px-4 py-3 text-slate-200 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none transition-all">
                @error('current_password') <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">New Password</label>
                <input type="password" name="password" required class="w-full bg-[#0f172a] border border-slate-700 rounded-lg px-4 py-3 text-slate-200 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none transition-all">
                @error('password') <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Confirm Password</label>
                <input type="password" name="password_confirmation" required class="w-full bg-[#0f172a] border border-slate-700 rounded-lg px-4 py-3 text-slate-200 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none transition-all">
            </div>

            <div class="flex items-center gap-4 pt-4">
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-500 text-white px-6 py-2.5 rounded-lg font-medium transition-colors shadow-lg shadow-indigo-500/25">Update Password</button>
            </div>
        </form>
    </div>

</div>
@endsection
