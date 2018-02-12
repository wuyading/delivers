<?php
/**
 * Created by PhpStorm.
 * User: lilei
 * Date: 17-4-7
 * Time: 上午10:44
 */

namespace App\Models;


use JasonGrimes\Paginator;
use Zilf\Db\ActiveRecord;
use Zilf\System\Zilf;

class BaseModel extends ActiveRecord
{
    public function __construct(array $config = [])
    {
        parent::__construct($config);
    }

    /**
     * 获取具有分页信息的数据列表
     *
     * @param string $where
     * @param $urlPattern
     * @param int $currentPage
     * @param array $options
     * @param int $itemsPerPage
     * @return array
     */
    public static function  getPageList($where='', $urlPattern, $currentPage=1,$options=[],$itemsPerPage=10){
        $find = self::find();

        if(empty($where['and'])&&empty($where['andFilterWhere'])){
            $find->where($where);
        }

        if(!empty($where['and'])){
            $find->where($where['and']);
        }

        if(!empty($where['andFilterWhere'])){
            $find->andFilterWhere($where['andFilterWhere']);
        }

        if(!isset($options['orderBy'])){
            $find->orderBy('id desc');
        }

        if($options){
            foreach ($options as $name => $value){
                $find->$name($value);
            }
        }

        $totalItems = $find->count();
        $list = $find->offset(($currentPage-1)*$itemsPerPage)->limit($itemsPerPage)->asArray()->all();

        //分页数据
        $paginator = new Paginator($totalItems, $itemsPerPage, $currentPage, $urlPattern);
        $paginator->setPreviousText('上一页');
        $paginator->setNextText('下一页');

        $data = [
            'list'=>$list,
            'page'=>$paginator,
        ];

        return $data;
    }

    /**
     * 使用sql语句获取分页数据
     * @param $sql
     * @param $countSql
     * @param $currentPage
     * @param int $itemsPerPage
     */
    public static function  getSqlPageList($sql,$countSql,$urlPattern,$currentPage=1,$itemsPerPage=10)
    {
        $find = self::find();


        $totalItems = $find->count();
        $list = $find->offset(($currentPage-1)*$itemsPerPage)->limit($itemsPerPage)->asArray()->all();

        //分页数据
        $paginator = new Paginator($totalItems, $itemsPerPage, $currentPage, $urlPattern);
        $paginator->setPreviousText('上一页');
        $paginator->setNextText('下一页');

        $data = [
            'list'=>$list,
            'page'=>$paginator,
        ];

        return $data;
    }

    /**
     * @param $model
     * @param string $orderBy
     * @param string $groupBy
     * @param string $having
     * @param $urlPattern
     * @param int $currentPage
     * @param int $itemsPerPage
     * @return array
     */
    public static function getModelPageList($model, $field='*' , $orderBy=null,$groupBy=null,$having=null,$urlPattern='', $currentPage=1,$itemsPerPage=10)
    {
        $totalItems = $model->count();
        $list = $model->select($field)->orderBy($orderBy)->groupBy($groupBy)->having($having)->offset(($currentPage-1)*$itemsPerPage)->limit($itemsPerPage)->asArray()->all();

        //分页数据
        $paginator = new Paginator($totalItems, $itemsPerPage, $currentPage, $urlPattern);
        $paginator->setPreviousText('上一页');
        $paginator->setNextText('下一页');

        $data = [
            'list'        => $list,
            'page'        => $paginator,
            'countNumber' => $totalItems,
            'countPage'   => ceil($totalItems / $itemsPerPage)
        ];
        return $data;
    }
    
    /** 输出SQL语句
     * @param $quer
     */
    public static function showSql($query)
    {
        $commandQuery = clone $query;
        // 输出SQL语句
        return $commandQuery->createCommand()->getRawSql();
    }
}