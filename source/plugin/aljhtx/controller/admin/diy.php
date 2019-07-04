<?php

/**
 * 为品牌商家需要登录的页面提供服务支持
 *
 * @author yuxinqi<yuxinqi@vip.qq.com>
 * @version 1.0
 * @link http://docs.liangjianyun.com/
 */


if(!defined('IN_DISCUZ')) {
    exit('Access Denied');
}

class DiyAction {
    public $page;

    public function __construct($page) {
        $this->page = $page;
        if(!$this->page->config->aljbd){
            $this->page->showMessage(lang("plugin/aljhtx","diy_php_2"), '<a style="color: rgb(30, 159, 255);" target="_blank" href="http://addon.dismall.com/?@aljbd.plugin">' . lang("plugin/aljhtx","diy_php_1") . '</a>');
        }
        $_G['setting']['hookscript'] = array();
    }

    public function pcdiy_logo(){
        if(submitcheck('formhash')){
            $pic = T::getPhotoFilePath();
            if ($this->page->get->uploadPhoto && $this->page->get->size == strlen($this->page->get->uploadPhoto)) {
                file_put_contents($pic,file_get_contents($this->page->get->uploadPhoto));
                if (file_exists($pic)) {
                    if($this->page->config->aljoss->Access_Key){
                        $pic = T::oss($pic, 'aljhtx/setting');
                    }
                    $this->updateCommonSetting('logo', $pic);
                }
            }
            $this->page->tips();
        }else{
            $logo = $this->page->config->aljbd->logo;
            $this->page->assign('logo', $logo, true);
            $this->page->display();
        }
    }

    public function pcdiy_nav(){
        if(submitcheck('formhash')){
            $skey = 'index_dh';
            if($this->page->get->deletesubmit){
                $lz_types = T::stringSettingToArray($this->page->config->aljbd->$skey);
                unset($lz_types[$this->page->get->displayorder]);
                $lz_types = array_values($lz_types);
                $lz_types = T::arrayToStringSetting($lz_types);
                $this->updateCommonSetting($skey, $lz_types);
                $this->updatePluginSetting($skey, $lz_types);

                $this->page->tips();
                exit;
            }
            $lz_types = T::stringSettingToArray($this->page->config->aljbd->$skey);
            $displayorder = $this->page->get->displayorder;

            $lz_types[$displayorder][1] = $this->page->get->url;
            $lz_types[$displayorder][0] = $this->page->get->title;
            $lz_types = T::arrayToStringSetting($lz_types);

            $this->updateCommonSetting($skey, $lz_types);
            $this->updatePluginSetting($skey, $lz_types);
            $this->page->tips();
        }else{
            $sj_index_dh_types = T::stringSettingToArray($this->page->config->aljbd->index_dh);
            $this->page->assign('sj_index_dh_types', $sj_index_dh_types, true);
            $this->page->display();
        }
    }

    public function pcdiy_swiper(){
        if(submitcheck('formhash')){
            $skey = 'index_lz';
            if($this->page->get->deletesubmit){
                $lz_types = T::stringSettingToArray($this->page->config->aljbd->$skey);
                unset($lz_types[$this->page->get->displayorder]);
                $lz_types = array_values($lz_types);
                $lz_types = T::arrayToStringSetting($lz_types);
                $this->updateCommonSetting($skey, $lz_types);
                $this->updatePluginSetting($skey, $lz_types);

                $this->page->tips();
                exit;
            }
            $lz_types = T::stringSettingToArray($this->page->config->aljbd->$skey);
            $displayorder = $this->page->get->displayorder;
            $pic = T::getPhotoFilePath();
            if ($this->page->get->uploadPhoto && $this->page->get->size == strlen($this->page->get->uploadPhoto)) {
                file_put_contents($pic,file_get_contents($this->page->get->uploadPhoto));
                if (file_exists($pic)) {
                    if($this->page->config->aljoss->Access_Key){
                        $pic = T::oss($pic, 'aljhtx/setting');
                    }
                    $lz_types[$displayorder][0] = $pic;
                }
            }
            $lz_types[$displayorder][1] = $this->page->get->url;
            $lz_types[$displayorder][2] = $this->page->get->title;
            $lz_types = T::arrayToStringSetting($lz_types);
            $this->updateCommonSetting($skey, $lz_types);
            $this->updatePluginSetting($skey, $lz_types);
            $this->page->tips();
        }else{
            $lz_types = T::stringSettingToArray($this->page->config->aljbd->index_lz);
            $this->page->assign('lz_types', $lz_types, true);
            $this->page->display();
        }
    }

    public function pcdiy_footer(){
        if(submitcheck('formhash')){
            $skey = $this->page->get->skey;
            if($this->page->get->pc_footer_new_bot){
                $this->updateCommonSetting($skey, stripslashes($this->page->get->pc_footer_new_bot));
                $this->updatePluginSetting($skey, stripslashes($this->page->get->pc_footer_new_bot));
                $this->page->tips();
                exit;
            }
            $lz_types = T::stringSettingToArray($this->page->config->aljbd->$skey);
            $displayorder = $this->page->get->displayorder;
            $pic = T::getPhotoFilePath();
            if ($this->page->get->uploadPhoto && $this->page->get->size == strlen($this->page->get->uploadPhoto)) {
                file_put_contents($pic,file_get_contents($this->page->get->uploadPhoto));
                if (file_exists($pic)) {
                    if($this->page->config->aljoss->Access_Key){
                        $pic = T::oss($pic, 'aljhtx/setting');
                    }
                    $lz_types[$displayorder][0] = $pic;
                }
            }
            if($skey == 'pc_footer_new_top_kefu'){
                $lz_types[$displayorder][1] = $this->page->get->url;
                $lz_types[$displayorder][2] = $this->page->get->title;
                if($this->page->get->uid){
                    $lz_types[$displayorder][3] = $this->page->get->uid;
                }
            }else if(in_array($skey, array('pc_footer_new_cron_c1', 'pc_footer_new_cron_c2','pc_footer_new_cron_c3','pc_footer_new_cron_c4','pc_footer_new_cron_c5'))){
                $lz_types[$displayorder][1] = $this->page->get->url;
                $lz_types[$displayorder][0] = $this->page->get->title;
                if($this->page->get->pc_footer_new_cron_t1){
                    $this->updateCommonSetting('pc_footer_new_cron_t1', $this->page->get->pc_footer_new_cron_t1);
                    $this->updatePluginSetting('pc_footer_new_cron_t1', $this->page->get->pc_footer_new_cron_t1);
                }
                if($this->page->get->pc_footer_new_cron_t2){
                    $this->updateCommonSetting('pc_footer_new_cron_t2', $this->page->get->pc_footer_new_cron_t2);
                    $this->updatePluginSetting('pc_footer_new_cron_t2', $this->page->get->pc_footer_new_cron_t2);
                }
                if($this->page->get->pc_footer_new_cron_t3){
                    $this->updateCommonSetting('pc_footer_new_cron_t3', $this->page->get->pc_footer_new_cron_t3);
                    $this->updatePluginSetting('pc_footer_new_cron_t3', $this->page->get->pc_footer_new_cron_t3);
                }
                if($this->page->get->pc_footer_new_cron_t4){
                    $this->updateCommonSetting('pc_footer_new_cron_t4', $this->page->get->pc_footer_new_cron_t4);
                    $this->updatePluginSetting('pc_footer_new_cron_t4', $this->page->get->pc_footer_new_cron_t4);
                }
                if($this->page->get->pc_footer_new_cron_t5){
                    $this->updateCommonSetting('pc_footer_new_cron_t5', $this->page->get->pc_footer_new_cron_t5);
                    $this->updatePluginSetting('pc_footer_new_cron_t5', $this->page->get->pc_footer_new_cron_t5);
                }
            }else{
                $lz_types[$displayorder][1] = $this->page->get->title;
            }

            $lz_types = T::arrayToStringSetting($lz_types);
            $this->updateCommonSetting($skey, $lz_types);
            $this->updatePluginSetting($skey, $lz_types);
            $this->page->tips();
        }else{
            $pc_footer_new_cron_qrcode = T::stringSettingToArray($this->page->config->aljbd->pc_footer_new_cron_qrcode);
            $pc_footer_new_top = T::stringSettingToArray($this->page->config->aljbd->pc_footer_new_top);
            $pc_footer_new_top_tel = T::stringSettingToArray($this->page->config->aljbd->pc_footer_new_top_tel);
            $pc_footer_new_top_kefu = T::stringSettingToArray($this->page->config->aljbd->pc_footer_new_top_kefu);
            $pc_footer_new_cron_c1 = T::stringSettingToArray($this->page->config->aljbd->pc_footer_new_cron_c1);
            $pc_footer_new_cron_c2 = T::stringSettingToArray($this->page->config->aljbd->pc_footer_new_cron_c2);
            $pc_footer_new_cron_c3 = T::stringSettingToArray($this->page->config->aljbd->pc_footer_new_cron_c3);
            $pc_footer_new_cron_c4 = T::stringSettingToArray($this->page->config->aljbd->pc_footer_new_cron_c4);
            $pc_footer_new_cron_c5 = T::stringSettingToArray($this->page->config->aljbd->pc_footer_new_cron_c5);
            $this->page->assign('pc_footer_new_cron_t1', $this->page->config->aljbd->pc_footer_new_cron_t1, true);
            $this->page->assign('pc_footer_new_cron_t2', $this->page->config->aljbd->pc_footer_new_cron_t2, true);
            $this->page->assign('pc_footer_new_cron_t3', $this->page->config->aljbd->pc_footer_new_cron_t3, true);
            $this->page->assign('pc_footer_new_cron_t4', $this->page->config->aljbd->pc_footer_new_cron_t4, true);
            $this->page->assign('pc_footer_new_cron_t5', $this->page->config->aljbd->pc_footer_new_cron_t5, true);
            $this->page->assign('pc_footer_new_bot', $this->page->config->aljbd->pc_footer_new_bot, true);
            $this->page->assign('pc_footer_new_cron_qrcode', $pc_footer_new_cron_qrcode, true);
            $this->page->assign('pc_footer_new_top', $pc_footer_new_top, true);
            $this->page->assign('pc_footer_new_top_tel', $pc_footer_new_top_tel, true);
            $this->page->assign('pc_footer_new_top_kefu', $pc_footer_new_top_kefu, true);
            $this->page->assign('pc_footer_new_cron_c1', $pc_footer_new_cron_c1, true);
            $this->page->assign('pc_footer_new_cron_c2', $pc_footer_new_cron_c2, true);
            $this->page->assign('pc_footer_new_cron_c3', $pc_footer_new_cron_c3, true);
            $this->page->assign('pc_footer_new_cron_c4', $pc_footer_new_cron_c4, true);
            $this->page->assign('pc_footer_new_cron_c5', $pc_footer_new_cron_c5, true);
            $this->page->display();
        }
    }

    public function pcdiy_hot(){
        if(submitcheck('formhash')){
            $this->updateCommonSetting('HomePageModuleTitle_1', $this->page->get->main_title);
            $this->updatePluginSetting('HomePageModuleTitle_1', $this->page->get->main_title);
            $skey = 'HomePageModuleContent_1';
            $lz_types = T::stringSettingToArray($this->page->config->aljbd->$skey);
            $displayorder = $this->page->get->displayorder;
            $lz_types[$displayorder][0] = $this->page->get->title;
            $lz_types[$displayorder][1] = $this->page->get->sub_title;
            $pic = T::getPhotoFilePath();
            if ($this->page->get->uploadPhoto && $this->page->get->size == strlen($this->page->get->uploadPhoto)) {
                file_put_contents($pic,file_get_contents($this->page->get->uploadPhoto));
                if (file_exists($pic)) {
                    if($this->page->config->aljoss->Access_Key){
                        $pic = T::oss($pic);
                    }
                    $lz_types[$displayorder][2] = $pic;
                }
            }

            $pic_two = T::getPhotoFilePath();
            if ($this->page->get->uploadPhoto_two && $this->page->get->size_two == strlen($this->page->get->uploadPhoto_two)) {
                file_put_contents($pic_two,file_get_contents($this->page->get->uploadPhoto_two));
                if (file_exists($pic_two)) {
                    if($this->page->config->aljoss->Access_Key){
                        $pic_two = T::oss($pic_two, 'aljhtx/setting');
                    }
                    $lz_types[$displayorder][3] = $pic_two;
                }
            }

            $pic_three = T::getPhotoFilePath();
            if ($this->page->get->uploadPhoto_three && $this->page->get->size_three == strlen($this->page->get->uploadPhoto_three)) {
                file_put_contents($pic_three,file_get_contents($this->page->get->uploadPhoto_three));
                if (file_exists($pic_three)) {
                    if($this->page->config->aljoss->Access_Key){
                        $pic_three = T::oss($pic_three, 'aljhtx/setting');
                    }
                    $lz_types[$displayorder][4] = $pic_three;
                }
            }

            $pic_four = T::getPhotoFilePath();
            if ($this->page->get->uploadPhoto_four && $this->page->get->size_four == strlen($this->page->get->uploadPhoto_four)) {
                file_put_contents($pic_four,file_get_contents($this->page->get->uploadPhoto_four));
                if (file_exists($pic_four)) {
                    if($this->page->config->aljoss->Access_Key){
                        $pic_four = T::oss($pic_four, 'aljhtx/setting');
                    }
                    $lz_types[$displayorder][5] = $pic_four;
                }
            }
            $lz_types[$displayorder][6] = $this->page->get->url;

            $lz_types = T::arrayToStringSetting($lz_types);
            $this->updateCommonSetting($skey, $lz_types);
            $this->updatePluginSetting($skey, $lz_types);
            $this->page->tips();
        }else{
            $lz_types = T::stringSettingToArray($this->page->config->aljbd->HomePageModuleContent_1);
            $this->page->assign('lz_types', $lz_types, true);
            $this->page->assign('main_title', $this->page->config->aljbd->HomePageModuleTitle_1, true);
            $this->page->display();
        }
    }


    public function pcdiy_rec(){
        if(submitcheck('formhash')){
            $this->updateCommonSetting('HomePageModuleTitle_2', $this->page->get->main_title);
            $this->updatePluginSetting('HomePageModuleTitle_2', $this->page->get->main_title);
            $skey = 'HomePageModuleContent_2';
            $lz_types = T::stringSettingToArray($this->page->config->aljbd->$skey);
            $displayorder = $this->page->get->displayorder;
            $lz_types[$displayorder][0] = $this->page->get->title;
            $lz_types[$displayorder][1] = $this->page->get->sub_title;
            $lz_types[$displayorder][4] = $this->page->get->button_title;
            $lz_types[$displayorder][5] = $this->page->get->button_color;
            $pic = T::getPhotoFilePath();
            if ($this->page->get->uploadPhoto && $this->page->get->size == strlen($this->page->get->uploadPhoto)) {
                file_put_contents($pic,file_get_contents($this->page->get->uploadPhoto));
                if (file_exists($pic)) {
                    if($this->page->config->aljoss->Access_Key){
                        $pic = T::oss($pic, 'aljhtx/setting');
                    }
                    $lz_types[$displayorder][2] = $pic;
                }
            }

            $pic_two = T::getPhotoFilePath();
            if ($this->page->get->uploadPhoto_two && $this->page->get->size_two == strlen($this->page->get->uploadPhoto_two)) {
                file_put_contents($pic_two,file_get_contents($this->page->get->uploadPhoto_two));
                if (file_exists($pic_two)) {
                    if($this->page->config->aljoss->Access_Key){
                        $pic_two = T::oss($pic_two, 'aljhtx/setting');
                    }
                    $lz_types[$displayorder][3] = $pic_two;
                }
            }

            $pic_three = T::getPhotoFilePath();
            if ($this->page->get->uploadPhoto_three && $this->page->get->size_three == strlen($this->page->get->uploadPhoto_three)) {
                file_put_contents($pic_three,file_get_contents($this->page->get->uploadPhoto_three));
                if (file_exists($pic_three)) {
                    if($this->page->config->aljoss->Access_Key){
                        $pic_three = T::oss($pic_three, 'aljhtx/setting');
                    }
                    $lz_types[$displayorder][4] = $pic_three;
                }
            }

            $pic_four = T::getPhotoFilePath();
            if ($this->page->get->uploadPhoto_four && $this->page->get->size_four == strlen($this->page->get->uploadPhoto_four)) {
                file_put_contents($pic_four,file_get_contents($this->page->get->uploadPhoto_four));
                if (file_exists($pic_four)) {
                    if($this->page->config->aljoss->Access_Key){
                        $pic_four = T::oss($pic_four, 'aljhtx/setting');
                    }
                    $lz_types[$displayorder][5] = $pic_four;
                }
            }
            $lz_types[$displayorder][6] = $this->page->get->url;

            $lz_types = T::arrayToStringSetting($lz_types);
            $this->updateCommonSetting($skey, $lz_types);
            $this->updatePluginSetting($skey, $lz_types);
            $this->page->tips();
        }else{
            $lz_types = T::stringSettingToArray($this->page->config->aljbd->HomePageModuleContent_2);
            $this->page->assign('lz_types', $lz_types, true);
            $this->page->assign('main_title', $this->page->config->aljbd->HomePageModuleTitle_2, true);
            $this->page->display();
        }
    }

    public function pcdiy_find(){
        if(submitcheck('formhash')){
            $this->updateCommonSetting('HomePageModuleTitle_3', $this->page->get->main_title);
            $this->updatePluginSetting('HomePageModuleTitle_3', $this->page->get->main_title);
            $skey = 'HomePageModuleContent_3';
            $lz_types = T::stringSettingToArray($this->page->config->aljbd->$skey);
            $displayorder = $this->page->get->displayorder;
            $lz_types[$displayorder][0] = $this->page->get->title;
            $lz_types[$displayorder][1] = $this->page->get->sub_title;
            $pic = T::getPhotoFilePath();
            if ($this->page->get->uploadPhoto && $this->page->get->size == strlen($this->page->get->uploadPhoto)) {
                file_put_contents($pic,file_get_contents($this->page->get->uploadPhoto));
                if (file_exists($pic)) {
                    if($this->page->config->aljoss->Access_Key){
                        $pic = T::oss($pic, 'aljhtx/setting');
                    }
                    $lz_types[$displayorder][2] = $pic;
                }
            }


            $lz_types[$displayorder][3] = $this->page->get->url;

            $lz_types = T::arrayToStringSetting($lz_types);
            $this->updateCommonSetting($skey, $lz_types);
            $this->updatePluginSetting($skey, $lz_types);
            $this->page->tips();
        }else{
            $lz_types = T::stringSettingToArray($this->page->config->aljbd->HomePageModuleContent_3);
            $this->page->assign('lz_types', $lz_types, true);
            $this->page->assign('main_title', $this->page->config->aljbd->HomePageModuleTitle_3, true);
            $this->page->display();
        }
    }

    public function modCopy(){
        $skey = $this->page->get->skey;
        $template = 'tpl_'.TIMESTAMP;
        DB::insert('aljhtx_diy', array(
            'page' => 'index',
            'type' => 'auto',
            'module' => $skey,
            'auto_module' => $skey.'_'.$template,
            'template' => $template,
            'displayorder' => DB::result_first('select max(displayorder) from %t', array('aljhtx_diy'))+1,
            'data' => $this->page->config->aljbd->$skey
        ));
    }

    public function modDelete(){
        $skey = $this->page->get->skey;
        DB::delete('aljhtx_diy', array(
            'auto_module' => $skey,
        ));
    }

    public function modUp(){
        $diy = DB::fetch_first('select * from %t where module=%s or auto_module=%s', array('aljhtx_diy', $this->page->get->skey, $this->page->get->skey));
        $upDiy =  DB::fetch_first('select * from %t where displayorder<%d order by displayorder desc', array('aljhtx_diy', $diy['displayorder']));
        if($diy && $upDiy){
            DB::update('aljhtx_diy', array(
                'displayorder' => $diy['displayorder']
            ), array(
                'id' => $upDiy['id']
            ));

            DB::update('aljhtx_diy', array(
                'displayorder' => $upDiy['displayorder']
            ), array(
                'id' => $diy['id']
            ));
        }
    }

    public function modDown(){
        $diy = DB::fetch_first('select * from %t where module=%s or auto_module=%s', array('aljhtx_diy', $this->page->get->skey, $this->page->get->skey));
        $downDiy =  DB::fetch_first('select * from %t where displayorder>%d order by displayorder asc', array('aljhtx_diy', $diy['displayorder']));
        if($diy && $downDiy){
            DB::update('aljhtx_diy', array(
                'displayorder' => $diy['displayorder']
            ), array(
                'id' => $downDiy['id']
            ));

            DB::update('aljhtx_diy', array(
                'displayorder' => $downDiy['displayorder']
            ), array(
                'id' => $diy['id']
            ));
        }
    }

    /**
     * 我的页面DIY
     *
     * @return void
     */
    public function my(){
        $skey = $this->page->get->skey;
        if($this->page->get->deletesubmit){
            $lz_types = T::stringSettingToArray($this->page->config->aljbd->$skey);
            unset($lz_types[$this->page->get->displayorder]);
            $lz_types = array_values($lz_types);
            $lz_types = T::arrayToStringSetting($lz_types);
            $this->updateCommonSetting($skey, $lz_types);
            $this->updatePluginSetting($skey, $lz_types);

            $this->page->tips(2, $skey.'_'.$this->page->get->displayorder);
            exit;
        }
        if(submitcheck('formhash')){
            if($skey == 'is_myLove'){
                $this->updateCommonSetting($skey, $this->page->get->$skey);
                $this->updatePluginSetting($skey, $this->page->get->$skey);
                $this->page->tips();
            }
            $lz_types = T::stringSettingToArray($this->page->config->aljbd->$skey);
            $displayorder = $this->page->get->displayorder;
            $pic = T::getPhotoFilePath();
            if ($this->page->get->uploadPhoto && $this->page->get->size == strlen($this->page->get->uploadPhoto)) {
                file_put_contents($pic,file_get_contents($this->page->get->uploadPhoto));
                if (file_exists($pic)) {
                    if($this->page->config->aljoss->Access_Key){
                        $pic = T::oss($pic, 'aljhtx/setting');
                    }
                    $lz_types[$displayorder][0] = $pic;
                }
            }

            $lz_types[$displayorder][1] = $this->page->get->url;
            $lz_types[$displayorder][2] = $this->page->get->title;
            $lz_types = T::arrayToStringSetting($lz_types);
            if(DB::result_first('select type from %t where auto_module=%s', array('aljhtx_diy', $skey)) == 'system'){
                $this->updateCommonSetting($skey, $lz_types);
                $this->updatePluginSetting($skey, $lz_types);
            }else{
                DB::update('aljhtx_diy', array(
                    'data' => $lz_types
                ), array(
                    'auto_module' => $skey
                ));
            }
            $this->page->tips();
        }else{
            $diyListData = DB::fetch_all('select * from %t where type=%s and page=%s', array('aljhtx_diy', 'system', 'my'));
            if(!$diyListData){
                $moduleList = array(
                    1001 => 'mobile_user_order',
                    1002 => 'mobile_user_common_entrance',
                    1003 => 'mobile_user_business_center',
                    1004 => 'mobile_user_city_wide',
                    1005 => 'is_myLove'
                );
                foreach($moduleList as $k => $v){
                    $insertArray = array(
                        'page' => 'my',
                        'type' => 'system',
                        'module' => $v,
                        'auto_module' => $v,
                        'displayorder' => $k,
                        'template' => 'mobile_user_order',
                    );
                    if($v == 'is_myLove'){
                        $insertArray['template'] = 'is_myLove';
                    }
                    DB::insert('aljhtx_diy', $insertArray);
                }
                $diyListData = DB::fetch_all('select * from %t where type=%s and page=%s', array('aljhtx_diy', 'system', 'my'));
            }
            foreach($diyListData as $k => $diy){
                if($diy['type'] == 'system'){
                    if($diy['module'] == 'is_myLove'){
                        $diy['data'] = $this->page->config->aljbd->{$diy['module']};
                    }else{
                        $diy['data'] = T::stringSettingToArray($this->page->config->aljbd->{$diy['module']});
                    }

                }else{
                    $diy['data'] = T::stringSettingToArray($diy['data']);
                }

                $diyAutoList[$diy['auto_module']] = $diy;
            }

            $this->page->assign('diyAutoList', $diyAutoList, true); //图文广告复制功能
            $this->page->display();
        }
    }


    /**
     * 店铺页DIY
     *
     * @return void
     */
    public function shop(){
        $skey = $this->page->get->skey;
        if($this->page->get->deletesubmit){
            $lz_types = T::stringSettingToArray($this->page->config->aljbd->$skey);
            unset($lz_types[$this->page->get->displayorder]);
            $lz_types = array_values($lz_types);
            $lz_types = T::arrayToStringSetting($lz_types);
            $this->updateCommonSetting($skey, $lz_types);
            $this->updatePluginSetting($skey, $lz_types);

            $this->page->tips(2, $skey.'_'.$this->page->get->displayorder);
            exit;
        }
        if(submitcheck('formhash')){
            $lz_types = T::stringSettingToArray($this->page->config->aljbd->$skey);
            $displayorder = $this->page->get->displayorder;
            $pic = T::getPhotoFilePath();
            if ($this->page->get->uploadPhoto && $this->page->get->size == strlen($this->page->get->uploadPhoto)) {
                file_put_contents($pic,file_get_contents($this->page->get->uploadPhoto));
                if (file_exists($pic)) {
                    if($this->page->config->aljoss->Access_Key){
                        $pic = T::oss($pic, 'aljhtx/setting');
                    }
                    $lz_types[$displayorder][0] = $pic;
                }
            }

            $lz_types[$displayorder][1] = $this->page->get->url;
            $lz_types[$displayorder][2] = $this->page->get->title;
            $lz_types = T::arrayToStringSetting($lz_types);
            if(DB::result_first('select type from %t where auto_module=%s', array('aljhtx_diy', $skey)) == 'system'){
                $this->updateCommonSetting($skey, $lz_types);
                $this->updatePluginSetting($skey, $lz_types);
            }else{
                DB::update('aljhtx_diy', array(
                    'data' => $lz_types
                ), array(
                    'auto_module' => $skey
                ));
            }
            $this->page->tips();
        }else{
            $diyListData = DB::fetch_all('select * from %t where type=%s and page=%s', array('aljhtx_diy', 'system', 'shop'));
            if(!$diyListData){
                DB::insert('aljhtx_diy', array(
                    'page' => 'shop',
                    'type' => 'system',
                    'module' => 'mobile_view_imgnav',
                    'auto_module' => 'mobile_view_imgnav',
                    'displayorder' => 1000,
                    'template' => 'mobile_view_imgnav',
                ));
                $diyListData = DB::fetch_all('select * from %t where type=%s and page=%s', array('aljhtx_diy', 'system', 'shop'));
            }
            foreach($diyListData as $k => $diy){
                if($diy['type'] == 'system'){
                    $diy['data'] = T::stringSettingToArray($this->page->config->aljbd->{$diy['module']});
                }else{
                    $diy['data'] = T::stringSettingToArray($diy['data']);
                }

                $diyAutoList[$diy['auto_module']] = $diy;
            }
            $bid = DB::result_first('select id from %t where status = 1 and rubbish=0', array('aljbd'));
            $shopUrl = 'plugin.php?id=aljbd&act=view&mobilediy=yes&bid='.$bid;

            $this->page->assign('diyAutoList', $diyAutoList, true); //图文广告复制功能
            $this->page->assign('shopUrl', $shopUrl, true); //图文广告复制功能
            $this->page->display();
        }
    }



    /**
     * 首页DIY
     *
     * @return void
     */
    public function setting(){
        if(submitcheck('formhash')){
            $skey = $this->page->get->skey;
            $diy = DB::fetch_first('select * from %t where auto_module=%s', array('aljhtx_diy', $skey));
            if($diy){
                if($this->page->get->deletesubmit){
                    $lz_types = T::stringSettingToArray($diy['data']);
                    unset($lz_types[intval($this->page->get->displayorder)]);
                    $lz_types = array_values($lz_types);
                    $lz_types = T::arrayToStringSetting($lz_types);
                    DB::update('oa_diy', array(
                        'data' => $lz_types
                    ), array(
                        'auto_module' => $skey
                    ));
                }else{
                    $lz_types = T::stringSettingToArray($diy['data']);
                    $displayorder = intval($this->page->get->displayorder);
                    $pic = T::getPhotoFilePath();
                    if ($this->page->get->uploadPhoto && $this->page->get->size == strlen($this->page->get->uploadPhoto)) {
                        file_put_contents($pic,file_get_contents($this->page->get->uploadPhoto));
                        if (file_exists($pic)) {
                            if($this->page->config->aljoss->Access_Key){
                                $pic = T::oss($pic, 'aljhtx/setting');
                            }
                            if($diy['module'] == 'mobile_index_Photo_Ads'){
                                $lz_types[$displayorder][2] = $pic;
                            }else{
                                $lz_types[$displayorder][0] = $pic;
                            }

                        }
                    }
                    if($diy['module'] == 'mobile_index_Photo_Ads') {
                        $lz_types[$displayorder][0] = $this->page->get->title;
                        $lz_types[$displayorder][1] = $this->page->get->desc;
                        $lz_types[$displayorder][3] = $this->page->get->url;
                    }else{
                        $lz_types[$displayorder][1] = $this->page->get->url;
                    }

                    $lz_types = T::arrayToStringSetting($lz_types);

                    DB::update('aljhtx_diy', array(
                        'data' => $lz_types
                    ), array(
                        'auto_module' => $skey
                    ));
                    $this->page->tips();
                }
            }else{
                if($this->page->get->deletesubmit){
                    $lz_types = T::stringSettingToArray($this->page->config->aljbd->$skey);
                    unset($lz_types[$this->page->get->displayorder]);
                    $lz_types = array_values($lz_types);
                    $lz_types = T::arrayToStringSetting($lz_types);
                    $this->updateCommonSetting($skey, $lz_types);
                    $this->updatePluginSetting($skey, $lz_types);

                    if($this->page->get->keyname && $this->page->get->module){
                        DB::delete('aljad_adsettings', array('keyname' => $this->page->get->keyname, 'module' => $this->page->get->module));
                    }
                    $this->page->tips(2, $skey.'_'.$this->page->get->displayorder);
                    exit;
                }else{
                    $this->$skey($skey);
                }
            }
        }else{
            //aljad_index_sangead
            if($this->page->config->aljbd->aljad_index_lz){
                $where = 'where 0 ';
                preg_match_all('/.*?(\'|")(.*?)(\'|").*?/is', htmlspecialchars_decode($this->page->config->aljbd->aljad_index_lz), $matchArray);
                foreach($matchArray[2] as $k => $v){ //http_build_query
                    $url = parse_url($v);
                    parse_str($url['query'], $queryArray);
                    $dataList[] = $queryArray;
                    $keyNameList[] = $queryArray['keyname'];
                    $where .= "or (keyname = '".addslashes($queryArray['keyname'])."' and module = '".addslashes($queryArray['module'])."')";
                    $moduleList[] = $queryArray['module'];
                }
                $aljad_index_lz = DB::fetch_all('select * from %t ' . $where, array('aljad_adsettings'));
            }

            if($this->page->config->aljbd->aljad_index_sangead){
                $where = 'where 0 ';
                preg_match_all('/.*?(\'|")(.*?)(\'|").*?/is', htmlspecialchars_decode($this->page->config->aljbd->aljad_index_sangead), $matchArray);
                foreach($matchArray[2] as $k => $v){ //http_build_query
                    $url = parse_url($v);
                    parse_str($url['query'], $queryArray);
                    $dataList[] = $queryArray;
                    $keyNameList[] = $queryArray['keyname'];
                    $where .= "or (keyname = '".addslashes($queryArray['keyname'])."' and module = '".addslashes($queryArray['module'])."')";
                    $moduleList[] = $queryArray['module'];
                }
                $aljad_index_sangead = DB::fetch_all('select * from %t ' . $where, array('aljad_adsettings'));
            }

            if($this->page->config->aljbd->aljad_index_four_lattice){
                $where = 'where 0 ';
                preg_match_all('/.*?(\'|")(.*?)(\'|").*?/is', htmlspecialchars_decode($this->page->config->aljbd->aljad_index_four_lattice), $matchArray);
                foreach($matchArray[2] as $k => $v){ //http_build_query
                    $url = parse_url($v);
                    parse_str($url['query'], $queryArray);
                    $dataList[] = $queryArray;
                    $keyNameList[] = $queryArray['keyname'];
                    $where .= "or (keyname = '".addslashes($queryArray['keyname'])."' and module = '".addslashes($queryArray['module'])."')";
                    $moduleList[] = $queryArray['module'];
                }
                $aljad_index_four_lattice = DB::fetch_all('select * from %t ' . $where, array('aljad_adsettings'));
            }


            //图片轮播
            $lz_types = T::stringSettingToArray($this->page->config->aljbd->sj_img_1);
            //底部导航
            $mobile_common_footernav = T::stringSettingToArray($this->page->config->aljbd->mobile_common_footernav);

            //公告
            $gg_types = T::stringSettingToArray($this->page->config->aljbd->gg);
            //图片导航
            $sj_index_dh_types = T::stringSettingToArray($this->page->config->aljbd->sj_index_dh);
            //三格广告
            $mobile_index_tad = T::stringSettingToArray($this->page->config->aljbd->mobile_index_tad);
            //四格头部广告
            $mobile_index_fad_title = T::stringSettingToArray($this->page->config->aljbd->mobile_index_fad_title);
            //四格广告
            $mobile_index_fad = T::stringSettingToArray($this->page->config->aljbd->mobile_index_fad);
            //图文广告
            $mobile_index_Photo_Ads = T::stringSettingToArray($this->page->config->aljbd->mobile_index_Photo_Ads);

            $diyListData = DB::fetch_all('select * from %t where type=%s and page=%s', array('aljhtx_diy', 'auto', 'index'));
            foreach($diyListData as $k => $diy){
                $templateName = $diy['module'].'_'.$diy['type'];
                $diy['module'] = $templateName;
                $diy['data'] = T::stringSettingToArray($diy['data']);
                $diyAutoList[$diy['auto_module']] = $diy;
            }

            $this->page->assign('diyAutoList', $diyAutoList, true); //图文广告复制功能
            $this->page->assign('is_mobile_index_love', $this->page->config->aljbd->is_mobile_index_love); //猜您喜欢开启分类横向滚动模式
            $this->page->assign('is_mobile_index_love_type', $this->page->config->aljbd->is_mobile_index_love_type); //猜您喜欢开启分类横向滚动模式
            $this->page->assign('sj_index_dh_types', $sj_index_dh_types); //图片导航
            $this->page->assign('lz_types', $lz_types); //轮播广告
            $this->page->assign('aljad_index_lz', $aljad_index_lz, true); //轮播自助广告
            $this->page->assign('gg_types', $gg_types); //公告
            $this->page->assign('mobile_index_tad', $mobile_index_tad); //三格广告
            $this->page->assign('aljad_index_sangead', $aljad_index_sangead); //三格自助广告
            $this->page->assign('mobile_index_fad', $mobile_index_fad); //四格广告
            $this->page->assign('mobile_index_fad_title', $mobile_index_fad_title); //四格头部广告
            $this->page->assign('aljad_index_four_lattice', $aljad_index_four_lattice); //四格自助广告
            $this->page->assign('mobile_common_footernav', $mobile_common_footernav, true); //底部导航
            $this->page->assign('mobile_index_Photo_Ads', $mobile_index_Photo_Ads, true); //图文广告
            $this->page->display();
        }

    }

    public function changeAdvSettingWindow(){
        $skey = $this->page->get->skey;
        $svalue = DB::result_first('select svalue from %t where action = %s and skey=%s', array('aljhtx_setting', ACTION, $skey));
        if($this->page->get->delete){
            if(!$svalue && $this->page->config->aljbd->$skey){
                DB::insert('aljhtx_setting', array(
                    'root' => ROOT,
                    'controller' => CONTROLLER,
                    'action' => ACTION,
                    'skey' => $skey,
                    'svalue' => $this->page->config->aljbd->$skey,
                ));
            }
            $data = '';
            $this->updateCommonSetting($skey, $data);
            $this->updatePluginSetting($skey, $data);
        }else{
            if(!$this->page->config->aljbd->$skey && $svalue){
                $data = $svalue;
                $this->updateCommonSetting($skey, $data);
                $this->updatePluginSetting($skey, $data);
            }
        }

        T::responseJson();
    }

    /**
     * 首页三格广告
     *
     * @param string $skey 变量名
     * @return void
     */
    public function aljad_index_sangead($skey){
        $this->aljad_index_lz($skey);
    }

    /**
     * 首页四格广告
     *
     * @param string $skey 变量名
     * @return void
     */
    public function aljad_index_four_lattice($skey){
        $this->aljad_index_lz($skey);
    }

    /**
     * 首页轮播自助广告
     *
     * @param string $skey 变量名
     * @return void
     */
    public function aljad_index_lz($skey){
        $data = array(
            'adheight' => $this->page->get->adheight,
            'adprice' => $this->page->get->adprice,
            'maxday' => $this->page->get->maxday,
        );
        $pic = T::getPhotoFilePath();
        if ($this->page->get->uploadPhoto && $this->page->get->size == strlen($this->page->get->uploadPhoto)) {
            file_put_contents($pic,file_get_contents($this->page->get->uploadPhoto));
            if (file_exists($pic)) {
                if($this->page->config->aljoss->Access_Key){
                    $pic = T::oss($pic, 'aljhtx/setting');
                }
                $data['adimg'] = $pic;
            }
        }

        if($this->page->get->add == 'yes'){
            $data['keyname'] = 'aljbd';
            $data['module'] = 'auto_'.TIMESTAMP;
            $keyname = $data['keyname'];
            $module = $data['module'];
            DB::insert('aljad_adsettings', $data);
        }else{
            DB::update('aljad_adsettings', $data, array(
                'keyname' => $this->page->get->keyname,
                'module' => $this->page->get->module,
            ));
            $keyname = $this->page->get->keyname;
            $module = $this->page->get->module;
        }


        $lz_types = T::stringSettingToArray(htmlspecialchars_decode($this->page->config->aljbd->$skey));
        $displayorder = $this->page->get->displayorder;
        $lz_types[$displayorder][0] = "<script src='plugin.php?id=aljad&act=getad&keyname={$keyname}&module={$module}&height=".$this->page->get->adheight."'></script>";;
        $lz_types = T::arrayToStringSetting($lz_types);
        $this->updateCommonSetting($skey, $lz_types);
        $this->updatePluginSetting($skey, $lz_types);
        $this->page->tips();
    }

    /**
     * 向下移动
     *
     * @return void
     */
    public function faArrowDown(){
        $skey = $this->page->get->skey;
        $lz_types = T::stringSettingToArray($this->page->config->aljbd->$skey);
        $tmp = $lz_types[$this->page->get->displayorder];
        $lz_types[$this->page->get->displayorder] = $lz_types[$this->page->get->displayorder+1];
        $lz_types[$this->page->get->displayorder+1] = $tmp;
        $lz_types = T::arrayToStringSetting($lz_types);
        $this->updateCommonSetting($skey, $lz_types);
        $this->updatePluginSetting($skey, $lz_types);
        T::responseJson();

    }

    /**
     * 向上移动
     *
     * @return void
     */
    public function faArrowUp(){
        $skey = $this->page->get->skey;
        $lz_types = T::stringSettingToArray($this->page->config->aljbd->$skey);
        $tmp = $lz_types[$this->page->get->displayorder];
        $lz_types[$this->page->get->displayorder] = $lz_types[$this->page->get->displayorder-1];
        $lz_types[$this->page->get->displayorder-1] = $tmp;
        $lz_types = T::arrayToStringSetting($lz_types);
        $this->updateCommonSetting($skey, $lz_types);
        $this->updatePluginSetting($skey, $lz_types);
        T::responseJson();

    }

    /**
     * 修改常用设置项
     *
     * @param string $skey 变量名
     * @param string $lz_types value值
     * @return void
     */
    public function updateCommonSetting($skey, $lz_types){
        if(!C::t('#aljbd#aljbd_setting')->fetch($skey)){
            C::t('#aljbd#aljbd_setting')->insert(array(
                'key' => $skey,
                'value' => $lz_types
            ));
        }else{
            C::t('#aljbd#aljbd_setting')->update_value_by_key($lz_types,$skey);
        }

    }

    /**
     * 修改插件设置项
     *
     * @param string $skey 变量名
     * @param string $lz_types value值
     * @return void
     */
    public function updatePluginSetting($skey, $lz_types){
        $plugin = C::t('common_plugin')->fetch_by_identifier('aljbd');
        C::t('common_pluginvar')->update_by_variable($plugin['pluginid'], $skey, array('value' => $lz_types));
    }

    /**
     * 公告
     *
     * @param string $skey 变量名
     * @return void
     */
    public function gg($skey){
        $lz_types = T::stringSettingToArray($this->page->config->aljbd->$skey);
        $displayorder = $this->page->get->displayorder;
        $lz_types[$displayorder][0] = $this->page->get->title;
        $lz_types[$displayorder][1] = $this->page->get->url;
        $lz_types = T::arrayToStringSetting($lz_types);
        $this->updateCommonSetting($skey, $lz_types);
        $this->updatePluginSetting($skey, $lz_types);
        $this->page->tips();
    }

    /**
     * 公告标题图
     *
     * @param string $skey 变量名
     * @return void
     */
    public function gg_icon($skey){
        $pic = T::getPhotoFilePath();
        if ($this->page->get->uploadPhoto && $this->page->get->size == strlen($this->page->get->uploadPhoto)) {
            file_put_contents($pic,file_get_contents($this->page->get->uploadPhoto));
            if (file_exists($pic)) {
                $this->updateCommonSetting($skey, $pic);
                $this->updatePluginSetting($skey, $pic);
            }
        }
        $this->page->tips();
    }

    /**
     * 三格广告标题图
     *
     * @param string $skey 变量名
     * @return void
     */
    public function mobile_index_tad_title($skey){
        $pic = T::getPhotoFilePath();
        if ($this->page->get->uploadPhoto && $this->page->get->size == strlen($this->page->get->uploadPhoto)) {
            file_put_contents($pic,file_get_contents($this->page->get->uploadPhoto));
            if (file_exists($pic)) {
                $this->updateCommonSetting($skey, $pic);
                $this->updatePluginSetting($skey, $pic);
            }
        }
        $this->page->tips();
    }

    /**
     * 四格广告标题图
     *
     * @param string $skey 变量名
     * @return void
     */
    public function mobile_index_fad_title($skey){
        $pic = T::getPhotoFilePath();
        $lz_types = T::stringSettingToArray($this->page->config->aljbd->$skey);
        if ($this->page->get->uploadPhoto && $this->page->get->size == strlen($this->page->get->uploadPhoto)) {
            file_put_contents($pic,file_get_contents($this->page->get->uploadPhoto));
            if (file_exists($pic)) {
                if($this->page->config->aljoss->Access_Key){
                    $pic = T::oss($pic, 'aljhtx/setting');
                }
                $lz_types[0][0] = $pic;
            }
        }
        $lz_types[0][1] = $this->page->get->url;
        $lz_types = T::arrayToStringSetting($lz_types);
        $this->updateCommonSetting($skey, $lz_types);
        $this->updatePluginSetting($skey, $lz_types);
        $this->page->tips();
    }

    /**
     * 修改底部菜单
     *
     * @param string $skey 变量名
     * @return void
     */
    public function mobile_common_footernav($skey){
        $lz_types = T::stringSettingToArray($this->page->config->aljbd->$skey);
        $displayorder = $this->page->get->displayorder;
        $pic = T::getPhotoFilePath();
        if ($this->page->get->uploadPhoto && $this->page->get->size == strlen($this->page->get->uploadPhoto)) {
            file_put_contents($pic,file_get_contents($this->page->get->uploadPhoto));
            if (file_exists($pic)) {
                if($this->page->config->aljoss->Access_Key){
                    $pic = T::oss($pic, 'aljhtx/setting');
                }
                $lz_types[$displayorder][0] = $pic;
            }
        }

        $pic_two = T::getPhotoFilePath();
        if ($this->page->get->uploadPhoto_two && $this->page->get->size_two == strlen($this->page->get->uploadPhoto_two)) {
            file_put_contents($pic_two,file_get_contents($this->page->get->uploadPhoto_two));
            if (file_exists($pic_two)) {
                if($this->page->config->aljoss->Access_Key){
                    $pic_two = T::oss($pic_two, 'aljhtx/setting');
                }
                $lz_types[$displayorder][1] = $pic_two;
            }
        }

        $lz_types[$displayorder][2] = $this->page->get->title;
        $lz_types[$displayorder][3] = $this->page->get->url;
        $lz_types = T::arrayToStringSetting($lz_types);
        $this->updateCommonSetting($skey, $lz_types);
        $this->updatePluginSetting($skey, $lz_types);
        $this->page->tips();
    }

    /**
     * 修改轮播图
     *
     * @param string $skey 变量名
     * @return void
     */
    public function sj_img_1($skey){
        $lz_types = T::stringSettingToArray($this->page->config->aljbd->$skey);
        $displayorder = $this->page->get->displayorder;
        $pic = T::getPhotoFilePath();
        if ($this->page->get->uploadPhoto && $this->page->get->size == strlen($this->page->get->uploadPhoto)) {
            file_put_contents($pic,file_get_contents($this->page->get->uploadPhoto));
            if (file_exists($pic)) {
                if($this->page->config->aljoss->Access_Key){
                    $pic = T::oss($pic, 'aljhtx/setting');
                }
                $lz_types[$displayorder][0] = $pic;
            }
        }
        $lz_types[$displayorder][1] = $this->page->get->url;
        $lz_types[$displayorder][2] = $this->page->get->title;
        $lz_types = T::arrayToStringSetting($lz_types);
        $this->updateCommonSetting($skey, $lz_types);
        $this->updatePluginSetting($skey, $lz_types);
        $this->page->tips();
    }

    /**
     * 修改轮播下方的图标导航
     *
     * @param string $skey 变量名
     * @return void
     */
    public function sj_index_dh($skey){
        $lz_types = T::stringSettingToArray($this->page->config->aljbd->$skey);
        $displayorder = $this->page->get->displayorder;

        $pic = T::getPhotoFilePath();
        $lz_types[$displayorder][0] = $this->page->get->title;
        $lz_types[$displayorder][1] = $this->page->get->url;
        if ($this->page->get->uploadPhoto && $this->page->get->size == strlen($this->page->get->uploadPhoto)) {
            file_put_contents($pic,file_get_contents($this->page->get->uploadPhoto));
            if (file_exists($pic)) {
                if($this->page->config->aljoss->Access_Key){
                    $pic = T::oss($pic, 'aljhtx/setting');
                }
                $lz_types[$displayorder][2] = $pic;
            }
        }


        $lz_types = T::arrayToStringSetting($lz_types);
        $this->updateCommonSetting($skey, $lz_types);
        $this->updatePluginSetting($skey, $lz_types);
        $this->page->tips();
    }


    /**
     * 修改首页猜您喜欢
     *
     * @param string $skey 变量名
     * @return void
     */
    public function is_mobile_index_love($skey){
        $this->updateCommonSetting($skey, $this->page->get->is_mobile_index_love);
        $this->updatePluginSetting($skey, $this->page->get->is_mobile_index_love);
        $this->updatePluginSetting('is_mobile_index_love_type', $this->page->get->is_mobile_index_love_type);
        $this->updateCommonSetting('is_mobile_index_love_type', $this->page->get->is_mobile_index_love_type);
        $this->page->tips();
    }

    /**
     * 修改图文广告
     *
     * @param string $skey 变量名
     * @return void
     */
    public function mobile_index_Photo_Ads($skey){
        $lz_types = T::stringSettingToArray($this->page->config->aljbd->$skey);
        $displayorder = $this->page->get->displayorder;
        $lz_types[$displayorder][0] = $this->page->get->title;
        $lz_types[$displayorder][1] = $this->page->get->desc;
        $pic = T::getPhotoFilePath();
        if ($this->page->get->uploadPhoto && $this->page->get->size == strlen($this->page->get->uploadPhoto)) {
            file_put_contents($pic,file_get_contents($this->page->get->uploadPhoto));
            if (file_exists($pic)) {
                if($this->page->config->aljoss->Access_Key){
                    $pic = T::oss($pic, 'aljhtx/setting');
                }
                $lz_types[$displayorder][2] = $pic;
            }
        }
        $lz_types[$displayorder][3] = $this->page->get->url;


        $lz_types = T::arrayToStringSetting($lz_types);
        $this->updateCommonSetting($skey, $lz_types);
        $this->updatePluginSetting($skey, $lz_types);
        $this->page->tips();
    }

    /**
     * 修改三格广告图
     *
     * @param string $skey 变量名
     * @return void
     */
    public function mobile_index_tad($skey){
        $lz_types = T::stringSettingToArray($this->page->config->aljbd->$skey);
        $displayorder = $this->page->get->displayorder;
        $pic = T::getPhotoFilePath();
        if ($this->page->get->uploadPhoto && $this->page->get->size == strlen($this->page->get->uploadPhoto)) {
            file_put_contents($pic,file_get_contents($this->page->get->uploadPhoto));
            if (file_exists($pic)) {
                if($this->page->config->aljoss->Access_Key){
                    $pic = T::oss($pic, 'aljhtx/setting');
                }
                $lz_types[$displayorder][0] = $pic;
            }
        }
        $lz_types[$displayorder][1] = $this->page->get->url;
        $lz_types = T::arrayToStringSetting($lz_types);
        $this->updateCommonSetting($skey, $lz_types);
        $this->page->tips();
    }

    /**
     * 修改四格广告图
     *
     * @param string $skey 变量名
     * @return void
     */
    public function mobile_index_fad($skey){
        $lz_types = T::stringSettingToArray($this->page->config->aljbd->$skey);
        $displayorder = $this->page->get->displayorder;
        $pic = T::getPhotoFilePath();
        if ($this->page->get->uploadPhoto && $this->page->get->size == strlen($this->page->get->uploadPhoto)) {
            file_put_contents($pic,file_get_contents($this->page->get->uploadPhoto));
            if (file_exists($pic)) {
                if($this->page->config->aljoss->Access_Key){
                    $pic = T::oss($pic, 'aljhtx/setting');
                }
                $lz_types[$displayorder][0] = $pic;
            }
        }
        $lz_types[$displayorder][1] = $this->page->get->url;
        $lz_types = T::arrayToStringSetting($lz_types);
        $this->updateCommonSetting($skey, $lz_types);
        $this->page->tips();
    }


}