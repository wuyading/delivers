$().ready(function(){
    //选择事故车辆
    $('.c-car').change(function(){
        var text = $(this).find('option:selected').text();
        $(this).prev().find('span').text(text);
    });
    //点击上传图片
    $('#img-upload').click(function(){
        $('#filePicker label').click()
    });
    //删除图片
    $('.upload-list').delegate('.file-item .img-delete','click',function(){
        $(this).parent().remove();
    });

    var ratio = window.devicePixelRatio || 1,

    // 缩略图大小
        thumbnailWidth = 100 * ratio,
        thumbnailHeight = 100 * ratio;

    // 初始化Web Uploader
    var uploader = WebUploader.create({

        // 选完文件后，是否自动上传。
        auto: true,

        // swf文件路径
        swf: 'Uploader.swf',

        // 文件接收服务端。
        server: "http://www.sevenlele.com/sysadmin/fileupload",

        // 选择文件的按钮。可选。
        // 内部根据当前运行是创建，可能是input元素，也可能是flash.
        pick: '#filePicker',

        fileNumLimit: 10,
        fileSingleSizeLimit:1048576,

        // 只允许选择文件，可选。
        accept: {
            title: 'Images',
            extensions: 'gif,jpg,jpeg,bmp,png',
            mimeTypes:'image/*,text/plain,application/msword,application/octet-stream,application/vnd.ms-excel,application/x-shockwave-flash'
        }
    });

    // 当有文件添加进来的时候
    uploader.on( 'fileQueued', function( file ) {
        var $li = $(
                '<div id="' + file.id + '" class="file-item pull-left thumbnail"><img class="img">' +
                '<img src="../../images/img-delete.png" class="img-delete"/>' +
                '<input type="hidden" class="img_path" name="info[logo]" value="">' +
                '</div>'
            ),
            $img = $li.find('.img');
        // $list为容器jQuery实例
        $('.upload-list').append( $li );

        // 创建缩略图
        // 如果为非图片文件，可以不用调用此方法。
        // thumbnailWidth x thumbnailHeight 为 100 x 100
        uploader.makeThumb( file, function( error, src ) {
            if ( error ) {
                $img.replaceWith('<span>不能预览</span>');
                return;
            }

            $img.attr( 'src', src );
        }, thumbnailWidth, thumbnailHeight );
    });

    // 文件上传过程中创建进度条实时显示。
    uploader.on( 'uploadProgress', function( file, percentage ) {
        var $li = $( '#'+file.id ),
            $percent = $li.find('.progress span');

        // 避免重复创建
        if ( !$percent.length ) {
            $percent = $('<p class="progress"><span></span></p>')
                .appendTo( $li )
                .find('span');
        }

        $percent.css( 'width', percentage * 100 + '%' );
    });

    // 文件上传成功，给item添加成功class, 用样式标记上传成功。
    uploader.on( 'uploadSuccess', function( file,response ) {
        $( '#'+file.id ).addClass('upload-state-done');
        $( '#'+file.id ).find('.img_path').val(response.img);
    });

    // 文件上传失败，显示上传出错。
    uploader.on( 'uploadError', function( file ) {
        var $li = $( '#'+file.id ),
            $error = $li.find('div.error');

        // 避免重复创建
        if ( !$error.length ) {
            $error = $('<div class="error"></div>').appendTo( $li );
        }

        $error.text('上传失败');
    });

    // 完成上传完了，成功或者失败，先删除进度条。
    uploader.on( 'uploadComplete', function( file ) {
        $( '#'+file.id ).find('.progress').remove();
    });

});