@extends('sysadmin.layout')

@section('title', 'Page Title')

@section('head_css')
    <?=asset_css('/assets/layouts/layout/css/custom.min.css')?>
@endsection

@section('content')
    <div class="page-content">
    @include('/sysadmin/common/crumb')
    <!-- BEGIN PAGE TITLE-->
        <div class="row">
            <div style="margin: 15px ;">
                <form class="form-inline" method="get">
                    <div class="form-group">
                        <label class="control-label">联系人：</label>
                        <input type="text" class="form-control form-control-diy " style="width: 200px" name="user" value="{{ $vars['user'] or '' }}">
                    </div>
                    <div class="form-group">
                        <label class="control-label">手机号：</label>
                        <input type="text" class="form-control form-control-diy " style="width: 200px" name="mobile" value="{{ $vars['mobile'] or '' }}">
                    </div>
                    <div class="form-group">
                        <label class="control-label">保险公司：</label>
                        <input type="text" class="form-control form-control-diy " style="width: 200px" name="company_name" value="{{ $vars['company_name'] or '' }}">
                    </div>

                    <div class="form-group">
                        <label class="control-label">付款状态：</label>
                        <td class="form-inline">
                            <select name="pay_status" class="form-control" required>
                                <option value="WAIT" @if(isset($vars['pay_status']) && $vars['pay_status'] == 'WAIT') selected @endif>未付款</option>
                                <option value="PAID" @if(isset($vars['pay_status']) && $vars['pay_status'] == 'PAID') selected @endif>已付款</option>
                            </select>
                        </td>
                    </div>


                    <button type="submit" class="btn btn-default">查询</button>
                </form>
            </div>
            <div class="col-md-12 col-sm-12">
                <table class="table table-hover">
                    <tr>
                        <th>编号</th>
                        <th>联系人</th>
                        <th>联系电话</th>
                        <th>保险公司</th>
                        <th>付款金额</th>
                        <th>付款状态</th>
                        <th>添加时间</th>
                    </tr>

                    @foreach( $list as $item )
                        <tr>
                            <td>{{ $item['id'] }} </td>
                            <td>{{ $item['user'] }}</td>
                            <td>{{ $item['mobile'] }}</td>
                            <td>{{ $item['company_name'] }}</td>
                            <td>{{ $item['price'] or '' }}元</td>
                            <td>@if($item['pay_status'] == 'PAID') 已付款 @else 待付款 @endif</td>
                            <td>{{ toDate($item['created_at']) }}</td>
                        </tr>
                    @endforeach
                </table>
                <div class="pagination">
                    <?=$page?>
                </div>
            </div>
        </div>
    </div>
    <!-- END PAGE TITLE-->
    <!-- END PAGE HEADER-->
    <!-- BEGIN DASHBOARD STATS 1-->
    <div class="clearfix"></div>
    <!-- END DASHBOARD STATS 1-->
    </div>
@endsection

@section('footer_js')
@endsection