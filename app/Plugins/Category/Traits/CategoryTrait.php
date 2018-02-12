<?php

namespace App\Plugins\Category\Traits;

use App\Models\Category;
use App\Models\CategoryType;
use App\Plugins\Category\CategoryPlugin;
use App\Plugins\Category\MenuPlugin;
use App\Plugins\Category\Tree;
use Zilf\Facades\Request;
use Zilf\System\Zilf;

trait CategoryTrait
{
    /**
     * @return CategoryPlugin
     */
    public function getPluginObj()
    {
        return Zilf::$container['categoryPlugin'];
    }

    /**
     * 默认的分类页面
     */
    public function index()
    {
        $type = Request::query()->getInt('type', 1);

        $indexMenuHtml = $this->getIndexMenuHtml($type);
        $typeMenuHtml = $this->getIndexTypeMenuHtml($type);

        return $this->getPluginObj()->viewIndex('@plugins/category/index', [
            'category_menu' => $indexMenuHtml,
            'category_type_menu' => $typeMenuHtml,
            'type' => $type,
            'app' => $this
        ]);
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////

    /**
     * 添加菜单页面
     */
    public function add()
    {
        $parent_id = Request::query()->get('parent_id');

        //类别id
        $typeId = Request::query()->get('type');
        if (empty($typeId)) {
            die('非法操作！');
        }

        $type_info = $this->getPluginObj()->getTypeInfo($typeId);
        $select_categorys = $this->getMenuHtml2($parent_id,$typeId);
        return $this->getPluginObj()->viewAdd('@plugins/category/add', [
            'select_categorys_html' => $select_categorys,
            'type_info' => $type_info,
            'type'=> $typeId,
            'parent_id' => $parent_id,
            'app' => $this
        ]);
    }

    /**
     * 修改菜单页面
     */
    public function edit()
    {
        $id = Request::query()->get('id');

        $info = $this->getPluginObj()->getInfo($id);

        $select_categorys = $this->getMenuHtml($info['parent_id']);

        return $this->getPluginObj()->viewEdit('@plugins/category/edit', [
            'select_categorys_html' => $select_categorys,
            'info' => $info,
            'app' => $this
        ]);
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////

    /**
     * 保存节点信息
     */
    public function ajax_save_add()
    {
        $category_info = Request::request()->get('info');

        //类别id
        $typeId = Request::request()->get('type_id');
        if (empty($typeId)) {
            die('非法操作！');
        }

        if (empty($category_info['name'])) {
            return $this->json(['status' => 2001, 'message' => '菜单名称不能为空!']);
        }

        $data = [
            'name' => $category_info['name'],
            'china_name' => $category_info['china_name'],
            'parent_id' => $category_info['parent_id'],
            'type_id' => $typeId,
            'is_delete' => 0,  //是否删除 0 不删除  1 删除
            'add_time' => time(),
            'sortid' => 0,
        ];

        /**
         * @var $img UploadedFile 上传文件
         */
        $file = Request::files()->get('logo');

        $upload_dir = '/upload/category';
        if ($file) {
            $banner_name = md5(microtime()) . '.' . $file->guessExtension();
            $file->move(ROOT_PATH . $upload_dir, $banner_name);
            if ($file->getError()) {
                return $this->json_callback($file->getErrorMessage());
            }
            $data['logo'] = $upload_dir . '/' . $banner_name;
        }
        $model = new Category();
        $model->setAttributes($data);
        $is_success = $model->save();

        if ($is_success) {
            $this->redirect(toRoute('category?type='.$typeId));
            return $this->json(['status' => 1001, 'message' => '保存成功!']);
        } else {
            return $this->json(['status' => 2110, 'message' => '保存失败!']);
        }
    }


    /**
     * 更新节点信息
     */
    public function ajax_save_update()
    {
        $update_id = Request::request()->getInt('id');
        $category_info = Request::request()->get('info');

        //类别id
        $typeId = Request::request()->get('type_id');
        if (empty($typeId)) {
            die('非法操作！');
        }

        if (empty($update_id)) {
            return $this->json(['status' => 2001, 'message' => '参数错误!']);
        }
        if (empty($category_info['name'])) {
            return $this->json(['status' => 2002, 'message' => '菜单名称不能为空!']);
        }


        $data = [
            'name' => $category_info['name'],
            'china_name' => $category_info['china_name'],
            'parent_id' => $category_info['parent_id'],
            'add_time' => time(),
        ];

        /**
         * @var $img UploadedFile 上传文件
         */
        $file = Request::files()->get('logo');
        $upload_dir = '/upload/category';
        if ($file) {
            $banner_name = md5(microtime()) . '.' . $file->guessExtension();
            $file->move(ROOT_PATH . $upload_dir, $banner_name);
            if ($file->getError()) {
                return $this->json_callback($file->getErrorMessage());
            }
            $data['logo'] = $upload_dir . '/' . $banner_name;
        }
        $is_success = $this->getPluginObj()->update($data, ['id' => $update_id]);
        if ($is_success) {
            $this->redirect(toRoute('category?type='.$typeId));
//            return $this->json(['status'=>1001,'message'=>'保存成功!']);
        } else {
            return $this->json(['status' => 2003, 'message' => '保存失败，请重试！!']);
        }
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////

    /**
     * 删除节点
     */
    public function ajax_delete()
    {
        $id = Request::request()->getInt('id');
//        $where['id'] = $id;
        if (empty($id)) {
            return $this->json(['status' => 2001, 'message' => '请选择分类!']);
        }

        $category = Category::findOne($id);
        $is_success = $category->delete();

//        $is_success = $this->getPluginObj()->delete($where);
        if ($is_success) {
            return $this->json(['status' => 1001, 'msg' => '删除成功!']);
        } else {
            return $this->json(['status' => 2003, 'msg' => '删除失败，请重试!']);
        }
    }

    /**
     * 删除节点type
     */
    public function ajax_delete_type()
    {
        $id = Request::request()->getInt('id');
//        $where['id'] = $id;
        if (empty($id)) {
            return $this->json(['status' => 2001, 'message' => '请选择分类!']);
        }

        $category = CategoryType::findOne($id);
        $is_success = $category->delete();

//        $is_success = $this->getPluginObj()->delete($where);
        if ($is_success) {
            return $this->json(['status' => 1001, 'msg' => '删除成功!']);
        } else {
            return $this->json(['status' => 2003, 'msg' => '删除失败，请重试!']);
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

    /**
     * 获取二手车分类
     * @param $selectId
     * @return string
     */
    private function getMenuHtml2($selectId,$type)
    {
        $result = $this->getPluginObj()->getAll(['type_id' => $type]);

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
     * @return string
     */
    private function getIndexMenuHtml($type)
    {
        $tree = new Tree();
        $tree->icon = array('&nbsp;&nbsp;&nbsp;│ ', '&nbsp;&nbsp;&nbsp;├─ ', '&nbsp;&nbsp;&nbsp;└─ ');
        $tree->nbsp = '&nbsp;&nbsp;&nbsp;';

        //获取分类类别信息
        //获取所有分类
        $result = $this->getPluginObj()->getAll(['type_id' => $type]);

        $array = array();
        foreach ($result as $r) {
            $r['cname'] = $r['name'];

            $r['class'] = $r['parent_id']==0 ? 'sel_one' : 'sel_two';
            $r['class_no'] = $r['parent_id']==0 ? '<span class="fuhao">+</span>' : '';
            $r['class_id'] = $r['parent_id']==0 ? $r['id'] : $r['parent_id'];

            $r['str_manage'] = '<a class="btn btn-primary" href="' . toRoute('category/add?type='.$type.'&parent_id=' . $r['id']) . '">添加子菜单</a> 
                                <a class="btn btn-default" href="' . toRoute('category/edit?id=' . $r['id']) . '">修改</a> 
                                <a class="btn btn-danger"  onclick="ajaxDelete('.$r['id'].')">删除</a> ';
            $array[] = $r;
        }

        $str = "<tr class='\$class' data-id='\$class_id'>
					<td><input name='id' type='text' size='3' value='\$sortid' class='text-center'></td>
					<td>\$id</td>
					<td>\$class_no\$spacer\$cname</td>
					<td>\$str_manage</td>
				</tr>";
        $tree->init($array);

        return $tree->get_tree(0, $str, 0, '', '');
    }


    /**
     * @param string $type
     * @return string
     */
    private function getIndexTypeMenuHtml($type = '')
    {
        $tree = new Tree();
        $tree->icon = array('&nbsp;&nbsp;&nbsp;│ ', '&nbsp;&nbsp;&nbsp;├─ ', '&nbsp;&nbsp;&nbsp;└─ ');
        $tree->nbsp = '&nbsp;&nbsp;&nbsp;';

        //获取分类类别信息
        //获取所有分类
        $result = $this->getPluginObj()->getTypeAll();

        $array = array();
        foreach ($result as $r) {
            $r['cname'] = $r['name'];
            $r['str_manage'] = '<a class="btn btn-info"  href="' . toRoute('category?type=' . $r['id']) . '">查看</a> ';
            $r['active'] = ($r['id'] == $type) ? 'active' : '';
            $array[] = $r;
        }

        $str = "<tr class='\$active'>
					<td>\$spacer\$cname</td>
					<td>\$str_manage</td>
				</tr>";
        $tree->init($array);

        return $tree->get_tree(0, $str, 0, '', '');
    }
}