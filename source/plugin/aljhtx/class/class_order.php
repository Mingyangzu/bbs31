<?php

/**
 * ��������
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
     * ͳ�ƶ������ status 1.������  2.�������Ѹ��� 3.�ѷ��� 4.��ɹ�� 5.��ɹ��  6.���˿�  7.���ڶ���
     *
     * @return void
     */
    public static function countOrderMoney() {
        return DB::result_first("select sum(price) from %t where status >= 2 and status < 6 and pid = 0 ", array('aljbd_goods_order'));
    }
}
