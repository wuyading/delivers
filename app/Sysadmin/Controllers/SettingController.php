<?php

namespace App\Sysadmin\Controllers;

use App\Models\Setting;
use Zilf\Facades\Request;
use Zilf\Facades\Validator;

class SettingController extends SysadminBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->isLogin();
    }

    //站点设置 基础设置
    public function index()
    {
        $setting = Setting::find()->asArray()->one();
        if( $setting ){
            $setting['web_logo'] = !empty($setting['web_logo'])?$setting['web_logo']:config('default_logo');
            $setting['title_logo'] = !empty($setting['title_logo'])?$setting['title_logo']:config('default_logo');
        }
        return $this->render('setting/index',['info'=>$setting]);
    }

    //站点设置 基础设置2
    public function second()
    {
        $setting = Setting::find()->select('smtp')->asArray()->one();
        if($setting){
            $info = json_decode($setting['smtp'],true);
        }
        return $this->render('setting/second',['info'=>$info]);
    }

    //站点设置 基础设置3
    public function third()
    {
        $setting = Setting::find()->select('id,comment,comment_type')->asArray()->one();
        return $this->render('setting/third',['info'=>$setting]);
    }

    //保存基础设置
    public function ajax_save_data()
    {
        $data = Request::request()->all();
        $data['web_name'] = isset($data['web_name']) ? filter_var($data['web_name'],FILTER_SANITIZE_SPECIAL_CHARS) : '';
        $data['keywords'] = isset($data['keywords']) ? filter_var($data['keywords'],FILTER_SANITIZE_SPECIAL_CHARS) : '';
        $data['description'] = isset($data['description']) ? filter_var($data['description'],FILTER_SANITIZE_SPECIAL_CHARS) : '';

        $data['update_time'] = time();
        /**
         * @var $img UploadedFile 上传文件
         */
        $web_logo = Request::files()->get('web_logo');
        $upload_dir = '/upload/logo';
        if($web_logo){
            $web_logo_name = md5(microtime()).'.'.$web_logo->guessExtension();
            $web_logo->move(ROOT_PATH.$upload_dir,$web_logo_name);
            if($web_logo->getError()){
                return $this->json_callback($web_logo->getErrorMessage());
            }
            $data['web_logo'] = $upload_dir.'/'.$web_logo_name;
        }

        $title_logo = Request::files()->get('title_logo');
        if($title_logo){
            $title_logo_name = md5(microtime()).'.'.$title_logo->guessExtension();
            $title_logo->move(ROOT_PATH.$upload_dir,$title_logo_name);
            if($title_logo->getError()){
                return $this->json_callback($title_logo->getErrorMessage());
            }
            $data['title_logo'] = $upload_dir.'/'.$title_logo_name;
        }

        if(isset($data['id']) && !empty($data['id'])){ //修改内容
            $setting = Setting::findOne($data['id']);
            if($setting){
                $setting->setAttributes($data);
                $is_success = $setting->save();
            }else{
                $is_success = false;
            }
        }else{ //添加内容
            $setting_model = new Setting();
            $setting_model->setAttributes($data);
            $is_success = $setting_model->save();
        }

        if($is_success){
            $msg = ['status'=>1001,'msg'=>'保存成功！'];
        }else{
            $msg = ['status'=>3001,'msg'=>'保存失败！'];
        }

        return $this->json_callback($msg);
    }


     function json_callback($data,$parent='parent',$method='show_message'){
        if(is_array($data)){
            $data = json_encode($data);
        }

        echo <<<EOT
        <script type="text/javascript">
            {$parent}.{$method}($data);
        </script>
EOT;
        die();
    }

    //保存smtp发送邮件配置
    public function ajax_save_second()
    {
        $data = Request::request()->all();
        $datas['smtp'] = json_encode($data);
        $datas['update_time'] = time();
        if(isset($data['id']) && !empty($data['id'])){ //修改内容
            $setting = Setting::findOne($data['id']);
            if($setting){
                $setting->setAttributes($datas);
                $is_success = $setting->save();
            }else{
                $is_success = false;
            }
        }else{ //添加内容
            $setting_model = new Setting();
            $setting_model->setAttributes($datas);
            $is_success = $setting_model->save();
        }

        if($is_success){
            $msg = ['status'=>1001,'msg'=>'保存成功！'];
        }else{
            $msg = ['status'=>3001,'msg'=>'保存失败！'];
        }

        return $this->json_callback($msg);
    }

    //保存功能开关配置
    public function ajax_save_third()
    {
        $data = Request::request()->all();
        $data['update_time'] = time();
        if(isset($data['id']) && !empty($data['id'])){ //修改内容
            $setting = Setting::findOne($data['id']);
            if($setting){
                $setting->setAttributes($data);
                $is_success = $setting->save();
            }else{
                $is_success = false;
            }
        }else{ //添加内容
            $setting_model = new Setting();
            $setting_model->setAttributes($data);
            $is_success = $setting_model->save();
        }

        if($is_success){
            $msg = ['status'=>1001,'msg'=>'保存成功！'];
        }else{
            $msg = ['status'=>3001,'msg'=>'保存失败！'];
        }

        return $this->json_callback($msg);
    }


}