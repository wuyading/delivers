<?php

namespace App\Sysadmin\Controllers;


use App\Plugins\Login\LoginPlugin;
use App\Plugins\Login\Traits\LoginBaseTrait;
use App\Plugins\Login\Traits\LoginRsaTrait;
use Zilf\Facades\Request;

class LoginController extends SysadminBaseController
{
    use LoginBaseTrait;
    use LoginRsaTrait;

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 登录插件的对象
     *
     * @return LoginPlugin
     */
    private function getLoginPluginObj()
    {
        $obj =  new LoginPlugin('userLoginToken');
        $obj -> setTableObj('App\Models\Sysadmin');
        $this->loginOutRedirectUrl = 'login';
        return $obj;
    }

    /**
     * 获取登录的信息
     *
     * @return array
     */
    private function getRequestData(){
        $username = Request::request()->filter('username',FILTER_SANITIZE_SPECIAL_CHARS);
        $password = Request::request()->get('password');
        $back_url = Request::request()->get('back_url');

        return ['username'=>$username,'password'=>$password,'back_url'=>($back_url)];
    }
}