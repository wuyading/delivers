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
        <body class="page-header-fixed page-sidebar-closed-hide-logo page-content-white">
            <!-- BEGIN HEADER -->
            @section('header')
                @include('/sysadmin/common/header')
            @show
            <!-- END HEADER -->
            <div class="clearfix"> </div>

            <div class="page-container">
                <!-- BEGIN SIDEBAR -->
                @section('sidebar')
                    @include('/sysadmin/common/sidebar')
                @show
                <!-- END SIDEBAR -->

                <div class="page-content-wrapper">
                    @yield('content')
                </div>

                <!-- BEGIN QUICK SIDEBAR -->
                @section('quick_sidebar')
                    @include('/sysadmin/common/quick_sidebar')
                @show
                <!-- END QUICK SIDEBAR -->
            </div>

            <!-- BEGIN FOOTER -->
            @section('footer')
                @include('/sysadmin/common/footer')
            @show
            <!-- END FOOTER -->
        </body>
    @show
</html>
<script type="text/javascript">
    var first_crumb = $('.page-sidebar-menu>li.active .nav-toggle').text();
    var second_crumb = $(".sub-menu .active").text();
    $("#first_crumb").text(first_crumb);
    $("#second_crumb").text(second_crumb);
</script>