@extends('sysadmin.layout')

@section('title', 'Page Title')

@section('head_css')
    <?=asset_css('/assets/layouts/layout/css/custom.min.css')?>
@endsection

@section('content')
    <div class="page-content">
        @include('/sysadmin/common/crumb')

        <div class="row">
            <div class="col-md-12 col-sm-12">
                <style>
                    .table th{text-align: right}
                </style>
                <form id="sub_form" target="iframe_message" action="<?=toRoute('adminmanage/ajax_save_role')?>" method="post" enctype="multipart/form-data">
                <table class="table" style="width: 1000px;">
                    <tr>
                        <th><span style="color: red">*</span>角色名称：</th>
                        <td><input class="form-control" type="text" name="role_name" value="<?=isset($info['role_name']) ? $info['role_name'] : ''?>" required></td>
                    </tr>
                    <tr>
                        <th>角色描述：</th>
                        <td>
                            <textarea name="description" id="" cols="100" rows="10">{{ isset($info['description'])?$info['description']:'' }}</textarea>
                        </td>
                    </tr>
                    <tr>
                        <th><span style="color: red">*</span>排序：</th>
                        <td>
                            <input type="text" name="sortid" value="<?=isset($info['sortid']) ? $info['sortid'] : '1'?>"> <span style="color:red">数字越大显示越靠前</span>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" style="text-align: center">
                            <input type="hidden" name="id" value="<?=isset($info['id']) ? $info['id'] : ''?>">
                            <input type="submit" class="btn btn-primary btn_save" value="保 存"> |
                            <input type="button" class="btn btn-info" onclick="history.back(-1)" value="返 回">
                        </td>
                    </tr>
                </table>
                </form>
            </div>
        </div>

        <iframe name="iframe_message" style="display: none">

        </iframe>

    <!-- END PAGE HEADER-->
    <!-- BEGIN DASHBOARD STATS 1-->
    <div class="clearfix"></div>
    <!-- END DASHBOARD STATS 1-->
    </div>
@endsection

@section('footer_js')
    <?=asset_js('/assets/pages/scripts/dashboard.min.js')?>
    <script type="text/javascript">

    </script>

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
                        window.location.href = "<?=toRoute('adminmanage/user_role')?>";
                    }
                });
            }else{
                layer.alert(json.msg,{
                    icon:0
                    ,time:0
                });
            }
        }
    </script>
@endsection