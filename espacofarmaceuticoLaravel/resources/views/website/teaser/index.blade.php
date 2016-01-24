<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="format-detection" content="telephone=no" />
    <meta name="author" content="Bruno Martins">
    <title>@yield('title'){{ $websiteSettings['title'] }}</title>
    <link href="{!! asset('assets/images/dados-do-site/'.$websiteSettings['favicon']) !!}" rel="shortcut icon" />
    <link href="{{ asset('assets/images/dados-do-site/'.$websiteSettings['appleTouchIcon']) }}" rel="apple-touch-icon" />
    <link href="{!! asset('assets/css/main.css') !!}" rel="stylesheet" type="text/css" />
    {!! $websiteSettings['googleAnalytics'] !!}
            <!-- Meta Tags -->
    <meta name="title" content="@yield('title'){{ $websiteSettings['title'] }}" />
    <!-- Tags Facebook -->
    <meta property="og:title" content="@yield('title'){{ $websiteSettings['title'] }}" />
    <meta property="og:type" content="website" />
    <meta property="og:locale" content="pt_BR" />
    <meta property="og:url" content="{{ \URL::current() }}" />
    <meta property="og:image" content="{{ asset('assets/images/websiteSettings/'.$websiteSettings['avatar']) }}" />
    <meta property="og:site_name" content="@yield('title'){{ $websiteSettings['title'] }}" />

    @yield('head')
    <style type="text/css">
        body{
            background: url('/assets/images/teaser.jpg') no-repeat center top;
        }
    </style>
</head>
<body></body>
</html>