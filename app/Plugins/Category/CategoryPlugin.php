<?php

namespace App\Plugins\Category;


use App\Models\MenuCategory;
use App\Sysadmin\Controllers\SysadminBaseController;
use Zilf\Db\ActiveRecord;
use Zilf\System\Controller;
use Zilf\System\Zilf;

class CategoryPlugin extends SysadminBaseController
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
     *
     * @param $id
     * @param array $where
     * @return array|null|ActiveRecord
     */
    public function getInfo($id, $where = [])
    {
        if (empty($where)) {
            $where = ['id' => $id];
        }
        return $this->tableObj->find()->where($where)->asArray()->one();
    }

    public function getTypeInfo($id)
    {
        return $this->tableTypeObj->find()->where(['id' => $id])->asArray()->one();
    }

    /**
     * 获取全部的菜单列表
     *
     * @param array $where
     * @return array|ActiveRecord[]
     */
    public function getAll($where = [])
    {
        $where['is_delete'] = 0;
        return $this->tableObj->find()->where($where)->orderBy('sortid ASC')->asArray()->all();
    }

    /**
     * 获取全部的菜单列表
     *
     * @return array|ActiveRecord[]
     */
    public function getTypeAll()
    {
        return $this->tableTypeObj->find()->orderBy('sortid ASC,id ASC')->asArray()->all();
    }

    /**
     * @return string
     */
    public function sidebarMenu()
    {
        $tree = new Tree();

        $result = $this->getAll();

        $array = array();
        foreach ($result as $r) {
            $r['cname'] = $r['name'];
            if (isset($r['link'])) {
                $r['link'] = toRoute($r['link']);
            }
            $array[] = $r;
        }
        $tree->init($array);

        $tree->str = '';
        $ul_style = ' data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200"';
        $style1 = "<a href='\$link' class='nav-link '><span class='title'>\$name</span></a>";
        $style2 = "<a href='\$link' class='nav-link nav-toggle'><i class='icon-diamond'></i><span class='title'>\$name</span><span class='arrow'></span></a>";
        $this->sidebarMenuHtml = $tree->get_customView(0, $ul_style, $style1, $style2, 0, 'page-sidebar-menu  page-header-fixed ');

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
        return $this->tableObj->find()->where(['parent_id' => $id])->orderBy('sortid ASC,id ASC')->asArray()->all();
    }

    /**
     * 保存节点信息
     */
    public function add($data)
    {
        $result = $this->addSqlData($this->tableObj, $data);
        return $this->json($result);
    }


    /**
     * 更新节点信息
     */
    public function update($data, $where)
    {
        $result = $this->updateSqlData($this->tableObj, $data, $where);
        return $this->json($result);
    }

    /**
     * 删除节点
     */
    public function delete($where)
    {
        $result = $this->deleteSqlData($this->tableObj, $where);
        return $this->json($result);
    }

    /**
     * 保存节点信息
     */
    public function type_add($data)
    {
        $result = $this->addSqlData($this->tableTypeObj, $data);
        return $this->json($result);
    }


    /**
     * 更新节点信息
     */
    public function type_update($data, $where)
    {
        $result = $this->updateSqlData($this->tableTypeObj, $data, $where);
        return $this->json($result);
    }

    /**
     * 删除节点
     */
    public function type_delete($where)
    {
        $result = $this->deleteSqlData($this->tableTypeObj, $where);
        return $this->json($result);
    }

    /**
     * @param $type
     * @param $selectId
     * @return string
     */
    public function getSelectHtml($type, $selectId = '')
    {
        $result = $this->getAll(['type_id' => $type]);

        $array = array();
        foreach ($result as $r) {
            $r['cname'] = $r['name'];
            $r['selected'] = $r['id'] == $selectId ? 'selected' : '';
            $array[] = $r;
        }

        $str = "<option value='\$id' \$selected>\$spacer \$cname</option>";
        $tree = new Tree();
        $tree->init($array);

        return $tree->get_tree(0, $str);
    }

    /**
     * @param $selectId
     * @return string
     */
    public function getCatalogSelectHtml($product_id,$selectId = '')
    {
        $result = $this->getAll(['product_id'=>$product_id]);

        $array = array();
        foreach ($result as $r) {
            $r['id'] = $r['ca_id'];
            $r['cname'] = $r['ca_name'];
            $r['selected'] = $r['ca_id'] == $selectId ? 'selected' : '';
            $array[] = $r;
        }

        $str = "<option value='\$id' \$selected>\$spacer \$cname</option>";
        $tree = new Tree();
        $tree->init($array);

        return $tree->get_tree(0, $str);
    }
}