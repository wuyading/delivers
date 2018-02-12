$().ready(function(){
    //选择车型
    $("#slider").delegate('ul ul li','click',function(){
        $('.overlay,.popup-type').show();
    });
    //选择车系
    $('.popup-type').delegate('li','click',function(){
        $('.overlay,.popup-type').hide();
    });
    $('.overlay').click(function(){
        $('.overlay,.popup-type').hide();
    });
});