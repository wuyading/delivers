<?php

namespace App\Models;

use Zilf\Db\ActiveRecord;

class MenuCategory extends ActiveRecord
{
    public static function tableName()
    {
        return 'menu_category';
    }
}