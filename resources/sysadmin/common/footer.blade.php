{{--<div class="page-footer">--}}
    {{--<div class="page-footer-inner"> 2014 &copy; Metronic by keenthemes.--}}
        {{--<a href="http://themeforest.net/item/metronic-responsive-admin-dashboard-template/4021469?ref=keenthemes" title="Purchase Metronic just for 27$ and get lifetime updates for free" target="_blank">Purchase Metronic!</a>--}}
    {{--</div>--}}
    {{--<div class="scroll-to-top">--}}
        {{--<i class="icon-arrow-up"></i>--}}
    {{--</div>--}}
{{--</div>--}}

<!--[if lt IE 9]>
<?=asset_js('/assets/global/plugins/respond.min.js')?>
<?=asset_js('/assets/global/plugins/excanvas.min.js')?>
<![endif]-->
<!-- BEGIN CORE PLUGINS -->
<?=asset_js('/assets/global/plugins/jquery.min.js')?>
<?=asset_js('/assets/global/plugins/bootstrap/js/bootstrap.min.js')?>
<?=asset_js('/assets/global/plugins/js.cookie.min.js')?>
<?=asset_js('/assets/global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js')?>
<?=asset_js('/assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js')?>
<?=asset_js('/assets/global/plugins/jquery.blockui.min.js')?>
<?=asset_js('assets/global/plugins/uniform/jquery.uniform.min.js')?>
<?=asset_js('/assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js')?>
<!-- END CORE PLUGINS -->
<!-- BEGIN PAGE LEVEL PLUGINS -->
@section('plugins_js')
@show
<!-- END PAGE LEVEL PLUGINS -->
<!-- BEGIN THEME GLOBAL SCRIPTS -->
<?=asset_js('/assets/global/scripts/app.min.js')?>
<!-- END THEME GLOBAL SCRIPTS -->
<!-- BEGIN PAGE LEVEL SCRIPTS -->
@section('footer_js')
@show
<!-- END PAGE LEVEL SCRIPTS -->
<!-- BEGIN THEME LAYOUT SCRIPTS -->
<?=asset_js('/assets/layouts/layout/scripts/layout.min.js')?>
<?=asset_js('/assets/layouts/layout/scripts/demo.min.js')?>
<?=asset_js('/assets/layouts/global/scripts/quick-sidebar.min.js')?>
<?=asset_js('/layer/layer.js')?>

<link href="/assets/global/plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen">

<script src="/assets/global/plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.min.js"></script>
<script src="/assets/global/plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.zh-CN.js"></script>
<!-- END THEME LAYOUT SCRIPTS -->
