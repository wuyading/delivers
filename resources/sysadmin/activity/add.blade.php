@extends('sysadmin.layout')

@section('title', 'Page Title')

@section('plugins_css')
    <?=asset_css('/assets/global/plugins/webuploader/webuploader.css')?>
@endsection

@section('head_css')
    <?=asset_css('/assets/pages/css/webuploader.css')?>
    <style>
        .table th{text-align: right}
    </style>
@endsection

@section('content')
    <div class="page-content">
    @include('/sysadmin/common/crumb')
    <!-- END PAGE HEADER-->
        <div class="row">
            <div class="col-md-12 col-sm-12">
                <form id="sub_form" name="sub_form" action="{{ toRoute('activity/ajax_save') }}" method="post">
                    <table class="table">
                                <tbody>
                                <tr>
                                    <th>{{--<span style="color: red">*</span>活动分类：--}}</th>
                                    <td class="form-inline">
                                         <select class="form-control" disabled>
                                             <option value="">请选择分类</option>
                                             @foreach($category as $_option)
                                                <option @if($_option['id']==$data['activity_category']) selected @endif value="{{ $_option['id'] }}">{{ $_option['name'] }}</option>
                                            @endforeach
                                         </select>
                                    </td>
                                </tr>
                                <tr>
                                     <th><span style="color: red">*</span>商品名称：</th>
                                      <td>
                                          <select name="info[product_id]" id="product_id" onchange="addName(this)" value="{{ $data['product_id'] or '' }}" class="form-control" required>
                                              <option value="">请选择商品</option>
                                              @foreach($product as $pro)
                                                    <option @if($data['product_id']==$pro['id']) selected @endif value="{{ $pro['id'] }}"  price="{{ $pro['price'] }}" stock="{{ $pro['stock'] }}" >{{ $pro['title'] }}</option>
                                              @endforeach
                                          </select>
                                      </td>
                                 </tr>

                                <tr>
                                    <th><span style="color: red">*</span>活动名称：</th>
                                    <td>
                                        <input type="text" id="info_name" name="info[title]" value="{{ $data['title'] or ''}}" class="form-control" required>
                                    </td>
                                </tr>

                                <tr>
                                    <th>商品价格：</th>
                                    <td class="form-inline" id="product_price">

                                    </td>
                                </tr>

                                <tr>
                                    <th><span style="color: red">*</span>活动价：</th>
                                    <td class="form-inline">
                                        <input type="text" name="info[price]" id="price" value="{{ $data['price'] or '' }}" class="form-control" required>
                                    </td>
                                </tr>
                                @if(!in_array($data['activity_category'],[63,62]))
                                <tr>
                                    <th><span style="color: red">*</span>库存：</th>
                                    <td class="form-inline">
                                        <input type="text" name="info[stock]" id="stock" value="{{ $data['stock'] or '' }}" class="form-control" required>
                                    </td>
                                </tr>
                                
                                <tr>
                                     <th><span style="color: red">*</span>商品类型：</th>
                                      <td id="radio_type">
                                          <label><input type="radio" @if($data['type']==1) checked @endif name="info[type]" value="1">普通商品</label>
                                          <label><input type="radio" @if($data['type']==2) checked @endif name="info[type]" value="2">自提商品</label>
                                      </td>
                                 </tr>

                                <tr>
                                     <th><span style="color: red">*</span>自提地址：</th>
                                      <td>
                                          <input name="info[address_ids]" type="hidden" value="{{ $data['address_ids'] or '' }}" id="select_address">
                                          <select multiple="multiple" class="SlectBox" onclick="addCheckValue($(this).val())" placeholder="选择自提地址" required>
                                             @foreach($address as $add)
                                                  <option @if(in_array($add['id'],explode(',',$data['address_ids']))) selected @endif value="{{ $add['id'] }}">{{ $add['address'] }}</option>
                                              @endforeach
                                          </select>
                                      </td>
                                 </tr>
                                @endif
                                <tr>
                                    <th><span style="color: red">*</span>抢购时间：</th>
                                    <td class="form-inline">
                                       <span class="input-group date form_datetime " data-date="" data-date-format="yyyy-mm-dd hh:ii:ss" data-link-field="dtp_input1" style="width: 220px" >
                                           <input class="form-control " size="16" type="text" value="{{ $data['start_date'] or '' }}" readonly placeholder="开始时间" required>
                                           <span class="input-group-addon">
                                               <span class="glyphicon glyphicon-th"></span>
                                           </span>
                                       </span>
                                                <input type="hidden" id="dtp_input1" value="{{ $data['start_date'] or '' }}" name="info[start_date]" />
                                                -
                                                <span class="input-group date form_datetime " data-date="" data-date-format="yyyy-mm-dd hh:ii:ss" data-link-field="dtp_input2" style="width: 220px">
                                        <input class="form-control " size="16" type="text" value="{{ $data['end_date'] or '' }}" readonly placeholder="结束时间" required>
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-th"></span>
                                        </span>
                                    </span>
                                        <input type="hidden" id="dtp_input2" value="{{ $data['end_date'] or '' }}" name="info[end_date]" />
                                    </td>
                                </tr>
                                @if(!in_array($data['activity_category'],[63,62]))
                                    <tr>
                                        <th><span style="color: red">*</span>每人限购：</th>
                                        <td class="form-inline">
                                             <input type="text" name="info[limit]" id="limit" value="{{ $data['limit'] or '' }}" class="form-control" required>个
                                        </td>
                                    </tr>
                                @endif
                                @if($data['activity_category'] ==63) {{--拍拍拍--}}
                                    <tr>
                                        <th><span style="color: red">*</span>活动周期：</th>
                                        <td class="form-inline">
                                             <input type="text" name="info[cycle]" value="{{ $data['cycle'] or '' }}" value="{{ $data['cycle'] or '' }}" class="form-control" required>分钟
                                        </td>
                                    </tr>

                                    <tr>
                                        <th><span style="color: red">*</span>降价规则：</th>
                                        <td class="form-inline">
                                             每<input type="text" name="info[rules]" value="{{ $data['rules'] or '' }}" class="form-control" required>
                                            分钟降<input type="text" name="info[rule_price]" value="{{ $data['rule_price'] or '' }}" class="form-control" required>元
                                        </td>
                                    </tr>
                                    <tr>
                                        <th><span style="color: red">*</span>保底价：</th>
                                        <td class="form-inline">
                                             <input type="text" name="info[protected_start]" value="{{ $data['protected_start'] or '' }}" class="form-control" required> -
                                            <input type="text" name="info[protected_end]" value="{{ $data['protected_end'] or '' }}" class="form-control" required>
                                        </td>
                                    </tr>
                                @endif
                                @if($data['activity_category'] ==61)
                                    <tr>
                                        <th><span style="color: red">*</span>时间限制：</th>
                                        <td class="form-inline">
                                             <select name="info['limit_time']" class="form-control" required>
                                                 @for($i=1;$i<41;$i++)
                                                 <option @if($data['limit_time']==$i) selected @endif value="{{ $i }}">{{ $i }}</option>
                                                 @endfor
                                             </select>小时
                                        </td>
                                    </tr>
                                @endif
                                </tbody>
                            </table>
                    <div style="margin-top: 20px;text-align: center;">
                        <input type="hidden" name="id" value="{{ $data['id'] or '' }}">
                        <input type="hidden" name="info[activity_category]" value="{{ $data['activity_category'] or '' }}" >
                        <input type="submit" class="btn btn-primary btn_save" value="&nbsp;&nbsp;&nbsp;提&nbsp;&nbsp;&nbsp;交&nbsp;&nbsp;&nbsp;保&nbsp;&nbsp;&nbsp;存&nbsp;&nbsp;&nbsp;">
                    </div>
                </form>
            </div>
        </div>

        <div class="clearfix"></div>
        <!-- END DASHBOARD STATS 1-->
    </div>
@endsection

@section('footer_js')
    <?=asset_js('/assets/pages/scripts/dashboard.min.js')?>
    <?=asset_js('/assets/pages/scripts/jquery.sumoselect.min.js')?>
    <?=asset_css('/assets/pages/css/sumoselect.min.css')?>

    <script type="text/javascript">
        var price = $("#product_id").find("option:selected").attr('price');
        if(price){
            $("#product_price").text(price+' 元');
        }
        $(document).ready(function () {
            window.asd = $('.SlectBox').SumoSelect({ csvDispCount: 3, selectAll:true, captionFormatAllSelected: "全部选择" });
            $('.SlectBox').on('sumo:opened', function(o) {
                console.log("dropdown opened", o)
            });
        });

        $(function () {
            $('.form_datetime').datetimepicker({
                language: 'zh-CN',
                weekStart: 1,
                todayBtn: 1,
                autoclose: 1,
                todayHighlight: 1,
                startView: 2,
                forceParse: 0,
                showMeridian: 1
            });
        });

        function addCheckValue(checkVal){
            $('#select_address').val(checkVal);
        }

        function addName(obj){
            var checkText = $(obj).find("option:selected").text();
            var price = $(obj).find("option:selected").attr('price');
            var stock = $(obj).find("option:selected").attr('stock');
            $('#info_name').val(checkText);
            $("#product_price").text('');
            if(price){
                $("#product_price").text(price+' 元');
            }
            $("#stock").val(stock);
        }
    </script>
    <script type="text/javascript">
        function show_message(msg) {
            layer.alert(msg);
        }
    </script>
@endsection