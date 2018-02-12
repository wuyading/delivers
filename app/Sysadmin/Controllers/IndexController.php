<?php

namespace App\Sysadmin\Controllers;


use App\Plugins\Friendly\Traits\FriendlyTrait;

class IndexController extends SysadminBaseController
{
    public function index()
    {
        $this->isLogin();
        return $this->render('index');
    }

}