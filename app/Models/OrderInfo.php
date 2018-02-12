<?php

namespace App\Models;


class OrderInfo extends BaseModel
{
    public static function tableName()
    {
        return 'order_info';
    }

    public function getOrder()
    {
        return $this->find()->leftJoin('order','`order_info`.order_id=`order`.id');
    }

    public function getOrders()
    {
        return $this->hasMany(Order::className(),['order_id'=>'id']);
    }
}