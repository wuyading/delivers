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
            <div style="margin: 15px;">
                <a class="btn btn-danger" href="<?=toRoute('supplier/add')?>">添加供应商 <i class="fa fa-plus"></i></a>
                {{--<button class="btn btn-default dc-QrCode">导出产品二维码</button>--}}
                <br/><br/>
                <form action="{{ toRoute('supplier') }}" class="form-inline" method="get">
                    <div class="form-group">
                        <label class="control-label">渠道名：</label>
                        <input type="text" class="form-control form-control-diy "  placeholder="渠道名" style="width: 200px" name="name" value="{{ $vars['name'] or '' }}">
                        <button type="submit" class="btn btn-default">查询</button>
                    </div>
                </form>
            </div>
            <div class="col-md-12 col-sm-12">
                <table class="table table-hover">
                    <tr>
                        <th>供应商id</th>
                        <th>供应商名</th>
                        <th>联系人</th>
                        <th>地址</th>
                        <th>纬度</th>
                        <th>经度</th>
                        <th>联系电话</th>
                        <th>添加时间</th>
                        <th>操作</th>
                    </tr>

                    @foreach( $list as $item )
                        <tr>
                            <td><?=$item['id'] ?></td>
                            <td><?=$item['name'] ?></td>
                            <td><?=$item['link_people'] ?></td>
                            <td><?=$item['address'] ?></td>
                            <td><?=$item['latitude'] ?></td>
                            <td><?=$item['longitude'] ?></td>
                            <td><?=$item['mobile'] ?></td>
                            <td><?=$item['add_date']?></td>
                            <td>
                                <a href="<?=toRoute('supplier/add/'.hashids_encode($item['id']))?>" class="btn btn-primary">修改</a>
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
                    $.post('<?=toRoute('supplier/ajax_delete')?>',{'id':id},function (res) {
                        if(res.status == 1001){
                            layer.alert(res.msg, {
                                icon: 6
                                ,time: 0 //不自动关闭
                                ,btn: ['确定']
                                ,area: '200px'
                                ,yes: function(index){
                                    layer.close(index);
                                    window.location.href = '<?=toRoute("supplier/index")?>';
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