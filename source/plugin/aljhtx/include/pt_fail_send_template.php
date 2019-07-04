<?php
if (!defined('IN_DISCUZ')) {
    exit('Access Denied');
}
if ($_G['cache']['plugin']['mapp_template'] && $_G['cache']['plugin']['aljhtx']
    && DB::fetch_first('select * from %t where type=%s', array('aljhtx_wechat_bindtemplate', 'notificationOfSpellingFailure'))) {
    $f_goods_info = C::t('#aljbd#aljbd_goods')->fetch($goods_id);
    require_once DISCUZ_ROOT .'source/plugin/aljhtx/class/class_aljhtx.php';
    $param = array(
        'order_subject' => lang("plugin/aljhtx","pt_fail_send_template_php_1"),
        'title' => $order['stitle'],
        'order_price' => $order['price'],
        'refund_price' => $order['price'],
        'order_content' => lang("plugin/aljhtx","pt_fail_send_template_php_2"),
        'type' => 'notificationOfSpellingFailure',
        'news_type' => 1,
        'logo' => $f_goods_info['pic1']
    );
    T::aljbd_notification($order['uid'],$param,'plugin.php?id=aljqb&act=account');
}
?>