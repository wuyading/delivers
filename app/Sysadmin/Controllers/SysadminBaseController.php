<?php

namespace App\Sysadmin\Controllers;

use App\Models\Store;
use App\Models\Supplier;
use App\Models\Wholesaler;
use App\Plugins\Category\Tree;
use App\Plugins\Login\LoginPlugin;
use Zilf\Facades\Request;
use Zilf\System\Controller;
use Zilf\System\Zilf;

class SysadminBaseController extends Controller
{
    public $userInfo;

    function __construct()
    {
        parent::__construct();

        $this->theme = 'sysadmin';
    }

    function isLogin()
    {
        //判断是否已经登陆
        $login = new LoginPlugin('userLoginToken');
        $userInfo = $login->checkUserIsLogin();
        $userInfo['store_id']=0;

        $this->userInfo =$userInfo;
    }

    /**
     * @param $tableObj
     * @param $data
     * @return array
     */
    public function addSqlData($tableObj,$data)
    {
        $tableObj->setAttributes($data);
        $is_success = $tableObj->save();
        if ($is_success) {
            return (['status' => 1001, 'message' => '保存成功!']);
        } else {
            return (['status' => 2110, 'message' => '保存失败!']);
        }
    }

    /**
     * @param $tableObj
     * @param $data
     * @param $where
     * @return array
     */
    public function updateSqlData($tableObj,$data,$where)
    {
        $is_success = $tableObj->updateAll($data, $where);
        if ($is_success) {
            return (['status' => 1001, 'message' => '保存成功!']);
        } else {
            return (['status' => 2110, 'message' => '保存失败!']);
        }
    }

    /**
     * @param $tableObj
     * @param $where
     * @return array
     */
    public function deleteSqlData($tableObj,$where)
    {
        if (false) {
            return (['status' => 1001, 'message' => '保存成功!']);
        } else {
            return (['status' => 2110, 'message' => '保存失败!']);
        }
    }

    public function getregion($json=true)
    {
        $parent_id = Request::query()->getInt('parent');
        $parentId = empty($parent_id) ? 100000 : $parent_id;
        $region = \App\Models\Region::regionAll('id,region_name',['parent_id'=>$parentId]);
        if($json){
            $res = $region ? ['code'=>1001,'msg'=>'成功','data'=>$region] : ['code'=>2001,'msg'=>'失败'];
            return $this->json($res);
        }
        return $region;
    }

    public function getCarType($json=true)
    {
        $parent_id = Request::query()->getInt('parent');
        $parentId = empty($parent_id) ? 0 : $parent_id;
        if($parentId){
            $type = \App\Models\Category::find()->where(['parent_id'=>$parentId])->asArray()->all();
        }else{
            $type = '';
        }
        if($json){
            $res = $type ? ['code'=>1001,'msg'=>'成功','data'=>$type] : ['code'=>2001,'msg'=>'失败'];
            return $this->json($res);
        }
        return $type;
    }
}