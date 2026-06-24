<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Print Labels</title>
    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.6/dist/JsBarcode.all.min.js"></script>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { background: #f3f4f6; font-family: Arial, sans-serif; }

        /* ── Screen wrapper ── */
        .screen-bar {
            display: flex; align-items: center; gap: 12px;
            padding: 12px 20px; background: #fff;
            border-bottom: 1px solid #e5e7eb;
        }
        .btn-print {
            display: inline-flex; align-items: center; gap: 8px;
            padding: 9px 22px; background: #0d9488; color: #fff;
            border: none; border-radius: 6px; font-size: 13px;
            font-weight: 700; cursor: pointer;
        }
        .btn-print:hover { background: #0f766e; }
        .btn-back {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 9px 16px; background: #f1f5f9; color: #374151;
            border: 1px solid #e2e8f0; border-radius: 6px;
            font-size: 13px; font-weight: 600;
            text-decoration: none; cursor: pointer;
        }
        .page-count { font-size: 13px; color: #6b7280; margin-left: 4px; }

        /* ── A4 sheet ── */
        .sheet-wrap { padding: 24px; display: flex; flex-direction: column; gap: 24px; }
        .sheet {
            width: 210mm; margin: 0 auto;
            background: #fff; border: 1px solid #d1d5db;
            box-shadow: 0 2px 8px rgba(0,0,0,.08);
        }

        /* ── Label grid ── */
        .labels-40 .label-grid { display: grid; grid-template-columns: repeat(4, 1fr); }
        .labels-24 .label-grid { display: grid; grid-template-columns: repeat(3, 1fr); }
        .labels-10 .label-grid { display: grid; grid-template-columns: repeat(2, 1fr); }

        /* ── Single label ── */
        .label {
            border: 1px dashed #d1d5db;
            display: flex; flex-direction: column;
            align-items: center; justify-content: center;
            text-align: center;
            overflow: hidden;
            padding: 4px 4px 2px;
        }
        .labels-40 .label { height: 25.4mm; }
        .labels-24 .label { height: 33.86mm; }
        .labels-10 .label { height: 27.94mm; }

        .label-name {
            font-size: 7pt; font-weight: 700; text-transform: uppercase;
            line-height: 1.1; color: #111;
            max-height: 2.4em; overflow: hidden;
            word-break: break-word;
            width: 100%;
        }
        .labels-24 .label-name,
        .labels-10 .label-name { font-size: 8.5pt; }

        .label-price {
            font-size: 7pt; color: #374151; font-weight: 600;
            margin-top: 1px;
        }
        .labels-24 .label-price,
        .labels-10 .label-price { font-size: 8.5pt; }

        .label svg { max-width: 100%; }

        .label-code {
            font-size: 6.5pt; color: #374151;
            margin-top: 1px; letter-spacing: 0.3px;
        }
        .labels-24 .label-code,
        .labels-10 .label-code { font-size: 8pt; }

        /* ── Print media ── */
        @media print {
            body { background: #fff; }
            .screen-bar { display: none !important; }
            .sheet-wrap { padding: 0; gap: 0; }
            .sheet { border: none; box-shadow: none; margin: 0; width: 100%; page-break-after: always; }
            .sheet:last-child { page-break-after: auto; }
            .label { border-color: #ccc; }
        }
    </style>
</head>
<body>

{{-- ── Screen top bar ── --}}
<div class="screen-bar">
    <button class="btn-print" onclick="window.print()">
        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
        </svg>
        Print
    </button>
    <a href="{{ url('/products/print-labels') }}" class="btn-back">← Back</a>
    <span class="page-count">{{ $pageCount }} {{ Str::plural('Page', $pageCount) }}</span>
</div>

{{-- ── Sheets ── --}}
@php
    $cols = match($paperSize) { '24_a4' => 3, '10_a4' => 2, default => 4 };
    $rows = match($paperSize) { '24_a4' => 8, '10_a4' => 5, default => 10 };
    $perSheet = $cols * $rows;
    $gridClass = match($paperSize) { '24_a4' => 'labels-24', '10_a4' => 'labels-10', default => 'labels-40' };

    // Expand labels: repeat each product `quantity` times
    $allLabels = [];
    foreach ($products as $product) {
        $qty = max(1, (int) ($product['quantity'] ?? 1));
        for ($i = 0; $i < $qty; $i++) {
            $allLabels[] = $product;
        }
    }

    $sheets = array_chunk($allLabels, $perSheet);
@endphp

<div class="sheet-wrap">
@foreach($sheets as $sheetIndex => $sheetLabels)
    <div class="sheet {{ $gridClass }}">
        <div class="label-grid">
            @foreach($sheetLabels as $li => $label)
                @php $code = $label['sku'] ?? ''; @endphp
                <div class="label">
                    <div class="label-name">{{ $label['name'] }}</div>
                    @if($displayPrice)
                        <div class="label-price">GHS {{ number_format($label['selling_price'], 2) }}</div>
                    @endif
                    @if($code)
                        <svg class="barcode-svg" data-code="{{ $code }}"></svg>
                        <div class="label-code">{{ $code }}</div>
                    @else
                        <div class="label-code" style="color:#ef4444;">No barcode</div>
                    @endif
                </div>
            @endforeach

            {{-- Fill remaining cells to complete the grid row --}}
            @php $remainder = count($sheetLabels) % $cols; @endphp
            @if($remainder !== 0)
                @for($f = 0; $f < ($cols - $remainder); $f++)
                    <div class="label"></div>
                @endfor
            @endif
        </div>
    </div>
@endforeach
</div>

<script>
    function renderBarcodes() {
        document.querySelectorAll('.barcode-svg').forEach(function(el) {
            var code = el.dataset.code;
            if (!code) return;
            try {
                JsBarcode(el, code, {
                    format: 'CODE128',
                    width: 1.1,
                    height: {{ $paperSize === '40_a4' ? 22 : ($paperSize === '24_a4' ? 30 : 38) }},
                    displayValue: false,
                    margin: 0,
                    background: '#ffffff',
                    lineColor: '#000000',
                });
            } catch(e) {
                el.style.display = 'none';
            }
        });
        @if($autoPrint)
        setTimeout(function() { window.print(); }, 600);
        @endif
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', renderBarcodes);
    } else {
        renderBarcodes();
    }
</script>
</body>
</html>
