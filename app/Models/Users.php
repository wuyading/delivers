<?php
/**
 * Created by PhpStorm.
 * User: lilei
 * Date: 17-6-26
 * Time: 上午9:31
 */

namespace App\Models;

class Users extends BaseModel
{
    function __construct(array $config = [])
    {
        parent::__construct($config);
    }

    public static function tableName()
    {
        return 'users';
    }

    /**
     * 获取用户绑定id
     * @param $user_id
     * @return int
     */
    public static function getBindUserId($user_id)
    {
        $userInfo = Users::findOne($user_id)->toArray();
        //todo 判断用户是否是分销者
        $isUserShare = $userInfo['is_share'];
        if (!$isUserShare) {
            return intval($userInfo['fx_user_id']);
        }
        return 0;
    }
}