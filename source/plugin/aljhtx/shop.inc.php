<?php

/**
 * 商家助手面向终端个人用户的服务入口页
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
//入口文件名
define('ROOT', 'shop');
//指定默认/首页CONTROLLER
define('DEFAULT_CONTROLLER', 'user');
//指定默认/首页action
define('DEFAULT_ACTION', 'user');
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


//处理页面iframe
$requestPage->iframe();
//处理页面路由
$requestPage->route();