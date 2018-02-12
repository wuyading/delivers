$().ready(function(){
    //选择车型
    $("#slider").delegate('ul ul li','click',function(){
        var id = this.value;
        $.ajax({
            type:'post',
            url:'/secondHands/getCarTypeById',
            data:{id:id},
            success:function(res){
                $(".popup-type").empty();
                var info = JSON.parse(res);
                console.log(info.code)
                if(info.code == 1001){
                    console.log(info.data)
                    var arr = info.data;
                    $(arr).each(function(){
                        var html = '<a href="/secondHands/index?category_type='+this.id+'">'+'<li>'+this.name+'</li>'+'</a>';
                        $(".popup-type").append(html);
                    });

                }
            }
        })
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