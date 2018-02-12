@extends('sysadmin.layout')

@section('title', 'Page Title')

@section('head_css')
    <?=asset_css('/assets/layouts/layout/css/custom.min.css')?>
@endsection

@section('content')
    <div class="page-content">
    @include('/sysadmin/common/crumb')
    <!-- BEGIN PAGE TITLE-->
        <div class="row">
            <div style="margin: 15px 15px 15px 0;">
                <a class="btn btn-danger" href="/sysadmin/adminmanage/role_add">添加角色 <i class="fa fa-plus"></i></a>
            </div>
            <div class="col-md-12 col-sm-12">
                <table class="table table-hover">
                    <tr>
                        <th>编号</th>
                        <th>角色名</th>
                        <th>描述</th>
                        <th>操作</th>
                    </tr>

                    @foreach( $list as $item )
                        <tr>
                            <td>{{ $item['id'] }}</td>
                            <td>{{ $item['role_name'] }}</td>
                            <td>{{ $item['description'] }}</td>
                            <td>
                                <a href="<?=toRoute('adminmanage/role_add/'.hashids_encode($item['id']))?>" class="btn btn-primary">修改</a>
                                <a href="<?=toRoute('adminmanage/role_privilege/'.hashids_encode($item['id']))?>" class="btn btn-primary">权限设置</a>
                                <a class="btn btn-danger" href="javascript:void(0)" onclick="ajaxDelete(<?=$item['id']?>)">删除</a>
                            </td>
                        </tr>
                    @endforeach
                </table>
                <div class="pagination">
                    <?=$page?>
                </div>
            </div>
        </div>
    </div>
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
        function ajaxDelete(id){
            layer.alert('确定删除吗？', {
                icon: 6
                ,time: 0 //不自动关闭
                ,btn: ['确定', '取消']
                ,area: '200px'
                ,yes: function(index){
                    layer.close(index);
                    $.post('<?=toRoute('adminmanage/ajax_role_delete')?>',{'id':id},function (res) {
                        if(res.status == 1001){
                            layer.alert(res.msg, {
                                icon: 6
                                ,time: 0 //不自动关闭
                                ,btn: ['确定']
                                ,area: '200px'
                                ,yes: function(index){
                                    layer.close(index);
                                    window.location.href = '<?=toRoute("adminmanage/user_role")?>';
                                }
                            });
                        }else{
                            layer.alert(res.msg, {icon: 0,time:0,closeBtn: 0});
                        }
                    },'json');
                }
                ,no: function(index){
                    layer.close(index);
                }
            });
        }
    </script>
@endsection