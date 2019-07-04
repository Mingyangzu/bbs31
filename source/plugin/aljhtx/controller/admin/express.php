<?php

/**
 * Controller的物流处理模块
 *
 * @author yuxinqi<yuxinqi@vip.qq.com>
 * @version 1.0
 * @link http://docs.liangjianyun.com/
 */


if(!defined('IN_DISCUZ')) {
    exit('Access Denied');
}

class ExpressAction{
    public $page;

    public function __construct() {
        global $requestPage;
        $this->page = $requestPage;
    }

    public function express_company(){
        $express_company = include('source/plugin/aljhtx/config/express_company.php');
        $express_company_list = DB::result_first('select svalue from %t where skey=%s', array('aljhtx_system_setting', 'express_company'));
        $express_company_list = unserialize($express_company_list);
        //debug($express_company_list);
        $svalue = serialize($_GET['express_company']);
        if(submitcheck('formhash')){
            if($express_company_list){
                DB::update('aljhtx_system_setting', array('svalue' => $svalue), array('skey' => 'express_company'));
            }else{
                DB::insert('aljhtx_system_setting', array(
                    'skey' => 'express_company',
                    'svalue' => $svalue,
                ));
            }

            $this->page->tips();
        }else{
            $this->page->assign('express_company', $express_company);
            $this->page->assign('express_company_list', $express_company_list);
            $this->page->display();
        }
    }

    public function express_company_delete(){
        DB::delete('aljhtx_qrcode_common', array('id' => $_GET['qid']));
        T::responseJson();
    }

}

