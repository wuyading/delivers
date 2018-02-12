$().ready(function(){
    var toast = new auiToast();
    //选择车型
    $('.car-type').change(function(){
        var txt = $(this).find('option:selected').text();
        $(this).parent().find('.aui-list-item-right').text(txt);
        $("#car-type").val($(this).val());
    });
    //选择过户次数
    $('.transfer-num li').click(function(){
        $('.transfer-num li').removeClass('active');
        $(this).addClass('active');
        $('#change').val($(this).val());
    });

    //点击提交
    $('#group-btn').click(function(){
        var car_type = $("#car-type").val();
        var change = $('#change').val();
        var gongli = $('#gongli').val();
        var name = $('#name').val();
        var mobile = $('#mobile').val();
        var msg = '';
        if(car_type ==''){
            msg = '请选择车型';
        }else if(gongli == ''){
            msg = '请输入行使里程';
        }else if(change == ''){
            msg = '请选择过户次数';
        }else if(name == ''){
            msg = '请输入联系人';
        }else if(mobile == '' || !(/^1[345789]\d{9}$/.test(mobile))){
            msg = '请输入正确电话';
        }else{
            //提交数据



        }
        if(msg != ''){
            toast.fail({
                title:msg,
                duration:2000
            });
        }

    });
});