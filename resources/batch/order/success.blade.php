@extends('batch.layout.base')

@section('title','批发商用户中心')

@section('css')
   <style>
       .order-button button{ width: 45%}
   </style>
@endsection

@section('header')
    <header>
        <div class="header-back" onclick="history.go(-1);"></div>
        七乐乐
    </header>
@endsection

@section('content')
    @if($isOrder)
        <div class="yu-success">
            <?=asset_img('seller/images/yu-success.png')?>
            <p class="t-msg">商品预订成功，稍后七乐乐客服会联系您</p>
            <div class="order-button">
                <a href="{{ toRoute('/seller/order') }}"><button>我的预定单</button></a>
                <a href="{{ toRoute('/seller/batch') }}"><button>继续预定</button></a>
            </div>
        </div>
    @else
        未知错误!
    @endif
@endsection

@section('js')

@endsection