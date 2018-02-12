$().ready(function(){
    var toast = new auiToast({});

    //点击提交
    $('.btn-msg').click(function(){
        var content = $('.msg').val();
        var msg = '';
        if(content == ''){
            toast.fail({
                title:'请填写反馈内容',
                duration:2000
            });
        }else{
            //提交请求
            $.ajax({
                type:'post',
                url:'/user/feedBackSave',
                data:{content:content},
                success:function(res){
                    var info = JSON.parse(res);
                    console.log(info)
                    if(info.code == 1001){
                        toast.success({
                            title:info.msg,
                            duration:2000
                        });
                        window.location.href = '/user/index';
                    }
                }
            })



        }

    });
});