$().ready(function(){
    $('.qu-list').delegate('li','click',function(){
        $('.qu-list li').removeClass('active');
        $(this).addClass('active');
        var get_data = $(this).attr('data-val');
        $('#install_type').val(get_data);
        if(get_data == "1"){
            $('.dao-jia').hide();
            $('.dao-dian').show();

        }else{
            $('.dao-jia').show();
            $('.dao-dian').hide();
        }

    });

    var total_price = parseFloat($('#totalPrice').val());
    $('.aui-list li').each(function(){
        $(this).delegate('input[type="checkbox"]','click',function(){
            //选择状态
            var _checked = $(this).is(':checked');
            //获取价格
            var _price = $(this).next().val();
            //计算价格，显示产品选择状态
            if(_checked){
                total_price += parseFloat(_price);
            }else{
                total_price -= parseFloat(_price);
            }
            $('#totalPrice').val(total_price);
            $('#total_price').text(total_price.toFixed(2));
        });
    });

    $('.btn-pay').click(function(){
        var pay_type = $('input[name="pay_type"]:checked').val();
        var url = BASE_URL+'pay/add_order';
        var formData = $('#payForm').serialize();
        if($("#install_type").val() == '1' && $(".c-dian").val() == ''){
            layer.open({
                content: '请选择附近门店！',
                skin: 'msg',
                time: 2 //2秒后自动关闭
            });
            return false;
        }
        //formData= decodeURIComponent(formData,true);//防止中文乱码
        //var json= JSON.parse(DataDeal.formToJson(formData));//转化为json
        //提交请求
        $.post(url,formData,function(result){
            console.log(typeof result)
            console.log('11111'+result);
            console.log('#######'+result);
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
                    return false;
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
    });

    $('.c-dian').change(function(){
        var txt = $(this).find('option:selected').text();
        var id = $(this).val();
        $(this).prev().text(txt);
    });
});
