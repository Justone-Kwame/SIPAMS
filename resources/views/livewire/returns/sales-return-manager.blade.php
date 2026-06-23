<div class="p-6 space-y-6">
    {{-- Header --}}
    <div>
        <h1 class="text-2xl font-black text-gray-800">Sales Returns</h1>
        <p class="text-sm text-gray-500 mt-0.5">Record goods returned by customers. Returned items are added back to stock and reduce revenue.</p>
    </div>

    @if (session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 text-sm rounded-lg px-4 py-3">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="bg-red-50 border border-red-200 text-red-700 text-sm rounded-lg px-4 py-3">{{ session('error') }}</div>
    @endif

    {{-- New return form --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 space-y-4">
        <h2 class="text-lg font-bold text-gray-800">New Sales Return</h2>

        <div class="flex flex-wrap gap-4 items-end">
            <div class="flex-1 min-w-64">
                <label class="block text-sm font-semibold text-gray-700 mb-1">Original Sale</label>
                <select wire:model.live="saleId"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-400">
                    <option value="">— Select a completed sale —</option>
                    @foreach ($sales as $sale)
                        <option value="{{ $sale->id }}">
                            {{ $sale->receipt_no }} — ₵{{ number_format($sale->net_amount, 2) }} ({{ \Illuminate\Support\Carbon::parse($sale->date)->format('Y-m-d') }})
                        </option>
                    @endforeach
                </select>
                @error('saleId') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
            </div>
        </div>

        @if (!empty($lines))
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left text-gray-500 border-b border-gray-200">
                            <th class="py-2 pr-4">Product</th>
                            <th class="py-2 pr-4 text-right">Sold Qty</th>
                            <th class="py-2 pr-4 text-right">Unit Price</th>
                            <th class="py-2 pr-4 text-right">Return Qty</th>
                            <th class="py-2 text-right">Line Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($lines as $index => $line)
                            <tr class="border-b border-gray-100">
                                <td class="py-2 pr-4 text-gray-800">{{ $line['name'] }}</td>
                                <td class="py-2 pr-4 text-right text-gray-600">{{ $line['sold_qty'] }}</td>
                                <td class="py-2 pr-4 text-right text-gray-600">₵{{ number_format($line['unit_price'], 2) }}</td>
                                <td class="py-2 pr-4 text-right">
                                    <input type="number" min="0" max="{{ $line['sold_qty'] }}"
                                           wire:model.live="lines.{{ $index }}.return_qty"
                                           class="w-24 border border-gray-300 rounded-lg px-2 py-1 text-sm text-right focus:outline-none focus:ring-2 focus:ring-teal-400">
                                </td>
                                <td class="py-2 text-right text-gray-800 font-medium">
                                    ₵{{ number_format(min((int) $line['return_qty'], (int) $line['sold_qty']) * $line['unit_price'], 2) }}
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
                    <input type="text" wire:model="reason" placeholder="e.g. Damaged item"
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
        <h2 class="text-lg font-bold text-gray-800 mb-3">Recent Sales Returns</h2>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-gray-500 border-b border-gray-200">
                        <th class="py-2 pr-4">Return No</th>
                        <th class="py-2 pr-4">Original Sale</th>
                        <th class="py-2 pr-4">Date</th>
                        <th class="py-2 pr-4">Reason</th>
                        <th class="py-2 text-right">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($returns as $return)
                        <tr class="border-b border-gray-100">
                            <td class="py-2 pr-4 font-medium text-gray-800">{{ $return->return_no }}</td>
                            <td class="py-2 pr-4 text-gray-600">{{ $return->sale?->receipt_no ?? '—' }}</td>
                            <td class="py-2 pr-4 text-gray-600">{{ \Illuminate\Support\Carbon::parse($return->date)->format('Y-m-d') }}</td>
                            <td class="py-2 pr-4 text-gray-600">{{ $return->reason ?? '—' }}</td>
                            <td class="py-2 text-right text-gray-800 font-medium">₵{{ number_format($return->total_amount, 2) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="py-4 text-center text-gray-400">No sales returns recorded yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3">{{ $returns->links() }}</div>
    </div>
</div>
