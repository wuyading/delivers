$().ready(function(){
    $('.cate-list').delegate('.cate-item','click',function(){
        $('.cate-item').removeClass('active');
        $(this).addClass('active');
    });
});