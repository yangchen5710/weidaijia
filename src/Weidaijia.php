<?php

namespace Ycstar\Weidaijia;

use Ycstar\Weidaijia\Exceptions\WeidaijiaException;

class Weidaijia extends Base
{
    protected $methods = [
        'lockStock', //下单
        'unlockStock', //取消订单
        'queryOrder', //订单详情
        'nearby', //周边空闲司机
        'yugujia', //预估价
        'queryPos', //订单司机实时位置
        'cityOpen', //城市是否开通
        'createCoupon', //创建券
        'queryCoupon', //查询单个券
        'cancelCoupon', //取消单个券
    ];

    public function __call($method, array $arguments)
    {
        if (!in_array($method, $this->methods)) {
            throw new WeidaijiaException('非法的方法名');
        }
        return $this->request($method, ...$arguments);
    }
}