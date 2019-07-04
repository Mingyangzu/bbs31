<?php
if (!defined('IN_DISCUZ')) {
    exit('Access Denied');
}
if($_G['cache']['plugin']['aljbdx']['fahuo_tips']) {
    $mes = str_replace(array('{username}','{url}','{shopname}'),array($order['username'],$orderlurln,$brand['name']),$_G['cache']['plugin']['aljbdx']['fahuo_tips']);
    $groupids = DB::fetch_all('select * from %t where groupid = %d', array('common_member', 1));
    foreach ($groupids as $g_uid) {
        if($_G['cache']['plugin']['mapp_template'] && $_G['cache']['plugin']['aljhtx'] && DB::fetch_first('select * from %t where type=%s', array('aljhtx_wechat_bindtemplate', 'deliveryNotificationUser'))){
            if($regionlist[$address['region']][name]){
                $addresstext = $regionlist[$address['region']][name].$regionlist[$address['region1']][name].$regionlist[$address['region2']][name].$address['addressDetail'];
            }else{
                $addresstext = $address['province'].$address['city'].$address['district'].$address['addressDetail'];
            }
            $param = array(
                'order_subject' => lang("plugin/aljhtx","delivery_admin_send_template_php_1"),
                'order_id' => $order['orderid'],
                'fh_time' => dgmdate(TIMESTAMP,'Y-m-d H:i:s'),
                'kd' => $express_company[$_GET['companyname']],
                'kd_order' => $_GET['worderid'],
                'sh_address' => $addresstext,
                'order_content' => $order['stitle'],
                'type' => 'deliveryNotificationUser',
                'news_type' => 1,
                'logo' => $f_goods_info['pic1']
            );
            T::aljbd_notification($g_uid['uid'],$param,'plugin.php?id=aljht&act=admin&op=orderlist&do=logistics_details&kgs=' . $_GET['companyname'] . '&number=' . $_GET['worderid'] . '&orderid=' . $_GET['orderid']);
        }else {
            notification_add($g_uid['uid'], 'system', $mes);
        }
    }
}
?>