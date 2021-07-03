<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @yield("title")
    <link href="{{ asset('merchant-static/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('merchant-static/css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('merchant-static/css/fonts.css') }}" rel="stylesheet">
    <link href="{{ asset('merchant-static/css/common.css') }}" rel="stylesheet">
    @yield("css")
</head>

<!--页面主要内容-->
@yield("content")

</html>
<script type="text/javascript" src="{{ asset('merchant-static/js/jquery.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('merchant-static/js/bootstrap.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('layer/layer.js') }}"></script>
<script type="text/javascript" src="{{ asset('merchant-static/js/public.js') }}"></script>
@yield("js")
