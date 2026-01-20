@extends('layouts.admin')

@section('title', 'Create Category')

@section('content')
<x-admin.ui.page-header title="Create Category" />

<div class="max-w-2xl mx-auto">
    <x-admin.ui.glass-panel class="p-6">
        <form action="{{ route('admin.categories.store') }}" method="POST" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 gap-6">
                <!-- Name -->
                <div>
                    <x-admin.ui.input name="name" label="Name" placeholder="e.g. Hats" required />
                </div>

                <!-- Slug -->
                <div>
                     <x-admin.ui.input name="slug" label="Slug (Optional)" placeholder="Auto-generated if empty" />
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-6 border-t border-slate-700/50">
                <x-admin.ui.button type="a" href="{{ route('admin.categories.index') }}" variant="ghost">Cancel</x-admin.ui.button>
                <x-admin.ui.button type="submit">Create Category</x-admin.ui.button>
            </div>
        </form>
    </x-admin.ui.glass-panel>
</div>
@endsection
