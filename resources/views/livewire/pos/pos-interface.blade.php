<div class="flex flex-col h-screen bg-gray-100 select-none" 
    x-data="{
        showCalculator: false,
        calcDisplay: '0',
        appendCalc(val) {
            if (this.calcDisplay === '0' && val !== '.') {
                this.calcDisplay = val;
            } else {
                this.calcDisplay += val;
            }
        },
        calcResult() {
            try {
                const result = Function('return ' + this.calcDisplay.replace('×', '*').replace('÷', '/'))();
                this.calcDisplay = result.toString();
            } catch(e) {
                this.calcDisplay = 'Error';
            }
        }
    }"
    style="font-family:'Figtree',sans-serif; font-size:17px;">

    {{-- ══════════ TOP BAR ══════════ --}}
    <header class="flex items-center justify-between px-6 h-14 bg-white border-b border-gray-200 flex-shrink-0 z-10">
        {{-- Brand --}}
        <a href="{{ route('dashboard') }}" wire:navigate class="flex items-center gap-2">
            <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
            </svg>
            <span class="font-extrabold text-blue-700 uppercase tracking-widest text-base">{{ config('app.name', 'POS') }}</span>
        </a>

        {{-- Clock / Counter --}}
        <div class="text-sm text-gray-500 font-medium tracking-wide">
            <span id="pos-clock"></span>
            &nbsp;·&nbsp; Counter 01
        </div>

        {{-- Icons + User --}}
        <div class="flex items-center gap-4">
            {{-- Calculator Button --}}
            <button @click="showCalculator = !showCalculator"
                class="p-2 hover:bg-gray-100 rounded-lg transition-all duration-200 group" title="Calculator">
                <svg class="w-6 h-6 text-gray-600 group-hover:text-blue-600" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M6 2a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V4a2 2 0 00-2-2H6zm2 4h8v2H8V6zm0 4h8v2H8v-2zm0 4h4v2H8v-2z"/>
                </svg>
            </button>

            {{-- Dashboard Link --}}
            <a href="{{ route('dashboard') }}" wire:navigate
                class="p-2 hover:bg-gray-100 rounded-lg transition-all duration-200 group" title="Dashboard">
                <svg class="w-6 h-6 text-gray-600 group-hover:text-blue-600" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M3 13h8V3H3v10zm0 8h8v-6H3v6zm10 0h8V11h-8v10zm0-18v6h8V3h-8z"/>
                </svg>
            </a>

            {{-- User --}}
            <div class="flex items-center gap-2 text-sm text-gray-600 font-medium border-l border-gray-200 pl-4">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                {{ auth()->user()->name ?? 'Admin' }}
            </div>
        </div>
    </header>

    {{-- ══════════ CALCULATOR MODAL ══════════ --}}
    <div x-show="showCalculator" x-transition 
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/40" 
        @click.self="showCalculator = false" style="display: none;">
        <div class="bg-white rounded-2xl shadow-2xl w-80 border border-gray-200">
            {{-- Header --}}
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-bold text-gray-800">Calculator</h3>
                <button @click="showCalculator = false" class="text-gray-500 hover:text-gray-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            {{-- Display --}}
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                <input type="text" 
                    @keydown="if(['0','1','2','3','4','5','6','7','8','9','+','-','*','/','.'].includes($event.key)) {
                        calcDisplay += $event.key; 
                        $event.preventDefault();
                    } else if($event.key === 'Enter') {
                        calcResult();
                        $event.preventDefault();
                    } else if($event.key === 'Backspace') {
                        calcDisplay = calcDisplay.slice(0, -1);
                        $event.preventDefault();
                    }"
                    x-model="calcDisplay"
                    class="w-full px-4 py-3 text-right text-2xl font-bold bg-white border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400"
                    placeholder="0">
            </div>

            {{-- Buttons Grid --}}
            <div class="p-4 grid grid-cols-4 gap-2">
                {{-- Row 1 --}}
                <button @click="calcDisplay = ''" class="col-span-2 bg-red-500 hover:bg-red-600 text-white font-bold py-3 rounded text-lg transition">C</button>
                <button @click="calcDisplay = calcDisplay.slice(0, -1)" class="bg-orange-500 hover:bg-orange-600 text-white font-bold py-3 rounded text-lg transition">⌫</button>
                <button @click="appendCalc('/')" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded text-lg transition">÷</button>

                {{-- Row 2 --}}
                <button @click="appendCalc('7')" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-3 rounded text-lg transition">7</button>
                <button @click="appendCalc('8')" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-3 rounded text-lg transition">8</button>
                <button @click="appendCalc('9')" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-3 rounded text-lg transition">9</button>
                <button @click="appendCalc('*')" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded text-lg transition">×</button>

                {{-- Row 3 --}}
                <button @click="appendCalc('4')" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-3 rounded text-lg transition">4</button>
                <button @click="appendCalc('5')" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-3 rounded text-lg transition">5</button>
                <button @click="appendCalc('6')" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-3 rounded text-lg transition">6</button>
                <button @click="appendCalc('-')" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded text-lg transition">−</button>

                {{-- Row 4 --}}
                <button @click="appendCalc('1')" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-3 rounded text-lg transition">1</button>
                <button @click="appendCalc('2')" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-3 rounded text-lg transition">2</button>
                <button @click="appendCalc('3')" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-3 rounded text-lg transition">3</button>
                <button @click="appendCalc('+')" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded text-lg transition">+</button>

                {{-- Row 5 --}}
                <button @click="appendCalc('0')" class="col-span-2 bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-3 rounded text-lg transition">0</button>
                <button @click="appendCalc('.')" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-3 rounded text-lg transition">.</button>
                <button @click="calcResult()" class="bg-green-600 hover:bg-green-700 text-white font-bold py-3 rounded text-lg transition">=</button>
            </div>
        </div>
    </div>

    {{-- ══════════ BODY ══════════ --}}
    <div class="flex flex-1 overflow-hidden">

        {{-- ────── LEFT: Current Sale ────── --}}
        <aside class="w-96 flex flex-col bg-white border-r border-gray-200 flex-shrink-0">

            {{-- Customer & Warehouse Selectors --}}
            <div class="px-4 py-3 border-b border-gray-200 space-y-2">
                {{-- Customer Selector --}}
                <div>
                    <label class="text-sm font-semibold text-gray-600 uppercase block mb-1">Customer</label>
                    <select wire:model.live="selectedCustomer"
                        class="w-full text-base border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400">
                        <option value="0">Walk-in Customer</option>
                        @foreach ($customers as $customer)
                            <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Warehouse Selector --}}
                <div>
                    <label class="text-sm font-semibold text-gray-600 uppercase block mb-1">Warehouse</label>
                    <select wire:model.live="selectedWarehouse"
                        class="w-full text-base border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400">
                        <option value="1">Main Warehouse</option>
                        <option value="2">Branch 1</option>
                        <option value="3">Branch 2</option>
                    </select>
                </div>
            </div>

            {{-- Header --}}
            <div class="flex items-center justify-between px-4 py-3 border-b border-gray-100">
                <span class="font-bold text-gray-800 text-lg">Current Sale</span>
                @if (!empty($cart))
                    <button wire:click="clearCart"
                        class="text-base text-red-400 hover:text-red-600 transition">Clear</button>
                @endif
            </div>

            {{-- Flash messages --}}
            @if (session()->has('error'))
                <div class="mx-3 mt-2 px-3 py-2 bg-red-50 text-red-600 rounded text-sm">{{ session('error') }}</div>
            @endif
            @if (session()->has('success'))
                <div class="mx-3 mt-2 px-3 py-2 bg-green-50 text-green-600 rounded text-sm">{{ session('success') }}</div>
            @endif

            {{-- Cart items --}}
            <div class="flex-1 overflow-y-auto px-3 py-2 space-y-0.5">
                @forelse ($cart as $id => $item)
                    @php
                        $lineDiscount = (float) ($lineDiscounts[$id] ?? 0);
                        $lineBase     = $item['price'] * $item['quantity'];
                        $lineAfterDisc = max(0, $lineBase - $lineDiscount);
                        $lineTax      = round($lineAfterDisc * 0.05, 2);
                        $lineTotal    = $lineAfterDisc; // tax shown separately
                    @endphp
                    <div class="py-2.5 border-b border-gray-100 group">
                        <div class="flex items-start justify-between">
                            <div class="flex-1 min-w-0 pr-2">
                                <p class="text-base font-semibold text-gray-800 truncate">{{ $item['name'] }}</p>
                                <div class="flex items-center gap-1.5 mt-1">
                                    <button wire:click="decrementQty({{ $id }})"
                                        class="w-7 h-7 rounded bg-gray-100 hover:bg-gray-200 text-gray-600 flex items-center justify-center text-base font-bold leading-none">−</button>
                                    <span class="text-base text-gray-600 w-6 text-center font-medium">{{ $item['quantity'] }}</span>
                                    <button wire:click="incrementQty({{ $id }})"
                                        class="w-7 h-7 rounded bg-gray-100 hover:bg-gray-200 text-gray-600 flex items-center justify-center text-base font-bold leading-none">+</button>
                                    <span class="text-sm text-gray-400 ml-1">
                                        @if($item['unit']) {{ $item['unit'] }} @endif
                                        · ₵{{ number_format($item['price'], 2) }}
                                    </span>
                                </div>
                            </div>
                            <div class="text-right flex-shrink-0">
                                <p class="text-lg font-bold text-gray-800">₵{{ number_format($lineTotal, 2) }}</p>
                                <button wire:click="removeFromCart({{ $id }})"
                                    class="text-gray-300 hover:text-red-400 transition mt-0.5 opacity-0 group-hover:opacity-100">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        {{-- Per-item discount + tax display --}}
                        <div class="mt-1.5 flex items-center gap-2">
                            <div class="relative flex-1">
                                <span class="absolute left-2 top-1 text-gray-400 text-xs">Disc ₵</span>
                                <input type="number" min="0" step="0.01"
                                    value="{{ $lineDiscount > 0 ? $lineDiscount : '' }}"
                                    wire:change="setLineDiscount({{ $id }}, $event.target.value)"
                                    placeholder="0.00"
                                    class="w-full text-xs border border-gray-200 rounded pl-12 pr-2 py-1 focus:outline-none focus:ring-1 focus:ring-green-400">
                            </div>
                            <span class="text-xs text-gray-400 whitespace-nowrap">Tax ₵{{ number_format($lineTax, 2) }}</span>
                        </div>
                    </div>
                @empty
                    <div class="flex flex-col items-center justify-center h-36 text-gray-300 text-sm">
                        <svg class="w-10 h-10 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                        </svg>
                        Cart is empty
                    </div>
                @endforelse
            </div>

            {{-- Barcode scanner --}}
            <div class="px-3 py-2 border-t border-gray-100">
                <form wire:submit.prevent="scan">
                    <input type="text" wire:model.defer="barcode"
                        placeholder="Scan barcode / SKU…"
                        class="w-full text-base border border-gray-200 rounded px-3 py-3 focus:outline-none focus:ring-2 focus:ring-green-400 focus:border-transparent"
                        autofocus />
                </form>
            </div>

            {{-- Totals --}}
            <div class="px-4 py-3 border-t border-gray-200 space-y-2 bg-gray-50">
                <div class="flex justify-between text-base text-gray-600">
                    <span>Subtotal</span>
                    <span class="font-semibold">₵{{ number_format($subtotal, 2) }}</span>
                </div>
                <div class="flex justify-between text-base text-gray-600">
                    <span>Discount</span>
                    <span class="font-semibold">₵{{ number_format($discount, 2) }}</span>
                </div>
                <div class="flex justify-between text-base text-gray-600">
                    <span>VAT (5%)</span>
                    <span class="font-semibold">₵{{ number_format($tax, 2) }}</span>
                </div>
                <div class="flex justify-between items-center pt-3 border-t border-gray-200 mt-2">
                    <span class="font-black text-gray-800 text-lg">Total</span>
                    <span class="font-black text-green-600 text-3xl">₵{{ number_format($total, 2) }}</span>
                </div>
            </div>
        </aside>

        {{-- ────── RIGHT: Product Catalog ────── --}}
        <main class="flex-1 flex flex-col overflow-hidden">

            {{-- Search Bar --}}
            <div class="px-4 pt-3 pb-2 flex-shrink-0">
                <input type="text" wire:model.live.debounce.300ms="search"
                    placeholder="Scan/Search Product by Code Name"
                    class="w-full text-base border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-400 bg-white" />
            </div>

            {{-- Primary Filter Buttons (All Categories & All Brands) --}}
            <div class="flex items-center gap-2 px-4 pt-2 pb-1 flex-shrink-0 overflow-x-auto">
                <button wire:click="selectCategory(0); $set('selectedBrand', '')"
                    class="flex-shrink-0 px-5 py-2.5 rounded-full text-base font-bold text-white transition shadow"
                    style="background:{{ ($selectedCategory === 0 && $selectedBrand === '') ? '#3b82f6' : '#a0aec0' }}">
                    All Categories
                </button>
                <button wire:click="$set('selectedBrand', '')"
                    class="flex-shrink-0 px-5 py-2.5 rounded-full text-base font-bold text-white transition shadow"
                    style="background:{{ $selectedBrand === '' ? '#3b82f6' : '#a0aec0' }}">
                    All Brands
                </button>
            </div>

            {{-- Category Tabs --}}
            <div class="flex items-center gap-2 px-4 pt-2 pb-1 flex-shrink-0 overflow-x-auto">
                @foreach ($categories as $cat)
                    @php
                        $paletteBg   = ['#6366f1','#8b5cf6','#ec4899','#f97316','#06b6d4','#10b981','#f59e0b'];
                        $paletteDark = ['#4f46e5','#7c3aed','#db2777','#ea580c','#0891b2','#059669','#d97706'];
                        $idx      = $loop->index % count($paletteBg);
                        $bg       = $paletteBg[$idx];
                        $dark     = $paletteDark[$idx];
                        $isActive = ($selectedCategory === $cat->id && $selectedBrand === '');
                    @endphp
                    <button wire:click="selectCategory({{ $cat->id }}); $set('selectedBrand', '')"
                        class="flex-shrink-0 px-4 py-2 rounded-full text-sm font-bold uppercase tracking-wide transition text-white whitespace-nowrap"
                        style="background:{{ $isActive ? $dark : $bg }}; opacity:{{ $isActive ? '1' : '0.7' }};"
                        onmouseover="this.style.opacity='1'"
                        onmouseout="this.style.opacity='{{ $isActive ? '1' : '0.7' }}'">
                        {{ $cat->name }}
                    </button>
                @endforeach
            </div>

            {{-- Brand Tabs --}}
            @if (count($brands) > 0)
            <div class="flex items-center gap-2 px-4 pt-1 pb-2 flex-shrink-0 overflow-x-auto border-b border-gray-200">
                @foreach ($brands as $brand)
                    @php
                        $brandPalettesBg   = ['#6b7280','#78716c','#5b21b6','#1f2937'];
                        $brandPalettesDark = ['#4b5563','#57534e','#5b21b6','#111827'];
                        $bIdx      = array_search($brand, $brands) % count($brandPalettesBg);
                        $bgBrand   = $brandPalettesBg[$bIdx];
                        $darkBrand = $brandPalettesDark[$bIdx];
                        $isActiveBrand = ($selectedBrand === $brand);
                    @endphp
                    <button wire:click="$set('selectedBrand', '{{ $brand }}')"
                        class="flex-shrink-0 px-3 py-1 rounded-full text-xs font-semibold uppercase tracking-wide transition text-white whitespace-nowrap"
                        style="background:{{ $isActiveBrand ? $darkBrand : $bgBrand }}; opacity:{{ $isActiveBrand ? '1' : '0.6' }};"
                        onmouseover="this.style.opacity='1'"
                        onmouseout="this.style.opacity='{{ $isActiveBrand ? '1' : '0.6' }}'">
                        {{ $brand }}
                    </button>
                @endforeach
            </div>
            @endif

            {{-- Product Grid --}}
            <div class="flex-1 overflow-y-auto px-4 pb-4 pt-2">
                @if ($products->isEmpty())
                    <div class="flex flex-col items-center justify-center h-40 text-gray-300 text-sm">
                        <svg class="w-12 h-12 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                        No products found
                    </div>
                @else
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-4">
                        @foreach ($products as $product)
                            <button
                                wire:click="addToCartById({{ $product->id }})"
                                class="bg-white rounded-lg p-3 flex flex-col items-stretch gap-2 shadow-sm border border-gray-100
                                       hover:shadow-lg hover:border-blue-300 hover:-translate-y-1 transition-all duration-150 overflow-hidden group"
                            >
                                {{-- Image Container with Price & Weight Badges --}}
                                <div class="relative w-full aspect-square flex items-center justify-center rounded-lg overflow-hidden mb-1"
                                     style="background:#f8f9fa;">
                                    {{-- Price Badge (Top Left) --}}
                                    <div class="absolute top-2 left-2 bg-blue-500 text-white rounded-full px-2 py-1 text-xs font-bold z-10">
                                        ₵ {{ number_format($product->selling_price, 0) }}
                                    </div>

                                    {{-- Weight/Quantity Badge (Top Right) --}}
                                    <div class="absolute top-2 right-2 bg-blue-400 text-white rounded-lg px-2 py-1 text-xs font-semibold z-10">
                                        {{ $product->unit ?? '1 pc' }}
                                    </div>

                                    {{-- Product Image --}}
                                    @if ($product->image_path)
                                        <img src="{{ asset('storage/' . $product->image_path) }}"
                                             alt="{{ $product->name }}"
                                             class="w-full h-full object-contain" />
                                    @else
                                        @php
                                            $bgColors = ['#fff3e0','#e8f5e9','#e3f2fd','#fce4ec','#f3e5f5','#e0f7fa','#fff8e1'];
                                            $fgColors = ['#f57c00','#388e3c','#1976d2','#c2185b','#7b1fa2','#0097a7','#f9a825'];
                                            $ci = ord($product->name[0]) % 7;
                                        @endphp
                                        <div class="w-full h-full flex items-center justify-center text-4xl font-black"
                                             style="background:{{ $bgColors[$ci] }}; color:{{ $fgColors[$ci] }};">
                                            {{ strtoupper($product->name[0]) }}
                                        </div>
                                    @endif
                                </div>

                                {{-- Product Details --}}
                                <div class="flex-1 flex flex-col">
                                    <p class="text-base font-bold text-gray-800 text-center leading-tight line-clamp-2">{{ $product->name }}</p>
                                    <p class="text-sm text-gray-500 text-center mt-auto">{{ $product->sku ?? 'N/A' }}</p>
                                </div>

                                {{-- Add to Cart Indicator --}}
                                <div class="text-center text-sm text-blue-600 font-semibold opacity-0 group-hover:opacity-100 transition">
                                    + Add to Cart
                                </div>
                            </button>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- ── Bottom Action Bar ── --}}
            <div class="flex items-center gap-3 px-4 py-3 bg-white border-t border-gray-200 flex-shrink-0">
                {{-- Hold --}}
                <button class="flex-1 flex flex-col items-center justify-center gap-1 py-3 rounded-lg bg-blue-500 hover:bg-blue-600 text-white transition text-base font-bold shadow">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Hold
                </button>

                {{-- Retrieve --}}
                <button class="flex-1 flex flex-col items-center justify-center gap-1 py-3 rounded-lg bg-purple-500 hover:bg-purple-600 text-white transition text-base font-bold shadow">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                    </svg>
                    Retrieve
                </button>

                {{-- Reset --}}
                <button wire:click="clearCart"
                    class="flex-1 flex flex-col items-center justify-center gap-1 py-3 rounded-lg bg-red-500 hover:bg-red-600 text-white transition text-base font-bold shadow">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    Reset
                </button>

                {{-- Pay Now --}}
                <button wire:click="openPayModal"
                    @if(empty($cart)) disabled @endif
                    class="flex-1 py-3 rounded-lg bg-green-500 hover:bg-green-600 text-white font-bold text-lg transition shadow disabled:opacity-40 disabled:cursor-not-allowed">
                    Pay Now
                </button>
            </div>
        </main>
    </div>

    {{-- ══════════ PAY MODAL ══════════ --}}
    @if ($showPayModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm"
         wire:click.self="$set('showPayModal', false)">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md mx-4 overflow-hidden">

            <div class="flex items-center justify-between px-6 py-4 bg-blue-600 text-white">
                <span class="font-black text-2xl">Payment</span>
                <button wire:click="$set('showPayModal', false)" class="hover:opacity-70">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                    </svg>
                </button>
            </div>

            <div class="px-6 py-5 space-y-5">
                <div class="text-center">
                    <p class="text-base text-gray-500 uppercase tracking-wider">Total Due</p>
                    <p class="text-6xl font-black text-blue-600 mt-1">₵{{ number_format($total, 2) }}</p>
                </div>

                <div>
                    <p class="text-base text-gray-500 mb-2 uppercase tracking-wider">Payment Method</p>
                    <div class="grid grid-cols-2 gap-2">
                        @foreach ([
                            'cash'             => ['label' => 'Cash',             'icon' => 'M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z'],
                            'momo'             => ['label' => 'Mobile Money',     'icon' => 'M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z'],
                            'card'             => ['label' => 'Bank Card',        'icon' => 'M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z'],
                            'bank_transfer'    => ['label' => 'Bank Transfer',    'icon' => 'M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z'],
                        ] as $val => $meta)
                            <button wire:click="$set('paymentMethod', '{{ $val }}')"
                                class="flex items-center gap-2 px-3 py-2.5 rounded-lg text-base font-bold border-2 transition
                                    {{ $paymentMethod === $val
                                        ? 'border-blue-600 bg-blue-50 text-blue-700'
                                        : 'border-gray-200 text-gray-500 hover:border-gray-300' }}">
                                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $meta['icon'] }}"/>
                                </svg>
                                {{ $meta['label'] }}
                            </button>
                        @endforeach
                    </div>
                </div>

                @if ($paymentMethod === 'cash')
                <div>
                    <p class="text-base text-gray-500 mb-1 uppercase tracking-wider">Amount Tendered</p>
                    <input type="number" wire:model.live="amountTendered" step="0.01"
                        class="w-full border border-gray-200 rounded-lg px-4 py-3 text-2xl font-bold text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-400"
                        placeholder="0.00" />
                    @if ($amountTendered !== '' && (float)$amountTendered >= $total)
                        <div class="mt-2 flex justify-between text-lg">
                            <span class="text-gray-500">Change</span>
                            <span class="font-bold text-blue-600">₵{{ number_format($change, 2) }}</span>
                        </div>
                    @endif
                </div>
                @endif

                <button wire:click="checkout"
                    class="w-full py-3.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-black text-xl transition shadow">
                    <span wire:loading.remove wire:target="checkout">Confirm Payment</span>
                    <span wire:loading wire:target="checkout">Processing…</span>
                </button>
            </div>
        </div>
    </div>
    @endif

    {{-- ══════════ RECEIPT MODAL (shown after successful payment) ══════════ --}}
    @if ($showReceiptModal && $lastSaleId)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm mx-4 overflow-hidden">

            <div class="flex items-center justify-between px-6 py-4 bg-teal-600 text-white">
                <span class="font-black text-2xl">Sale Complete!</span>
                <button wire:click="$set('showReceiptModal', false)" class="hover:opacity-70">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                    </svg>
                </button>
            </div>

            <div class="px-6 py-5 space-y-3">
                <div class="flex justify-center mb-2">
                    <div class="w-16 h-16 rounded-full bg-green-100 flex items-center justify-center">
                        <svg class="w-9 h-9 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                </div>

                <p class="text-center text-base text-gray-500">Payment received. Choose a receipt format to print or download.</p>

                {{-- Thermal Receipt --}}
                <a href="{{ route('sales.receipt', $lastSaleId) }}" target="_blank"
                   class="flex items-center gap-3 w-full px-4 py-3 rounded-xl border-2 border-gray-200 hover:border-teal-400 hover:bg-teal-50 transition group">
                    <div class="w-9 h-9 rounded-lg bg-gray-100 group-hover:bg-teal-100 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-gray-500 group-hover:text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-base font-bold text-gray-700 group-hover:text-teal-700">Thermal Receipt</p>
                        <p class="text-sm text-gray-400">80mm PDF — for thermal printers</p>
                    </div>
                    <svg class="w-4 h-4 text-gray-300 group-hover:text-teal-400 ml-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                    </svg>
                </a>

                {{-- A4 Invoice --}}
                <a href="{{ route('sales.invoice', $lastSaleId) }}" target="_blank"
                   class="flex items-center gap-3 w-full px-4 py-3 rounded-xl border-2 border-gray-200 hover:border-blue-400 hover:bg-blue-50 transition group">
                    <div class="w-9 h-9 rounded-lg bg-gray-100 group-hover:bg-blue-100 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-gray-500 group-hover:text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-base font-bold text-gray-700 group-hover:text-blue-700">A4 Invoice / PDF</p>
                        <p class="text-sm text-gray-400">Full A4 invoice with all details</p>
                    </div>
                    <svg class="w-4 h-4 text-gray-300 group-hover:text-blue-400 ml-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                    </svg>
                </a>

                <button wire:click="$set('showReceiptModal', false)"
                    class="w-full py-3 rounded-xl border border-gray-200 text-base font-semibold text-gray-600 hover:bg-gray-50 transition mt-1">
                    Skip — New Sale
                </button>
            </div>
        </div>
    </div>
    @endif

    <script>
        (function tick() {
            const el = document.getElementById('pos-clock');
            if (el) {
                const now = new Date();
                el.textContent = now.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
            }
            setTimeout(tick, 10000);
        })();
    </script>
</div>
