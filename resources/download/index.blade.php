@extends('vendor.layout.base')

@section('title','产品中心')

@section('css')
    <style>
        .t-msg{ width: 100%; text-align: left; padding-left: 10px; height: 40px; line-height: 40px;font-weight: bold; background: #e5e5e5;}
        .order-button p{ width: 100%; text-align: center;}
        .order-button span{ float:left; width: 100%; height: 30px; line-height: 30px; font-size: 16px; }
    </style>
@endsection

@section('header')
    <header>
        <div class="header-back" onclick="history.go(-1);"></div>
        七乐乐
    </header>
@endsection

@section('content')
    <p class="t-msg">安卓下载</p>
    <div class="order-button">
        <p style="margin-bottom: 5px; border-bottom:1px #ccc solid;">
            <span>批发商</span>
            <?=asset_img('apk/1512987008.png')?></p>
        <p>
            <span>供应商</span>
            <?=asset_img('apk/1512986964.png')?></p>
    </div>
@endsection

@section('footer','')

@section('js','')