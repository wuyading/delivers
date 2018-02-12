$().ready(function(){
    //手机号校验
    $('#loginForm').validate({
        rules:{
            mobile:{
                required:true,
                mobile:true
            },
            password:{
                required:true
            }
        },
        messages:{
            mobile:{
                required:'手机号不能为空',
                mobile:'手机号格式有误'
            },
            password:{
                required:'密码不能为空'
            }
        },
        submitHandler:function(){
            var formData = $('#loginForm').serialize();
            formData= decodeURIComponent(formData,true);//防止中文乱码
            var json= JSON.parse(DataDeal.formToJson(formData));//转化为json
            //提交请求

        }
    });
});