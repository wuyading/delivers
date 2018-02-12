<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/1/5
 * Time: 19:55
 */

namespace App\Sysadmin;

use Zilf\Facades\Request;
use Zilf\HttpFoundation\File\UploadedFile;
trait UploadTrait
{
    public function uploadOne($inputName='logo')
    {
        /**
         * @var $upload UploadedFile
         */
        $upload = Request::files()->get($inputName);
        //判断上传的文件类型
        $img = '';
        if ($upload) {
            $upload_dir = '/upload/'.date('ymd').'/'.mt_rand(0,99).'/';
            if(!file_exists($upload_dir) || !is_dir($upload_dir)){
                @mkdir($upload_dir,777,true);
            }

            $file_name = md5(microtime()) . '.' . $upload->guessExtension();
            $upload->move(ROOT_PATH . $upload_dir, $file_name);

            if ($upload->getError()) {
                return $this->json_callback($upload->getErrorMessage());
            }

            return $upload_dir . $file_name;
        }
    }
}