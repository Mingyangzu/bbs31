<?php

/**
 * ΪƷ���̼Ҳ���Ҫ��¼��ҳ���ṩ����֧��
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
     * ������ҳ
     *
     * @return void
     */
    public function index(){

        $settings=C::t('#aljbd#aljbd_setting')->range();$is_openarray = array('iswatermark','is_daohang','alipay','malipay','isextcredit','pic','isgo','isnews','isyouh','ispd','isrewrite','islogo','isqq','ista','sjurl','sj_index_lz','time');
        foreach($settings as $k => $v){
            if(in_array($k,$is_openarray)){//�����ж�
                if($v['value'] == 1){
                    $_G['cache']['plugin']['aljbd'][$k] = 1;
                }elseif($v['value'] == 2){
                    $_G['cache']['plugin']['aljbd'][$k] = 0;
                }
            }else{
                if($v['value']){
                    $_G['cache']['plugin']['aljbd'][$k] = htmlspecialchars_decode($v['value']);//ͬ����������ֵ���������б���
                }
            }
        }

        //��ҳ�ֲ��������-��������
        if($settings['aljad_index_lz']['value']) {
            $aljad_index_lz = explode ("\n", str_replace ("\r", "", htmlspecialchars_decode($settings['aljad_index_lz']['value'])));
        }

        //��ҳ�ֲ��������-�������
        $sjlz = explode ("\n", str_replace ("\r", "", $this->page->config->aljbd->sj_img_1));
        foreach($sjlz as $key=>$value){
            $arr=explode('|',$value);
            $lz_types[$arr[0]]=$arr;
        }

        //ͼƬ����
        $sj_index_dh = explode ("\n", str_replace ("\r", "", $this->page->config->aljbd->sj_index_dh));
        foreach($sj_index_dh as $key=>$value){
            $arr=explode('|',$value);
            $sj_index_dh_types[]=$arr;
        }

        $this->page->assign('pluginid', 'aljbd'); //�����ʶ��
        $this->page->assign('sj_index_dh_types', $sj_index_dh_types); //ͼƬ����
        $this->page->assign('lz_types', $lz_types); //�ֲ����
        $this->page->assign('settings', $settings); //������
        $this->page->display();
    }


}