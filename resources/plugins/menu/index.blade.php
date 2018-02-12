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
        @include('/sysadmin/common/crumb')
        <div class="row con-top-menu">
            <div class="top-menu">
                <a href="{{ toRoute('menu') }}" class="btn btn-primary">菜单管理</a>
                <a href="{{ toRoute('menu/add') }}" class="btn btn-default">添加菜单</a>
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

@endsection