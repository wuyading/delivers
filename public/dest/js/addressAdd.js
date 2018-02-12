$().ready(function(){
    var toast = new auiToast({});
    $('.btn-address').click(function(){
        var name = $('.name').val();
        var mobile = $('.mobile').val();
        var address = $('.address').val();
        if(name == '' || mobile == '' || address == ''){
            toast.fail({
                title:"请完善地址信息",
                duration:1000
            });
        }else if(!(/^1[345789]\d{9}$/.test(mobile))){
            toast.fail({
                title:"手机号格式有误",
                duration:1000
            });
        }else{
            insertAddress();
            /*//提交请求
            toast.success({
                title:"保存成功",
                duration:1000
            });*/
        }
    });
});

function insertAddress(){
    var formData = $('#addressFrom').serialize();
    $('#addressFrom').find('button').attr('disabled','disabled');
    $.ajax({
        type: "post",
        url: BASE_URL+"user/userAddressSave",
        dataType: "json",
        async:false,
        data:formData,
        success:function(data){
            var back_url=$('input[name=back_url]').val();
            if(data.code==1){
                window.location.href='/user/addressList?back_url='+back_url;
            }else{
                $('#addressFrom').find('button').removeAttr('disabled','disabled');
                $('#loginForm .error-message').text(data.msg);
            }
        },error:function(){
            console.log('error');
            $('#addressFrom').find('button').removeAttr('disabled','disabled');
        }
    });
}