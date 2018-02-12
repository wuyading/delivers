<?php

namespace App\Plugins\Menu\Traits;

use App\Plugins\Category\MenuPlugin;
use App\Plugins\Category\Tree;
use Zilf\Facades\Request;
use Zilf\System\Zilf;

trait MenuTrait
{
    /**
     * @return MenuPlugin
     */
    public function getPluginObj()
    {
        return Zilf::$container['menuplugin'];
    }

    /**
     * 默认的分类页面
     */
    public function index()
    {

        $tree = new Tree();
        $tree->icon = array('&nbsp;&nbsp;&nbsp;│ ', '&nbsp;&nbsp;&nbsp;├─ ', '&nbsp;&nbsp;&nbsp;└─ ');
        $tree->nbsp = '&nbsp;&nbsp;&nbsp;';

        //获取所有分类
        $result = $this->getPluginObj()->getAll(['role_id'=>$this->userInfo['role_id']]);

        $array = array();
        foreach ($result as $r) {
            $r['cname'] = $r['name'];
            $r['str_manage'] = '<a class="btn btn-primary" href="' . toRoute('menu/add?parent_id=' . $r['id']) . '">添加子菜单</a> 
                                <a class="btn btn-default" href="' . toRoute('menu/edit?id=' . $r['id']) . '">修改</a> 
                                <a class="btn btn-danger"  href="#">删除</a> ';
            $array[] = $r;
        }

        $str = "<tr>
					<td><input name='id' type='text' size='3' value='\$sortid' class='text-center'></td>
					<td>\$id</td>
					<td>\$spacer\$cname</td>
					<td>\$str_manage</td>
				</tr>";
        $tree->init($array);

        $categorys = $tree->get_tree(0, $str, 0, '', '');

        return $this->getPluginObj()->viewIndex('@plugins/menu/index',['category_html' => $categorys,'app'=>$this]);
    }


    /////////////////////////////////////////////////////////////////////////////////////////////////////////

    /**
     * 添加菜单页面
     */
    public function add()
    {
        $parent_id = Request::query()->get('parent_id');

        $select_categorys = $this->getMenuHtml($parent_id);

        return $this->getPluginObj()->viewAdd('@plugins/menu/add',['select_categorys_html' => $select_categorys,'app'=>$this]);
    }

    /**
     * 保存节点信息
     */
    public function ajax_save_add()
    {
        $category_info = Request::request()->get('info');

        if (empty($category_info['name'])) {
            return $this->json(['status' => 2001, 'message' => '菜单名称不能为空!']);
        }
        if (empty($category_info['alias'])) {
            return $this->json(['status' => 2002, 'message' => '别名不能为空!']);
        }
        if (empty($category_info['link'])) {
            return $this->json(['status' => 2003, 'message' => '链接不能为空!']);
        }

        $data = [
            'name' => $category_info['name'],
            'alias' => $category_info['alias'],
            'parent_id' => $category_info['parent_id'],
            'link' => $category_info['link'],
            'is_delete' => 1,  //是否删除 1 不删除  2 删除
            'add_time' => time(),
            'orderid' => 0,
        ];

        $is_success = $this->getPluginObj()->add($data);
        if ($is_success) {
            $this->redirect(toRoute('menu'));
//            $insert_id = $is_success;
            return $this->json(['status' => 1001, 'message' => '保存成功!']);
        } else {
            return $this->json(['status' => 2110, 'message' => '保存失败!']);
        }
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////

    /**
     * 修改菜单页面
     */
    public function edit()
    {
        $id = Request::query()->get('id');

        $info = $this->getPluginObj()->getInfo($id);

        $select_categorys = $this->getMenuHtml($info['parent_id']);

        return $this->getPluginObj()->viewEdit('@plugins/menu/edit',['select_categorys_html' => $select_categorys,'info'=>$info,'app'=>$this]);
    }

    /**
     * 更新节点信息
     */
    public function ajax_save_update()
    {
        $update_id = Request::request()->getInt('id');
        $category_info = Request::request()->get('info');

        if (empty($update_id)) {
            return $this->json(['status' => 2001, 'message' => '参数错误!']);
        }
        if (empty($category_info['name'])) {
            return $this->json(['status' => 2002, 'message' => '菜单名称不能为空!']);
        }
        if (empty($category_info['alias'])) {
            return $this->json(['status' => 2003, 'message' => '别名不能为空!']);
        }
        if (empty($category_info['link'])) {
            return $this->json(['status' => 2004, 'message' => '链接不能为空!']);
        }

        $data = [
            'name' => $category_info['name'],
            'alias' => $category_info['alias'],
            'parent_id' => $category_info['parent_id'],
            'link' => $category_info['link'],
            'add_time' => time(),
        ];

        $is_success = $this->getPluginObj()->update($data, ['id' => $update_id]);
        if ($is_success) {
            $this->redirect(toRoute('menu'));
//            return $this->json(['status'=>1001,'message'=>'保存成功!']);
        } else {
            return $this->json(['status' => 2003, 'message' => '保存失败，请重试！!']);
        }
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////

    /**
     * 删除节点
     */
    public function ajax_delete($where)
    {
        $id = Request::request()->getInt('id');

        if (empty($id)) {
            return $this->json(['status' => 2001, 'message' => '请选择分类!']);
        }

        /*$category = Category::findOne($id);
        $category->is_delete = 2;
        $is_success = $category->save();*/

        $is_success = $this->getPluginObj()->delete($where);
        if ($is_success) {
            return $this->json(['status' => 1001, 'message' => '删除成功!']);
        } else {
            return $this->json(['status' => 2003, 'message' => '删除失败，请重试!']);
        }
    }

    /**
     * @param $selectId
     * @return string
     */
    private function getMenuHtml($selectId)
    {
        $result = $this->getPluginObj()->getAll();

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
}