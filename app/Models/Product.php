<?php

namespace App\Models;


class Product extends BaseModel
{
    public function __construct($config = [])
    {
        parent::__construct($config);
    }

    public static function tableName()
    {
        return 'products';
    }

    public function getCategorys()
    {
        return $this->hasMany(Category::className(),['id'=>'category_id']);
    }
}