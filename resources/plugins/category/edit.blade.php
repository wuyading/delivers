@extends('sysadmin.layout')

@section('title', 'Page Title')

@section('plugins_css')
@endsection

@section('head_css')
    <?=asset_css('/assets/layouts/layout/css/custom.min.css')?>
@endsection

@section('head_css')
    <style rel="stylesheet" type="text/css">
        .con-top-menu{
            margin-top:15px;
            margin-bottom:15px;
        }
        .top-menu{
            padding-bottom: 10px;
            border-bottom:1px solid #666;
        }
    </style>
@endsection

@section('content')
    <div class="page-content">
        <!-- BEGIN PAGE HEADER-->
        <!-- BEGIN PAGE BAR -->
        <div class="page-bar">
            <ul class="page-breadcrumb">
                <li>
                    <a href="{{ toRoute() }}">Home</a>
                    <i class="fa fa-circle"></i>
                </li>
                <li>
                    <span>Dashboard</span>
                </li>
            </ul>
        </div>
        <!-- END PAGE BAR -->
        <div class="row con-top-menu">
            <div class="top-menu">
                <a href="{{ toRoute('category?type='.$info['type_id']) }}" class="btn btn-primary">分类管理</a>
                <a href="{{ toRoute('category/add?type='.$info['type_id']) }}" class="btn btn-default">添加分类</a>
            </div>
        </div>
        <!-- END PAGE HEADER-->
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <form id="sub_form" action="{{ toRoute('category/ajax_save_update') }}" method="post" enctype="multipart/form-data">
                    <table class="table table-bordered">
                        <tbody>
                        <tr>
                            <th>上级菜单：</th>
                            <td>
                                <select class="form-control" name="info[parent_id]">
                                    <option value="0">作为一级菜单</option>
                                    <?=$select_categorys_html?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th>分类名称：</th>
                            <td>
                                <input type="text" name="info[name]" value="{{ $info['name'] }}" class="form-control">
                            </td>
                        </tr>

                        <tr>
                            <th>中文名称：</th>
                            <td>
                                <input type="text" name="info[china_name]" value="{{ $info['china_name'] }}" class="form-control">
                            </td>
                        </tr>

                        <tr>
                            <th>分类logo：</th>

                            <td>
                                <img id="preview" style="height: 120px;border: 1px solid #AAAAAA;" src="<?=isset($info['logo']) ? $info['logo'] : ''?>" />
                                <br /><br />
                                <input type="file" name="logo" onchange="imgPreview(this)" />
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <input type="hidden" name="id" value="{{ $info['id']}}">
                                <input type="hidden" name="type_id" value="{{ $info['type_id'] }}">
                                <input type="submit" value="提 交">
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </form>
            </div>
        </div>
        <div class="clearfix"></div>
        <!-- END DASHBOARD STATS 1-->
    </div>
@endsection

@section('footer_js')

    <?=asset_js('/assets/pages/scripts/dashboard.min.js')?>

    <script type="text/javascript">
        function imgPreview(fileDom){
            //判断是否支持FileReader
            if (window.FileReader) {
                var reader = new FileReader();
            } else {
                alert("您的设备不支持图片预览功能，如需该功能请升级您的设备！");
            }

            //获取文件
            var file = fileDom.files[0];
            var imageType = /^image\//;
            //是否是图片
            if (!imageType.test(file.type)) {
                alert("请选择图片！");
                return;
            }
            //读取完成
            reader.onload = function(e) {
                //获取图片dom
                var img = document.getElementById("preview");
                //图片路径设置为读取的图片
                img.src = e.target.result;
//                    img.style.width = "50%";
            };
            reader.readAsDataURL(file);
        }
    </script>
    <script>
        function upload() {
            var xhr = new XMLHttpRequest();
            var progress = document.getElementById("progress")
            progress.style.display = "block";

            xhr.upload.addEventListener("progress", function(e) {
                if (e.lengthComputable) {
                    var percentage = Math.round((e.loaded * 100) / e.total);
                    progress.value = percentage;
                }
            }, false);

            xhr.upload.addEventListener("load", function(e){
                console.log("上传完毕...")
            }, false);

            xhr.open("POST", "upload");
            xhr.overrideMimeType('text/plain; charset=x-user-defined-binary');
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    alert(xhr.responseText); // handle response.
                    progress.style.display = "none";
                    progress.value = 0;
                }
            };
            var file = document.getElementById("imgFile");
            var fd = new FormData();
            fd.append(file.files[0].name, file.files[0]);
            xhr.send(fd);
        }
    </script>

@endsection