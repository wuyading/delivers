@extends('vendor.layout.base')

@section('title','产品中心')

@section('css','')

@section('header')
    <header> 七乐乐  </header>
@endsection

@section('content')
    <div class="cate-list">
        <p class="cate-title">已发布的商品</p>
    </div>

    <div class="order-list">
        @if($data)
            @foreach($data as $item)
                <div class="order-item product-item">
                    <a href="{{ toRoute('product/detail/'.$item['id']) }}">
                        <div class="i-top overflow">
                            <?php
                            $logos = empty($item['logo']) ? [] : explode(',',$item['logo']);
                            $logo = $logos[0];
                            ?>
                            <?=asset_img(empty($logo) ? 'seller/images/img-order.png' : $logo,['class'=>'order-img'])?>
                            <div class="i-detail">
                                <span class="i-name overflow">{{ $item['title'] }}</span>
                                <span class="pull-right i-shen">
                                 <?php
                                    $statusTitles = ['审核中','审核通过','审核失败'];
                                    echo $statusTitles[$item['status']]
                                 ?>
                                </span>
                                <p class="i-pnum">
                                    <span class="i-price">¥{{ $item['price'] }}</span>
                                    <span class="i-desc pull-right">{{ $item['brand'] }}</span>
                                </p>
                                <p class="i-cate">分类名称</p>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        @else
            <div class="order-item product-item">
                <p>暂无任何商品</p>
            </div>
        @endif
    </div>
@endsection

@section('footer')
    @include('vendor/layout/footer')
@endsection

@section('js')
    <?=asset_js('seller/js/index.js')?>
@endsection