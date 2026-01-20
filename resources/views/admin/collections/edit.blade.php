@extends('layouts.admin')

@section('title', 'Edit Collection')

@section('content')
<x-admin.ui.page-header title="Edit Collection" />

<div class="max-w-2xl mx-auto">
    <x-admin.ui.glass-panel class="p-6">
        <form action="{{ route('admin.collections.update', $collection) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Title -->
                <div class="col-span-2">
                    <x-admin.ui.input name="title" label="Title" :value="$collection->title" required />
                </div>

                <!-- Slug -->
                <div>
                     <x-admin.ui.input name="slug" label="Slug" :value="$collection->slug" />
                </div>

                 <!-- Sort Order -->
                 <div>
                    <x-admin.ui.input type="number" name="sort_order" label="Sort Order" :value="$collection->sort_order" />
               </div>

                <!-- Subtitle -->
                <div class="col-span-2">
                    <x-admin.ui.textarea name="subtitle" label="Subtitle" :value="$collection->subtitle" rows="2" />
                </div>

                <!-- Image -->
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-slate-400 mb-2">Cover Image</label>
                    @if($collection->image)
                        <div class="mb-4">
                            <img src="{{ Str::startsWith($collection->image, ['http','https']) ? $collection->image : asset('storage/' . $collection->image) }}" alt="Current Image" class="h-32 rounded-lg border border-slate-700">
                        </div>
                    @endif
                    <input type="file" name="image" class="block w-full text-sm text-slate-400
                        file:mr-4 file:py-2.5 file:px-4
                        file:rounded-lg file:border-0
                        file:text-sm file:font-semibold
                        file:bg-blue-600/10 file:text-blue-400
                        hover:file:bg-blue-600/20
                    " accept="image/*">
                    @error('image') <span class="text-red-400 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                <!-- Layout Class -->
                 <div>
                    <x-admin.ui.input name="layout_class" label="Layout Class" :value="$collection->layout_class" />
               </div>
               
               <!-- CTA Text -->
               <div>
                  <x-admin.ui.input name="cta_text" label="CTA Text" :value="$collection->cta_text" />
             </div>
            </div>

            <div class="flex justify-end gap-3 pt-6 border-t border-slate-700/50">
                <x-admin.ui.button type="a" href="{{ route('admin.collections.index') }}" variant="ghost">Cancel</x-admin.ui.button>
                <x-admin.ui.button type="submit">Update Collection</x-admin.ui.button>
            </div>
        </form>
    </x-admin.ui.glass-panel>
</div>
@endsection
@endsection
