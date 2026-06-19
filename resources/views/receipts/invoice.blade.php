<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Invoice {{ $sale->receipt_no }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'DejaVu Sans', 'Helvetica Neue', Arial, sans-serif;
            font-size: 12px;
            color: #1a1a1a;
            background: #fff;
            padding: 40px;
        }
        /* Header */
        .header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 32px; }
        .brand-name { font-size: 24px; font-weight: 900; color: #0e2a38; letter-spacing: 2px; text-transform: uppercase; }
        .brand-sub  { font-size: 11px; color: #64748b; margin-top: 2px; }
        .invoice-meta { text-align: right; }
        .invoice-title { font-size: 20px; font-weight: 900; color: #0891b2; text-transform: uppercase; letter-spacing: 1px; }
        .invoice-no   { font-size: 13px; font-weight: 700; color: #334155; margin-top: 4px; }
        .invoice-date { font-size: 11px; color: #64748b; margin-top: 2px; }

        /* Info row */
        .info-row { display: flex; justify-content: space-between; margin-bottom: 28px; }
        .info-box  { width: 48%; }
        .info-box h4 { font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: #94a3b8; margin-bottom: 6px; border-bottom: 1px solid #e2e8f0; padding-bottom: 4px; }
        .info-box p  { font-size: 12px; color: #334155; margin-bottom: 2px; }
        .info-box p.strong { font-weight: 700; color: #0f172a; }

        /* Items table */
        table { width: 100%; border-collapse: collapse; margin-bottom: 24px; }
        thead tr { background: #0e2a38; color: #fff; }
        thead th { padding: 9px 12px; text-align: left; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; }
        thead th.right { text-align: right; }
        tbody tr { border-bottom: 1px solid #f1f5f9; }
        tbody tr:nth-child(even) { background: #f8fafc; }
        tbody td { padding: 8px 12px; font-size: 12px; color: #334155; vertical-align: top; }
        tbody td.right { text-align: right; }
        tbody td.center { text-align: center; }

        /* Totals */
        .totals-wrap { display: flex; justify-content: flex-end; margin-bottom: 32px; }
        .totals-table { width: 280px; }
        .totals-table tr td { padding: 5px 12px; font-size: 12px; color: #475569; }
        .totals-table tr td:last-child { text-align: right; font-weight: 600; }
        .totals-table tr.total-row td { font-size: 15px; font-weight: 900; color: #0e2a38; border-top: 2px solid #0e2a38; padding-top: 8px; }

        /* Payment badge */
        .payment-badge { display: inline-block; padding: 4px 12px; border-radius: 20px; font-size: 11px; font-weight: 700; text-transform: uppercase; background: #dcfce7; color: #166534; letter-spacing: 0.5px; }

        /* Footer */
        .footer { text-align: center; border-top: 1px dashed #cbd5e1; padding-top: 16px; color: #94a3b8; font-size: 11px; }
        .footer strong { color: #334155; }

        /* Divider */
        .divider { border: none; border-top: 1px solid #e2e8f0; margin: 16px 0; }
    </style>
</head>
<body>

    {{-- ── HEADER ── --}}
    <div class="header">
        <div>
            <div class="brand-name">{{ config('app.name', 'SIPAMS') }}</div>
            <div class="brand-sub">Sales & Inventory Management System</div>
            <div style="margin-top:8px; font-size:11px; color:#64748b; line-height:1.6;">
                123 Business Road, Accra, Ghana<br>
                Phone: +233 24 000 0000<br>
                Email: info@sipams.com
            </div>
        </div>
        <div class="invoice-meta">
            <div class="invoice-title">Invoice</div>
            <div class="invoice-no">{{ $sale->receipt_no }}</div>
            <div class="invoice-date">Date: {{ \Carbon\Carbon::parse($sale->date)->format('d M Y, H:i') }}</div>
            <div style="margin-top:8px;">
                <span class="payment-badge">{{ strtoupper(str_replace('_', ' ', $sale->payment_method)) }}</span>
            </div>
        </div>
    </div>

    {{-- ── INFO ROW ── --}}
    <div class="info-row">
        <div class="info-box">
            <h4>Billed To</h4>
            @if($sale->customer)
                <p class="strong">{{ $sale->customer->name }}</p>
                @if($sale->customer->phone) <p>{{ $sale->customer->phone }}</p> @endif
                @if($sale->customer->email) <p>{{ $sale->customer->email }}</p> @endif
            @else
                <p class="strong">Walk-in Customer</p>
            @endif
        </div>
        <div class="info-box" style="text-align:right;">
            <h4>Cashier Details</h4>
            <p class="strong">{{ $sale->user->name ?? 'N/A' }}</p>
            <p>{{ \Carbon\Carbon::parse($sale->date)->format('d M Y') }}</p>
            <p>{{ \Carbon\Carbon::parse($sale->date)->format('H:i:s') }}</p>
        </div>
    </div>

    {{-- ── ITEMS TABLE ── --}}
    <table>
        <thead>
            <tr>
                <th style="width:30px;">#</th>
                <th>Product</th>
                <th class="right" style="width:70px;">Unit Price</th>
                <th class="right" style="width:50px;">Qty</th>
                <th class="right" style="width:80px;">Discount</th>
                <th class="right" style="width:60px;">Tax</th>
                <th class="right" style="width:90px;">Line Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sale->items as $i => $item)
            @php
                $lineBase    = $item->unit_price * $item->quantity;
                $lineDisc    = 0; // per-line discount not stored in DB — show 0
                $lineNet     = $lineBase - $lineDisc;
                $lineTax     = round($lineNet * 0.05, 2);
                $lineTotal   = $item->subtotal;
            @endphp
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>
                    <strong>{{ $item->product->name ?? 'Item' }}</strong>
                    @if($item->product->sku ?? null)
                        <br><span style="font-size:10px; color:#94a3b8;">SKU: {{ $item->product->sku }}</span>
                    @endif
                </td>
                <td class="right">₵{{ number_format($item->unit_price, 2) }}</td>
                <td class="center">{{ $item->quantity }}</td>
                <td class="right">₵{{ number_format($lineDisc, 2) }}</td>
                <td class="right">₵{{ number_format($lineTax, 2) }}</td>
                <td class="right"><strong>₵{{ number_format($lineTotal, 2) }}</strong></td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{-- ── TOTALS ── --}}
    <div class="totals-wrap">
        <table class="totals-table">
            <tr>
                <td>Subtotal</td>
                <td>₵{{ number_format($sale->total_amount, 2) }}</td>
            </tr>
            @if($sale->discount_amount > 0)
            <tr>
                <td>Discount</td>
                <td>-₵{{ number_format($sale->discount_amount, 2) }}</td>
            </tr>
            @endif
            @if($sale->tax_amount > 0)
            <tr>
                <td>VAT (5%)</td>
                <td>₵{{ number_format($sale->tax_amount, 2) }}</td>
            </tr>
            @endif
            <tr class="total-row">
                <td>TOTAL DUE</td>
                <td>₵{{ number_format($sale->net_amount, 2) }}</td>
            </tr>
        </table>
    </div>

    {{-- ── FOOTER ── --}}
    <div class="footer">
        <p><strong>Thank you for your business!</strong></p>
        <p style="margin-top:4px;">This is a computer-generated invoice and requires no signature.</p>
        <p style="margin-top:4px;">{{ config('app.name') }} · Generated {{ now()->format('d M Y H:i') }}</p>
    </div>

</body>
</html>
