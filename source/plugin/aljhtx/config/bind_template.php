<?php

/**
 *消息配置文件
 *
 * @author yuxinqi<yuxinqi@vip.qq.com>
 * @version 1.0
 * @link http://docs.liangjianyun.com?/
 */


if(!defined('IN_DISCUZ')) {
    exit('Access Denied');
}

return array(
    'system'=>array(
        'name' => lang("plugin/aljhtx","bind_template_php_1"),
        'cols' => array(
            'dateline' => lang("plugin/aljhtx","bind_template_php_2"),
            'author' => lang("plugin/aljhtx","bind_template_php_3"),
            'username' => lang("plugin/aljhtx","bind_template_php_4"),
            'type' => lang("plugin/aljhtx","bind_template_php_5"),
            'content' => lang("plugin/aljhtx","bind_template_php_6"),
        ),
    ),
    'reply'=>array(
        'name' => lang("plugin/aljhtx","bind_template_php_7"),
        'cols' => array(
            'title' => lang("plugin/aljhtx","bind_template_php_8"),
            'type' => lang("plugin/aljhtx","bind_template_php_9"),
            'username' => lang("plugin/aljhtx","bind_template_php_10"),
            'fromusername' => lang("plugin/aljhtx","bind_template_php_11"),
            'content' => lang("plugin/aljhtx","bind_template_php_12"),
            'posttime' => lang("plugin/aljhtx","bind_template_php_13"),
            'replytime' => lang("plugin/aljhtx","bind_template_php_14"),
        ),
    ),
    'ask'=>array(
        'name' => lang("plugin/aljhtx","bind_template_php_15"),
        'cols' => array(
            'id' => lang("plugin/aljhtx","bind_template_php_16"),
            'status' => lang("plugin/aljhtx","bind_template_php_17"),
            'author' => lang("plugin/aljhtx","bind_template_php_18"),
            'content' => lang("plugin/aljhtx","bind_template_php_19"),
            'dateline' => lang("plugin/aljhtx","bind_template_php_20"),
        )
    ),
    'new'=>array(
        'name' => lang("plugin/aljhtx","bind_template_php_21"),
        'cols' => array(
            'id' => lang("plugin/aljhtx","bind_template_php_22"),
            'status' => lang("plugin/aljhtx","bind_template_php_23"),
            'author' => lang("plugin/aljhtx","bind_template_php_24"),
            'content' => lang("plugin/aljhtx","bind_template_php_25"),
            'dateline' => lang("plugin/aljhtx","bind_template_php_26"),
        )
    ),
    'buyNotificationUser'=>array(
        'name' => lang("plugin/aljhtx","bind_template_php_27"),
        'cols' => array(
            'order_subject' => lang("plugin/aljhtx","bind_template_php_28"),
            'order_title' => lang("plugin/aljhtx","bind_template_php_29"),
            'order_id' => lang("plugin/aljhtx","bind_template_php_30"),
            'order_price' => lang("plugin/aljhtx","bind_template_php_31"),
            'order_paytime' => lang("plugin/aljhtx","bind_template_php_32"),
            'order_content' => lang("plugin/aljhtx","bind_template_php_33"),
        )
    ),
    'buyingNotificationBusiness'=>array(
        'name' => lang("plugin/aljhtx","bind_template_php_34"),
        'cols' => array(
            'order_subject' => lang("plugin/aljhtx","bind_template_php_35"),
            'order_id' => lang("plugin/aljhtx","bind_template_php_36"),
            'order_price' => lang("plugin/aljhtx","bind_template_php_37"),
            'order_username' => lang("plugin/aljhtx","bind_template_php_38"),
            'order_status' => lang("plugin/aljhtx","bind_template_php_39"),
            'order_content' => lang("plugin/aljhtx","bind_template_php_40"),
        )
    ),
    'deliveryNotificationUser'=>array(
        'name' => lang("plugin/aljhtx","bind_template_php_41"),
        'cols' => array(
            'order_subject' => lang("plugin/aljhtx","bind_template_php_42"),
            'order_id' => lang("plugin/aljhtx","bind_template_php_43"),
            'fh_time' => lang("plugin/aljhtx","bind_template_php_44"),
            'kd' => lang("plugin/aljhtx","bind_template_php_45"),
            'kd_order' => lang("plugin/aljhtx","bind_template_php_46"),
            'sh_address' => lang("plugin/aljhtx","bind_template_php_47"),
            'order_content' => lang("plugin/aljhtx","bind_template_php_48"),
        )
    ),
    'pickUpCode'=>array(
        'name' => lang("plugin/aljhtx","bind_template_php_49"),
        'cols' => array(
            'order_subject' => lang("plugin/aljhtx","bind_template_php_50"),
            'order_id' => lang("plugin/aljhtx","bind_template_php_51"),
            'dd_code' => lang("plugin/aljhtx","bind_template_php_52"),
            'title' => lang("plugin/aljhtx","bind_template_php_53"),
            'tel' => lang("plugin/aljhtx","bind_template_php_54"),
            'dd_address' => lang("plugin/aljhtx","bind_template_php_55"),
            'order_content' => lang("plugin/aljhtx","bind_template_php_56"),
        )
    ),
    'groupBuyingCoupon'=>array(
        'name' => lang("plugin/aljhtx","bind_template_php_57"),
        'cols' => array(
            'order_subject' => lang("plugin/aljhtx","bind_template_php_58"),
            'order_id' => lang("plugin/aljhtx","bind_template_php_59"),
            'fh_time' => lang("plugin/aljhtx","bind_template_php_60"),
            'kd' => lang("plugin/aljhtx","bind_template_php_61"),
            'kd_order' => lang("plugin/aljhtx","bind_template_php_62"),
            'sh_address' => lang("plugin/aljhtx","bind_template_php_63"),
            'order_content' => lang("plugin/aljhtx","bind_template_php_64"),
        )
    ),
    'participationInSuccessfulSpellingNotice'=>array(
        'name' => lang("plugin/aljhtx","bind_template_php_65"),
        'cols' => array(
            'order_subject' => lang("plugin/aljhtx","bind_template_php_66"),
            'title' => lang("plugin/aljhtx","bind_template_php_67"),
            'order_time' => lang("plugin/aljhtx","bind_template_php_68"),
            'order_content' => lang("plugin/aljhtx","bind_template_php_69"),
        )
    ),
    'successfulSpellingNotice'=>array(
        'name' => lang("plugin/aljhtx","bind_template_php_70"),
        'cols' => array(
            'order_subject' => lang("plugin/aljhtx","bind_template_php_71"),
            'title' => lang("plugin/aljhtx","bind_template_php_72"),
            'order_member' => lang("plugin/aljhtx","bind_template_php_73"),
            'order_content' => lang("plugin/aljhtx","bind_template_php_74"),
        )
    ),
    'notificationOfSpellingFailure'=>array(
        'name' => lang("plugin/aljhtx","bind_template_php_75"),
        'cols' => array(
            'order_subject' => lang("plugin/aljhtx","bind_template_php_76"),
            'title' => lang("plugin/aljhtx","bind_template_php_77"),
            'order_price' => lang("plugin/aljhtx","bind_template_php_78"),
            'refund_price' => lang("plugin/aljhtx","bind_template_php_79"),
            'order_content' => lang("plugin/aljhtx","bind_template_php_80"),
        )
    ),
    'successfulReminderOfTheStartOfTheMission'=>array(
        'name' => lang("plugin/aljhtx","bind_template_php_81"),
        'cols' => array(
            'order_subject' => lang("plugin/aljhtx","bind_template_php_82"),
            'title' => lang("plugin/aljhtx","bind_template_php_83"),
            'order_price' => lang("plugin/aljhtx","bind_template_php_84"),
            'colonel' => lang("plugin/aljhtx","bind_template_php_85"),
            'group_number' => lang("plugin/aljhtx","bind_template_php_86"),
            'deadline' => lang("plugin/aljhtx","bind_template_php_87"),
            'order_content' => lang("plugin/aljhtx","bind_template_php_88"),
        )
    ),
    'transactionReminder'=>array(
        'name' => lang("plugin/aljhtx","bind_template_php_89"),
        'cols' => array(
            'order_subject' => lang("plugin/aljhtx","bind_template_php_90"),
            'trade_time' => lang("plugin/aljhtx","bind_template_php_91"),
            'trade_price' => lang("plugin/aljhtx","bind_template_php_92"),
            'trade_orderid' => lang("plugin/aljhtx","bind_template_php_93"),
            'order_content' => lang("plugin/aljhtx","bind_template_php_94"),
        )
    ),
);