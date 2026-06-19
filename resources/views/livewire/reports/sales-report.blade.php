<div class="p-6 space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-black text-gray-800">Sales Reports</h1>
            <p class="text-sm text-gray-500 mt-0.5">Analyze sales performance over time.</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('reports.sales.export.csv', ['start_date' => $startDate, 'end_date' => $endDate]) }}" class="px-4 py-2 bg-teal-600 hover:bg-teal-700 text-white rounded-lg text-sm font-semibold">
                Export CSV
            </a>
            <a href="{{ route('reports.sales.export.pdf', ['start_date' => $startDate, 'end_date' => $endDate]) }}" class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg text-sm font-semibold">
                Export PDF
            </a>
        </div>
    </div>

    {{-- Filters --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 space-y-4">
        <div class="flex flex-wrap gap-4 items-end">
            <div class="flex-1 min-w-40">
                <label class="block text-sm font-semibold text-gray-700 mb-1">Period</label>
                <select wire:model.live="period"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-400">
                    <option value="daily">Daily</option>
                    <option value="weekly">Weekly</option>
                    <option value="monthly">Monthly</option>
                    <option value="yearly">Yearly</option>
                </select>
            </div>
            <div class="flex-1 min-w-40">
                <label class="block text-sm font-semibold text-gray-700 mb-1">Start Date</label>
                <input type="date" wire:model.live="startDate"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-400">
            </div>
            <div class="flex-1 min-w-40">
                <label class="block text-sm font-semibold text-gray-700 mb-1">End Date</label>
                <input type="date" wire:model.live="endDate"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-400">
            </div>
        </div>
    </div>

    {{-- Summary Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-gradient-to-br from-teal-500 to-teal-600 text-white rounded-xl shadow-sm p-5">
            <p class="text-sm font-semibold opacity-90">Total Revenue</p>
            <p class="text-3xl font-black mt-1">₵{{ number_format($totalRevenue, 2) }}</p>
        </div>
        <div class="bg-gradient-to-br from-green-500 to-green-600 text-white rounded-xl shadow-sm p-5">
            <p class="text-sm font-semibold opacity-90">Total Profit</p>
            <p class="text-3xl font-black mt-1">₵{{ number_format($totalProfit, 2) }}</p>
        </div>
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 text-white rounded-xl shadow-sm p-5">
            <p class="text-sm font-semibold opacity-90">Items Sold</p>
            <p class="text-3xl font-black mt-1">{{ number_format($totalItemsSold) }}</p>
        </div>
        <div class="bg-gradient-to-br from-purple-500 to-purple-600 text-white rounded-xl shadow-sm p-5">
            <p class="text-sm font-semibold opacity-90">Transactions</p>
            <p class="text-3xl font-black mt-1">{{ number_format($totalTransactions) }}</p>
        </div>
    </div>

    {{-- Sales Table --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-5 py-3 border-b border-gray-100 bg-gray-50">
            <h2 class="text-sm font-black uppercase tracking-wider text-gray-600">Sales Transactions</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-100 text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left font-bold text-gray-600 uppercase tracking-wider text-xs">Receipt No</th>
                        <th class="px-4 py-3 text-left font-bold text-gray-600 uppercase tracking-wider text-xs">Date</th>
                        <th class="px-4 py-3 text-left font-bold text-gray-600 uppercase tracking-wider text-xs">Items</th>
                        <th class="px-4 py-3 text-right font-bold text-gray-600 uppercase tracking-wider text-xs">Total</th>
                        <th class="px-4 py-3 text-right font-bold text-gray-600 uppercase tracking-wider text-xs">Profit</th>
                        <th class="px-4 py-3 text-left font-bold text-gray-600 uppercase tracking-wider text-xs">Cashier</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($sales as $sale)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-4 py-3">
                                <p class="font-semibold text-gray-800">{{ $sale->receipt_no }}</p>
                            </td>
                            <td class="px-4 py-3 text-gray-600">{{ Carbon\Carbon::parse($sale->date)->format('M d, Y') }}</td>
                            <td class="px-4 py-3 text-gray-600">{{ $sale->items->count() }} items</td>
                            <td class="px-4 py-3 text-right font-semibold text-gray-800">₵{{ number_format($sale->net_amount, 2) }}</td>
                            <td class="px-4 py-3 text-right font-semibold text-green-600">₵{{ number_format($sale->items->sum('profit'), 2) }}</td>
                            <td class="px-4 py-3 text-gray-600">{{ $sale->user->name ?? '—' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
