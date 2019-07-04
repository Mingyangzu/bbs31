<?php

/**
 * Controller的促销活动处理模块
 *
 * @author yuxinqi<yuxinqi@vip.qq.com>
 * @version 1.0
 * @link http://docs.liangjianyun.com/
 */


if(!defined('IN_DISCUZ')) {
    exit('Access Denied');
}

class ActivityAction{
    public $page;

    public function __construct() {
        global $requestPage;
        $this->page = $requestPage;
        if(!$this->page->config->aljwx && ACTION == 'miniprogram'){
            $this->page->showMessage(lang("plugin/aljhtx","activity_php_2"), '<a style="color: rgb(30, 159, 255);" target="_blank" href="http://addon.dismall.com/?@aljwx.plugin">' . lang("plugin/aljhtx","activity_php_1") . '</a>');
        }
    }



    public function ms(){
        if($this->page->get->render == 'yes'){
            $per = 20;
            $page = $this->page->get->page>0 ? $this->page->get->page :1;
            $start = ($page - 1) * $per;
            $logList = DB::fetch_all('select * from %t order by id asc limit %d, %d', array('aljhtx_activity_ms', $start, $per));
            $count = DB::result_first('select count(*) from %t order by id asc', array('aljhtx_activity_ms'));
            foreach($logList as $k => $v){
                $logList[$k]['dateline'] = dgmdate($v['dateline'], 'u');
                $logList[$k]['qrcode'] = 'source/plugin/aljhtx/static/img/qrcode/common/'.$logList[$k]['qrcode'];
            }
            T::responseJson(array(
                'code' => 0,
                'msg' => "",
                'count' => $count,
                'data' => $logList
            ));
        }
        $this->page->display();
    }

    public function ms_delete(){
        DB::delete('aljhtx_activity_ms', array('id' => $_GET['mid']));
        T::responseJson();
    }


    public function ms_add(){
        $mid = intval($_GET['mid']);
        if($mid){
            $activity = DB::fetch_first('select * from %t where id=%d', array('aljhtx_activity_ms', $mid));
        }
        if(submitcheck('formhash')){
            if($mid && $activity){
                DB::update('aljhtx_activity_ms', array(
                    'title' => $_GET['title'],
                    'start_time' => $_GET['start_time'],
                    'end_time' => $_GET['end_time'],
                ), array('id' => $mid));
            }else{
                DB::insert('aljhtx_activity_ms', array(
                    'title' => $_GET['title'],
                    'start_time' => $_GET['start_time'],
                    'end_time' => $_GET['end_time'],
                ), true);
            }
            $this->page->tips();
        }else{
            $this->page->assign('activity', $activity);
            $this->page->display();
        }
    }
}

