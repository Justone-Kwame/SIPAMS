<div class="p-6 space-y-6">
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <a href="{{ route('inventory.index') }}" wire:navigate class="p-2 -ml-2 rounded-full text-gray-500 hover:bg-gray-100">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-black text-gray-800">Product Batches</h1>
                <p class="text-sm text-gray-500 mt-0.5">Manage all inventory batches</p>
            </div>
        </div>
    </div>

    {{-- Filters --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
        <div class="flex flex-wrap gap-3 items-center">
            <div class="relative flex-1 min-w-48">
                <svg class="absolute left-3 top-2.5 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text" wire:model.live.debounce.300ms="search"
                       placeholder="Search batches..."
                       class="w-full border border-gray-200 rounded-lg pl-9 pr-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-400">
            </div>
            <select wire:model.live="status" class="border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-400">
                <option value="">All Status</option>
                <option value="active">Active</option>
                <option value="depleted">Depleted</option>
            </select>
            <select wire:model.live="productId" class="border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-400">
                <option value="">All Products</option>
                @foreach($products as $product)
                    <option value="{{ $product->id }}">{{ $product->name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    {{-- Batches Table --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-100 text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left font-bold text-gray-600 uppercase tracking-wider text-xs">Batch</th>
                        <th class="px-4 py-3 text-left font-bold text-gray-600 uppercase tracking-wider text-xs">Product</th>
                        <th class="px-4 py-3 text-center font-bold text-gray-600 uppercase tracking-wider text-xs">Initial</th>
                        <th class="px-4 py-3 text-center font-bold text-gray-600 uppercase tracking-wider text-xs">Remaining</th>
                        <th class="px-4 py-3 text-center font-bold text-gray-600 uppercase tracking-wider text-xs">Cost</th>
                        <th class="px-4 py-3 text-center font-bold text-gray-600 uppercase tracking-wider text-xs">Expiry</th>
                        <th class="px-4 py-3 text-center font-bold text-gray-600 uppercase tracking-wider text-xs">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($batches as $batch)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-4 py-3 font-mono text-sm text-gray-700">{{ $batch->batch_number }}</td>
                            <td class="px-4 py-3">
                                <p class="font-semibold text-gray-800">{{ $batch->product->name }}</p>
                                <p class="text-xs text-gray-400">{{ $batch->product->sku ?? 'No SKU' }}</p>
                            </td>
                            <td class="px-4 py-3 text-center text-gray-600">{{ $batch->quantity_initial }}</td>
                            <td class="px-4 py-3 text-center font-semibold {{ $batch->quantity_remaining > 0 ? 'text-green-600' : 'text-gray-400' }}">
                                {{ $batch->quantity_remaining }}
                            </td>
                            <td class="px-4 py-3 text-center text-gray-700">₵{{ number_format($batch->cost_price, 2) }}</td>
                            <td class="px-4 py-3 text-center">
                                @if($batch->expiry_date)
                                    @php
                                        $expiryDate = \Carbon\Carbon::parse($batch->expiry_date);
                                        $isExpired = $expiryDate < now();
                                        $isExpiring = !$isExpired && $expiryDate <= now()->addDays(30);
                                    @endphp
                                    <span class="text-xs font-bold px-2 py-1 rounded-full
                                        {{ $isExpired ? 'bg-red-100 text-red-700' : ($isExpiring ? 'bg-amber-100 text-amber-700' : 'bg-gray-100 text-gray-600') }}">
                                        {{ $expiryDate->format('d M Y') }}
                                    </span>
                                @else
                                    <span class="text-xs text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span class="inline-flex px-2 py-1 rounded-full text-xs font-bold {{ $batch->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                                    {{ ucfirst($batch->status) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-12 text-center text-gray-400">
                                <svg class="w-12 h-12 mx-auto mb-3 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
                                </svg>
                                <p class="text-sm font-semibold">No batches found</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($batches->hasPages())
            <div class="px-4 py-3 border-t border-gray-100">
                {{ $batches->links() }}
            </div>
        @endif
    </div>
</div>
