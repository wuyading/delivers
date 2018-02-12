<?php
/**
 * Created by PhpStorm.
 * User: lilei
 * Date: 17-6-13
 * Time: 下午1:06
 */

namespace App\Sysadmin\Controllers;


use App\Models\Store;
use App\Models\StoreBank;
use App\Models\PayLog;
use App\Models\Supplier;
use Zilf\Facades\Request;
use App\Http\Services\SendSMS2;
use Zilf\System\Zilf;
class SettlementController extends SysadminBaseController
{

    public $bank=[
        0=>'请选择',
        1=>'农业银行',
        2=>'建设银行',
        3=>'交通银行',
        4=>'中信银行',
        5=>'光大银行',
        6=>'华夏银行',
        7=>'民生银行',
        8=>'招商银行',
        9=>'兴业银行',
        10=>'广发银行',
        11=>'平安银行',
        12=>'上海浦东发展银行',
        13=>'恒丰银行',
        14=>'中国邮政储蓄银行',
        15=>'工商银行',
    ];

    public $state=[
        0=>'处理中',
        1=>'已到账',
    ];

    public function __construct()
    {
        parent::__construct();

        $this->isLogin();
    }

    //订单列表
    public function index()
    {
        $currentPage = Request::query()->getInt('zget0');
        $currentPage = $currentPage > 0 ? $currentPage : 1;

        $urlPattern = toRoute('settlement/index/(:num)?'.$_SERVER['QUERY_STRING']);

        $where=[];
        $store=[];
        $supplier=[];
        $vars=Request::query()->all();

        if(!empty($vars['type'])){
            $where['and']['type']=intval($vars['type']);
        }
        //商户
        if($this->userInfo['role_id']==2)
        {
            $store=Store::findOne($this->userInfo['store_id'])->toArray();
            $where['and']['store_id']=$this->userInfo['store_id'];
        }elseif($this->userInfo['role_id']==5){
            $supplier=Supplier::findOne($this->userInfo['supplier_id'])->toArray();
            $where['and']['supplier_id']=$this->userInfo['supplier_id'];
        }

        if(!empty($vars['start_date'])&&!empty($vars['end_date'])){
            $where['andFilterWhere']=['between','add_time',strtotime($vars['start_date']),strtotime($vars['end_date'])];
        }

        $data = PayLog::getPageList($where, $urlPattern, $currentPage,['orderBy'=>'id desc']);

        $list=$data['list'];
        $page=$data['page'];

        foreach($list as $k=>$v){

            if($v['type']==2){
                $list[$k]['xs_money']= '- ￥'.$v['money'];
            }else{
                if($this->userInfo['role_id']==2){
                    $list[$k]['xs_money']= '+ ￥'.$v['js_money'];
                }elseif($this->userInfo['role_id']==5){
                    $list[$k]['xs_money']= '+ ￥'.$v['gys_money'];
                }else{
                    $list[$k]['xs_money']= '+ ￥'.$v['money'];
                }
            }

        }

        return $this->render('settlement/index',[
                'list'=>$list,
                'page'=>$page,
                'vars'=>$vars,
                'store'=>$store,
                'state'=>$this->state,
                'supplier'=>$supplier
            ]
        );
    }


    //渠道提现
    public function txLog()
    {
        $currentPage = Request::query()->getInt('zget0');
        $currentPage = $currentPage > 0 ? $currentPage : 1;

        $urlPattern = toRoute('settlement/index/(:num)?'.$_SERVER['QUERY_STRING']);

        $where=[];
        $store=[];
        $vars=Request::query()->all();

        $where['and']['type']=2;

        if(!empty($vars['start_date'])&&!empty($vars['end_date'])){
            $where['andFilterWhere']=['between','add_time',strtotime($vars['start_date']),strtotime($vars['end_date'])];
        }

        $data = PayLog::getPageList($where, $urlPattern, $currentPage,['orderBy'=>'id desc']);

        return $this->render('settlement/txlog',[
                'list'=>$data['list'],
                'page'=>$data['page'],
                'vars'=>$vars,
                'store'=>$store,
                'userInfo'=>$this->userInfo,
                'state'=>$this->state
            ]
        );
    }

    /**
     * 汇款
     */
    function ajax_remit()
    {
        $id=Request::request()->getInt('id');
        $roleMode=PayLog::findOne($id);
        $roleMode->setAttributes(['state'=>1]);
        $is_success=$roleMode->save();
        if($is_success){
            $msg = ['status'=>1001,'msg'=>'保存成功！'];
        }else{
            $msg = ['status'=>3001,'msg'=>'保存失败！'];
        }
        return $this->json($msg);
    }


    //银行列表
    public function bankList()
    {
        $currentPage = Request::query()->getInt('zget0');
        $currentPage = $currentPage > 0 ? $currentPage : 1;

        $urlPattern = toRoute('settlement/index/(:num)?'.$_SERVER['QUERY_STRING']);

        $where=[];
        $vars=Request::query()->all();

        //商户
        if($this->userInfo['role_id']==2)
        {
            $where['and']['sysadmin_id']=$this->userInfo['id'];
        }

        $data = StoreBank::getPageList($where, $urlPattern, $currentPage,['orderBy'=>'id desc']);
        return $this->render('settlement/banklist',[
                'list'=>$data['list'],
                'page'=>$data['page'],
                'vars'=>$vars,
                'bank'=>$this->bank
            ]
        );
    }

    //添加银行卡
    public function bankAdd()
    {
        $admin=$this->userInfo;
        $id = Request::query()->get('zget0');
        $res=[];
        if($id){
            $res = StoreBank::findOne(hashids_decode($id))->toArray();
        }

        return $this->render('settlement/bankadd',[
            'res'=>$res,
            'admin'=>$admin,
            'bank'=>$this->bank
        ]);
    }

    //商户提现
    public function storetx()
    {
        $res=[];
        if($this->userInfo['role_id']==2){
            $res=Store::find()->where(['sysadmin_id'=>$this->userInfo['id']])->asArray()->one();
        }elseif($this->userInfo['role_id']==5){
            $res=Supplier::find()->where(['sysadmin_id'=>$this->userInfo['id']])->asArray()->one();
        }
        if(empty($res)){
            $this->redirect('/sysadmin');
        }
      //  $storeBank=StoreBank::find()->where(['sysadmin_id'=>$this->userInfo['id']])->asArray()->all();

        return $this->render('settlement/storetx',[
            'res'=>$res,
            'bank'=>$this->bank,
         //   'storeBank'=>$storeBank
        ]);
    }

    //保存体现
    public function ajax_save_storetx()
    {
        $data = Request::request()->all();

        if($this->userInfo['role_id']==2){ //商户
            $row=Store::find()->where(['sysadmin_id'=>$this->userInfo['id']])->asArray()->one();
            $data['name']='商户提现';
            $data['store_id']=$row['id'];
            $model=Store::findOne($row['id']);

        }else{     //渠道
            $row=Supplier::find()->where(['sysadmin_id'=>$this->userInfo['id']])->asArray()->one();
            $data['name']='供应商提现';
            $data['supplier_id']=$row['id'];
            $model=Supplier::findOne($row['id']);

        }

        $data['money']=$data['tx_money'];
        $data['type']=2;
        $data['add_time']=time();


        if($data['tx_money']>$row['money']){
            $msg = ['status'=>1002,'msg'=>'提现金额不能大于可提现金额！'];
        }elseif($data['tx_money']<100){
            $msg = ['status'=>1002,'msg'=>'提现金额不能小于100元！'];
        } else{

            $role_model = new PayLog();
            $role_model->setAttributes($data);
            $is_success = $role_model->save();

            $model->money=$row['money']-$data['tx_money'];
            $model->save();

            if($is_success){
                $msg = ['status'=>1001,'msg'=>'提现成功！'];
            }else{
                $msg = ['status'=>3001,'msg'=>'提现失败！'];
            }
        }

        return $this->json_callback($msg);
    }



    /**
     * ajax发送短信验证码
     */
    function ajaxSendSMS(){
        $mobile=isset($_POST['mobile'])?filter_var($_POST['mobile'],FILTER_SANITIZE_SPECIAL_CHARS) : '';

        if(!$this->isMobile($mobile))
        {
            $res = ['code' => 0, 'msg' => '手机号格式错误'];
        }else {
            $send=new SendSMS2();
            $code=rand(1000,9999);
            $c="您好,您本次操作的验证码为{$code}，请勿将验证码提供给他人。【筑牛网】";
            //发送短信
            $row=$send->send($mobile,$c);

            if(isset($row['result'])&&$row['result']==1){
                $redis = Zilf::$container->get('redis');
                $cacheName=$mobile.'_'.$code.'_bank';
                $redis->set($cacheName,$code,300);
                $res = ['code' => 1, 'msg' => '发送成功'];
            }else{
                $res = ['code' => 0, 'msg' => '发送失败'];
            }
        }
        exit(json_encode($res));
    }

    //保存修改添加
    public function ajax_save_bank()
    {
        $data = Request::request()->all();

        $data['bank'] = isset($data['bank']) ? intval($data['bank']) : 0;
        $data['mobile'] = isset($data['mobile']) ?$data['mobile'] :0;
        $data['sysadmin_id']=$this->userInfo['id'];

        if($this->isMobile($data['mobile'])===false){
            $msg = ['status'=>1002,'msg'=>'手机号格式错误！'];
        }else{
            $redis = Zilf::$container->get('redis');
            $cacheName=$data['mobile'].'_'.$data['dx_code'].'_bank';
            if(!$redis->get($cacheName)){
                $this->json_callback(['code'=>0,'msg'=>'验证码错误或已过期']);
            }

            if(isset($data['id']) && !empty($data['id']) ){ //修改内容
                $role = StoreBank::findOne($data['id']);
                if($role){
                    $role->setAttributes($data);
                    $is_success = $role->save();
                }else{
                    $is_success = false;
                }
            }else{ //添加内容
                $data['add_time']=time();
                $role_model = new StoreBank();
                $role_model->setAttributes($data);
                $is_success = $role_model->save();
            }

            if($is_success){
                $msg = ['status'=>1001,'msg'=>'保存成功！'];
            }else{
                $msg = ['status'=>3001,'msg'=>'保存失败！'];
            }

        }

        return $this->json_callback($msg);
    }

    //删除银行卡
    public function ajax_delete_bank()
    {
        $id = Request::request()->getInt('id');

        if (empty($id)) {
            $msg = ['status'=>1002,'msg'=>'删除失败！'];
        }else{
            $where['id'] = ['eq',$id];
            $member = StoreBank::findOne($id);
            $is_success = $member->delete();
            if ($is_success) {
                $msg = ['status'=>1001,'msg'=>'删除成功！'];
            } else {
                $msg = ['status'=>1002,'msg'=>'删除失败！'];
            }
        }

        return $this->json($msg);
    }




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