<?php
/**
 * 车源
 * Created by PhpStorm.
 * User: qinlin
 * Date: 2018/1/3
 * Time: 11:06
 */

namespace App\Common\Services;

use App\Models\Product;
use App\Models\Category;
class CartService
{
    /**
     * 获取列表数据
     * @return array
     */
    public static function getListData($model , $col='*', $orderBy=null, $groupBy=null , $having=null , $url='', $page=1 , $pageSize = 10)
    {
        //$orderBy='',$groupBy='',$having='',$urlPattern
        return Product::getModelPageList($model ,$col, $orderBy , $groupBy , $having , $url , $page , $pageSize);
    }

    /**
     * @param $id
     * @return array|bool|null|\Zilf\Db\ActiveRecord
     */
    public static function getById($id)
    {
        if(empty($id)){
            return false;
        }
        $model = Product::find()->where(['id'=>$id]);
        if(!$model){
            return false;
        }
        return $model->asArray()->one();
    }
    /**
     * @param $where
     */
    public static function getByWhere($where)
    {
        $model = Product::find()->where($where);
        if(!$model){
            return false;
        }
        return $model->asArray()->one();
    }

    /**
     * @param $set
     * @param $where
     * @return int
     */
    public static function update($set,$where)
    {
        return Product::updateAll($set,$where);
    }
}