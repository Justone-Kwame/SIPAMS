<div class="p-6 space-y-5">

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-black text-gray-800">Purchase Orders</h1>
            <p class="text-sm text-gray-500 mt-0.5">Manage your purchase orders and track deliveries.</p>
        </div>
        <a href="{{ route('purchases.create') }}" wire:navigate
           class="flex items-center gap-2 px-4 py-2.5 rounded-lg bg-teal-600 hover:bg-teal-700 text-white text-sm font-bold shadow transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            New Purchase Order
        </a>
    </div>

    @if (session()->has('success'))
        <div class="px-4 py-3 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm">{{ session('success') }}</div>
    @endif

    {{-- Filters --}}
    <div class="flex flex-wrap items-center gap-3">
        <div class="relative flex-1 min-w-48">
            <svg class="absolute left-3 top-2.5 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <input type="text" wire:model.live.debounce.300ms="search"
                placeholder="Search PO number or supplier…"
                class="w-full border border-gray-200 rounded-lg pl-9 pr-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-400 bg-white">
        </div>

        <select wire:model.live="supplierFilter"
            class="border border-gray-200 rounded-lg px-3 py-2 text-sm text-gray-700 bg-white focus:outline-none focus:ring-2 focus:ring-teal-400">
            <option value="">All Suppliers</option>
            @foreach($suppliers as $s)
                <option value="{{ $s->id }}">{{ $s->name }}</option>
            @endforeach
        </select>

        <select wire:model.live="statusFilter"
            class="border border-gray-200 rounded-lg px-3 py-2 text-sm text-gray-700 bg-white focus:outline-none focus:ring-2 focus:ring-teal-400">
            <option value="">All Status</option>
            <option value="draft">Draft</option>
            <option value="ordered">Ordered</option>
            <option value="delivered">Delivered</option>
            <option value="cancelled">Cancelled</option>
        </select>

        <span class="text-sm text-gray-400 ml-auto">{{ $purchaseOrders->total() }} PO{{ $purchaseOrders->total() !== 1 ? 's' : '' }}</span>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-100 text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left font-bold text-gray-600 uppercase tracking-wider text-xs">PO Number</th>
                        <th class="px-4 py-3 text-left font-bold text-gray-600 uppercase tracking-wider text-xs">Supplier</th>
                        <th class="px-4 py-3 text-left font-bold text-gray-600 uppercase tracking-wider text-xs">Order Date</th>
                        <th class="px-4 py-3 text-left font-bold text-gray-600 uppercase tracking-wider text-xs">Expected Delivery</th>
                        <th class="px-4 py-3 text-right font-bold text-gray-600 uppercase tracking-wider text-xs">Total Amount</th>
                        <th class="px-4 py-3 text-right font-bold text-gray-600 uppercase tracking-wider text-xs">Paid</th>
                        <th class="px-4 py-3 text-center font-bold text-gray-600 uppercase tracking-wider text-xs">Status</th>
                        <th class="px-4 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($purchaseOrders as $po)
                        @php
                            $statusColors = [
                                'draft' => ['bg' => '#f3f4f6', 'color' => '#6b7280'],
                                'ordered' => ['bg' => '#dbeafe', 'color' => '#2563eb'],
                                'delivered' => ['bg' => '#dcfce7', 'color' => '#16a34a'],
                                'cancelled' => ['bg' => '#fee2e2', 'color' => '#dc2626'],
                            ];
                            $status = $statusColors[$po->status] ?? $statusColors['draft'];
                        @endphp
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-4 py-3 font-semibold text-gray-800">{{ $po->po_number }}</td>
                            <td class="px-4 py-3 text-gray-600">{{ $po->supplier->name ?? '—' }}</td>
                            <td class="px-4 py-3 text-gray-600">{{ \Carbon\Carbon::parse($po->order_date)->format('M d, Y') }}</td>
                            <td class="px-4 py-3 text-gray-600">{{ $po->expected_delivery_date ? \Carbon\Carbon::parse($po->expected_delivery_date)->format('M d, Y') : '—' }}</td>
                            <td class="px-4 py-3 text-right font-semibold text-gray-800">₵{{ number_format($po->total_amount, 2) }}</td>
                            <td class="px-4 py-3 text-right text-gray-600">₵{{ number_format($po->paid_amount, 2) }}</td>
                            <td class="px-4 py-3 text-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold"
                                    style="background:{{ $status['bg'] }}; color:{{ $status['color'] }};">
                                    {{ ucfirst($po->status) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('purchases.edit', $po->id) }}" wire:navigate
                                       class="p-1.5 rounded-lg text-gray-400 hover:text-teal-600 hover:bg-teal-50 transition" title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </a>
                                    <button wire:click="confirmDelete({{ $po->id }})"
                                       class="p-1.5 rounded-lg text-gray-400 hover:text-red-600 hover:bg-red-50 transition" title="Delete">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-4 py-12 text-center text-gray-400">
                                <svg class="w-12 h-12 mx-auto mb-3 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                <p class="text-sm font-semibold">No purchase orders found</p>
                                <p class="text-xs mt-1">Try adjusting your filters or <a href="{{ route('purchases.create') }}" wire:navigate class="text-teal-600 hover:underline">create a new purchase order</a>.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($purchaseOrders->hasPages())
            <div class="px-4 py-3 border-t border-gray-100">
                {{ $purchaseOrders->links() }}
            </div>
        @endif
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
            <h3 class="text-lg font-black text-gray-800 text-center">Delete Purchase Order?</h3>
            <p class="text-sm text-gray-500 text-center mt-1 mb-5">This will permanently delete the purchase order and all its items. This cannot be undone.</p>
            <div class="flex gap-3">
                <button wire:click="cancelDelete" class="flex-1 py-2.5 rounded-lg border border-gray-300 text-sm font-semibold text-gray-700 hover:bg-gray-50 transition">Cancel</button>
                <button wire:click="delete" class="flex-1 py-2.5 rounded-lg bg-red-600 hover:bg-red-700 text-white text-sm font-bold shadow transition">Delete</button>
            </div>
        </div>
    </div>
    @endif
</div>
