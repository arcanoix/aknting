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

    <!-- Font -->
    <link rel="stylesheet" href="{{ asset('public/vendor/opensans/css/opensans.css?v=' . version('short')) }}" type="text/css">

    <!-- Icons -->
    <link rel="stylesheet" href="{{ asset('public/vendor/nucleo/css/nucleo.css?v=' . version('short')) }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('public/vendor/fontawesome/css/all.min.css?v=' . version('short')) }}" type="text/css">

    <!-- Css -->
    <link rel="stylesheet" href="{{ asset('public/css/argon.css?v=' . version('short')) }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('public/css/akaunting-color.css?v=' . version('short')) }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('public/css/custom.css?v=' . version('short')) }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('public/css/element.css?v=' . version('short')) }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('modules/Pos/Resources/assets/css/app.css?v=' . module_version('pos')) }}" type="text/css">

    <script type="text/javascript">
        var url = '{{ url("/" . company_id()) }}';
        var app_url = '{{ config("app.url") }}';
        var aka_currency = {!! !empty($currency) ? $currency : 'false' !!};
    </script>

{{--    TODO: implement a translation--}}
    <script type="text/javascript">
        window.Laravel = <?php echo json_encode([
            'csrfToken' => csrf_token(),
        ]); ?>;
        window.company_id = {{ company_id() }};
    </script>
</head>
<body>
    <div id="pos">
        <app
            company-name="{{ setting('company.name') }}"
            user-name="{{ user()->name }}"
        >
        </app>
    </div>
    <script src="{{ asset('modules/Pos/Resources/assets/js/app.min.js?v=' . module_version('pos')) }}"></script>
</body>
</html>
