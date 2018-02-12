<?php

namespace App\Sysadmin\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductWholesale;
use App\Models\Store;
use Zilf\Db\Expression;
use Zilf\Facades\Request;
use App\Common\Services\CartService;

class ProductController extends SysadminBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->isLogin();
    }

    /**
     * @return \Zilf\HttpFoundation\Response
     */
    public function index()
    {
        $currentPage = Request::query()->getInt('zget0');
        $currentPage = $currentPage > 0 ? $currentPage : 1;
        $vars = Request::query()->all();
        $porduct = Product::find()->joinWith('categorys',false);
        if (!empty($vars['title'])) {
            $porduct-> andWhere(['like','products.product_name',trim($vars['title'])]);
        }
        $urlPattern = toRoute('product/index/(:num)?'.$_SERVER['QUERY_STRING']);
        $data = CartService::getListData($porduct,'products.*,category.name',null,null,null , $urlPattern, $currentPage);
        return $this->render('product/index', [
            'vars' => $vars,
            'list' => $data['list'],
            'page' => $data['page']
        ]);
    }

    /**
     * 查看、修改操作
     * @return \Zilf\HttpFoundation\Response
     * @throws \Exception
     */
    public function add()
    {
        $data = [];
        $id = Request::query()->get('id');
        $bands = Category::find()->where(['parent_id'=>0,'type_id'=>9])->asArray()->all();
        $result = ['bands'];
        if($id){
            $data = Product::findOne($id)->toArray();
            if($data['category_id']){
                $brand = Category::find()->where(['id'=>$data['category_id']])->asArray()->one();
                $type = Category::find()->where(['parent_id'=>$brand['parent_id'],'type_id'=>9])->asArray()->all();
                array_push($result,'type');
                array_push($result,'brand');
            }
        }
        array_push($result,'data');
        return $this->render('product/add',compact($result));
    }

    /**
     * 保存车
     */
    public function ajax_save()
    {
        if(Request::isMethod('post')){
            $id = Request::request()->get('id');
            $data = Request::request()->get('info');
            $data['updated_at'] = time();
            if(isset($data['logo'])){
                $data['buy_time'] = strtotime($data['buy_time']);
                $data['image'] = $data['logo'];
            }
            $is_success = false;
            if (!empty($id)) { //修改内容
                $activity = Product::findOne($id);
                if ($activity) {
                    $activity->setAttributes($data);
                    $is_success = $activity->update();
                }
            } else { //添加内容
                $data['created_at'] = time();
                $model = new Product();
                $model->setAttributes($data);
                $is_success = $model->save();
            }
            if ($is_success) {
                $this->redirect('/sysadmin/product');
            } else {
            }
        }
    }

    /**
     * 删除车
     */
    public function ajax_delete(){
        $id = Request::request()->getInt('id');
        if($id){
            $model = Product::findOne(['id'=>$id]);
            $is_success = $model->delete();
            if ($is_success) {
                $res = ['code' => 1001, 'msg' => '删除成功！'];
            } else {
                $res = ['code' => 2001, 'msg' => '删除失败！'];
            }
            return $this->json($res);
        }
    }

    /**
     * 批发产品列表
     */
    public function wholesale_list()
    {
        $currentPage = Request::query()->getInt('zget0');
        $currentPage = $currentPage > 0 ? $currentPage : 1;

        $vars = Request::query()->all();

        $where = [];

        if($this->userInfo['role_id']==6){
            $where=['is_onsale'=>1];
        }

        $urlPattern = toRoute('product/wholesale_list/(:num)?'.$_SERVER['QUERY_STRING']);

        $data = ProductWholesale::getPageList($where, $urlPattern, $currentPage);

        return $this->render('product/wholesale_list', [
            'vars' => $vars,
            'list' => $data['list'],
            'page' => $data['page']
        ]);
    }

    /**
     * 批发产品列表
     */
    public function wholesale_add()
    {
        $id = Request::query()->getInt('id');

        $res = [];
        if ($id) {
            //产品信息
            $res = ProductWholesale::find()->where(['id' => $id])->asArray()->one();
        }
        return $this->render('product/wholesaleadd', [
            'res' => $res,
        ]);
    }


    /**
     * 保存批发产品信息
     */
    public function ajax_save_product_wholesale()
    {
        $request = Request::request()->all();

        $product_data = [
            'title' => isset($request['title']) ? filter_var($request['title'], FILTER_SANITIZE_SPECIAL_CHARS) : '',
            'price_pf' => isset($request['price_pf']) && is_numeric($request['price_pf']) ? $request['price_pf'] : 1,
            'min_num' => isset($request['min_num']) && is_numeric($request['min_num']) ? $request['min_num'] : 1,
            'describe' => isset($request['describe']) ? filter_var($request['describe'], FILTER_SANITIZE_SPECIAL_CHARS) : '',
            'is_onsale' => isset($request['is_onsale']) && is_numeric($request['is_onsale']) ? $request['is_onsale'] :2,
        ];

        if (!empty($id)) { //修改内容
            $role = ProductWholesale::findOne($id);
            if ($role) {
                $role->setAttributes($product_data);
                $is_success = $role->save();
            } else {
                $is_success = false;
            }
        } else { //添加内容
            $product_data['add_time'] = time();
            $role_model = new ProductWholesale();
            $role_model->setAttributes($product_data);
            $is_success = $role_model->save();
        }

        if ($is_success) {
            $res = ['status' => 1001, 'msg' => '保存成功！'];
        } else {
            $res = ['status' => 2000, 'msg' => '保存失败！'];
        }
        return $this->json($res);
    }

    private function json_callback($data,$parent='parent',$method='show_message'){
        if(is_array($data)){
            $data = json_encode($data);
        }

        echo <<<EOT
        <script type="text/javascript">
            {$parent}.{$method}($data);
        </script>
EOT;
        die();
    }
}