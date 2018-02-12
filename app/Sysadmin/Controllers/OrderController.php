<?php
/**
 * Created by PhpStorm.
 * User: lilei
 * Date: 17-6-13
 * Time: 下午1:06
 */

namespace App\Sysadmin\Controllers;


use App\Models\Order;
use App\Models\OrderInfo;
//use App\Models\OrderRefund;
use App\Models\Store;
use JasonGrimes\Paginator;
use Zilf\Facades\Log;
use Zilf\Facades\Request;
use App\Plugins\Login\LoginPlugin;
use Zilf\Support\DB;
use Zilf\System\Zilf;

use App\Sysadmin\ReservationTrait;
class OrderController extends SysadminBaseController
{

    public $payState=[
        0=>'未支付',
        1=>'已支付'
    ];

    public $orderState=[
        0=>'待支付',
        1=>'待发货',
        2=>'待收货',
        3=>'交易完成',
        4=>'售后中',
        5=>'售后完成',
        6=>'待自提'
    ];

    public $deliverCompany=[
        ''=>'请选择',
        'shunfeng'=>'顺丰',
        'youzhengguonei'=>'邮政',
        'yuantong'=>'圆通',
        'shentong'=>'申通',
        'yunda'=>'韵达',
        'huitongkuaidi'=>'汇通',
        'zhongtong'=>'中通',
        'tiantian'=>'天天',
        'huitongkuaidi'=>'百世汇通',
    ];

    public $refundState=[
        0=>'未处理',
        1=>'同意退款',
        2=>'驳回'
    ];

    public function __construct()
    {
        parent::__construct();

        $this->isLogin();
    }

    use ReservationTrait;

    //订单列表
    public function index()
    {
        $vars=Request::query()->all();

//        if($this->userInfo['role_id']==2) //商户
//        {
//            $where['and']['o.recommend_id']=$this->userInfo['store_id'];
//
//        } elseif($this->userInfo['role_id']==3)//销售
//        {
//            $store=Store::find()->select('id')->where(['salesman_id'=>$this->userInfo['id']])->asArray()->all();
//            $store=array_column($store,'id');
//            $where['and']=['in','o.recommend_id',$store];
//        }elseif($this->userInfo['role_id']==5)//供应商
//        {
//            $where['and']['oi.supplier_id']=$this->userInfo['supplier_id'];
//        }
        $find = DB::query()->from('order as o')->join('left join','order_info as oi','o.id=oi.order_id');

        if(!empty($vars['order_no'])){
            $find->andWhere(['o.order_no'=>trim($vars['order_no'])]);
        }

        if(isset($vars['pay_state'])&&$vars['pay_state']!==''){
            $find->andWhere(['o.pay_state'=>intval($vars['pay_state'])]);
        }

        if(!empty($vars['mobile'])){
            $find->andWhere(['o.mobile'=>trim($vars['mobile'])]);
        }

        if(!empty($vars['consignee'])){
            $find->andWhere(['like','o.consignee',trim($vars['consignee'])]);
        }

        if(!empty($vars['start_date'])&&!empty($vars['end_date'])){
            $find->andFilterWhere(['between','o.add_time',strtotime($vars['start_date']),strtotime($vars['end_date'])]);
        }

        if(!empty($vars['product_title'])){
            $find->andWhere(['like','oi.product_title',trim($vars['product_title'])]);
        }

        $currentPage = Request::query()->getInt('zget0');
        $currentPage = $currentPage > 0 ? $currentPage : 1;
        $pagesize=10;
        //总订单
        $totalItems   = $find->count();
        Log::info('订单数据！！！！！！！！！：'.$totalItems);
        //总金额
        $sumMoney     = $find->select(['sum(o.money) as money'])->scalar();
        //总商品数量
        $countProduct = $find->select(['count(DISTINCT oi.product_id) as count'])->scalar();

        $list = $find->select(['o.*','oi.product_id','oi.deliver_company','oi.product_title','oi.product_img','oi.order_state','oi.id as oi_id','oi.num'])
                     ->offset(($currentPage-1)*$pagesize)->limit($pagesize)->orderBy('id desc')->all();

        //分页数据
        $urlPattern = toRoute('order/index/(:num)?'.$_SERVER['QUERY_STRING']);

        $paginator = new Paginator($totalItems, $pagesize, $currentPage, $urlPattern);
        $paginator->setPreviousText('上一页');
        $paginator->setNextText('下一页');
        Log::info('第三页订单@@@@@@@@@@@@@@@@@@');
        return $this->render('order/index',[
                'list'=>$list,
                'page'=>$paginator,
                'vars'=>$vars,
                'payState'=>$this->payState,
                'orderState'=>$this->orderState,
                'deliverCompany'=>$this->deliverCompany,
                'totalItems'=>$totalItems,
                'sumMoney'=>$sumMoney,
                'countProduct'=>$countProduct
            ]
        );
    }


    //订单详情
    public function info()
    {
        $id = Request::query()->get('zget0');

        if(empty($id)){
            $this->redirect('/sysadmin/order');
        }
        $info = OrderInfo::findOne(hashids_decode($id));

        if(empty($info)){
            $this->redirect('/sysadmin/order');
        }

        $res=Order::find()->where(['order_no'=>$info['order_no']])->asArray()->one();
        $res['info']=$info;

        /*$res['refund']=OrderRefund::find()->where(['order_info_id'=>$info['id']])->asArray()->one();
        if($res['refund']){
            $res['refund']['arrImg']=explode(',',$res['refund']['img']);
        }*/

        return $this->render('order/orderinfo',[
            'res'=>$res,
            'payState'=>$this->payState,
            'orderState'=>$this->orderState,
            'deliverCompany'=>$this->deliverCompany,
            //'refundState'=>$this->refundState
        ]);
    }

    //发货
    public function ajax_deliver_goods()
    {
        $data = Request::request()->all();
        $data['deliver_company'] = isset($data['deliver_company']) ? filter_var($data['deliver_company'],FILTER_SANITIZE_SPECIAL_CHARS) : '';
        $data['deliver_no'] = isset($data['deliver_no']) ? filter_var($data['deliver_no'],FILTER_SANITIZE_SPECIAL_CHARS) : '';
        $data['deliver_time']=time();
        $data['order_state']=2;
        $is_success = false;
        if(isset($data['id']) && !empty($data['id']) ){ //修改内容
            $role = OrderInfo::findOne($data['id']);

            if($role){
                $role->setAttributes($data);
                $is_success = $role->save();
            }
        }

        if($is_success){
            $msg = ['status' => 1001,'msg' => '发货成功！'];
        }else {
            $msg = ['status' => 3001,'msg' => '发货失败！'];
        }

        return $this->json($msg);
    }

    /**
     * 处理售后

    function process_refund()
    {
        $data=Request::request()->all();
        $data['result'] = isset($data['result']) ? filter_var($data['result'],FILTER_SANITIZE_SPECIAL_CHARS) : '';

        $refundModel=OrderRefund::findOne($data['id']);
        $refundModel->setAttributes($data);
        $refundModel->save();

        $refund=$refundModel->toArray();

        $orderInfoModel=OrderInfo::find()->where(['id'=>$refund['order_info_id']])->one();
        $orderInfoModel->order_state=5;
        $orderInfoModel->save();

        $this->redirect($_SERVER['HTTP_REFERER']);
    }
*/


    function isMobile($mobile) {
        if (!is_numeric($mobile)) {
            return false;
        }
        return preg_match('#^13[\d]{9}$|^14[5,7]{1}\d{8}$|^15[^4]{1}\d{8}$|^17[0,6,7,8]{1}\d{8}$|^18[\d]{9}$#', $mobile) ? true : false;
    }

    function isEmail($email){
        $pattern="/([a-z0-9]*[-_.]?[a-z0-9]+)*@([a-z0-9]*[-_]?[a-z0-9]+)+[.][a-z]{2,3}([.][a-z]{2})?/i";
        if(preg_match($pattern,$email)){
            return true;
        } else{
            return false;
        }
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