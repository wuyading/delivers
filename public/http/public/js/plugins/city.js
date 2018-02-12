
    //加载省份
    function initProvince(){
        $.ajax({
            type: "GET",
            url: baseUrl+"findRegionByParentId/100000",
            dataType: "json",
            contentType : 'application/json',
            async:false,
            crossDomain: true,
            success: function (data){
                $(data).each(function(){
                    var html = '<li data-id="'+this.id+'">'+this.regionName+'</li>';
                    $('.province-list').append(html);
                });
                //点击省份显示城市
                $('.province-list').delegate('li','click',function(){
                    $('.over-menu li').removeClass('active');
                    $('#city').show().addClass('active');
                    $('#area').hide();
                    $('.province-list li').removeClass('active');
                    $(this).addClass('active');
                    var id = $(this).attr('data-id');
                    $('#provinceId').val(id);
                    initCity(id);
                });
            }, error: function () {
                console.log("error");
            }
        });
    }
    //加载城市
    function initCity(id){
        $.ajax({
            type: "GET",
            url: baseUrl+"findRegionByParentId/"+id,
            dataType: "json",
            contentType : 'application/json',
            async:false,
            crossDomain: true,
            success: function (data){
                $('.city-list').empty().show();
                $('.province-list').hide();
                $(data).each(function(){
                    var html = '<li data-id="'+this.id+'">'+this.regionName+'</li>';
                    $('.city-list').append(html);
                });
                //点击城市显示区县
                $('.city-list').delegate('li','click',function(){
                    $('.over-menu li').removeClass('active');
                    $('#area').show().addClass('active');
                    $('.city-list li').removeClass('active');
                    $(this).addClass('active');
                    var id = $(this).attr('data-id');
                    $('#cityId').val(id);
                    initArea(id);
                });
            }, error: function () {
                console.log("error");
            }
        });
    }
    //加载区县
    function initArea(id){
        $.ajax({
            type: "GET",
            url: baseUrl+"findRegionByParentId/"+id,
            dataType: "json",
            contentType : 'application/json',
            async:false,
            crossDomain: true,
            success: function (data){
                $('.area-list').empty().show();
                $('.city-list').hide();
                $(data).each(function(){
                    var html = '<li data-id="'+this.id+'">'+this.regionName+'</li>';
                    $('.area-list').append(html);
                });
                //点击区县确认地址
                $('.area-list').delegate('li','click',function(){
                    $('.area-list li').removeClass('active');
                    $(this).addClass('active');
                    var id = $(this).attr('data-id');
                    $('#areaId').val(id);
                    var name = $('.province-list .active').text()+" "+$('.city-list .active').text()+" "+$('.area-list .active').text();
                    $('.overlay,.over-city').hide();
                    $('#address').val(name).parent().find('span').remove();
                });
            }, error: function () {
                console.log("error");
            }
        });
    }
$().ready(function(){
    //点击省份
    $('#province').click(function(){
        $('.over-menu li').removeClass('active');
        $(this).addClass('active');
        $('.province-list').show();
        $('.city-list,.area-list').hide();
    });
    //点击城市
    $('#city').click(function(){
        $('.over-menu li').removeClass('active');
        $(this).addClass('active');
        $('.city-list').show();
        $('.province-list,.area-list').hide();
    });
    //点击区县
    $('#area').click(function(){
        $('.over-menu li').removeClass('active');
        $(this).addClass('active');
        $('.area-list').show();
        $('.city-list,.province-list').hide();
    });
});