@extends('sysadmin.layout')

@section('title', 'Page Title')

@section('plugins_css')
    <?=asset_css('/assets/global/plugins/webuploader/webuploader.css')?>
@endsection

@section('head_css')
    <?=asset_css('/assets/pages/css/webuploader.css')?>
    <style>
        .table th{ text-align: right;width: 120px;}
        .tab-title{
            color: #ddd;
            font-size: 16px;
            margin: 10px;
        }
    </style>
@endsection

@section('content')
    <div class="page-content">
        @include('/sysadmin/common/crumb')
                <!-- END PAGE HEADER-->
        <div class="row" style="margin-top: 15px">

            <div class="col-md-8 col-sm-8">
                <form id="sub_form" target="iframe_message" action="<?=toRoute('supplier/ajax_save')?>" method="post">

                    <table class="table">
                        <tbody>
                        @if($admin['role_id']==3||$admin['role_id']==1)
                            <tr>
                                <th><span style="color: red">*</span>登录账号：</th>
                                <td>
                                    <input class="form-control" type="text" name="username" value="{{ $supplierAdmin['username'] or '' }}" {{ !empty($supplierAdmin['username'])?'disabled':'' }}  required>
                                    @if(!empty($supplierAdmin['id']))
                                    <input type="hidden" name="username" value="{{ $supplierAdmin['username'] or '' }} ">
                                    @endif
                                </td>
                            </tr>

                            <tr>
                                <th><span style="color: red">*</span>登录密码：</th>
                                <td>
                                    <input class="form-control" type="text" name="password" value="" {{ empty($supplierAdmin['id'])?'required':'' }}>
                                    <font color="red">为空表示不修改原密码</font>
                                </td>
                            </tr>
                            <input type="hidden" name="admin_id" value="{{ $supplierAdmin['id'] or '' }}">
                        @endif
                        <tr>
                            <th><span style="color: red">*</span>渠道商名：</th>
                            <td><input class="form-control" type="text" name="name" value="{{ $res['name'] or '' }}" required></td>
                        </tr>
                        <tr>
                            <th>地址：</th>
                            <td>
                                <input class="form-control" type="text" name="address" value="{{ $res['address'] or '' }}" >
                            </td>
                        </tr>
                        <tr>
                            <th>纬度：</th>
                            <td><input class="form-control" type="text" name="latitude" value="{{ $res['latitude'] or '' }}" required></td>
                        </tr>

                        <tr>
                            <th>经度：</th>
                            <td><input class="form-control" type="text" name="longitude" value="{{ $res['longitude'] or '' }}" required></td>
                        </tr>

                        <tr>
                            <th>联系电话：</th>
                            <td><input class="form-control" type="text" name="mobile" value="{{ $res['mobile'] or '' }}" required></td>
                        </tr>


                        <tr>
                            <th>联系人姓名</th>
                            <td><input class="form-control" type="text" name="link_people" value="{{ $res['link_people'] or '' }}" required ></td>
                        </tr>

                        <tr>
                            <th>LOGO：</th>
                            <td>
                                @if (isset($res['logo']) &&!empty($res['logo']))
                                    <div class="update_logo">
                                        <img style="height:100px;" src="{{ $res['logo'] or '' }}">
                                        <input type="hidden" class="img_path" name="info[logo]" value="{{ $res['logo'] or '' }}">
                                    </div>
                                @endif
                                    <div id="uploader-demo" class="wu-example">
                                        <div id="fileList" class="uploader-list"></div>
                                        <div id="filePicker">选择图片</div>
                                    </div>
                            </td>
                        </tr>
                        <tr>
                            <th>简介：</th>
                            <td>
                                <textarea id="editor" style="width: 800px;height: 300px;" name="remark">{{ $res['remark'] or '' }}</textarea>
                            </td>
                        </tr>
                        <tr>
                            <th>添加时间：</th>
                            <td><input class="form-control" type="text" name="last_login_time" value="{{ $res['add_date'] or '' }}" required disabled></td>
                        </tr>

                        <tr>
                            <th></th>
                            <td>
                                <div class="tab-pane active in" id="tab_6_2" style="display: none">

                                    <div id="uploader" class="wu-example">
                                        <div class="queueList">
                                        </div>
                                        <div class="statusBar" style="display:none;">
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>

                        </tbody>
                    </table>
                    <div style="margin-top: 20px;text-align: center;">
                        <input type="hidden" name="id" value="{{ $res['id'] or ''}}">
                        <input type="submit" class="btn btn-primary btn_save" value="保 存"> |
                        <input type="button" class="btn btn-info" onclick="history.back(-1)" value="返 回">
                    </div>
                </form>
            </div>
        </div>

        <iframe name="iframe_message" style="display: none">

        </iframe>
    </div>
@endsection

@section('plugins_js')
    <?=asset_js('/assets/global/plugins/ueditor/ueditor.config.js')?>
    <?=asset_js('/assets/global/plugins/ueditor/ueditor.all.min.js')?>
    <?=asset_js('/assets/global/plugins/ueditor/lang/zh-cn/zh-cn.js')?>
    <?=asset_js('/assets/global/plugins/webuploader/webuploader.js')?>
@endsection

@section('footer_js')
    <?=asset_js('/assets/pages/scripts/dashboard.min.js')?>

    <script type="text/javascript">
        var BASE_URL = '{{ asset_link('/assets/global/plugins/webuploader') }}';
        var UPLOAD_URL = '{{ toRoute('fileupload') }}';
        var logo_image = '{{ $res['logo'] or '' }}';
        //实例化编辑器
        UE.getEditor('editor');
        function show_message(json) {
            if(json.status == 1001){
                layer.alert(json.msg, {
                    icon: 6
                    ,time: 0 //不自动关闭
                    ,btn: ['确定']
                    ,area: '200px'
                    ,yes: function(index){
                        layer.close(index);
                        window.location.href = "<?=toRoute('supplier/index')?>";
                    }
                });
            }else{
                layer.alert(json.msg,{
                    icon:0
                    ,time:0
                });
            }
        }

        function delete_img(obj) {
            $(obj).parent().parent().remove();
        }
    </script>
    <?=asset_js('/assets/pages/scripts/webuploader_js_demo.js')?>
@endsection