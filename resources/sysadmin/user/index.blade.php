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
                <a class="btn btn-danger" href="<?=toRoute('user/add')?>">添加用户 <i class="fa fa-plus"></i></a>
            </div>
            <div class="col-md-12 col-sm-12">
                <div>
                    <form action="#" method="get">
                        移动电话：<input type="text" name="mobile" value="{{ $where['mobile'] ?? '' }}">
                        <input type="submit" class="btn btn-primary" value="搜 索"/>
                    </form>
                </div>
                <table class="table table-hover">
                    <tr>
                        <th>编号</th>
                        <th>用户名</th>
                        <th>邮箱</th>
                        <th>移动电话</th>
                        <th>推荐人</th>
                        <th>推荐人编号</th>
                        <th>真实姓名</th>
                        <th>身份证号码</th>
                        <th>支付宝账号</th>
                        <th>最后登录时间</th>
                        <th>操作</th>
                    </tr>

                    @foreach( $list as $item )
                        <tr>
                            <td>{{ $item['id'] }} </td>
                            <td>{{ $item['username'] }}</td>
                            <td>{{ $item['email'] }}</td>
                            <td>{{ $item['mobile'] }}</td>
                            <td>{{ $item['recommender'] }}</td>
                            <td>{{ $item['recommender_id'] }}</td>
                            <td>{{ $item['realname'] }}</td>
                            <td>{{ $item['identity'] }}</td>
                            <td>{{ $item['alipay_account'] }}</td>
                            <td>{{ $item['last_login_date'] }}</td>
                            <td>
                                <a href="<?=toRoute('user/add/'.hashids_encode($item['id']))?>" class="btn btn-primary">修改</a>
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
                    $.post('<?=toRoute('user/ajax_delete_user')?>',{'id':id},function (res) {
                        if(res.status == 1001){
                            layer.alert(res.msg, {
                                icon: 6
                                ,time: 0 //不自动关闭
                                ,btn: ['确定']
                                ,area: '200px'
                                ,yes: function(index){
                                    layer.close(index);
                                    window.location.href = '<?=toRoute("user/index")?>';
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