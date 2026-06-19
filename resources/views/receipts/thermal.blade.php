<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Receipt {{ $sale->receipt_no }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 10px;
            color: #000;
            margin: 0;
            padding: 10px;
            width: 100%;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-left { text-align: left; }
        .font-bold { font-weight: bold; }
        .margin-bottom { margin-bottom: 10px; }
        .divider { border-top: 1px dashed #000; margin: 5px 0; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 2px 0; }
    </style>
</head>
<body>
    <div class="text-center margin-bottom">
        <h2 style="margin: 0;">SIPAMS Store</h2>
        <p style="margin: 2px 0;">123 Business Road</p>
        <p style="margin: 2px 0;">Phone: +1 234 567 890</p>
    </div>

    <div class="divider"></div>

    <div class="margin-bottom">
        <p style="margin: 2px 0;">Receipt: {{ $sale->receipt_no }}</p>
        <p style="margin: 2px 0;">Date: {{ \Carbon\Carbon::parse($sale->date)->format('d M Y H:i') }}</p>
        <p style="margin: 2px 0;">Cashier: {{ $sale->user->name ?? 'Admin' }}</p>
    </div>

    <div class="divider"></div>

    <table class="margin-bottom">
        <thead>
            <tr>
                <th class="text-left">Item</th>
                <th class="text-center">Qty</th>
                <th class="text-right">Unit</th>
                <th class="text-right">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sale->items as $item)
            <tr>
                <td class="text-left">{{ $item->product->name ?? 'Item' }}</td>
                <td class="text-center">{{ $item->quantity }}</td>
                <td class="text-right">₵{{ number_format($item->unit_price, 2) }}</td>
                <td class="text-right">₵{{ number_format($item->subtotal, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="divider"></div>

    <table class="margin-bottom font-bold">
        <tr>
            <td class="text-left">Subtotal:</td>
            <td class="text-right">₵{{ number_format($sale->total_amount, 2) }}</td>
        </tr>
        @if($sale->discount_amount > 0)
        <tr>
            <td class="text-left">Discount:</td>
            <td class="text-right">-₵{{ number_format($sale->discount_amount, 2) }}</td>
        </tr>
        @endif
        @if($sale->tax_amount > 0)
        <tr>
            <td class="text-left">Tax:</td>
            <td class="text-right">₵{{ number_format($sale->tax_amount, 2) }}</td>
        </tr>
        @endif
        <tr>
            <td class="text-left" style="font-size: 12px;">Total:</td>
            <td class="text-right" style="font-size: 12px;">₵{{ number_format($sale->net_amount, 2) }}</td>
        </tr>
    </table>

    <div class="divider"></div>

    <div class="text-center margin-bottom">
        <p style="margin: 2px 0;">Payment Method: {{ strtoupper($sale->payment_method) }}</p>
    </div>

    <div class="text-center" style="margin-top: 20px;">
        <p style="margin: 2px 0;">Thank you for your business!</p>
        <p style="margin: 2px 0;">Please come again.</p>
    </div>
</body>
</html>
