<div class="page-header navbar navbar-fixed-top">
    <!-- BEGIN HEADER INNER -->
    <div class="page-header-inner ">
        <!-- BEGIN LOGO -->
        <div class="page-logo">
            <a href="/sysadmin">
                <?=asset_img('assets/layouts/layout/img/logo.png',['class'=>'login-img'])?>
            </a>
            <div class="menu-toggler sidebar-toggler"> </div>
        </div>
        <!-- END LOGO -->
        <!-- BEGIN RESPONSIVE MENU TOGGLER -->
        <a href="javascript:;" class="menu-toggler responsive-toggler" data-toggle="collapse" data-target=".navbar-collapse"> </a>
        <!-- END RESPONSIVE MENU TOGGLER -->
        <!-- BEGIN TOP NAVIGATION MENU -->
        <div class="top-menu">
            <ul class="nav navbar-nav pull-right">
                <!-- BEGIN NOTIFICATION DROPDOWN -->
                <!-- DOC: Apply "dropdown-dark" class after below "dropdown-extended" to change the dropdown styte -->
                {{--<li class="dropdown dropdown-extended dropdown-notification" id="header_notification_bar">--}}
                    {{--<a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">--}}
                        {{--<i class="icon-bell"></i>--}}
                        {{--<span class="badge badge-default"> 1 </span>--}}
                    {{--</a>--}}
                    {{--<ul class="dropdown-menu">--}}
                        {{--<li class="external">--}}
                            {{--<h3>--}}
                                {{--<span class="bold">1 pending</span> notifications</h3>--}}
                            {{--<a href="page_user_profile_1.html">view all</a>--}}
                        {{--</li>--}}
                        {{--<li>--}}
                            {{--<ul class="dropdown-menu-list scroller" style="height: 250px;" data-handle-color="#637283">--}}
                                {{--<li>--}}
                                    {{--<a href="javascript:;">--}}
                                        {{--<span class="time">just now</span>--}}
                                        {{--<span class="details">--}}
                                                    {{--<span class="label label-sm label-icon label-success">--}}
                                                        {{--<i class="fa fa-plus"></i>--}}
                                                    {{--</span> New user registered. </span>--}}
                                    {{--</a>--}}
                                {{--</li>--}}
                            {{--</ul>--}}
                        {{--</li>--}}
                    {{--</ul>--}}
                {{--</li>--}}
                <!-- END NOTIFICATION DROPDOWN -->
                <!-- BEGIN INBOX DROPDOWN -->
                <!-- DOC: Apply "dropdown-dark" class after below "dropdown-extended" to change the dropdown styte -->
                {{--<li class="dropdown dropdown-extended dropdown-inbox" id="header_inbox_bar">--}}
                    {{--<a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">--}}
                        {{--<i class="icon-envelope-open"></i>--}}
                        {{--<span class="badge badge-default"> 4 </span>--}}
                    {{--</a>--}}
                    {{--<ul class="dropdown-menu">--}}
                        {{--<li class="external">--}}
                            {{--<h3>You have--}}
                                {{--<span class="bold">7 New</span> Messages</h3>--}}
                            {{--<a href="app_inbox.html">view all</a>--}}
                        {{--</li>--}}
                        {{--<li>--}}
                            {{--<ul class="dropdown-menu-list scroller" style="height: 275px;" data-handle-color="#637283">--}}
                                {{--<li>--}}
                                    {{--<a href="#">--}}
                                                {{--<span class="photo">--}}
                                                  {{--asset_img('assets/layouts/layout/img/avatar2.jpg') --}}
                                                {{--</span>--}}
                                        {{--<span class="subject">--}}
                                                    {{--<span class="from"> Lisa Wong </span>--}}
                                                    {{--<span class="time">Just Now </span>--}}
                                                {{--</span>--}}
                                        {{--<span class="message"> Vivamus sed auctor nibh congue nibh. auctor nibh auctor nibh... </span>--}}
                                    {{--</a>--}}
                                {{--</li>--}}
                            {{--</ul>--}}
                        {{--</li>--}}
                    {{--</ul>--}}
                {{--</li>--}}
                <!-- END INBOX DROPDOWN -->
                <!-- BEGIN USER LOGIN DROPDOWN -->
                <!-- DOC: Apply "dropdown-dark" class after below "dropdown-extended" to change the dropdown styte -->
                <li class="dropdown dropdown-user">
                    <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                        <?=asset_img('assets/layouts/layout/img/avatar3_small.jpg')?>
                        <span class="username username-hide-on-mobile"> {{ $app->userInfo['username'] or '' }} </span>
                        <i class="fa fa-angle-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-default">
                        <li>
                            <a href="{{ toRoute('adminmanage/edit_info') }}">
                                <i class="icon-user"></i> 个人信息 </a>
                        </li>
                        <li>
                            <a href="{{ toRoute('login/login_out') }}">
                                <i class="icon-key"></i> 退 出 </a>
                        </li>

                    </ul>
                </li>
                <!-- END USER LOGIN DROPDOWN -->
                <!-- BEGIN QUICK SIDEBAR TOGGLER -->
                <!-- DOC: Apply "dropdown-dark" class after below "dropdown-extended" to change the dropdown styte -->
                {{--<li class="dropdown dropdown-quick-sidebar-toggler">--}}
                    {{--<a href="javascript:;" class="dropdown-toggle">--}}
                        {{--<i class="icon-logout"></i>--}}
                    {{--</a>--}}
                {{--</li>--}}
                <!-- END QUICK SIDEBAR TOGGLER -->
            </ul>
        </div>
        <!-- END TOP NAVIGATION MENU -->
    </div>
    <!-- END HEADER INNER -->
</div>
<style>
    .login-img{margin-top: 15px}
</style>