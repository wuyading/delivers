<?php

namespace App\Plugins\Category\Traits;


use App\Plugins\Category\Tree;
use Zilf\Facades\Request;

trait TypeTrait
{
    public function type(){


        $typeMenuHtml = $this->getTypeMenuHtml();

        return $this->getPluginObj()->viewIndex('@plugins/category/type',['category_html' => $typeMenuHtml,'app'=>$this]);
    }

    /**
     * @return mixed
     */
    public function type_add(){
        $parent_id = Request::query()->get('parent_id');

        $select_categorys = $this->getTypeMenuSelectHtml($parent_id);
        return $this->getPluginObj()->viewAdd('@plugins/category/type_add',['select_categorys_html' => $select_categorys,'app'=>$this]);
    }

    /**
     * @return mixed
     */
    public function type_edit(){
        $id = Request::query()->get('id');

        $info = $this->getPluginObj()->getTypeInfo($id);

        $select_categorys = $this->getTypeMenuSelectHtml($info['parent_id']);

        return $this->getPluginObj()->viewEdit('@plugins/category/type_edit',['select_categorys_html' => $select_categorys,'info'=>$info,'app'=>$this]);
    }

    /**
     *
     */
    public function type_delete(){

    }


    /**
     * 保存节点信息
     */
    public function ajax_type_add()
    {
        $category_info = Request::request()->get('info');

        if (empty($category_info['name'])) {
            return $this->json(['status' => 2001, 'message' => '菜单名称不能为空!']);
        }

        $data = [
            'name' => $category_info['name'],
            'alias' => isset($category_info['alias'])?$category_info['alias']:'#',
            'parent_id' => $category_info['parent_id'],
            'is_delete' => 1,  //是否删除 1 不删除  2 删除
            'add_time' => time(),
            'orderid' => 0,
        ];

        $is_success = $this->getPluginObj()->type_add($data);
        if ($is_success) {
            $this->redirect(toRoute('category/type'));
//            $insert_id = $is_success;
            return $this->json(['status' => 1001, 'message' => '保存成功!']);
        } else {
            return $this->json(['status' => 2110, 'message' => '保存失败!']);
        }
    }


    /**
     * 更新节点信息
     */
    public function ajax_type_update()
    {
        $update_id = Request::request()->getInt('id');
        $category_info = Request::request()->get('info');

        if (empty($update_id)) {
            return $this->json(['status' => 2001, 'message' => '参数错误!']);
        }
        if (empty($category_info['name'])) {
            return $this->json(['status' => 2002, 'message' => '菜单名称不能为空!']);
        }

        $data = [
            'name' => $category_info['name'],
            'alias' => isset($category_info['alias'])?$category_info['alias']:'#',
            'parent_id' => $category_info['parent_id'],
            'add_time' => time(),
        ];

        $is_success = $this->getPluginObj()->type_update($data, ['id' => $update_id]);
        if ($is_success) {
            $this->redirect(toRoute('category/type'));
//            return $this->json(['status'=>1001,'message'=>'保存成功!']);
        } else {
            return $this->json(['status' => 2003, 'message' => '保存失败，请重试！!']);
        }
    }


    /**
     * @param $selectId
     * @return string
     */
    private function getTypeMenuSelectHtml($selectId)
    {
        $result = $this->getPluginObj()->getTypeAll();

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
    private function getTypeMenuHtml(){
        $tree = new Tree();
        $tree->icon = array('&nbsp;&nbsp;&nbsp;│ ', '&nbsp;&nbsp;&nbsp;├─ ', '&nbsp;&nbsp;&nbsp;└─ ');
        $tree->nbsp = '&nbsp;&nbsp;&nbsp;';

        //获取分类类别信息
        //获取所有分类
        $result = $this->getPluginObj()->getTypeAll();

        $array = array();
        foreach ($result as $r) {
            $r['cname'] = $r['name'];
            $r['str_manage'] = '<a class="btn btn-default" href="' . toRoute('category/type_add?parent_id=' . $r['id']) . '">添加子菜单</a> 
                                <a class="btn btn-default" href="' . toRoute('category/type_edit?id=' . $r['id']) . '">修改</a> 
                                <a class="btn btn-danger"  href="javascript:void(0)" onclick="ajaxDelete('.$r['id'].')">删除</a> ';
            $array[] = $r;
        }

        $str = "<tr>
					<td><input name='id' type='text' size='3' value='\$sortid' class='text-center'></td>
					<td>\$id</td>
					<td>\$spacer\$cname</td>
					<td>\$str_manage</td>
				</tr>";
        $tree->init($array);

        return $tree->get_tree(0, $str, 0, '', '');
    }

}