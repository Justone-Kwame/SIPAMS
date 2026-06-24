<!DOCTYPE html>
<html>
<head>
    <title>Purchase {{ $purchase->po_number }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; color: #1f2937; }
        h2 { margin: 0 0 4px; }
        .meta { margin-bottom: 16px; }
        .meta div { margin: 2px 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 8px; }
        th, td { border: 1px solid #ddd; padding: 6px; text-align: left; }
        th { background-color: #f2f2f2; }
        .right { text-align: right; }
        .totals { margin-top: 12px; width: 40%; float: right; }
        .totals td { border: none; padding: 3px 6px; }
    </style>
</head>
<body>
    <h2>Purchase Order</h2>
    <div class="meta">
        <div><strong>Reference:</strong> {{ $purchase->po_number }}</div>
        <div><strong>Date:</strong> {{ \Carbon\Carbon::parse($purchase->order_date)->format('Y-m-d') }}</div>
        <div><strong>Supplier:</strong> {{ $purchase->supplier->name ?? '—' }}</div>
        <div><strong>Status:</strong> {{ ucfirst($purchase->status) }}</div>
        <div><strong>Payment Status:</strong> {{ $paymentStatus }}</div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Product</th>
                <th class="right">Qty</th>
                <th class="right">Unit Cost</th>
                <th class="right">Total</th>
            </tr>
        </thead>
        <tbody>
            @forelse($purchase->items as $item)
                <tr>
                    <td>{{ $item->product->name ?? '—' }}</td>
                    <td class="right">{{ $item->quantity_ordered }}</td>
                    <td class="right">GHS {{ number_format($item->unit_cost, 2) }}</td>
                    <td class="right">GHS {{ number_format($item->total_cost, 2) }}</td>
                </tr>
            @empty
                <tr><td colspan="4">No items.</td></tr>
            @endforelse
        </tbody>
    </table>

    <table class="totals">
        <tr><td>Total</td><td class="right">GHS {{ number_format($purchase->total_amount, 2) }}</td></tr>
        <tr><td>Paid</td><td class="right">GHS {{ number_format($purchase->paid_amount, 2) }}</td></tr>
        <tr><td>Due</td><td class="right">GHS {{ number_format(max((float) $purchase->total_amount - (float) $purchase->paid_amount, 0), 2) }}</td></tr>
    </table>
</body>
</html>
