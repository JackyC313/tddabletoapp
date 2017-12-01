<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name') }}</title>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @if( !empty($chart))
        <!-- Chart Styles -->
        {!! Charts::styles() !!}
    @endif

    <!--[if lt IE 9]>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.js"></script>
    <![endif]-->

</head>
<body>
    <div id="app">
        @include('partials.topnav')
        <div class="container">
            <div class="row">
                <div class="col-md-8 col-md-offset-2">
                @yield('content-nav')
                @include('partials.status_messages')
                @yield('content')
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
    @if( !empty($chart))
        <!-- Chart Scripts -->
        {!! Charts::scripts() !!}
        {!! $chart->script() !!}
    @endif
</body>
</html>
