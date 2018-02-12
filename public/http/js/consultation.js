$().ready(function(){
    var toast = new auiToast({});
    $('.btn-address').click(function(){
        var name = $('.name').val();
        var mobile = $('.mobile').val();
        var product_id = $('.product_id').val();
        var type = 2;
        if(name == '' || mobile == ''){
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

            $.ajax({
                type:'post',
                url:'/secondHands/consultationSave',
                data:{product_id:product_id,name:name,mobile:mobile,type:type},
                success:function(res){
                    var info = JSON.parse(res);
                    console.log(info.code)
                    if(info.code == 1001){
                        toast.success({
                            title:"保存成功",
                            duration:2000
                        });
                        window.location.href='/secondHands/index';
                    }
                }
            })
        }
    });
});