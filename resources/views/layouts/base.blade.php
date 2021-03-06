<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="renderer" content="webkit">
    <meta http-equiv="Cache-Control" content="no-transform"> {{--防止被转码--}}
    <meta http-equiv="Cache-Control" content="no-siteapp"> {{--防止被转码--}}
    <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="万事胜意。">
    <meta name="keywords" content="FLL">

    <title>@yield('title', '查价喵') | 以史为镜，可以知奸商</title>

    <!-- common css -->
    <link href="/bootstrap/v4.3.1/css/bootstrap.min.css" rel="stylesheet">
    <link href="/ionicons/v4.5.10-1/css/ionicons.min.css" rel="stylesheet">
    <!-- end common css -->

    <!-- 当前页单独css -->
    @stack('css')
    <!-- end 当前页单独css -->
</head>
<body>

@yield('header', View::make('common.header'))

@yield('content')

@yield('footer', View::make('common.footer'))

<!-- common js -->
<script src="/js/jquery-3.4.1.min.js"></script>
<script src="/bootstrap/v4.3.1/js/bootstrap.min.js"></script>
<script src="/js/sweetalert.min.js"></script>
<script src="/js/common.js"></script>
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
</script>
<!-- end common js -->

<!-- 当前页单独js -->
@stack('js')
<!-- end 当前页单独js -->

</body>
</html>
