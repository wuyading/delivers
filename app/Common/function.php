<?php
/**
 * 自定义全局公共函数库
 * User: cheney (秦林)
 * Date: 2017/12/13
 * Time: 11:06
 */

if(!function_exists('getImagesOne')){
    /**
     * 获取图片集合的首图
     * @param $imgAllStr 图片集合字符串
     * @param $default   图片不存在的默认显示图片地址
     * @return mixed
     */
    function getImagesOne($imgAllStr,$default='seller/images/img-order.png'){
        $logos = empty($imgAllStr) ? [''] : explode(',',$imgAllStr);
        return empty($logos[0]) ? $default : $logos[0];
    }
}

if(!function_exists('showImgAll')) {
    function showImgAll($imgs = '',$class='detail-img',$br='&nbsp;')
    {
        $logos = empty($imgs) ? '' : explode(',', $imgs);
        if ($logos) {
            foreach ($logos as $logo){
                $html = asset_img($logo, ['class' => $class]).$br;
            }
            echo $html;
        }
        echo '';
    }
}

if(!function_exists('GetIP')) {
    /**
     * 获取用户真实ip
     */
    function GetIP()
    {
        if (getenv("HTTP_CLIENT_IP") && strcasecmp(getenv("HTTP_CLIENT_IP"), "unknown"))
            $ip = getenv("HTTP_CLIENT_IP");
        else if (getenv("HTTP_X_FORWARDED_FOR") && strcasecmp(getenv("HTTP_X_FORWARDED_FOR"), "unknown"))
            $ip = getenv("HTTP_X_FORWARDED_FOR");
        else if (getenv("REMOTE_ADDR") && strcasecmp(getenv("REMOTE_ADDR"), "unknown"))
            $ip = getenv("REMOTE_ADDR");
        else if (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], "unknown"))
            $ip = $_SERVER['REMOTE_ADDR'];
        else
            $ip = "unknown";
        return ($ip);
    }
}

if(!function_exists('isMobile')) {
    /**
     * 验证手机号格式是否正确
     * @param $mobile
     * @return bool
     */
    function isMobile($mobile)
    {
        if (!is_numeric($mobile)) {
            return false;
        }
        return preg_match('#^13[\d]{9}$|^14[5,7]{1}\d{8}$|^15[^4]{1}\d{8}$|^17[0,6,7,8]{1}\d{8}$|^18[\d]{9}$#', $mobile) ? true : false;
    }
}

if(!function_exists('isEmail')) {
    /**
     * 验证email格式是否正确
     * @param $email
     * @return bool
     */
    function isEmail($email)
    {
        $pattern = "/([a-z0-9]*[-_.]?[a-z0-9]+)*@([a-z0-9]*[-_]?[a-z0-9]+)+[.][a-z]{2,3}([.][a-z]{2})?/i";
        if (preg_match($pattern, $email)) {
            return true;
        } else {
            return false;
        }
    }
}

if(!function_exists('jsBack')) {
    /**
     * 返回页面并输出提示
     * @param $msg
     */
    function jsBack($msg)
    {
        header('Content-Type:text/html;charset=utf-8 ');
        echo '<script type="text/javascript">';
        echo "alert(' ".$msg." ');";
        echo 'window.history.go(-1);';
        echo '</script>';
        die;
    }
}

if(!function_exists('getRegionName')) {
    /**
     * 根据id获取省份名称，城市名称，区域名称
     */
    function getRegionName($provinceId = 0, $cityId = 0, $areaId = 0)
    {
        $res = ['province' => '', 'city' => '', 'area' => ''];
        if ($provinceId) {
            $row = \App\Models\Region::findOne(intval($provinceId))->toArray();
            $res['province'] = $row['region_name'];
        }
        if ($cityId) {
            $row = \App\Models\Region::findOne(intval($cityId))->toArray();
            $res['city'] = $row['region_name'];
        }
        if ($cityId) {
            $row = \App\Models\Region::findOne(intval($areaId))->toArray();
            $res['area'] = $row['region_name'];
        }
        return $res;
    }
}

if(!function_exists('getStatusStr')) {
    /**
     * 获取审核状态
     * @return string
     */
    function getStatusStr($status,$statusArray=['待审核','审核通过','审核失败'])
    {
        return isset($statusArray[(int) $status]) ? $statusArray[$status] : '';
    }
}

if(!function_exists('toDate')) {
    /**
     * 获取审核状态
     * @return string
     */
    function toDate($time=null,$format='Y-m-d H:i:s')    {
        return ($time) ? date($format,$time) : '';
    }
}


if(!function_exists('storeToName')) {
    /**
     * 获取审核状态
     * @return string
     */
    function storeToName($store_id)    {
        $store = \App\Models\Store::findOne($store_id);
        return $store ? $store->name : '';
    }
}

if(!function_exists('showSelected')) {
    /**
     * 下拉框默认选择
     * @return string
     */
    function showSelected($optionVal,$find,$field,$defaultVal=''){
        if(isset($find[$field])){
            $selectVal = $find[$field];
            $defaultVal = ($selectVal == $optionVal) ? $selectVal : $defaultVal;
        }
        return ($defaultVal == $optionVal) ? 'selected' : '';
    }
}
if(!function_exists('getOrderSn')) {
    /**
     * 生成订单号
     * @return string
     */
    function getOrderSn()
    {
        return date('YmdHis') . rand(1000, 9999);
    }
}

if(!function_exists('setCodeUrl')) {
    /**
     * 数组转换为路径
     * @return string
     */
    function setCodeUrl($action,$pathArr)
    {
        return urlencode($action.'?'.http_build_query($pathArr));
    }
}
if(!function_exists('get_device_type')) {
    /**
     * 判断手机操作系统
     * @return string
     */
    function get_device_type()
    {
        //全部变成小写字母
        $agent = strtolower($_SERVER['HTTP_USER_AGENT']);
        $type = 'other';
        //分别进行判断
        if (strpos($agent, 'iphone') || strpos($agent, 'ipad')) {
            $type = 'ios';
        }
        if (strpos($agent, 'android')) {
            $type = 'android';
        }
        return $type;
    }

if(!function_exists('author_hidden')) {
    //加密字符串
    function author_hidden($author_name)
    {
        $preg_mobile = "/^13[0-9]{1}[0-9]{8}$|15[0-9]{1}[0-9]{8}$|18[0-9]{1}[0-9]{8}$/";
        $preg_mail = "/^(\w )+(\.\w+)*@(\w)+((\.\w +)+)$/";
        $preg_name = "/^[\u 4E00-\u9FFF]+$/";
        if (preg_match($preg_mobile, $author_name)) {
            return substr_replace($author_name, "****", 3, 4);
        } elseif (preg_match($preg_mail, $author_name)) {
            $back = strstr($author_name, "@");
            $front = strstr($author_name, "@", true);
            $author_name = substr_replace($front, "**", 3) . $back;
            return $author_name;
        } elseif (preg_match($preg_name, $author_name)) {
            $author_name = substr_replace($author_name, "**", 3);
            return $author_name;
        } else {
            return substr_replace($author_name, "**", 3);
        }
    }
}

}