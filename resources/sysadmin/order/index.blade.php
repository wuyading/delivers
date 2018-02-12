@extends('sysadmin.layout')

@section('title', 'Page Title')

@section('head_css')
    <?=asset_css('/assets/layouts/layout/css/custom.min.css')?>
@endsection
<style type="text/css">
    .vars .form-group{margin-bottom: 10px!important;}
</style>
@section('content')
    <div class="page-content">
    @include('/sysadmin/common/crumb')
    <!-- BEGIN PAGE TITLE-->
        <div class="row">
            <div style="margin: 15px ">
                <form  action="{{ toRoute('order') }}" class="form-inline vars" method="get">
                    <div class="form-group">
                         <label class="control-label">商品名称：</label>
                        <input type="text" class="form-control form-control-diy "  placeholder="请输入商品名称" style="width: 200px" name="product_title" value="{{ $vars['product_title'] or '' }}">
                    </div>
                    <div class="form-group">
                        <label class="control-label">订单编号：</label>
                        <input type="text" class="form-control form-control-diy "  placeholder="订单编号" style="width: 200px" name="order_no" value="{{ $vars['order_no'] or '' }}">
                    </div>
                    <div class="form-group">
                        <label class="control-label">收货人姓名：</label>
                        <input type="text" class="form-control form-control-diy "  placeholder="收货人姓名" style="width: 200px" name="consignee" value="{{ $vars['consignee'] or '' }}">
                    </div>
                    <div class="form-group">
                        <label class="control-label">收货人手机：</label>
                        <input type="text" class="form-control form-control-diy "  placeholder="收货人手机" style="width: 200px" name="mobile" value="{{ $vars['mobile'] or '' }}">
                    </div>
                    <div class="form-group">
                       <label class="control-label">支付状态：</label>
                       <select name="pay_state" class="form-control form-control-diy" style="width: 200px">
                           <option value="">请选择</option>
                           <option value="1" {{ isset($vars['pay_state'])&&$vars['pay_state']==1?'selected':'' }} >已支付</option>
                           <option value="0" {{ isset($vars['pay_state'])&&$vars['pay_state']==='0'?'selected':'' }}>未支付</option>
                       </select>
                    </div>
                    <div class="form-group">
                        <label class="control-label">创建时间：</label>
                               <span class="input-group date form_datetime " data-date="" data-date-format="yyyy-mm-dd hh:ii:ss" data-link-field="dtp_input1" style="width: 220px">
                                   <input class="form-control " size="16" type="text" value="{{ $vars['start_date'] or '' }}" readonly placeholder="开始时间">
                                   <span class="input-group-addon">
                                       <span class="glyphicon glyphicon-th"></span>
                                   </span>
                               </span>
                        <input type="hidden" id="dtp_input1" value="{{ $vars['start_date'] or '' }}" name="start_date" />
                        -
                            <span class="input-group date form_datetime " data-date="" data-date-format="yyyy-mm-dd hh:ii:ss" data-link-field="dtp_input2" style="width: 220px">
                                <input class="form-control " size="16" type="text" value="{{ $vars['end_date'] or '' }}" readonly placeholder="结束时间">
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-th"></span>
                                </span>
                            </span>
                        <input type="hidden" id="dtp_input2" value="{{ $vars['end_date'] or '' }}" name="end_date" />
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-default">查询</button>
                        <input type="reset"  class="btn btn-default" value="重置">
                    </div>
                </form>
            </div>
            <div style="margin: 15px;">
                <span>总共{{ $totalItems }}个订单，包含{{ $countProduct }}个商品，总金额：￥{{ $sumMoney }}</span>
            </div>
            <div class="col-md-12 col-sm-12">
                <table class="table table-hover">
                    <tr>
                        <th>订单号</th>
                        <th>买家id</th>
                        <th>买家昵称</th>
                        <th>商品id</th>
                        <th>商品名称</th>
                        <th>商品图片</th>
                        <th>支付状态</th>
                        <th>订单状态</th>
                        <th>数量</th>
                        <th>金额</th>
                        <th>收货人姓名</th>
                        <th>收货人手机</th>
                        <th>下单时间</th>
                        <th>操作</th>
                    </tr>
                    @foreach( $list as $item )
                        <tr>
                            <td><?=$item['order_no'] ?></td>
                            <td><?=$item['user_id'] ?></td>
                            <td><?=$item['user_name'] ?></td>
                            <td><?=$item['product_id'] ?></td>
                            <td><?=$item['product_title'] ?></td>

                            <td><?= asset_img($item['product_img'])?></td>

                            <td><?=$payState[$item['pay_state']] ?></td>

                            <td><?=$orderState[$item['order_state']] ?></td>

                            <td><?=$item['num'] ?></td>
                            <td><?=$item['money'] ?></td>
                            <td><?=$item['consignee'] ?></td>
                            <td><?=$item['mobile'] ?></td>
                            <td><?=toDate($item['add_time']) ?></td>

                            <td>
                                <a href="<?=toRoute('order/info/'.hashids_encode($item['oi_id']))?>" class="btn btn-primary">查看</a>
                            </td>
                        </tr>
                    @endforeach
                </table>
                <div class="pagination">
                    {!!  isset($page) ? $page : ''  !!}
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

    <!-- 模态框（Modal） -->
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title text-center" id="myModalLabel">商品发货</h4>
                </div>
                <form id="sub_form" class="form-horizontal" target="iframe_message" action="<?=toRoute('order/ajax_deliver_goods')?>" method="post">
                <div class="modal-body">
                        <div class="form-group">
                            <label for="firstname" class="col-sm-2 control-label">物流公司:</label>
                            <div class="col-sm-10">
                                <select name="deliver_company" class="form-control" required>
                                    @foreach($deliverCompany as $k=>$v)
                                        <option value="{{ $k }}">{{ $v }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="firstname" class="col-sm-2 control-label">快递单号:</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="deliver_no" placeholder="请输入快递单号" required>
                            </div>
                        </div>
                </div>
                <div class="modal-footer text-center" >
                    <input type="hidden" name="id" value="">
                    <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                    <button type="submit" class="btn btn-primary btn_save btn-deliver">发货</button>
                </div>
                </form>

            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->



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

        $(function () {
            $('.form_datetime').datetimepicker({
                language:  'zh-CN',
                weekStart: 1,
                todayBtn:  1,
                autoclose: 1,
                todayHighlight: 1,
                startView: 2,
                forceParse: 0,
                showMeridian: 1
            });

            $('#sub_form').submit(function(){
                var deliver_company=$(this).find('select[name=deliver_company]').val();
                var deliver_no=$(this).find('input[name=deliver_no]').val();
                var id=$(this).find('input[name=id]').val();

                if(!deliver_company){
                    alert('物流公司不能为空');
                    return false;
                }
                if(!deliver_no){
                    alert('物流单号不能为空');
                    return false;
                }

                $.post('<?=toRoute('order/ajax_deliver_goods')?>', {'deliver_company':deliver_company,deliver_no:deliver_no,id:id},function (res) {
                    if(res.status == 1001){
                        layer.alert(res.msg, {
                            icon: 6
                            ,time: 0 //不自动关闭
                            ,btn: ['确定']
                            ,area: '200px'
                            ,yes: function(index){
                                layer.close(index);
                                window.location.href = '<?=toRoute("order/index")?>';
                            }
                        });
                    }else{
                        layer.alert(res.msg, {icon: 0,time:0,closeBtn: 0});
                    }
                },'json');
                return false;
            })


        });

        function deliver(id){
            $('#myModal').find('input[name=id]').val(id);
            $('#myModal').find('select').eq(0).prop('selected',true);
            $('#myModal').find('input[name=deliver_no]').val('');
            $('#myModal').modal({keyboard: true});
        }


    </script>
@endsection
<style>
    .table img{
        width: 80px;
        height: 80px;
    }
    .form-control-diy {
        display:inline!important;;
        margin-right: 20px;
    }
</style>