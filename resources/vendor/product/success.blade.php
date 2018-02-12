@extends('vendor.layout.base')

@section('title','产品中心')

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
    <div class="yu-success">
        <?=asset_img('seller/images/yu-success.png')?>
        <p class="t-msg">商品发布成功，等待审核</p>
            <div class="order-button">
            <a href="{{ toRoute('/seller/vendor') }}"><button>查看发布</button></a>
            <a href="{{ toRoute('/seller/product/add') }}"><button>继续发布</button></a>
        </div>

    </div>
@endsection

@section('footer','')

@section('js','')