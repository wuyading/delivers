<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/1/3
 * Time: 14:41
 */

namespace App\Models;


class Brand extends BaseModel
{
    public function __construct($config = [])
    {
        parent::__construct($config);
    }

    public static function tableName()
    {
        return 'brand';
    }
}