$().ready(function(){
    var popup = new auiPopup();
    //修改车系
    $('.car_type').change(function(){
        var text = $(this).find('option:selected').text();
        $('.type_val').text(text);
    });
    //保养方式
    $('.maintainList li').click(function(){
        $('.maintainList li').removeClass('active');
        $(this).addClass('active');
        var getdata = $(this).attr('data');
        $("input[name='func']").val(getdata);
    });
    //取车方式
    $('.qu-type li').click(function(){
        $('.qu-type li').removeClass('active');
        $(this).addClass('active');
        var get_data = $(this).attr('data');
        $("input[name='getcart_type']").val(get_data);
    });
    //选择时间
    $('.qu-date').change(function(){
        if($(this).val() != ''){
            $('.qu-time li').removeClass('active');
            $('.yu-time').text($(this).val()).addClass('active');
            $("input[name='arrange_time']").val($(this).val());
        }else{
            $('.yu-time').text('今天');
            $("input[name='arrange_time']").val('');
        }
    });
    //选择预约时间
    $('.qu-time li').click(function(){
        $('.qu-time li').removeClass('active');
        $(this).addClass('active');
    });
    //选择机油
    $('#top-jiyou').delegate('input[type="radio"]','click',function(){
        var txt = $(this).parent().parent().find('p').text();
        $('#txt-jiyou').text(txt);
    });
    //选择机滤
    $('#top-jilv').delegate('input[type="radio"]','click',function(){
        var txt = $(this).parent().parent().find('p').text();
        $('#txt-jilv').text(txt);
    });
    //选择空滤
    $('#top-konglv').delegate('input[type="radio"]','click',function(){
        var txt = $(this).parent().parent().find('p').text();
        $('#txt-konglv').text(txt);
    });
});