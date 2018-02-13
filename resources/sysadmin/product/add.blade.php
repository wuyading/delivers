@extends('sysadmin.layout')

@section('title', 'Page Title')

@section('plugins_css')
    <?=asset_css('/assets/global/plugins/webuploader/webuploader.css')?>
@endsection

@section('head_css')
    <?=asset_css('/assets/pages/css/webuploader.css')?>
    <style>
        .table th{ text-align: right;width: 200px;}
        .tab-title{
            color: #ddd;
            font-size: 16px;
            margin: 10px;
        }
    </style>
@endsection

@section('content')
    <div class="page-content">
    @include('/sysadmin/common/crumb')

        <!-- END PAGE HEADER-->
        <div class="row" style="margin-top: 15px">
            <div class="col-md-12 col-sm-12">
                    <form action="{{ toRoute('product/ajax_save') }}" method="post">
                        <table class="table">
                            <tbody>
                            <tr>
                                <th><span style="color: red">*</span>商品名称：</th>
                                <td>
                                    <input type="text" id="" name="info[name]" value="{{ $data['name'] or ''}}" class="form-control" required>
                                </td>
                            </tr>

                            <tr>
                                <th><span style="color: red">*</span>商品编号：</th>
                                <td>
                                    <input type="text" id="" name="info[sku]" value="{{ $data['sku'] or ''}}" class="form-control" required>
                                </td>
                            </tr>

                            <tr>
                                <th><span style="color: red">*</span>单位：</th>
                                <td>
                                    <input type="text" id="" name="info[unit]" value="{{ $data['unit'] or ''}}" class="form-control" required>
                                </td>
                            </tr>

                            <tr>
                                <th><span style="color: red">*</span>选择品牌：</th>
                                <td class="form-inline">
                                    <select name="info[brand_id]" class="form-control" required>
                                        <option value="">请选择品牌</option>
                                        @foreach($bands as $band)
                                            <option value="{{ $band['id'] }}" @if( isset($data['brand_id']) && $data['brand_id'] == $band['id']) selected @endif> {{ $band['name'] }}</option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>

                            <tr>
                                <th><span style="color: red">*</span>销售价：</th>
                                <td>
                                    <input type="text" id="" name="info[sell_price]" value="{{ $data['sell_price'] or ''}}" class="form-control" required>
                                </td>
                            </tr>

                            <tr>
                                <th><span style="color: red">*</span>规格型号：</th>
                                <td>
                                    <input type="text" id="" name="info[format]"  value="{{ $data['format'] or ''}}" class="form-control" required>
                                </td>
                            </tr>

                            <tr>
                                <th><span style="color: red">*</span>库存：</th>
                                <td>
                                    <input type="text" id="" name="info[stock]" value="{{ $data['stock'] or ''}}" class="form-control" required>
                                </td>
                            </tr>

                            <tr>
                                <th>图片：</th>

                                <td>
                                    @if (isset($data['image']) &&!empty($data['image']))
                                        <div class="update_logo">
                                            <img style="height:100px;" src="{{ $data['image'] or '' }}">
                                            <input type="hidden" class="img_path" name="info[logo]" value="{{ $data['image'] or '' }}">
                                        </div>
                                    @endif
                                    <div id="uploader-demo" class="wu-example">
                                        <div id="fileList" class="uploader-list"></div>
                                        <div id="filePicker">选择图片</div>
                                    </div>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <div style="margin-top: 20px;text-align: center;">
                            <input type="hidden" name="id" value="{{ $data['id'] or '' }}">
                            <input type="submit" class="btn btn-primary  btn_save" value="&nbsp;&nbsp;&nbsp;提&nbsp;&nbsp;&nbsp;交&nbsp;&nbsp;&nbsp;保&nbsp;&nbsp;&nbsp;存&nbsp;&nbsp;&nbsp;">
                        </div>
                    </form>
                </div>
        </div>

        <div class="clearfix"></div>
        <!-- END DASHBOARD STATS 1-->
    </div>
@endsection

@section('plugins_js')
    <?=asset_js('/assets/global/plugins/webuploader/webuploader.js')?>
@endsection

@section('footer_js')
    <?=asset_js('/assets/pages/scripts/dashboard.min.js')?>
    <script type="text/javascript">
        var BASE_URL = '{{ asset_link('/assets/global/plugins/webuploader') }}';
        var UPLOAD_URL = '{{ toRoute('imgup/index') }}';

        $('form').submit(function(){
            if($("input[name='info[gys_price]']").val()>0&&$("input[name='info[supplier_id]']").val()==0){
                alert('请输入商品供应商');
                return false;
            }
            if($("input[name='info[gys_price]']").val()<=0&&$("input[name='info[supplier_id]']").val()>0){
                alert('请输入商品供应价');
                return false;
            }
        });

        $(function(){
            $("input[name='info[type]']").change(function(){
                if($(this).val()==2){
                    $("input[name='info[address]']").attr('disabled',false);
                }else{
                    $("input[name='info[address]']").attr('disabled',true);
                }
            })
        })

    </script>


    <?=asset_js('/assets/pages/scripts/webuploader_js_demo2.js')?>
@endsection