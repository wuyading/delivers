<?php
/**
 * Created by PhpStorm.
 * User: lilei
 * Date: 17-6-13
 * Time: 下午1:06
 */

namespace App\Sysadmin\Controllers;

class DeliverController extends SysadminBaseController
{

    public function __construct()
    {
        parent::__construct();

        $this->isLogin();
    }

    //订单列表
    public function index()
    {
        return $this->render('/deliver/index');
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