$().ready(function(){
    var toast = new auiToast({});
    //选择车型
    $('.car-type').change(function(){
        var txt = $(this).find('option:selected').text();
        $(this).parent().find('.aui-list-item-right').text(txt);
        $("#car-type").val($(this).val());
    });
    //选择过户次数
    $('.transfer-num li').click(function(){
        $('.transfer-num li').removeClass('active');
        $(this).addClass('active');
        $('#change').val($(this).val());
    });

    //点击提交
    $('#group-btn').click(function(){
        var id = $("#insurance_company_id").val();
        var car_type = $("#car-type").val();
        var times = $('#change').val();
        var mileage = $('#gongli').val();
        var user = $('#name').val();
        var mobile = $('#mobile').val();
        var pay_type = $('input[name="pay_type"]:checked').val();
        var msg = '';
        var url = BASE_URL+'insuranceCompany/ajaxInsureSave';
        if(id == ''){
            msg = '保险公司参数错误';
        }else if(car_type ==''){
            msg = '请选择车型';
        }else if(mileage == ''){
            msg = '请输入行使里程';
        }else if(times == ''){
            msg = '请选择过户次数';
        }else if(user == ''){
            msg = '请输入联系人';
        }else if(mobile == '' || !(/^1[345789]\d{9}$/.test(mobile))){
            msg = '请输入正确电话';
        }else if(pay_type == '' || !pay_type){
            msg = '请选择支付方式';
        }else{

            $.post(url,{id:id,car_type:car_type,times:times,mileage:mileage,user:user,mobile:mobile,pay_type:pay_type},function(result){
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
});