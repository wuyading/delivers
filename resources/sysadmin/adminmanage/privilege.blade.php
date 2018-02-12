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

                <div style="margin-top: 15px;margin-bottom: 20px">
                    <div style="">
                        <a class="btn btn-primary" onclick="submit()" href="javascript:"> 提交选择 </a>
                    </div>
                </div>

                <form id="form_tab" onsubmit="return false;">
                    <table class="table table-striped table-bordered table-hover table-checkable order-column">
                        <thead>
                        <tr>
                            <th> 选择 </th>
                            <th> 权限模块名 </th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach( $list as $item )
                            <tr>
                                <td><input type="checkbox" v="{{ $privilege['privilege'] ?? '' }}" {{ (isset($privilege) && strstr($privilege['privilege'],$item['id'])?'checked':'' )}} name="privilege[]" value="{{ $item['id'] }}"></td>
                                <td> {{ $item['name'] }} </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <input type="hidden" name="id" value="{{ $id }}">
                </form>

                <div style="margin-top: 15px;margin-bottom: 20px">
                    <div style="">
                        <a class="btn btn-primary" onclick="submit()" href="javascript:"> 提交选择 </a>
                    </div>
                </div>
            </div>
        </div>

    <!-- END PAGE HEADER-->
    <!-- BEGIN DASHBOARD STATS 1-->
    <div class="clearfix"></div>
    <!-- END DASHBOARD STATS 1-->
    </div>
@endsection

@section('footer_js')
    <?=asset_js('/assets/pages/scripts/dashboard.min.js')?>

    <script type="text/javascript">

        function submit(){
            var data = $('#form_tab').serialize();
            $.post('{{ toRoute("adminmanage/ajax_save_privilege") }}',data,function (res) {
                if(res.status == 1001){
                    layer.alert(res.msg, {
                        icon: 6
                        ,time: 0 //不自动关闭
                        ,btn: ['确定']
                        ,area: '200px'
                        ,yes: function(index){
                            layer.close(index);
                            window.location.href = '{{ toRoute("adminmanage/user_role") }}';
                        }
                    });
                }else{
                    layer.alert(res.msg, {
                        icon: 0
                        ,time: 0 //不自动关闭
                        ,btn: ['确定']
                    });
                }
            },'json');
        };
    </script>
@endsection