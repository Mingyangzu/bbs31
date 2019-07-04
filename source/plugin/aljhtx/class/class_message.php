<?php

/**
 *消息发送类
 *
 * @author yuxinqi<yuxinqi@vip.qq.com>
 * @version 1.0
 * @link http://docs.liangjianyun.com?/
 */

if(!defined('IN_DISCUZ')) {
    exit('Access Denied');
}

class Message{

    public $loginUser;
    public $friendUser;
    public $content;

    public function __construct($loginUser, $content, $friendUser = array()) {
        $this->loginUser = $loginUser;
        $this->friendUser = $friendUser;
        $this->content = $content;
    }

    /**
     * 发送消息
     *
     * @param string $type 消息类别
     * @param array $friendUser 消息发送对象
     * @return void
     */
    public function send($type = 'system', $friendUser = array()){
        if($friendUser){
            if (is_array($friendUser)) {
                $friendUser = (object)$friendUser;
            }
            $this->friendUser = $friendUser;
        }

        if($type == 'system'){
            self::sendNotification($this->friendUser->uid, $this->content);
        }else if($type == 'email'){
            self::sendEmail($this->friendUser->email, $this->content);
        }else if($type == 'aljdx'){
            $cron = T::getObject('cron', array(
                'pid' => $this->friendUser->mobile,
                'touid' => $this->friendUser->uid,
                'type' => 'aljdx',
                'content' => $this->content
            ));
            $cron->push();
        }else if($type == 'aljol'){
            $aljol = T::getObject('Aljol', array(
                'loginUser' => $this->loginUser,
                'friendUser' => $this->friendUser
            ));
            $aljol->send($this->content);
        }
    }

    /**
     * 发送系统消息
     *
     * @param int $uid 用户UID
     * @param string $content 消息内容
     * @return void
     */
    public static function sendNotification($uid, $content){
        notification_add($uid, 'system', $content);
    }

    /**
     * 发送邮件
     *
     * @param string $email 邮箱地址
     * @param string $content 邮件内容
     * @return void
     */
    public static function sendEmail($email = '', $content){
        require_once libfile('function/mail');
        sendmail_cron($email, $content, $content);
    }


}