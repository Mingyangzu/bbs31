<?php

/**
 * 微信登录/绑定
 *
 * @author yuxinqi<yuxinqi@vip.qq.com>
 * @version 1.0
 * @link http://docs.liangjianyun.com/
 */

if(!defined('IN_DISCUZ')) {
    exit('Access Denied');
}

class Aljwsq{

    public function __construct() {

    }

    /**
     * 获取微信用户数
     *
     * @return void
     */
    public static function count() {
        return DB::result_first('select count(*) from %t where uid>0', array('aljwsq_mapp_user'));
    }

    public static function countLogNum($search = '') {
        $where = ' where 1 ';
        $conn = array('aljwsq_mapp_template_log');
        if($search){
            $str = '%'.$search.'%';
            $where .= ' and touser like %s';
            $conn[] = $str;
        }
        return DB::result_first('select count(*) from %t '.$where, $conn);
    }


    public static function getLogList($search = '', $curPage=1, $limit = 10) {
        $where = ' where 1 ';
        $conn = array('aljwsq_mapp_template_log', 'aljwsq_mapp_user');
        if($search){
            $str = '%'.$search.'%';
            $where .= ' and a.touser like %s';
            $conn[] = $str;
        }
        $where .= ' order by a.dateline desc limit %d, %d';
        $conn[] = ($curPage-1) * $limit;
        $conn[] = $limit;
        return DB::fetch_all('select a.*, b.openid, b.nickname, b.headimgurl, b.username from %t a left join %t b on a.touser=b.openid'.$where, $conn);
    }

    public static function countBindNoFollowUserNum($search = '') {
        $where = ' where 1 and uid>0 and subscribe=0';
        $conn = array('aljwsq_mapp_user');
        if($search){
            $str = '%'.$search.'%';
            $where .= ' and username like %s';
            $conn[] = $str;
        }
        return DB::result_first('select count(*) from %t '.$where, $conn);
    }


    public static function getBindNoFollowUserList($search = '', $curPage=1, $limit = 10) {
        $where = ' where 1 and uid>0 and subscribe=0';
        $conn = array('aljwsq_mapp_user');
        if($search){
            $str = '%'.$search.'%';
            $where .= ' and username like %s';
            $conn[] = $str;
        }
        $where .= ' order by bindtime desc limit %d, %d';
        $conn[] = ($curPage-1) * $limit;
        $conn[] = $limit;
        return DB::fetch_all('select * from %t'.$where, $conn);
    }

    public static function countBindFollowUserNum($search = '') {
        $where = ' where 1 and uid>0 and subscribe=1';
        $conn = array('aljwsq_mapp_user');
        if($search){
            $str = '%'.$search.'%';
            $where .= ' and nickname like %s';
            $conn[] = $str;
        }
        return DB::result_first('select count(*) from %t '.$where, $conn);
    }


    public static function getBindFollowUserList($search = '', $curPage=1, $limit = 10) {
        $where = ' where 1 and uid>0 and subscribe=1';
        $conn = array('aljwsq_mapp_user');
        if($search){
            $str = '%'.$search.'%';
            $where .= ' and nickname like %s';
            $conn[] = $str;
        }
        $where .= ' order by bindtime desc limit %d, %d';
        $conn[] = ($curPage-1) * $limit;
        $conn[] = $limit;
        return DB::fetch_all('select * from %t'.$where, $conn);
    }

    public static function countBindUserNum($search = '') {
        $where = ' where 1 and uid>0';
        $conn = array('aljwsq_mapp_user');
        if($search){
            $str = '%'.$search.'%';
            $where .= ' and nickname like %s';
            $conn[] = $str;
        }
        return DB::result_first('select count(*) from %t '.$where, $conn);
    }


    public static function getBindUserList($search = '', $curPage=1, $limit = 10) {
        $where = ' where 1 and uid>0';
        $conn = array('aljwsq_mapp_user');
        if($search){
            $str = '%'.$search.'%';
            $where .= ' and nickname like %s';
            $conn[] = $str;
        }
        $where .= ' order by bindtime desc limit %d, %d';
        $conn[] = ($curPage-1) * $limit;
        $conn[] = $limit;
        return DB::fetch_all('select * from %t'.$where, $conn);
    }


    public static function countFollowUserNum($search = '') {
        $where = ' where 1 and subscribe=1';
        $conn = array('aljwsq_mapp_user');
        if($search){
            $str = '%'.$search.'%';
            $where .= ' and nickname like %s';
            $conn[] = $str;
        }
        return DB::result_first('select count(*) from %t '.$where, $conn);
    }


    public static function getFollowUserList($search = '', $curPage=1, $limit = 10) {
        $where = ' where 1 and subscribe=1';
        $conn = array('aljwsq_mapp_user');
        if($search){
            $str = '%'.$search.'%';
            $where .= ' and nickname like %s';
            $conn[] = $str;
        }
        $where .= ' order by subscribe_time desc limit %d, %d';
        $conn[] = ($curPage-1) * $limit;
        $conn[] = $limit;
        return DB::fetch_all('select * from %t'.$where, $conn);
    }

    /**
     * 删除绑定记录
     *
     * @param int $uid 用户UID
     * @return void
     */
    public static function delete($uid) {
        DB::query('delete from %t where uid=%d', array('aljwsq_mapp_user', $uid));
    }
}
