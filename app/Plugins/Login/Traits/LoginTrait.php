<?php

namespace App\Plugins\Login\Traits;

use Zilf\Facades\Request;

/**
 * @method getLoginPluginObj() App\Plugins\Login\LoginPlugin
 *
 * Trait LoginTrait
 * @package App\Plugins\Login\Traits
 */
trait LoginTrait
{
    /**
     * 默认的登录页面
     */
    public function index()
    {
        $back_url = Request::query()->get('back_url');
        return $this->getLoginPluginObj()->loginView('plugins/login/login', [
            'app' => $this,
            'back_url' => base64_encode($back_url)
        ]);
    }

    /**
     * 登录操作
     */
    public function login_in()
    {
        $data = $this->getRequestData();
        $result = $this->getLoginPluginObj()->loginIn($data);
        $save['last_time'] = time();
        $save['last_ip'] = Request::getClientIp();

        if ($result['code'] == 1001) {
            //将登录IP和时间写入到admin表中
            $this->getLoginPluginObj()->update($save, ['id' => $result['data']['id']]);
            if (isset($data['back_url']) && !empty($data['back_url'])) {
                $back_url = urldecode(base64_decode($data['back_url']));
                $this->redirect($back_url);
            } else {
                $this->redirect($this->loginInRedirectUrl);
            }
        } else {
            dump($result);
            die();
        }
    }

}