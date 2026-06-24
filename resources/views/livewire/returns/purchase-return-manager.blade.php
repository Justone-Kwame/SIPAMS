<div class="p-6 space-y-6">
    {{-- Header --}}
    <div>
        <h1 class="text-2xl font-black text-gray-800">Purchase Returns</h1>
        <p class="text-sm text-gray-500 mt-0.5">Record goods returned to suppliers. Returned items are removed from stock and reduce net cash sent.</p>
    </div>

    @if (session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 text-sm rounded-lg px-4 py-3">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="bg-red-50 border border-red-200 text-red-700 text-sm rounded-lg px-4 py-3">{{ session('error') }}</div>
    @endif

    {{-- New return form --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 space-y-4">
        <h2 class="text-lg font-bold text-gray-800">New Purchase Return</h2>

        <div class="flex flex-wrap gap-4 items-end">
            <div class="flex-1 min-w-64">
                <label class="block text-sm font-semibold text-gray-700 mb-1">Purchase Order</label>
                <select wire:model.live="purchaseOrderId"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-400">
                    <option value="">— Select a purchase order —</option>
                    @foreach ($purchaseOrders as $po)
                        <option value="{{ $po->id }}">
                            {{ $po->po_number }} — {{ $po->supplier?->name ?? 'Supplier' }} — ₵{{ number_format($po->total_amount, 2) }} ({{ \Illuminate\Support\Carbon::parse($po->order_date)->format('Y-m-d') }})
                        </option>
                    @endforeach
                </select>
                @error('purchaseOrderId') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
            </div>
        </div>

        @if (!empty($lines))
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left text-gray-500 border-b border-gray-200">
                            <th class="py-2 pr-4">Product</th>
                            <th class="py-2 pr-4 text-right">Received Qty</th>
                            <th class="py-2 pr-4 text-right">Unit Cost</th>
                            <th class="py-2 pr-4 text-right">Return Qty</th>
                            <th class="py-2 text-right">Line Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($lines as $index => $line)
                            <tr class="border-b border-gray-100">
                                <td class="py-2 pr-4 text-gray-800">{{ $line['name'] }}</td>
                                <td class="py-2 pr-4 text-right text-gray-600">{{ $line['received_qty'] }}</td>
                                <td class="py-2 pr-4 text-right text-gray-600">₵{{ number_format($line['unit_cost'], 2) }}</td>
                                <td class="py-2 pr-4 text-right">
                                    <input type="number" min="0" max="{{ $line['received_qty'] }}"
                                           wire:model.live="lines.{{ $index }}.return_qty"
                                           class="w-24 border border-gray-300 rounded-lg px-2 py-1 text-sm text-right focus:outline-none focus:ring-2 focus:ring-teal-400">
                                </td>
                                <td class="py-2 text-right text-gray-800 font-medium">
                                    ₵{{ number_format(min((int) $line['return_qty'], (int) $line['received_qty']) * $line['unit_cost'], 2) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @error('lines') <span class="text-xs text-red-600">{{ $message }}</span> @enderror

            <div class="flex flex-wrap gap-4 items-end">
                <div class="flex-1 min-w-64">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Reason (optional)</label>
                    <input type="text" wire:model="reason" placeholder="e.g. Defective batch"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-400">
                </div>
                <div class="text-right">
                    <p class="text-sm text-gray-500">Return Total</p>
                    <p class="text-xl font-black text-gray-800">₵{{ number_format($this->returnTotal, 2) }}</p>
                </div>
                <button wire:click="save" wire:loading.attr="disabled"
                        class="bg-teal-600 hover:bg-teal-700 text-white text-sm font-semibold px-5 py-2.5 rounded-lg shadow-sm">
                    Record Return
                </button>
            </div>
        @endif
    </div>

    {{-- Recent returns --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
        <h2 class="text-lg font-bold text-gray-800 mb-3">Recent Purchase Returns</h2>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-gray-500 border-b border-gray-200">
                        <th class="py-2 pr-4">Return No</th>
                        <th class="py-2 pr-4">Purchase Order</th>
                        <th class="py-2 pr-4">Supplier</th>
                        <th class="py-2 pr-4">Date</th>
                        <th class="py-2 text-right">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($returns as $return)
                        <tr class="border-b border-gray-100">
                            <td class="py-2 pr-4 font-medium text-gray-800">{{ $return->return_no }}</td>
                            <td class="py-2 pr-4 text-gray-600">{{ $return->purchaseOrder?->po_number ?? '—' }}</td>
                            <td class="py-2 pr-4 text-gray-600">{{ $return->supplier?->name ?? '—' }}</td>
                            <td class="py-2 pr-4 text-gray-600">{{ \Illuminate\Support\Carbon::parse($return->date)->format('Y-m-d') }}</td>
                            <td class="py-2 text-right text-gray-800 font-medium">₵{{ number_format($return->total_amount, 2) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="py-4 text-center text-gray-400">No purchase returns recorded yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3">{{ $returns->links() }}</div>
    </div>
</div>
