@extends('sysadmin.layout')

@section('title', '修改个人信息')

@section('plugins_css')
@endsection

@section('head_css')
    <?=asset_css('/assets/layouts/layout/css/custom.min.css')?>
@endsection

@section('content')
    <div class="page-content">
        @include('/sysadmin/common/crumb')

        <!-- BEGIN DASHBOARD STATS 1-->
        <div class="row">
            <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                <form id="sub_form" target="iframe_message" action="{{ toRoute('Adminmanage/ajax_save_info') }}" method="post" enctype="multipart/form-data">
                    @if($user&&$is_gh==0)
                    <div class="text-center">
                        <h3 ><strong>已绑定的微信号</strong></h3>
                        <img src="{{ $user['head_img'] }}" style="width: 300px;height: 300px;border-radius: 50%">
                        <p style="font-size: 20px">{{ $user['username'] }}</p>
                        <p style="font-size: 20px"><a class="btn btn-default" href="{{ toRoute('adminmanage/bind_wx?is_gh=1') }}">更换绑定微信</a></p>
                    </div>
                    @else
                        <div class="text-center">
                            <h3 ><strong>绑定微信号</strong></h3>
                            <img src="/sysadmin/adminmanage/bind_qrcode" style="width: 300px;height: 300px">
                            <p style="font-size: 20px">请使用微信扫一扫该二维码</p>
                        </div>
                    @endif

                    <hr style="width: 80%; margin: 0 auto;"/>
                    <div style="width: 80%;margin: 0 auto;padding-left: 20px" >
                        <h4>绑定微信号用途：</h4>
                        <p>1.用于提现。商户产生佣金后，可将金额提取到该微信号中</p>
                        <p>2.验证优惠券。使用绑定的微信号可以验证商户优惠券的有效性</p>
                        <p>3.可以使用微信扫一扫登录后台（尚未开放）</p>
                    </div>
                </form>
            </div>
        </div>

        <iframe name="iframe_message" style="display: none">

        </iframe>

        <div class="clearfix"></div>
        <!-- END DASHBOARD STATS 1-->
    </div>
@endsection

@section('plugins_js')
@endsection

@section('footer_js')
    <?=asset_js('/assets/pages/scripts/dashboard.min.js')?>

    <script type="text/javascript">

        function show_message(json) {
            if(json.status == 1001){
                layer.alert(json.msg, {
                    icon: 6
                    ,time: 0 //不自动关闭
                    ,btn: ['确定']
                    ,area: '200px'
                    ,yes: function(index){
                        layer.close(index);
                        window.location.href = "{{ toRoute('Adminmanage/edit_info') }}";
                    }
                });
            }else{
                layer.alert(json.msg);
            }
        }
    </script>
@endsection