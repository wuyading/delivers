$().ready(function(){
    var toast = new auiToast({});
    $('#group-btn').click(function(){
        var id = $("#id").val();
        var name = $("#name").val();
        var mobile = $("#mobile").val();
        var identity = $('#identity').val();
        var driver_type = $('#jia').val();
        var time = $('#time').val();
        var pay_type = $('input[name="pay_type"]:checked').val();
        var msg = '';
        var url = BASE_URL+'driverSchool/ajaxApplySave';
        if(name == ''){
            msg = '请输入姓名';
        }else if(mobile == '' || !(/^1[345789]\d{9}$/.test(mobile))){
            msg = '请输入正确电话';
        }else if(identity == ''){
            msg = '请输入身份证号';
        }else if(driver_type == ''){
            msg = '请输入驾照类型';
        }else if(time == ''){
            msg = '预约时间';
        }else if(pay_type == '' || !pay_type){
            msg = '请选择支付方式';
        }else{

            $.post(url,{id:id,name:name,mobile:mobile,identity:identity,time:time,pay_type:pay_type,driver_type:driver_type},function(result){
                if(mobile_type == 1){
                    //android 请求
                    if(pay_type == 'alipay'){
                        result = result.data;
                        $("#ali_pay").attr('onclick',"WebViewJavaScriptFunction.onCallAliPayPayForCheTuShi('"+result.timeoutExpress+"','"+result.totalAmount+"','"+result.subject+"','"+result.body+"','"+result.outTradeNo+"','"+result.timestamp+"','"+result.notifyUrl+"','"+result.returnUrl+"')");
                        $("#ali_pay").click();
                        return false;
                    }else{
                        console.log(result);
                        console.log((typeof result))
                        if((typeof result) != 'string'){
                            toast.fail({
                                title:result.msg,
                                duration:2000
                            });
                        }
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

                //if(mobile_type == 2){
                //}
            });

        }
        if(msg != ''){
            toast.fail({
                title:msg,
                duration:2000
            });
        }

    });
    //选择预约时间
    $('.yu-time').bind('change',function(){
        $('input[name="time"]').val($(this).val()).blur();
    });
    //选择驾照
    $('.jia').change(function(){
        if($(this).val() != ''){
            $('input[name="driver_type"]').val($(this).find('option:selected').text()).blur();
        }else{
            $('input[name="driver_type"]').val('');
        }

    });
});