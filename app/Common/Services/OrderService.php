<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/1/4
 * Time: 11:18
 */

namespace App\Common\Services;

use App\Models\Order;
use App\Models\OrderInfo;
use App\Models\PayLog;
use App\Models\ShoppingCart;
use Zilf\Db\Exception;
use Zilf\Facades\Log;

class OrderService
{
    protected $user;
    public function __construct($userInfo)
    {
        $this->user = $userInfo;
    }

    /**
     * 添加支付日志
     * @param $payLog
     * @return bool
     */
    public function addPayLog($payLog)
    {
        $model = new PayLog();
        $model->setAttributes($payLog);
        return $model->save();
    }
    /**
     * 添加订单
     * @param $orderData
     * @param $orderInfoData
     * @param null $carts
     * @return bool
     * @throws \Exception
     * @throws \Throwable
     */
    public function addOrder($orderData,$orderInfoData,$carts=null)
    {
        $order = new Order();
        $transaction = Order::getDb()->beginTransaction();
        try{
            $orderData['order_no'] = getOrderSn();
            $order->setAttributes($orderData);
            $isOrder = $order->save();
            if(!$isOrder){
                $transaction->rollBack();
            }
            $model = new OrderInfo();
            foreach($orderInfoData as $attributes)
            {
                $_model = clone $model;
                $attributes['order_id']   = $order->attributes['id'];
                $attributes['order_no']   = $order->attributes['order_no'];
                $goodsInfo = $this->getGoodsInfo($attributes['product_id']);
                $attributes['product_title']   = $goodsInfo['title'];
                $attributes['product_img']     = $goodsInfo['goods_image'];
                $attributes['add_time']        = time();
                $_model->setAttributes($attributes);
                $is_success = $_model->save();
                if(!$is_success){
                    $transaction->rollBack();
                }
                if($carts){
                    Log::info('我进来清除购物车!!!!!!!');
                    ShoppingCart::deleteAll(
                        'product_id = :product_id AND user_id = :user_id ',
                        [
                            ':product_id' => $attributes['product_id'], ':user_id' =>$this->user['id']
                        ]);
                }
            }
            $transaction->commit();
            return $order->attributes['id'];
        }catch (\Exception $e){
            $transaction->rollBack();
            throw $e;
        }catch (\Throwable $e){
            $transaction->rollBack();
            throw $e;
        }
        return false;
    }

    private function getGoodsInfo($goods_id)
    {
        return GoodService::getById($goods_id);
    }
}