<?php

namespace App\Models;

use Zilf\Db\ActiveRecord;

class CategoryType extends ActiveRecord
{
    public static function tableName()
    {
        return 'category_type';
    }
}