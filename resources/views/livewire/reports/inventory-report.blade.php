<div class="p-6 space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-black text-gray-800">Inventory Reports</h1>
            <p class="text-sm text-gray-500 mt-0.5">Analyze your inventory status and stock levels.</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('reports.inventory.export.csv') }}" class="px-4 py-2 bg-teal-600 hover:bg-teal-700 text-white rounded-lg text-sm font-semibold">
                Export CSV
            </a>
        </div>
    </div>

    {{-- Tabs --}}
    <div class="flex flex-wrap gap-2 border-b border-gray-200">
        <button wire:click="$set('activeTab', 'current')"
                class="px-4 py-2 font-semibold text-sm transition {{ $activeTab === 'current' ? 'text-teal-600 border-b-2 border-teal-600' : 'text-gray-500 hover:text-gray-700' }}">
            Current Stock
        </button>
        <button wire:click="$set('activeTab', 'low')"
                class="px-4 py-2 font-semibold text-sm transition {{ $activeTab === 'low' ? 'text-orange-600 border-b-2 border-orange-600' : 'text-gray-500 hover:text-gray-700' }}">
            Low Stock
        </button>
        <button wire:click="$set('activeTab', 'out')"
                class="px-4 py-2 font-semibold text-sm transition {{ $activeTab === 'out' ? 'text-red-600 border-b-2 border-red-600' : 'text-gray-500 hover:text-gray-700' }}">
            Out of Stock
        </button>
        <button wire:click="$set('activeTab', 'expiry')"
                class="px-4 py-2 font-semibold text-sm transition {{ $activeTab === 'expiry' ? 'text-purple-600 border-b-2 border-purple-600' : 'text-gray-500 hover:text-gray-700' }}">
            Expiry Report
        </button>
    </div>

    {{-- Tab Content --}}
    @if($activeTab === 'current')
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-5 py-3 border-b border-gray-100 bg-gray-50">
                <h2 class="text-sm font-black uppercase tracking-wider text-gray-600">Current Stock Levels</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-100 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left font-bold text-gray-600 uppercase tracking-wider text-xs">Product</th>
                            <th class="px-4 py-3 text-left font-bold text-gray-600 uppercase tracking-wider text-xs">Category</th>
                            <th class="px-4 py-3 text-right font-bold text-gray-600 uppercase tracking-wider text-xs">Stock</th>
                            <th class="px-4 py-3 text-right font-bold text-gray-600 uppercase tracking-wider text-xs">Unit</th>
                            <th class="px-4 py-3 text-right font-bold text-gray-600 uppercase tracking-wider text-xs">Cost Price</th>
                            <th class="px-4 py-3 text-right font-bold text-gray-600 uppercase tracking-wider text-xs">Inventory Value</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($currentStock as $product)
                            @php
                                $stock = $product->batches->sum('quantity_remaining');
                                $value = $stock * $product->cost_price;
                            @endphp
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-4 py-3">
                                    <p class="font-semibold text-gray-800">{{ $product->name }}</p>
                                    <p class="text-xs text-gray-400">{{ $product->sku }}</p>
                                </td>
                                <td class="px-4 py-3 text-gray-600">{{ $product->category->name ?? '—' }}</td>
                                <td class="px-4 py-3 text-right font-semibold text-gray-800">{{ number_format($stock) }}</td>
                                <td class="px-4 py-3 text-right text-gray-600">{{ $product->unit }}</td>
                                <td class="px-4 py-3 text-right text-gray-600">₵{{ number_format($product->cost_price, 2) }}</td>
                                <td class="px-4 py-3 text-right font-semibold text-blue-600">₵{{ number_format($value, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @elseif($activeTab === 'low')
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-5 py-3 border-b border-gray-100 bg-orange-50">
                <h2 class="text-sm font-black uppercase tracking-wider text-orange-800">Low Stock Products</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-100 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left font-bold text-gray-600 uppercase tracking-wider text-xs">Product</th>
                            <th class="px-4 py-3 text-left font-bold text-gray-600 uppercase tracking-wider text-xs">Category</th>
                            <th class="px-4 py-3 text-right font-bold text-gray-600 uppercase tracking-wider text-xs">Current Stock</th>
                            <th class="px-4 py-3 text-right font-bold text-gray-600 uppercase tracking-wider text-xs">Reorder Level</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($lowStock as $product)
                            @php
                                $stock = $product->batches->sum('quantity_remaining');
                            @endphp
                            <tr class="hover:bg-orange-50 transition">
                                <td class="px-4 py-3">
                                    <p class="font-semibold text-gray-800">{{ $product->name }}</p>
                                    <p class="text-xs text-gray-400">{{ $product->sku }}</p>
                                </td>
                                <td class="px-4 py-3 text-gray-600">{{ $product->category->name ?? '—' }}</td>
                                <td class="px-4 py-3 text-right font-semibold text-orange-600">{{ number_format($stock) }}</td>
                                <td class="px-4 py-3 text-right text-gray-600">{{ $product->reorder_level }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @elseif($activeTab === 'out')
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-5 py-3 border-b border-gray-100 bg-red-50">
                <h2 class="text-sm font-black uppercase tracking-wider text-red-800">Out of Stock Products</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-100 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left font-bold text-gray-600 uppercase tracking-wider text-xs">Product</th>
                            <th class="px-4 py-3 text-left font-bold text-gray-600 uppercase tracking-wider text-xs">Category</th>
                            <th class="px-4 py-3 text-right font-bold text-gray-600 uppercase tracking-wider text-xs">Reorder Level</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($outOfStock as $product)
                            <tr class="hover:bg-red-50 transition">
                                <td class="px-4 py-3">
                                    <p class="font-semibold text-gray-800">{{ $product->name }}</p>
                                    <p class="text-xs text-gray-400">{{ $product->sku }}</p>
                                </td>
                                <td class="px-4 py-3 text-gray-600">{{ $product->category->name ?? '—' }}</td>
                                <td class="px-4 py-3 text-right text-gray-600">{{ $product->reorder_level }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @elseif($activeTab === 'expiry')
        <div class="space-y-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-5 py-3 border-b border-gray-100 bg-orange-50">
                    <h2 class="text-sm font-black uppercase tracking-wider text-orange-800">Expiring Soon (Next 30 Days)</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-100 text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left font-bold text-gray-600 uppercase tracking-wider text-xs">Product</th>
                                <th class="px-4 py-3 text-left font-bold text-gray-600 uppercase tracking-wider text-xs">Batch Number</th>
                                <th class="px-4 py-3 text-right font-bold text-gray-600 uppercase tracking-wider text-xs">Qty Remaining</th>
                                <th class="px-4 py-3 text-right font-bold text-gray-600 uppercase tracking-wider text-xs">Expiry Date</th>
                                <th class="px-4 py-3 text-center font-bold text-gray-600 uppercase tracking-wider text-xs">Days Left</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach($expiringProducts as $batch)
                                <tr class="hover:bg-orange-50 transition">
                                    <td class="px-4 py-3">
                                        <p class="font-semibold text-gray-800">{{ $batch->product->name }}</p>
                                        <p class="text-xs text-gray-400">{{ $batch->product->sku }}</p>
                                    </td>
                                    <td class="px-4 py-3 text-gray-600">{{ $batch->batch_number }}</td>
                                    <td class="px-4 py-3 text-right text-gray-600">{{ number_format($batch->quantity_remaining) }}</td>
                                    <td class="px-4 py-3 text-right text-orange-600 font-semibold">{{ $batch->expiry_date->format('M d, Y') }}</td>
                                    <td class="px-4 py-3 text-center text-orange-600 font-bold">{{ $batch->expiry_date->diffInDays(now()) }} days</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-5 py-3 border-b border-gray-100 bg-red-50">
                    <h2 class="text-sm font-black uppercase tracking-wider text-red-800">Expired Products</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-100 text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left font-bold text-gray-600 uppercase tracking-wider text-xs">Product</th>
                                <th class="px-4 py-3 text-left font-bold text-gray-600 uppercase tracking-wider text-xs">Batch Number</th>
                                <th class="px-4 py-3 text-right font-bold text-gray-600 uppercase tracking-wider text-xs">Qty Remaining</th>
                                <th class="px-4 py-3 text-right font-bold text-gray-600 uppercase tracking-wider text-xs">Expiry Date</th>
                                <th class="px-4 py-3 text-center font-bold text-gray-600 uppercase tracking-wider text-xs">Days Ago</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach($expiredProducts as $batch)
                                <tr class="hover:bg-red-50 transition">
                                    <td class="px-4 py-3">
                                        <p class="font-semibold text-gray-800">{{ $batch->product->name }}</p>
                                        <p class="text-xs text-gray-400">{{ $batch->product->sku }}</p>
                                    </td>
                                    <td class="px-4 py-3 text-gray-600">{{ $batch->batch_number }}</td>
                                    <td class="px-4 py-3 text-right text-gray-600">{{ number_format($batch->quantity_remaining) }}</td>
                                    <td class="px-4 py-3 text-right text-red-600 font-semibold">{{ $batch->expiry_date->format('M d, Y') }}</td>
                                    <td class="px-4 py-3 text-center text-red-600 font-bold">{{ $batch->expiry_date->diffInDays(now()) }} days ago</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif
</div>
