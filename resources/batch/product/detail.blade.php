@extends('batch.layout.base')

@section('title',$detail->title)

@section('css')
    <?=asset_css('seller/public/js/lib/layer/mobile/need/layer.css')?>
@endsection

@section('header')
    <header>
        <div class="header-back" onclick="history.go(-1);"></div>
        商品详情
    </header>
@endsection

@section('content')
    <div class="detail-top">
        {{--<p class="d-status">审核中</p>--}}
        <p>{{ $detail->title }}</p>
        <div class="overflow">
            <p class="sales pull-left">¥{{ $detail->show_price }}</p>
            <p class="pull-right d-name">{{ $detail->brand }}</p>
        </div>
        <p class="d-desc">{{ html_decode($detail->remark) }}</p>
    </div>
    @if(!empty($detail->logo))
        <div class="product-detail">
            <?php
            $logos = explode(',',$detail->logo);
            ?>
            @foreach($logos as $logo)
                <?=asset_img($logo,['class'=>'detail-img'])?>&nbsp;
            @endforeach
        </div>
    @endif
    <form id="order">
        <div class="btn-area">
            <div class="area-num">
                <p class="red">合计：¥<font id="totalPrice">{{ $detail->show_price }}</font><span></span></p>
                <div class="counts clearfix">
                    <p class="pull-left">数量：</p>
                    <input class="pull-left sub" type="button" value="－">
                    <input class="pull-left num" type="text" name="num" value="1" oninput="value=value.replace(/\D/g,'')">
                    <input class="pull-left add" type="button" value="+">
                </div>
            </div>
            <input type="hidden" name="money" id="money" value="{{ $detail->show_price }}">
            <input type="hidden" name="product_id" value="{{ $detail->id }}">
            <button type="button" class="buy-now">确认预订</button>
        </div>
    </form>
@endsection

@section('js')
    <?=asset_js('seller/public/js/lib/layer/mobile/layer.js')?>
    <?=asset_js('seller/js/productDetail.js')?>
@endsection