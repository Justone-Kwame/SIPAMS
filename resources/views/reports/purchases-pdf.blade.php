<!DOCTYPE html>
<html>
<head>
    <title>Purchases</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 16px; }
        th, td { border: 1px solid #ddd; padding: 6px; text-align: left; }
        th { background-color: #f2f2f2; }
        .header { margin-bottom: 12px; }
        .right { text-align: right; }
    </style>
</head>
<body>
    <div class="header">
        <h2>All Purchases</h2>
        <p>Generated: {{ now()->format('Y-m-d H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Reference</th>
                <th>Supplier</th>
                <th>Status</th>
                <th class="right">Total</th>
                <th class="right">Paid</th>
                <th class="right">Due</th>
                <th>Payment Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($purchases as $po)
                @php $due = max((float) $po->total_amount - (float) $po->paid_amount, 0); @endphp
                <tr>
                    <td>{{ \Carbon\Carbon::parse($po->order_date)->format('Y-m-d') }}</td>
                    <td>{{ $po->po_number }}</td>
                    <td>{{ $po->supplier->name ?? '—' }}</td>
                    <td>{{ ucfirst($po->status) }}</td>
                    <td class="right">GHS {{ number_format($po->total_amount, 2) }}</td>
                    <td class="right">GHS {{ number_format($po->paid_amount, 2) }}</td>
                    <td class="right">GHS {{ number_format($due, 2) }}</td>
                    <td>{{ $paymentStatus($po) }}</td>
                </tr>
            @empty
                <tr><td colspan="8">No purchases found.</td></tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
