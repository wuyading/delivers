@extends('sysadmin.layout')

@section('title', 'Page Title')

@section('plugins_css')
@endsection

@section('head_css')
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
                <form action="{{ toRoute('menu/ajax_save_update') }}" method="post">
                    <table class="table table-bordered">
                        <tbody>
                        <tr>
                            <th>上级菜单：</th>
                            <td>
                                <select class="form-control" name="info[parent_id]">
                                    <option value="0">作为一级菜单</option>
                                    <?=$select_categorys_html?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th>菜单名称：</th>
                            <td>
                                <input type="text" name="info[name]" value="{{ $info['name'] }}" class="form-control">
                            </td>
                        </tr>
                        <tr>
                            <th>别名：</th>
                            <td>
                                <input type="text" name="info[alias]" value="{{ $info['alias'] }}" class="form-control">
                            </td>
                        </tr>
                        <tr>
                            <th>链接：</th>
                            <td>
                                <input type="text" name="info[link]" value="{{ $info['link'] }}" class="form-control">
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <input type="hidden" name="id" value="{{ $info['id']}}">
                                <input type="submit" value="提 交">
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </form>
            </div>
        </div>
        <div class="clearfix"></div>
        <!-- END DASHBOARD STATS 1-->
    </div>
@endsection

@section('footer_js')
@endsection