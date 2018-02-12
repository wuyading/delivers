<?php
/**
 * Created by PhpStorm.
 * User: lilei
 * Date: 17-6-26
 * Time: 上午9:31
 */

namespace App\Models;

use Carbon\Carbon;

class Order extends BaseModel
{
    static $CommissionRate = 0.1;//佣金比例
    static $CommissionShare = ['0.8','0.2'];//二级佣金分成
    static $ShareEndTime   = 2;//分销过期时间两天后
    function __construct(array $config = [])
    {
        parent::__construct($config);
    }

    public static function tableName()
    {
        return 'order';
    }

    public function getOrderInfo()
    {
        return $this->hasMany(OrderInfo::className(), ['order_no' => 'order_no']);
    }

    /**
     * 计算佣金
     */
    public static function setCommission($price,$total=false)
    {
        $CommissionTotal = self::numberFormat($price * self::$CommissionRate,2);
        if($total) return $CommissionTotal;
        $commissionArr = [];
        foreach(self::$CommissionShare as $key => $share){
            $commissionArr[$key] = self::numberFormat($CommissionTotal * $share ,2);
        }
        return $commissionArr;
    }

    /**
     * 获取分销结算时间
     * @return int
     */
    public static function getShareEndTime()
    {
        return Carbon::now()->addDay(self::$ShareEndTime)->timestamp;
    }

    /**
     * 格式化浮点数并舍弃多余的
     * @param int $num
     * @param int $dist
     * @param bool $zeroComplete
     * @return int|string
     */
    public static function numberFormat($num = 0, $dist =2, $zeroComplete = TRUE) {
        if (!preg_match('/^(-?\d+)(\.\d+)?$/', $num)) {
            return $num;
        }
        if ($dist > 4) {
            $dist = 4;
        }else if ($dist <= 0) {
            $dist = 0;
        }
        if (!is_bool($zeroComplete)) {
            $zeroComplete = TRUE;
        }
        $newNum = floor($num * pow(10, $dist)) / pow(10, $dist);
        if (!$zeroComplete) {
            //去掉小数末尾的0
            $newNum = floatZeroCut($newNum);
            $pos = strpos(strval($newNum), '.');//获取小数点位置
            if (!$pos) {
                //如果没找到
                $dist = 0;
            }else {
                $dist = strlen(strval($newNum)) - $pos - 1;
            }
        }
        $result = number_format($newNum, $dist);
        return $result;
    }
}