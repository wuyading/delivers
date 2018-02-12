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
        .fuhao{
            border: 1px solid #666;
            padding: 0 5px;
            margin-right: 5px;
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
        <div class="row con-top-menu">
            <div class="top-menu">
                <a href="{{ toRoute('category?type='.$type) }}" class="btn btn-primary">分类管理</a>
                <a href="{{ toRoute('category/add?type='.$type) }}" class="btn btn-default">添加分类</a>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                <table class="table table-bordered table-striped" id="category_show">
                    <thead>
                        <tr>
                            <th>排序</th>
                            <th>id</th>
                            <th>名称</th>
                            <th>管理操作</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?=$category_menu?>
                    </tbody>
                </table>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                <table class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th>名称</th>
                        <th>管理操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?=$category_type_menu?>
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
        $('.sel_two').hide();
        $('#category_show').delegate('.fuhao','click',function(){
            var id = $(this).parent().parent().attr('data-id');
            $('.sel_two').each(function(){
                if($(this).attr('data-id') == id){
                    $(this).toggle();
                }
            });
        });

        function ajaxDelete(id){
            layer.alert('确定删除吗？', {
                icon: 6
                ,time: 0 //不自动关闭
                ,btn: ['确定', '取消']
                ,area: '200px'
                ,yes: function(index){
                    layer.close(index);
                    $.post('<?=toRoute('category/ajax_delete')?>',{'id':id},function (res) {
                        if(res.status == 1001){
                            layer.alert(res.msg, {
                                icon: 6
                                ,time: 0 //不自动关闭
                                ,btn: ['确定']
                                ,area: '200px'
                                ,yes: function(index){
                                    layer.close(index);
                                    window.location.href = 'category/index';
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
    <style>
        .layer-anim {
            top: 50%!important;
        }
    </style>
@endsection