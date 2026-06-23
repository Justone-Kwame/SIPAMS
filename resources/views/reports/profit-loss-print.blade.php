<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Profit and Loss — {{ $pl['start_date'] }} to {{ $pl['end_date'] }}</title>
    <style>
        * { box-sizing: border-box; }
        body { margin: 0; padding: 28px; background: #f5f6f8; font-family: Arial, Helvetica, sans-serif; }
        .toolbar { max-width: 760px; margin: 0 auto 18px; display: flex; justify-content: flex-end; gap: 10px; }
        .btn { display: inline-block; padding: 8px 16px; border-radius: 6px; font-size: 13px; font-weight: 600; cursor: pointer; border: 1px solid transparent; text-decoration: none; }
        .btn-print { background: #0d9488; color: #fff; }
        .btn-print:hover { background: #0f766e; }
        .btn-back { background: #fff; color: #374151; border-color: #d1d5db; }
        .btn-back:hover { background: #f3f4f6; }
        .sheet { max-width: 760px; margin: 0 auto; background: #fff; padding: 28px 28px 36px; border: 1px solid #e5e7eb; box-shadow: 0 1px 3px rgba(0,0,0,0.06); }
        @media print {
            body { background: #fff; padding: 0; }
            .toolbar { display: none; }
            .sheet { border: none; box-shadow: none; max-width: none; padding: 0; }
        }
    </style>
</head>
<body>
    <div class="toolbar">
        @isset($backUrl)
            <a href="{{ $backUrl }}" class="btn btn-back">Back</a>
        @endisset
        <a href="javascript:window.print()" class="btn btn-print">Print</a>
    </div>

    <div class="sheet">
        @include('reports.partials.profit-loss-statement', ['pl' => $pl])
    </div>

    @if(request()->boolean('autoprint'))
        <script>window.addEventListener('load', () => window.print());</script>
    @endif
</body>
</html>
