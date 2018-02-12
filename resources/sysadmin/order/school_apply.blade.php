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
                        <input type="text" class="form-control form-control-diy " style="width: 200px" name="name" value="{{ $vars['name'] or '' }}">
                    </div>
                    <div class="form-group">
                        <label class="control-label">手机号：</label>
                        <input type="text" class="form-control form-control-diy " style="width: 200px" name="mobile" value="{{ $vars['mobile'] or '' }}">
                    </div>
                    <div class="form-group">
                        <label class="control-label">驾校名称：</label>
                        <input type="text" class="form-control form-control-diy " style="width: 200px" name="school_name" value="{{ $vars['school_name'] or '' }}">
                    </div>

                    <div class="form-group">
                        <label class="control-label">驾照类型：</label>
                        <td class="form-inline">
                            <select name="driver_type" class="form-control">
                                <option value="">--驾照类型--</option>
                                <option value="A1" @if(isset($vars['driver_type']) && $vars['driver_type'] == 'A1') selected @endif>A1</option>
                                <option value="A2" @if(isset($vars['driver_type']) && $vars['driver_type'] == 'A2') selected @endif>A2</option>
                                <option value="A3" @if(isset($vars['driver_type']) && $vars['driver_type'] == 'A3') selected @endif>A3</option>
                                <option value="B1" @if(isset($vars['driver_type']) && $vars['driver_type'] == 'B1') selected @endif>B1</option>
                                <option value="B2" @if(isset($vars['driver_type']) && $vars['driver_type'] == 'B2') selected @endif>B2</option>
                                <option value="C1" @if(isset($vars['driver_type']) && $vars['driver_type'] == 'C1') selected @endif>C1</option>
                                <option value="C2" @if(isset($vars['driver_type']) && $vars['driver_type'] == 'C2') selected @endif>C2</option>
                                <option value="C3" @if(isset($vars['driver_type']) && $vars['driver_type'] == 'C3') selected @endif>C3</option>
                            </select>
                        </td>
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
                        <th>姓名</th>
                        <th>手机号</th>
                        <th>身份证</th>
                        <th>驾照类型</th>
                        <th>预约时间</th>
                        <th>预约驾校</th>
                        <th>付款金额</th>
                        <th>付款状态</th>
                        <th>添加时间</th>
                    </tr>

                    @foreach( $list as $item )
                        <tr>
                            <td>{{ $item['id'] }} </td>
                            <td>{{ $item['name'] }}</td>
                            <td>{{ $item['mobile'] }}</td>
                            <td>{{ $item['identity'] }}</td>
                            <td>{{ $item['driver_type'] }}</td>
                            <td>{{ toDate($item['subscribe_time']) }}</td>
                            <td>{{ $item['driver_name'] }}</td>
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