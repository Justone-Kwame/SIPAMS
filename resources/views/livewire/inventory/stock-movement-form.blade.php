<div class="p-6 space-y-6">
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <a href="{{ route('inventory.index') }}" wire:navigate class="p-2 -ml-2 rounded-full text-gray-500 hover:bg-gray-100">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-black text-gray-800">{{ $type === 'in' ? 'Add Stock' : 'Remove Stock' }}</h1>
                <p class="text-sm text-gray-500 mt-0.5">{{ $type === 'in' ? 'Receive new stock' : 'Issue stock from inventory' }}</p>
            </div>
        </div>
    </div>

    <div class="max-w-2xl">
        <form wire:submit.prevent="save" class="space-y-5 bg-white rounded-xl border border-gray-100 shadow-sm p-6">
            {{-- Type Toggle --}}
            <div class="flex gap-2 p-1 bg-gray-100 rounded-lg">
                <button type="button" wire:click="$set('type', 'in')"
                    class="flex-1 py-2 text-sm font-bold rounded-md transition {{ $type === 'in' ? 'bg-white text-teal-600 shadow-sm' : 'text-gray-500 hover:text-gray-700' }}">
                    Add Stock (In)
                </button>
                <button type="button" wire:click="$set('type', 'out')"
                    class="flex-1 py-2 text-sm font-bold rounded-md transition {{ $type === 'out' ? 'bg-white text-red-600 shadow-sm' : 'text-gray-500 hover:text-gray-700' }}">
                    Remove Stock (Out)
                </button>
            </div>

            {{-- Product --}}
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1.5">Product <span class="text-red-500">*</span></label>
                <select wire:model.live="productId" class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-teal-400">
                    <option value="">Select Product</option>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}">{{ $product->name }} ({{ $product->sku ?? 'No SKU' }})</option>
                    @endforeach
                </select>
                @error('productId') <span class="text-xs text-red-600 mt-1 block">{{ $message }}</span> @enderror
            </div>

            {{-- Quantity --}}
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1.5">Quantity <span class="text-red-500">*</span></label>
                <input type="number" wire:model="quantity" min="1" class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-teal-400">
                @error('quantity') <span class="text-xs text-red-600 mt-1 block">{{ $message }}</span> @enderror
            </div>

            {{-- Cost Price (Only for In) --}}
            @if($type === 'in')
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1.5">Cost Price <span class="text-red-500">*</span></label>
                    <input type="number" step="0.01" wire:model="costPrice" min="0" class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-teal-400">
                    @error('costPrice') <span class="text-xs text-red-600 mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1.5">Expiry Date</label>
                    <input type="date" wire:model="expiryDate" class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-teal-400">
                </div>
            @endif

            {{-- Batch (Only for Out) --}}
            @if($type === 'out' && $productId)
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1.5">Batch <span class="text-red-500">*</span></label>
                    <select wire:model="batchId" class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-teal-400">
                        <option value="">Select Batch</option>
                        @foreach($batches as $batch)
                            <option value="{{ $batch->id }}">
                                {{ $batch->batch_number }} - {{ $batch->quantity_remaining }} left
                                @if($batch->expiry_date)
                                    (Exp: {{ \Carbon\Carbon::parse($batch->expiry_date)->format('d M Y') }})
                                @endif
                            </option>
                        @endforeach
                    </select>
                    @error('batchId') <span class="text-xs text-red-600 mt-1 block">{{ $message }}</span> @enderror
                </div>
            @endif

            {{-- Notes --}}
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1.5">Notes</label>
                <textarea wire:model="notes" rows="3" class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-teal-400"></textarea>
            </div>

            <div class="flex items-center gap-3 pt-2">
                <a href="{{ route('inventory.index') }}" wire:navigate class="flex-1 py-2.5 text-center rounded-lg border border-gray-300 text-sm font-semibold text-gray-700 hover:bg-gray-50 transition">Cancel</a>
                <button type="submit" class="flex-1 py-2.5 text-center rounded-lg {{ $type === 'in' ? 'bg-teal-600 hover:bg-teal-700' : 'bg-red-600 hover:bg-red-700' }} text-white text-sm font-bold shadow transition">
                    {{ $type === 'in' ? 'Add Stock' : 'Remove Stock' }}
                </button>
            </div>
        </form>
    </div>
</div>
