@php
    $logo = \Modules\Setting\Entities\System::getProperty('logo');
    $site_title =\Modules\Setting\Entities\System::getProperty('site_title');
@endphp
    <!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{ $site_title .' | '}}@yield('title')</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow"/>
    <link rel="icon" type="image/png" href="{{ asset('assets/back-end/system/' . $logo) }}"/>
    <!-- Bootstrap CSS-->
    @include('back-end.layouts.auth.partials.css')
    @yield('css')
</head>
<body>

@yield('content')
@yield('js')
</body>

</html>
