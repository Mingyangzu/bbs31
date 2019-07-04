<?php

/**
 * QQ��¼/��
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
     * ��ȡQQ�û���
     *
     * @return void
     */
    public static function count() {
        return DB::result_first('select count(*) from %t where uid>0', array('aljqq_user'));
    }

    /**
     * ɾ���󶨼�¼
     *
     * @param int $uid �û�UID
     * @return void
     */
    public static function delete($uid) {
        DB::query('delete from %t where uid=%d', array('aljqq_user', $uid));
    }
}
