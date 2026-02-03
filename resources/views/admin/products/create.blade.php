@extends('layouts.admin')

@section('title', 'Create Product')

@section('header')
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-white tracking-tight font-display">New Product</h1>
            <p class="text-moon-gray-400 mt-1">Add a new perfume to your catalog.</p>
        </div>
    </div>
@endsection

@section('content')
    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" id="product-form">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column (Main Details) -->
            <div class="lg:col-span-2 space-y-8">

                <!-- Basic Details -->
                <div class="bg-moon-dark/50 backdrop-blur-xl border border-white/10 rounded-2xl p-8 shadow-2xl">
                    <h3 class="text-xl font-bold text-white mb-6 font-display flex items-center gap-2">
                        <svg class="w-5 h-5 text-moon-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Basic Information
                    </h3>

                    <div class="space-y-6">
                        <div>
                            <label for="name" class="block text-sm font-medium text-moon-gray-300 mb-2">Perfume Name</label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" required
                                placeholder="e.g. Sauvage Elixir"
                                class="w-full bg-black/20 border border-white/10 rounded-xl px-4 py-3 text-white placeholder-moon-gray-500 focus:border-moon-gold focus:ring-1 focus:ring-moon-gold transition-all">
                            @error('name') <span class="text-red-400 text-sm mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label for="description"
                                class="block text-sm font-medium text-moon-gray-300 mb-2">Description</label>
                            <textarea name="description" id="description" rows="5" required
                                placeholder="Describe the fragrance notes, inspiration, and scent profile..."
                                class="w-full bg-black/20 border border-white/10 rounded-xl px-4 py-3 text-white placeholder-moon-gray-500 focus:border-moon-gold focus:ring-1 focus:ring-moon-gold transition-all resize-none">{{ old('description') }}</textarea>
                            @error('description') <span class="text-red-400 text-sm mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Perfume Specifics -->
                <div class="bg-moon-dark/50 backdrop-blur-xl border border-white/10 rounded-2xl p-8 shadow-2xl">
                    <h3 class="text-xl font-bold text-white mb-6 font-display flex items-center gap-2">
                        <svg class="w-5 h-5 text-moon-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z">
                            </path>
                        </svg>
                        Fragrance Profile
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="concentration"
                                class="block text-sm font-medium text-moon-gray-300 mb-2">Concentration</label>
                            <select name="concentration" id="concentration"
                                class="w-full bg-neutral-900 border border-white/10 rounded-xl px-4 py-3 text-moon-gray-300 focus:border-moon-gold focus:ring-1 focus:ring-moon-gold transition-all">
                                <option value="">Select Concentration</option>
                                <option value="EDT" {{ old('concentration') == 'EDT' ? 'selected' : '' }}>Eau de Toilette
                                    (EDT)</option>
                                <option value="EDP" {{ old('concentration') == 'EDP' ? 'selected' : '' }}>Eau de Parfum (EDP)
                                </option>
                                <option value="Parfum" {{ old('concentration') == 'Parfum' ? 'selected' : '' }}>Parfum /
                                    Extrait</option>
                                <option value="Cologne" {{ old('concentration') == 'Cologne' ? 'selected' : '' }}>Eau de
                                    Cologne</option>
                            </select>
                        </div>

                        <div>
                            <label for="gender" class="block text-sm font-medium text-moon-gray-300 mb-2">Gender</label>
                            <select name="gender" id="gender" required
                                class="w-full bg-neutral-900 border border-white/10 rounded-xl px-4 py-3 text-moon-gray-300 focus:border-moon-gold focus:ring-1 focus:ring-moon-gold transition-all">
                                <option value="unisex" {{ old('gender') == 'unisex' ? 'selected' : '' }}>Unisex</option>
                                <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Men</option>
                                <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Women</option>
                            </select>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-moon-gray-300 mb-2">Olfactory Notes</label>
                            <div
                                class="bg-black/20 border border-white/10 rounded-xl p-4 max-h-60 overflow-y-auto custom-scrollbar">
                                <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                                    @foreach($notes as $note)
                                        <label
                                            class="flex items-center gap-2 p-2 rounded-lg hover:bg-white/5 cursor-pointer transition-colors">
                                            <input type="checkbox" name="notes[]" value="{{ $note->id }}" {{ in_array($note->id, old('notes', [])) ? 'checked' : '' }}
                                                class="rounded border-white/10 bg-black/40 text-moon-gold focus:ring-moon-gold">
                                            <span class="text-sm text-gray-300">{{ $note->name }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Images (Enhanced) -->
                <div class="bg-moon-dark/50 backdrop-blur-xl border border-white/10 rounded-2xl p-8 shadow-2xl">
                    <h3 class="text-xl font-bold text-white mb-6 font-display flex items-center gap-2">
                        <svg class="w-5 h-5 text-moon-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                            </path>
                        </svg>
                        Media Gallery
                    </h3>

                    <div id="image-upload-area"
                        class="border-2 border-dashed border-white/10 rounded-xl p-8 text-center hover:border-moon-gold/50 hover:bg-white/[0.02] transition-colors relative cursor-pointer"
                        onclick="document.getElementById('temp-image-input').click()">
                        <div class="space-y-2 pointer-events-none">
                            <div
                                class="mx-auto w-14 h-14 rounded-full bg-moon-gold/10 flex items-center justify-center text-moon-gold mb-4">
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12">
                                    </path>
                                </svg>
                            </div>
                            <div class="text-base text-white font-medium">Click to upload image</div>
                            <div class="text-sm text-moon-gray-500">Supports JPG, PNG, WEBP</div>
                        </div>
                        <!-- Hidden input for selecting files -->
                        <input type="file" id="temp-image-input" multiple accept="image/*" class="hidden"
                            onchange="handleFiles(this.files)">
                    </div>

                    <!-- Dynamic Image List -->
                    <div id="images-container" class="mt-6 space-y-4"></div>

                    @error('images') <span class="text-red-400 text-sm mt-1 block">{{ $message }}</span> @enderror
                </div>

                <!-- Variants -->
                <div class="bg-moon-dark/50 backdrop-blur-xl border border-white/10 rounded-2xl p-8 shadow-2xl">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-bold text-white font-display flex items-center gap-2">
                            <svg class="w-5 h-5 text-moon-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01">
                                </path>
                            </svg>
                            Variants & Inventory
                        </h3>
                        <button type="button" onclick="addVariant()"
                            class="px-4 py-2 bg-gradient-to-r from-moon-gold to-yellow-500 text-moon-dark font-bold text-xs uppercase tracking-wider rounded-lg hover:shadow-[0_0_15px_rgba(255,215,0,0.3)] transition-all transform hover:-translate-y-0.5">
                            <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4">
                                </path>
                            </svg>
                            Add Variant
                        </button>
                    </div>

                    <div class="overflow-x-auto rounded-xl border border-white/5">
                        <table class="w-full text-left">
                            <thead class="bg-white/5">
                                <tr>
                                    <th class="px-4 py-3 text-xs font-bold text-moon-gray-400 uppercase tracking-widest">
                                        Type</th>
                                    <th
                                        class="px-4 py-3 text-xs font-bold text-moon-gray-400 uppercase tracking-widest pl-4">
                                        Capacity</th>
                                    <th
                                        class="px-4 py-3 text-xs font-bold text-moon-gray-400 uppercase tracking-widest pl-4">
                                        Unit</th>
                                    <th
                                        class="px-4 py-3 text-xs font-bold text-moon-gray-400 uppercase tracking-widest pl-4">
                                        Price (Override)</th>
                                    <th
                                        class="px-4 py-3 text-xs font-bold text-moon-gray-400 uppercase tracking-widest pl-4">
                                        Qty</th>
                                    <th
                                        class="px-4 py-3 text-xs font-bold text-moon-gray-400 uppercase tracking-widest pl-4 text-right">
                                        Action</th>
                                </tr>
                            </thead>
                            <tbody id="variants-container" class="divide-y divide-white/5">
                                <!-- Helper template for JS -->
                            </tbody>
                        </table>
                    </div>
                    @error('variants') <span class="text-red-400 text-sm mt-1 block">{{ $message }}</span> @enderror
                </div>
            </div>

            <!-- Right Column (Settings) -->
            <div class="space-y-8">

                <!-- Pricing -->
                <div class="bg-moon-dark/50 backdrop-blur-xl border border-white/10 rounded-2xl p-6 shadow-2xl">
                    <h3 class="text-lg font-bold text-white mb-6 font-display">Pricing</h3>
                    <div class="space-y-4">
                        <div>
                            <label for="price" class="block text-sm font-medium text-moon-gray-300 mb-2">Base Price
                                (LE)</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-moon-gray-500">LE</span>
                                <input type="number" step="0.01" name="price" id="price" value="{{ old('price') }}" required
                                    class="w-full bg-black/20 border border-white/10 rounded-xl pl-10 pr-4 py-2.5 text-white placeholder-moon-gray-500 focus:border-moon-gold focus:ring-1 focus:ring-moon-gold transition-all font-mono">
                            </div>
                            @error('price') <span class="text-red-400 text-sm mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label for="original_price" class="block text-sm font-medium text-moon-gray-300 mb-2">Original
                                Price (LE)</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-moon-gray-500">LE</span>
                                <input type="number" step="0.01" name="original_price" id="original_price"
                                    value="{{ old('original_price') }}"
                                    class="w-full bg-black/20 border border-white/10 rounded-xl pl-10 pr-4 py-2.5 text-white placeholder-moon-gray-500 focus:border-moon-gold focus:ring-1 focus:ring-moon-gold transition-all font-mono">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Organization -->
                <div class="bg-moon-dark/50 backdrop-blur-xl border border-white/10 rounded-2xl p-6 shadow-2xl">
                    <h3 class="text-lg font-bold text-white mb-6 font-display">Organization</h3>
                    <div class="space-y-6">
                        <div>
                            <label for="category_id"
                                class="block text-sm font-medium text-moon-gray-300 mb-2">Category</label>
                            <select name="category_id" id="category_id" required
                                class="w-full bg-neutral-900 border border-white/10 rounded-xl px-4 py-2.5 text-moon-gray-300 focus:border-moon-gold focus:ring-1 focus:ring-moon-gold transition-all">
                                <option value="">Select Category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                @endforeach
                            </select>
                            @error('category_id') <span class="text-red-400 text-sm mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label for="brand_id" class="block text-sm font-medium text-moon-gray-300 mb-2">Brand</label>
                            <select name="brand_id" id="brand_id" required
                                class="w-full bg-neutral-900 border border-white/10 rounded-xl px-4 py-2.5 text-moon-gray-300 focus:border-moon-gold focus:ring-1 focus:ring-moon-gold transition-all">
                                <option value="">Select Brand</option>
                                @foreach($brands as $brand)
                                    <option value="{{ $brand->id }}" {{ old('brand_id') == $brand->id ? 'selected' : '' }}>
                                        {{ $brand->name }}</option>
                                @endforeach
                            </select>
                            @error('brand_id') <span class="text-red-400 text-sm mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-moon-gray-300 mb-2">Collections</label>
                            <div class="space-y-2 max-h-48 overflow-y-auto pr-2 custom-scrollbar">
                                @foreach($collections as $collection)
                                    <label
                                        class="flex items-center gap-3 p-3 rounded-xl border border-white/5 bg-white/[0.02] hover:bg-white/5 cursor-pointer transition-all group">
                                        <div class="relative flex items-center">
                                            <input type="checkbox" name="collections[]" value="{{ $collection->id }}" {{ in_array($collection->id, old('collections', [])) ? 'checked' : '' }}
                                                class="peer h-5 w-5 cursor-pointer appearance-none rounded-md border border-white/10 bg-black/40 checked:border-moon-gold checked:bg-moon-gold transition-all">
                                            <svg class="pointer-events-none absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 w-3.5 h-3.5 opacity-0 peer-checked:opacity-100 text-moon-dark transition-opacity"
                                                fill="none" viewBox="0 0 14 14" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                    d="M3 7l3 3 5-5"></path>
                                            </svg>
                                        </div>
                                        <span
                                            class="text-sm font-medium text-moon-gray-300 group-hover:text-white transition-colors">{{ $collection->name }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Status -->
                <div class="bg-moon-dark/50 backdrop-blur-xl border border-white/10 rounded-2xl p-6 shadow-2xl">
                    <h3 class="text-lg font-bold text-white mb-6 font-display">Status</h3>
                    <div class="space-y-3">
                        <label
                            class="flex items-center gap-3 p-3 rounded-xl border border-white/5 bg-white/[0.02] hover:bg-white/5 cursor-pointer transition-all group">
                            <div class="relative flex items-center">
                                <input type="checkbox" name="featured" value="1" {{ old('featured') ? 'checked' : '' }}
                                    class="peer h-5 w-5 cursor-pointer appearance-none rounded-md border border-white/10 bg-black/40 checked:border-moon-gold checked:bg-moon-gold transition-all">
                                <svg class="pointer-events-none absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 w-3.5 h-3.5 opacity-0 peer-checked:opacity-100 text-moon-dark transition-opacity"
                                    fill="none" viewBox="0 0 14 14" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M3 7l3 3 5-5">
                                    </path>
                                </svg>
                            </div>
                            <span
                                class="text-sm font-medium text-moon-gray-300 group-hover:text-white transition-colors">Featured
                                Product</span>
                        </label>
                        <label
                            class="flex items-center gap-3 p-3 rounded-xl border border-white/5 bg-white/[0.02] hover:bg-white/5 cursor-pointer transition-all group">
                            <div class="relative flex items-center">
                                <input type="checkbox" name="trending" value="1" {{ old('trending') ? 'checked' : '' }}
                                    class="peer h-5 w-5 cursor-pointer appearance-none rounded-md border border-white/10 bg-black/40 checked:border-moon-gold checked:bg-moon-gold transition-all">
                                <svg class="pointer-events-none absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 w-3.5 h-3.5 opacity-0 peer-checked:opacity-100 text-moon-dark transition-opacity"
                                    fill="none" viewBox="0 0 14 14" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M3 7l3 3 5-5">
                                    </path>
                                </svg>
                            </div>
                            <span
                                class="text-sm font-medium text-moon-gray-300 group-hover:text-white transition-colors">Trending
                                Product</span>
                        </label>
                    </div>
                </div>

                <!-- Badge -->
                <div class="bg-moon-dark/50 backdrop-blur-xl border border-white/10 rounded-2xl p-6 shadow-2xl">
                    <h3 class="text-lg font-bold text-white mb-6 font-display">Badge</h3>
                    <div class="space-y-4">
                        <div>
                            <label for="badge" class="block text-sm font-medium text-moon-gray-300 mb-2">Label</label>
                            <input type="text" name="badge" id="badge" value="{{ old('badge') }}"
                                placeholder="e.g. New Arrival"
                                class="w-full bg-black/20 border border-white/10 rounded-xl px-4 py-2.5 text-white placeholder-moon-gray-500 focus:border-moon-gold focus:ring-1 focus:ring-moon-gold transition-all">
                        </div>
                        <div>
                            <label for="badge_color" class="block text-sm font-medium text-moon-gray-300 mb-2">Color</label>
                            <div class="grid grid-cols-5 gap-2">
                                @foreach(['blue', 'green', 'red', 'yellow', 'purple'] as $c)
                                    <label class="cursor-pointer">
                                        <input type="radio" name="badge_color" value="{{ $c }}" class="peer hidden" {{ old('badge_color') == $c ? 'checked' : '' }}>
                                        <div
                                            class="w-full aspect-square rounded-lg bg-{{ $c }}-500/20 border border-{{ $c }}-500/50 hover:bg-{{ $c }}-500/40 peer-checked:bg-{{ $c }}-500 peer-checked:text-white transition-all flex items-center justify-center">
                                            <div class="w-3 h-3 rounded-full bg-{{ $c }}-500 peer-checked:bg-white"></div>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SEO Settings -->
                <div class="bg-moon-dark/50 backdrop-blur-xl border border-white/10 rounded-2xl p-6 shadow-2xl">
                    <h3 class="text-lg font-bold text-white mb-6 font-display flex items-center gap-2">
                        <svg class="w-5 h-5 text-moon-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        SEO Settings
                    </h3>
                    <div class="space-y-4">
                        <div>
                            <label for="meta_title" class="block text-sm font-medium text-moon-gray-300 mb-2">Meta
                                Title</label>
                            <input type="text" name="meta_title" id="meta_title" value="{{ old('meta_title') }}"
                                placeholder="Custom title for search engines"
                                class="w-full bg-black/20 border border-white/10 rounded-xl px-4 py-2.5 text-white placeholder-moon-gray-500 focus:border-moon-gold focus:ring-1 focus:ring-moon-gold transition-all">
                            <p class="text-xs text-moon-gray-500 mt-1">Leave blank to use product name.</p>
                        </div>
                        <div>
                            <label for="meta_description" class="block text-sm font-medium text-moon-gray-300 mb-2">Meta
                                Description</label>
                            <textarea name="meta_description" id="meta_description" rows="3"
                                placeholder="Summary for search results..."
                                class="w-full bg-black/20 border border-white/10 rounded-xl px-4 py-2.5 text-white placeholder-moon-gray-500 focus:border-moon-gold focus:ring-1 focus:ring-moon-gold transition-all resize-none">{{ old('meta_description') }}</textarea>
                        </div>
                        <div>
                            <label for="keywords" class="block text-sm font-medium text-moon-gray-300 mb-2">Keywords</label>
                            <textarea name="keywords" id="keywords" rows="2" placeholder="comma, separated, keywords"
                                class="w-full bg-black/20 border border-white/10 rounded-xl px-4 py-2.5 text-white placeholder-moon-gray-500 focus:border-moon-gold focus:ring-1 focus:ring-moon-gold transition-all resize-none">{{ old('keywords') }}</textarea>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <div class="flex items-center justify-end gap-3 mt-8 pt-6 border-t border-white/10">
        <a href="{{ route('admin.products.index') }}"
            class="px-6 py-3 text-sm text-moon-gray-400 hover:text-white transition-colors font-bold">
            Cancel
        </a>
        <button type="submit"
            class="px-8 py-3 bg-gradient-to-r from-moon-gold to-yellow-500 text-moon-dark font-bold text-sm rounded-xl hover:shadow-[0_0_20px_rgba(255,215,0,0.2)] transition-all transform hover:-translate-y-0.5">
            Save Product
        </button>
    </div>
</form>

    <script>
        let imageIndex = 0;

        // Image Handling
        function handleFiles(files) {
            const container = document.getElementById('images-container');

            Array.from(files).forEach(file => {
                const currentIndex = imageIndex++;
                const reader = new FileReader();

                reader.onload = function (e) {
                    const div = document.createElement('div');
                    div.className = 'flex items-center gap-4 p-4 rounded-xl bg-white/5 border border-white/5';
                    div.id = `image-row-${currentIndex}`;

                    div.innerHTML = `
                        <div class="w-20 h-20 rounded-lg overflow-hidden border border-white/10 bg-black/50 shrink-0">
                            <img src="${e.target.result}" class="w-full h-full object-cover">
                        </div>
                        <div class="flex-1">
                            <div class="text-sm font-medium text-white truncate max-w-[200px]">${file.name}</div>
                            <div class="text-xs text-moon-gray-500">${(file.size / 1024).toFixed(1)} KB</div>
                        </div>
                        <button type="button" onclick="removeImage(${currentIndex})" class="p-2 text-moon-gray-400 hover:text-red-500 hover:bg-red-500/10 rounded-lg transition-all">
                             <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </button>
                        <!-- Actual File Input (Cloned) -->
                        <input type="file" name="images[${currentIndex}][file]" class="hidden" id="file-input-${currentIndex}">
                    `;

                    container.appendChild(div);

                    // Assign the file object to a new DataTransfer to populate the input
                    const dt = new DataTransfer();
                    dt.items.add(file);
                    const fileInput = document.getElementById(`file-input-${currentIndex}`);
                    fileInput.files = dt.files;
                }
                reader.readAsDataURL(file);
            });

            // Reset main input
            document.getElementById('temp-image-input').value = '';
        }

        function removeImage(index) {
            document.getElementById(`image-row-${index}`).remove();
        }

        // Variants Management
        let variantIndex = 0;

        function addVariant() {
            const container = document.getElementById('variants-container');
            const row = document.createElement('tr');
            row.className = 'group hover:bg-white/[0.02] transition-colors';
            row.id = `variant-${variantIndex}`;

            row.innerHTML = `
                <td class="px-4 py-2">
                    <select name="variants[${variantIndex}][container_type]" required
                            class="w-32 bg-neutral-900 border border-white/10 rounded-lg px-3 py-1.5 text-moon-gray-300 focus:border-moon-gold focus:ring-1 focus:ring-moon-gold transition-all text-sm">
                        <option value="Bottle">Bottle</option>
                        <option value="Decant">Decant</option>
                        <option value="Sample">Sample</option>
                        <option value="Tester">Tester</option>
                    </select>
                </td>
                <td class="px-4 py-2 pl-4">
                     <input type="number" name="variants[${variantIndex}][capacity]" placeholder="100" required
                           class="w-24 bg-black/20 border border-white/10 rounded-lg px-3 py-1.5 text-white focus:border-moon-gold focus:ring-1 focus:ring-moon-gold transition-all text-sm">
                </td>
                <td class="px-4 py-2 pl-4">
                     <select name="variants[${variantIndex}][unit]" required
                            class="w-20 bg-neutral-900 border border-white/10 rounded-lg px-3 py-1.5 text-moon-gray-300 focus:border-moon-gold focus:ring-1 focus:ring-moon-gold transition-all text-sm">
                        <option value="ml">ml</option>
                        <option value="oz">oz</option>
                        <option value="g">g</option>
                    </select>
                </td>
                <td class="px-4 py-2 pl-4">
                    <input type="number" step="0.01" name="variants[${variantIndex}][price]" placeholder="Override"
                           class="w-28 bg-black/20 border border-white/10 rounded-lg px-3 py-1.5 text-white focus:border-moon-gold focus:ring-1 focus:ring-moon-gold transition-all text-sm">
                </td>
                <td class="px-4 py-2 pl-4">
                    <input type="number" name="variants[${variantIndex}][quantity]" placeholder="0" required min="0"
                           class="w-20 bg-black/20 border border-white/10 rounded-lg px-3 py-1.5 text-white focus:border-moon-gold focus:ring-1 focus:ring-moon-gold transition-all text-sm">
                </td>
                <td class="px-4 py-2 pl-4 text-right">
                    <button type="button" onclick="removeVariant(${variantIndex})" class="p-2 text-moon-gray-400 hover:text-red-500 hover:bg-red-500/10 rounded-lg transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                    </button>
                </td>
            `;
            container.appendChild(row);
            variantIndex++;
        }

        function removeVariant(index) {
            document.getElementById(`variant-${index}`).remove();
        }

        // Add initial variant
        addVariant();
    </script>
@endsection