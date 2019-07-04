<?php

/**
 *��������
 *
 * @author yuxinqi<yuxinqi@vip.qq.com>
 * @version 1.0
 * @link http://docs.liangjianyun.com/
 */

if(!defined('IN_DISCUZ')) {
    exit('Access Denied');
}

class ProgressBar{

    //��ҳ��
    public $requestPage;
    //��ҳ��
    public $maxPage;
    //���������Ҫ��ת������
    public $goUrl;
    //��������ʾ��
    public $tips;
    //������ÿ�μ��ر���
    public $addPer = 10;
    //��ǰҳ��
    public $curPage;
    //����������ҳ������
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
     * ���ؽ�����
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