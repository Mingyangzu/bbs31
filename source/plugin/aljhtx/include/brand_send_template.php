<?php
if (!defined('IN_DISCUZ')) {
    exit('Access Denied');
}
if($_G['cache']['plugin'][$pluginid]['notify_merchant']){

    if($_G['cache']['plugin']['mapp_template'] && $_G['cache']['plugin']['aljhtx'] && DB::fetch_first('select * from %t where type=%s', array('aljhtx_wechat_bindtemplate', 'buyingNotificationBusiness'))){
        $param = array(
            'order_subject' => lang("plugin/aljhtx","brand_send_template_php_1"),
            'order_id' => $orderid,
            'order_price' => $order['price'].lang("plugin/aljhtx","brand_send_template_php_2"),
            'order_username' => $order['username'],
            'order_status' => lang("plugin/aljhtx","brand_send_template_php_3"),
            'order_content' => lang("plugin/aljhtx","brand_send_template_php_4").$order['stitle'].lang("plugin/aljhtx","brand_send_template_php_5"),
            'type' => 'buyingNotificationBusiness',
            'news_type' => 1,
            'logo' => $f_goods_info['pic1']
        );
        if($order['commodity_type'] == 2){
            $param['order_subject'] = lang("plugin/aljhtx","brand_send_template_php_6");
            if($order['amount'] == 1 && $order['status']<6){
                $param['order_status'] = lang("plugin/aljhtx","brand_send_template_php_7");
                $param['order_content'] = lang("plugin/aljhtx","brand_send_template_php_8").$order['stitle'].lang("plugin/aljhtx","brand_send_template_php_9");
            }else{
                $param['order_status'] = lang("plugin/aljhtx","brand_send_template_php_10");
                $param['order_content'] = lang("plugin/aljhtx","brand_send_template_php_11").$order['stitle'].lang("plugin/aljhtx","brand_send_template_php_12");
            }
        }else if($aljstg_tips && $order['get_to_the_shop'] == 1){//到店自取
            $param['order_subject'] = lang("plugin/aljhtx","brand_send_template_php_13");
            $param['order_status'] = lang("plugin/aljhtx","brand_send_template_php_14");
            $param['order_content'] = lang("plugin/aljhtx","brand_send_template_php_15").$order['stitle'].lang("plugin/aljhtx","brand_send_template_php_16");
        }else if($aljstg_tips && $order['commodity_type'] == 1 && $order['category'] == 1){//团购券
            $param['order_subject'] = lang("plugin/aljhtx","brand_send_template_php_17");
            $param['order_status'] = lang("plugin/aljhtx","brand_send_template_php_18");
            $param['order_content'] = lang("plugin/aljhtx","brand_send_template_php_19").$order['stitle'].lang("plugin/aljhtx","brand_send_template_php_20");
        }
        T::aljbd_notification($brand['uid'],$param,$orderlurln.'&ord=dianp');
    }else{
        notification_add($brand['uid'],'system',str_replace('{orderid}','<a href="'.$orderlurln.'&ord=dianp'.'">'.$orderid.'</a>',str_replace('{username}',$brand['username'],str_replace('{name}',$order['username'],str_replace('{shopname}',$order['stitle'],$_G['cache']['plugin'][$pluginid]['notify_merchant'])))));
    }
}
?>