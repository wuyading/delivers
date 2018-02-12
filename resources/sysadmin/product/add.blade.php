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
                                    <input type="text" id="" name="info[product_name]" value="{{ $data['product_name'] or ''}}" class="form-control" required>
                                </td>
                            </tr>
                            <tr>
                                <th><span style="color: red">*</span>车牌车型车系选择：</th>
                                <td class="form-inline">
                                    <select name="info[province]" onchange="selectRegion('#city',this.value)" class="form-control" required>
                                        <option value="">请选择车牌车型</option>
                                        @foreach($bands as $band)
                                            <option value="{{ $band['id'] }}" @if(isset($brand['parent_id']) && $brand['parent_id']==$band['id']) selected @endif>{{ $band['name'] }}</option>
                                        @endforeach
                                    </select>
                                    &nbsp;&nbsp;
                                    <select name="info[category_id]" id="city" onchange="selectRegion('#district',this.value)" class="form-control" required>
                                        <option value="">请选择车系</option>
                                        @if(isset($type))
                                            @foreach($type as $t)
                                                <option value="{{ $t['id'] }}" @if($data['category_id']==$t['id']) selected @endif>{{ $t['name'] }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th><span style="color: red">*</span>价格：</th>
                                <td>
                                    <input type="text" id="" name="info[price]" placeholder="单位：万元" value="{{ $data['price'] or ''}}" class="form-control" required>
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

                            <tr>
                                <th><span style="color: red">*</span>时间：</th>
                                <td>
                                    <input type="text" id="" name="info[buy_time]" placeholder="格式：-年-月-日" value="@if(isset($data['buy_time'])){{ date('Y-m-d',($data['buy_time']))}}@endif" class="form-control" required>
                                </td>
                            </tr>

                            <tr>
                                <th><span style="color: red">*</span>行驶里程：</th>
                                <td>
                                    <input type="text" id="" name="info[journey]" placeholder="格式：X万里" value="{{ $data['journey'] or ''}}" class="form-control" required>
                                </td>
                            </tr>

                            <tr>
                                <th>简介：</th>
                                <td>
                                    <textarea id="editor" style="width: 800px;height: 300px;" name="info[description]">{{ $data['description'] or '' }}</textarea>
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
    <?=asset_js('/assets/global/plugins/ueditor/ueditor.config.js')?>
    <?=asset_js('/assets/global/plugins/ueditor/ueditor.all.min.js')?>
    <?=asset_js('/assets/global/plugins/ueditor/lang/zh-cn/zh-cn.js')?>
    <?=asset_js('/assets/global/plugins/webuploader/webuploader.js')?>
@endsection

@section('footer_js')
    <?=asset_js('/assets/pages/scripts/dashboard.min.js')?>
    <script type="text/javascript">
        var BASE_URL = '{{ asset_link('/assets/global/plugins/webuploader') }}';
        var UPLOAD_URL = '{{ toRoute('imgup/index') }}';

        //实例化编辑器
        UE.getEditor('editor');

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

        function selectRegion(setid,val){
            $.ajax({
                url:'/sysadmin/secondHands/getCarType?parent='+val,
                type:'GET',
                dataType:'json',
                success:function(result){
                    if(result.code ==1001){
                        $(setid + " option:not(:first)").remove();
                        $("#district option:not(:first)").remove();
                        $.each(result.data,function(index,item){
                            $(setid).append(new Option(item.name, item.id));
                        });
                    }
                },
                error:function(){
                    alert('error');
                }
            });
        }

    </script>


    <?=asset_js('/assets/pages/scripts/webuploader_js_demo2.js')?>
@endsection