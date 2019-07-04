<?php

/**
 * �̼����������ն˸����û��ķ������ҳ
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
//����ļ���
define('ROOT', 'shop');
//ָ��Ĭ��/��ҳCONTROLLER
define('DEFAULT_CONTROLLER', 'user');
//ָ��Ĭ��/��ҳaction
define('DEFAULT_ACTION', 'user');
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


//����ҳ��iframe
$requestPage->iframe();
//����ҳ��·��
$requestPage->route();