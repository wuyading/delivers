$().ready(function(){
    var toast = new auiToast({});
    $('.btn-address').click(function(){
        var name = $('.name').val();
        var mobile = $('.mobile').val();
        var email = $('.email').val();
        if(name == '' || mobile == '' ){
            toast.fail({
                title:"请完善信息",
                duration:1000
            });
        }else if(!(/^1[345789]\d{9}$/.test(mobile))){
            toast.fail({
                title:"手机号格式有误",
                duration:1000
            });
        }else{
            //提交请求
            toast.success({
                title:"保存成功",
                duration:1000
            });
        }
    });
});