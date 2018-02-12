<?php

namespace App\Plugins\Login;

use App\Plugins\Login\Services\LoginService;
use App\Sysadmin\Controllers\SysadminBaseController;
use Zilf\System\Controller;
use Zilf\System\Zilf;

class LoginPlugin extends Controller
{
    public $loginParams = [];

    public $privateRSAKey = '-----BEGIN RSA PRIVATE KEY-----
MIICXQIBAAKBgQC3//sR2tXw0wrC2DySx8vNGlqt3Y7ldU9+LBLI6e1KS5lfc5jl
TGF7KBTSkCHBM3ouEHWqp1ZJ85iJe59aF5gIB2klBd6h4wrbbHA2XE1sq21ykja/
Gqx7/IRia3zQfxGv/qEkyGOx+XALVoOlZqDwh76o2n1vP1D+tD3amHsK7QIDAQAB
AoGBAKH14bMitESqD4PYwODWmy7rrrvyFPEnJJTECLjvKB7IkrVxVDkp1XiJnGKH
2h5syHQ5qslPSGYJ1M/XkDnGINwaLVHVD3BoKKgKg1bZn7ao5pXT+herqxaVwWs6
ga63yVSIC8jcODxiuvxJnUMQRLaqoF6aUb/2VWc2T5MDmxLhAkEA3pwGpvXgLiWL
3h7QLYZLrLrbFRuRN4CYl4UYaAKokkAvZly04Glle8ycgOc2DzL4eiL4l/+x/gaq
deJU/cHLRQJBANOZY0mEoVkwhU4bScSdnfM6usQowYBEwHYYh/OTv1a3SqcCE1f+
qbAclCqeNiHajCcDmgYJ53LfIgyv0wCS54kCQAXaPkaHclRkQlAdqUV5IWYyJ25f
oiq+Y8SgCCs73qixrU1YpJy9yKA/meG9smsl4Oh9IOIGI+zUygh9YdSmEq0CQQC2
4G3IP2G3lNDRdZIm5NZ7PfnmyRabxk/UgVUWdk47IwTZHFkdhxKfC8QepUhBsAHL
QjifGXY4eJKUBm3FpDGJAkAFwUxYssiJjvrHwnHFbg0rFkvvY63OSmnRxiL4X6EY
yI9lblCsyfpl25l7l5zmJrAHn45zAiOoBrWqpM5edu7c
-----END RSA PRIVATE KEY-----';

    public $publicRSAKey = '-----BEGIN PUBLIC KEY-----
MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQC3//sR2tXw0wrC2DySx8vNGlqt
3Y7ldU9+LBLI6e1KS5lfc5jlTGF7KBTSkCHBM3ouEHWqp1ZJ85iJe59aF5gIB2kl
Bd6h4wrbbHA2XE1sq21ykja/Gqx7/IRia3zQfxGv/qEkyGOx+XALVoOlZqDwh76o
2n1vP1D+tD3amHsK7QIDAQAB
-----END PUBLIC KEY-----';

    /**
     * 1 帐号登录
     * 2 帐号、手机号登录
     * 3 帐号、手机号、邮箱登录
     */
    public $loginType = 1;
    public $tableObj = '';
    public $loginService = null;

    /**
     * LoginPlugin constructor.
     * @param $token
     */
    function __construct($token)
    {
        parent::__construct();

        if (!$this->loginService)
            $this->loginService = new LoginService($token);
    }

    /**
     * 设置数据库对象
     *
     * @param $tableObj
     */
    function setTableObj($tableObj)
    {
        Zilf::$container->register($tableObj, $tableObj);
        $this->tableObj = Zilf::$container->get($tableObj);
    }

    /**
     * 页面视图
     *
     * @param string $viewPath
     * @param array $data
     * @return \Zilf\HttpFoundation\Response
     */
    function loginView($viewPath, $data = [])
    {
        return $this->render($viewPath, $data);
    }

    /**
     * 设置登录的参数
     *
     * @param array $data
     * @param array $checkRules 验证规则
     */
    public function setParams(array $data = [], $checkRules = [])
    {
        $this->loginParams = $data;
    }

    /**
     * 用户登录
     */
    public function loginIn($data)
    {
        $username = $data['username'];
        $password = $data['password'];
        $result = $this->tableObj->find()->where(['username' => $username])->asArray()->one();
        if (!empty($result)) {
            if (password_check($password, $result['password'])) {
                //设置登录状态
                $this->loginService->setLoginCookie($result, config_helper('cookie.cookie_expire'));
                $msg = array('code' => 1001, 'message' => '登录成功', 'data' => $result);
            } else {
                $msg = array('code' => 2001, 'message' => '帐号密码不正确！');
            }
        } else {
            $msg = array('code' => 2002, 'message' => '帐号密码不正确！');
        }

        return $msg;
    }

    /** 验证登录
     * @param $where
     * @param $password
     * @return array
     */
    public function checkLoginIn($where,$password)
    {
        $result = $this->tableObj->find()->where($where)->asArray()->one();
        if (!empty($result)) {
            if (password_check($password, $result['password'])) {
                //设置登录状态
                $this->loginService->setLoginCookie($result, config_helper('cookie.cookie_expire'));
                $msg = array('code' => 1001, 'message' => '登录成功', 'data' => $result);
            } else {
                $msg = array('code' => 2001, 'message' => '帐号密码不正确！');
            }
        } else {
            $msg = array('code' => 2002, 'message' => '帐号密码不正确！');
        }

        return $msg;
    }

    /**
     * 用户登录
     */
    public function loginRsaIn($data)
    {
        $username = $data['username'];
        $public_password = $data['password'];

        //通过私钥解密
        $password = '';
        $pi_key =  openssl_pkey_get_private($this->privateRSAKey);//这个函数可用来判断私钥是否是可用的，可用返回资源id Resource id
        openssl_private_decrypt(base64_decode($public_password),$password,$pi_key);//

        //
        $result = $this->tableObj->find()->where(['username' => $username])->asArray()->one();
        if (!empty($result)) {
            if (password_check($password, $result['password'])) {
                //设置登录状态
                $this->loginService->setLoginCookie($result, config_helper('cookie.cookie_expire'));
                $msg = array('code' => 1001, 'message' => '登录成功', 'data' => $result);
            } else {
                $msg = array('code' => 2001, 'message' => '帐号密码不正确！');
            }
        } else {
            $msg = array('code' => 2002, 'message' => '帐号密码不正确！');
        }

        return $msg;
    }

    /**
     * 退出登录
     *
     * @return array
     */
    public function loginOut()
    {
        $this->loginService->clearLoginInfo();
        return array('code' => 1001, 'message' => 'success!');
    }

    /**
     * 判断用户是否已经登录
     *
     * 使用方法
     * 1/ isLogin()  如果没有登录则会跳转到登录页面并加上当前的 $back_url
     * 2/ isLogin($url) 会跳转到 $url 的网址
     * 3/ isLogin(false) 则不会跳转直接返回当前的用户信息，没有登录则返回空
     *
     * @param string $back_url
     * @return bool|mixed
     */
    public function checkUserIsLogin($back_url = '')
    {
        $info = $this->loginService->checkLoginToken();
        if (empty($info)) {
            if ($back_url === false || $back_url == 'false') {
            } else {
                if ($back_url) {
                    if (stripos('?', $back_url) === false) {
                        $back_url = '?back_url=' . urlencode($back_url);
                    } else {
                        $back_url = '&back_url=' . urlencode($back_url);
                    }
                } else {
                    $back_url = '?back_url=' . urlencode(current_url());
                }
                $this->redirect('/sysadmin/login' . $back_url);
            }
        }

        return json_decode($info, true);
    }

    /**
     * 判断用户是否已经登录
     *
     * 使用方法
     * 1/ isLogin()  如果没有登录则会跳转到登录页面并加上当前的 $back_url
     * 2/ isLogin($url) 会跳转到 $url 的网址
     * 3/ isLogin(false) 则不会跳转直接返回当前的用户信息，没有登录则返回空
     *
     * @param string $back_url
     * @return bool|mixed
     */
    public function checkMembersLogin($back_url = '')
    {
        $info = $this->loginService->checkLoginToken();
        if (empty($info)) {
            if ($back_url === false || $back_url == 'false') {
            } else {
                $this->redirect($back_url);
            }
        }

        return json_decode($info, true);
    }
    /**
     * 更新用户信息
     */
    public function update($data, $where)
    {
        $class = new SysadminBaseController();
        $result = $class->updateSqlData($this->tableObj, $data, $where);
        return $this->json($result);
    }

}