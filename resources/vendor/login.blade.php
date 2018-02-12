@extends('vendor.layout.base')

@section('title','供应商登录')

@section('css')
    <?= asset_css('seller/public/js/lib/layer/mobile/need/layer.css') ?>
@endsection

@section('body_class','class="bg-w"')
@section('content')
    <div class="main-contain">
        <div class="icon-logo">
            <?=asset_img('seller/images/i-logo.png')?>
        </div>
        <form id="loginForm" class="login-form" action="<?=toRoute('login/check_login')?>" method="post">
            <div class="form-line">
                <input type="text" placeholder="登录账号" name="username" id="username"/>
            </div>
            <div class="form-line">
                <input type="password" placeholder="密码" name="password" id="password"/>
            </div>
            <p class="error-message">
                <input type="hidden" name="back_url" value="{{ $back_url }}">
                <input type="hidden" name="role_id" value="1">
            </p>
            <button type="submit">登录</button>
        </form>
        <!--<a href="register.html" class="pull-left" style="padding-left:0.75rem;">注册</a>-->
        <div class="forget"><a>忘记密码?</a></div>
    </div>
@endsection

@section('js')
    <?= asset_js('seller/public/js/plugins/validate.js')?>
    <?= asset_js('seller/public/js/lib/layer/mobile/layer.js')?>
    <?= asset_js('seller/js/login.js')?>
@endsection