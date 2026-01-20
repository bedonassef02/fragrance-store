@extends('layouts.admin')

@section('title', 'Profile')

@section('content')
<x-admin.ui.page-header title="Profile Settings" description="Manage your account security and details." />

<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    
    <!-- Update Profile Info -->
    <x-admin.ui.glass-panel class="p-8 relative overflow-hidden group">
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
                <x-admin.ui.input name="name" label="Name" :value="$user->name" required />
            </div>

            <div>
                 <x-admin.ui.input type="email" name="email" label="Email" :value="$user->email" required />
            </div>

            <div class="flex items-center gap-4 pt-4">
                <x-admin.ui.button type="submit">Save Changes</x-admin.ui.button>
            </div>
        </form>
    </x-admin.ui.glass-panel>

    <!-- Update Password -->
    <x-admin.ui.glass-panel class="p-8 relative overflow-hidden">
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
                <x-admin.ui.input type="password" name="current_password" label="Current Password" required />
            </div>

            <div>
                 <x-admin.ui.input type="password" name="password" label="New Password" required />
            </div>

            <div>
                 <x-admin.ui.input type="password" name="password_confirmation" label="Confirm Password" required />
            </div>

            <div class="flex items-center gap-4 pt-4">
                 <x-admin.ui.button type="submit">Update Password</x-admin.ui.button>
            </div>
        </form>
    </x-admin.ui.glass-panel>

</div>
@endsection
