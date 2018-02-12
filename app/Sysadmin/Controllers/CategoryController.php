<?php
/**
 * Created by PhpStorm.
 * User: lilei
 * Date: 17-6-9
 * Time: 下午2:29
 */

namespace App\Sysadmin\Controllers;


use App\Plugins\Category\Traits\CategoryTrait;
use App\Plugins\Category\Traits\TypeTrait;
use Zilf\Facades\Request;

class CategoryController extends SysadminBaseController
{
    use CategoryTrait;
    use TypeTrait;

    public function __construct()
    {
        parent::__construct();

        $this->isLogin(); //判断是否已经登录
    }

}