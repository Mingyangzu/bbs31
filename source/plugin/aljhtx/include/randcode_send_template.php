<?php
if (!defined('IN_DISCUZ')) {
    exit('Access Denied');
}
$mes = str_replace(array('{goodsname}','{code}','{url}'),array($order['stitle'],$grouponpw,'plugin.php?id=aljbd&act=orderlist&orderid='.$order['orderid']),$replacetext);
if($_G['cache']['plugin']['mapp_template'] && $_G['cache']['plugin']['aljhtx'] && DB::fetch_first('select * from %t where type=%s',
        array(
            'aljhtx_wechat_bindtemplate',
            'pickUpCode'
        )) && $type == 1){
    $param = array(
        'order_subject' => lang("plugin/aljhtx","randcode_send_template_php_1"),
        'order_id' => $order['orderid'],
        'dd_code' => $grouponpw,
        'title' => $order['stitle'],
        'tel' => $brand['tel'],
        'dd_address' => $brand['addr'].lang("plugin/aljhtx","randcode_send_template_php_2").$brand['name'].lang("plugin/aljhtx","randcode_send_template_php_3"),
        'order_content' => lang("plugin/aljhtx","randcode_send_template_php_4"),
        'type' => 'pickUpCode',
        'news_type' => 1,
        'logo' => $f_goods_info['pic1']
    );
    if($order['commodity_type'] == 2){
        $param['order_subject'] = lang("plugin/aljhtx","randcode_send_template_php_5");
    }
    T::aljbd_notification($order['uid'],$param,'plugin.php?id=aljbd&act=orderlist&orderid='.$order['orderid']);
}else if($_G['cache']['plugin']['mapp_template'] && $_G['cache']['plugin']['aljhtx'] && DB::fetch_first('select * from %t where type=%s',
        array(
            'aljhtx_wechat_bindtemplate',
            'groupBuyingCoupon'
        ))){
    $param = array(
        'order_subject' => lang("plugin/aljhtx","randcode_send_template_php_6").lang("plugin/aljhtx","randcode_send_template_php_7").$order['stitle'].lang("plugin/aljhtx","randcode_send_template_php_8"),
        'order_id' => $order['orderid'],
        'fh_time' => $brand['name'],
        'kd' => $grouponpw,
        'kd_order' => $order['price'],
        'sh_address' => $_GET['paytime'] ? dgmdate($_GET['paytime'],'Y-m-d H:i:s') : dgmdate($order['confirmdate'],'Y-m-d H:i:s'),
        'order_content' => lang("plugin/aljhtx","randcode_send_template_php_9"),
        'type' => 'groupBuyingCoupon',
        'news_type' => 1,
        'logo' => $f_goods_info['pic1']
    );
    T::aljbd_notification($order['uid'],$param,'plugin.php?id=aljbd&act=orderlist&orderid='.$order['orderid']);
}else {
    notification_add($order['uid'], 'system', $mes, array('from_id' => $goods_id, 'from_idtype' => 'aljbd'));
}
?>