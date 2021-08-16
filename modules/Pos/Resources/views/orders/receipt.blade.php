<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8; charset=ISO-8859-1"/>

    <title>@setting('company.name')</title>

    <base href="{{ config('app.url') . '/' }}">

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('public/img/favicon.ico') }}" type="image/png">

    <!-- Css -->
    <link rel="stylesheet" href="{{ asset('modules/Pos/Resources/assets/css/receipt.css?v=' . module_version('pos')) }}" type="text/css">

    <style type="text/css">
        * {
            font-family: DejaVu Sans, sans-serif !important;
        }
    </style>
    <style>
        @page {
            size: {{ setting('pos.general.printer_paper_size') }}mm;
            margin: 0;
        }
        html {
            font-size: {{ setting('pos.general.printer_paper_size') === 80 ? '13px' : '9px' }};
        }
        body {
            width: {{ setting('pos.general.printer_paper_size') }}mm;
        }
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
@if(isset($print))
    <body onload="window.print();">
@else
    <body>
@endif
    <div class="receipt pa-2">
        @if(setting('pos.general.show_logo_in_receipt'))
            <div class="text-center mb-1">
                <img src="{{ $logo }}" width="50%">
            </div>
        @endif

        <div class="text-center text-small mb-1">
            <div>{{ setting('company.name') }}</div>
            @if (setting('company.tax_number'))
                <div>{{ setting('company.tax_number') }}</div>
            @endif
            @if(setting('company.address'))
                <div>{!! nl2br(setting('company.address')) !!}</div>
            @endif
            @if(setting('company.phone'))
                <div>{{ setting('company.phone') }}</div>
            @endif
            @if(setting('company.email'))
                <div>{{ setting('company.email') }}</div>
            @endif
            <div class="double-dash-line mt-1"></div>
        </div>

        <div class="mb-1">
            @foreach($order->items as $item)
                <div>
                    <span class="half">{{ $item->name }}</span>
                    <span class="half text-right">@money($item->total, $order->currency_code, true)</span>
                </div>
                <div class="pl-3">{{ $item->quantity }} x {{ money($item->price, $order->currency_code, true) }}</div>
                @if($item->discount_rate)
                    <div class="pl-3">{{ trans('pos::pos.discount') }}: {{ $item->discount_rate }}%</div>
                @endif
            @endforeach
            <div class="double-dash-line mt-1"></div>
        </div>

{{--        TODO: implement taxes--}}
{{--        <div class="mb-1">--}}
{{--            <div>--}}
{{--                <span class="half">Subtotal</span>--}}
{{--                <span class="half text-right">$ {{ $order->subtotal }}</span>--}}
{{--            </div>--}}
{{--            <div>--}}
{{--                <span class="half">Tax 10%</span>--}}
{{--                <span class="half text-right">$ {{ $order->tax }}</span>--}}
{{--            </div>--}}
{{--        </div>--}}

        <div class="mb-1 text-big">
            <span class="half text-uppercase">{{ trans('pos::pos.total') }}</span>
            <span class="half text-right">@money($total, $order->currency_code, true)</span>
        </div>


        <div class="mb-1">
            @foreach($payments as $payment)
                <div>
                    <span class="half">{{ $payment['type'] }}</span>
                    <span class="half text-right">@money($payment['amount'], $order->currency_code, true)</span>
                </div>
            @endforeach
        </div>

        @if($change)
            <div class="mb-1 text-big">
                <span class="half text-uppercase">{{ trans('general.change') }}</span>
                <span class="half text-right">@money($change, $order->currency_code, true)</span>
                <div class="double-dash-line mt-1"></div>
            </div>
        @else
            <div class="double-dash-line mb-1"></div>
        @endif

        <div class="text-center text-small mb-1">{{ trans('pos::pos.served_by') }} {{ $served_by }}</div>

        <div class="text-center text-small">
            <div>{{ trans_choice('pos::general.orders', 1) }} {{ $order->document_number }}</div>
            <div>{{ $order->issued_at }}</div>
        </div>

        @if(setting('pos.general.use_paper_cutter'))
            <div class="page-break"></div>
        @endif
    </div>
</body>
</html>
