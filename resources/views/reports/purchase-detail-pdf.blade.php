<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Purchase Order {{ $purchase->po_number }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: DejaVu Sans, Arial, sans-serif; font-size: 12px; color: #1f2937; background: #fff; }

        /* ── Header ── */
        .header { display: table; width: 100%; margin-bottom: 20px; }
        .header-left { display: table-cell; vertical-align: top; width: 50%; }
        .header-right { display: table-cell; vertical-align: top; width: 50%; text-align: right; }
        .logo-circle {
            width: 54px; height: 54px; border-radius: 50%;
            background: #0d9488;
            color: #fff; font-size: 22px; font-weight: bold;
            text-align: center; line-height: 54px;
        }
        .doc-title {
            font-size: 26px; font-weight: bold;
            color: #0d9488; letter-spacing: 1px;
        }
        .po-badge {
            display: inline-block; margin-top: 6px;
            border: 1px solid #cbd5e1; border-radius: 4px;
            padding: 4px 14px; font-size: 13px;
            font-weight: bold; color: #374151;
        }

        /* ── Meta info ── */
        .meta-table { width: 100%; margin-bottom: 18px; }
        .meta-table td { padding: 3px 6px; font-size: 12px; }
        .meta-label { color: #6b7280; text-align: right; width: 90px; }
        .meta-value { font-weight: bold; text-align: right; }
        .badge {
            display: inline-block; padding: 2px 10px; border-radius: 4px;
            font-size: 11px; font-weight: bold; text-transform: uppercase;
        }
        .badge-received { background: #d1fae5; color: #065f46; }
        .badge-pending  { background: #fef3c7; color: #92400e; }
        .badge-ordered  { background: #dbeafe; color: #1e40af; }
        .badge-paid     { background: #ccfbf1; color: #0f766e; }
        .badge-unpaid   { background: #fee2e2; color: #991b1b; }
        .badge-partial  { background: #fef3c7; color: #92400e; }

        /* ── Info panels ── */
        .panels { display: table; width: 100%; margin-bottom: 18px; border-collapse: separate; border-spacing: 8px 0; }
        .panel { display: table-cell; width: 50%; border: 1px solid #e2e8f0; border-radius: 6px; vertical-align: top; }
        .panel-header {
            background: #0d9488; color: #fff;
            font-size: 11px; font-weight: bold;
            letter-spacing: 0.5px; padding: 7px 12px;
            border-radius: 5px 5px 0 0;
        }
        .panel-body { padding: 10px 12px; }
        .panel-body .company-name { font-size: 14px; font-weight: bold; color: #111827; margin-bottom: 6px; }
        .info-row { display: table; width: 100%; margin-bottom: 3px; }
        .info-label { display: table-cell; color: #6b7280; width: 70px; font-size: 11px; }
        .info-val { display: table-cell; color: #1f2937; font-size: 11px; }

        /* ── Items table ── */
        .items-table { width: 100%; border-collapse: collapse; margin-bottom: 16px; }
        .items-table thead tr { background: #0d9488; color: #fff; }
        .items-table thead th {
            padding: 8px 10px; font-size: 11px;
            font-weight: bold; letter-spacing: 0.4px;
            text-transform: uppercase; border: none;
        }
        .items-table tbody tr:nth-child(even) { background: #f8fafc; }
        .items-table tbody td { padding: 9px 10px; border-bottom: 1px solid #e5e7eb; vertical-align: top; }
        .product-name { font-weight: bold; color: #111827; }
        .product-code { font-size: 10px; color: #9ca3af; margin-top: 2px; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .disc-val { color: #ef4444; font-weight: bold; }
        .total-val { color: #0d9488; font-weight: bold; }

        /* ── Totals ── */
        .totals-wrap { width: 55%; float: right; margin-top: 4px; }
        .totals-table { width: 100%; border-collapse: collapse; }
        .totals-table td { padding: 7px 12px; font-size: 12px; border: 1px solid #e2e8f0; }
        .totals-table .t-label { color: #374151; }
        .totals-table .t-val { text-align: right; font-weight: bold; color: #111827; }
        .row-total   { background: #0d9488; color: #fff !important; }
        .row-total td { border-color: #0d9488; color: #fff; font-weight: bold; font-size: 13px; }
        .row-paid    { background: #d1fae5; }
        .row-paid td { border-color: #a7f3d0; }
        .row-due     { background: #fef9c3; }
        .row-due td  { border-color: #fde68a; }
        .disc-negative { color: #ef4444; }

        /* ── Thank you ── */
        .thankyou { margin-top: 28px; text-align: center; color: #0d9488; font-size: 14px; font-style: italic; font-weight: bold; clear: both; }
        .divider { border: none; border-top: 1px solid #e2e8f0; margin: 14px 0; }
    </style>
</head>
<body>

    {{-- ── Header ── --}}
    <div class="header">
        <div class="header-left">
            <div class="logo-circle">{{ strtoupper(substr(config('app.name', 'S'), 0, 1)) }}</div>
        </div>
        <div class="header-right">
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
    <table class="meta-table">
        <tr>
            <td class="meta-label">Date:</td>
            <td class="meta-value">{{ \Carbon\Carbon::parse($purchase->order_date)->format('Y-m-d H:i:s') }}</td>
        </tr>
        <tr>
            <td class="meta-label">Order #:</td>
            <td class="meta-value">{{ $purchase->po_number }}</td>
        </tr>
        <tr>
            <td class="meta-label">Status:</td>
            <td class="meta-value"><span class="badge {{ $statusClass }}">{{ strtoupper($purchase->status) }}</span></td>
        </tr>
        <tr>
            <td class="meta-label">Payment:</td>
            <td class="meta-value"><span class="badge {{ $payClass }}">{{ strtoupper($paymentStatus) }}</span></td>
        </tr>
    </table>

    <hr class="divider">

    {{-- ── Info panels ── --}}
    <table class="panels">
        <tr>
            <td class="panel">
                <div class="panel-header">SUPPLIER INFO</div>
                <div class="panel-body">
                    <div class="company-name">{{ $purchase->supplier->name ?? '—' }}</div>
                    <div class="info-row"><span class="info-label">Phone:</span><span class="info-val">{{ $purchase->supplier->phone ?? '—' }}</span></div>
                    <div class="info-row"><span class="info-label">Email:</span><span class="info-val">{{ $purchase->supplier->email ?? '—' }}</span></div>
                    <div class="info-row"><span class="info-label">Address:</span><span class="info-val">{{ $purchase->supplier->address ?? '—' }}</span></div>
                </div>
            </td>
            <td style="width: 10px;"></td>
            <td class="panel">
                <div class="panel-header">COMPANY INFO</div>
                <div class="panel-body">
                    <div class="company-name">{{ config('app.name', 'Company') }}</div>
                    <div class="info-row"><span class="info-label">Phone:</span><span class="info-val">{{ config('company.phone', '—') }}</span></div>
                    <div class="info-row"><span class="info-label">Email:</span><span class="info-val">{{ config('company.email', env('MAIL_FROM_ADDRESS', '—')) }}</span></div>
                    <div class="info-row"><span class="info-label">Address:</span><span class="info-val">{{ config('company.address', '—') }}</span></div>
                </div>
            </td>
        </tr>
    </table>

    {{-- ── Items table ── --}}
    <table class="items-table">
        <thead>
            <tr>
                <th style="text-align:left;">PRODUCT</th>
                <th class="text-right">COST</th>
                <th class="text-right">QTY</th>
                <th class="text-right">DISC</th>
                <th class="text-right">TAX</th>
                <th class="text-right">TOTAL</th>
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
                    <td colspan="6" style="text-align:center; color:#9ca3af; padding: 20px;">No items found.</td>
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

    <div style="clear:both;"></div>
    <div class="thankyou">Thank you for your business!</div>

</body>
</html>
