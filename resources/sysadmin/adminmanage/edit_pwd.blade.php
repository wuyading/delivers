@extends('sysadmin.layout')

@section('title', '修改密码')

@section('head_css')
    <?=asset_css('/assets/layouts/layout/css/custom.min.css')?>
@endsection

@section('content')
    <div class="page-content">
    @include('/sysadmin/common/crumb')

        <!-- BEGIN DASHBOARD STATS 1-->
        <div class="row">
            <div class="col-lg-6 col-md-9 col-sm-12 col-xs-12">
                <form id="sub_form" target="iframe_message" action="{{ toRoute('Adminmanage/ajax_save_pwd') }}" method="post" enctype="multipart/form-data">
                    <table class="table table-bordered" style="margin-top: 15px">
                        <tbody>
                            <tr>
                                <th>用户名：</th>
                                <td><input type="text" name="username" disabled value="{{ $app->userInfo['username'] }}"/></td>
                            </tr>
                            <tr>
                                <th>旧密码：</th>
                                <td><input type="password" name="old_pwd" class="form-control" /></td>
                            </tr>
                            <tr>
                                <th>新密码：</th>
                                <td><input type="password" name="pwd" class="form-control" /></td>
                            </tr>
                            <tr>
                                <th>重复新密码：</th>
                                <td><input type="password" name="repwd" class="form-control"/></td>
                            </tr>
                            <tr class="text-center">
                                <td colspan="2"> <input type="submit" value="提 交" class="btn btn-primary"></td>
                            </tr>
                        </tbody>
                    </table>
                </form>
            </div>
        </div>

        <iframe name="iframe_message" style="display: none">

        </iframe>

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
                        window.location.reload();
                    }
                });
            }else{
                layer.alert(json.msg, {
                    icon: 0
                    ,time: 0 //不自动关闭
                    ,btn: ['确定']
                });
            }
        }
    </script>
@endsection