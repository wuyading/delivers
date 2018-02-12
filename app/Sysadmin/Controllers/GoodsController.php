<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/1/3
 * Time: 11:57
 */

namespace App\Sysadmin\Controllers;

use App\Common\Services\GoodService;
use App\Models\Brand;
use App\Models\Goods;
use Zilf\Facades\Request;
use App\Models\Category;
use Zilf\Facades\Validator;

class GoodsController extends SysadminBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->isLogin();
    }

    /**
     * 列表
     */
    public function index()
    {
        $currentPage = Request::query()->getInt('zget0');
        $currentPage = $currentPage > 0 ? $currentPage : 1;
        $vars = Request::query()->all();

        $porduct = Goods::find()->joinWith('categorys', false);
        if (!empty($vars['title'])) {
            $porduct->andWhere(['like', 'goods.title', trim($vars['title'])]);
        }
        if (!empty($vars['goods_no'])) {
            $porduct->andWhere(['like', 'goods.goods_no', trim($vars['goods_no'])]);
        }
        if (!empty($vars['name'])) {
            $porduct->andWhere(['like', 'category.name', trim($vars['name'])]);
        }

        $urlPattern = toRoute('goods/index/(:num)?'.$_SERVER['QUERY_STRING']);

        $data = GoodService::getListData($porduct, 'goods.*,category.name', 'goods.id desc', null, null, $urlPattern, $currentPage);

        return $this->render('goods/index', [
            'vars' => $vars,
            'list' => $data['list'],
            'page' => $data['page']
        ]);
    }

    /**
     * 详情
     */
    public function add()
    {
        $id = Request::query()->getInt('id');
        $category = Category::find()->where(['is_delete' => 0])->andWhere(['in', 'type_id', [10, 11]])->asArray()->all();
        $brand = Brand::find()->asArray()->all();
        $product_info = [];
        if (!empty($id)) {
            $product_info = GoodService::getById($id);
        }
        return $this->render('goods/add', compact(['product_info', 'category', 'brand']));
    }

    /**
     * 保存
     */
    public function ajax_save()
    {
        $id = Request::request()->getInt('id');

        $post = Request::request()->all();
        $post['is_recommend'] = isset($post['is_recommend'])?$post['is_recommend']:1;
        $validator = Validator::make($post, ['type_id' => 'required|min:0', 'title' => 'required', 'goods_price' => 'required|min:1']);
        if ($validator->fails()) {
            $error = $validator->errors()->all();
            jsBack($error[0]);
            exit;
        }

        if ($id) {  //修改
            GoodService::update($post, ['id' => $id]);
        } else {
            $post['created_at'] = time();
            GoodService::save($post);
        }
        return $this->redirect('/sysadmin/goods');

    }

    /**
     * 删除商品
     */
    public function ajax_del()
    {
        $id = Request::request()->getInt('id');

        $goods = Goods::find()->where(['id' => $id])->one();
        $is_success = $goods->delete();
        if ($is_success) {
            $msg = ['code' => 1001, 'msg' => '删除成功'];
        } else {
            $msg = ['code' => 2001, 'msg' => '删除失败'];
        }

        return $this->json($msg);
    }
}