<div class="p-6 max-w-5xl mx-auto space-y-6">

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-black text-gray-800">
                {{ $productId ? 'Edit Product' : 'New Product' }}
            </h1>
            <p class="text-sm text-gray-500 mt-0.5">Fill in the details below to {{ $productId ? 'update the' : 'register a new' }} product.</p>
        </div>
        <a href="{{ route('products.index') }}" wire:navigate
           class="flex items-center gap-1 text-sm text-gray-500 hover:text-gray-700 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Back to Products
        </a>
    </div>

    @if (session()->has('success'))
        <div class="px-4 py-3 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm">{{ session('success') }}</div>
    @endif

    <form wire:submit.prevent="save" class="space-y-6">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- ══ LEFT: Main Info ══ --}}
            <div class="lg:col-span-2 space-y-6">

                {{-- ── Product Registration ── --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-5 py-3 border-b border-gray-100 bg-gray-50">
                        <h2 class="text-sm font-black uppercase tracking-wider text-gray-600">Product Registration</h2>
                    </div>
                    <div class="p-5 grid grid-cols-1 sm:grid-cols-2 gap-4">

                        {{-- Product Name --}}
                        <div class="sm:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Product Name <span class="text-red-500">*</span></label>
                            <input type="text" wire:model.live="name"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-400"
                                placeholder="e.g. Sunflower Oil 1L">
                            @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        {{-- SKU --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">SKU</label>
                            <div class="flex gap-2">
                                <input type="text" wire:model="sku"
                                    class="flex-1 border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-400"
                                    placeholder="Auto-generated">
                            </div>
                            @error('sku') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        {{-- Barcode --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Barcode</label>
                            <input type="text" wire:model="barcode"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-400"
                                placeholder="Scan or enter barcode">
                            @error('barcode') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        {{-- Category --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Category <span class="text-red-500">*</span></label>
                            <select wire:model="category_id"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-400">
                                <option value="">— Select Category —</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                            @error('category_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        {{-- Brand --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Brand</label>
                            <input type="text" wire:model="brand"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-400"
                                placeholder="e.g. Frytol">
                        </div>

                        {{-- Unit --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Unit <span class="text-red-500">*</span></label>
                            <select wire:model="unit"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-400">
                                @foreach($units as $u)
                                    <option value="{{ $u }}">{{ $u }}</option>
                                @endforeach
                            </select>
                            @error('unit') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        {{-- Description --}}
                        <div class="sm:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Description</label>
                            <textarea wire:model="description" rows="3"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-400 resize-none"
                                placeholder="Optional product description…"></textarea>
                        </div>
                    </div>
                </div>

                {{-- ── Pricing ── --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-5 py-3 border-b border-gray-100 bg-gray-50">
                        <h2 class="text-sm font-black uppercase tracking-wider text-gray-600">Pricing</h2>
                    </div>
                    <div class="p-5 grid grid-cols-2 gap-4">

                        {{-- Unit Cost --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Unit Cost (₵) <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <span class="absolute left-3 top-2 text-gray-400 text-sm font-bold">₵</span>
                                <input type="number" step="0.01" min="0" wire:model.live="cost_price"
                                    class="w-full border border-gray-300 rounded-lg pl-7 pr-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-400"
                                    placeholder="0.00">
                            </div>
                            @error('cost_price') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        {{-- Selling Price --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Selling Price (₵) <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <span class="absolute left-3 top-2 text-gray-400 text-sm font-bold">₵</span>
                                <input type="number" step="0.01" min="0" wire:model.live="selling_price"
                                    class="w-full border border-gray-300 rounded-lg pl-7 pr-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-400"
                                    placeholder="0.00">
                            </div>
                            @error('selling_price') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        {{-- Profit Amount (calculated) --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Profit Amount (₵)</label>
                            <div class="flex items-center h-9 px-3 rounded-lg border text-sm font-bold
                                {{ $profit_amount >= 0 ? 'bg-green-50 border-green-200 text-green-700' : 'bg-red-50 border-red-200 text-red-600' }}">
                                {{ $profit_amount >= 0 ? '+' : '' }}₵{{ number_format($profit_amount, 2) }}
                            </div>
                            <p class="text-xs text-gray-400 mt-1">Selling Price − Cost Price</p>
                        </div>

                        {{-- Profit % (calculated) --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Profit Margin (%)</label>
                            <div class="flex items-center h-9 px-3 rounded-lg border text-sm font-bold
                                {{ $profit_percent >= 0 ? 'bg-green-50 border-green-200 text-green-700' : 'bg-red-50 border-red-200 text-red-600' }}">
                                {{ $profit_percent >= 0 ? '+' : '' }}{{ number_format($profit_percent, 2) }}%
                            </div>
                            <p class="text-xs text-gray-400 mt-1">(Profit ÷ Cost) × 100</p>
                        </div>
                    </div>
                </div>

                {{-- ── Stock Information ── --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-5 py-3 border-b border-gray-100 bg-gray-50">
                        <h2 class="text-sm font-black uppercase tracking-wider text-gray-600">Stock Information</h2>
                    </div>
                    <div class="p-5 grid grid-cols-2 sm:grid-cols-4 gap-4">

                        {{-- Initial Quantity --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">
                                {{ $productId ? 'Current Quantity' : 'Opening Stock' }}
                                <span class="text-red-500">*</span>
                            </label>
                            <input type="number" min="0" wire:model.live="initial_quantity"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-400"
                                {{ $productId ? 'readonly' : '' }}>
                            @if($productId)
                                <p class="text-xs text-gray-400 mt-1">Use Stock Adjustment to change</p>
                            @endif
                            @error('initial_quantity') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        {{-- Reorder Level --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Reorder Level <span class="text-red-500">*</span></label>
                            <input type="number" min="0" wire:model="reorder_level"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-400">
                            @error('reorder_level') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        {{-- Max Stock --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Maximum Stock</label>
                            <input type="number" min="0" wire:model="max_stock"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-400"
                                placeholder="Optional">
                            @error('max_stock') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        {{-- Inventory Value (calculated) --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Inventory Value (₵)</label>
                            <div class="flex items-center h-9 px-3 rounded-lg border bg-blue-50 border-blue-200 text-blue-700 text-sm font-bold">
                                ₵{{ number_format($this->inventoryValue, 2) }}
                            </div>
                            <p class="text-xs text-gray-400 mt-1">Qty × Unit Cost</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ══ RIGHT: Image ══ --}}
            <div class="space-y-6">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-5 py-3 border-b border-gray-100 bg-gray-50">
                        <h2 class="text-sm font-black uppercase tracking-wider text-gray-600">Product Image</h2>
                    </div>
                    <div class="p-5">

                        {{-- Preview --}}
                        <div class="w-full aspect-square rounded-xl border-2 border-dashed border-gray-200 flex items-center justify-center overflow-hidden mb-4 bg-gray-50">
                            @if ($image)
                                <img src="{{ $image->temporaryUrl() }}" alt="Preview" class="w-full h-full object-contain">
                            @elseif ($productId && \App\Models\Product::find($productId)?->image_path)
                                <img src="{{ asset('storage/' . \App\Models\Product::find($productId)->image_path) }}" alt="Current" class="w-full h-full object-contain">
                            @else
                                <div class="text-center text-gray-300 p-4">
                                    <svg class="w-16 h-16 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    <p class="text-xs">No image</p>
                                </div>
                            @endif
                        </div>

                        <label class="w-full flex flex-col items-center justify-center gap-1 py-3 rounded-lg border border-gray-300 cursor-pointer hover:bg-gray-50 transition text-sm text-gray-600">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                            </svg>
                            Upload Image
                            <input type="file" wire:model="image" accept="image/*" class="hidden">
                        </label>
                        <p class="text-xs text-gray-400 text-center mt-1">PNG, JPG, GIF up to 2MB</p>
                        @error('image') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- Summary card --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 space-y-3">
                    <h2 class="text-sm font-black uppercase tracking-wider text-gray-600">Summary</h2>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-500">Cost Price</span>
                            <span class="font-semibold text-gray-800">₵{{ number_format((float)$cost_price, 2) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Selling Price</span>
                            <span class="font-semibold text-gray-800">₵{{ number_format((float)$selling_price, 2) }}</span>
                        </div>
                        <div class="flex justify-between border-t border-gray-100 pt-2">
                            <span class="text-gray-500">Profit</span>
                            <span class="font-bold {{ $profit_amount >= 0 ? 'text-green-600' : 'text-red-500' }}">
                                {{ $profit_amount >= 0 ? '+' : '' }}₵{{ number_format($profit_amount, 2) }}
                                ({{ number_format($profit_percent, 1) }}%)
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Opening Stock</span>
                            <span class="font-semibold text-gray-800">{{ number_format((int)$initial_quantity) }} {{ $unit }}</span>
                        </div>
                        <div class="flex justify-between border-t border-gray-100 pt-2">
                            <span class="text-gray-500">Inventory Value</span>
                            <span class="font-bold text-blue-600">₵{{ number_format($this->inventoryValue, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        {{-- Submit --}}
        <div class="flex items-center justify-end gap-3 pt-2">
            <a href="{{ route('products.index') }}" wire:navigate
               class="px-5 py-2.5 rounded-lg border border-gray-300 text-sm font-semibold text-gray-700 hover:bg-gray-50 transition">
                Cancel
            </a>
            <button type="submit"
                class="px-6 py-2.5 rounded-lg bg-teal-600 hover:bg-teal-700 text-white text-sm font-black shadow transition">
                <span wire:loading.remove wire:target="save">{{ $productId ? 'Update Product' : 'Save Product' }}</span>
                <span wire:loading wire:target="save">Saving…</span>
            </button>
        </div>
    </form>
</div>
