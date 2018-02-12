<?php

namespace App\Models;

use Zilf\Db\ActiveRecord;

class Category extends ActiveRecord
{
    public static function tableName()
    {
        return 'category';
    }

    public static function categoryAll($field='*',$where = array())
    {
        return self::find()->select($field)->where($where)->asArray()->all();
    }

    public function getGoods()
    {
        return $this->hasMany(Goods::className(),['type_id'=>'id']);
    }

    public function getGood()
    {
        return $this->hasOne(Goods::className(),['type_id'=>'id']);
    }
}