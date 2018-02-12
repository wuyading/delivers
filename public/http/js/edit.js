$().ready(function(){
    var dialog = new auiDialog({});
    var toast = new auiToast({});
    //点击修改姓名
    $(".edit-txt").click(function(){
        dialog.prompt({
            title:"修改昵称",
            text:'修改昵称',
            buttons:['取消','确定']
        },function(ret){
            if(ret.buttonIndex == 2){
                var txt = $('.aui-dialog-body').find('input').val();
                $('.edit-name').text(txt);
            }
        })
    });
    //选择性别
    $('.edit-sex').change(function(){
        var text = $(this).find('option:selected').text();
        $(this).prev().text(text);
    });
    //选择生日
    $('.edit-birth').change(function(){
        var text = $(this).val();
        $(this).prev().text(text);
    });
    //选择星座
    $('.edit-xing').change(function(){
        var text = $(this).find('option:selected').text();
        $(this).prev().text(text);
    });
    $('.btn-submit').click(function(){
        var username = $('.edit-name').text();
        var sex = $('.edit-sex').prev().text();
        var head_image = $('.edit-head').attr('src');
        if(username == '' || sex == ''){
            toast.fail({
                title:"请完善资料",
                duration:2000
            });
        }else{
            $.ajax({
                type:'post',
                url:'/user/ajax_save_edit',
                data:{username:username,sex:sex,head_image:head_image},
                success:function(res){
                    var info = JSON.parse(res);
                    console.log(info.code)
                    if(info.code == 1001){
                        toast.success({
                            title:info.msg,
                            duration:2000
                        });
                        window.location.href='/user/index';
                    }
                }
            })

        }
    });
});