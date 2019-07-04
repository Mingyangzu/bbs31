<?php

/**
 * QQ登录/绑定
 *
 * @author yuxinqi<yuxinqi@vip.qq.com>
 * @version 1.0
 * @link http://docs.liangjianyun.com/
 */

if(!defined('IN_DISCUZ')) {
    exit('Access Denied');
}

class Aljqq{

    public function __construct() {

    }

    /**
     * 获取QQ用户数
     *
     * @return void
     */
    public static function count() {
        return DB::result_first('select count(*) from %t where uid>0', array('aljqq_user'));
    }

    /**
     * 删除绑定记录
     *
     * @param int $uid 用户UID
     * @return void
     */
    public static function delete($uid) {
        DB::query('delete from %t where uid=%d', array('aljqq_user', $uid));
    }
}
