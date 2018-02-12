@extends('sysadmin.layout')

@section('title', 'Page Title')

@section('plugins_css')
@endsection

@section('head_css')
    {{--<link href="../assets/layouts/layout/css/custom.min.css" rel="stylesheet" type="text/css" />--}}
    <style rel="stylesheet" type="text/css">
        .con-top-menu{
            margin-top:15px;
            margin-bottom:15px;
        }
        .top-menu{
            padding-bottom: 10px;
            border-bottom:1px solid #666;
        }
    </style>
@endsection

@section('content')
    <div class="page-content">
        <!-- BEGIN PAGE HEADER-->
        <!-- BEGIN PAGE BAR -->
        <div class="page-bar">
            <ul class="page-breadcrumb">
                <li>
                    <a href="{{ toRoute() }}">Home</a>
                    <i class="fa fa-circle"></i>
                </li>
                <li>
                    <span>Dashboard</span>
                </li>
            </ul>
        </div>
        <!-- END PAGE BAR -->
        <div class="row con-top-menu">
            <div class="top-menu">
                <a href="{{ toRoute('category/type') }}" class="btn btn-primary">类别管理</a>
                <a href="{{ toRoute('category/type_add') }}" class="btn btn-default">添加类别</a>
            </div>
        </div>
        <!-- END PAGE HEADER-->
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>排序</th>
                            <th>id</th>
                            <th>菜单名称</th>
                            <th>管理操作</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?=$category_html?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="clearfix"></div>
        <!-- END DASHBOARD STATS 1-->
    </div>
@endsection

@section('footer_js')
    <script type="text/javascript">
        function ajaxDelete(id){
            if(confirm('确定删除吗,删除后将不能恢复？')){
                $.post('<?=toRoute('category/ajax_delete_type')?>',{'id':id},function (res) {
                    if(res.status == 1001){
                        layer.alert(res.msg, {
                            icon: 6
                            ,time: 0 //不自动关闭
                            ,btn: ['确定']
                            ,area: '200px'
                            ,yes: function(index){
                                layer.close(index);
                                window.location.reload();
                            }
                        });
                    }else{
                        layer.alert(res.msg, {icon: 0,time:0,closeBtn: 0});
                    }
                },'json');
            }
        }
    </script>
@endsection