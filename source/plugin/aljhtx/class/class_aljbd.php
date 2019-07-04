<?php

/**
 * 品牌商家处理类
 *
 * @author yuxinqi<yuxinqi@vip.qq.com>
 * @version 1.0
 * @link http://docs.liangjianyun.com/
 */

if(!defined('IN_DISCUZ')) {
    exit('Access Denied');
}

class Aljbd{

    public function __construct() {

    }

    /**
     * 获取商家数量
     *
     * @return void
     */
    public static function count() {
        return DB::result_first('select count(*) from %t where status = 1 and rubbish=0', array('aljbd'));
    }
}
