
// 图片上传demo
jQuery(function() {
    var $ = jQuery,
        $list = $('#fileList'),
        // 优化retina, 在retina下这个值是2
        ratio = window.devicePixelRatio || 1,

        // 缩略图大小
        thumbnailWidth = 100 * ratio,
        thumbnailHeight = 100 * ratio,

        // Web Uploader实例
        uploader;

    // 初始化Web Uploader
    uploader = WebUploader.create({

        // 自动上传。
        auto: true,

        // swf文件路径
        swf: BASE_URL + '/js/Uploader.swf',

        // 文件接收服务端。
        server: UPLOAD_URL,

        // 选择文件的按钮。可选。
        // 内部根据当前运行是创建，可能是input元素，也可能是flash.
        pick: '#filePicker',

        fileNumLimit: 1,
        fileSingleSizeLimit:1048576 * 10,

        // 只允许选择文件，可选。
        accept: {
            title: 'Images',
            extensions: 'gif,jpg,jpeg,bmp,png',
            mimeTypes: 'image/*'
        },
        compress:false
    });

    uploader.on("ready",function () {
        // WebUploader.Base.id = 2
    })

    // 当有文件添加进来的时候
    uploader.on( 'fileQueued', function( file ) {
        var $li = $(
                '<div id="' + file.id + '" class="file-item thumbnail">' +
                '<img>' +
                '<span class="remove glyphicon glyphicon-remove" aria-hidden="true"></span>' +
                '<input type="hidden" class="img_path" name="info[logo]" value="">' +
                '<div class="info">' + file.name + '</div>' +
                '</div>'
            ),
            $img = $li.find('img');

        $list.append( $li );

        $li.on('click', '.remove', function() {
            $li.remove();
            uploader.removeFile( file );
        })

        // 创建缩略图
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
    uploader.on( 'uploadSuccess', function( file , response) {
        $( '#'+file.id ).addClass('upload-state-done');
        $( '#'+file.id ).find('.img_path').val(response.img);
    });

    // 文件上传失败，现实上传出错。
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

    //上传文件错误检测
    uploader.on( 'error', function( type ) {
        console.log(type);
        if(type == 'Q_EXCEED_NUM_LIMIT'){
            alert('只允许上传一张图片！');
        }
        if(type == 'Q_TYPE_DENIED'){
            alert('该图片类型不允许上传！');
        }
        if(type == 'Q_EXCEED_SIZE_LIMIT'){
            alert('文件总大小超过最大限制！');
        }
        if(type == 'F_EXCEED_SIZE'){
            alert('文件大小超过最大限制！');
        }
    });
});
