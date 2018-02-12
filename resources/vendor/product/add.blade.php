@extends('vendor.layout.base')

@section('title','发布商品')

@section('css')
    <?=asset_css('seller/css/webuploader.css')?>
@endsection

@section('header')
    <header>
        <div class="header-back" onclick="history.go(-1);"></div>
        发布商品
    </header>
@endsection
<script>
    var imgId = 'img';
    var num = 1;
</script>
@section('content')
    <form class="release-form" id="release-form" action="{{ toRoute('product/save') }}" method="post" enctype="multipart/form-data">
        <div class="top">
            <div class="form-line">
                <input type="text" placeholder="请输入商品名称" name="title" class="p-name"/>
            </div>
            <div class="form-line">
                <textarea class="p-desc" placeholder="请输入商品描述" name="remark"></textarea>
            </div>

            <div class="upload-area">
                <div class="upload-list">
                    <!-- 图片上传区域 -->
                    <div class="file-item pull-left">
                        <img id="img" src="" style="display:none;">
                        <img src="/seller/images/img-delete.png" class="img-delete" style="display:none;">
                        <input type="hidden" class="img_path" name="logo[]" value="">
                    </div>
                </div>
                <a href="javascript:WebViewJavaScriptFunction.onCallPhoto(imgId)">
                    <?=asset_img('seller/images/icon-service.png',['id'=>'img-upload'])?>
                </a>
                <div id="filePicker" style="display:none;">选择图片</div>
                </a>
            </div>
        </div>

        <div class="p-line">
            <div class="form-line">
                <p class="pull-left p-title">类别</p>
                <input type="text" style="display: none;"  name="cat_id" id="cat_id">
                <input type="text" placeholder="请选择商品类别" id="productCate" class="p-name" readonly/>
                <div class="right-arrow"></div>
                <select class="select-cate" >
                    <option value="">--请选择--</option>
                    @foreach($category as $item)
                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-line">
                <p class="pull-left p-title">品牌</p>
                <input type="text" placeholder="请输入商品品牌" name="brand" class="p-name"/>
            </div>
            <div class="form-line">
                <p class="pull-left p-title">单价</p>
                <input type="text" placeholder="请输入商品单价" name="price" class="p-name"/>
            </div>
            <div class="form-line">
                <p class="pull-left p-title">单位</p>
                <input type="text" placeholder="请选择商品单位" name="unit" id="productDan" class="p-name" readonly/>
                <div class="right-arrow"></div>
                <select class="select-dan">
                    <option value="">--请选择--</option>
                    @foreach($unitArr as $unit)
                        <option value="{{ $unit }}">{{ $unit }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <button type="submit" class="btn-release">确认发布</button>
    </form>
@endsection

@section('js')
    <script>
        var UPLOAD_URL = BASE_URL+'seller/fileupload';
        function callJavaScript(itemId,url){
            $("#" + itemId).prop("src",url).css('display','block');
            $("#" + itemId).parent().find('.img_path').val(url);
            $("#" + itemId).parent().find('.img-delete').css('display','block');
            imgId = 'img'+num;
            num++;
            var img = '<div class="file-item pull-left">'+
                      '<img id="'+imgId+'" src="" style="display:none;">'+
                      '<img src="/seller/images/img-delete.png" class="img-delete" style="display:none;">'+
                      '<input type="hidden" class="img_path" name="logo[]" value="">'+
                      '</div>';
            $('.upload-list').append(img);
        }
    </script>
    <?=asset_js('seller/public/js/plugins/validate.js')?>
    <?=asset_js('seller/js/webuploader.js')?>
    <?=asset_js('seller/js/release.js')?>
@endsection