@extends('sysadmin.layout')

@section('title', '修改个人信息')

@section('plugins_css')
@endsection

@section('head_css')
    <?=asset_css('/assets/layouts/layout/css/custom.min.css')?>
@endsection

@section('content')
    <div class="page-content">
        @include('/sysadmin/common/crumb')

        <!-- BEGIN DASHBOARD STATS 1-->
        <div class="row">
            <div class="col-lg-6 col-md-9 col-sm-12 col-xs-12">
                <form id="sub_form" target="iframe_message" action="{{ toRoute('Adminmanage/ajax_save_info') }}" method="post" enctype="multipart/form-data">
                    <table class="table table-bordered" style="margin-top: 15px">
                        <tbody>
                        <tr>
                            <th>用户名：</th>
                            <td><input type="text" name="username" disabled value="{{ $info['username'] }}"/></td>
                        </tr>
                        <tr>
                            <th>最后登录时间：</th>
                            <td><input type="text" name="last_time" disabled class="form-control" value="{{ date('Y-m-d H:i;s',$info['last_time']) }}" /></td>
                        </tr>
                        <tr>
                            <th>最后登录IP：</th>
                            <td><input type="text" name="last_ip" disabled class="form-control" value="{{ $info['last_ip'] }}" /></td>
                        </tr>
                        <tr>
                            <th>真实姓名：</th>
                            <td><input type="text" name="real_name" class="form-control" value="{{ $info['real_name'] }}"/></td>
                        </tr>
                        <tr>
                            <th>手机号：</th>
                            <td><input type="text" name="mobile" class="form-control" value="{{ $info['mobile'] }}"/></td>
                        </tr>
                        <tr>
                            <th>邮箱：</th>
                            <td><input type="text" name="email" class="form-control" value="{{ $info['email'] }}"/></td>
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

@section('plugins_js')
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
                        window.location.href = "{{ toRoute('Adminmanage/edit_info') }}";
                    }
                });
            }else{
                layer.alert(json.msg);
            }
        }
    </script>
@endsection