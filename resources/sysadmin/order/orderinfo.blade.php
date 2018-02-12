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
        <div class="row" style="padding-top: 10px">
                {{--<div class="panel panel-default container-fluid " style="padding-top: 10px;padding-bottom: 10px;height: 100px;position: relative">--}}
                    {{--<div class="text-center" >--}}
                        {{--<div class="col-md-4">--}}
                           {{--<div>买家下单</div>--}}
                            {{--<div class="step"><span>1</span></div>--}}
                            {{--<div>{{ !empty($res['add_date'])?date('Y-m-d H:i:s',$res['add_date']):'' }}</div>--}}
                        {{--</div>--}}
                        {{--<div class="col-md-4">--}}
                            {{--<div>买家支付</div>--}}
                            {{--<div class="step"><span >2</span></div>--}}
                            {{--<div>{{ !empty($res['pay_date'])?date('Y-m-d H:i:s',$res['pay_date']):'' }}</div>--}}
                        {{--</div>--}}
                        {{--<div class="col-md-4">--}}
                            {{--<div>商家发货</div>--}}
                            {{--<div class="step"><span >3</span></div>--}}
                            {{--<div>{{ !empty($res['deliver_date'])?date('Y-m-d H:i:s',$res['deliver_date']):'' }}</div>--}}
                        {{--</div>--}}
                        {{--<div class="bar"></div>--}}
                    {{--</div>--}}
                {{--</div>--}}
            <table class="table table-bordered">
                <thead>
                <tr>
                </tr>
                </thead>
                <tbody style="padding-left: 30px">
                <tr>
                    <td colspan="3">订单状态：{{ $orderState[$res['info']['order_state']] }}
                        @if($res['info']['order_state']==1&&$app->userInfo['role_id']!=3)
                            <button class="btn btn-default btn-fh" onclick="deliver({{ $res['info']['id'] }})">发货</button>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td>订单号：{{ $res['order_no'] }}</td>
                    <td>买家：{{ $res['user_name'] }} </td>
                    <td>支付方式：微信支付</td>
                </tr>
                <tr>
                    <td>收货人：{{ $res['consignee'] }} </td>
                    <td>手机号码：{{ $res['mobile'] }}</td>
                    <td> 收货地址：{{ $res['province'].$res['city'].$res['area'].$res['address'] }}</td>
                </tr>
                </tbody>
            </table>

            <table class="table table-bordered text-center">
                <thead class="text-center">
                <tr class="active">
                    <td>商品</td>
                    <td>价格</td>
                    <td>数量</td>
                    <td>商品总价</td>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>
                        <?= asset_img($res['info']['product_img'],['class'=>'product_img'])?>
                        {{ $res['info']['product_title'] }}
                    </td>
                    <td>
                        {{ $res['info']['product_money'] }}
                    </td>
                    <td>
                        {{ $res['info']['num'] }}
                    </td>
                    <td>
                        {{ $res['money'] }}
                    </td>
                </tr>
                <tr>
                    <td class="text-right" colspan="4">订单共1件商品，总计：￥{{ $res['money'] }}（含运费 ￥0.00）</td>
                </tr>

                </tbody>
            </table>
        </div>
    </div>

    <!-- 处理售后模态框（Modal） -->
    <div class="modal fade" id="refundModel" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title text-center" id="myModalLabel">商品发货</h4>
                </div>
                <form  class="form-horizontal" action="<?=toRoute('order/process_refund')?>" method="post">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="firstname" class="col-sm-2 control-label">处理结果:</label>
                            <div class="col-sm-10">
                                <select name="state" class="form-control" required>
                                    <option value="">请选择</option>
                                    <option value="1">同意</option>
                                    <option value="2">拒绝</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="firstname" class="col-sm-2 control-label">原因:</label>
                            <div class="col-sm-10">
                                <textarea class="form-control" name="result" placeholder="请输入原因" required></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer text-center" >
                        <input type="hidden" name="id" value="{{ $res['refund']['id'] or '' }}">
                        <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                        <button type="submit" class="btn btn-primary btn_save ">保存</button>
                    </div>
                </form>

            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->

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

@section('plugins_js')
@endsection

@section('footer_js')
    <?=asset_js('/assets/pages/scripts/dashboard.min.js')?>
    <script type="text/javascript">

        $(function () {
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

        function refundCl(){
            $('#refundModel').modal({keyboard: true});
        }

        function deliver(id){
            $('#myModal').find('input[name=id]').val(id);
            $('#myModal').find('select').eq(0).prop('selected',true);
            $('#myModal').find('input[name=deliver_no]').val('');
            $('#myModal').modal({keyboard: true});
        }


    </script>
@endsection
<style type="text/css">
    .step{
        padding: 12px;
    }
    .step span{
        color: white;
        background-color: #3f444a;
        padding: 6px 12px;
        border-radius: 15px;
    }
    .bar{
        position: absolute;
        background-color:black;
        height: 20px;
        top: 0;
    }
    .product_img{
        width: 80px;
    }
</style>