<div class="flex flex-col h-screen select-none"
    style="font-family:'Figtree',sans-serif; background:#f1f5f9;"
    x-data="{
        showCalculator: false,
        calcDisplay: '0',
        appendCalc(val) {
            if (this.calcDisplay === '0' && val !== '.') { this.calcDisplay = val; }
            else { this.calcDisplay += val; }
        },
        calcResult() {
            try {
                const r = Function('return ' + this.calcDisplay.replace(/×/g,'*').replace(/÷/g,'/'))();
                this.calcDisplay = r.toString();
            } catch(e) { this.calcDisplay = 'Error'; }
        }
    }">

{{-- ══════════════════════════════════════════════════════
     TOP BAR
══════════════════════════════════════════════════════ --}}
<header style="background:#0e2a38; height:58px; flex-shrink:0;"
    class="flex items-center justify-between px-5 z-20 shadow-lg">

    {{-- Brand --}}
    <a href="{{ route('dashboard') }}" wire:navigate class="flex items-center gap-2.5 no-underline">
        <div style="width:34px;height:34px;border-radius:9px;background:#0d9488;display:flex;align-items:center;justify-content:center;">
            <svg width="18" height="18" fill="none" stroke="#fff" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
            </svg>
        </div>
        <span style="font-weight:900;font-size:16px;letter-spacing:1.5px;color:#fff;">SIP<span style="color:#0d9488;">AMS</span> <span style="font-weight:400;font-size:12px;color:#64748b;letter-spacing:.5px;">POS</span></span>
    </a>

    {{-- Center: Clock + Counter --}}
    <div class="flex items-center gap-3">
        <div style="background:#1a3e52;border-radius:8px;padding:5px 14px;display:flex;align-items:center;gap:8px;">
            <svg width="14" height="14" fill="none" stroke="#0d9488" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span id="pos-clock" style="font-size:13px;font-weight:600;color:#94a3b8;letter-spacing:.5px;"></span>
        </div>
        <div style="background:#1a3e52;border-radius:8px;padding:5px 14px;">
            <span style="font-size:12px;color:#64748b;font-weight:600;">COUNTER </span>
            <span style="font-size:12px;color:#0d9488;font-weight:800;">01</span>
        </div>
    </div>

    {{-- Right: actions + user --}}
    <div class="flex items-center gap-2">
        {{-- Calculator --}}
        <button @click="showCalculator = !showCalculator"
            style="padding:8px;border-radius:8px;background:transparent;border:none;cursor:pointer;transition:background .2s;"
            onmouseover="this.style.background='#1a3e52'" onmouseout="this.style.background='transparent'"
            title="Calculator">
            <svg width="20" height="20" fill="#94a3b8" viewBox="0 0 24 24">
                <path d="M6 2a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V4a2 2 0 00-2-2H6zm2 4h8v2H8V6zm0 4h2v2H8v-2zm4 0h2v2h-2v-2zm-4 4h2v2H8v-2zm4 0h4v2h-4v-2z"/>
            </svg>
        </button>

        {{-- Dashboard --}}
        <a href="{{ route('dashboard') }}" wire:navigate
            style="padding:8px;border-radius:8px;transition:background .2s;"
            onmouseover="this.style.background='#1a3e52'" onmouseout="this.style.background='transparent'"
            title="Dashboard">
            <svg width="20" height="20" fill="#94a3b8" viewBox="0 0 24 24">
                <path d="M3 13h8V3H3v10zm0 8h8v-6H3v6zm10 0h8V11h-8v10zm0-18v6h8V3h-8z"/>
            </svg>
        </a>

        {{-- Divider --}}
        <div style="width:1px;height:24px;background:#1a3e52;margin:0 4px;"></div>

        {{-- User --}}
        <div style="display:flex;align-items:center;gap:8px;">
            <div style="width:30px;height:30px;border-radius:50%;background:#0d9488;display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:800;color:#fff;">
                {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
            </div>
            <span style="font-size:13px;font-weight:600;color:#94a3b8;">{{ auth()->user()->name ?? 'Admin' }}</span>
        </div>
    </div>
</header>

{{-- ══════════════════════════════════════════════════════
     CALCULATOR MODAL
══════════════════════════════════════════════════════ --}}
<div x-show="showCalculator" x-transition
    class="fixed inset-0 z-50 flex items-center justify-center"
    style="background:rgba(0,0,0,.6);backdrop-filter:blur(6px);display:none;"
    @click.self="showCalculator = false">
    <div style="background:#0e2a38;border-radius:20px;width:300px;overflow:hidden;box-shadow:0 25px 60px rgba(0,0,0,.5);border:1px solid #1a3e52;">
        <div style="display:flex;align-items:center;justify-content:space-between;padding:16px 20px;border-bottom:1px solid #1a3e52;">
            <span style="font-weight:800;font-size:16px;color:#fff;">Calculator</span>
            <button @click="showCalculator = false" style="background:none;border:none;cursor:pointer;color:#64748b;" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='#64748b'">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <div style="padding:16px 20px;background:#1a3e52;">
            <input type="text" x-model="calcDisplay"
                style="width:100%;text-align:right;font-size:26px;font-weight:800;background:transparent;border:none;outline:none;color:#fff;letter-spacing:1px;"
                @keydown="
                    if(['0','1','2','3','4','5','6','7','8','9','+','-','*','/','.'].includes($event.key)){calcDisplay+=$event.key;$event.preventDefault();}
                    else if($event.key==='Enter'){calcResult();$event.preventDefault();}
                    else if($event.key==='Backspace'){calcDisplay=calcDisplay.slice(0,-1);$event.preventDefault();}
                ">
        </div>
        <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:1px;background:#1a3e52;padding:4px;">
            @foreach([['C','','','⌫','÷'],['7','8','9','×'],['4','5','6','−'],['1','2','3','+'],['0','0','.','=']] as $ri => $row)
            @foreach($row as $ci => $k)
            @php
                $span = ($ri === 4 && $ci <= 1) ? 'grid-column:span 2' : '';
                $bg = match($k) {
                    'C'   => '#ef4444',
                    '⌫'   => '#f97316',
                    '÷','×','−','+' => '#0d9488',
                    '='   => '#059669',
                    default => '#243f52'
                };
                $click = match($k) {
                    'C'  => "calcDisplay='0'",
                    '⌫'  => "calcDisplay=calcDisplay.length>1?calcDisplay.slice(0,-1):'0'",
                    '='  => "calcResult()",
                    '÷'  => "appendCalc('/')",
                    '×'  => "appendCalc('*')",
                    '−'  => "appendCalc('-')",
                    default => "appendCalc('$k')"
                };
            @endphp
            @if (!($ri === 4 && $ci === 1))
            <button @click="{{ $click }}"
                style="{{ $span }};background:{{ $bg }};border:none;cursor:pointer;padding:14px 0;font-size:18px;font-weight:800;color:#fff;border-radius:8px;margin:2px;transition:filter .15s;"
                onmouseover="this.style.filter='brightness(1.2)'" onmouseout="this.style.filter='brightness(1)'">
                {{ $k }}
            </button>
            @endif
            @endforeach
            @endforeach
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════
     BODY
══════════════════════════════════════════════════════ --}}
<div class="flex flex-1 overflow-hidden">

    {{-- ─────────────────────────────────────────────────
         LEFT: CART PANEL (dark)
    ───────────────────────────────────────────────── --}}
    <aside style="width:370px;flex-shrink:0;display:flex;flex-direction:column;background:#0e2a38;">

        {{-- ── Customer & Warehouse ── --}}
        <div style="padding:12px 14px;border-bottom:1px solid #1a3e52;display:flex;gap:8px;">
            <div style="flex:1;">
                <label style="font-size:10px;font-weight:700;color:#0d9488;letter-spacing:1px;text-transform:uppercase;display:block;margin-bottom:4px;">Customer</label>
                <select wire:model.live="selectedCustomer"
                    style="width:100%;background:#1a3e52;border:1px solid #243f52;border-radius:8px;padding:7px 10px;font-size:13px;color:#e2e8f0;outline:none;">
                    <option value="0">Walk-in</option>
                    @foreach ($customers as $customer)
                        <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                    @endforeach
                </select>
            </div>
            <div style="flex:1;">
                <label style="font-size:10px;font-weight:700;color:#0d9488;letter-spacing:1px;text-transform:uppercase;display:block;margin-bottom:4px;">Warehouse</label>
                <select wire:model.live="selectedWarehouse"
                    style="width:100%;background:#1a3e52;border:1px solid #243f52;border-radius:8px;padding:7px 10px;font-size:13px;color:#e2e8f0;outline:none;">
                    <option value="1">Main Store</option>
                    <option value="2">Branch 1</option>
                    <option value="3">Branch 2</option>
                </select>
            </div>
        </div>

        {{-- ── Cart header ── --}}
        <div style="display:flex;align-items:center;justify-content:space-between;padding:10px 14px;border-bottom:1px solid #1a3e52;">
            <div style="display:flex;align-items:center;gap:8px;">
                <svg width="16" height="16" fill="none" stroke="#0d9488" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                </svg>
                <span style="font-size:13px;font-weight:800;color:#fff;letter-spacing:.5px;">CURRENT ORDER</span>
                @if (!empty($cart))
                    <span style="background:#0d9488;color:#fff;font-size:10px;font-weight:800;padding:2px 7px;border-radius:99px;">{{ $totalQty }}</span>
                @endif
            </div>
            @if (!empty($cart))
                <button wire:click="clearCart"
                    style="font-size:11px;font-weight:700;color:#ef4444;background:none;border:none;cursor:pointer;letter-spacing:.3px;"
                    onmouseover="this.style.color='#fca5a5'" onmouseout="this.style.color='#ef4444'">
                    CLEAR ALL
                </button>
            @endif
        </div>

        {{-- ── Flash messages ── --}}
        @if (session()->has('error'))
            <div style="margin:8px 12px;padding:8px 12px;background:rgba(239,68,68,.15);border:1px solid rgba(239,68,68,.3);border-radius:8px;font-size:12px;color:#fca5a5;">
                ⚠ {{ session('error') }}
            </div>
        @endif
        @if (session()->has('success'))
            <div style="margin:8px 12px;padding:8px 12px;background:rgba(16,185,129,.15);border:1px solid rgba(16,185,129,.3);border-radius:8px;font-size:12px;color:#6ee7b7;">
                ✓ {{ session('success') }}
            </div>
        @endif

        {{-- ── Cart items ── --}}
        <div style="flex:1;overflow-y:auto;padding:8px 10px;" class="custom-scroll">
            @forelse ($cart as $id => $item)
                @php
                    $lineDiscount  = (float) ($lineDiscounts[$id] ?? 0);
                    $lineBase      = $item['price'] * $item['quantity'];
                    $lineAfterDisc = max(0, $lineBase - $lineDiscount);
                    $lineTotal     = $lineAfterDisc;
                @endphp
                <div style="background:#1a3e52;border-radius:12px;padding:10px 12px;margin-bottom:6px;border:1px solid #243f52;">
                    <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:8px;">
                        {{-- Name + controls --}}
                        <div style="flex:1;min-width:0;">
                            <p style="font-size:13px;font-weight:700;color:#e2e8f0;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;margin-bottom:6px;">
                                {{ $item['name'] }}
                            </p>
                            <div style="display:flex;align-items:center;gap:6px;">
                                <button wire:click="decrementQty({{ $id }})"
                                    style="width:26px;height:26px;border-radius:7px;background:#0e2a38;border:1px solid #243f52;color:#94a3b8;font-size:16px;font-weight:800;cursor:pointer;display:flex;align-items:center;justify-content:center;line-height:1;"
                                    onmouseover="this.style.background='#0d9488';this.style.color='#fff'" onmouseout="this.style.background='#0e2a38';this.style.color='#94a3b8'">−</button>
                                <span style="font-size:14px;font-weight:800;color:#fff;width:24px;text-align:center;">{{ $item['quantity'] }}</span>
                                <button wire:click="incrementQty({{ $id }})"
                                    style="width:26px;height:26px;border-radius:7px;background:#0e2a38;border:1px solid #243f52;color:#94a3b8;font-size:16px;font-weight:800;cursor:pointer;display:flex;align-items:center;justify-content:center;line-height:1;"
                                    onmouseover="this.style.background='#0d9488';this.style.color='#fff'" onmouseout="this.style.background='#0e2a38';this.style.color='#94a3b8'">+</button>
                                <span style="font-size:11px;color:#64748b;margin-left:2px;">₵{{ number_format($item['price'], 2) }} ea</span>
                            </div>
                        </div>
                        {{-- Right: total + remove --}}
                        <div style="text-align:right;flex-shrink:0;">
                            <p style="font-size:16px;font-weight:800;color:#0d9488;">₵{{ number_format($lineTotal, 2) }}</p>
                            <button wire:click="removeFromCart({{ $id }})"
                                style="background:none;border:none;cursor:pointer;color:#334155;margin-top:4px;"
                                onmouseover="this.style.color='#ef4444'" onmouseout="this.style.color='#334155'">
                                <svg width="14" height="14" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                    {{-- Discount input --}}
                    <div style="margin-top:7px;display:flex;align-items:center;gap:6px;">
                        <span style="font-size:10px;color:#475569;font-weight:600;">DISC ₵</span>
                        <input type="number" min="0" step="0.01"
                            value="{{ $lineDiscount > 0 ? $lineDiscount : '' }}"
                            wire:change="setLineDiscount({{ $id }}, $event.target.value)"
                            placeholder="0.00"
                            style="flex:1;background:#0e2a38;border:1px solid #243f52;border-radius:6px;padding:4px 8px;font-size:12px;color:#94a3b8;outline:none;">
                    </div>
                </div>
            @empty
                <div style="display:flex;flex-direction:column;align-items:center;justify-content:center;height:200px;gap:12px;">
                    <div style="width:60px;height:60px;border-radius:16px;background:#1a3e52;display:flex;align-items:center;justify-content:center;">
                        <svg width="28" height="28" fill="none" stroke="#334155" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                        </svg>
                    </div>
                    <p style="font-size:13px;color:#334155;font-weight:600;">Cart is empty</p>
                    <p style="font-size:11px;color:#243f52;text-align:center;">Click a product or scan a barcode<br>to add items</p>
                </div>
            @endforelse
        </div>

        {{-- ── Barcode scan ── --}}
        <div style="padding:8px 10px;border-top:1px solid #1a3e52;">
            <form wire:submit.prevent="scan">
                <div style="display:flex;align-items:center;background:#1a3e52;border:1px solid #243f52;border-radius:10px;padding:0 12px;gap:8px;">
                    <svg width="16" height="16" fill="none" stroke="#0d9488" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M7 21V3M3 7h4M3 12h4M3 17h4M17 21V3m4 4h-4m4 5h-4m4 5h-4M12 3v18"/>
                    </svg>
                    <input type="text" wire:model.defer="barcode"
                        placeholder="Scan barcode or enter SKU…"
                        style="flex:1;background:transparent;border:none;outline:none;padding:10px 0;font-size:13px;color:#e2e8f0;"
                        autofocus>
                </div>
            </form>
        </div>

        {{-- ── Totals ── --}}
        <div style="padding:10px 14px;border-top:1px solid #1a3e52;background:#091e2b;space-y:4px;">
            <div style="display:flex;justify-content:space-between;align-items:center;padding:4px 0;">
                <span style="font-size:12px;color:#64748b;font-weight:600;">Subtotal</span>
                <span style="font-size:13px;color:#94a3b8;font-weight:700;">₵{{ number_format($subtotal, 2) }}</span>
            </div>
            <div style="display:flex;justify-content:space-between;align-items:center;padding:4px 0;">
                <span style="font-size:12px;color:#64748b;font-weight:600;">Discount</span>
                <span style="font-size:13px;color:#f97316;font-weight:700;">− ₵{{ number_format($discountAmount, 2) }}</span>
            </div>
            <div style="display:flex;justify-content:space-between;align-items:center;padding:4px 0;">
                <span style="font-size:12px;color:#64748b;font-weight:600;">VAT (5%)</span>
                <span style="font-size:13px;color:#94a3b8;font-weight:700;">₵{{ number_format($taxAmount, 2) }}</span>
            </div>
            <div style="display:flex;justify-content:space-between;align-items:center;padding:10px 0 4px;border-top:1px solid #1a3e52;margin-top:4px;">
                <span style="font-size:14px;color:#e2e8f0;font-weight:900;letter-spacing:.5px;">TOTAL</span>
                <span style="font-size:28px;color:#0d9488;font-weight:900;letter-spacing:-1px;">₵{{ number_format($total, 2) }}</span>
            </div>
        </div>

        {{-- ── Action Buttons ── --}}
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:0;padding:0;border-top:1px solid #1a3e52;">
            {{-- Hold --}}
            <button style="background:#1a3e52;border:none;cursor:pointer;padding:12px 0;display:flex;flex-direction:column;align-items:center;gap:3px;border-right:1px solid #0e2a38;transition:background .2s;"
                onmouseover="this.style.background='#243f52'" onmouseout="this.style.background='#1a3e52'">
                <svg width="18" height="18" fill="none" stroke="#94a3b8" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span style="font-size:10px;font-weight:700;color:#64748b;letter-spacing:.5px;">HOLD</span>
            </button>

            {{-- Reset --}}
            <button wire:click="clearCart"
                style="background:#1a3e52;border:none;cursor:pointer;padding:12px 0;display:flex;flex-direction:column;align-items:center;gap:3px;transition:background .2s;"
                onmouseover="this.style.background='rgba(239,68,68,.15)'" onmouseout="this.style.background='#1a3e52'">
                <svg width="18" height="18" fill="none" stroke="#ef4444" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
                <span style="font-size:10px;font-weight:700;color:#ef4444;letter-spacing:.5px;">RESET</span>
            </button>

            {{-- Pay Now (full width) --}}
            <button wire:click="openPayModal"
                @if(empty($cart)) disabled @endif
                style="grid-column:span 2;background:{{ empty($cart) ? '#1a3e52' : '#0d9488' }};border:none;cursor:{{ empty($cart) ? 'not-allowed' : 'pointer' }};padding:16px;display:flex;align-items:center;justify-content:center;gap:10px;transition:background .2s;opacity:{{ empty($cart) ? '.4' : '1' }};"
                onmouseover="this.style.background='{{ empty($cart) ? '#1a3e52' : '#0f766e' }}'" onmouseout="this.style.background='{{ empty($cart) ? '#1a3e52' : '#0d9488' }}'">
                <svg width="20" height="20" fill="none" stroke="#fff" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                </svg>
                <span style="font-size:16px;font-weight:900;color:#fff;letter-spacing:.5px;">CHARGE  ₵{{ number_format($total, 2) }}</span>
            </button>
        </div>

    </aside>

    {{-- ─────────────────────────────────────────────────
         RIGHT: PRODUCT CATALOG
    ───────────────────────────────────────────────── --}}
    <main style="flex:1;display:flex;flex-direction:column;overflow:hidden;">

        {{-- ── Search + Filter bar ── --}}
        <div style="background:#fff;padding:10px 16px 0;border-bottom:1px solid #e2e8f0;flex-shrink:0;">
            {{-- Search --}}
            <div style="display:flex;align-items:center;background:#f8fafc;border:1.5px solid #e2e8f0;border-radius:10px;padding:0 14px;gap:10px;margin-bottom:10px;">
                <svg width="16" height="16" fill="none" stroke="#94a3b8" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text" wire:model.live.debounce.300ms="search"
                    placeholder="Search products by name, SKU or barcode…"
                    style="flex:1;border:none;background:transparent;padding:11px 0;font-size:14px;color:#1e293b;outline:none;">
                @if ($search !== '')
                    <button wire:click="$set('search', '')" style="background:none;border:none;cursor:pointer;color:#94a3b8;">
                        <svg width="14" height="14" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                    </button>
                @endif
            </div>

            {{-- Category pills --}}
            <div style="display:flex;gap:8px;padding-bottom:10px;overflow-x:auto;">
                {{-- All --}}
                <button wire:click="selectCategory(0); $set('selectedBrand', '')"
                    style="flex-shrink:0;padding:5px 14px;border-radius:99px;font-size:12px;font-weight:700;letter-spacing:.3px;border:1.5px solid {{ ($selectedCategory === 0 && $selectedBrand === '') ? '#0d9488' : '#e2e8f0' }};background:{{ ($selectedCategory === 0 && $selectedBrand === '') ? '#0d9488' : '#fff' }};color:{{ ($selectedCategory === 0 && $selectedBrand === '') ? '#fff' : '#64748b' }};cursor:pointer;transition:all .2s;">
                    All
                </button>
                @foreach ($categories as $cat)
                    @php
                        $palette = ['#6366f1','#8b5cf6','#ec4899','#f97316','#06b6d4','#10b981','#f59e0b','#ef4444','#14b8a6'];
                        $c = $palette[$loop->index % count($palette)];
                        $isA = ($selectedCategory === $cat->id && $selectedBrand === '');
                    @endphp
                    <button wire:click="selectCategory({{ $cat->id }}); $set('selectedBrand', '')"
                        style="flex-shrink:0;padding:5px 14px;border-radius:99px;font-size:12px;font-weight:700;letter-spacing:.3px;border:1.5px solid {{ $isA ? $c : '#e2e8f0' }};background:{{ $isA ? $c : '#fff' }};color:{{ $isA ? '#fff' : '#64748b' }};cursor:pointer;transition:all .2s;">
                        {{ $cat->name }}
                    </button>
                @endforeach
                @foreach ($brands as $brand)
                    @php $isB = ($selectedBrand === $brand); @endphp
                    <button wire:click="$set('selectedBrand', '{{ $brand }}')"
                        style="flex-shrink:0;padding:5px 14px;border-radius:99px;font-size:12px;font-weight:600;letter-spacing:.3px;border:1.5px solid {{ $isB ? '#64748b' : '#e2e8f0' }};background:{{ $isB ? '#64748b' : '#f8fafc' }};color:{{ $isB ? '#fff' : '#94a3b8' }};cursor:pointer;transition:all .2s;">
                        {{ $brand }}
                    </button>
                @endforeach
            </div>
        </div>

        {{-- ── Product Grid ── --}}
        <div style="flex:1;overflow-y:auto;padding:16px;" class="custom-scroll">
            @if ($products->isEmpty())
                <div style="display:flex;flex-direction:column;align-items:center;justify-content:center;height:280px;gap:14px;">
                    <div style="width:72px;height:72px;border-radius:20px;background:#f1f5f9;display:flex;align-items:center;justify-content:center;">
                        <svg width="32" height="32" fill="none" stroke="#cbd5e1" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                    </div>
                    <p style="font-size:15px;font-weight:700;color:#94a3b8;">No products found</p>
                    <p style="font-size:12px;color:#cbd5e1;">Try a different search or category</p>
                </div>
            @else
                <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(150px,1fr));gap:12px;">
                    @foreach ($products as $product)
                        @php
                            $bgColors = ['#fff3e0','#e8f5e9','#e3f2fd','#fce4ec','#f3e5f5','#e0f7fa','#fff8e1','#e8eaf6','#f1f8e9'];
                            $fgColors = ['#f57c00','#388e3c','#1976d2','#c2185b','#7b1fa2','#0097a7','#f9a825','#3949ab','#558b2f'];
                            $ci = ord($product->name[0]) % 9;

                            $stock = $product->batches->sum('quantity') ?? 0;
                            $lowStock = $stock > 0 && $stock <= 5;
                            $outStock = $stock <= 0;
                        @endphp
                        <button wire:click="addToCartById({{ $product->id }})"
                            style="background:#fff;border:1.5px solid #e2e8f0;border-radius:14px;padding:0;overflow:hidden;cursor:pointer;text-align:left;transition:all .2s;position:relative;display:flex;flex-direction:column;"
                            onmouseover="this.style.borderColor='#0d9488';this.style.boxShadow='0 4px 24px rgba(13,148,136,.18)';this.style.transform='translateY(-2px)'"
                            onmouseout="this.style.borderColor='#e2e8f0';this.style.boxShadow='none';this.style.transform='translateY(0)'">

                            {{-- Image area --}}
                            <div style="width:100%;aspect-ratio:1;position:relative;overflow:hidden;background:{{ $bgColors[$ci] }};">
                                {{-- Price badge --}}
                                <div style="position:absolute;top:8px;left:8px;background:#0d9488;color:#fff;font-size:11px;font-weight:800;padding:3px 8px;border-radius:99px;z-index:2;">
                                    ₵{{ number_format($product->selling_price, 0) }}
                                </div>

                                {{-- Stock badge --}}
                                @if ($outStock)
                                    <div style="position:absolute;top:8px;right:8px;background:#ef4444;color:#fff;font-size:9px;font-weight:800;padding:3px 7px;border-radius:99px;z-index:2;letter-spacing:.3px;">OUT</div>
                                @elseif ($lowStock)
                                    <div style="position:absolute;top:8px;right:8px;background:#f97316;color:#fff;font-size:9px;font-weight:800;padding:3px 7px;border-radius:99px;z-index:2;">LOW</div>
                                @endif

                                @if ($product->image_path)
                                    <img src="{{ asset('storage/' . $product->image_path) }}"
                                         alt="{{ $product->name }}"
                                         style="width:100%;height:100%;object-fit:contain;">
                                @else
                                    <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;font-size:38px;font-weight:900;color:{{ $fgColors[$ci] }};">
                                        {{ strtoupper($product->name[0]) }}
                                    </div>
                                @endif

                                {{-- Hover overlay --}}
                                <div style="position:absolute;inset:0;background:rgba(13,148,136,.85);display:flex;align-items:center;justify-content:center;opacity:0;transition:opacity .2s;"
                                    onmouseover="this.style.opacity='1'" onmouseout="this.style.opacity='0'">
                                    <span style="color:#fff;font-size:13px;font-weight:800;letter-spacing:.5px;">+ ADD</span>
                                </div>
                            </div>

                            {{-- Info --}}
                            <div style="padding:8px 10px;flex:1;display:flex;flex-direction:column;gap:2px;">
                                <p style="font-size:12px;font-weight:700;color:#1e293b;line-height:1.3;overflow:hidden;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;">{{ $product->name }}</p>
                                <p style="font-size:10px;color:#94a3b8;font-weight:500;">{{ $product->sku ?? '—' }}</p>
                                @if ($stock > 0)
                                    <p style="font-size:10px;color:{{ $lowStock ? '#f97316' : '#10b981' }};font-weight:600;">{{ $stock }} {{ $product->unit ?? 'units' }}</p>
                                @endif
                            </div>
                        </button>
                    @endforeach
                </div>
            @endif
        </div>

    </main>
</div>

{{-- ══════════════════════════════════════════════════════
     PAY MODAL
══════════════════════════════════════════════════════ --}}
@if ($showPayModal)
<div class="fixed inset-0 z-50 flex items-center justify-center"
     style="background:rgba(0,0,0,.65);backdrop-filter:blur(8px);"
     wire:click.self="$set('showPayModal', false)">
    <div style="background:#fff;border-radius:24px;width:100%;max-width:440px;margin:0 16px;overflow:hidden;box-shadow:0 32px 80px rgba(0,0,0,.3);">

        {{-- Header --}}
        <div style="background:linear-gradient(135deg,#0e2a38 0%,#1a3e52 100%);padding:24px 28px;display:flex;align-items:center;justify-content:space-between;">
            <div>
                <p style="font-size:11px;color:#64748b;font-weight:700;letter-spacing:1px;text-transform:uppercase;margin-bottom:2px;">Payment</p>
                <p style="font-size:32px;font-weight:900;color:#0d9488;letter-spacing:-1px;">₵{{ number_format($total, 2) }}</p>
            </div>
            <button wire:click="$set('showPayModal', false)"
                style="background:#243f52;border:none;cursor:pointer;width:36px;height:36px;border-radius:10px;display:flex;align-items:center;justify-content:center;"
                onmouseover="this.style.background='#334155'" onmouseout="this.style.background='#243f52'">
                <svg width="16" height="16" fill="none" stroke="#94a3b8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <div style="padding:24px 28px;">
            {{-- Payment method --}}
            <p style="font-size:11px;color:#64748b;font-weight:700;letter-spacing:1px;text-transform:uppercase;margin-bottom:10px;">Payment Method</p>
            <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:8px;margin-bottom:20px;">
                @foreach ([
                    ['cash',    'Cash',   'M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z'],
                    ['card',    'Card',   'M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z'],
                    ['mobile',  'Mobile', 'M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z'],
                ] as [$val, $label, $path])
                <button wire:click="$set('paymentMethod', '{{ $val }}')"
                    style="padding:12px 0;border-radius:12px;border:2px solid {{ $paymentMethod === $val ? '#0d9488' : '#e2e8f0' }};background:{{ $paymentMethod === $val ? '#f0fdfb' : '#fff' }};cursor:pointer;display:flex;flex-direction:column;align-items:center;gap:6px;transition:all .2s;">
                    <svg width="18" height="18" fill="none" stroke="{{ $paymentMethod === $val ? '#0d9488' : '#94a3b8' }}" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $path }}"/>
                    </svg>
                    <span style="font-size:12px;font-weight:700;color:{{ $paymentMethod === $val ? '#0d9488' : '#64748b' }};">{{ $label }}</span>
                </button>
                @endforeach
            </div>

            {{-- Tendered --}}
            <p style="font-size:11px;color:#64748b;font-weight:700;letter-spacing:1px;text-transform:uppercase;margin-bottom:8px;">Amount Tendered</p>
            <div style="display:flex;align-items:center;background:#f8fafc;border:2px solid #e2e8f0;border-radius:12px;padding:0 16px;gap:8px;margin-bottom:16px;">
                <span style="font-size:20px;font-weight:800;color:#94a3b8;">₵</span>
                <input type="number" wire:model.live="amountTendered" placeholder="0.00" min="0" step="0.01"
                    style="flex:1;background:transparent;border:none;outline:none;font-size:24px;font-weight:800;color:#1e293b;padding:12px 0;">
            </div>

            {{-- Quick amounts --}}
            @php
                $quickAmounts = [
                    ceil($total / 5) * 5,
                    ceil($total / 10) * 10,
                    ceil($total / 20) * 20,
                    ceil($total / 50) * 50,
                ];
                $quickAmounts = array_unique(array_filter($quickAmounts, fn($a) => $a >= $total));
                sort($quickAmounts);
                $quickAmounts = array_slice($quickAmounts, 0, 4);
            @endphp
            @if(count($quickAmounts))
            <div style="display:flex;gap:6px;flex-wrap:wrap;margin-bottom:16px;">
                @foreach ($quickAmounts as $qa)
                <button wire:click="$set('amountTendered', '{{ $qa }}')"
                    style="flex:1;min-width:60px;padding:7px 0;border-radius:8px;border:1.5px solid #e2e8f0;background:#fff;font-size:12px;font-weight:700;color:#64748b;cursor:pointer;"
                    onmouseover="this.style.borderColor='#0d9488';this.style.color='#0d9488'" onmouseout="this.style.borderColor='#e2e8f0';this.style.color='#64748b'">
                    ₵{{ number_format($qa, 0) }}
                </button>
                @endforeach
            </div>
            @endif

            {{-- Change --}}
            @if ((float) $amountTendered >= $total && $amountTendered !== '')
            <div style="background:#f0fdfb;border:1.5px solid #ccfbf1;border-radius:12px;padding:12px 16px;display:flex;justify-content:space-between;align-items:center;margin-bottom:16px;">
                <span style="font-size:13px;font-weight:700;color:#0d9488;">Change Due</span>
                <span style="font-size:24px;font-weight:900;color:#0d9488;">₵{{ number_format($change, 2) }}</span>
            </div>
            @endif

            {{-- Confirm button --}}
            <button wire:click="checkout"
                style="width:100%;background:#0d9488;border:none;cursor:pointer;padding:16px;border-radius:14px;font-size:17px;font-weight:900;color:#fff;letter-spacing:.5px;display:flex;align-items:center;justify-content:center;gap:10px;transition:background .2s;"
                onmouseover="this.style.background='#0f766e'" onmouseout="this.style.background='#0d9488'">
                <svg width="20" height="20" fill="none" stroke="#fff" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                Confirm Payment
            </button>
        </div>
    </div>
</div>
@endif

{{-- ══════════════════════════════════════════════════════
     RECEIPT MODAL
══════════════════════════════════════════════════════ --}}
@if ($showReceiptModal)
<div class="fixed inset-0 z-50 flex items-center justify-center"
     style="background:rgba(0,0,0,.65);backdrop-filter:blur(8px);">
    <div style="background:#fff;border-radius:24px;width:100%;max-width:400px;margin:0 16px;overflow:hidden;box-shadow:0 32px 80px rgba(0,0,0,.3);text-align:center;">
        <div style="background:linear-gradient(135deg,#0d9488,#059669);padding:32px 28px;">
            <div style="width:60px;height:60px;border-radius:50%;background:rgba(255,255,255,.2);display:flex;align-items:center;justify-content:center;margin:0 auto 12px;">
                <svg width="28" height="28" fill="none" stroke="#fff" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
            <p style="font-size:22px;font-weight:900;color:#fff;margin-bottom:4px;">Sale Complete!</p>
            <p style="font-size:13px;color:rgba(255,255,255,.7);">Transaction recorded successfully</p>
        </div>
        <div style="padding:24px 28px;">
            <p style="font-size:12px;color:#94a3b8;font-weight:600;margin-bottom:14px;">PRINT RECEIPT</p>
            <div style="display:flex;gap:10px;margin-bottom:20px;">
                <a href="{{ $lastSaleId ? route('sales.thermal', $lastSaleId) : '#' }}" target="_blank"
                    style="flex:1;padding:12px 0;border-radius:12px;background:#0d9488;color:#fff;font-size:13px;font-weight:700;text-decoration:none;display:flex;align-items:center;justify-content:center;gap:6px;">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                    Thermal
                </a>
                <a href="{{ $lastSaleId ? route('sales.invoice', $lastSaleId) : '#' }}" target="_blank"
                    style="flex:1;padding:12px 0;border-radius:12px;background:#f8fafc;border:1.5px solid #e2e8f0;color:#64748b;font-size:13px;font-weight:700;text-decoration:none;display:flex;align-items:center;justify-content:center;gap:6px;">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    Invoice
                </a>
            </div>
            <button wire:click="$set('showReceiptModal', false)"
                style="width:100%;background:#f1f5f9;border:none;cursor:pointer;padding:13px;border-radius:12px;font-size:14px;font-weight:700;color:#475569;"
                onmouseover="this.style.background='#e2e8f0'" onmouseout="this.style.background='#f1f5f9'">
                New Sale
            </button>
        </div>
    </div>
</div>
@endif

{{-- Clock Script --}}
<script>
    (function updateClock() {
        const el = document.getElementById('pos-clock');
        if (el) {
            const now = new Date();
            const h = String(now.getHours()).padStart(2,'0');
            const m = String(now.getMinutes()).padStart(2,'0');
            const s = String(now.getSeconds()).padStart(2,'0');
            el.textContent = `${h}:${m}:${s}`;
        }
        setTimeout(updateClock, 1000);
    })();
</script>

<style>
.custom-scroll::-webkit-scrollbar { width: 4px; }
.custom-scroll::-webkit-scrollbar-track { background: transparent; }
.custom-scroll::-webkit-scrollbar-thumb { background: #243f52; border-radius: 4px; }
.custom-scroll::-webkit-scrollbar-thumb:hover { background: #0d9488; }
</style>

</div>
