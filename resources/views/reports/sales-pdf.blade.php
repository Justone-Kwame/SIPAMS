<!DOCTYPE html>
<html>
<head>
    <title>Sales Report - {{ $startDate }} to {{ $endDate }}</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .header { margin-bottom: 30px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Sales Report</h1>
        <p>Period: {{ $startDate }} - {{ $endDate }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Receipt No</th>
                <th>Date</th>
                <th>Items Count</th>
                <th>Total Amount</th>
                <th>Profit</th>
                <th>Cashier</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sales as $sale)
            <tr>
                <td>{{ $sale->receipt_no }}</td>
                <td>{{ $sale->date }}</td>
                <td>{{ $sale->items->count() }}</td>
                <td>₵{{ number_format($sale->net_amount, 2) }}</td>
                <td>₵{{ number_format($sale->items->sum('profit'), 2) }}</td>
                <td>{{ $sale->user->name ?? '—' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
