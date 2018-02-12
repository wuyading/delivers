<?php

namespace App\Plugins\Menu;


use App\Models\MenuCategory;
use App\Models\SysadminRolePrivilege;
use App\Plugins\Login\Services;
use App\Plugins\Category\Tree;
use App\Sysadmin\Controllers\SysadminBaseController;
use Zilf\Db\ActiveRecord;
use Zilf\System\Zilf;

class MenuPlugin extends SysadminBaseController
{
    /**
     * @var ActiveRecord
     */
    public $tableObj = '';
    public $tableTypeObj = '';

    public $sidebarMenuHtml = '';


    /**
     * 设置数据库对象
     *
     * @param $tableObj
     */
    function setTableObj($tableObj)
    {
        Zilf::$container->register($tableObj, $tableObj);
        $this->tableObj = Zilf::$container->get($tableObj);
    }

    /**
     * 设置数据库对象
     *
     * @param $tableObj
     */
    function setTableTypeObj($tableObj)
    {
        Zilf::$container->register($tableObj, $tableObj);
        $this->tableTypeObj = Zilf::$container->get($tableObj);
    }

    /**
     * 分类首页
     *
     * @param array $data
     * @return \Zilf\HttpFoundation\Response
     */
    public function viewIndex($viewPath, $data = [])
    {
        return $this->render($viewPath, $data);
    }

    /**
     * @param array $data
     * @return \Zilf\HttpFoundation\Response
     */
    public function viewAdd($viewPath, $data = [])
    {
        return $this->render($viewPath, $data);
    }

    public function viewEdit($viewPath, $data = [])
    {
        return $this->render($viewPath, $data);
    }

    /**
     * 获取单条信息
     */
    public function getInfo($id)
    {
        return $this->tableObj->find()->where(['id' => $id])->asArray()->one();
    }

    /**
     * 获取全部的菜单列表
     *
     * @return array|ActiveRecord[]
     */
    public function getAll($where = [])
    {
        if(!empty($where['role_id'])&&$where['role_id']!=1){
            $rolePrivilege=SysadminRolePrivilege::find()->where($where)->asArray()->one();

            return $this->tableObj->find()->where(['in','id',explode(',',$rolePrivilege['privilege'])])->orderBy('sortid ASC,id ASC')->asArray()->all();
        }else{
            return $this->tableObj->find()->where(['is_delete'=>1])->orderBy('sortid ASC,id ASC')->asArray()->all();
        }
    }


    /**
     * @param string $where
     * @param string $current
     * @return string
     */
    public function sidebarMenu($where = [], $current = '/')
    {
        $tree = new Tree();

        $result = $this->getAll($where);

        if(empty($result)){return ;}

        $array = array();
        foreach ($result as $r) {
            $r['cname'] = $r['name'];
            if (isset($r['link'])) {
                $r['href'] = toRoute($r['link']);
            }
            $array[] = $r;
        }
        $tree->init($array);

        $tree->str = '';
        $ul_style = ' data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200"';
        $style1 = "<a href='\$href' class='nav-link '><span class='title'>\$name</span></a>";
        $style2 = "<a href='\$href' class='nav-link nav-toggle'><i class='icon-diamond'></i><span class='title'>\$name</span><span class='arrow'></span></a>";
        $this->sidebarMenuHtml = $tree->get_customView(0, $ul_style, $style1, $style2, 0, 'page-sidebar-menu  page-header-fixed ', $current);

        return $this->sidebarMenuHtml;
    }


    /**
     * 递归运算
     * 获取分类$id下面的所有分类信息
     *
     * @param $id
     * @return array|bool|\Zilf\Db\ActiveRecord[]
     */
    public function getCategory($id)
    {

        $childrenCategory = $this->get_sub_category($id);

        if (empty($childrenCategory)) {
            return false;
        } else {
            foreach ($childrenCategory as $key => $value) {
                $childrenCategory[$key]['children'] = $this->getCategory($value['id']);
            }
        }

        return $childrenCategory;
    }

    /**
     * 获取一个分类的子分类信息
     *
     * @param $id
     * @return array|\Zilf\Db\ActiveRecord[]
     */
    public function get_sub_category($id)
    {
        return $this->tableObj->find()->where(['parent_id' => $id])->orderBy('orderid ASC,id DESC')->asArray()->all();
    }

    /**
     * 保存节点信息
     */
    public function add($data)
    {

        $category = $this->tableObj;
        $category->setAttributes($data);
        $is_success = $category->save();
        if ($is_success) {
            $insert_id = $category->attributes['id'];
            return $this->json(['status' => 1001, 'message' => '保存成功!', 'data' => $insert_id]);
        } else {
            return $this->json(['status' => 2110, 'message' => '保存失败!']);
        }
    }


    /**
     * 更新节点信息
     */
    public function update($data, $where)
    {
        $is_success = $this->tableObj->updateAll($data, $where);
        if ($is_success) {
            return $this->json(['status' => 1001, 'message' => '保存成功!']);
        } else {
            return $this->json(['status' => 2003, 'message' => '保存失败，请重试！!']);
        }
    }

    /**
     * 删除节点
     */
    public function delete($where)
    {
        $id = Request::request()->getInt('id');

        if (empty($id)) {
            return $this->json(['status' => 2001, 'message' => '请选择分类!']);
        }

        $category = Category::findOne($id);
        $category->is_delete = 2;
        $is_success = $category->save();
        if ($is_success) {
            return $this->json(['status' => 1001, 'message' => '删除成功!']);
        } else {
            return $this->json(['status' => 2003, 'message' => '删除失败，请重试!']);
        }
    }

}