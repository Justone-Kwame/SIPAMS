<div class="p-6 space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-black text-gray-800">Inventory</h1>
            <p class="text-sm text-gray-500 mt-0.5">Manage your inventory, batches, and stock movements.</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('inventory.stock-in') }}" wire:navigate class="px-4 py-2 rounded-lg bg-teal-600 hover:bg-teal-700 text-white text-sm font-bold shadow">
                <span class="flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Add Stock
                </span>
            </a>
            <a href="{{ route('inventory.movements') }}" wire:navigate class="px-4 py-2 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50 text-sm font-semibold">
                Stock Log
            </a>
            <a href="{{ route('inventory.batches') }}" wire:navigate class="px-4 py-2 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50 text-sm font-semibold">
                Batches
            </a>
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Total Stock Value</p>
                    <p class="text-2xl font-black text-gray-800 mt-1">₵{{ number_format($totalStockValue, 2) }}</p>
                </div>
                <div class="w-10 h-10 rounded-full bg-teal-100 text-teal-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Low Stock</p>
                    <p class="text-2xl font-black text-amber-600 mt-1">{{ $lowStockCount }}</p>
                </div>
                <div class="w-10 h-10 rounded-full bg-amber-100 text-amber-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Out of Stock</p>
                    <p class="text-2xl font-black text-red-600 mt-1">{{ $outOfStockCount }}</p>
                </div>
                <div class="w-10 h-10 rounded-full bg-red-100 text-red-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Expiring Soon</p>
                    <p class="text-2xl font-black text-purple-600 mt-1">{{ $expiringCount }}</p>
                </div>
                <div class="w-10 h-10 rounded-full bg-purple-100 text-purple-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
            </div>
        </div>
    </div>

    {{-- Products Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Products List --}}
        <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-4 border-b border-gray-100">
                <div class="relative">
                    <svg class="absolute left-3 top-2.5 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input type="text" wire:model.live.debounce.300ms="search"
                           placeholder="Search products..."
                           class="w-full border border-gray-200 rounded-lg pl-9 pr-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-400">
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-100 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left font-bold text-gray-600 uppercase tracking-wider text-xs">Product</th>
                            <th class="px-4 py-3 text-center font-bold text-gray-600 uppercase tracking-wider text-xs">Stock</th>
                            <th class="px-4 py-3 text-center font-bold text-gray-600 uppercase tracking-wider text-xs">Reorder</th>
                            <th class="px-4 py-3 text-right font-bold text-gray-600 uppercase tracking-wider text-xs">Value</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($products as $product)
                            @php
                                $totalQty = $product->batches->sum('quantity_remaining');
                                $value = $totalQty * $product->cost_price;
                                $isLow = $totalQty <= $product->reorder_level;
                            @endphp
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center text-gray-500">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="font-semibold text-gray-800">{{ $product->name }}</p>
                                            <p class="text-xs text-gray-400">{{ $product->sku ?? 'No SKU' }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <span class="inline-flex px-2 py-1 rounded-full text-xs font-bold {{ $isLow ? 'bg-amber-100 text-amber-700' : 'bg-green-100 text-green-700' }}">
                                        {{ $totalQty }} {{ $product->unit }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-center text-gray-600">{{ $product->reorder_level }}</td>
                                <td class="px-4 py-3 text-right font-semibold text-gray-800">₵{{ number_format($value, 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-12 text-center text-gray-400">
                                    <svg class="w-12 h-12 mx-auto mb-3 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                    </svg>
                                    <p class="text-sm font-semibold">No products found</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($products->hasPages())
                <div class="px-4 py-3 border-t border-gray-100">
                    {{ $products->links() }}
                </div>
            @endif
        </div>

        {{-- Recent Movements --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-4 border-b border-gray-100">
                <h3 class="font-bold text-gray-800">Recent Movements</h3>
            </div>
            <div class="divide-y divide-gray-50">
                @forelse($recentMovements as $movement)
                    <div class="p-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center {{ $movement->type === 'in' ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' }}">
                                    @if($movement->type === 'in')
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>
                                    @else
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/></svg>
                                    @endif
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-800">{{ $movement->product->name }}</p>
                                    <p class="text-xs text-gray-400">{{ $movement->notes ?? $movement->reference_type }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-bold {{ $movement->type === 'in' ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $movement->type === 'in' ? '+' : '-' }}{{ $movement->quantity }}
                                </p>
                                <p class="text-xs text-gray-400">{{ $movement->created_at->format('d M H:i') }}</p>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center text-gray-400 text-sm">No recent movements</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
