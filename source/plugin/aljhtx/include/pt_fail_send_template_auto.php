<?php
if (!defined('IN_DISCUZ')) {
    exit('Access Denied');
}
if ($_G['cache']['plugin']['mapp_template'] && $_G['cache']['plugin']['aljhtx']
    && DB::fetch_first('select * from %t where type=%s', array('aljhtx_wechat_bindtemplate', 'notificationOfSpellingFailure'))) {
    require_once DISCUZ_ROOT .'source/plugin/aljhtx/class/class_aljhtx.php';
    $goods_id = DB::result_first('select goods_id from %t where orderid=%s',array('aljbd_goods_order_list',$pt_order['orderid']));
    $f_goods_info = C::t('#aljbd#aljbd_goods')->fetch($goods_id);
    $param = array(
        'order_subject' => lang("plugin/aljhtx","pt_fail_send_template_auto_php_1"),
        'title' => $pt_order['stitle'],
        'order_price' => $pt_order['price'],
        'refund_price' => $pt_order['price'],
        'order_content' => lang("plugin/aljhtx","pt_fail_send_template_auto_php_2"),
        'type' => 'notificationOfSpellingFailure',
        'news_type' => 1,
        'logo' => $f_goods_info['pic1']
    );
    T::aljbd_notification($pt_order['uid'],$param,'plugin.php?id=aljqb&act=account');
}
?>