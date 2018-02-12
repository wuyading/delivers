$().ready(function(){
    var toast = new auiToast({});
    //选择事故车辆
    $('.c-car').change(function(){
        var text = $(this).find('option:selected').text();
        $(this).prev().find('span').text(text);
    });

    //点击提交
    $('.accident-submit').click(function(){
        var car_id = $('.c-car').find('option:selected').val();
        var accident_address = $('.accident_address').val();
        var accident_case = $('.txtArea-center').val();
        var longitude = $("input[name=longitude]").val();
        var latitude = $("input[name=latitude]").val();
        var arr = [];
        $('.img_path').each(function(){
            arr.push($(this).val());
        })
        var accident_images = arr.join(',');
        if(car_id == '' || accident_address == '' || accident_case == '' ){
            toast.fail({
                title:"请完善资料",
                duration:2000
            });
        }else{
            $.ajax({
                type:'post',
                url:'/accidentCenter/ajaxAccidentSave',
                data:{car_id:car_id,accident_address:accident_address,accident_case:accident_case,accident_images:accident_images,longitude:longitude,latitude:latitude},
                success:function(res){
                    var info = JSON.parse(res);
                    console.log(info.code)
                    if(info.code == 1001){
                        toast.success({
                            title:info.msg,
                            duration:2000
                        });
                        window.location.href='/';
                    }
                }
            })

        }
    });

    //function init(){
    //    window.webkit.messageHandlers.TestMaching.postMessage({});
    //}
    //
    //WebViewJavaScriptFunction.onCallDeviceType();
    ////ZHUNIU_ANDROID
    //function callJavaScriptDeviceType(data){
    //    machine = data;
    //}
    //
    ////ZHUNIU_IOS
    //function ObjCToJavaScriptreturnUrl(str)
    //{
    //    machine = str;
    //}




    ////点击上传图片
    //$('#img-upload').click(function(){
    //    //updata(imgId);
    //
    //    //}else if(machine == 'ZHUNIU_ANDROID'){
    //    //    //机型ANDROID
    //    //}else{
    //    //    //WEB
    //    //    $('#filePicker label').click();
    //    //}
    //
    //
    //});

    //删除图片
    $('.upload-list').delegate('.file-item .img-delete','click',function(){
        $(this).parent().remove();
    });

    //var ratio = window.devicePixelRatio || 1,
    //
    //// 缩略图大小
    //    thumbnailWidth = 100 * ratio,
    //    thumbnailHeight = 100 * ratio;
    //
    //// 初始化Web Uploader
    //var uploader = WebUploader.create({
    //
    //    // 选完文件后，是否自动上传。
    //    auto: true,
    //
    //    // swf文件路径
    //    swf: 'Uploader.swf',
    //
    //    // 文件接收服务端。
    //    server: "/FileUpload/index",
    //
    //    // 选择文件的按钮。可选。
    //    // 内部根据当前运行是创建，可能是input元素，也可能是flash.
    //    pick: '#filePicker',
    //
    //    fileNumLimit: 10,
    //    fileSingleSizeLimit:1048576,
    //
    //    // 只允许选择文件，可选。
    //    accept: {
    //        title: 'Images',
    //        extensions: 'gif,jpg,jpeg,bmp,png',
    //        mimeTypes:'image/*,text/plain,application/msword,application/octet-stream,application/vnd.ms-excel,application/x-shockwave-flash'
    //    }
    //});
    //
    //// 当有文件添加进来的时候
    //uploader.on( 'fileQueued', function( file ) {
    //    var $li = $(
    //            '<div id="' + file.id + '" class="file-item pull-left thumbnail"><img class="img">' +
    //            '<img src="/dest/images/img-delete.png" class="img-delete"/>' +
    //            '<input type="hidden" class="img_path" name="info[logo]" value="">' +
    //            '</div>'
    //        ),
    //        $img = $li.find('.img');
    //    // $list为容器jQuery实例
    //    $('.upload-list').append( $li );
    //
    //    // 创建缩略图
    //    // 如果为非图片文件，可以不用调用此方法。
    //    // thumbnailWidth x thumbnailHeight 为 100 x 100
    //    uploader.makeThumb( file, function( error, src ) {
    //        if ( error ) {
    //            $img.replaceWith('<span>不能预览</span>');
    //            return;
    //        }
    //
    //        $img.attr( 'src', src );
    //    }, thumbnailWidth, thumbnailHeight );
    //});
    //
    //// 文件上传过程中创建进度条实时显示。
    //uploader.on( 'uploadProgress', function( file, percentage ) {
    //    var $li = $( '#'+file.id ),
    //        $percent = $li.find('.progress span');
    //
    //    // 避免重复创建
    //    if ( !$percent.length ) {
    //        $percent = $('<p class="progress"><span></span></p>')
    //            .appendTo( $li )
    //            .find('span');
    //    }
    //
    //    $percent.css( 'width', percentage * 100 + '%' );
    //});
    //
    //// 文件上传成功，给item添加成功class, 用样式标记上传成功。
    //uploader.on( 'uploadSuccess', function( file,response ) {
    //    $( '#'+file.id ).addClass('upload-state-done');
    //    $( '#'+file.id ).find('.img_path').val(response.img);
    //});
    //
    //// 文件上传失败，显示上传出错。
    //uploader.on( 'uploadError', function( file ) {
    //    var $li = $( '#'+file.id ),
    //        $error = $li.find('div.error');
    //
    //    // 避免重复创建
    //    if ( !$error.length ) {
    //        $error = $('<div class="error"></div>').appendTo( $li );
    //    }
    //
    //    $error.text('上传失败');
    //});
    //
    //// 完成上传完了，成功或者失败，先删除进度条。
    //uploader.on( 'uploadComplete', function( file ) {
    //    $( '#'+file.id ).find('.progress').remove();
    //});

});