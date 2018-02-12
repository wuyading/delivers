$().ready(function(){
    var toast = new auiToast({});
    $('.btn-save').click(function(){
        var car_no = $('.car_no').val();
        var car_type = $('.car_type').val();
        if(car_no == ''){
            toast.fail({
                title:"请填写车牌号",
                duration:1000
            });
        }else if(car_type == ''){
            toast.fail({
                title:"请选择车系",
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