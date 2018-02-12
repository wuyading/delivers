<?php

namespace App\Plugins\Login\Traits;

use App\Plugins\Login\LoginPlugin;


trait LoginBaseTrait
{
    private $loginInRedirectUrl = '/sysadmin';
    private $loginOutRedirectUrl = '/';

    /**
     * 设置登录url
     * @param string $url
     */
    public function setLonginInUrl($url='/')
    {
        $this->loginInRedirectUrl = $url;
    }

    /**
     * 设置退出返回地址
     * @param string $url
     */
    public function setLoginOutUrl($url='/'){
        $this->loginOutRedirectUrl = $url;
    }
    /**
     * 退出登录
     */
    public function login_out()
    {
        $this->getLoginPluginObj()->loginOut();
        $this->redirect(toRoute($this->loginOutRedirectUrl));
    }

    /**
     * @return LoginPlugin
     */
    private function getLoginPluginObj()
    {
        throw new \RuntimeException('控制器里面必须创建getPluginObj的方法.');
    }
}