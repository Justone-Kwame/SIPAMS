<div class="p-6 space-y-6">
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <a href="{{ route('inventory.index') }}" wire:navigate class="p-2 -ml-2 rounded-full text-gray-500 hover:bg-gray-100">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-black text-gray-800">Stock Movements</h1>
                <p class="text-sm text-gray-500 mt-0.5">Complete log of all stock transactions</p>
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
                       placeholder="Search..."
                       class="w-full border border-gray-200 rounded-lg pl-9 pr-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-400">
            </div>
            <select wire:model.live="type" class="border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-400">
                <option value="">All Types</option>
                <option value="in">Stock In</option>
                <option value="out">Stock Out</option>
            </select>
            <select wire:model.live="productId" class="border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-400">
                <option value="">All Products</option>
                @foreach($products as $product)
                    <option value="{{ $product->id }}">{{ $product->name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    {{-- Movements Table --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-100 text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left">
                            <button wire:click="sortBy('created_at')" class="flex items-center gap-1 font-bold text-gray-600 hover:text-gray-900 uppercase tracking-wider text-xs">
                                Date
                                @if($sortBy === 'created_at') <span class="text-teal-500">{{ $sortDir === 'asc' ? '↑' : '↓' }}</span> @endif
                            </button>
                        </th>
                        <th class="px-4 py-3 text-left font-bold text-gray-600 uppercase tracking-wider text-xs">Product</th>
                        <th class="px-4 py-3 text-center font-bold text-gray-600 uppercase tracking-wider text-xs">Type</th>
                        <th class="px-4 py-3 text-center font-bold text-gray-600 uppercase tracking-wider text-xs">Qty</th>
                        <th class="px-4 py-3 text-left font-bold text-gray-600 uppercase tracking-wider text-xs">Ref / Notes</th>
                        <th class="px-4 py-3 text-left font-bold text-gray-600 uppercase tracking-wider text-xs">User</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($movements as $movement)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-4 py-3 whitespace-nowrap">
                                <div class="text-sm text-gray-800">{{ $movement->created_at->format('d M Y') }}</div>
                                <div class="text-xs text-gray-400">{{ $movement->created_at->format('h:i A') }}</div>
                            </td>
                            <td class="px-4 py-3">
                                <p class="font-semibold text-gray-800">{{ $movement->product->name }}</p>
                                <p class="text-xs text-gray-400">{{ $movement->product->sku ?? 'No SKU' }}</p>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span class="inline-flex px-2 py-1 rounded-full text-xs font-bold {{ $movement->type === 'in' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                    {{ $movement->type === 'in' ? 'In' : 'Out' }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span class="font-bold {{ $movement->type === 'in' ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $movement->type === 'in' ? '+' : '-' }}{{ $movement->quantity }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-gray-600 text-sm">
                                {{ $movement->notes ?? ($movement->reference_type ? ucfirst($movement->reference_type) : '-') }}
                            </td>
                            <td class="px-4 py-3 text-gray-700 text-sm">
                                {{ $movement->user?->name ?? 'System' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-12 text-center text-gray-400">
                                <svg class="w-12 h-12 mx-auto mb-3 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                                <p class="text-sm font-semibold">No movements found</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($movements->hasPages())
            <div class="px-4 py-3 border-t border-gray-100">
                {{ $movements->links() }}
            </div>
        @endif
    </div>
</div>
