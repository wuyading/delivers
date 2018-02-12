<?php
/**
 * Created by PhpStorm.
 * User: lilei
 * Date: 17-6-13
 * Time: 下午1:06
 */

namespace App\Sysadmin\Controllers;


use App\Models\Store;
use App\Models\Sysadmin;
use App\Models\Role;
use App\Models\Privilege;
use App\Models\MenuCategory;
use App\Http\Services\Functions;
use App\Models\Users;
use Endroid\QrCode\QrCode;
use Zilf\Facades\Request;
use App\Plugins\Login\LoginPlugin;

class AdminmanageController extends SysadminBaseController
{
    public function __construct()
    {
        parent::__construct();

        $this->isLogin();
    }

    public function user_list()
    {
        $currentPage = Request::query()->getInt('zget0');
        $currentPage = $currentPage > 0 ? $currentPage : 1;
        $urlPattern = toRoute('adminmanage/user_list/(:num)');
        $data = Sysadmin::getPageList('', $urlPattern, $currentPage);
        $role=Role::find()->asArray()->all();
        $role=array_column($role,'role_name','id');
        foreach($data['list'] as $k=>$v){
            $data['list'][$k]['role_id']=$role[$v['role_id']];
        }
        return $this->render('adminmanage/index',['list'=>$data['list'],'page'=>$data['page']]);
    }

    public function user_add(){
        $adminId = hashids_decode(Request::query()->get('zget0'));
        $res=[];
        if($adminId){
            $res = Sysadmin::findOne($adminId)->toArray();
        }
        $role=Role::find()->where(['is_delete' => 1])->asArray()->all();
        return $this->render('adminmanage/user_add',['res'=>$res,'role'=>$role]);
    }



    /**
     * ajax保存管理员信息
     */
    public function ajax_user_save(){
        $data = Request::request()->all();


        $data['username'] = isset($data['username']) ? filter_var($data['username'],FILTER_SANITIZE_SPECIAL_CHARS) : '';
        $data['password'] = isset($data['password']) ? filter_var($data['password'],FILTER_SANITIZE_SPECIAL_CHARS) : '';
        $data['email']    = isset($data['email']) ? filter_var($data['email'],FILTER_SANITIZE_SPECIAL_CHARS) : '';
        $data['real_name'] = isset($data['real_name']) ? filter_var($data['real_name'],FILTER_SANITIZE_SPECIAL_CHARS) : '';
        $data['role_id']  = isset($data['role_id']) ? intval($data['role_id']) : 0;
        $data['mobile']   = isset($data['mobile']) ? $data['mobile'] : 0;

        $data['add_time'] =time();

        if($data['password']){
            $data['password']=password_make($data['password']);
        }else{
            unset($data['password']);
        }

        $Functions=new Functions();

        if($Functions->isMobile($data['mobile'])===false){
            $msg = ['status'=>1002,'msg'=>'手机号格式错误！'];
        }elseif(!empty($data['email'])&&$Functions->isEmail($data['email'])===false){
            $msg = ['status'=>1003,'msg'=>'邮箱格式错误！'];
        }else{

            if(isset($data['id']) && !empty($data['id'])) { //修改内容
                $isSignUp = Sysadmin::find()->where(['username' => $data['username']])->andWhere(['not in','id',[$data['id']]])->count();
                if ($isSignUp) {
                    $msg = ['status' => 0, 'msg' => '该账号已被注册！'];
                    return $this->json_callback($msg);
                }

                $data['add_date'] = date('Y-m-d H;i;s');

                $sysadmin_model = Sysadmin::find()->where(['id'=>$data['id']])->one();
                $sysadmin_model->setAttributes($data);
                $is_success=$sysadmin_model->save();

            }else{ //添加管理员

                $isSignUp = Sysadmin::find()->where(['username' => $data['username']])->count();
                if ($isSignUp) {
                    $msg = ['status' => 0, 'msg' => '该账号已被注册！'];
                    return $this->json_callback($msg);
                }

                $data['add_date'] = date('Y-m-d H;i;s');
                $sysadmin_model = new Sysadmin();
                $sysadmin_model->setAttributes($data);
                $is_success=$sysadmin_model->save();
            }

            if ($is_success) {
                $msg = ['status' => 1001, 'msg' => '保存成功！'];
            }else {
                $msg = ['status' => 3001, 'msg' => '保存失败！'];
            }
        }

        return $this->json_callback($msg);
    }


    /**
     * ajax保存管理员信息
     */
    public function ajax_user_delete()
    {
        $id = Request::request()->getInt('id');

        if (empty($id)) {
            $msg = ['status'=>1002,'msg'=>'删除失败！'];
        }else{
            $where['id'] = ['eq',$id];
            $member = Sysadmin::findOne($id);
            $is_success = $member->delete();
            if ($is_success) {
                $msg = ['status'=>1001,'msg'=>'删除成功！'];
            } else {
                $msg = ['status'=>1002,'msg'=>'删除失败！'];
            }
        }

        return $this->json($msg);
    }

    /**
     * 修改个人信息
     */
    public function edit_info(){
        $info = Sysadmin::findOne($this->userInfo['id'])->toArray();
        return $this->render('adminmanage/edit_info',['info'=>$info]);
    }

    /**
     * 修改密码
     */
    public function edit_pwd(){
        return $this->render('adminmanage/edit_pwd');
    }

    /**
     * @return String
     */
    public function ajax_save_info(){
        $data['real_name'] = Request::get('real_name');
        $data['mobile'] = Request::get('mobile');
        $data['email'] = Request::get('email');
        $setting = Sysadmin::findOne($this->userInfo['id']);

        if ($setting) {
            $setting->setAttributes($data);
            $is_success = $setting->save();
        }else{
            $is_success = false;
        }

        if($is_success){
            $msg = ['status'=>1001,'msg'=>'保存成功！'];
        }else{
            $msg = ['status'=>3001,'msg'=>'保存失败！'];
        }
        return $this->json_callback($msg);
    }

    public function json_callback($data,$parent='parent',$method='show_message'){
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

    /**
     * 修改密码
     * @return String
     */
    public function ajax_save_pwd(){
        $old_pwd = Request::get('old_pwd');
        $pwd = Request::get('pwd');
        $repwd = Request::get('repwd');
        if(!$old_pwd || !$pwd || !$repwd){
            $msg = ['status'=>3001,'msg'=>'请输入正确内容！'];
        }elseif($old_pwd == $pwd){
            $msg = ['status'=>3001,'msg'=>'输入的新密码和原密码一样！'];
        }elseif($pwd != $repwd){
                $msg = ['status'=>3001,'msg'=>'两次密码输入不一致！'];
        }else{
            $obj = Sysadmin::findOne($this->userInfo['id']);
            if(password_check($old_pwd,$obj->password)){
                $data['password'] = password_make($pwd);
                $return = $obj->updateAll($data,['id'=>$this->userInfo['id']]);
                if($return){
                    $msg = ['status'=>1001,'msg'=>'密码修改成功！'];
                    $login = new LoginPlugin('userLoginToken');
                    $login->loginOut();
                }else{
                    $msg = ['status'=>3001,'msg'=>'密码修改失败！'];
                }
            }else{
                $msg = ['status'=>3001,'msg'=>'原密码输入错误！'];
            }
        }

        return $this->json_callback($msg);
    }

    //管理员角色表
    public function user_role()
    {
        $currentPage = Request::query()->getInt('zget0');
        $currentPage = $currentPage > 0 ? $currentPage : 1;
        $urlPattern = toRoute('adminmanage/role/(:num)');
        $where['is_delete'] = 1;
        $data = Role::getPageList($where, $urlPattern, $currentPage);
        return $this->render('adminmanage/role',['list'=>$data['list'],'page'=>$data['page']]);
    }

    //添加角色
    public function role_add()
    {
        $id = Request::query()->get('zget0');
        if($id){
            $info = Role::findOne(hashids_decode($id))->toArray();
            return $this->render('adminmanage/role_add',['info'=>$info]);
        }else{
            return $this->render('adminmanage/role_add');
        }
    }

    //保存修改添加
    public function ajax_save_role()
    {
        $data = Request::request()->all();
        $data['role_name'] = isset($data['role_name']) ? filter_var($data['role_name'],FILTER_SANITIZE_SPECIAL_CHARS) : '';
        $data['description'] = isset($data['description']) ? filter_var($data['description'],FILTER_SANITIZE_SPECIAL_CHARS) : '';
        $data['sortid'] = isset($data['sortid'])?$data['sortid']:1;
        if(isset($data['id']) && !empty($data['id']) ){ //修改内容
            $role = Role::findOne($data['id']);
            if($role){
                $role->setAttributes($data);
                $is_success = $role->save();
            }else{
                $is_success = false;
            }
        }else{ //添加内容
            $role_model = new Role();
            $role_model->setAttributes($data);
            $is_success = $role_model->save();
        }

        if($is_success){
            $msg = ['status'=>1001,'msg'=>'保存成功！'];
        }else{
            $msg = ['status'=>3001,'msg'=>'保存失败！'];
        }


        return $this->json_callback($msg);
    }

    //删除角色 伪删除
    public function ajax_role_delete()
    {
        $id = Request::request()->getInt('id');

        if (empty($id)) {
            $msg = ['status'=>1002,'msg'=>'删除失败！'];
        }else{
            $data['is_delete'] = 2;
            $where['id'] = ['eq',$id];
            $role = Role::findOne($id);
            $role->setAttributes($data);
            $is_success = $role->save();
            if ($is_success) {
                $msg = ['status'=>1001,'msg'=>'删除成功！'];
            } else {
                $msg = ['status'=>1002,'msg'=>'删除失败！'];
            }
        }

        return $this->json($msg);
    }


    //权限设置
    public function role_privilege()
    {
        $id = Request::query()->get('zget0');
        $menu = MenuCategory::find()->asArray()->all();
        $info = Privilege::findOne(hashids_decode($id));
        if($info){
            $privilege = $info->toArray();
            return $this->render('/adminmanage/privilege',['id'=>$id,'privilege'=>$privilege,'list'=>$menu]);
        }else{
            return $this->render('/adminmanage/privilege',['id'=>$id,'list'=>$menu]);
        }
    }

    //权限保存
    public function ajax_save_privilege()
    {
        $privileges = Request::get('privilege');
        $id = Request::get('id');
        if(isset($privileges)){
            $privilege['privilege'] = implode(',',$privileges);
        }else{
            $privilege['privilege'] = '';
        }
        if (!empty($id)) {
            $id = hashids_decode($id);
            $info = Privilege::findOne($id);
            $privilege['role_id'] = $id;
            if ($info) {
                $info->setAttributes($privilege);
                $return = $info->save();
            } else {
                $privilege_model = new Privilege();
                $privilege_model->setAttributes($privilege);
                $return = $privilege_model->save();
            }
            if ($return) {
                $msg = ['status' => 1001, 'msg' => '保存成功！'];
            } else {
                $msg = ['status' => 1002, 'msg' => '保存失败！'];
            }
        }else{
            $msg = ['status' => 1002, 'msg' => '参数错误！'];
        }
        return $this->json($msg);
    }

    /**
     * 绑定微信
     */
    function bind_wx()
    {
        $is_gh=Request::query()->getInt('is_gh');
        if($this->userInfo['store_id']==0){
            $this->redirect('/sysadmin');
        }
        $store=Store::find()->where(['id'=>$this->userInfo['store_id']])->asArray()->one();

        $user=Users::find()->where(['id'=>$store['user_id']])->asArray()->one();

        return $this->render('adminmanage/bind_wx',[
            'user'=>$user,
            'is_gh'=>$is_gh
        ]);
    }

    /**
     * 绑定跳转二维码
     */
    function bind_qrcode()
    {
        $url="http://${_SERVER['HTTP_HOST']}/store/bind_wx/".hashids_encode($this->userInfo['store_id']);
        $qrCode = new QrCode($url);
        header('Content-Type: '.$qrCode->getContentType());
        echo $qrCode->writeString();
    }

}