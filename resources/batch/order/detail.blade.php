@extends('batch.layout.base')

@section('title','订单详情')

@section('css','')

@section('header')
    <header>
        <div class="header-back" onclick="history.go(-1);"></div>
        我的预定单
    </header>
@endsection

@section('content')
    <div class="order-item">
        <div class="i-top overflow">
            <?php $logos = explode(',',$info['product_img']); $logo = $logos[0];?>
            <?=asset_img(empty($logo) ? 'seller/images/img-order.png' : $logo,['class'=>'order-img'])?>

            <div class="i-detail">
                <span class="i-name overflow">{{ $info['product_title'] }}</span>
                <span class="status">
                    <?php
                    $statusTitles = ['待审核','审核通过','审核失败'];
                    ?>
                    {{ $statusTitles[$info['confirm_status']] }}
                </span>
                <p class="i-pnum">
                    <span class="i-price">¥{{ $info['product_money'] }}</span>
                    <span class="i-num"> x {{ $info['num'] }}</span>
                </p>
            </div>
        </div>
        <div class="i-bottom overflow">
            <div class="total">共计¥{{ $info['money'] }}</div>
        </div>
    </div>
    <div class="order-info">
        <p>预定时间：{{ date('Y-m-d H:i:s',$info['add_time']) }}</p>
        @if($info['confirm_status']>0)
            <p>确认时间：{{ date('Y-m-d H:i:s',$info['confirm_time']) }}</p>
        @endif

        @if($info['confirm_status']==2)
            <p>失败原因：<span>{{ $info['confirm_remark'] }}</span></p>
        @endif
    </div>
@endsection

@section('js')

@endsection