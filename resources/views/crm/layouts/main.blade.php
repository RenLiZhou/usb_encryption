<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>CRM后台</title>
    <link rel="stylesheet" href="{{ asset('layui/css/layui.css') }}"/>
    <link rel="stylesheet" href="{{ asset('crm-static/css/common.css') }}"/>
    <style>
        .or-mid {display: flex; display: -webkit-flex;justify-content: center;}
    </style>
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('crm-static/index/or.ico') }}"/>
    @yield("css")
</head>
<body class="layui-fluid" style="padding: 0 8px;">
<a class="layui-btn layui-btn-sm" style="position: fixed; right: 2px; opacity: 0.4; z-index: 10;" href="javascript:location.replace(location.href);" title="刷新">
    <i class="layui-icon">&#xe669;</i>
</a>
<div style="padding: 6px 0;">
    @yield("content")
</div>
<script src="{{ asset('layui/layui.js') }}"></script>
<script>layui.config({base: '/crm-static/base/'});</script>
@yield("js")
</body>
</html>
