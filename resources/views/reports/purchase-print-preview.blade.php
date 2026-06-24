<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Purchase Order {{ $purchase->po_number }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Arial, sans-serif; font-size: 12px; color: #1f2937; background: #f3f4f6; }

        .page-wrapper { max-width: 860px; margin: 30px auto; background: #fff; border-radius: 10px; box-shadow: 0 4px 24px rgba(0,0,0,.10); padding: 40px; }

        /* ── Print bar ── */
        .print-bar {
            display: flex; align-items: center; gap: 12px;
            margin-bottom: 24px; padding-bottom: 16px;
            border-bottom: 1px solid #e5e7eb;
        }
        .btn-print {
            display: inline-flex; align-items: center; gap-8px;
            gap: 8px; padding: 9px 22px;
            background: #0d9488; color: #fff; border: none;
            border-radius: 6px; font-size: 13px; font-weight: 600;
            cursor: pointer; text-decoration: none;
        }
        .btn-print:hover { background: #0f766e; }
        .btn-close {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 9px 18px; background: #f1f5f9; color: #374151;
            border: 1px solid #e2e8f0; border-radius: 6px;
            font-size: 13px; font-weight: 600; cursor: pointer;
            text-decoration: none;
        }

        /* ── Header ── */
        .header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 24px; }
        .logo-circle {
            width: 56px; height: 56px; border-radius: 50%;
            background: #0d9488; color: #fff;
            font-size: 22px; font-weight: bold;
            display: flex; align-items: center; justify-content: center;
        }
        .doc-title { font-size: 28px; font-weight: 800; color: #0d9488; letter-spacing: 1px; }
        .po-badge {
            display: inline-block; margin-top: 6px;
            border: 1px solid #cbd5e1; border-radius: 6px;
            padding: 4px 16px; font-size: 13px; font-weight: 700; color: #374151;
        }

        /* ── Meta ── */
        .meta-grid { display: grid; grid-template-columns: auto 1fr; column-gap: 12px; row-gap: 4px; justify-content: end; text-align: right; margin-bottom: 18px; }
        .meta-label { color: #6b7280; font-size: 12px; }
        .meta-value { font-weight: 700; font-size: 12px; }
        .badge {
            display: inline-block; padding: 2px 12px; border-radius: 5px;
            font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: .3px;
        }
        .badge-received { background: #d1fae5; color: #065f46; }
        .badge-pending  { background: #fef3c7; color: #92400e; }
        .badge-ordered  { background: #dbeafe; color: #1e40af; }
        .badge-paid     { background: #ccfbf1; color: #0f766e; }
        .badge-unpaid   { background: #fee2e2; color: #991b1b; }
        .badge-partial  { background: #fef3c7; color: #92400e; }

        .divider { border: none; border-top: 1px solid #e5e7eb; margin: 16px 0; }

        /* ── Info panels ── */
        .panels { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 20px; }
        .panel { border: 1px solid #e2e8f0; border-radius: 8px; overflow: hidden; }
        .panel-header {
            background: #0d9488; color: #fff;
            font-size: 11px; font-weight: 700; letter-spacing: .6px;
            padding: 8px 14px; text-transform: uppercase;
        }
        .panel-body { padding: 12px 14px; }
        .company-name { font-size: 15px; font-weight: 700; color: #111827; margin-bottom: 8px; }
        .info-row { display: flex; gap: 8px; margin-bottom: 3px; font-size: 11px; }
        .info-label { color: #6b7280; min-width: 58px; }
        .info-val { color: #374151; }

        /* ── Items table ── */
        .items-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; border-radius: 8px; overflow: hidden; }
        .items-table thead tr { background: #0d9488; color: #fff; }
        .items-table thead th {
            padding: 10px 12px; font-size: 11px; font-weight: 700;
            letter-spacing: .5px; text-transform: uppercase; border: none;
        }
        .items-table tbody tr:nth-child(even) { background: #f8fafc; }
        .items-table tbody tr:hover { background: #f0fdfa; }
        .items-table tbody td { padding: 10px 12px; border-bottom: 1px solid #e5e7eb; }
        .product-name { font-weight: 600; color: #111827; }
        .product-code { font-size: 10px; color: #9ca3af; margin-top: 2px; }
        .text-right { text-align: right; }
        .disc-val { color: #ef4444; font-weight: 600; }
        .total-val { color: #0d9488; font-weight: 700; }

        /* ── Totals ── */
        .totals-wrap { display: flex; justify-content: flex-end; margin-bottom: 30px; }
        .totals-table { width: 340px; border-collapse: collapse; border: 1px solid #e2e8f0; border-radius: 8px; overflow: hidden; }
        .totals-table td { padding: 9px 14px; border-bottom: 1px solid #e2e8f0; font-size: 12px; }
        .t-label { color: #374151; }
        .t-val { text-align: right; font-weight: 700; color: #111827; }
        .row-total   { background: #0d9488; }
        .row-total td { color: #fff !important; font-weight: 800; font-size: 13px; border-color: #0d9488; }
        .row-paid    { background: #d1fae5; }
        .row-paid td { border-color: #a7f3d0; }
        .row-due     { background: #fef9c3; }
        .row-due td  { border-color: #fde68a; }
        .disc-negative { color: #ef4444; }

        /* ── Thank you ── */
        .thankyou { text-align: center; color: #0d9488; font-size: 15px; font-style: italic; font-weight: 700; margin-top: 10px; }

        /* ── Print media ── */
        @media print {
            body { background: #fff; }
            .page-wrapper { box-shadow: none; border-radius: 0; margin: 0; padding: 24px; max-width: 100%; }
            .print-bar { display: none !important; }
        }
    </style>
</head>
<body>
<div class="page-wrapper">

    {{-- ── Print bar ── --}}
    <div class="print-bar">
        <button class="btn-print" onclick="window.print()">
            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
            Print
        </button>
        <a href="{{ route('purchases.pdf', $purchase->id) }}" class="btn-close">
            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            Download PDF
        </a>
        <a href="{{ route('purchases.index') }}" class="btn-close">← Back to Purchases</a>
    </div>

    {{-- ── Header ── --}}
    <div class="header">
        <div class="logo-circle">{{ strtoupper(substr(config('app.name', 'S'), 0, 1)) }}</div>
        <div style="text-align:right;">
            <div class="doc-title">PURCHASE ORDER</div>
            <div class="po-badge">{{ $purchase->po_number }}</div>
        </div>
    </div>

    {{-- ── Meta info ── --}}
    @php
        $due = max((float) $purchase->total_amount - (float) $purchase->paid_amount, 0);
        $statusClass = match(strtolower($purchase->status)) {
            'received' => 'badge-received',
            'ordered'  => 'badge-ordered',
            default    => 'badge-pending',
        };
        $payClass = match(strtolower($paymentStatus)) {
            'paid'    => 'badge-paid',
            'unpaid'  => 'badge-unpaid',
            'partial' => 'badge-partial',
            default   => 'badge-unpaid',
        };
    @endphp
    <div style="display:flex; flex-direction:column; align-items:flex-end; gap:4px; margin-bottom:18px;">
        <div style="display:grid; grid-template-columns: auto 1fr; column-gap:16px; row-gap:5px; text-align:right;">
            <span style="color:#6b7280;">Date:</span>
            <span style="font-weight:700;">{{ \Carbon\Carbon::parse($purchase->order_date)->format('Y-m-d H:i:s') }}</span>
            <span style="color:#6b7280;">Order #:</span>
            <span style="font-weight:700;">{{ $purchase->po_number }}</span>
            <span style="color:#6b7280;">Status:</span>
            <span><span class="badge {{ $statusClass }}">{{ strtoupper($purchase->status) }}</span></span>
            <span style="color:#6b7280;">Payment:</span>
            <span><span class="badge {{ $payClass }}">{{ strtoupper($paymentStatus) }}</span></span>
        </div>
    </div>

    <hr class="divider">

    {{-- ── Info panels ── --}}
    <div class="panels">
        <div class="panel">
            <div class="panel-header">Supplier Info</div>
            <div class="panel-body">
                <div class="company-name">{{ $purchase->supplier->name ?? '—' }}</div>
                <div class="info-row"><span class="info-label">Phone:</span><span class="info-val">{{ $purchase->supplier->phone ?? '—' }}</span></div>
                <div class="info-row"><span class="info-label">Email:</span><span class="info-val">{{ $purchase->supplier->email ?? '—' }}</span></div>
                <div class="info-row"><span class="info-label">Address:</span><span class="info-val">{{ $purchase->supplier->address ?? '—' }}</span></div>
            </div>
        </div>
        <div class="panel">
            <div class="panel-header">Company Info</div>
            <div class="panel-body">
                <div class="company-name">{{ config('app.name', 'Company') }}</div>
                <div class="info-row"><span class="info-label">Phone:</span><span class="info-val">{{ config('company.phone', '—') }}</span></div>
                <div class="info-row"><span class="info-label">Email:</span><span class="info-val">{{ config('company.email', env('MAIL_FROM_ADDRESS', '—')) }}</span></div>
                <div class="info-row"><span class="info-label">Address:</span><span class="info-val">{{ config('company.address', '—') }}</span></div>
            </div>
        </div>
    </div>

    {{-- ── Items table ── --}}
    <table class="items-table">
        <thead>
            <tr>
                <th style="text-align:left;">Product</th>
                <th class="text-right">Cost</th>
                <th class="text-right">Qty</th>
                <th class="text-right">Disc</th>
                <th class="text-right">Tax</th>
                <th class="text-right">Total</th>
            </tr>
        </thead>
        <tbody>
            @forelse($purchase->items as $item)
                <tr>
                    <td>
                        <div class="product-name">{{ $item->product->name ?? '—' }}</div>
                        @if($item->product->code ?? null)
                            <div class="product-code">Code: {{ $item->product->code }}</div>
                        @endif
                    </td>
                    <td class="text-right">{{ number_format($item->unit_cost, 2) }}</td>
                    <td class="text-right">{{ number_format($item->quantity_ordered, 2) }} pc</td>
                    <td class="text-right disc-val">0.00</td>
                    <td class="text-right">0.00</td>
                    <td class="text-right total-val">{{ number_format($item->total_cost, 2) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align:center; color:#9ca3af; padding:24px;">No items found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- ── Totals ── --}}
    <div class="totals-wrap">
        <table class="totals-table">
            <tr>
                <td class="t-label">Subtotal:</td>
                <td class="t-val">GHS {{ number_format($purchase->total_amount, 2) }}</td>
            </tr>
            <tr>
                <td class="t-label">Order Tax:</td>
                <td class="t-val">GHS 0.00</td>
            </tr>
            <tr>
                <td class="t-label">Discount:</td>
                <td class="t-val"><span class="disc-negative">- GHS 0.00</span></td>
            </tr>
            <tr>
                <td class="t-label">Shipping:</td>
                <td class="t-val">GHS 0.00</td>
            </tr>
            <tr class="row-total">
                <td>TOTAL:</td>
                <td style="text-align:right;">GHS {{ number_format($purchase->total_amount, 2) }}</td>
            </tr>
            <tr class="row-paid">
                <td class="t-label">Paid Amount:</td>
                <td class="t-val">GHS {{ number_format($purchase->paid_amount, 2) }}</td>
            </tr>
            <tr class="row-due">
                <td class="t-label">Amount Due:</td>
                <td class="t-val">GHS {{ number_format($due, 2) }}</td>
            </tr>
        </table>
    </div>

    <div class="thankyou">Thank you for your business!</div>

</div>
</body>
</html>
