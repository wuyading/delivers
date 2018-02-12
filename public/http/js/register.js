var tag = true;
$().ready(function(){
    //手机号校验
    $('#registerForm').validate({
        rules:{
            mobile:{
                required:true,
                mobile:true
            },
            send_code:{
                required:true
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
            send_code:{
                required:'验证码不能为空'
            },
            password:{
                required:'密码不能为空'
            }
        },
        submitHandler:function(){
            var formData = $('#registerForm').serialize();
            //提交请求
            $.ajax({
                type:'post',
                url:'/login/registerSave',
                data:formData,
                success:function(res){
                    var info = JSON.parse(res);
                    console.log(info.code)
                    if(info.code == 1001){
                        window.location.href = '/login/index';
                    }
                }
            })

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
                url:'/login/send',
                data:{mobile:mobile },
                success:function(res){
                    var info = JSON.parse(res);
                    console.log(info.code)
                    if(info.code == 1001){
                        console.log(res.msg);
                        return true;
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