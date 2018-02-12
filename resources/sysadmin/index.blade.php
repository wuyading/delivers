@extends('sysadmin.layout')

@section('title', 'Page Title')

@section('plugins_css')
@endsection

@section('head_css')
    <link href="../assets/layouts/layout/css/custom.min.css" rel="stylesheet" type="text/css" />
@endsection

@section('content')
    <div class="page-content">
    @include('/sysadmin/common/crumb')
        <!-- BEGIN PAGE TITLE-->
        <!-- END PAGE TITLE-->
        <!-- END PAGE HEADER-->
        <!-- BEGIN DASHBOARD STATS 1-->
        <div class="row">
            <div style="margin-top: 120px;font-size: 50px;text-align: center">欢迎使用<br>后台管理系统</div>
        </div>
        <div class="clearfix"></div>
        <!-- END DASHBOARD STATS 1-->
    </div>
@endsection

@section('plugins_js')
@endsection

@section('footer_js')
    <?=asset_js('/assets/pages/scripts/dashboard.min.js')?>
@endsection