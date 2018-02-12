@extends('vendor.layout.base')

@section('title','供应商用户中心')

@section('css','')

@section('header')
    <header> 我的 </header>
@endsection

@section('content')
    <div class="top-area">
        <?= !empty($userInfo['head_img'])? "<img src='{$userInfo['head_img']}' class = 'head-img'/>" : asset_img('http/images/img-head.png',['class'=>'head-img']);?>

        @if(empty($userInfo))
            <a href="{{ toRoute('/login') }}"><div class="disLogin">登录/注册</div></a>
        @else
            <div class="disLogin" style="line-height: 1;padding-top: 15px" >
                <p class="name">{{ $userInfo['username'] or ''}}</p>
            <?php if(empty($userInfo['mobile'])){?>
            <!-- <a class="">绑定手机号></a>-->
                <?php }else{?>
                {{ $userInfo['mobile'] }}
                <?php }?>
            </div>
        @endif
    </div>
    <div class="menu-list">
        <div class="menu-item online">
            联系客服
            <div class="right-arrow"></div>
        </div>
    </div>

    <button type="button" class="exit" onclick="javascript:window.location.href='<?= toRoute('/seller/login/login_out')?>';">退出登录</button>
@endsection

@section('bottom')
    <div class="overlay"></div>
    <div class="over-code">
        <?=asset_img('seller/images/code.jpg')?>
        <p>扫一扫上面的二维码图案，加客服微信</p>
    </div>
@endsection

@section('footer')
    @include('vendor/layout/footer')
@endsection


@section('js')
    <script>
        $('.online').click(function(){
            $('.overlay,.over-code').show();
        });
        $('.overlay').click(function(){
            $('.overlay,.over-code').hide();
        });

    </script>
@endsection