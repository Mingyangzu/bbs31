<?php

/**
 *在线聊天
 *
 * @author yuxinqi<yuxinqi@vip.qq.com>
 * @version 1.0
 * @link http://docs.liangjianyun.com/
 */

if(!defined('IN_DISCUZ')) {
    exit('Access Denied');
}

class Aljol{

    public $loginUser;
    public $friendUser;
    public $content;

    public function __construct($param) {
        $this->loginUser = $param['loginUser'];
        $this->friendUser = $param['friendUser'];
        if($param['content']){
            $this->content = $param['content'];
        }
    }

    /**
     * 发送消息
     *
     * @param $content string 消息内容
     * @return void
     */
    public function send($content = '') {
        if($content){
            $this->content = $content;
        }
        $talkid = DB::insert('aljol_talk',array(
            'uid' => $this->loginUser->uid,
            'username' => $this->loginUser->username,
            'friendid' => $this->friendUser->uid,
            'talk' => $this->content,
            'datetime' => TIMESTAMP,
            'talkstate' => 1,
        ),true);
        notification_add($this->friendUser->uid, 'system', lang("plugin/aljhtx","class_aljol_php_1").$this->loginUser->username.'」给您发了一条消息，点击查看!',array('from_idtype'=>'aljol', 'from_id' => $this->loginUser->uid));
        $this->news($this->friendUser->uid,$this->content);
    }

    /**
     * 插入消息盒子
     *
     * @param $friendid string 用户UID
     * @param $chat string 消息内容
     * @return void
     */
    public function news($friendid,$chat) {
        global $_G;
        $talkrecord = DB::fetch_first('select * from %t where uid=%d and friendid=%d',array('aljol_news',$_G['uid'],$friendid));
        $friendrecord = DB::fetch_first('select * from %t where friendid=%d and uid=%d',array('aljol_news',$_G['uid'],$friendid));
        if(empty($talkrecord)) {
            DB::insert('aljol_news',array(
                'uid'=>$_G['uid'],
                'username'=>$_G['username'],
                'friendid'=>$friendid,
                'datetime'=>TIMESTAMP,
                'lastnews'=>$chat,
            ));
        }else {
            DB::update('aljol_news',array('datetime'=>TIMESTAMP,'lastnews'=>$chat),array('id'=>$talkrecord['id']));
        }
        if(empty($friendrecord)) {
            $firendname = DB::fetch_first('select * from %t where uid=%d',array('common_member',$friendid));
            DB::insert('aljol_news',array(
                'uid'=>$friendid,
                'username'=>$firendname['username'],
                'friendid'=>$_G['uid'],
                'datetime'=>TIMESTAMP,
                'lastnews'=>$chat,
            ));
        }else {
            DB::update('aljol_news',array('datetime'=>TIMESTAMP,'lastnews'=>$chat),array('id'=>$friendrecord['id']));
        }
    }
}
