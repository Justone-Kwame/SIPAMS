<div class="p-6 space-y-5" x-data="{ showFilters: false }">

    {{-- Header / breadcrumb --}}
    <div class="flex items-end gap-3">
        <h1 class="text-2xl font-black text-gray-800">All Purchases</h1>
        <span class="text-sm text-gray-400 pb-0.5">Purchases <span class="mx-1">|</span> All Purchases</span>
    </div>

    @if (session()->has('success'))
        <div class="px-4 py-3 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm">{{ session('success') }}</div>
    @endif

    {{-- Toolbar --}}
    <div class="flex flex-wrap items-center gap-3">
        <div class="relative w-full sm:w-72">
            <input type="text" wire:model.live.debounce.300ms="search"
                placeholder="Search this table"
                class="w-full border border-gray-200 rounded-md px-3 py-2 text-sm text-gray-600 focus:outline-none focus:ring-2 focus:ring-teal-400 bg-white">
        </div>

        <div class="flex items-center gap-2 ml-auto">
            <button type="button" @click="showFilters = !showFilters"
                class="flex items-center gap-2 px-3 py-2 rounded-md border border-gray-200 text-sm font-medium text-gray-600 hover:bg-gray-50 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L14 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 018 21v-7.586L3.293 6.707A1 1 0 013 6V4z"/>
                </svg>
                Filter
            </button>

            <a href="{{ route('purchases.export.pdf', ['search' => $search, 'supplierFilter' => $supplierFilter, 'statusFilter' => $statusFilter]) }}"
                class="flex items-center gap-2 px-3 py-2 rounded-md border border-gray-200 text-sm font-medium text-gray-600 hover:bg-gray-50 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                PDF
            </a>

            <a href="{{ route('purchases.export.csv', ['search' => $search, 'supplierFilter' => $supplierFilter, 'statusFilter' => $statusFilter]) }}"
                class="flex items-center gap-2 px-3 py-2 rounded-md border border-gray-200 text-sm font-medium text-gray-600 hover:bg-gray-50 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m4 11H5a2 2 0 01-2-2V5a2 2 0 012-2h14a2 2 0 012 2v14a2 2 0 01-2 2z"/>
                </svg>
                EXCEL
            </a>

            <a href="{{ route('purchases.create') }}" wire:navigate
                class="flex items-center gap-2 px-4 py-2 rounded-md bg-violet-600 hover:bg-violet-700 text-white text-sm font-semibold shadow-sm transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Create
            </a>
        </div>
    </div>

    {{-- Collapsible filter panel --}}
    <div x-show="showFilters" x-cloak x-transition
        class="flex flex-wrap items-center gap-3 bg-white border border-gray-100 rounded-md p-4 shadow-sm">
        <select wire:model.live="supplierFilter"
            class="border border-gray-200 rounded-md px-3 py-2 text-sm text-gray-700 bg-white focus:outline-none focus:ring-2 focus:ring-teal-400">
            <option value="">All Suppliers</option>
            @foreach($suppliers as $s)
                <option value="{{ $s->id }}">{{ $s->name }}</option>
            @endforeach
        </select>

        <select wire:model.live="statusFilter"
            class="border border-gray-200 rounded-md px-3 py-2 text-sm text-gray-700 bg-white focus:outline-none focus:ring-2 focus:ring-teal-400">
            <option value="">All Status</option>
            <option value="draft">Draft</option>
            <option value="ordered">Ordered</option>
            <option value="delivered">Delivered</option>
            <option value="cancelled">Cancelled</option>
        </select>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-md shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-100 text-sm">
                <thead>
                    <tr class="text-gray-500">
                        <th class="px-4 py-3 text-left w-10">
                            <input type="checkbox" wire:model.live="selectAll"
                                class="rounded border-gray-300 text-violet-600 focus:ring-violet-400">
                        </th>
                        <th class="px-4 py-3 text-left font-semibold">Action</th>
                        <th class="px-4 py-3 text-left font-semibold">Date</th>
                        <th class="px-4 py-3 text-left font-semibold">Reference</th>
                        <th class="px-4 py-3 text-left font-semibold">Supplier</th>
                        <th class="px-4 py-3 text-left font-semibold">Warehouse</th>
                        <th class="px-4 py-3 text-left font-semibold">Status</th>
                        <th class="px-4 py-3 text-left font-semibold">Total</th>
                        <th class="px-4 py-3 text-left font-semibold">Paid</th>
                        <th class="px-4 py-3 text-left font-semibold">Due</th>
                        <th class="px-4 py-3 text-left font-semibold">Payment Status</th>
                        <th class="px-4 py-3 text-left font-semibold">Documents</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($purchaseOrders as $po)
                        @php
                            $statusBadges = [
                                'draft' => 'bg-gray-50 text-gray-600 border-gray-200',
                                'ordered' => 'bg-orange-50 text-orange-600 border-orange-200',
                                'delivered' => 'bg-emerald-50 text-emerald-600 border-emerald-200',
                                'cancelled' => 'bg-red-50 text-red-600 border-red-200',
                            ];
                            $statusClass = $statusBadges[$po->status] ?? $statusBadges['draft'];

                            $due = (float) $po->total_amount - (float) $po->paid_amount;
                            if ((float) $po->total_amount > 0 && $due <= 0) {
                                $payLabel = 'Paid';
                                $payClass = 'bg-emerald-50 text-emerald-600 border-emerald-200';
                            } elseif ((float) $po->paid_amount <= 0) {
                                $payLabel = 'Unpaid';
                                $payClass = 'bg-amber-50 text-amber-600 border-amber-200';
                            } else {
                                $payLabel = 'Partial';
                                $payClass = 'bg-violet-50 text-violet-600 border-violet-200';
                            }
                        @endphp
                        <tr wire:key="po-{{ $po->id }}" class="hover:bg-gray-50 transition text-gray-700">
                            <td class="px-4 py-3">
                                <input type="checkbox" wire:model.live="selected" value="{{ $po->id }}"
                                    class="rounded border-gray-300 text-violet-600 focus:ring-violet-400">
                            </td>
                            <td class="px-4 py-3">
                                <div x-data="{ open: false }" class="relative">
                                    <button @click="open = !open" @click.outside="open = false"
                                        class="px-2 py-1 rounded text-gray-500 hover:bg-gray-100 transition" title="Actions">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM18 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                        </svg>
                                    </button>
                                    <div x-show="open" x-cloak x-transition
                                        class="absolute left-0 z-20 mt-1 w-36 rounded-md bg-white shadow-lg border border-gray-100 py-1">
                                        <a href="{{ route('purchases.edit', $po->id) }}" wire:navigate
                                            class="flex items-center gap-2 px-3 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                            Edit
                                        </a>
                                        <button wire:click="confirmDelete({{ $po->id }})" @click="open = false"
                                            class="flex w-full items-center gap-2 px-3 py-2 text-sm text-red-600 hover:bg-red-50">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                            Delete
                                        </button>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-gray-600">
                                {{ \Carbon\Carbon::parse($po->order_date)->format('Y-m-d') }}
                                <span class="text-gray-400">{{ \Carbon\Carbon::parse($po->created_at)->format('H:i') }}</span>
                            </td>
                            <td class="px-4 py-3">
                                <a href="{{ route('purchases.edit', $po->id) }}" wire:navigate
                                    class="text-blue-600 hover:underline font-medium">{{ $po->po_number }}</a>
                            </td>
                            <td class="px-4 py-3 text-gray-600">{{ $po->supplier->name ?? '—' }}</td>
                            <td class="px-4 py-3 text-gray-400">—</td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded border text-xs font-medium {{ $statusClass }}">
                                    {{ ucfirst($po->status) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-gray-700">₵ {{ number_format($po->total_amount, 2) }}</td>
                            <td class="px-4 py-3 whitespace-nowrap text-gray-700">₵ {{ number_format($po->paid_amount, 2) }}</td>
                            <td class="px-4 py-3 whitespace-nowrap text-gray-700">₵ {{ number_format(max($due, 0), 2) }}</td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded border text-xs font-medium {{ $payClass }}">
                                    {{ $payLabel }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-gray-400">—</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="12" class="px-4 py-12 text-center text-gray-400">
                                <svg class="w-12 h-12 mx-auto mb-3 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                <p class="text-sm font-semibold">No purchases found</p>
                                <p class="text-xs mt-1">Try adjusting your search or <a href="{{ route('purchases.create') }}" wire:navigate class="text-violet-600 hover:underline">create a new purchase</a>.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Footer --}}
        <div class="flex flex-wrap items-center justify-between gap-3 px-4 py-3 border-t border-gray-100 text-sm text-gray-500">
            <div class="flex items-center gap-2">
                <span>Rows per page:</span>
                <select wire:model.live="perPage"
                    class="border border-gray-200 rounded-md px-2 py-1 text-sm text-gray-700 bg-white focus:outline-none focus:ring-2 focus:ring-teal-400">
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
            </div>

            <div class="flex items-center gap-4">
                <span>
                    {{ $purchaseOrders->total() === 0 ? 0 : $purchaseOrders->firstItem() }} - {{ $purchaseOrders->lastItem() ?? 0 }} of {{ $purchaseOrders->total() }}
                </span>
                <div class="flex items-center gap-1">
                    <button wire:click="previousPage" @disabled($purchaseOrders->onFirstPage())
                        class="flex items-center gap-1 px-2 py-1 rounded hover:bg-gray-100 transition disabled:opacity-40 disabled:cursor-not-allowed">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                        prev
                    </button>
                    <button wire:click="nextPage" @disabled(!$purchaseOrders->hasMorePages())
                        class="flex items-center gap-1 px-2 py-1 rounded hover:bg-gray-100 transition disabled:opacity-40 disabled:cursor-not-allowed">
                        next
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Delete Confirmation Modal --}}
    @if($confirmDelete)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm mx-4 p-6">
            <div class="flex items-center justify-center w-12 h-12 rounded-full bg-red-100 mx-auto mb-4">
                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
            <h3 class="text-lg font-black text-gray-800 text-center">Delete Purchase?</h3>
            <p class="text-sm text-gray-500 text-center mt-1 mb-5">This will permanently delete the purchase and all its items. This cannot be undone.</p>
            <div class="flex gap-3">
                <button wire:click="cancelDelete" class="flex-1 py-2.5 rounded-lg border border-gray-300 text-sm font-semibold text-gray-700 hover:bg-gray-50 transition">Cancel</button>
                <button wire:click="delete" class="flex-1 py-2.5 rounded-lg bg-red-600 hover:bg-red-700 text-white text-sm font-bold shadow transition">Delete</button>
            </div>
        </div>
    </div>
    @endif
</div>
