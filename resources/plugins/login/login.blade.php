@extends('sysadmin.layout')

@section('title', '登录 | 系统管理后台')

@section('plugins_css')
@endsection

@section('head_css')
    <?=asset_css('assets/pages/css/login.min.css')?>
    <style>
        html, body {
            width: 100%;
            height: 100%;
            overflow: hidden;
        }

        body {
            background: #16a085;
            font-family: 'Montserrat', sans-serif;
            color: #fff;
            line-height: 1.3;
            -webkit-font-smoothing: antialiased;
        }

        .login {
            width: 100%;
            height: 100%;
            overflow: hidden;
        }

        .intro {
            position: absolute;
            left: 0;
            top: 50%;
            padding: 0 20px;
            width: 100%;
            text-align: center;
        }
    </style>
@endsection

@section('body')
    <body class=" login">

        <div class="intro">
            <div class="menu-toggler sidebar-toggler"></div>
            <!-- END SIDEBAR TOGGLER BUTTON -->
            <!-- BEGIN LOGO -->
            <div class="logo">
                <a href="index.html"><?=asset_img('assets/pages/img/logo-big.png')?></a>
            </div>
            <!-- END LOGO -->
            <!-- BEGIN LOGIN -->
            <div class="content">
                <!-- BEGIN LOGIN FORM -->
                <form class="login-form" action="<?=toRoute('login/login_in')?>" method="post">
                    <h3 class="form-title font-green">网站后台登录系统</h3>
                    <div class="alert alert-danger display-hide">
                        <button class="close" data-close="alert"></button>
                        <span> Enter any username and password. </span>
                    </div>
                    <div class="form-group">
                        <label class="control-label visible-ie8 visible-ie9">帐号</label>
                        <input class="form-control form-control-solid placeholder-no-fix" type="text" autocomplete="off" placeholder="帐号" name="username" />
                    </div>
                    <div class="form-group">
                        <label class="control-label visible-ie8 visible-ie9">密码</label>
                        <input class="form-control form-control-solid placeholder-no-fix" type="password" autocomplete="off" placeholder="密码" name="password" />
                    </div>
                    <div class="form-actions text-center">
                        <input type="hidden" name="back_url" value="{{ $back_url }}">
                        <button type="submit" class="btn green uppercase">登 录</button>
                    </div>
                </form>
                <!-- END LOGIN FORM -->
            </div>

            <div class="copyright"> 2017 © test. </div>
        </div>

        <!--[if lt IE 9]>
        <?=asset_js('/assets/global/plugins/respond.min.js')?>
        <?=asset_js('/assets/global/plugins/excanvas.min.js')?>
        <![endif]-->
        <!-- BEGIN CORE PLUGINS -->
        <?=asset_js('/assets/global/plugins/jquery.min.js')?>
        <!-- END CORE PLUGINS -->
        <!-- BEGIN PAGE LEVEL PLUGINS -->
        <?=asset_js('assets/global/plugins/particleground/jquery.particleground.min.js')?>
        <?=asset_js('assets/global/plugins/jquery-validation/js/jquery.validate.min.js')?>
        <?=asset_js('assets/global/plugins/jquery-validation/js/additional-methods.min.js')?>
        <!-- END PAGE LEVEL PLUGINS -->
        <!-- BEGIN PAGE LEVEL SCRIPTS -->
        <?=asset_js('assets/pages/scripts/login.min.js')?>
        <!-- END PAGE LEVEL SCRIPTS -->
    </body>
@endsection