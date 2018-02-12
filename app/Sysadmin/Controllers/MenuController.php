<?php
/**
 * Created by PhpStorm.
 * User: lilei
 * Date: 17-6-9
 * Time: 下午2:29
 */

namespace App\Sysadmin\Controllers;



use App\Plugins\Menu\Traits\MenuTrait;

class MenuController extends SysadminBaseController
{
    use MenuTrait;

    public function __construct()
    {
        parent::__construct();

        $this->isLogin(); //判断是否已经登录
    }

}