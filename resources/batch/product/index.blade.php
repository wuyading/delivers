@extends('batch.layout.base')

@section('title','产品中心')

@section('css','')

@section('header')
    <header> 七乐乐  </header>
@endsection

@section('content')
    <div class="cate-list">
        @if(count($category) >1)
            <ul id="category">
                @foreach($category as $_k => $cat)
                    <li class="cate-item @if($_k ==0) active @endif"  alt="{{ $cat['id'] }}">{{ $cat['name'] }}</li>
                @endforeach
            </ul>
        @endif
    </div>

    <div class="order-list">
        @if($data)
            @foreach($data as $item)
                <div class="order-item product-item">
                    <a href="{{ toRoute('batch/detail/'.$item['id']) }}">
                        <div class="i-top overflow">
                            <?php
                            $logos = empty($item['logo']) ? [] : explode(',',$item['logo']);
                            $logo = $logos[0];
                            ?>
                            <?=asset_img(empty($logo) ? 'seller/images/img-order.png' : $logo,['class'=>'order-img'])?>

                            <div class="i-detail">
                                <span class="i-name overflow">{{ $item['title'] }}</span>
                                <p class="i-pnum">
                                    <span class="i-price">¥{{ $item['show_price'] }}</span>
                                    <span class="i-desc pull-right">{{ $item['brand'] }}</span>
                                </p>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        @else
            <p>暂无任何商品</p>
        @endif
    </div>
@endsection

@section('footer')
    @include('batch/layout/footer')
@endsection

@section('js')
  <?=asset_js('seller/js/index.js')?>
    <script>
        @if(count($category) >1)
            $('.cate-item').click(function(){
                var cat_id = $(this).attr('alt');
                showData(cat_id);
            });
        @endif

        function showData(cat){
            $.getJSON('/seller/batch/ajax_list',{cat:cat},function(jsonData){
                var _innerHtml = '';
                $('.order-list').html(_innerHtml);
                $.each(jsonData.data,function(index,item){
                    _innerHtml += '<div class="i-top overflow"><img src="'+item.logo+'" class="order-img">';
                    _innerHtml +='<div class="i-detail"><span class="i-name overflow">'+item.title+'</span>';
                    _innerHtml +='<p class="i-pnum"><span class="i-price">¥'+item.show_price+'</span>';
                    _innerHtml +='<span class="i-desc pull-right">'+item.brand+'</span></p>';
                    _innerHtml +='</div></div></a></div>';
                });
                $('.order-list').html(_innerHtml);
            });
        }
    </script>

@endsection