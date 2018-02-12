var tag = true;
$().ready(function(){
    //手机号校验
    $('#bindForm').validate({
        rules:{
            mobile:{
                required:true,
                mobile:true
            },
            send_yz:{
                required:true
            }
        },
        messages:{
            mobile:{
                required:'手机号不能为空',
                mobile:'手机号格式有误'
            },
            send_yz:{
                required:'不能为空'
            }
        },
        submitHandler:function(){
            var formData = $('#bindForm').serialize();
            formData= decodeURIComponent(formData,true);//防止中文乱码
            var json= JSON.parse(DataDeal.formToJson(formData));//转化为json
            //提交请求

        }
    });

    var t = 60;
    //发送验证码
    $('.send').click(function(){
        var mobile = $('input[name="mobile"]').val();
        if(!(/^1[345789]\d{9}$/.test(mobile))){
            $('.error-message').html("手机号码有误，请重填");
            return false;
        }else{
            $('.error-message').html("");
        }
        if(tag){
            run(t);
            tag = false;
            //发送验证码接口
            $.ajax({
                type:'post',
                url:'',
                data:{mobile:mobile },
                success:function(res){
                    console.log(res);
                    if(res.code == '1001'){

                    }
                }
            })
        }
    });
    function run(t){
        if(t>0){
            t--;
            setTimeout(function(){run(t);},1000);
            $('.send').html(t+'s');
        }else{
            tag = true;
            $('.send').html('重新发送');
        }
    }
});