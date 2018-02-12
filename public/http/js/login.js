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
            //提交请求
            $.ajax({
                type:'post',
                url:'/login/check_login',
                data:formData,
                success:function(res){
                    ///var info = JSON.parse(res);
                    //console.log(info.code)
                    if(res.code == 1001){
                        window.location.href = '/';
                    }else{
                        layer.open({
                            content: res.message,
                            skin: 'msg',
                            time: 2 //2秒后自动关闭
                        });
                    }
                }
            })

        }
    });
});