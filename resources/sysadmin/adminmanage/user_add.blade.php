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
                <form id="sub_form" target="iframe_message" action="<?=toRoute('adminmanage/ajax_user_save')?>" method="post" enctype="multipart/form-data">
                <table class="table" style="width: 1000px;">
                    <tr>
                        <th><span style="color: red">*</span>用户名：</th>
                        <td><input class="form-control" type="text" name="username" value="{{ $res['username'] or '' }}" required></td>
                    </tr>
                    <tr>
                        <th>密码：</th>
                        <td><input class="form-control" type="text" name="password" value=""><font color="red">(备注：为空代表不修改密码否则将会修改密码)</font></td>

                    </tr>
                    <tr>
                        <th>真实姓名：</th>
                        <td><input class="form-control" type="text" name="real_name" value="{{ $res['real_name'] or '' }}" required></td>
                    </tr>
                    <tr>
                        <th>手机：</th>
                        <td><input class="form-control" type="text" name="mobile" value="{{ $res['mobile'] or '' }}" required></td>
                    </tr>
                    <tr>
                        <th>邮箱：</th>
                        <td><input class="form-control" type="text" name="email" value="{{ $res['email'] or '' }}" ></td>
                    </tr>
                    <tr>
                        <th>角色：</th>
                        <td>
                            <select class="form-control" name="role_id" required>
                                <?php foreach($role as $k=>$v){?>
                                    <option value="<?= $v['id']?>" <?= !empty($res['role_id'])&&$v['id']==$res['role_id']?'selected':''?> ><?= $v['role_name']?></option>
                                <?php }?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" style="text-align: center">
                            <input type="hidden" name="id" value="<?=isset($res['id']) ? $res['id'] : ''?>">
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
                        window.location.href = "<?=toRoute('adminmanage/user_list')?>";
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