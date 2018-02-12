@extends('sysadmin.layout')

@section('title', 'Page Title')

@section('head_css')
    <?=asset_css('/assets/layouts/layout/css/custom.min.css')?>
@endsection

@section('content')
    <div class="page-content">
        @include('/sysadmin/common/crumb')

        <div class="portlet-body">
            <ul class="nav nav-pills">
                <li class="active">
                    <a href="#" aria-expanded="true"> 基础配置 </a>
                </li>
                <li class="">
                    <a href="/sysadmin/setting/second" aria-expanded="false"> 邮件配置 </a>
                </li>
                <li class="">
                    <a href="/sysadmin/setting/third" aria-expanded="false"> 功能开关配置 </a>
                </li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane fade active in">
                    <div class="row">
                        <div class="col-md-12 col-sm-12">
                            <style>
                                .table th{text-align: right}
                            </style>
                            <form id="sub_form" target="iframe_message" action="<?=toRoute('setting/ajax_save_data')?>" method="post" enctype="multipart/form-data">
                                <table class="table" style="width: 1000px;">
                                    <tr>
                                        <th>网站名称：</th>
                                        <td><input class="form-control" type="text" name="web_name" value="<?=isset($info['web_name']) ? $info['web_name'] : ''?>"></td>
                                    </tr>
                                    <tr>
                                        <th>关键字：</th>
                                        <td><input class="form-control" type="text" name="keywords" value="<?=isset($info['keywords']) ? $info['keywords'] : ''?>"></td>
                                    </tr>
                                    <tr>
                                        <th>网站描述：</th>
                                        <td><input class="form-control" type="text" name="description" value="<?=isset($info['description']) ? $info['description'] : ''?>"></td>
                                    </tr>
                                    <tr>
                                        <th>网站版权信息：</th>
                                        <td><input class="form-control" type="text" name="copyright" value="<?=isset($info['copyright']) ? $info['copyright'] : ''?>"></td>
                                    </tr>
                                    <tr>
                                        <th>网站备案：</th>
                                        <td><input class="form-control" type="text" name="record" value="<?=isset($info['record']) ? $info['record'] : ''?>"></td>
                                    </tr>
                                    <tr>
                                        <th>网站LOGO：</th>
                                        <td>
                                            <div style="width: 30%;margin-bottom: 10px;">
                                                <?=asset_img($info['web_logo'],['width'=>'100px'])?>
                                            </div>
                                            <input type="file" name="web_logo">
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>地址栏LOGO：</th>
                                        <td>
                                            <div style="width: 30%;margin-bottom: 10px;">
                                                <?=asset_img($info['title_logo'],['width'=>'50px'])?>
                                            </div>
                                            <input type="file" name="title_logo">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" style="text-align: center">
                                            <input type="hidden" name="id" value="<?=isset($info['id']) ? $info['id'] : ''?>">
                                            <input type="submit" class="btn btn-primary btn_save" value="保 存">
                                        </td>
                                    </tr>
                                </table>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <iframe name="iframe_message" style="display: none">

        </iframe>
        <!-- END PAGE BAR -->
        <!-- BEGIN PAGE TITLE-->
        <!-- END PAGE TITLE-->
        <!-- END PAGE HEADER-->
        <!-- BEGIN DASHBOARD STATS 1-->
        <div class="clearfix"></div>
        <!-- END DASHBOARD STATS 1-->
    </div>
@endsection

@section('footer_js')
    <?=asset_js('/assets/pages/scripts/dashboard.min.js')?>
    <script type="text/javascript">

        function show_message(json) {
            if(json.status == 1001){
                layer.alert(json.msg, {
                    icon: 6
                    ,time: 0 //不自动关闭
                    ,btn: ['确定']
                    ,area: '200px'
                    ,yes: function(index){
                        layer.close(index);
                        window.location.href = "<?=toRoute('setting/index')?>";
                    }
                });
            }else{
                layer.alert(json.msg);
            }
        }
    </script>
@endsection