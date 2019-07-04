<?php

/**
 * 商家助手面向管理员的服务入口页
 *
 * @author yuxinqi<yuxinqi@vip.qq.com>
 * @version 1.0
 * @link http://docs.liangjianyun.com/
 */

if (!defined('IN_DISCUZ')) {
    exit('Access Denied');
}

//页面初始化类
require_once 'source/plugin/aljhtx/class/class_page.php';

//常用工具类
require_once 'source/plugin/aljhtx/class/class_aljhtx.php';
//用户类
require_once 'source/plugin/aljhtx/class/class_user.php';
//消息处理类
require_once 'source/plugin/aljhtx/class/class_message.php';
//任务处理类
require_once 'source/plugin/aljhtx/class/class_cron.php';


//插件标识符
define('APP_ID', 'aljhtx');
define('PLUGIN_ID', 'aljhtx');
//指定默认/首页CONTROLLER
define('DEFAULT_CONTROLLER', 'log');
//指定默认/首页action
define('DEFAULT_ACTION', 'plugin');
//插件入口目录
define('PLUGIN_ROOT_PATH', 'source/plugin/');
define('APP_ROOT_PATH', 'source/plugin/');
//当前插件目录
define('APP_PATH', 'source/plugin/' . APP_ID . '/');
define('PLUGIN_PATH', 'source/plugin/' . PLUGIN_ID . '/');
//插件首页链接
define('APP_URL', 'plugin.php?id=' . APP_ID);
define('PLUGIN_URL', 'plugin.php?id=' . APP_ID);

//获取当前页面对象
$requestPage = new RequestPage();

//作为入口文件名及相关脚本与模板的起始目录
define('ROOT', 'admin');
//页面CONTROLLER
define('CONTROLLER', $requestPage->get->c);
//页面action
define('ACTION', $requestPage->get->a);
//模块首页链接
define('C_URL', APP_URL . '&c=' . $requestPage->get->c);
//动作页面链接
define('A_URL', APP_URL . '&c=' . $requestPage->get->c . '&a=' . $requestPage->get->a);
//iframe页面链接
define('IFRAME_PAGE_URL', RequestPage::getIframeUrl());
//当前页面链接
define('PAGE_URL', RequestPage::getUrl());
//登录页面链接

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

//处理页面iframe
$requestPage->iframe();
//处理页面路由
$requestPage->route();
