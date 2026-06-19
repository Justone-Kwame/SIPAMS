<div class="min-h-full bg-gradient-to-br from-gray-50 via-white to-gray-50 p-8">

    {{-- ══════════ PAGE HEADER & GREETING ══════════ --}}
    <div class="flex items-center justify-between mb-8">
        <div>
            <p class="text-gray-500 text-lg font-medium">Good {{ now()->format('A') === 'AM' ? 'Morning' : 'Afternoon' }}, <span class="text-blue-600 font-bold">Admin {{ auth()->user()->name ?? 'POS' }}</span></p>
        </div>
        <div class="flex items-center gap-3">
            <div class="flex items-center gap-2 bg-white px-4 py-2.5 rounded-lg border border-gray-200 shadow-sm">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <select wire:model.live="selectedYear" class="text-sm font-bold text-gray-700 bg-transparent focus:outline-none">
                    @foreach($years as $y)
                        <option value="{{ $y }}">{{ \Carbon\Carbon::create($y)->format('M Y') }} - {{ \Carbon\Carbon::create($y)->addYear()->format('M Y') }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    {{-- ══════════ PRIMARY STAT CARDS (4 Large) ══════════ --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        
        {{-- Sales Card --}}
        <div class="rounded-2xl p-6 text-white shadow-lg overflow-hidden group hover:shadow-xl transition-all" 
             style="background: linear-gradient(135deg, #14b8a6 0%, #0891b2 100%);">
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <p class="text-sm font-semibold opacity-90 mb-1">Sales</p>
                    <p class="text-4xl font-black">₵ {{ number_format($dailySales, 0) }}K</p>
                    <p class="text-xs opacity-75 mt-2">{{ \Carbon\Carbon::now()->daysInMonth }} days in month</p>
                </div>
                <div class="flex-shrink-0 opacity-30">
                    <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>
                </div>
            </div>
        </div>

        {{-- Purchases Card --}}
        <div class="rounded-2xl p-6 text-white shadow-lg overflow-hidden group hover:shadow-xl transition-all"
             style="background: linear-gradient(135deg, #a855f7 0%, #9333ea 100%);">
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <p class="text-sm font-semibold opacity-90 mb-1">Purchases</p>
                    <p class="text-4xl font-black">₵ {{ number_format($dailyPurchases, 0) }}K</p>
                    <p class="text-xs opacity-75 mt-2">{{ \Carbon\Carbon::now()->daysInMonth }} days in month</p>
                </div>
                <div class="flex-shrink-0 opacity-30">
                    <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>
                </div>
            </div>
        </div>

        {{-- Sales Returns Card --}}
        <div class="rounded-2xl p-6 text-white shadow-lg overflow-hidden group hover:shadow-xl transition-all"
             style="background: linear-gradient(135deg, #f97316 0%, #fb923c 100%);">
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <p class="text-sm font-semibold opacity-90 mb-1">Sales Returns</p>
                    <p class="text-4xl font-black">₵ {{ number_format($dailySalesReturns, 0) }},5</p>
                    <p class="text-xs opacity-75 mt-2">{{ \Carbon\Carbon::now()->daysInMonth }} days in month</p>
                </div>
                <div class="flex-shrink-0 opacity-30">
                    <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                    </svg>
                </div>
            </div>
        </div>

        {{-- Purchases Returns Card --}}
        <div class="rounded-2xl p-6 text-white shadow-lg overflow-hidden group hover:shadow-xl transition-all"
             style="background: linear-gradient(135deg, #06b6d4 0%, #0ea5e9 100%);">
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <p class="text-sm font-semibold opacity-90 mb-1">Purchases Returns</p>
                    <p class="text-4xl font-black">₵ 0,0</p>
                    <p class="text-xs opacity-75 mt-2">{{ \Carbon\Carbon::now()->daysInMonth }} days in month</p>
                </div>
                <div class="flex-shrink-0 opacity-30">
                    <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    {{-- ══════════ SECONDARY STAT CARDS (4 Small) ══════════ --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        
        {{-- Today Total Sales --}}
        <div class="rounded-xl p-5 text-white shadow-md overflow-hidden hover:shadow-lg transition-all"
             style="background: linear-gradient(135deg, #ec4899 0%, #f59e0b 100%);">
            <p class="text-sm font-semibold opacity-90">Today Total Sales</p>
            <p class="text-3xl font-black mt-1">₵ {{ number_format($dailySales, 0) }},0</p>
            <div class="flex justify-end mt-3 opacity-30">
                <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                </svg>
            </div>
        </div>

        {{-- Today Total Received --}}
        <div class="rounded-xl p-5 text-white shadow-md overflow-hidden hover:shadow-lg transition-all"
             style="background: linear-gradient(135deg, #06b6d4 0%, #10b981 100%);">
            <p class="text-sm font-semibold opacity-90">Today Total Received(Sales)</p>
            <p class="text-3xl font-black mt-1">₵ {{ number_format($dailyGross, 0) }},0</p>
            <div class="flex justify-end mt-3 opacity-30">
                <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                </svg>
            </div>
        </div>

        {{-- Today Total Purchases --}}
        <div class="rounded-xl p-5 text-white shadow-md overflow-hidden hover:shadow-lg transition-all"
             style="background: linear-gradient(135deg, #7c3aed 0%, #ec4899 100%);">
            <p class="text-sm font-semibold opacity-90">Today Total Purchases</p>
            <p class="text-3xl font-black mt-1">₵ {{ number_format($dailyPurchases, 0) }},0</p>
            <div class="flex justify-end mt-3 opacity-30">
                <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                </svg>
            </div>
        </div>

        {{-- Today Total Expense --}}
        <div class="rounded-xl p-5 text-white shadow-md overflow-hidden hover:shadow-lg transition-all"
             style="background: linear-gradient(135deg, #06b6d4 0%, #a855f7 100%);">
            <p class="text-sm font-semibold opacity-90">Today Total Expense</p>
            <p class="text-3xl font-black mt-1">₵ {{ number_format($dailyExpenses, 0) }},0</p>
            <div class="flex justify-end mt-3 opacity-30">
                <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v2h16V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a2 2 0 002 2h8a2 2 0 002-2H6z" clip-rule="evenodd"/>
                </svg>
            </div>
        </div>
    </div>

    {{-- ══════════ CHARTS SECTION ══════════ --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        {{-- Weekly Sales & Purchases Chart --}}
        <div class="rounded-2xl shadow-lg bg-white p-6 border border-gray-100">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-black text-gray-800">This Week Sales & Purchases</h3>
                <div class="flex gap-4 text-sm">
                    <div class="flex items-center gap-2">
                        <div class="w-3 h-3 rounded-sm" style="background: #93c5fd;"></div>
                        <span class="text-gray-600">Sales</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-3 h-3 rounded-sm" style="background: #bfdbfe;"></div>
                        <span class="text-gray-600">Purchases</span>
                    </div>
                </div>
            </div>
            <div id="chartWeeklySalesAndPurchases"></div>
        </div>

        {{-- Top Selling Products Chart --}}
        <div class="rounded-2xl shadow-lg bg-white p-6 border border-gray-100">
            <h3 class="text-lg font-black text-gray-800 mb-6">Top Selling Products ({{ $year }})</h3>
            <div id="chartTopSellingProducts"></div>
        </div>
    </div>

    {{-- ══════════ PRODUCTS & CUSTOMERS SECTION ══════════ --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-8">
        
        {{-- Top Selling Products Table --}}
        <div class="rounded-2xl shadow-lg bg-white p-6 border border-gray-100">
            <h3 class="text-lg font-black text-gray-800 mb-6">Top Selling Products (June)</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b-2 border-gray-200">
                            <th class="text-left px-4 py-3 font-bold text-gray-500 uppercase text-xs">Product</th>
                            <th class="text-right px-4 py-3 font-bold text-gray-500 uppercase text-xs">Quantity</th>
                            <th class="text-right px-4 py-3 font-bold text-gray-500 uppercase text-xs">Grand Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($topSellingProducts as $item)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-4 py-4 font-semibold text-gray-700">{{ $item->product->name ?? '—' }}</td>
                                <td class="text-right px-4 py-4">
                                    <span class="inline-block bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-sm font-bold">
                                        {{ number_format($item->total_qty) }} kg
                                    </span>
                                </td>
                                <td class="text-right px-4 py-4 font-bold text-gray-800">₵ {{ number_format($item->grand_total, 1) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-4 py-8 text-center text-gray-400">No sales data this month</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Top 5 Customers Pie Chart --}}
        <div class="rounded-2xl shadow-lg bg-white p-6 border border-gray-100">
            <h3 class="text-lg font-black text-gray-800 mb-6">Top 5 Customers (June)</h3>
            <div id="chartTopCustomers"></div>
        </div>
    </div>

    {{-- ══════════ RECENT SALES TABLE ══════════ --}}
    <div class="rounded-2xl shadow-lg bg-white p-6 border border-gray-100 mt-8">
        <h3 class="text-lg font-black text-gray-800 mb-6">Recent Sales</h3>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b-2 border-gray-200">
                        <th class="text-left px-4 py-3 font-bold text-gray-500 uppercase text-xs">Reference</th>
                        <th class="text-left px-4 py-3 font-bold text-gray-500 uppercase text-xs">Customer</th>
                        <th class="text-center px-4 py-3 font-bold text-gray-500 uppercase text-xs">Status</th>
                        <th class="text-right px-4 py-3 font-bold text-gray-500 uppercase text-xs">Grand Total</th>
                        <th class="text-right px-4 py-3 font-bold text-gray-500 uppercase text-xs">Paid</th>
                        <th class="text-right px-4 py-3 font-bold text-gray-500 uppercase text-xs">Due</th>
                        <th class="text-center px-4 py-3 font-bold text-gray-500 uppercase text-xs">Payment Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($recentSales as $sale)
                        @php
                            $due = $sale->total_amount - $sale->paid_amount;
                            $paymentStatus = $due <= 0 ? 'Paid' : ($sale->paid_amount > 0 ? 'Partial' : 'Unpaid');
                            $statusColor = match($paymentStatus) {
                                'Paid' => 'bg-green-100 text-green-700',
                                'Partial' => 'bg-yellow-100 text-yellow-700',
                                'Unpaid' => 'bg-red-100 text-red-700',
                            };
                        @endphp
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-4 py-4 font-bold text-blue-600">{{ $sale->reference ?? '—' }}</td>
                            <td class="px-4 py-4 text-gray-700">{{ $sale->customer->name ?? 'Walk-in' }}</td>
                            <td class="px-4 py-4 text-center">
                                <span class="inline-block bg-teal-100 text-teal-700 px-3 py-1 rounded-full text-xs font-bold">
                                    Completed
                                </span>
                            </td>
                            <td class="px-4 py-4 text-right font-bold text-gray-800">₵ {{ number_format($sale->total_amount, 1) }}</td>
                            <td class="px-4 py-4 text-right font-bold text-gray-800">₵ {{ number_format($sale->paid_amount, 1) }}</td>
                            <td class="px-4 py-4 text-right font-bold text-gray-800">₵ {{ number_format($due, 1) }}</td>
                            <td class="px-4 py-4 text-center">
                                <span class="inline-block px-3 py-1 rounded-full text-xs font-bold {{ $statusColor }}">
                                    {{ $paymentStatus }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-8 text-center text-gray-400">No recent sales</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- ══════════ APEX CHARTS ══════════ --}}
    <script src="https://cdn.jsdelivr.net/npm/apexcharts@3.49.0/dist/apexcharts.min.js"></script>
    <script>
        const months = @json($monthLabels);
        const monthlySalesData = @json($monthlySalesData);
        const monthlyProfitData = @json($monthlyProfitData);

        // Weekly Sales & Purchases Chart
        new ApexCharts(document.querySelector('#chartWeeklySalesAndPurchases'), {
            chart: { 
                type: 'bar',
                height: 320,
                toolbar: { show: false },
                fontFamily: 'Figtree, sans-serif'
            },
            series: [
                { 
                    name: 'Sales', 
                    data: monthlySalesData.slice(-4)
                },
                { 
                    name: 'Purchases', 
                    data: monthlySalesData.slice(-4).map(v => v * 0.6)
                }
            ],
            colors: ['#93c5fd', '#bfdbfe'],
            xaxis: {
                categories: ['Mon', 'Tue', 'Wed', 'Thu'],
                labels: { style: { fontSize: '12px', colors: '#6b7280' } }
            },
            yaxis: {
                labels: { 
                    style: { fontSize: '12px', colors: '#6b7280' },
                    formatter: v => '₵' + Number(v).toLocaleString()
                }
            },
            plotOptions: {
                bar: {
                    columnWidth: '60%',
                    borderRadius: 6,
                    dataLabels: { position: 'top' }
                }
            },
            grid: { borderColor: '#e5e7eb', strokeDashArray: 5 },
            dataLabels: { enabled: false },
            tooltip: { theme: 'light' }
        }).render();

        // Top Selling Products Chart
        const topProducts = [
            { name: 'Apple', value: 35 },
            { name: 'Banana', value: 28 },
            { name: 'Tomato', value: 20 },
            { name: 'Potato', value: 12 },
            { name: 'Orange', value: 5 }
        ];

        new ApexCharts(document.querySelector('#chartTopSellingProducts'), {
            chart: { 
                type: 'pie',
                height: 320,
                fontFamily: 'Figtree, sans-serif'
            },
            series: topProducts.map(p => p.value),
            labels: topProducts.map(p => p.name),
            colors: ['#3b82f6', '#22c55e', '#fbbf24', '#f87171', '#f97316'],
            plotOptions: {
                pie: {
                    dataLabels: { offset: -25 }
                }
            },
            dataLabels: {
                formatter: (val) => val.toFixed(1) + '%',
                style: { fontSize: '13px', fontWeight: 600 }
            },
            legend: {
                position: 'right',
                fontSize: '13px',
                fontFamily: 'Figtree, sans-serif'
            },
            tooltip: { theme: 'light' }
        }).render();

        // Top Customers Pie Chart
        const topCustomersData = @json($topCustomers->map(fn($c) => ['name' => $c->customer->name ?? 'Walk-in', 'value' => (float) $c->total_spent])->values());
        
        if (topCustomersData.length > 0) {
            new ApexCharts(document.querySelector('#chartTopCustomers'), {
                chart: { 
                    type: 'pie',
                    height: 320,
                    fontFamily: 'Figtree, sans-serif'
                },
                series: topCustomersData.map(c => c.value),
                labels: topCustomersData.map(c => c.name),
                colors: ['#3b82f6', '#22c55e', '#fbbf24', '#f87171', '#f97316'],
                plotOptions: {
                    pie: {
                        dataLabels: { offset: -25 }
                    }
                },
                dataLabels: {
                    formatter: (val) => val.toFixed(1) + '%',
                    style: { fontSize: '13px', fontWeight: 600 }
                },
                legend: {
                    position: 'right',
                    fontSize: '12px',
                    fontFamily: 'Figtree, sans-serif'
                },
                tooltip: { theme: 'light' }
            }).render();
        }
    </script>
</div>
