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
                <form id="sub_form" target="iframe_message" action="<?=toRoute('user/ajax_save_user')?>" method="post" enctype="multipart/form-data">
                <table class="table" style="width: 1000px;">
                    <tr>
                        <th>用户名：</th>
                        <td><input class="form-control" type="text" name="username" value="{{ $res['username'] or '' }}" ></td>
                    </tr>
                    <tr>
                        <th>密码：</th>
                        <td>
                            <input class="form-control" type="text" name="password" value="" {{ empty($res['password'])?'required':'' }}>
                            <font color="red">(备注：为空代表不修改密码否则将会修改密码)</font>
                        </td>
                    </tr>
                    <tr>
                        <th>移动电话：</th>
                        <td><input class="form-control" type="text" name="mobile" value="{{ $res['mobile'] or '' }}" required></td>
                    </tr>

                    <tr>
                        <th>邮箱：</th>
                        <td><input class="form-control" type="text" name="email" value="{{ $res['email'] or '' }}" ></td>
                    </tr>

                    <tr>
                        <th>注册时间：</th>
                        <td><input class="form-control" type="text" name="register_date" value="{{ $res['created_at'] ? date('Y-m-d H:i:s', $res['created_at']) : '' }}" required disabled></td>
                    </tr>
                    <tr>
                        <th>最后登录时间：</th>
                        <td><input class="form-control" type="text" name="last_login_date" value="{{ $res['last_login_date'] or '' }}" required disabled></td>
                    </tr>

                    <tr>
                        <th>最后登录ip：</th>
                        <td><input class="form-control" type="text" name="last_ip" value="{{ $res['last_ip'] or '' }}" required disabled></td>
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
                        window.location.href = "<?=toRoute('user/index')?>";
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