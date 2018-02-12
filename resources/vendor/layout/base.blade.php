<!DOCTYPE html>
<html>
<head>
    @include('vendor/layout/header')
    <title>七乐乐-@yield('title')</title>
    @yield('css')
</head>
<body @yield('body_class')>
@yield('header')
<div class="main-contain">
    <!--#详情-->
@yield('content')
<!-- end -->
</div>

@yield('bottom')

@yield('footer')

@yield('js')

</body>
</html>