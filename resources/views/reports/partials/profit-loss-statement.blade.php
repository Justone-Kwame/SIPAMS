@php
    // Expects: $pl (array from AccountingService::getProfitLoss)
    $currency = $currency ?? '₵';
    $money = fn ($v) => $currency . ' ' . number_format((float) $v, 2);

    $rowLabel  = 'padding:11px 16px; border-top:1px solid #e8e8e8; color:#1f2937;';
    $rowAmount = 'padding:11px 16px; border-top:1px solid #e8e8e8; text-align:right; background:#fbfbfb; color:#1f2937; white-space:nowrap;';
    $spacer    = 'padding:6px 16px; background:#ffffff;';

    $hiLabel  = fn ($bg) => "padding:12px 16px; border-top:1px solid #e0e0e0; font-weight:700; color:#1f2937; background:{$bg};";
    $hiAmount = fn ($bg) => "padding:12px 16px; border-top:1px solid #e0e0e0; text-align:right; font-weight:700; color:#1f2937; background:{$bg}; white-space:nowrap;";
@endphp

<div class="pl-statement" style="max-width:760px; margin:0 auto; font-family:Arial, Helvetica, sans-serif; color:#1f2937;">
    <h2 style="font-size:15px; font-weight:700; margin:0 0 14px 2px;">Reports / Profit and Loss</h2>

    <table style="width:100%; border-collapse:collapse; border:1px solid #dcdcdc; font-size:13px;">
        {{-- Card header --}}
        <tr>
            <td colspan="2" style="background:#f3f3f3; border-bottom:1px solid #dcdcdc; padding:12px 16px; font-weight:700; color:#374151;">
                Reports / Profit and Loss
            </td>
        </tr>
        {{-- Period --}}
        <tr>
            <td colspan="2" style="padding:9px 16px; border-bottom:1px solid #ececec; font-size:12px; color:#4b5563;">
                {{ $pl['start_date'] }} &mdash; {{ $pl['end_date'] }}
            </td>
        </tr>

        {{-- Volume rows --}}
        <tr>
            <td style="{{ $rowLabel }}">Sales ({{ $pl['sales_count'] }})</td>
            <td style="{{ $rowAmount }}">{{ $money($pl['sales_amount']) }}</td>
        </tr>
        <tr>
            <td style="{{ $rowLabel }}">Purchases ({{ $pl['purchases_count'] }})</td>
            <td style="{{ $rowAmount }}">{{ $money($pl['purchases_amount']) }}</td>
        </tr>
        <tr>
            <td style="{{ $rowLabel }}">Sales Return ({{ $pl['sales_return_count'] }})</td>
            <td style="{{ $rowAmount }}">{{ $money($pl['sales_return_amount']) }}</td>
        </tr>
        <tr>
            <td style="{{ $rowLabel }}">Purchases Return ({{ $pl['purchases_return_count'] }})</td>
            <td style="{{ $rowAmount }}">{{ $money($pl['purchases_return_amount']) }}</td>
        </tr>

        <tr><td colspan="2" style="{{ $spacer }}"></td></tr>

        {{-- Revenue --}}
        <tr>
            <td style="{{ $hiLabel('#eceffb') }}">Revenue</td>
            <td style="{{ $hiAmount('#eceffb') }}">{{ $money($pl['revenue']) }}</td>
        </tr>

        {{-- Cash flow rows --}}
        <tr>
            <td style="{{ $rowLabel }}">Payments Received</td>
            <td style="{{ $rowAmount }}">{{ $money($pl['payments_received']) }}</td>
        </tr>
        <tr>
            <td style="{{ $rowLabel }}">Payments Sent</td>
            <td style="{{ $rowAmount }}">{{ $money($pl['payments_sent']) }}</td>
        </tr>
        <tr>
            <td style="{{ $rowLabel }}">Expenses</td>
            <td style="{{ $rowAmount }}">{{ $money($pl['expenses']) }}</td>
        </tr>

        {{-- Payments Net --}}
        <tr>
            <td style="{{ $hiLabel('#efefef') }}">Payments Net</td>
            <td style="{{ $hiAmount('#efefef') }}">{{ $money($pl['payments_net']) }}</td>
        </tr>

        <tr><td colspan="2" style="{{ $spacer }}"></td></tr>

        {{-- Profit --}}
        <tr>
            <td style="{{ $hiLabel('#e6f7fc') }}">ProfitNet (FIFO)</td>
            <td style="{{ $hiAmount('#e6f7fc') }}">{{ $money($pl['profit_net_fifo']) }}</td>
        </tr>
        <tr>
            <td style="{{ $hiLabel('#fcf8e3') }}">ProfitNet (AverageCost)</td>
            <td style="{{ $hiAmount('#fcf8e3') }}">{{ $money($pl['profit_net_average_cost']) }}</td>
        </tr>
    </table>
</div>
