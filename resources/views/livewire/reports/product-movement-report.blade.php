<div class="p-6 space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-black text-gray-800">Product Movement Reports</h1>
            <p class="text-sm text-gray-500 mt-0.5">Identify fast and slow-moving products.</p>
        </div>
    </div>

    {{-- Filters --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 space-y-4">
        <div class="flex flex-wrap gap-4 items-end">
            <div class="flex-1 min-w-40">
                <label class="block text-sm font-semibold text-gray-700 mb-1">Time Period (Days)</label>
                <select wire:model.live="period"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-400">
                    <option value="7">Last 7 Days</option>
                    <option value="30">Last 30 Days</option>
                    <option value="90">Last 90 Days</option>
                    <option value="365">Last 365 Days</option>
                </select>
            </div>
        </div>
    </div>

    {{-- Tabs --}}
    <div class="flex flex-wrap gap-2 border-b border-gray-200">
        <button wire:click="$set('activeTab', 'fast')"
                class="px-4 py-2 font-semibold text-sm transition {{ $activeTab === 'fast' ? 'text-teal-600 border-b-2 border-teal-600' : 'text-gray-500 hover:text-gray-700' }}">
            Fast Moving (≥10 sold)
        </button>
        <button wire:click="$set('activeTab', 'slow')"
                class="px-4 py-2 font-semibold text-sm transition {{ $activeTab === 'slow' ? 'text-orange-600 border-b-2 border-orange-600' : 'text-gray-500 hover:text-gray-700' }}">
            Slow Moving (<10 sold)
        </button>
    </div>

    {{-- Tab Content --}}
    @if($activeTab === 'fast')
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-5 py-3 border-b border-gray-100 bg-green-50">
                <h2 class="text-sm font-black uppercase tracking-wider text-green-800">Fast Moving Products</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-100 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left font-bold text-gray-600 uppercase tracking-wider text-xs">Product</th>
                            <th class="px-4 py-3 text-left font-bold text-gray-600 uppercase tracking-wider text-xs">Category</th>
                            <th class="px-4 py-3 text-right font-bold text-gray-600 uppercase tracking-wider text-xs">Total Sold</th>
                            <th class="px-4 py-3 text-right font-bold text-gray-600 uppercase tracking-wider text-xs">Total Revenue</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($fastMoving as $product)
                            <tr class="hover:bg-green-50 transition">
                                <td class="px-4 py-3">
                                    <p class="font-semibold text-gray-800">{{ $product->product->name ?? '—' }}</p>
                                    <p class="text-xs text-gray-400">{{ $product->product->sku ?? '' }}</p>
                                </td>
                                <td class="px-4 py-3 text-gray-600">{{ $product->product->category->name ?? '—' }}</td>
                                <td class="px-4 py-3 text-right font-semibold text-green-600">{{ number_format($product->total_sold) }}</td>
                                <td class="px-4 py-3 text-right font-semibold text-gray-800">₵{{ number_format($product->total_revenue, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-5 py-3 border-b border-gray-100 bg-orange-50">
                <h2 class="text-sm font-black uppercase tracking-wider text-orange-800">Slow Moving Products</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-100 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left font-bold text-gray-600 uppercase tracking-wider text-xs">Product</th>
                            <th class="px-4 py-3 text-left font-bold text-gray-600 uppercase tracking-wider text-xs">Category</th>
                            <th class="px-4 py-3 text-right font-bold text-gray-600 uppercase tracking-wider text-xs">Total Sold</th>
                            <th class="px-4 py-3 text-right font-bold text-gray-600 uppercase tracking-wider text-xs">Total Revenue</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($slowMoving as $product)
                            <tr class="hover:bg-orange-50 transition">
                                <td class="px-4 py-3">
                                    <p class="font-semibold text-gray-800">{{ $product->product->name ?? '—' }}</p>
                                    <p class="text-xs text-gray-400">{{ $product->product->sku ?? '' }}</p>
                                </td>
                                <td class="px-4 py-3 text-gray-600">{{ $product->product->category->name ?? '—' }}</td>
                                <td class="px-4 py-3 text-right font-semibold text-orange-600">{{ number_format($product->total_sold) }}</td>
                                <td class="px-4 py-3 text-right font-semibold text-gray-800">₵{{ number_format($product->total_revenue, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>
