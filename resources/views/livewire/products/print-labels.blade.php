<div
    x-data="{}"
    x-on:open-print-window.window="window.open('{{ route('labels.print') }}', '_blank')"
>
    {{-- Page header --}}
    <div class="flex items-center gap-2 mb-6">
        <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
        </svg>
        <h1 class="text-xl font-bold text-gray-800">Print Labels</h1>
        <span class="text-gray-400">|</span>
        <nav class="text-sm text-gray-500 flex gap-1">
            <span>Products</span><span>|</span>
            <span class="text-teal-600 font-medium">Print Labels</span>
        </nav>
    </div>

    {{-- CONFIGURATION --}}
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm mb-6">
        <div class="flex items-center gap-2 px-5 py-3 border-b border-gray-100">
            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            <span class="font-semibold text-gray-700 text-sm">Configuration</span>
        </div>
        <div class="p-5 space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Paper size</label>
                    <div class="relative">
                        <select wire:model="paperSize"
                            class="w-full appearance-none border border-gray-300 rounded-lg px-3 py-2.5 text-sm bg-white text-gray-700 focus:ring-2 focus:ring-teal-500 focus:border-teal-500 pr-10">
                            @foreach($paperSizes as $key => $label)
                                <option value="{{ $key }}">{{ $label }}</option>
                            @endforeach
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <label class="flex items-center gap-3 p-3 rounded-lg border border-gray-200 cursor-pointer hover:bg-gray-50">
                    <input type="checkbox" wire:model="displayPrice"
                        class="w-4 h-4 rounded text-teal-600 border-gray-300 focus:ring-teal-500">
                    <span class="text-sm font-medium text-gray-700">Display Price</span>
                </label>
                <label class="flex items-center gap-3 p-3 rounded-lg border border-gray-200 cursor-pointer hover:bg-gray-50">
                    <input type="checkbox" wire:model="autoPrint"
                        class="w-4 h-4 rounded text-teal-600 border-gray-300 focus:ring-teal-500">
                    <span class="text-sm font-medium text-gray-700">Auto Print</span>
                </label>
            </div>
        </div>
    </div>

    {{-- PRODUCT SEARCH --}}
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm mb-6">
        <div class="flex items-center gap-2 px-5 py-3 border-b border-gray-100">
            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <span class="font-semibold text-gray-700 text-sm">Product Name</span>
        </div>
        <div class="p-5">
            <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                <div class="flex items-center border border-gray-300 rounded-lg overflow-hidden focus-within:ring-2 focus-within:ring-teal-500 focus-within:border-teal-500">
                    <span class="flex items-center justify-center w-11 h-11 bg-teal-600 flex-shrink-0">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                        </svg>
                    </span>
                    <input
                        type="text"
                        wire:model.live.debounce.300ms="search"
                        @focus="open = true"
                        @keydown.enter.prevent=""
                        placeholder="Scan/Search Product by Code Or Name"
                        class="flex-1 px-4 py-3 text-sm text-gray-700 bg-white outline-none"
                        autocomplete="off"
                    >
                    @if($search)
                    <button wire:click="$set('search', '')" @click="open = false" class="px-3 text-gray-400 hover:text-gray-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                    @endif
                </div>

                @if(!empty($searchResults))
                <div x-show="open" class="absolute z-50 w-full mt-1 bg-white border border-gray-200 rounded-xl shadow-lg overflow-hidden">
                    @foreach($searchResults as $result)
                    <button
                        wire:click="addProduct({{ $result['id'] }})"
                        @click="open = false"
                        class="w-full flex items-center gap-3 px-4 py-3 hover:bg-teal-50 text-left border-b border-gray-50 last:border-0"
                    >
                        <div class="w-8 h-8 rounded-lg bg-teal-100 flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                        </div>
                        <div class="min-w-0">
                            <div class="text-sm font-semibold text-gray-800 truncate">{{ $result['name'] }}</div>
                            <div class="text-xs text-gray-400">{{ $result['sku'] ?: ($result['barcode'] ?? 'No code') }}</div>
                        </div>
                        <div class="ml-auto text-xs font-bold text-teal-600">GHS {{ number_format($result['selling_price'], 2) }}</div>
                    </button>
                    @endforeach
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- SELECTED PRODUCTS --}}
    @if(!empty($selectedProducts))
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm mb-6">
        <div class="flex items-center justify-between px-5 py-3 border-b border-gray-100">
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                <span class="font-semibold text-gray-700 text-sm">Selected Products</span>
                <span class="inline-flex items-center justify-center w-5 h-5 text-xs font-bold bg-teal-600 text-white rounded-full">
                    {{ count($selectedProducts) }}
                </span>
            </div>
            <div class="flex items-center gap-2">
                <button wire:click="resetProducts"
                    class="flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    Reset
                </button>
                <button wire:click="printLabels"
                    class="flex items-center gap-1.5 px-4 py-1.5 text-xs font-bold text-white bg-teal-600 hover:bg-teal-700 rounded-lg">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                    </svg>
                    Print
                </button>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100">
                        <th class="px-5 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wide">Product Name</th>
                        <th class="px-5 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wide">Code Product</th>
                        <th class="px-5 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wide">Quantity</th>
                        <th class="px-5 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wide">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($selectedProducts as $i => $product)
                    <tr class="border-b border-gray-50 hover:bg-gray-50">
                        <td class="px-5 py-3 font-medium text-gray-800">{{ $product['name'] }}</td>
                        <td class="px-5 py-3 text-gray-400 font-mono text-xs">{{ $product['sku'] ?: 'N/A' }}</td>
                        <td class="px-5 py-3 text-center">
                            <input
                                type="number"
                                wire:model.lazy="selectedProducts.{{ $i }}.quantity"
                                min="1"
                                class="w-20 text-center border border-gray-300 rounded-lg px-2 py-1 text-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500"
                            >
                        </td>
                        <td class="px-5 py-3 text-center">
                            <button wire:click="removeProduct({{ $i }})"
                                class="inline-flex items-center justify-center w-7 h-7 rounded-lg bg-red-100 hover:bg-red-200 text-red-600">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- BARCODE PREVIEW --}}
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm">
        <div class="flex items-center justify-between px-5 py-3 border-b border-gray-100">
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M4 6h1M4 10h1M4 14h1M4 18h1M8 6h1M8 10h1M8 14h1M8 18h1M12 6h2M12 10h2M12 14h2M12 18h2M16 6h4M16 10h4M16 14h4M16 18h4"/>
                </svg>
                <span class="font-semibold text-gray-700 text-sm">Barcode Preview</span>
                @php
                    $totalQty = array_sum(array_column($selectedProducts, 'quantity'));
                    $cols  = match($paperSize) { '24_a4' => 3, '10_a4' => 2, default => 4 };
                    $rows  = match($paperSize) { '24_a4' => 8, '10_a4' => 5, default => 10 };
                    $pages = (int) ceil($totalQty / ($cols * $rows)) ?: 1;
                @endphp
                <span class="text-xs text-gray-400">{{ $pages }} {{ Str::plural('Page', $pages) }}</span>
            </div>
            <button wire:click="printLabels"
                class="flex items-center gap-1.5 px-4 py-1.5 text-xs font-bold text-white bg-teal-600 hover:bg-teal-700 rounded-lg">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                </svg>
                Print
            </button>
        </div>
        <div class="p-5 overflow-x-auto">
            @php
                $previewLabels = [];
                foreach ($selectedProducts as $p) {
                    $qty = max(1, (int)($p['quantity'] ?? 1));
                    for ($x = 0; $x < $qty; $x++) {
                        $previewLabels[] = $p;
                    }
                }
                $previewGridCols = match($paperSize) { '24_a4' => 'grid-cols-3', '10_a4' => 'grid-cols-2', default => 'grid-cols-4' };
            @endphp
            <div class="border border-gray-200 bg-white inline-block min-w-full shadow-sm">
                <div class="grid {{ $previewGridCols }}">
                    @foreach($previewLabels as $pl)
                    <div class="border border-dashed border-gray-200 flex flex-col items-center justify-center text-center p-1.5 min-h-[64px]">
                        <div class="text-[10px] font-bold text-gray-800 uppercase leading-tight w-full px-1 overflow-hidden">{{ $pl['name'] }}</div>
                        @if($displayPrice)
                        <div class="text-[9px] text-gray-500 font-semibold">GHS {{ number_format($pl['selling_price'], 2) }}</div>
                        @endif
                        @if(!empty($pl['sku']))
                        <svg class="preview-barcode w-full" style="max-height:28px;" data-code="{{ $pl['sku'] }}"></svg>
                        <div class="text-[8px] text-gray-500 font-mono">{{ $pl['sku'] }}</div>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    @else
    {{-- Empty state --}}
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-16 text-center">
        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-teal-50 mb-4">
            <svg class="w-8 h-8 text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                      d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
            </svg>
        </div>
        <p class="text-gray-500 font-medium">Search and add products above to generate labels</p>
        <p class="text-sm text-gray-400 mt-1">You can also scan a barcode — the scanner acts as a keyboard</p>
    </div>
    @endif

</div>

@script
<script>
    (function () {
        function loadJsBarcode(cb) {
            if (window.JsBarcode) { cb(); return; }
            var s = document.createElement('script');
            s.src = 'https://cdn.jsdelivr.net/npm/jsbarcode@3.11.6/dist/JsBarcode.all.min.js';
            s.onload = cb;
            document.head.appendChild(s);
        }

        function renderBarcodes() {
            loadJsBarcode(function () {
                document.querySelectorAll('.preview-barcode').forEach(function (el) {
                    var code = el.dataset.code;
                    if (!code || el.dataset.rendered) return;
                    try {
                        JsBarcode(el, code, {
                            format: 'CODE128',
                            width: 1,
                            height: 22,
                            displayValue: false,
                            margin: 0,
                            background: '#ffffff',
                            lineColor: '#000000',
                        });
                        el.dataset.rendered = '1';
                    } catch (e) {
                        el.style.display = 'none';
                    }
                });
            });
        }

        renderBarcodes();

        document.addEventListener('livewire:updated', function () {
            document.querySelectorAll('.preview-barcode[data-rendered]').forEach(function(el) {
                delete el.dataset.rendered;
            });
            renderBarcodes();
        });
    })();
</script>
@endscript
