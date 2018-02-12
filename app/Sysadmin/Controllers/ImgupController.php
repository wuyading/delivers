<?php

namespace App\Sysadmin\Controllers;

use App\Plugins\UploaderVideo;
use Zilf\Facades\Request;
use Zilf\HttpFoundation\File\UploadedFile;
use Zilf\HttpFoundation\FileBag;

class ImgupController
{

    public function index()
    {
        /**
         * @var $upload UploadedFile
         */
        $upload = Request::files()->get('file');

        //判断上传的文件类型
        $img = '';
        if ($upload) {
            $upload_dir = '/upload/'.mt_rand(0,99).'/'.mt_rand(0,99).'/';
            if(!file_exists($upload_dir) || !is_dir($upload_dir)){
                @mkdir($upload_dir,777,true);
            }

            $file_name = md5(microtime()) . '.' . $upload->guessExtension();
            $upload->move(ROOT_PATH . $upload_dir, $file_name);

            if ($upload->getError()) {
                return die(json_encode($upload->getErrorMessage()));
            }

            $img = $upload_dir . $file_name;
        }

        die(json_encode(['img'=>$img]));
    }

    public function uploadVideo()
    {
        $upload = Request::files()->get('file');

        //判断上传的文件类型
        $img = '';
        if ($upload) {

            $upload_dir = '/upload/video/';
            if(!file_exists($upload_dir) || !is_dir($upload_dir)){
                @mkdir($upload_dir,777,true);
            }

            $file_name = md5(microtime()) . '.' . $upload->guessExtension();
            $upload->move(ROOT_PATH . $upload_dir, $file_name);

            if ($upload->getError()) {
                die(json_encode($upload->getErrorMessage()));
            }

            $video = $upload_dir . $file_name;
        }

        die(json_encode(['video'=>$video]));
    }


}