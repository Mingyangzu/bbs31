<?php

/**
 * �û���
 *
 * @author yuxinqi<yuxinqi@vip.qq.com>
 * @version 1.0
 * @link http://docs.liangjianyun.com/
 */

if(!defined('IN_DISCUZ')) {
    exit('Access Denied');
}

class User{

    public $user;
    public $uid;
    public $username;
    public $groupid;
    public $email;
    public $mobile;
    public $avatar;

    public function __construct($uid = 0, $username = '') {
        if($uid){
            $this->user = (object)$this->getuserbyuid($uid);
        }else{
            $this->user = (object)$this->getuserbyusername($username);
        }
        $userProfile = (object)self::getUserProfileByUid($this->user->uid);
        $this->uid  = $this->user->uid;
        $this->groupid  = $this->user->groupid;
        $this->email  = $this->user->email;
        $this->mobile  = $userProfile->mobile;
        $this->username = $this->user->username;
        $this->avatar = $this -> avatar($this->uid, true);
    }

    public static function getUser($uid = 0, $username = ''){
        return new User($uid, $username);
    }

    public static function getUserProfileByUid($uid){
        return DB::fetch_first('select * from %t where uid = %d', array('common_member_profile', $uid));
    }

    /**
     * ͳ�ƻ�Ա����
     *
     * @param int $groupid �û���ID
     * @return int
     */
    public static function countUserNumByGroupid($groupid = 0) {
        if($groupid){
            $sql = !empty($groupid) ? " WHERE groupid = '".intval($groupid)."'" : '';
        }
        return DB::result_first("SELECT count(*) FROM ".DB::table('common_member'). $sql);
    }

    /**
     * ͳ���ֻ��Ű�����
     *
     * @return void
     */
    public static function countUserNumByMobile() {
        return DB::result_first('SELECT count(*) FROM %t m LEFT JOIN %t mp ON mp.uid=m.uid WHERE mp.mobile > 0', 
        array('common_member', 'common_member_profile'));
    }

    /**
     * ��ȡ��Ա�б�
     *
     * @param int $groupid �û���ID
     * @param string $orderby �����ֶ�
     * @param string $sort  �Ƿ�����ASC|DESC
     * @param int $curPage ҳ��
     * @param int $limit ����
     * @param object $requestPage ��ǰ����ҳ�����
     * @return array
     */
    public static function getUserListByGroupid($groupid = 0, $orderby = '', $sort = '', $curPage = 1, $limit =  0, $requestPage) {
        $orderby = in_array($orderby, array('uid','credits','regdate', 'gender','username','posts','lastvisit'), true) ? $orderby : 'uid';
        $sql = ' where 1 ';
        $sql .= $groupid>0 ? " and m.groupid = '".intval($groupid)."'" : '';
        $sql .= !empty($requestPage->get->search) ? " and m.username LIKE '%".addslashes(stripsearchkey($requestPage->get->search))."%' " : '';
        $memberlist = array();
        $columns = '';
        $leftJoin= " m
			LEFT JOIN ".DB::table('common_member_profile')." mp ON mp.uid=m.uid
			LEFT JOIN ".DB::table('common_member_status')." ms ON ms.uid=m.uid
			LEFT JOIN ".DB::table('common_member_count')." mc ON mc.uid=m.uid
			";
        if($requestPage->config->aljwsq){
            $columns .= ' ,wx.openid wxid , wx.nickname';
            $leftJoin .= " 
			LEFT JOIN ".DB::table('aljwsq_mapp_user')." wx ON wx.uid>0 and wx.uid=m.uid
			";
        }
        if($requestPage->config->aljqq){
            $columns .= ' ,qq.openid qqid';
            $leftJoin .= " 
			LEFT JOIN ".DB::table('aljqq_user')." qq ON qq.uid=m.uid
			";
        }
        if($requestPage->config->aljqb){
            $columns .= ' ,qb.rechargenumber, qb.balance';
            $leftJoin .= " 
			LEFT JOIN ".DB::table('aljqb_wallet')." qb ON qb.uid=m.uid
			";
        }
        $query = DB::query("SELECT m.uid, m.groupid, m.username, mp.gender, mp.mobile, m.email, m.regdate, ms.lastvisit, mc.posts, m.credits $columns
			FROM ".DB::table('common_member').$leftJoin." $sql ORDER BY ".DB::order($orderby, $sort).DB::limit(($curPage-1) * $limit, $limit));

        while($member = DB::fetch($query)) {
            $member['usernameenc'] = rawurlencode($member['username']);
            $member['regdate'] = dgmdate($member['regdate']);
            $member['lastvisit'] = dgmdate($member['lastvisit']);
            $memberlist[$member['uid']] = $member;
        }
        return $memberlist;
    }

    /**
     * ��ȡ��Ա�б�
     *
     * @param string $username ��Ա��
     * @param string $orderby �����ֶ�
     * @param string $sort  �Ƿ�����ASC|DESC
     * @param int $curPage ��ʼ��
     * @param int $limit ����
     * @return array
     */
    public static function getUserList($username, $orderby = '', $sort = '', $curPage = 0, $limit =  0) {
        $orderby = in_array($orderby, array('uid','credits','regdate', 'gender','username','posts','lastvisit'), true) ? $orderby : 'uid';

        $sql = !empty($username) ? " WHERE username LIKE '".addslashes(stripsearchkey($username))."%'" : '';

        $memberlist = array();
        $query = DB::query("SELECT m.uid, m.groupid, m.username, mp.gender, mp.mobile, m.email, m.regdate, ms.lastvisit, mc.posts, m.credits
			FROM ".DB::table('common_member')." m
			LEFT JOIN ".DB::table('common_member_profile')." mp ON mp.uid=m.uid
			LEFT JOIN ".DB::table('common_member_status')." ms ON ms.uid=m.uid
			LEFT JOIN ".DB::table('common_member_count')." mc ON mc.uid=m.uid
			$sql ORDER BY ".DB::order($orderby, $sort).DB::limit(($curPage-1) * $limit, $limit));
        while($member = DB::fetch($query)) {
            $member['username'] = strip_tags($member['username']);
            $member['usernameenc'] = rawurlencode($member['username']);
            $member['regdate'] = dgmdate($member['regdate']);
            $member['lastvisit'] = dgmdate($member['lastvisit']);
            $memberlist[$member['uid']] = $member;
        }
        return $memberlist;
    }

    /**
     * ����û�����
     *
     * @param int $groupid �û���ID
     * @return array
     */
    public static function getUserGroupTtitle($groupid) {
        global $_G;
        if(!$_G['cache']['usergroups'][$groupid]['grouptitle']){
            loadcache('usergroups');
        }
        return $_G['cache']['usergroups'][$groupid]['grouptitle'];
    }

    /**
     * ����û����б�
     *
     * @return array
     */
    public static function getUserGroupList() {
        global $_G;
        if(!$_G['cache']['usergroups']){
            loadcache('usergroups');
        }
        return $_G['cache']['usergroups'];
    }

    /**
     * ��ȡ�û���Ϣ
     *
     * @param int $uid �û�ID
     * @param int $fetch_archive  0��ֻ��ѯ��ǰ��1����ѯ��ǰ��ʹ浵��
     * @return array
     */
    private function getUserByUid($uid, $fetch_archive = 0) {
        $users = array();
        if(empty($users[$uid])) {
            $users[$uid] = C::t('common_member'.($fetch_archive === 2 ? '_archive' : ''))->fetch($uid);
            if($fetch_archive === 1 && empty($users[$uid])) {
                $users[$uid] = C::t('common_member_archive')->fetch($uid);
            }
        }
        if(!isset($users[$uid]['self']) && $uid == getglobal('uid') && getglobal('uid')) {
            $users[$uid]['self'] = 1;
        }
        return $users[$uid];
    }

    private function getUserByUsername($username, $fetch_archive = 0){
        $user = array();
        if($username) {
            $user = DB::fetch_first('SELECT * FROM %t WHERE username=%s', array('common_member', $username));
            if($fetch_archive && empty($user)) {
                $user = C::t('common_member_archive')->fetch_by_username($username, 0);
            }
        }
        return $user;
    }

    /**
     * ��ȡ�û�ͷ��
     *
     * @param int $uid �û�ID
     * @param string $size  ͷ���С
     * @param bool $returnsrc �Ƿ�ֻ����ͷ��·��
     * @param bool $real �Ƿ񷵻�ͷ��ԭͼ
     * @param bool $static ���ض�̬/��̬ͷ������
     * @param string $ucenterurl ��ָ��uccenter����
     * @return string
     */
    public function avatar($uid, $size = 'middle', $returnsrc = FALSE, $real = FALSE, $static = FALSE, $ucenterurl = '') {
        global $_G;
        if($_G['setting']['plugins']['func'][HOOKTYPE]['avatar']) {
            $_G['hookavatar'] = '';
            $param = func_get_args();
            hookscript('avatar', 'global', 'funcs', array('param' => $param), 'avatar');
            if($_G['hookavatar']) {
                return $_G['hookavatar'];
            }
        }
        static $staticavatar;
        if($staticavatar === null) {
            $staticavatar = $_G['setting']['avatarmethod'];
        }

        $ucenterurl = empty($ucenterurl) ? $_G['setting']['ucenterurl'] : $ucenterurl;
        $size = in_array($size, array('big', 'middle', 'small')) ? $size : 'middle';
        $uid = abs(intval($uid));
        if(!$staticavatar && !$static) {
            return $returnsrc ? $ucenterurl.'/avatar.php?uid='.$uid.'&size='.$size.($real ? '&type=real' : '') : '<img src="'.$ucenterurl.'/avatar.php?uid='.$uid.'&size='.$size.($real ? '&type=real' : '').'" />';
        } else {
            $uid = sprintf("%09d", $uid);
            $dir1 = substr($uid, 0, 3);
            $dir2 = substr($uid, 3, 2);
            $dir3 = substr($uid, 5, 2);
            $file = $ucenterurl.'/data/avatar/'.$dir1.'/'.$dir2.'/'.$dir3.'/'.substr($uid, -2).($real ? '_real' : '').'_avatar_'.$size.'.jpg';
            return $returnsrc ? $file : '<img src="'.$file.'" onerror="this.onerror=null;this.src=\''.$ucenterurl.'/images/noavatar_'.$size.'.gif\'" />';
        }
    }
}
