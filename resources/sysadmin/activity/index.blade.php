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
            <div style="margin: 15px ;">
                <a class="btn btn-danger" href="{{ toRoute('activity/add?cat='.$cat) }}">新增活动 <i class="fa fa-plus"></i></a>
                <br>  <br>
                <form action="{{ toRoute('activity/index') }}" class="form-inline" method="get">
                    <div class="form-group">
                        <label class="control-label">商品名称：</label>
                        <input type="text" class="form-control form-control-diy "  placeholder="请输入商品名称" style="width: 200px" name="title" value="{{ $vars['title'] or '' }}">
                    </div>
                    <input type="hidden" name="cat" value="{{ $cat }}" />
                    <button type="submit" class="btn btn-default">查询</button>
                    <input type="reset"  class="btn btn-default" value="重置">
                </form>

            </div>
            <div class="col-md-12 col-sm-12">
                <table class="table table-hover">
                    <tr>
                        <th>编号</th>
                        <th>商品名称</th>
                        <th>活动时间</th>
                        <th>每人限购</th>
                        <th>活动状态</th>
                        <th>操作</th>
                    </tr>

                    @foreach( $list as $item )
                        <tr>
                            <td>{{ $item['id'] }} </td>
                            <td>{{ $item['title'] }}</td>
                            <td>{{ date('Y-m-d H:i:s',$item['start_date']) }} 至 {{ date('Y-m-d H:i:s',$item['end_date']) }}</td>
                            <td>{{ $item['limit'] }}</td>
                            <td>
                               @if($item['is_onsale'] ==2)
                                    已下架
                                @else
                                    @if($item['start_date'] >time())
                                        未开始
                                    @elseif($item['end_date'] < time())
                                        已结束
                                    @else
                                        @if($item['status']==2) 已结束 @else 进行中 @endif
                                    @endif
                                @endif
                                </td>
                            <td>
                                <a href="<?=toRoute('activity/add/'.hashids_encode($item['id']))?>" class="btn btn-primary">修改</a>
                                <a class="btn btn-danger" href="javascript:void(0)" onclick="ajaxDelete(<?=$item['id']?>,0,'结束')">结束</a>
                                @if($item['is_onsale']==2)
                                    <a class="btn btn-danger" href="javascript:void(0)" onclick="ajaxDelete(<?=$item['id']?>,1,'上架')">上架</a>
                                @else
                                    <a class="btn btn-danger" href="javascript:void(0)" onclick="ajaxDelete(<?=$item['id']?>,2,'下架')">下架</a>
                                @endif
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
        function ajaxDelete(id,type,title){
            layer.alert('确定'+title+'吗？', {
                icon: 6
                ,time: 0 //不自动关闭
                ,btn: ['确定', '取消']
                ,area: '200px'
                ,yes: function(index){
                    layer.close(index);
                    $.post('<?=toRoute('activity/ajax_delete')?>',{'id':id,'type':type},function (res) {
                        if(res.code == 1001){
                            layer.alert(res.msg, {
                                icon: 6
                                ,time: 0 //不自动关闭
                                ,btn: ['确定']
                                ,area: '200px'
                                ,yes: function(index){
                                    layer.close(index);
                                    location.reload();
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