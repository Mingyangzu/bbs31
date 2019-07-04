<?php

/**
 * 为品牌商家不需要登录的页面提供服务支持
 *
 * @author yuxinqi<yuxinqi@vip.qq.com>
 * @version 1.0
 * @link http://docs.liangjianyun.com/
 */


if(!defined('IN_DISCUZ')) {
    exit('Access Denied');
}

class ShopAction{
    public $page;

    public function __construct($page) {
        $this->page = $page;
    }

    /**
     * 单店首页
     *
     * @return void
     */
    public function index(){

        $settings=C::t('#aljbd#aljbd_setting')->range();$is_openarray = array('iswatermark','is_daohang','alipay','malipay','isextcredit','pic','isgo','isnews','isyouh','ispd','isrewrite','islogo','isqq','ista','sjurl','sj_index_lz','time');
        foreach($settings as $k => $v){
            if(in_array($k,$is_openarray)){//开关判断
                if($v['value'] == 1){
                    $_G['cache']['plugin']['aljbd'][$k] = 1;
                }elseif($v['value'] == 2){
                    $_G['cache']['plugin']['aljbd'][$k] = 0;
                }
            }else{
                if($v['value']){
                    $_G['cache']['plugin']['aljbd'][$k] = htmlspecialchars_decode($v['value']);//同名变量，有值覆盖设置中变量
                }
            }
        }

        //首页轮播自助广告-常用设置
        if($settings['aljad_index_lz']['value']) {
            $aljad_index_lz = explode ("\n", str_replace ("\r", "", htmlspecialchars_decode($settings['aljad_index_lz']['value'])));
        }

        //首页轮播自助广告-插件设置
        $sjlz = explode ("\n", str_replace ("\r", "", $this->page->config->aljbd->sj_img_1));
        foreach($sjlz as $key=>$value){
            $arr=explode('|',$value);
            $lz_types[$arr[0]]=$arr;
        }

        //图片导航
        $sj_index_dh = explode ("\n", str_replace ("\r", "", $this->page->config->aljbd->sj_index_dh));
        foreach($sj_index_dh as $key=>$value){
            $arr=explode('|',$value);
            $sj_index_dh_types[]=$arr;
        }

        $this->page->assign('pluginid', 'aljbd'); //插件标识符
        $this->page->assign('sj_index_dh_types', $sj_index_dh_types); //图片导航
        $this->page->assign('lz_types', $lz_types); //轮播广告
        $this->page->assign('settings', $settings); //常用置
        $this->page->display();
    }


}