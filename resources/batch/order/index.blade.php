@extends('batch.layout.base')

@section('title','批发商用户中心')

@section('css')
    <?=asset_css('seller/public/js/lib/layer/mobile/need/layer.css')?>
@endsection

@section('header')
    <header>
        <div class="header-back" onclick="history.go(-1);"></div>
        预订单
    </header>
@endsection

@section('content')
    @if($list)
        <div class="order-list">
            @foreach($list as $item)
            <div class="order-item">
                <a href="{{ toRoute('order/detail/'.$item['id']) }}">
                    <div class="i-top overflow">
                        <?php $logos = explode(',',$item['product_img']); $logo = $logos[0];?>
                        <?=asset_img(empty($logo) ? 'seller/images/img-order.png' : $logo,['class'=>'order-img'])?>

                        <div class="i-detail">
                            <span class="i-name overflow">{{ $item['product_title'] }}</span>
                            <span class="status">
                                 <?php
                                $statusTitles = ['待审核','审核通过','审核失败'];
                                echo ($item['order_status']==2)? '订单取消' : $statusTitles[$item['confirm_status']]
                                ?>
                            </span>
                            <p class="i-pnum">
                                <span class="i-price">¥{{ $item['product_money'] }}</span>
                                <span class="i-num"> x {{ $item['num'] }}</span>
                            </p>
                        </div>
                    </div>
                </a>
                <div class="i-bottom overflow">
                    <div class="total">共计¥{{ $item['money'] }}</div>
                    @if($item['confirm_status']==0 && $item['order_status']!=2)
                        <a class="toPay" onclick="cancelOrder({{ $item['id'] }})">取消订单</a>
                    @endif
                </div>
            </div>
            @endforeach

        </div>
    @else
        <div class="noData">
            <?=asset_img('seller/images/noImg.png')?>
            <p>你的订单空空如也</p>
        </div>
        <div class="overlay"></div>
    @endif
@endsection

@section('js')
    <?=asset_js('seller/public/js/lib/layer/mobile/layer.js')?>
    <script>
        function cancelOrder(id){
            $.post('/seller/order/cancelOrder',{id:id},function(result){
                layer.open({
                    content: result.msg,
                    skin: 'msg',
                    time: 2 //2秒后自动关闭
                });return false;
            },'json');
        }
    </script>
@endsection