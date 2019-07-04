<?php

/**
 *��Ϣ������
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
     * ������Ϣ
     *
     * @param string $type ��Ϣ���
     * @param array $friendUser ��Ϣ���Ͷ���
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
     * ����ϵͳ��Ϣ
     *
     * @param int $uid �û�UID
     * @param string $content ��Ϣ����
     * @return void
     */
    public static function sendNotification($uid, $content){
        notification_add($uid, 'system', $content);
    }

    /**
     * �����ʼ�
     *
     * @param string $email �����ַ
     * @param string $content �ʼ�����
     * @return void
     */
    public static function sendEmail($email = '', $content){
        require_once libfile('function/mail');
        sendmail_cron($email, $content, $content);
    }


}