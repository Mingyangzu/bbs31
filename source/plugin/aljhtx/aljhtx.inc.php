<?php

/**
 * �̼������������Ա�ķ������ҳ
 *
 * @author yuxinqi<yuxinqi@vip.qq.com>
 * @version 1.0
 * @link http://docs.liangjianyun.com/
 */

if (!defined('IN_DISCUZ')) {
    exit('Access Denied');
}

//ҳ���ʼ����
require_once 'source/plugin/aljhtx/class/class_page.php';

//���ù�����
require_once 'source/plugin/aljhtx/class/class_aljhtx.php';
//�û���
require_once 'source/plugin/aljhtx/class/class_user.php';
//��Ϣ������
require_once 'source/plugin/aljhtx/class/class_message.php';
//��������
require_once 'source/plugin/aljhtx/class/class_cron.php';


//�����ʶ��
define('APP_ID', 'aljhtx');
define('PLUGIN_ID', 'aljhtx');
//ָ��Ĭ��/��ҳCONTROLLER
define('DEFAULT_CONTROLLER', 'log');
//ָ��Ĭ��/��ҳaction
define('DEFAULT_ACTION', 'plugin');
//������Ŀ¼
define('PLUGIN_ROOT_PATH', 'source/plugin/');
define('APP_ROOT_PATH', 'source/plugin/');
//��ǰ���Ŀ¼
define('APP_PATH', 'source/plugin/' . APP_ID . '/');
define('PLUGIN_PATH', 'source/plugin/' . PLUGIN_ID . '/');
//�����ҳ����
define('APP_URL', 'plugin.php?id=' . APP_ID);
define('PLUGIN_URL', 'plugin.php?id=' . APP_ID);

//��ȡ��ǰҳ�����
$requestPage = new RequestPage();

//��Ϊ����ļ�������ؽű���ģ�����ʼĿ¼
define('ROOT', 'admin');
//ҳ��CONTROLLER
define('CONTROLLER', $requestPage->get->c);
//ҳ��action
define('ACTION', $requestPage->get->a);
//ģ����ҳ����
define('C_URL', APP_URL . '&c=' . $requestPage->get->c);
//����ҳ������
define('A_URL', APP_URL . '&c=' . $requestPage->get->c . '&a=' . $requestPage->get->a);
//iframeҳ������
define('IFRAME_PAGE_URL', RequestPage::getIframeUrl());
//��ǰҳ������
define('PAGE_URL', RequestPage::getUrl());
//��¼ҳ������

define('LOGIN_URL', 'member.php?mod=logging&action=login&referer=' . urlencode(PAGE_URL));


if ($requestPage->get->c == 'cron') {
    Cron::start();
    echo 1;
    exit;
}
if($requestPage->get->c == 'aljbd' && $requestPage->get->a == 'order' && $requestPage->get->type && $_G[mobile]){
    if($requestPage->get->type == 5){
        if($requestPage->get->twok == '7'){
            $dheaderurl = 'plugin.php?id=aljbd&act=consumelist&i=1';
        }else if($requestPage->get->twok == '5'){
            $dheaderurl = 'plugin.php?id=aljbd&act=noticelist&i=1';
        }else if($requestPage->get->twok == '4'){
            $dheaderurl = 'plugin.php?id=aljbd&act=albumlist&i=1';
        }else if($requestPage->get->twok == '10'){
            $dheaderurl = 'plugin.php?id=aljsp&act=videolist&i=1';
        }else{
            $dheaderurl = 'plugin.php?id=aljbd&act=member&i=1';
        }
    }else if($requestPage->get->type == 3){
        $dheaderurl = 'plugin.php?id=aljbd&act=goodslist&i=1';
    }
    if($dheaderurl){
        dheader("location: ".$dheaderurl);
        exit;
    }
}
if(CONTROLLER != 'qrcode' && ACTION != 'common_link' && !$_GET['qid']){

    if (empty($requestPage->global->uid)) {
        dheader("location:" . LOGIN_URL);
        exit;
    } else if (!in_array($requestPage->loginUser->groupid, $requestPage->config->{APP_ID}->gids)) {
        $stips = lang("plugin/aljhtx","aljhtx_inc_php_1");
        $requestPage->showmessage($stips);
        exit;
    }
}

//����ҳ��iframe
$requestPage->iframe();
//����ҳ��·��
$requestPage->route();
