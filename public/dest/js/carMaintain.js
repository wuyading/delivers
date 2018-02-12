$().ready(function(){
    var popup = new auiPopup();
    var toast = new auiToast({});
    //修改车系
    $('.car_type').change(function(){
        var text = $(this).find('option:selected').text();
        $('.type_val').text(text);
    });
    //保养方式
    $('.maintainList li').click(function(){
        $('.maintainList li').removeClass('active');
        $(this).addClass('active');
        var getdata = $(this).attr('data');
        $("input[name='func']").val(getdata);
        if(getdata == 2) {
            $(".s_baoyang").hide();
            $(".xbx").show();
        }else {
            $(".s_baoyang").show();
        }
    });
    //取车方式
    $('.qu-type li').click(function(){
        $('.qu-type li').removeClass('active');
        $(this).addClass('active');
        var get_data = $(this).attr('data');
        $("input[name='getcart_type']").val(get_data);
    });
    //选择时间
    $('.qu-date').change(function(){
        if($(this).val() != ''){
            $('.qu-time li').removeClass('active');
            $('.yu-time').text($(this).val()).addClass('active');
            $("input[name='arrange_time']").val($(this).val());
        }else{
            $('.yu-time').text('今天');
            $("input[name='arrange_time']").val('');
        }
    });
    //选择预约时间
    $('.qu-time li').click(function(){
        $('.qu-time li').removeClass('active');
        $(this).addClass('active');
    });
    var total_price = 0;
    $('.aui-popup li').each(function(){
        $(this).delegate('input[type="checkbox"]','click',function(){
            //todo 清空同级别选择
            //$(this).nextAll().attr('checked',false);


            //这边要减去原来 选中的值
            var priceOld = 0;
            if($(this).closest("ul").find('input[type="checkbox"]:checked').length >0){
                for(var i = 0;i<$(this).parent().parent().parent().find('input[type="checkbox"]:checked').length;i++){
                    priceOld += parseFloat($(this).closest("ul").find('input[type="checkbox"]:checked').eq(i).closest("li").find("span").children().text());
                }
            };
            total_price -= parseFloat(priceOld);


            // return;
            // total_price -=0；


            $(this).parent().parent().parent().find('input[type="checkbox"]').removeAttr("checked");
            $(this).prop("checked",true);

            var _alt =  $(this).parent().attr('alt');
            var text_id = '#txt-'+_alt;
            //选择状态
            var _checked = $(this).is(':checked');
            //获取产品名称
            var txt = $(this).parent().parent().find('p').text();
            //获取价格
            var _price = $(this).parent().parent().find('span').children().text();
            //计算价格，显示产品选择状态
            if(_checked){
                $(text_id).text(txt);
                total_price += parseFloat(_price)*2;
            }else{
                $(text_id).text('');
                total_price += parseFloat(_price)*2;
            }
            $('#totalPrice').val(total_price.toFixed(2));
            $('#total_price').text(total_price.toFixed(2));
            $(".aui-popup").hide();
            popup.hide();

        });
    });
    //选择机油
    /*$('#top-jiyou').delegate('input[type="radio"]','click',function(){
        var txt = $(this).parent().parent().find('p').text();
        $('#txt-jiyou').text(txt);
    });
    //选择机滤
    $('#top-jilv').delegate('input[type="radio"]','click',function(){
        var txt = $(this).parent().parent().find('p').text();
        $('#txt-jilv').text(txt);
    });
    //选择空滤
    $('#top-konglv').delegate('input[type="radio"]','click',function(){
        var txt = $(this).parent().parent().find('p').text();
        $('#txt-konglv').text(txt);
    });*/

    //表单校验
    $('#carForm').validate({
        rules:{
            series_id:{
                required:true
            },
        },
        messages:{
            series_id:{
                required:'请选择车系'
            },
        },
        submitHandler:function(){
            var formData = $('#carForm').serialize();
            var pay_type = $('input[name="pay_type"]:checked').val();
            //formData= decodeURIComponent(formData,true);//防止中文乱码
            //var json= JSON.parse(DataDeal.formToJson(formData));//转化为json

            //提交请求
            $.ajax({
                type:'post',
                url:BASE_URL+'maintain/save',
                data:formData,
                success:function(result){
                    console.log(result);
                    if(result.code == 2001){
                        toast.fail({
                            title:result.msg,
                            duration:2000
                        });
                        return false;
                    }
                    console.log(mobile_type)
                    console.log(pay_type)
                    if(mobile_type == 1){
                        //android 请求
                        if(pay_type == 'alipay'){
                            result = result.data;
                            $("#ali_pay").attr('onclick',"WebViewJavaScriptFunction.onCallAliPayPayForCheTuShi('"+result.timeoutExpress+"','"+result.totalAmount+"','"+result.subject+"','"+result.body+"','"+result.outTradeNo+"','"+result.timestamp+"','"+result.notifyUrl+"','"+result.returnUrl+"')");
                            $("#ali_pay").click();
                            return false;
                        }else{
                            $("#wei_pay").attr('onclick',"WebViewJavaScriptFunction.onCallWeiXinPay('"+result+"')");
                            $("#wei_pay").click();
                        }
                    }else {
                        //ios 请求
                        if(pay_type == 'alipay'){
                            window.webkit.messageHandlers.onCallAliPayPayForCheTuShi.postMessage(result);
                        }else{
                            window.webkit.messageHandlers.onCallWeiXinPayPayForCheTuShi.postMessage(result);
                        }
                    }
                }
            })
        }
    });

});