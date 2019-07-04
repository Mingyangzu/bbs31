<?php

/**
 *进度条类
 *
 * @author yuxinqi<yuxinqi@vip.qq.com>
 * @version 1.0
 * @link http://docs.liangjianyun.com/
 */

if(!defined('IN_DISCUZ')) {
    exit('Access Denied');
}

class ProgressBar{

    //总页数
    public $requestPage;
    //总页数
    public $maxPage;
    //进度走完后要跳转的链接
    public $goUrl;
    //进度条提示语
    public $tips;
    //进度条每次加载比例
    public $addPer = 10;
    //当前页数
    public $curPage;
    //进度条所在页面链接
    public $url;

    public function __construct($progressBar) {
        $this->requestPage = $progressBar['requestPage'];
        $this->tips = $progressBar['tips'];
        $this->url = $progressBar['url'];
        $this->goUrl = $progressBar['goUrl'];
        $this->doSendGroupMessageUrl = $progressBar['doSendGroupMessageUrl'];
        $this->curPage = $progressBar['curPage'];
        $this->maxPage = $progressBar['maxPage'];
    }

    /**
     * 加载进度条
     *
     * @return void
     */
    public function start(){
        $this->requestPage->assign('tips', $this->tips);
        $this->requestPage->assign('doSendGroupMessageUrl', $this->doSendGroupMessageUrl, true);
        $this->requestPage->assign('goUrl', $this->goUrl, true);
        $this->requestPage->assign('maxPage', $this->maxPage);
        $this->requestPage->assign('url', $this->url, true);
        $this->requestPage->assign('curPage', $this->curPage);
        $this->requestPage->assign('addPer', $this->addPer);
        $this->requestPage->display(PLUGIN_ID . ':progressbar');
    }

}