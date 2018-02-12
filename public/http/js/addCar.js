$().ready(function(){
    var toast = new auiToast({});
    $('.btn-save').click(function(){
        var car_number = $('.car_number').val();
        var license_plate_train = $('.license_plate_train').val();
        var car_type = $('.car_type').val();
        var driven_distance = $('.driven_distance').val();
        var car_id = $('.car_id').val();
        if(car_number == '' || car_number.length != 7){
            toast.fail({
                title:"请填7位车牌号",
                duration:1000
            });
        }else if(license_plate_train == ''){
            toast.fail({
                title:"请填写车系",
                duration:1000
            });
        }else{
            //提交请求
            $.ajax({
                type:'post',
                url:'/user/ajax_save_car',
                data:{car_id:car_id,car_number:car_number,license_plate_train:license_plate_train,car_type:car_type,driven_distance:driven_distance},
                success:function(res){
                    console.log(res);
                    var info = JSON.parse(res);
                    if(info.code == 1001){
                        toast.success({
                            title:info.msg,
                            duration:1000
                        });
                        window.location.href = '/user/carport';
                    }
                }
            })

            

        }

    });
});