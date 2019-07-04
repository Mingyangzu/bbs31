<?php

/**
 * 订单管理
 *
 * @author yuxinqi<yuxinqi@vip.qq.com>
 * @version 1.0
 * @link http://docs.liangjianyun.com/
 */

if(!defined('IN_DISCUZ')) {
    exit('Access Denied');
}

class Order{

    public function __construct() {

    }

    /**
     * 统计订单金额 status 1.待付款  2.待发货已付款 3.已发货 4.待晒单 5.已晒单  6.已退款  7.过期订单
     *
     * @return void
     */
    public static function countOrderMoney() {
        return DB::result_first("select sum(price) from %t where status >= 2 and status < 6 and pid = 0 ", array('aljbd_goods_order'));
    }
}
