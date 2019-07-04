<?php

/**
 *一级菜单配置文件
 *
 * @author yuxinqi<yuxinqi@vip.qq.com>
 * @version 1.0
 * @link http://docs.liangjianyun.com?/
 */


if(!defined('IN_DISCUZ')) {
    exit('Access Denied');
}
foreach(DB::fetch_all('select * from %t',array('common_plugin')) as $c_pv){
    $c_parray[$c_pv['identifier']] = $c_pv;
}
return array(
    '1' => array(
        'name' => 'DIY',
        'icon' => '<i class="fa fa-thumbs-o-up"></i>',
        'url' => 'plugin.php?id=aljhtx&c=diy&a=setting&ajax=yes',
        'is_open' => '1',
    ),
    '2' => array(
        'name' => lang("plugin/aljhtx","first_nav_php_1"),
        'icon' => '<i class="fa fa-twitter"></i>',
        'url' => 'plugin.php?id=aljhtx&c=user&a=user&ajax=yes',
        'is_open' => '1',
    ),
    '6' => array(
        'name' => lang("plugin/aljhtx","first_nav_php_2"),
        'icon' => '<i class="fa fa-weixin"></i>',
        'url' => 'plugin.php?id=aljhtx&c=wechat&a=gettemplatelist&ajax=yes',
        'is_open'=>'1',
    ),
    '8' => array(
        'name' => lang("plugin/aljhtx","first_nav_php_3"),
        'icon' => '<i class="fa fa-qrcode"></i>',
        'url' => 'plugin.php?id=aljhtx&c=qrcode&a=common&ajax=yes',
        'is_open' => '1',
    ),
    '7' => array(
        'name' => lang("plugin/aljhtx","first_nav_php_4"),
        'icon' => '<i class="fa fa-commenting-o"></i>',
        'url' => 'plugin.php?id=aljhtx&c=notification&a=getlist&ajax=yes',
        'is_open' => '1',
    ),
    '4' => array(
        'name' => lang("plugin/aljhtx","first_nav_php_5"),
        'icon' => '<i class="fa fa-file-code-o"></i>',
        'url' => 'plugin.php?id=aljhtx&c=aljbd&a=order&ajax=yes',
        'is_open' => '1',
        'nav' => array(
            '0' => array(
                'name' => lang("plugin/aljhtx","first_nav_php_6"),
                'icon' => '<i class="fa fa-file-code-o"></i>',
                'url' => 'plugin.php?id=aljht&act=admin&op=orderlist&ajax=yes',
                'is_open' => '1',
                'navlist' => array(
                    '0' => array(
                        'name' => lang("plugin/aljhtx","first_nav_php_7"),
                        'icon' => '',
                        'url' => 'plugin.php?id=aljht&act=admin&op=orderlist&ajax=yes',
                        'is_open' => '1',
                    ),
                    '1' => array(
                        'name' => lang("plugin/aljhtx","first_nav_php_8"),
                        'icon' => '',
                        'url' => 'plugin.php?id=aljht&act=admin&op=orderlist&status=1&ord=&ajax=yes',
                        'is_open' => '1',
                    ),
                    '2' => array(
                        'name' => lang("plugin/aljhtx","first_nav_php_9"),
                        'icon' => '',
                        'url' => 'plugin.php?id=aljht&act=admin&op=orderlist&status=2&ord=&ajax=yes',
                        'is_open' => '1',
                    ),
                    '3' => array(
                        'name' => lang("plugin/aljhtx","first_nav_php_10"),
                        'icon' => '',
                        'url' => 'plugin.php?id=aljht&act=admin&op=orderlist&status=3&ord=&ajax=yes',
                        'is_open' => '1',
                    ),
                    '4' => array(
                        'name' => lang("plugin/aljhtx","first_nav_php_11"),
                        'icon' => '',
                        'url' => 'plugin.php?id=aljht&act=admin&op=orderlist&status=4&ord=&ajax=yes',
                        'is_open' => '1',
                    ),
                    '5' => array(
                        'name' => lang("plugin/aljhtx","first_nav_php_12"),
                        'icon' => '',
                        'url' => 'plugin.php?id=aljht&act=admin&op=orderlist&status=5&ord=&ajax=yes',
                        'is_open' => '1',
                    ),
                )
            ),
            '1' => array(
                'name' => lang("plugin/aljhtx","first_nav_php_13"),
                'icon' => '<i class="fa fa-file-code-o"></i>',
                'url' => 'plugin.php?id=aljht&act=admin&op=refundlist&state=1&ajax=yes',
                'is_open' => '1',
                'navlist' => array(
                    '0' => array(
                        'name' => lang("plugin/aljhtx","first_nav_php_14"),
                        'icon' => '',
                        'url' => 'plugin.php?id=aljht&act=admin&op=refundlist&state=1&ajax=yes',
                        'is_open' => '1',
                    ),
                    '1' => array(
                        'name' => lang("plugin/aljhtx","first_nav_php_15"),
                        'icon' => '',
                        'url' => 'plugin.php?id=aljht&act=admin&op=refundlist&state=2&ajax=yes',
                        'is_open' => '1',
                    ),
                    '2' => array(
                        'name' => lang("plugin/aljhtx","first_nav_php_16"),
                        'icon' => '',
                        'url' => 'plugin.php?id=aljht&act=admin&op=refundlist&state=3&ajax=yes',
                        'is_open' => '1',
                    ),
                    '3' => array(
                        'name' => lang("plugin/aljhtx","first_nav_php_17"),
                        'icon' => '',
                        'url' => 'plugin.php?id=aljht&act=admin&op=refundlist&state=4&ajax=yes',
                        'is_open' => '1',
                    ),
                    '4' => array(
                        'name' => lang("plugin/aljhtx","first_nav_php_18"),
                        'icon' => '',
                        'url' => 'plugin.php?id=aljht&act=admin&op=refundlist&state=5&ajax=yes',
                        'is_open' => '1',
                    )
                )
            ),
            '2' => array(
                'name' => lang("plugin/aljhtx","first_nav_php_19"),
                'icon' => '<i class="fa fa-file-code-o"></i>',
                'url' => 'plugin.php?id=aljhtx&c=qrcode&a=common&ajax=yes&ajax=yes',
                'is_open' => '1',
                'navlist' => array(
                    '0' => array(
                        'name' => lang("plugin/aljhtx","first_nav_php_20"),
                        'icon' => '',
                        'url' => 'plugin.php?id=aljhtx&c=express&a=express_company&ajax=yes',
                        'is_open' => '1',
                    )
                )
            )
        )
    ),
    '23' => array(
        'name' => lang("plugin/aljhtx","first_nav_php_21"),
        'icon' => '<i class="fa fa-file-code-o"></i>',
        'url' => 'plugin.php?id=aljhtx&c=aljbd&a=order&ajax=yes',
        'is_open' => '1',
        'nav' => array(
            '0' => array(
                'name' => lang("plugin/aljhtx","first_nav_php_22"),
                'icon' => '<i class="fa fa-file-code-o"></i>',
                'url' => 'plugin.php?id=aljht&act=admin&op=distribution&do=shoplist&status=1&ajax=yes',
                'is_open' => '1',
                'navlist' => array(
                    '0' => array(
                        'name' => lang("plugin/aljhtx","first_nav_php_23"),
                        'icon' => '',
                        'url' => 'plugin.php?id=aljht&act=admin&op=distribution&do=shoplist&status=1&ajax=yes',
                        'is_open' => '1',
                    ),
                    '1' => array(
                        'name' => lang("plugin/aljhtx","first_nav_php_24"),
                        'icon' => '',
                        'url' => 'plugin.php?id=aljht&act=admin&op=distribution&ajax=yes',
                        'is_open' => '1',
                    ),
                    '2' => array(
                        'name' => lang("plugin/aljhtx","first_nav_php_25"),
                        'icon' => '',
                        'url' => 'plugin.php?id=aljht&act=admin&op=distribution&do=cashlog&ajax=yes',
                        'is_open' => '1',
                    ),
                    '3' => array(
                        'name' => lang("plugin/aljhtx","first_nav_php_26"),
                        'icon' => '',
                        'url' => 'plugin.php?id=aljht&act=admin&op=distribution&do=order&ajax=yes',
                        'is_open' => '1',
                    ),
                    '4' => array(
                        'name' => lang("plugin/aljhtx","first_nav_php_27"),
                        'icon' => '',
                        'url' => 'plugin.php?id=aljht&act=admin&op=distribution&do=userlist&ajax=yes',
                        'is_open' => '1',
                    ),
                    '10' => array(
                        'name' => lang("plugin/aljhtx","first_nav_php_28"),
                        'icon' => '',
                        'url' => 'plugin.php?id=aljht&act=admin&op=help&ajax=yes',
                        'is_open' => '1',
                    )
                )
            )
        )
    ),
    '30' => array(
        'name' => lang("plugin/aljhtx","first_nav_php_29"),
        'icon' => '<i class="fa fa-file-code-o"></i>',
        'url' => 'plugin.php?id=aljhtx&c=aljbd&a=order&ajax=yes',
        'is_open' => $c_parray['aljms']?'1':'0',
        'nav' => array(
            '0' => array(
                'name' => lang("plugin/aljhtx","first_nav_php_30"),
                'icon' => '<i class="fa fa-file-code-o"></i>',
                'url' => 'plugin.php?id=aljhtx&c=activity&a=ms&ajax=yes',
                'is_open' => '1',
                'navlist' => array(
                    '0' => array(
                        'name' => lang("plugin/aljhtx","first_nav_php_31"),
                        'icon' => '',
                        'url' => 'plugin.php?id=aljhtx&c=activity&a=ms&ajax=yes',
                        'is_open' => '1',
                    ),
                )
            )
        )
    ),
    '5' => array(
        'name' => lang("plugin/aljhtx","first_nav_php_32"),
        'icon' => '<i class="fa fa-file-code-o"></i>',
        'url' => 'plugin.php?id=aljhtx&c=aljbd&a=order&ajax=yes',
        'is_open' => '1',
        'nav' => array(
            '0' => array(
                'name' => lang("plugin/aljhtx","first_nav_php_33"),
                'icon' => '<i class="fa fa-file-code-o"></i>',
                'url' => 'plugin.php?id=aljht&act=admin&op=brand&do=yes&ajax=yes',
                'is_open' => '1',
                'navlist' => array(
                    '0' => array(
                        'name' => lang("plugin/aljhtx","first_nav_php_34"),
                        'icon' => '',
                        'url' => 'plugin.php?id=aljht&act=admin&op=brand&do=yes&ajax=yes',
                        'is_open' => '1',
                    ),
                    '8' => array(
                        'name' => lang("plugin/aljhtx","first_nav_php_35").'VIP'.lang("plugin/aljhtx","first_nav_php_36"),
                        'icon' => '',
                        'url' => 'plugin.php?id=aljht&act=admin&op=vip&ajax=yes',
                        'is_open' => '1',
                    ),
                    '13' => array(
                        'name' => lang("plugin/aljhtx","first_nav_php_37"),
                        'icon' => '',
                        'url' => 'plugin.php?id=aljbzj&c=aljbzj&a=bondLog&ajax=yes&status=2&i=1',
                        'is_open' => $c_parray['aljbzj']?'1':'0',
                    ),
                    '7' => array(
                        'name' => lang("plugin/aljhtx","first_nav_php_38"),
                        'icon' => '',
                        'url' => 'plugin.php?id=aljht&act=admin&op=consume&ajax=yes',
                        'is_open' => '1',
                    ),
                    '10' => array(
                        'name' => lang("plugin/aljhtx","first_nav_php_39"),
                        'icon' => '',
                        'url' => 'plugin.php?id=aljht&act=admin&op=videolist&ajax=yes',
                        'is_open' => '1',
                    ),
                    '12' => array(
                        'name' => lang("plugin/aljhtx","first_nav_php_40"),
                        'icon' => '',
                        'url' => 'plugin.php?id=aljht&act=admin&op=aljbd&do=setting&identity=aljbd&do=brandtype&link=1&ajax=yes',
                        'is_open' => '1',
                    ),
                    '4' => array(
                        'name' => lang("plugin/aljhtx","first_nav_php_41"),
                        'icon' => '',
                        'url' => 'plugin.php?id=aljht&act=admin&op=album&ajax=yes',
                        'is_open' => '1',
                    ),
                    '9' => array(
                        'name' => lang("plugin/aljhtx","first_nav_php_42"),
                        'icon' => '',
                        'url' => 'plugin.php?id=aljht&act=admin&op=appointment_list&ajax=yes',
                        'is_open' => '1',
                    ),
                    '5' => array(
                        'name' => lang("plugin/aljhtx","first_nav_php_43"),
                        'icon' => '',
                        'url' => 'plugin.php?id=aljht&act=admin&op=notice&ajax=yes',
                        'is_open' => '1',
                    ),
                    '1' => array(
                        'name' => lang("plugin/aljhtx","first_nav_php_44"),
                        'icon' => '',
                        'url' => 'plugin.php?id=aljht&act=admin&op=brand&do=reply&ajax=yes',
                        'is_open' => '1',
                    ),
                    '3' => array(
                        'name' => lang("plugin/aljhtx","first_nav_php_45"),
                        'icon' => '',
                        'url' => 'plugin.php?id=aljht&act=admin&op=attestation&ajax=yes',
                        'is_open' => '1',
                    ),
                    '6' => array(
                        'name' => lang("plugin/aljhtx","first_nav_php_46"),
                        'icon' => '',
                        'url' => 'plugin.php?id=aljht&act=admin&op=rubbish&do=brand&ajax=yes',
                        'is_open' => '1',
                    ),
                )
            )
        )
    ),
    '3' => array(
        'name' => lang("plugin/aljhtx","first_nav_php_47"),
        'icon' => '<i class="fa fa-file-code-o"></i>',
        'url' => 'plugin.php?id=aljhtx&c=aljbd&a=order&ajax=yes',
        'is_open' => '1',
        'nav' => array(
            '0' => array(
                'name' => lang("plugin/aljhtx","first_nav_php_48"),
                'icon' => '<i class="fa fa-file-code-o"></i>',
                'url' => 'plugin.php?id=aljht&act=admin&op=goods&ajax=yes',
                'is_open' => '1',
                'navlist' => array(
                    '0' => array(
                        'name' => lang("plugin/aljhtx","first_nav_php_49"),
                        'icon' => '',
                        'url' => 'plugin.php?id=aljht&act=admin&op=goods&ajax=yes',
                        'is_open' => '1',
                    ),
                    '1' => array(
                        'name' => lang("plugin/aljhtx","first_nav_php_50"),
                        'icon' => '',
                        'url' => 'plugin.php?id=aljht&act=admin&op=ather&ajax=yes',
                        'is_open' => '1',
                    ),
                    '2' => array(
                        'name' => lang("plugin/aljhtx","first_nav_php_51"),
                        'icon' => '',
                        'url' => 'plugin.php?id=aljht&act=admin&op=aljbd&do=brandtype&identity=aljbd&status=goods&link=1&ajax=yes',
                        'is_open' => '1',
                    ),
                    '3' => array(
                        'name' => lang("plugin/aljhtx","first_nav_php_52"),
                        'icon' => '',
                        'url' => 'plugin.php?id=aljht&act=admin&op=rubbish&do=goods&ajax=yes',
                        'is_open' => '1',
                    ),
                    '4' => array(
                        'name' => lang("plugin/aljhtx","first_nav_php_53"),
                        'icon' => '',
                        'url' => 'plugin.php?id=aljht&act=admin&op=goods&do=reply&ajax=yes',
                        'is_open' => '1',
                    ),
                )
            )
        )
    ),
    '10' => array(
        'name' => lang("plugin/aljhtx","first_nav_php_54"),
        'icon' => '<i class="fa fa-file-code-o"></i>',
        'url' => 'plugin.php?id=aljhtx&c=aljbd&a=order&ajax=yes',
        'is_open' => '1',
        'nav' => array(
            '0' => array(
                'name' => lang("plugin/aljhtx","first_nav_php_55"),
                'icon' => '<i class="fa fa-file-code-o"></i>',
                'url' => 'plugin.php?id=aljht&act=admin&op=aljbd&do=setting&identity=aljbd&status=index&ajax=yes',
                'is_open' => '1',
                'navlist' => array(
                    '0' => array(
                        'name' => lang("plugin/aljhtx","first_nav_php_56"),
                        'icon' => '',
                        'url' => 'plugin.php?id=aljht&act=admin&op=aljbd&do=setting&identity=aljbd&status=index&ajax=yes',
                        'is_open' => '1',
                    ),
                    '1' => array(
                        'name' => lang("plugin/aljhtx","first_nav_php_57"),
                        'icon' => '',
                        'url' => 'plugin.php?id=aljht&act=admin&op=aljbd&do=setting&identity=aljbd&status=nav&ajax=yes',
                        'is_open' => '1',
                    ),
                    '2' => array(
                        'name' => 'PC'.lang("plugin/aljhtx","first_nav_php_58"),
                        'icon' => '',
                        'url' => 'plugin.php?id=aljht&act=admin&op=aljbd&do=setting&identity=aljbd&status=footer&ajax=yes',
                        'is_open' => '1',
                    ),
                    '3' => array(
                        'name' => lang("plugin/aljhtx","first_nav_php_59"),
                        'icon' => '',
                        'url' => 'plugin.php?id=aljht&act=admin&op=aljbd&do=setting&identity=aljbd&status=common&ajax=yes',
                        'is_open' => '1',
                    ),
                    '4' => array(
                        'name' => lang("plugin/aljhtx","first_nav_php_60"),
                        'icon' => '',
                        'url' => 'plugin.php?id=aljht&act=admin&op=aljbd&do=setting&identity=aljbd&status=list&ajax=yes',
                        'is_open' => '1',
                    ),
                    '5' => array(
                        'name' => lang("plugin/aljhtx","first_nav_php_61"),
                        'icon' => '',
                        'url' => 'plugin.php?id=aljht&act=admin&op=aljbd&do=setting&identity=aljbd&status=view&ajax=yes',
                        'is_open' => '1',
                    ),
                    '6' => array(
                        'name' => lang("plugin/aljhtx","first_nav_php_62"),
                        'icon' => '',
                        'url' => 'plugin.php?id=aljht&act=admin&op=aljbd&do=setting&identity=aljbd&status=member&ajax=yes',
                        'is_open' => '1',
                    ),
                    '7' => array(
                        'name' => lang("plugin/aljhtx","first_nav_php_63"),
                        'icon' => '',
                        'url' => 'plugin.php?id=aljht&act=admin&op=aljbd&do=setting&identity=aljbd&status=post&ajax=yes',
                        'is_open' => '1',
                    ),
                    '8' => array(
                        'name' => lang("plugin/aljhtx","first_nav_php_64"),
                        'icon' => '',
                        'url' => 'plugin.php?id=aljht&act=admin&op=aljbd&do=setting&identity=aljbd&status=search&ajax=yes',
                        'is_open' => '1',
                    ),
                    '9' => array(
                        'name' => lang("plugin/aljhtx","first_nav_php_65"),
                        'icon' => '',
                        'url' => 'plugin.php?id=aljht&act=admin&op=aljbd&do=setting&identity=aljbd&status=comment&ajax=yes',
                        'is_open' => '1',
                    ),
                    '10' => array(
                        'name' => lang("plugin/aljhtx","first_nav_php_66"),
                        'icon' => '',
                        'url' => 'plugin.php?id=aljht&act=admin&op=aljbd&do=setting&identity=aljbd&status=tips&ajax=yes',
                        'is_open' => '1',
                    ),
                    '11' => array(
                        'name' => lang("plugin/aljhtx","first_nav_php_67"),
                        'icon' => '',
                        'url' => 'plugin.php?id=aljht&act=admin&op=aljbd&do=setting&identity=aljbd&status=mail&ajax=yes',
                        'is_open' => '1',
                    ),
                    '12' => array(
                        'name' => lang("plugin/aljhtx","first_nav_php_68"),
                        'icon' => '',
                        'url' => 'plugin.php?id=aljht&act=admin&op=aljbd&do=setting&identity=aljbd&status=mod&ajax=yes',
                        'is_open' => '1',
                    ),
                    '14' => array(
                        'name' => lang("plugin/aljhtx","first_nav_php_69"),
                        'icon' => '',
                        'url' => 'plugin.php?id=aljht&act=admin&op=aljbd&do=setting&identity=aljbd&status=forum&ajax=yes',
                        'is_open' => '1',
                    ),
                    '15' => array(
                        'name' => lang("plugin/aljhtx","first_nav_php_70"),
                        'icon' => '',
                        'url' => 'plugin.php?id=aljht&act=admin&op=aljbd&do=setting&identity=aljbd&status=anmi&ajax=yes',
                        'is_open' => '1',
                    ),
                    '16' => array(
                        'name' => lang("plugin/aljhtx","first_nav_php_71"),
                        'icon' => '',
                        'url' => 'plugin.php?id=aljht&act=admin&op=aljbd&do=setting&identity=aljbd&status=dx&ajax=yes',
                        'is_open' => '1',
                    ),
                    '17' => array(
                        'name' => lang("plugin/aljhtx","first_nav_php_72"),
                        'icon' => '',
                        'url' => 'plugin.php?id=aljht&act=admin&op=aljbd&do=setting&identity=aljbd&status=ad&ajax=yes',
                        'is_open' => '1',
                    ),
                )
            ),
            '1' => array(
                'name' => lang("plugin/aljhtx","first_nav_php_73"),
                'icon' => '<i class="fa fa-file-code-o"></i>',
                'url' => 'plugin.php?id=aljht&act=admin&op=aljbd&do=brandtype&identity=aljbd&do=region',
                'is_open' => '1',
                'navlist' => array(
                    '0' => array(
                        'name' => lang("plugin/aljhtx","first_nav_php_74"),
                        'icon' => '',
                        'url' => 'plugin.php?id=aljht&act=admin&op=aljbd&do=brandtype&identity=aljbd&do=region&ajax=yes',
                        'is_open' => '1',
                    ),
                    '3' => array(
                        'name' => lang("plugin/aljhtx","first_nav_php_75"),
                        'icon' => '',
                        'url' => 'plugin.php?id=aljht&act=admin&op=aljbd&do=seo&identity=aljbd&do=cache&ajax=yes',
                        'is_open' => '1',
                    ),
                )
            ),
            '2' => array(
                'name' => lang("plugin/aljhtx","first_nav_php_76"),
                'icon' => '<i class="fa fa-file-code-o"></i>',
                'url' => 'plugin.php?id=aljht&act=admin&op=aljbd&do=brandtype&identity=aljbd&do=region',
                'is_open' => '1',
                'navlist' => array(
                    '0' => array(
                        'name' => lang("plugin/aljhtx","first_nav_php_77"),
                        'icon' => '',
                        'url' => 'plugin.php?id=aljht&act=admin&op=aljbd&do=region&identity=aljbd&do=cache&ajax=yes',
                        'is_open' => '1',
                    ),
                    '3' => array(
                        'name' => lang("plugin/aljhtx","first_nav_php_78"),
                        'icon' => '',
                        'url' => 'plugin.php?id=aljht&act=admin&op=aljbd&do=cache&identity=aljbd&cache=p_s&ajax=yes',
                        'is_open' => '1',
                    ),
                    '4' => array(
                        'name' => lang("plugin/aljhtx","first_nav_php_79"),
                        'icon' => '',
                        'url' => 'plugin.php?id=aljht&act=admin&op=aljbd&do=cache&identity=aljbd&cache=gl&ajax=yes',
                        'is_open' => '1',
                    ),
                )
            ),
            '3' => array(
                'name' => 'SEO'.lang("plugin/aljhtx","first_nav_php_80"),
                'icon' => '<i class="fa fa-file-code-o"></i>',
                'url' => 'plugin.php?id=aljht&act=admin&op=aljbd&do=brandtype&identity=aljbd&do=region',
                'is_open' => '1',
                'navlist' => array(
                    '0' => array(
                        'name' => 'SEO'.lang("plugin/aljhtx","first_nav_php_81"),
                        'icon' => '',
                        'url' => 'plugin.php?id=aljht&act=admin&op=aljbd&do=region&identity=aljbd&do=seo&ajax=yes',
                        'is_open' => '1',
                    ),
                    '13' => array(
                        'name' => lang("plugin/aljhtx","first_nav_php_82").'SEO',
                        'icon' => '',
                        'url' => 'plugin.php?id=aljht&act=admin&op=aljbd&do=setting&identity=aljbd&status=seo&ajax=yes',
                        'is_open' => '1',
                    ),
                    '2' => array(
                        'name' => lang("plugin/aljhtx","first_nav_php_83"),
                        'icon' => '',
                        'url' => 'plugin.php?id=aljseo&c=xiongzhang&a=list&ajax=yes',
                        'is_open' => $c_parray['aljseo']?'1':'0',
                    ),
                )
            ),
            '12' => array(
                'name' => lang("plugin/aljhtx","first_nav_php_84"),
                'icon' => '<i class="fa fa-file-code-o"></i>',
                'url' => 'plugin.php?id=aljhtx&c=log&a=check&ajax=yes',
                'is_open' => '1',
                'navlist' => array(
                    '0' => array(
                        'name' => lang("plugin/aljhtx","first_nav_php_85"),
                        'icon' => '',
                        'url' => 'plugin.php?id=aljhtx&c=log&a=check&ajax=yes',
                        'is_open' => '1',
                    ),
                    '1' => array(
                        'name' => lang("plugin/aljhtx","first_nav_php_86"),
                        'icon' => '',
                        'url' => 'plugin.php?id=aljhtx&c=log&a=dir&ajax=yes',
                        'is_open' => '1',
                    ),
                    '3' => array(
                        'name' => lang("plugin/aljhtx","first_nav_php_87"),
                        'icon' => '',
                        'url' => 'plugin.php?id=aljhtx&c=log&a=plugin&ajax=yes',
                        'is_open' => '1',
                    ),
                    '4' => array(
                        'name' => lang("plugin/aljhtx","first_nav_php_88"),
                        'icon' => '',
                        'url' => 'plugin.php?id=aljht&act=admin&op=aljbd&do=cache&identity=aljbd&do=upgrade&ajax=yes',
                        'is_open' => '1',
                    ),
                )
            )
        )
    ),
    '11' => array(
        'name' => lang("plugin/aljhtx","first_nav_php_89"),
        'icon' => '<i class="fa fa-download"></i>',
        'url' => 'plugin.php?id=aljhtx&c=log&a=plugin&ajax=yes',
        'is_open' => '1',
    ),
);
