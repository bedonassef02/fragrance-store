@extends('layouts.admin')

@section('title', 'Edit Collection')

@section('header')
    <x-admin.layout.header title="Edit Collection" subtitle="Update details for &quot;{{ $collection->title }}&quot;" />
@endsection

@section('content')
<form action="{{ route('admin.collections.update', $collection) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="space-y-8">
        <!-- Main Details -->
        <x-admin.ui.section title="Collection Details" icon='<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>'>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <x-admin.form.input name="title" label="Collection Title" :value="$collection->title" required />
                <x-admin.form.input name="slug" label="Slug" :value="$collection->slug" required />
            </div>

            <x-admin.form.textarea name="subtitle" label="Subtitle" :value="$collection->subtitle" rows="2" placeholder="Brief description displayed under the title..." />

            <x-admin.form.image-upload name="image" label="Cover Image" :existing="$collection->image ? asset('storage/' . $collection->image) : null" />

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                 <x-admin.form.input type="number" name="sort_order" label="Sort Order" :value="$collection->sort_order" placeholder="0" />
                 
                <x-admin.form.select name="layout_class" label="Layout Style" :selected="$collection->layout_class" :options="['grid' => 'Grid', 'list' => 'List', 'carousel' => 'Carousel']" />

                <x-admin.form.input name="cta_text" label="CTA Text" :value="$collection->cta_text" placeholder="e.g. Shop Collection" />
            </div>
        </x-admin.ui.section>

        <!-- Action Buttons -->
        <div class="flex items-center justify-end gap-3 pt-6 border-t border-white/10">
            <x-admin.ui.button href="{{ route('admin.collections.index') }}" variant="ghost">Cancel</x-admin.ui.button>
            <x-admin.ui.button type="submit">Update Collection</x-admin.ui.button>
        </div>
    </div>
</form>
@endsection
