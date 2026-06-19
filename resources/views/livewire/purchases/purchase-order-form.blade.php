<div class="p-8 bg-white min-h-screen">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-black text-gray-800">
                {{ $purchaseOrderId ? 'Edit Purchase Order' : 'Create Purchase Order' }}
            </h1>
            <p class="text-sm text-gray-500 mt-1">{{ now()->format('l, F j, Y') }}</p>
        </div>
        <a href="{{ route('purchases.index') }}" wire:navigate
           class="flex items-center gap-2 px-4 py-2 bg-gray-100 hover:bg-gray-200 rounded-lg transition text-gray-700 font-semibold">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Back
        </a>
    </div>

    @if (session()->has('success'))
        <div class="mb-6 px-4 py-3 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm font-semibold">{{ session('success') }}</div>
    @endif

    <form wire:submit.prevent="save" class="space-y-6">

        {{-- ═══════════════════ TOP FIELDS ═══════════════════ --}}
        <div class="grid grid-cols-3 gap-6">
            {{-- Date --}}
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Date <span class="text-red-500">*</span></label>
                <input type="date" wire:model="order_date"
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400 text-sm">
                @error('order_date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Supplier --}}
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Supplier <span class="text-red-500">*</span></label>
                <select wire:model="supplier_id"
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400 text-sm">
                    <option value="">Choose Supplier</option>
                    @foreach($suppliers as $s)
                        <option value="{{ $s->id }}">{{ $s->name }}</option>
                    @endforeach
                </select>
                @error('supplier_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        {{-- ═══════════════════ PRODUCT SEARCH ═══════════════════ --}}
        <div>
            <label class="block text-sm font-bold text-gray-700 mb-2">Product</label>
            <div class="relative">
                <svg class="absolute left-4 top-3 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text" placeholder="Search Product by Code Name"
                    class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400 text-sm">
            </div>
        </div>

        {{-- ═══════════════════ ORDER ITEMS TABLE ═══════════════════ --}}
        <div class="overflow-x-auto border border-gray-200 rounded-lg">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-4 py-3 text-left font-bold text-gray-600 text-xs uppercase">Product</th>
                        <th class="px-4 py-3 text-right font-bold text-gray-600 text-xs uppercase">Net Unit Cost</th>
                        <th class="px-4 py-3 text-right font-bold text-gray-600 text-xs uppercase">Stock</th>
                        <th class="px-4 py-3 text-center font-bold text-gray-600 text-xs uppercase">Qty</th>
                        <th class="px-4 py-3 text-right font-bold text-gray-600 text-xs uppercase">Discount</th>
                        <th class="px-4 py-3 text-right font-bold text-gray-600 text-xs uppercase">Tax</th>
                        <th class="px-4 py-3 text-right font-bold text-gray-600 text-xs uppercase">Subtotal</th>
                        <th class="px-4 py-3 text-center font-bold text-gray-600 text-xs uppercase">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @if(count($items) === 0)
                        <tr>
                            <td colspan="8" class="px-4 py-8 text-center text-gray-400">No Data Available</td>
                        </tr>
                    @else
                        @foreach($items as $index => $item)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3">
                                    <select wire:model.live="items.{{ $index }}.product_id"
                                        class="w-full px-2 py-1.5 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                                        <option value="">— Select —</option>
                                        @foreach($products as $p)
                                            <option value="{{ $p->id }}">{{ $p->name }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <div class="relative flex items-center">
                                        <span class="absolute left-2 text-gray-500 text-xs">$</span>
                                        <input type="number" step="0.01" min="0" wire:model.live="items.{{ $index }}.unit_cost"
                                            class="w-full pl-6 pr-2 py-1.5 border border-gray-300 rounded text-sm text-right focus:outline-none focus:ring-2 focus:ring-blue-400">
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-right text-gray-600 font-semibold">0</td>
                                <td class="px-4 py-3">
                                    <input type="number" min="1" wire:model.live="items.{{ $index }}.quantity"
                                        class="w-full px-2 py-1.5 border border-gray-300 rounded text-sm text-center focus:outline-none focus:ring-2 focus:ring-blue-400">
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <div class="relative flex items-center">
                                        <span class="absolute right-2 text-gray-500 text-xs">$</span>
                                        <input type="number" step="0.01" min="0" wire:model.live="items.{{ $index }}.discount"
                                            class="w-full px-2 pr-5 py-1.5 border border-gray-300 rounded text-sm text-right focus:outline-none focus:ring-2 focus:ring-blue-400">
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <div class="relative flex items-center">
                                        <span class="absolute right-2 text-gray-500 text-xs">$</span>
                                        <input type="number" step="0.01" min="0" wire:model.live="items.{{ $index }}.tax"
                                            class="w-full px-2 pr-5 py-1.5 border border-gray-300 rounded text-sm text-right focus:outline-none focus:ring-2 focus:ring-blue-400">
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-right font-bold text-gray-800">
                                    $ {{ number_format($item['total'] ?? 0, 2) }}
                                </td>
                                <td class="px-4 py-3 text-center">
                                    @if(count($items) > 1)
                                        <button type="button" wire:click="removeItem({{ $index }})"
                                            class="text-red-600 hover:text-red-800 font-bold text-lg">
                                            ×
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>

        {{-- Add Item Button --}}
        <button type="button" wire:click="addItem"
            class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-semibold text-sm transition">
            + Add Item
        </button>

        {{-- ═══════════════════ SUMMARY + FIELDS ROW ═══════════════════ --}}
        <div class="grid grid-cols-3 gap-6">
            {{-- Left: Empty --}}
            <div></div>

            {{-- Middle: Empty --}}
            <div></div>

            {{-- Right: Summary --}}
            <div class="border border-gray-200 rounded-lg overflow-hidden">
                <div class="divide-y divide-gray-200 text-sm">
                    <div class="flex items-center justify-between px-4 py-3 bg-gray-50">
                        <span class="text-gray-600 font-semibold">Order Tax</span>
                        <span class="text-gray-800 font-bold">$ {{ number_format($order_tax, 2) }} ({{ number_format($order_tax_percent, 1) }})%</span>
                    </div>
                    <div class="flex items-center justify-between px-4 py-3">
                        <span class="text-gray-600 font-semibold">Discount</span>
                        <span class="text-gray-800 font-bold">$ {{ number_format($discount, 2) }}</span>
                    </div>
                    <div class="flex items-center justify-between px-4 py-3 bg-gray-50">
                        <span class="text-gray-600 font-semibold">Shipping</span>
                        <span class="text-gray-800 font-bold">$ {{ number_format($shipping, 2) }}</span>
                    </div>
                    <div class="flex items-center justify-between px-4 py-3 bg-blue-50">
                        <span class="text-blue-700 font-bold">Grand Total</span>
                        <span class="text-blue-700 font-black text-lg">$ {{ number_format($total_amount, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- ═══════════════════ ORDER TOTALS INPUTS ═══════════════════ --}}
        <div class="grid grid-cols-3 gap-6">
            {{-- Order Tax --}}
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Order Tax</label>
                <div class="flex gap-2">
                    <div class="flex-1 relative">
                        <input type="number" step="0.01" min="0" wire:model.live="order_tax_percent"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400 text-sm">
                        <span class="absolute right-3 top-2.5 text-gray-500 font-semibold">%</span>
                    </div>
                </div>
            </div>

            {{-- Discount --}}
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Discount</label>
                <div class="relative">
                    <span class="absolute left-4 top-2.5 text-gray-500 font-semibold">$</span>
                    <input type="number" step="0.01" min="0" wire:model.live="discount"
                        class="w-full pl-7 pr-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400 text-sm">
                </div>
            </div>

            {{-- Shipping --}}
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Shipping</label>
                <div class="relative">
                    <span class="absolute left-4 top-2.5 text-gray-500 font-semibold">$</span>
                    <input type="number" step="0.01" min="0" wire:model.live="shipping"
                        class="w-full pl-7 pr-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400 text-sm">
                </div>
            </div>
        </div>

        {{-- ═══════════════════ STATUS FIELDS ═══════════════════ --}}
        <div class="grid grid-cols-2 gap-6">
            {{-- Status --}}
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Status <span class="text-red-500">*</span></label>
                <select wire:model="status"
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400 text-sm">
                    <option value="draft">Draft</option>
                    <option value="ordered">Ordered</option>
                    <option value="delivered">Received</option>
                    <option value="cancelled">Cancelled</option>
                </select>
                @error('status') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Payment Status --}}
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Payment Status <span class="text-red-500">*</span></label>
                <select wire:model="payment_status"
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400 text-sm">
                    <option value="unpaid">Unpaid</option>
                    <option value="partial">Partial</option>
                    <option value="paid">Paid</option>
                </select>
                @error('payment_status') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        {{-- ═══════════════════ NOTES ═══════════════════ --}}
        <div>
            <label class="block text-sm font-bold text-gray-700 mb-2">Note</label>
            <textarea wire:model="notes" rows="3"
                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400 text-sm resize-none"
                placeholder="Enter Note"></textarea>
        </div>

        {{-- ═══════════════════ ACTIONS ═══════════════════ --}}
        <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-200">
            <a href="{{ route('purchases.index') }}" wire:navigate
               class="px-6 py-2.5 rounded-lg border border-gray-300 text-gray-700 font-semibold hover:bg-gray-50 transition">
                Cancel
            </a>
            <button type="submit"
                class="px-8 py-2.5 rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-bold shadow transition">
                <span wire:loading.remove wire:target="save">{{ $purchaseOrderId ? 'Update' : 'Submit' }}</span>
                <span wire:loading wire:target="save">Processing…</span>
            </button>
        </div>
    </form>
</div>
