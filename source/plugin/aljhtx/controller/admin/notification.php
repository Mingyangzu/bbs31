<?php

/**
 * Controller的微信处理模块
 *
 * @author yuxinqi<yuxinqi@vip.qq.com>
 * @version 1.0
 * @link http://docs.liangjianyun.com/
 */


if(!defined('IN_DISCUZ')) {
    exit('Access Denied');
}

class NotificationAction{
    public $page;

    public function __construct() {
        global $requestPage;
        $this->page = $requestPage;
    }

    public function delete(){
        DB::delete('aljhtx_notification', array('id' => $this->page->get->nid));
        T::responseJson();
    }
    public function getList(){
        T::getObject('Notification');
        $count = Notification::count($this->page->get->search, $this->page->get->type);
        $logList = Notification::getList($this->page->get->search, $this->page->get->type, $this->page->get->page>0 ? $this->page->get->page :1, 20);
        if($this->page->get->render == 'yes'){
            foreach($logList as $k => $v){
                $bindTemplateConfig = Page::loadConfig('bind_template');
                $logList[$k]['content'] = str_replace("\n", '<br>', $logList[$k]['content']);
                $logList[$k]['dateline'] = dgmdate($v['dateline'], 'u');
                $logList[$k]['type'] = $bindTemplateConfig[$v['type']]['name'];
            }
            T::responseJson(array(
                'code' => 0,
                'msg' => "",
                'count' => $count,
                'data' => $logList
            ));
        }
        $this->page->assign('dourl', PAGE_URL); //当前请求页面URL
        $this->page->assign('bindTemplateList', Page::loadConfig('bind_template')); //当前请求页面URL
        $this->page->assign('logList', $logList, true);
        $this->page->assign('type', $this->page->get->type);
        $this->page->display();
    }
}

