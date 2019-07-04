<?php

/**
 *全局触发定时任务
 *
 * @author yuxinqi<yuxinqi@vip.qq.com>
 * @version 1.0
 * @link http://docs.liangjianyun.com/
 */

if (!defined('IN_DISCUZ')) {
    exit('Access Denied');
}

class plugin_aljhtx {

    function common() {
        global $_G;
            if(!$_G['cache']['plugin']['aljhtx']['server']){
                require_once 'source/plugin/aljhtx/class/class_aljhtx.php';
                require_once 'source/plugin/aljhtx/class/class_cron.php';
                Cron::start();
            }
            if($_G['cache']['plugin']['aljol']['is_sendpm']){
                if($_GET['mod'] == 'spacecp' && $_GET['ac'] == 'pm' && $_GET['op'] == 'showmsg' && $_GET['inajax']){
                    $touid = intval($_GET['touid']);
                    $pmuser = getuserbyuid($touid);
                    include template('common/header');
                    echo '<h3 class="flb">
	<em>'.lang("plugin/aljhtx","aljhtx_class_php_1").$pmuser['username'].lang("plugin/aljhtx","aljhtx_class_php_2").'</em>
	<span><a href="javascript:;" class="flbc" onclick="hideWindow(\''.$_GET['handlekey'].'\');" title="{lang close}">'.lang("plugin/aljhtx","aljhtx_class_php_3").'</a></span>

</h3>';
                    echo '<iframe src="plugin.php?id=aljol&act=talk&friendid='.$touid.'" style="width:400px;height:450px;border: none;"></iframe>';
                    include template('common/footer');
                }else if($_GET['mod'] == 'spacecp' && $_GET['ac'] == 'pm'){
                    if($_GET['touid']){
                        $touid = intval($_GET['touid']);
                        header('Location: plugin.php?id=aljol&act=talk&friendid='.$touid);
                    }
                }
            }
    }

    function global_footer(){
        global $_G;
        if($_G['cache']['plugin']['aljhtx']['is_global_aljol'] && $_G['cache']['plugin']['aljol'] && $_G['uid']){
            $avatar = avatar($_G['uid'], 'small');
            $newsCount = DB::result_first('select count(*) from %t where friendid=%d and talkstate=1',array('aljol_talk',$_G['uid']));
            $new_noti = DB::result_first('select count(*) from %t where uid=%d and status=0 and news_type=1',array('aljhtx_notification',$_G['uid']));
            $newsCount = $newsCount + $new_noti;
            include template('aljhtx:aljol');
        }
        return $return;

    }

}

class mobileplugin_aljhtx {

    function common() {
        global $_G;
        if(!$_G['cache']['plugin']['aljhtx']['server']){
            require_once 'source/plugin/aljhtx/class/class_aljhtx.php';
            require_once 'source/plugin/aljhtx/class/class_cron.php';
            Cron::start();
        }
        if($_GET['mod'] == 'space' && $_GET['do'] == 'pm'){
            if($_GET['touid']){
                $touid = intval($_GET['touid']);
                header('Location: plugin.php?id=aljol&act=talk&friendid='.$touid);
            }else{
                header('Location: plugin.php?id=aljol');
            }
        }
        if($_G['cache']['plugin']['aljol']){
            $cycle = DB::result_first('select svalue from %t where skey=%s ', array('aljhtx_setting', 'cycle'));
            if($cycle){
                $cycle_timestamp = $cycle*86400;
                DB::query('delete from %t where datetime<%d', array('aljol_talk', TIMESTAMP-$cycle_timestamp));
                $imglist = DB::fetch_all('select * from %t where datetime<%d', array('aljol_picture', TIMESTAMP-$cycle_timestamp));
                foreach($imglist as $img){
                    if($img['picture']){
                        @unlink($img['picture']);
                    }
                }
                DB::query('delete from %t where datetime<%d', array('aljol_picture', TIMESTAMP-$cycle_timestamp));
            }
        }
        if(!$_G['cache']['plugin']['aljhtx']['server']){
            require_once 'source/plugin/aljhtx/class/class_aljhtx.php';
            require_once 'source/plugin/aljhtx/class/class_cron.php';
            Cron::start();
        }
    }
    public static function global_footer_mobile(){
        global $_G;
        if($_G['cache']['plugin']['aljhtx']['is_mobile_global_aljol'] && $_G['cache']['plugin']['aljol'] && $_G['uid']){
            $avatar = avatar($_G['uid'], 'small');
            $newsCount = DB::result_first('select count(*) from %t where friendid=%d and talkstate=1',array('aljol_talk',$_G['uid']));
            $new_noti = DB::result_first('select count(*) from %t where uid=%d and status=0 and news_type=1',array('aljhtx_notification',$_G['uid']));
            $newsCount = $newsCount + $new_noti;
            include template('aljhtx:aljol');
        }
        return $return;
    }
}
