<?php
if (!defined('IN_DISCUZ')) {
    exit('Access Denied');
}
if($order['commodity_type'] == 2
    && $_G['cache']['plugin']['mapp_template']
    && $_G['cache']['plugin']['aljhtx']
    && (DB::fetch_first('select * from %t where type=%s', array('aljhtx_wechat_bindtemplate', 'participationInSuccessfulSpellingNotice'))
        || DB::fetch_first('select * from %t where type=%s', array('aljhtx_wechat_bindtemplate', 'successfulSpellingNotice'))
        || DB::fetch_first('select * from %t where type=%s', array('aljhtx_wechat_bindtemplate', 'successfulReminderOfTheStartOfTheMission')))
){
    $group=DB::fetch_first('select * from %t where grouporderid=%s ',array('aljspt_collage_order', $order['collage_order']));
    if($group['groupheaduid'] != $order['uid']) {//参团
        if ($order['amount'] == 1 && $order['status']<6 && DB::fetch_first('select * from %t where type=%s', array('aljhtx_wechat_bindtemplate', 'successfulSpellingNotice'))) {//拼单成功通知
            $pt_ordergroup_tmp=DB::fetch_all('select * from %t where collage_order=%s and status=%d order by confirmdate asc',array('aljbd_goods_order',$order['collage_order'],2));
            $pt_username = array();
            foreach($pt_ordergroup_tmp as $tmp_key => $tmp_value){
                $pt_username[] = $tmp_value['username'];
            }
            $param = array(
                'order_subject' => '恭喜您拼单成功，下次再来购买哦~',
                'title' => $order['stitle'],
                'order_member' => implode(lang("plugin/aljhtx","purchaser_send_template_php_1"),$pt_username),
                'order_content' => lang("plugin/aljhtx","purchaser_send_template_php_2"),
                'type' => 'successfulSpellingNotice',
                'news_type' => 1,
                'logo' => $f_goods_info['pic1']
            );
            foreach($pt_ordergroup_tmp as $tmp_key => $tmp_value){
                T::aljbd_notification($tmp_value['uid'],$param,$orderlurln);
            }

        }else if (DB::fetch_first('select * from %t where type=%s', array('aljhtx_wechat_bindtemplate', 'participationInSuccessfulSpellingNotice'))) {//参加拼单成功通知
            $param = array(
                'order_subject' => '你已成功参团，快去分享给好友一起拼单吧，谢谢~',
                'title' => $order['stitle'],
                'order_time' => dgmdate($order['confirmdate'],'Y-m-d H:i:s'),
                'order_content' => lang("plugin/aljhtx","purchaser_send_template_php_3"),
                'type' => 'participationInSuccessfulSpellingNotice',
                'news_type' => 1,
                'logo' => $f_goods_info['pic1']
            );
            T::aljbd_notification($order['uid'],$param,$orderlurln);
        }
    }else{//开团
        if (DB::fetch_first('select * from %t where type=%s', array('aljhtx_wechat_bindtemplate', 'successfulReminderOfTheStartOfTheMission'))) {
            $group=DB::fetch_first('select * from %t where grouporderid=%s ',array('aljspt_collage_order', $order['collage_order']));
            $grouptime=$_G['cache']['plugin']['aljspt']['grouptime']?intval($_G['cache']['plugin']['aljspt']['grouptime'])*3600 : 86400;
            $param = array(
                'order_subject' => '你好，恭喜你开团成功，快去分享给好友一起拼单吧~',
                'title' => $order['stitle'],
                'order_price' => $order['price'],
                'colonel' => $group['groupheadusername'],
                'group_number' => $group['groupnum'],
                'deadline' => dgmdate($group['create_time']+$grouptime,'Y-m-d H:i:s'),
                'order_content' => lang("plugin/aljhtx","purchaser_send_template_php_4"),
                'type' => 'successfulReminderOfTheStartOfTheMission',
                'news_type' => 1,
                'logo' => $f_goods_info['pic1']
            );
            T::aljbd_notification($order['uid'],$param,$orderlurln);
        }
    }
}else if($_G['cache']['plugin']['mapp_template'] && $_G['cache']['plugin']['aljhtx'] && DB::fetch_first('select * from %t where type=%s',
        array(
            'aljhtx_wechat_bindtemplate',
            'buyNotificationUser'
        ))){
    $param = array(
        'order_subject' => lang("plugin/aljhtx","purchaser_send_template_php_5"),
        'order_title' => $order['stitle'],
        'order_id' => $orderid,
        'order_price' => $order['price'].lang("plugin/aljhtx","purchaser_send_template_php_6"),
        'order_paytime' => dgmdate($order['confirmdate'],'Y-m-d H:i:s'),
        'order_content' => lang("plugin/aljhtx","purchaser_send_template_php_7"),
        'type' => 'buyNotificationUser',
        'news_type' => 1,
        'logo' => $f_goods_info['pic1']
    );
    if($order['commodity_type'] == 2){
        $param['order_subject'] = lang("plugin/aljhtx","purchaser_send_template_php_8");
        if($order['amount'] == 1 && $order['status']<6){
            $param['order_subject'] .= lang("plugin/aljhtx","purchaser_send_template_php_9");
        }else{
            $param['order_subject'] .= lang("plugin/aljhtx","purchaser_send_template_php_10");
        }
    }
    T::aljbd_notification($order['uid'],$param,$orderlurln);
}else{
    notification_add($order['uid'], 'system', str_replace('{username}', $order['username'], str_replace('{shopname}', $order['stitle'], $_G['cache']['plugin'][$pluginid]['suctips'])) . ' <a href="' . $orderlurln . '">' . lang('plugin/aljgwc', 'View_orders') . '</a>');
}
?>