<?php
if (!defined('IN_DISCUZ')) {
    exit('Access Denied');
}
if($_G['cache']['plugin']['aljbdx']['pay_tips']){
    $groupids=DB::fetch_all('select * from %t where groupid = %d',array('common_member',1));
    $mes = str_replace(array('{username}','{url}','{shopname}'),array($order['username'],$orderlurln,$brand['name']),$_G['cache']['plugin']['aljbdx']['pay_tips']);
    foreach($groupids as $g_uid){
        if($_G['cache']['plugin']['mapp_template'] && $_G['cache']['plugin']['aljhtx'] && DB::fetch_first('select * from %t where type=%s', array('aljhtx_wechat_bindtemplate', 'buyingNotificationBusiness'))){
            $param = array(
                'order_subject' => lang("plugin/aljhtx","admin_send_template_php_1"),
                'order_id' => $orderid,
                'order_price' => $order['price'].lang("plugin/aljhtx","admin_send_template_php_2"),
                'order_username' => $order['username'],
                'order_status' => lang("plugin/aljhtx","admin_send_template_php_3"),
                'order_content' => lang("plugin/aljhtx","admin_send_template_php_4").$brand['name'].lang("plugin/aljhtx","admin_send_template_php_5"),
                'type' => 'buyingNotificationBusiness',
                'news_type' => 1,
                'logo' => $f_goods_info['pic1']
            );

            if($order['commodity_type'] == 2){
                $param['order_subject'] = lang("plugin/aljhtx","admin_send_template_php_6");
                if($order['amount'] == 1 && $order['status']<6){
                    $param['order_status'] = lang("plugin/aljhtx","admin_send_template_php_7");
                }else{
                    $param['order_status'] = lang("plugin/aljhtx","admin_send_template_php_8");
                }
            }else if($aljstg_tips && $order['get_to_the_shop'] == 1){//到店自取
                $param['order_subject'] = lang("plugin/aljhtx","admin_send_template_php_9");
                $param['order_status'] = lang("plugin/aljhtx","admin_send_template_php_10");
            }else if($aljstg_tips && $order['commodity_type'] == 1 && $order['category'] == 1){//团购券
                $param['order_subject'] = lang("plugin/aljhtx","admin_send_template_php_11");
                $param['order_status'] = lang("plugin/aljhtx","admin_send_template_php_12");
            }
            T::aljbd_notification($g_uid['uid'],$param,$orderlurln);
            unset($param);
        }else {
            notification_add($g_uid['uid'], 'system', $mes);
        }
    }
}
?>