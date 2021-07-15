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
    <link rel="stylesheet" href="{{ asset('modules/Pos/Resources/assets/css/print.css?v=' . module_version('pos')) }}" type="text/css">

    <style type="text/css">
        * {
            font-family: DejaVu Sans, sans-serif !important;
        }
    </style>
</head>
@if(isset($print))
    <body onload="window.print();">
@else
    <body>
@endif
    <div class="receipt pa-2">
        <div class="text-center text-10 mb-1">
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
                    <span class="half text-right">$ {{ $item->total }}</span>
                </div>
                <div class="pl-3">{{ $item->quantity }} x {{ $item->price }}</div>
                @if($item->discount_rate)
                    <div class="pl-3">Discount: {{ $item->discount_rate }}%</div>
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

        <div class="mb-1 text-20">
            <span class="half">TOTAL</span>
            <span class="half text-right">$ {{ $total }}</span>
        </div>


        <div class="mb-1">
            @foreach($payments as $payment)
                <div>
                    <span class="half">{{ $payment['type'] }}</span>
                    <span class="half text-right">$ {{ $payment['amount'] }}</span>
                </div>
            @endforeach
        </div>

        @if($change)
            <div class="mb-1 text-20">
                <span class="half">CHANGE</span>
                <span class="half text-right">$ {{ $change }}</span>
                <div class="double-dash-line mt-1"></div>
            </div>
        @else
            <div class="double-dash-line mb-1"></div>
        @endif

        <div class="text-center text-10 mb-1">Served by {{ $served_by }}</div>

        <div class="text-center text-10">
            <div>Order {{ $order->document_number }}</div>
            <div>{{ $order->issued_at }}</div>
        </div>
    </div>
</body>
</html>
