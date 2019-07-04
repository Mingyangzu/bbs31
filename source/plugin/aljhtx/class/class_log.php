<?php

/**
 * ��Ϣ��־
 *
 * @author yuxinqi<yuxinqi@vip.qq.com>
 * @version 1.0
 * @link http://docs.liangjianyun.com/
 */

if(!defined('IN_DISCUZ')) {
    exit('Access Denied');
}

class Log{

    public function __construct() {

    }

    /**
     * ͳ��վ����Ϣ�ļ�¼����
     *
     * @param $search string ������
     * @param $type string ��Ϣ����
     * @return void
     */
    public static function countNotificationList($type = '', $search = '') {
        $where = ' where 1';
        $conn = array('home_notification');
        if($type){
            $where .= ' and type = %s';
            $conn[] = $type;
        }
        if($search){
            $str = '%'.$search.'%';
            $where .= ' and note like %s';
            $conn[] = $str;
        }
        return DB::result_first('select count(*) from %t '.$where, $conn);
    }

    /**
     * ��ȡ��Ϣ��־�б�
     *
     * @param $type string ��Ϣ����
     * @param $search string ������
     * @param $curPage int ��ǰҳ
     * @param $limit int ȡ����������
     * @return void
     */
    public static function getNotificationList($type = '', $search = '', $curPage=1, $limit = 10) {
        $where = ' where 1';
        $conn = array('home_notification', 'common_member');
        if($type){
            $where .= ' and type = %s';
            $conn[] = $type;
        }
        if($search){
            $str = '%'.$search.'%';
            $where .= ' and note like %s';
            $conn[] = $str;
        }
        $where .= ' order by id desc limit %d, %d';
        $conn[] = ($curPage-1) * $limit;
        $conn[] = $limit;
        return DB::fetch_all('select a.*, b.username from %t a left join %t b on a.uid=b.uid'.$where, $conn);
    }

    /**
     * ͳ�ƶ�����־�ļ�¼����
     *
     * @param $search string ������
     * @return void
     */
    public static function countAljdx($search = '') {
        $where = ' where 1';
        $conn = array('aljdx_log');
        if($search){
            $str = '%'.$search.'%';
            $where .= ' and b.username like %s';
            $conn[] = $str;
        }
        return DB::result_first('select count(*) from %t '.$where, $conn);
    }

    /**
     * ��ȡ���ŷ�����־
     *
     * @param $search string ������
     * @param $curPage int ��ǰҳ
     * @param $limit int ȡ����������
     * @return void
     */
    public static function aljdx($search = '', $curPage=1, $limit = 10) {
        $where = ' where 1';
        $conn = array('aljdx_log', 'common_member');
        if($search){
            $str = '%'.$search.'%';
            $where .= ' and b.username like %s';
            $conn[] = $str;
        }
        $where .= ' order by a.dxlog_id desc limit %d, %d';
        $conn[] = ($curPage-1) * $limit;
        $conn[] = $limit;
        return DB::fetch_all('select a.*, b.uid, b.username from %t a left join %t b on a.dx_uid=b.uid'.$where, $conn);
    }

    /**
     * ͳ����������ļ�¼����
     *
     * @param $search string ������
     * @return void
     */
    public static function countAljol($search = '') {
        $where = ' where 1';
        $conn = array('aljol_talk');

        if($search){
            $str = '%'.$search.'%';
            $where .= ' and b.username like %s';
            $conn[] = $str;
        }
        return DB::result_first('select count(*) from %t '.$where, $conn);
    }

    /**
     * ��ȡ���ŷ�����־
     *
     * @param $search string ������
     * @param $curPage int ��ǰҳ
     * @param $limit int ȡ����������
     * @return void
     */
    public static function aljol($search = '', $curPage=1, $limit = 10) {
        $where = ' where 1';
        $conn = array('aljol_talk', 'common_member');
        if($search){
            $str = '%'.$search.'%';
            $where .= ' and b.username like %s';
            $conn[] = $str;
        }
        $where .= ' order by a.id desc limit %d, %d';
        $conn[] = ($curPage-1) * $limit;
        $conn[] = $limit;
        return DB::fetch_all('select a.*, b.username friendusername from %t a left join %t b on a.friendid=b.uid'.$where, $conn);
    }

}
