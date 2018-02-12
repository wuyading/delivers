@extends('sysadmin.layout')

@section('title', 'Page Title')

@section('head_css')
    <?=asset_css('/assets/layouts/layout/css/custom.min.css')?>
@endsection

@section('content')
    <div class="page-content">
        @include('/sysadmin/common/crumb')

        <div class="portlet-body">
            <ul class="nav nav-pills">
                <li class="">
                    <a href="/sysadmin/setting/index" aria-expanded="false"> 基础配置 </a>
                </li>
                <li class="">
                    <a href="/sysadmin/setting/second" aria-expanded="false"> 邮件配置 </a>
                </li>
                <li class="active">
                    <a href="#" aria-expanded="true"> 功能开关配置 </a>
                </li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane fade active in">
                    <div class="row">
                        <div class="col-md-12 col-sm-12">
                            <style>
                                .table th{text-align: right}
                            </style>
                            <form id="sub_form" target="iframe_message" action="{{ toRoute('setting/ajax_save_third') }}" method="post" enctype="multipart/form-data">
                                <table class="table" style="width: 1000px;">
                                    <tr>
                                        <th>是否开启评论：</th>
                                        <td>
                                            <label><input name="comment" type="radio" value="1" {{ (isset($info['comment']) && $info['comment'] == 1 || empty($info['comment']) )?'checked':'' }} />是</label>
                                            <label><input name="comment" type="radio" value="2" {{ (isset($info['comment']) && $info['comment'] == 2 )?'checked':'' }} />否</label>
                                        </td>
                                    </tr>

                                    <tr>
                                        <th>评论默认状态：</th>
                                        <td>
                                            <label><input name="comment_type" type="radio" value="1" {{ (isset($info['comment_type']) && $info['comment_type'] == 1 || empty($info['comment_type']) )?'checked':'' }} />通过</label>
                                            <label><input name="comment_type" type="radio" value="2" {{ (isset($info['comment_type']) && $info['comment_type'] == 2 )?'checked':'' }} />不通过</label>
                                            <label><input name="comment_type" type="radio" value="3" {{ (isset($info['comment_type']) && $info['comment_type'] == 3 )?'checked':'' }} />待审核</label>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td colspan="2" style="text-align: center">
                                            <input type="hidden" name="id" value="{{ isset($info['id']) ? $info['id'] : '' }}">
                                            <input type="submit" class="btn btn-primary btn_save" value="保 存">
                                        </td>
                                    </tr>
                                </table>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <iframe name="iframe_message" style="display: none">

            </iframe>
        <!-- END PAGE BAR -->
        <!-- BEGIN PAGE TITLE-->
        <!-- END PAGE TITLE-->
        <!-- END PAGE HEADER-->
        <!-- BEGIN DASHBOARD STATS 1-->
        <div class="clearfix"></div>
        <!-- END DASHBOARD STATS 1-->
    </div>
    </div>
@endsection

@section('footer_js')
    <?=asset_js('/assets/pages/scripts/dashboard.min.js')?>
        <script type="text/javascript">

            function show_message(json) {
                if(json.status == 1001){
                    layer.alert(json.msg, {
                        icon: 6
                        ,time: 0 //不自动关闭
                        ,btn: ['确定']
                        ,area: '200px'
                        ,yes: function(index){
                            layer.close(index);
                            window.location.href = "{{  toRoute('setting/third') }}";
                        }
                    });
                }else{
                    layer.alert(json.msg);
                }
            }
        </script>
@endsection