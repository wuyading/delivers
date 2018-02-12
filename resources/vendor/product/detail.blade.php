@extends('vendor.layout.base')

@section('title','产品中心')

@section('css')

@endsection

@section('header')
    <header>
        <div class="header-back" onclick="history.go(-1);"></div>
        商品详情
    </header>
@endsection

@section('content')
    <div class="detail-top">
        <p class="d-status">
            <?php
            $statusTitles = ['审核中','审核通过','审核失败'];
            echo $statusTitles[$detail['status']]
            ?>
        </p>
        <p>绿色无污染果园现摘鹰嘴大芒果超甜</p>
        <div class="overflow">
            <p class="sales pull-left">¥{{ $detail['price'] }}</p>
            <p class="pull-right d-name">{{ $detail['brand'] }}</p>
        </div>
        <p class="d-desc">{{ html_decode($detail['remark']) }}</p>
    </div>
    <div class="product-detail">
        <?php
        $logos = empty($detail['logo']) ? '' : explode(',',$detail['logo']);
        ?>
        @if($logos)
            @foreach($logos as $logo)
                <?=asset_img($logo,['class'=>'detail-img'])?>&nbsp;
            @endforeach
        @endif
    </div>
    <div class="btn-area">
        <button class="buy-now" onclick="delProduct({{ $detail['id'] }})">删除商品</button>
    </div>
@endsection

@section('footer','')

@section('js')
    <?=asset_js('seller/public/js/lib/layer/mobile/layer.js')?>
    <script>
        function delProduct(id){
            $.post(BASE_URL+'seller/product/del',{id:id},function(result){
                layer.open({
                    content: result.msg,
                    skin: 'msg',
                    time: 2 //2秒后自动关闭
                });
                return false;
            },'json');
        }
    </script>
@endsection