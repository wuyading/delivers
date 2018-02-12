<?php
/**
 * Created by PhpStorm.
 * User: lilei
 * Date: 16-9-8
 * Time: 下午1:49
 */

namespace App\Models;

class Setting extends BaseModel
{
    function __construct(array $config = [])
    {
        parent::__construct($config);
    }

    public static function tableName()
    {
        return 'settings';
    }
}