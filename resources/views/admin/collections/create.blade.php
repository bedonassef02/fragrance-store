@extends('layouts.admin')

@section('title', 'Create Collection')

@section('content')
<x-admin.ui.page-header title="Create Collection" />

<div class="max-w-2xl mx-auto">
    <x-admin.ui.glass-panel class="p-6">
        <form action="{{ route('admin.collections.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Title -->
                <div class="col-span-2">
                    <x-admin.ui.input name="title" label="Title" placeholder="e.g. Summer Essentials" required />
                </div>

                <!-- Slug -->
                <div>
                     <x-admin.ui.input name="slug" label="Slug (Optional)" placeholder="Auto-generated if empty" />
                </div>

                 <!-- Sort Order -->
                 <div>
                    <x-admin.ui.input type="number" name="sort_order" label="Sort Order" value="0" />
               </div>

                <!-- Subtitle -->
                <div class="col-span-2">
                    <x-admin.ui.textarea name="subtitle" label="Subtitle" rows="2" />
                </div>

                <!-- Image -->
                <div class="col-span-2">
                    <x-admin.ui.file-input name="image" label="Cover Image" />
                    <p class="mt-1 text-xs text-slate-500">Recommended size: 1200x600px</p>
                </div>

                <!-- Layout Class -->
                 <div>
                    <x-admin.ui.input name="layout_class" label="Layout Class" placeholder="e.g. col-span-2" />
               </div>
               
               <!-- CTA Text -->
               <div>
                  <x-admin.ui.input name="cta_text" label="CTA Text" placeholder="e.g. Shop Now" />
             </div>

            </div>

            <div class="flex justify-end gap-3 pt-6 border-t border-slate-700/50">
                <x-admin.ui.button type="a" href="{{ route('admin.collections.index') }}" variant="ghost">Cancel</x-admin.ui.button>
                <x-admin.ui.button type="submit">Create Collection</x-admin.ui.button>
            </div>
        </form>
    </x-admin.ui.glass-panel>
</div>
@endsection
@endsection
