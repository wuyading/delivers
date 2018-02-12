var tag = true;
$().ready(function(){
    //手机号校验
    $('#resetForm').validate({
        rules:{
            mobile:{
                required:true,
                mobile:true
            },
            send_code:{
                required:true
            },
            password:{
                required:true,
                minlength:8
            },
            password2:{
                required:true,
                equalTo:'#password'
            }
        },
        messages:{
            mobile:{
                required:'手机号不能为空',
                mobile:'手机号格式有误'
            },
            send_code:{
                required:'不能为空'
            },
            password:{
                required:'密码不能为空',
                minlength:'输入最少8位密码'
            },
            password2:{
                required:'密码不能为空',
                equalTo:'请输入一样的密码'
            }
        },
        submitHandler:function(){
            var formData = $('#resetForm').serialize();
            //提交请求
            $.ajax({
                type:'post',
                url:'/login/resetPasswordSave',
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
                    console.log(res);
                    if(res.code == 1001){
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