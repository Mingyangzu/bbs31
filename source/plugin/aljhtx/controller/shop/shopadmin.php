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

class shopAdminAction{
    public $page;

    public function __construct($page) {
        $this->page = $page;
        if (empty($this->page->global->uid)) {
            dheader("location:" . LOGIN_URL);
            exit;
        }
    }

    /**
     * 提供通用设置功能
     *
     * @return void
     */
    public function setting(){
        if(submitcheck('formhash')){
            $skey = $this->page->get->skey;
            if($this->page->get->deletesubmit){

                $lz_types = T::stringSettingToArray($this->page->config->aljbd->$skey);
                unset($lz_types[$this->page->get->displayorder]);

                $lz_types = array_values($lz_types);
                $lz_types = T::arrayToStringSetting($lz_types);
                $this->updateCommonSetting($skey, $lz_types);
                $this->updatePluginSetting($skey, $lz_types);
                $this->page->tips(1);
                exit;
            }else{
                $this->$skey($skey);
            }

        }else{

            //图片轮播
            $lz_types = T::stringSettingToArray($this->page->config->aljbd->sj_img_1);

            //图片导航
            $sj_index_dh_types = T::stringSettingToArray($this->page->config->aljbd->sj_index_dh);
            //三格广告
            $mobile_index_tad = T::stringSettingToArray($this->page->config->aljbd->mobile_index_tad);
            //四格格广告
            $mobile_index_fad = T::stringSettingToArray($this->page->config->aljbd->mobile_index_fad);

            $this->page->assign('sj_index_dh_types', $sj_index_dh_types); //图片导航
            $this->page->assign('lz_types', $lz_types); //轮播广告
            $this->page->assign('mobile_index_tad', $mobile_index_tad); //三格广告
            $this->page->assign('mobile_index_fad', $mobile_index_fad); //四格广告
            $this->page->display();
        }

    }

    public function updateCommonSetting($skey, $lz_types){
        C::t('#aljbd#aljbd_setting')->update_value_by_key($lz_types,$skey);
    }

    public function updatePluginSetting($skey, $lz_types){
        $plugin = C::t('common_plugin')->fetch_by_identifier('aljbd');
        C::t('common_pluginvar')->update_by_variable($plugin['pluginid'], $skey, array('value' => $lz_types));
        T::f('cache');
        updatecache(array('plugin', 'setting', 'styles'));
        cleartemplatecache();

    }


    public function sj_img_1($skey){
        $lz_types = T::stringSettingToArray($this->page->config->aljbd->$skey);
        $displayorder = $this->page->get->displayorder;
        $img_dir = 'source/plugin/aljhtx/static/img/setting/'.date('Ymd',TIMESTAMP).'/';
        if (!is_dir($img_dir)) {
            mkdir($img_dir);
        }
        $pic = $img_dir . date("YmdHis") . rand(100, 999) . '.jpg';
        //debug($fn_length);

        if ($this->page->get->uploadPhoto && $this->page->get->size == strlen($this->page->get->uploadPhoto)) {
            file_put_contents($pic,file_get_contents($this->page->get->uploadPhoto));
            if (file_exists($pic)) {
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

    public function sj_index_dh($skey){
        $lz_types = T::stringSettingToArray($this->page->config->aljbd->$skey);
        $displayorder = $this->page->get->displayorder;

        $pic = T::getPhotoFilePath();
        if ($this->page->get->uploadPhoto && $this->page->get->size == strlen($this->page->get->uploadPhoto)) {
            file_put_contents($pic,file_get_contents($this->page->get->uploadPhoto));
            if (file_exists($pic)) {
                $lz_types[$displayorder][2] = $pic;
            }
        }
        $lz_types[$displayorder][1] = $this->page->get->url;
        $lz_types[$displayorder][0] = $this->page->get->title;
        $lz_types = T::arrayToStringSetting($lz_types);
        $this->updateCommonSetting($skey, $lz_types);
        $this->updatePluginSetting($skey, $lz_types);
        $this->page->tips();
    }

    public function mobile_index_tad($skey){
        $lz_types = T::stringSettingToArray($this->page->config->aljbd->$skey);
        $displayorder = $this->page->get->displayorder;
        $pic = T::getPhotoFilePath();
        if ($this->page->get->uploadPhoto && $this->page->get->size == strlen($this->page->get->uploadPhoto)) {
            file_put_contents($pic,file_get_contents($this->page->get->uploadPhoto));
            if (file_exists($pic)) {
                $lz_types[$displayorder][0] = $pic;
            }
        }
        $lz_types[$displayorder][1] = $this->page->get->url;
        $lz_types = T::arrayToStringSetting($lz_types);
        $this->updateCommonSetting($skey, $lz_types);
        $this->page->tips();
    }

    public function mobile_index_fad($skey){
        $lz_types = T::stringSettingToArray($this->page->config->aljbd->$skey);
        $displayorder = $this->page->get->displayorder;
        $pic = T::getPhotoFilePath();
        if ($this->page->get->uploadPhoto && $this->page->get->size == strlen($this->page->get->uploadPhoto)) {
            file_put_contents($pic,file_get_contents($this->page->get->uploadPhoto));
            if (file_exists($pic)) {
                $lz_types[$displayorder][0] = $pic;
            }
        }
        $lz_types[$displayorder][1] = $this->page->get->url;
        $lz_types = T::arrayToStringSetting($lz_types);
        $this->updateCommonSetting($skey, $lz_types);
        $this->page->tips();
    }

    /**
     * 查询快递信息
     *
     * @return void
     */
    public function express(){
        $express = T::getObject('Express',array(
            'ebid' => $this->page->config->aljhtx->ebid,
            'ekey' => $this->page->config->aljhtx->ekey,
            'kgs' => $this->page->get->kgs,
            'number' => $this->page->get->number,
        ));

        echo diconv($express->getOrderTracesByJson(), 'utf-8', CHARSET);
        exit;
    }


}