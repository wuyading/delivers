<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<!--<![endif]-->
<head>
    <meta charset="utf-8" />
    <title>应用程序名称 - @yield('title')</title>
    @section('head')
        @include('/sysadmin/common/head')
    @show
</head>
@section('body')
    <body class="page-header-fixed page-content-white">

    <div style="padding: 10px;">
        @yield('content')
    </div>
    @section('footer')
        @include('/sysadmin/common/footer')
    @show
    <!-- END FOOTER -->
    </body>
@show
</html>