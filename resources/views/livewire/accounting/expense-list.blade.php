<div class="p-6 space-y-6">

    {{-- ══ Header ══ --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-black text-gray-800">Expenses</h1>
            <p class="text-sm text-gray-500 mt-0.5">Track and manage all business expenditures.</p>
        </div>
        <a href="{{ route('expenses.create') }}" wire:navigate
           class="flex items-center gap-2 px-4 py-2.5 rounded-lg bg-orange-500 hover:bg-orange-600 text-white text-sm font-bold shadow transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Record Expense
        </a>
    </div>

    @if (session()->has('success'))
        <div class="px-4 py-3 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm">
            {{ session('success') }}
        </div>
    @endif

    {{-- ══ Summary Cards ══ --}}
    <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">

        @foreach([
            ['Daily Expenses',   $dailyTotal,   'Today',       '#fff7ed', '#ea580c', 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z'],
            ['Monthly Expenses', $monthlyTotal, 'This month',  '#fce4ec', '#e91e63', 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2'],
            ['Yearly Expenses',  $yearlyTotal,  now()->year,   '#e8f5e9', '#16a34a', 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z'],
            ['Period Total',     $periodTotal,  'Selected period', '#e0f7fa', '#0891b2', 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
        ] as [$label, $val, $sub, $bg, $color, $icon])
        <div class="rounded-xl p-4 border border-gray-100 bg-white shadow-sm flex items-center gap-3">
            <div class="w-11 h-11 rounded-xl flex items-center justify-center flex-shrink-0"
                 style="background:{{ $bg }};">
                <svg class="w-5 h-5" style="color:{{ $color }};" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $icon }}"/>
                </svg>
            </div>
            <div>
                <p class="text-xs text-gray-500 font-semibold uppercase tracking-wider">{{ $label }}</p>
                <p class="text-xl font-black text-gray-800">₵{{ number_format($val, 2) }}</p>
                <p class="text-xs text-gray-400">{{ $sub }}</p>
            </div>
        </div>
        @endforeach
    </div>

    {{-- ══ Charts Row ══ --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

        {{-- Monthly Chart --}}
        <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <h2 class="text-sm font-black text-gray-700 mb-4">Monthly Expenses — {{ now()->year }}</h2>
            <div id="chartMonthlyExpenses"></div>
        </div>

        {{-- By Category --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <h2 class="text-sm font-black text-gray-700 mb-4">By Category</h2>
            @if($byCategory->isEmpty())
                <p class="text-sm text-gray-400 text-center py-8">No data for this period</p>
            @else
                @php $catMax = $byCategory->max('total') ?: 1; @endphp
                <div class="space-y-3">
                    @foreach($byCategory as $row)
                        @php $pct = ($row->total / $catMax) * 100; @endphp
                        <div>
                            <div class="flex justify-between items-center mb-1">
                                <span class="text-xs font-semibold text-gray-700 truncate max-w-[60%]">
                                    {{ $row->category->name ?? 'Unknown' }}
                                </span>
                                <span class="text-xs font-black text-orange-500">₵{{ number_format($row->total, 2) }}</span>
                            </div>
                            <div class="h-2 rounded-full bg-gray-100 overflow-hidden">
                                <div class="h-full rounded-full" style="width:{{ $pct }}%; background:linear-gradient(90deg,#f97316,#fb923c);"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    {{-- ══ Filters ══ --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
        <div class="flex flex-wrap items-center gap-3">

            {{-- Search --}}
            <div class="relative flex-1 min-w-48">
                <svg class="absolute left-3 top-2.5 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text" wire:model.live.debounce.300ms="search"
                    placeholder="Search expense name or description…"
                    class="w-full border border-gray-200 rounded-lg pl-9 pr-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400 bg-white">
            </div>

            {{-- Category filter --}}
            <select wire:model.live="categoryFilter"
                class="border border-gray-200 rounded-lg px-3 py-2 text-sm text-gray-700 bg-white focus:outline-none focus:ring-2 focus:ring-orange-400">
                <option value="">All Categories</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                @endforeach
            </select>

            {{-- Period filter --}}
            <div class="flex rounded-lg border border-gray-200 overflow-hidden text-sm">
                @foreach(['today' => 'Today', 'week' => 'Week', 'month' => 'Month', 'year' => 'Year', 'custom' => 'Custom'] as $val => $label)
                    <button wire:click="$set('period', '{{ $val }}')"
                        class="px-3 py-2 font-semibold transition
                            {{ $period === $val ? 'bg-orange-500 text-white' : 'text-gray-600 hover:bg-gray-50' }}">
                        {{ $label }}
                    </button>
                @endforeach
            </div>

            {{-- Custom date range --}}
            @if($period === 'custom')
                <input type="date" wire:model.live="dateFrom"
                    class="border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400">
                <span class="text-gray-400 text-sm">to</span>
                <input type="date" wire:model.live="dateTo"
                    class="border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400">
            @endif

            <span class="text-sm text-gray-400 ml-auto">{{ $expenses->total() }} record{{ $expenses->total() !== 1 ? 's' : '' }}</span>
        </div>
    </div>

    {{-- ══ Table ══ --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-100 text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-5 py-3 text-left">
                            <button wire:click="sortBy('date')"
                                class="flex items-center gap-1 font-bold text-gray-600 hover:text-gray-900 uppercase tracking-wider text-xs">
                                Date & Time
                                @if($sortBy === 'date') <span class="text-orange-500">{{ $sortDir === 'asc' ? '↑' : '↓' }}</span> @endif
                            </button>
                        </th>
                        <th class="px-5 py-3 text-left font-bold text-gray-600 uppercase tracking-wider text-xs">Category</th>
                        <th class="px-5 py-3 text-left">
                            <button wire:click="sortBy('title')"
                                class="flex items-center gap-1 font-bold text-gray-600 hover:text-gray-900 uppercase tracking-wider text-xs">
                                Expense Name
                                @if($sortBy === 'title') <span class="text-orange-500">{{ $sortDir === 'asc' ? '↑' : '↓' }}</span> @endif
                            </button>
                        </th>
                        <th class="px-5 py-3 text-right">
                            <button wire:click="sortBy('amount')"
                                class="flex items-center gap-1 font-bold text-gray-600 hover:text-gray-900 uppercase tracking-wider text-xs ml-auto">
                                Amount
                                @if($sortBy === 'amount') <span class="text-orange-500">{{ $sortDir === 'asc' ? '↑' : '↓' }}</span> @endif
                            </button>
                        </th>
                        <th class="px-5 py-3 text-left font-bold text-gray-600 uppercase tracking-wider text-xs">Description</th>
                        <th class="px-5 py-3 text-left font-bold text-gray-600 uppercase tracking-wider text-xs">Recorded By</th>
                        <th class="px-5 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($expenses as $expense)
                        @php
                            $catColors = [
                                'Rent'          => ['#e3f2fd','#1976d2'],
                                'Electricity'   => ['#fff9c4','#f57f17'],
                                'Water'         => ['#e0f7fa','#0097a7'],
                                'Fuel'          => ['#fce4ec','#c2185b'],
                                'Salaries'      => ['#e8f5e9','#388e3c'],
                                'Repairs'       => ['#fff3e0','#f57c00'],
                                'Miscellaneous' => ['#f3e5f5','#7b1fa2'],
                            ];
                            $catName = $expense->category->name ?? 'Other';
                            [$cbg, $cfg] = $catColors[$catName] ?? ['#f1f5f9','#64748b'];
                        @endphp
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-5 py-3 whitespace-nowrap">
                                <p class="font-semibold text-gray-800">{{ \Carbon\Carbon::parse($expense->date)->format('d M Y') }}</p>
                                <p class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($expense->date)->format('H:i') }}</p>
                            </td>
                            <td class="px-5 py-3">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold"
                                    style="background:{{ $cbg }}; color:{{ $cfg }};">
                                    {{ $catName }}
                                </span>
                            </td>
                            <td class="px-5 py-3 font-semibold text-gray-800">{{ $expense->title }}</td>
                            <td class="px-5 py-3 text-right font-black text-orange-600">₵{{ number_format($expense->amount, 2) }}</td>
                            <td class="px-5 py-3 text-gray-500 max-w-xs truncate">{{ $expense->description ?: '—' }}</td>
                            <td class="px-5 py-3 text-gray-600">
                                <div class="flex items-center gap-1.5">
                                    <div class="w-6 h-6 rounded-full bg-gray-200 flex items-center justify-center text-xs font-bold text-gray-600 flex-shrink-0">
                                        {{ strtoupper(substr($expense->user->name ?? 'U', 0, 1)) }}
                                    </div>
                                    <span class="text-xs">{{ $expense->user->name ?? 'N/A' }}</span>
                                </div>
                            </td>
                            <td class="px-5 py-3 text-right">
                                <div class="flex items-center justify-end gap-1">
                                    <a href="{{ route('expenses.edit', $expense->id) }}" wire:navigate
                                       class="p-1.5 rounded-lg text-gray-400 hover:text-teal-600 hover:bg-teal-50 transition" title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </a>
                                    <button wire:click="confirmDelete({{ $expense->id }})"
                                       class="p-1.5 rounded-lg text-gray-400 hover:text-red-600 hover:bg-red-50 transition" title="Delete">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-12 text-center text-gray-400">
                                <svg class="w-12 h-12 mx-auto mb-3 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                                <p class="text-sm font-semibold">No expenses found</p>
                                <p class="text-xs mt-1">
                                    <a href="{{ route('expenses.create') }}" wire:navigate class="text-orange-500 hover:underline">Record your first expense</a>
                                </p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>

                {{-- Period total row --}}
                @if($expenses->count() > 0)
                <tfoot>
                    <tr class="bg-orange-50 border-t-2 border-orange-200">
                        <td colspan="3" class="px-5 py-3 text-sm font-black text-gray-700 uppercase tracking-wider">Period Total</td>
                        <td class="px-5 py-3 text-right text-lg font-black text-orange-600">₵{{ number_format($periodTotal, 2) }}</td>
                        <td colspan="3"></td>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>

        {{-- Pagination --}}
        @if($expenses->hasPages())
            <div class="px-5 py-3 border-t border-gray-100">
                {{ $expenses->links() }}
            </div>
        @endif
    </div>

    {{-- ══ Delete Modal ══ --}}
    @if($confirmDelete)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm mx-4 p-6">
            <div class="flex items-center justify-center w-12 h-12 rounded-full bg-red-100 mx-auto mb-4">
                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
            <h3 class="text-lg font-black text-gray-800 text-center">Delete Expense?</h3>
            <p class="text-sm text-gray-500 text-center mt-1 mb-5">This action cannot be undone.</p>
            <div class="flex gap-3">
                <button wire:click="cancelDelete"
                    class="flex-1 py-2.5 rounded-lg border border-gray-300 text-sm font-semibold text-gray-700 hover:bg-gray-50 transition">
                    Cancel
                </button>
                <button wire:click="delete"
                    class="flex-1 py-2.5 rounded-lg bg-red-600 hover:bg-red-700 text-white text-sm font-bold shadow transition">
                    Delete
                </button>
            </div>
        </div>
    </div>
    @endif

    {{-- ApexCharts --}}
    @script
    <script>
        function initExpenseChart() {
            const el = document.querySelector('#chartMonthlyExpenses');
            if (!el || el._chart) return;

            const chart = new ApexCharts(el, {
                chart: {
                    type: 'bar',
                    height: 200,
                    toolbar: { show: false },
                    fontFamily: 'Figtree, sans-serif',
                },
                series: [{ name: 'Expenses', data: @json($monthlyChart) }],
                colors: ['#f97316'],
                plotOptions: { bar: { columnWidth: '55%', borderRadius: 4 } },
                dataLabels: { enabled: false },
                xaxis: {
                    categories: ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'],
                    labels: { style: { fontSize: '11px', colors: '#9ca3af' } },
                },
                yaxis: {
                    labels: {
                        style: { fontSize: '11px', colors: '#9ca3af' },
                        formatter: v => '₵' + Number(v).toLocaleString(),
                    },
                },
                grid: { borderColor: '#f3f4f6' },
                tooltip: { theme: 'light', y: { formatter: v => '₵' + Number(v).toLocaleString() } },
            });
            chart.render();
            el._chart = chart;
        }
        initExpenseChart();
    </script>
    @endscript
</div>
