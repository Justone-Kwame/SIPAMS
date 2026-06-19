<div class="p-6 space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-black text-gray-800">Financial Reports</h1>
            <p class="text-sm text-gray-500 mt-0.5">Analyze revenue, expenses, and profitability.</p>
        </div>
    </div>

    {{-- Filters --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 space-y-4">
        <div class="flex flex-wrap gap-4 items-end">
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
        <div class="bg-gradient-to-br from-orange-500 to-orange-600 text-white rounded-xl shadow-sm p-5">
            <p class="text-sm font-semibold opacity-90">Total Cost of Goods</p>
            <p class="text-3xl font-black mt-1">₵{{ number_format($totalCost, 2) }}</p>
        </div>
        <div class="bg-gradient-to-br from-green-500 to-green-600 text-white rounded-xl shadow-sm p-5">
            <p class="text-sm font-semibold opacity-90">Gross Profit</p>
            <p class="text-3xl font-black mt-1">₵{{ number_format($grossProfit, 2) }}</p>
        </div>
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 text-white rounded-xl shadow-sm p-5">
            <p class="text-sm font-semibold opacity-90">Net Profit</p>
            <p class="text-3xl font-black mt-1">₵{{ number_format($netProfit, 2) }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Expenses Summary --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-5 py-3 border-b border-gray-100 bg-gray-50">
                <h2 class="text-sm font-black uppercase tracking-wider text-gray-600">Total Expenses</h2>
            </div>
            <div class="p-5">
                <p class="text-4xl font-black text-orange-600">₵{{ number_format($totalExpenses, 2) }}</p>
            </div>
        </div>

        {{-- Profit Margin --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-5 py-3 border-b border-gray-100 bg-gray-50">
                <h2 class="text-sm font-black uppercase tracking-wider text-gray-600">Profit Margin</h2>
            </div>
            <div class="p-5">
                <p class="text-4xl font-black {{ $netProfit >= 0 ? 'text-green-600' : 'text-red-600' }}">
                    {{ $totalRevenue > 0 ? number_format(($netProfit / $totalRevenue) * 100, 1) : 0 }}%
                </p>
            </div>
        </div>
    </div>

    {{-- Expenses Table --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-5 py-3 border-b border-gray-100 bg-gray-50">
            <h2 class="text-sm font-black uppercase tracking-wider text-gray-600">Expense Transactions</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-100 text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left font-bold text-gray-600 uppercase tracking-wider text-xs">Date</th>
                        <th class="px-4 py-3 text-left font-bold text-gray-600 uppercase tracking-wider text-xs">Category</th>
                        <th class="px-4 py-3 text-left font-bold text-gray-600 uppercase tracking-wider text-xs">Description</th>
                        <th class="px-4 py-3 text-right font-bold text-gray-600 uppercase tracking-wider text-xs">Amount</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($expenses as $expense)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-4 py-3 text-gray-600">{{ Carbon\Carbon::parse($expense->date)->format('M d, Y') }}</td>
                            <td class="px-4 py-3 text-gray-800 font-semibold">{{ $expense->category->name ?? '—' }}</td>
                            <td class="px-4 py-3 text-gray-600">{{ $expense->description }}</td>
                            <td class="px-4 py-3 text-right font-semibold text-orange-600">₵{{ number_format($expense->amount, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
