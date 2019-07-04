<?php

/**
 * 系统消息
 *
 * @author yuxinqi<yuxinqi@vip.qq.com>
 * @version 1.0
 * @link http://docs.liangjianyun.com/
 */

if(!defined('IN_DISCUZ')) {
    exit('Access Denied');
}

class Notification{

    public function __construct() {

    }

    public static function count($search = '', $type = '') {
        $where = ' where 1 ';
        $conn = array('aljhtx_notification');
        if($search){
            $str = '%'.$search.'%';
            $where .= ' and content like %s';
            $conn[] = $str;
        }
        if($type){
            $where .= ' and type = %s';
            $conn[] = $type;
        }
        return DB::result_first('select count(*) from %t '.$where, $conn);
    }


    public static function getList($search = '', $type = '', $curPage=1, $limit = 10) {
        $where = ' where 1 ';
        $conn = array('aljhtx_notification');
        if($search){
            $str = '%'.$search.'%';
            $where .= ' and content like %s';
            $conn[] = $str;
        }
        if($type){
            $where .= ' and type = %s';
            $conn[] = $type;
        }
        $where .= ' order by dateline desc limit %d, %d';
        $conn[] = ($curPage-1) * $limit;
        $conn[] = $limit;
        return DB::fetch_all('select * from %t '.$where, $conn);
    }
}
