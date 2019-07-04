<?php
/**
 *	开发团队：IT618
 *	it618_copyright 插件设计：<a href="http://www.cnit618.com" target="_blank" title="专业Discuz!应用及周边提供商">IT618</a>
 */
 
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

global $_G,$it618_storemapad,$it618_point_get;

$it618_storemapad = $_G['cache']['plugin']['it618_storemapad'];

header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Pramga: no-cache");

if($_GET['ac']=="it618_point_add"){
	$cid = intval($_GET['cid']);
	$it618_point = trim($_POST['it618_point_add']);
	$it618_pointname = trim($_POST['it618_pointname_add']);
	$it618_pointimg = trim($_POST['it618_pointimg_add']);
	$it618_pointurl = trim($_POST['it618_pointurl_add']);
	$it618_pointabout = trim($_POST['it618_pointabout_add']);
	$it618_width = intval($_POST['it618_width_add']);
	$it618_height = intval($_POST['it618_height_add']);
	
	if($it618_point != '') {
		if($it618_class2id = DB::result_first("SELECT id FROM ".DB::table('it618_storemapad_class2')." WHERE id=".$cid)) {

				DB::insert('it618_storemapad', array(
					'it618_class2_id' => $cid,
					'it618_uid' => $_G[uid],
					'it618_point' => $it618_point,
					'it618_pointname' => $it618_pointname,
					'it618_pointimg' => $it618_pointimg,
					'it618_pointurl' => $it618_pointurl,
					'it618_pointabout' =>$it618_pointabout,
					'it618_width' =>$it618_width,
					'it618_height' =>$it618_height,
					'it618_time' => $_G['timestamp'],
				));

				if($_G['adminid'] != 1){
					$ad_credit = $it618_storemapad['ad_credit'];
					$ad_creditnum = $it618_storemapad['ad_creditnum'];
					C::t('common_member_count')->increase($_G['uid'], array(
						'extcredits'.$ad_credit => (0-$ad_creditnum))
					);
				}

		}
	}
}

if($_GET['ac']=="it618_point_edit"){
	$cid = intval($_GET['cid']);
	$it618_pointid = intval($_POST['it618_pointid_edit']);
	$it618_point = trim($_POST['it618_point_edit']);
	$it618_pointname = trim($_POST['it618_pointname_edit']);
	$it618_pointimg = trim($_POST['it618_pointimg_edit']);
	$it618_pointurl = trim($_POST['it618_pointurl_edit']);
	$it618_pointabout = trim($_POST['it618_pointabout_edit']);
	$it618_width = intval($_POST['it618_width_edit']);
	$it618_height = intval($_POST['it618_height_edit']);
	
	if($it618_point != '') {
		if($it618_class2id = DB::result_first("SELECT id FROM ".DB::table('it618_storemapad_class2')." WHERE id=".$cid)) {

				DB::update('it618_storemapad', array(
					'it618_point' => $it618_point,
					'it618_pointname' => $it618_pointname,
					'it618_pointimg' => $it618_pointimg,
					'it618_pointurl' => $it618_pointurl,
					'it618_pointabout' =>$it618_pointabout,
					'it618_width' =>$it618_width,
					'it618_height' =>$it618_height,
				), "id=".$it618_pointid);
				
				if($_G['adminid'] != 1){
					$ad_credit = $it618_storemapad['ad_credit'];
					$ad_creditnum_edit = $it618_storemapad['ad_creditnum_edit'];
					C::t('common_member_count')->increase($_G['uid'], array(
						'extcredits'.$ad_credit => (0-$ad_creditnum_edit))
					);
				}

		}
	}
}

if($_GET['ac']=="it618_pointdel"){
	$it618_pointid = intval($_GET['pointid']);
	
	DB::delete('it618_storemapad', "id=".$it618_pointid);
	
}

if($_GET['ac']=="main"){

	$cid = intval($_GET['cid']);

	$ad_tips=getit618_credit();

	getit618_pointitem($cid);
	
	$it618_images_ok = DB::result_first("SELECT count(1) FROM ".DB::table('common_plugin')." WHERE identifier='it618_images' and available=1");
																   
	include template('it618_storemapad:setstoremapad');
}

if($_GET['ac']=="getit618_credit"){
	echo getit618_credit();
}

function getit618_credit(){
	global $_G,$it618_storemapad;
	$ad_credit = $it618_storemapad['ad_credit'];
	$ad_creditnum = $it618_storemapad['ad_creditnum'];
	$ad_creditnum_edit = $it618_storemapad['ad_creditnum_edit'];
	$creditnum=DB::result_first("select extcredits".$ad_credit." from ".DB::table('common_member_count')." where uid=".$_G['uid']);
	$ad_credit=$_G['setting']['extcredits'][$ad_credit]['title'];
	
	$ad_tips=str_replace("{creditname}",$ad_credit,$it618_storemapad['ad_tips']);
	$ad_tips=str_replace("{creditnum}",$ad_creditnum,$ad_tips);
	$ad_tips=str_replace("{creditnum_edit}",$ad_creditnum_edit,$ad_tips);
	$ad_tips=str_replace("{curcreditnum}",$creditnum,$ad_tips);
	$ad_tips.='<input id="it618_creditname" type="hidden" value="'.$ad_credit.'"/>
			   <input id="it618_creditnum" type="hidden" value="'.$ad_creditnum.'"/>
			   <input id="it618_creditnum_edit" type="hidden" value="'.$ad_creditnum_edit.'"/>
			   <input id="it618_curcreditnum" type="hidden" value="'.$creditnum.'"/>';
	
	return $ad_tips;
}

if($_GET['ac']=="getit618_pointitem"){
	$cid = intval($_GET['cid']);
	getit618_pointitem($cid);
}

function getit618_pointitem($cid){
	global $_G,$it618_point_get;
	$tid = intval($cid);
	
	$query = DB::query("SELECT * FROM ".DB::table('it618_storemapad')." WHERE it618_class2_id=".$cid." and it618_uid=".$_G[uid]);
	while($it618_point =DB::fetch($query)) {
		$it618_point['it618_pointname'] = dhtmlspecialchars($it618_point['it618_pointname']);

		$it618_point_get.='<span title="'.$it618_point['it618_pointabout'].'">'.$it618_point['it618_pointname'].' <a onclick="it618_point_edit('.$it618_point['id'].')">'.lang('plugin/it618_storemapad', 'it618_edit').'</a> <a onclick="it618_point_del('.$it618_point['id'].')">'.lang('plugin/it618_storemapad', 'it618_del').'</a></span><input id="it618_pointid_'.$it618_point['id'].'" type="hidden" value="'.$it618_point['id'].'"/>
		<input id="it618_pointid_'.$it618_point['id'].'" type="hidden" value="'.$it618_point['id'].'"/>
		<input id="it618_point_'.$it618_point['id'].'" type="hidden" value="'.$it618_point['it618_point'].'"/>
		<input id="it618_pointname_'.$it618_point['id'].'" type="hidden" value="'.$it618_point['it618_pointname'].'"/>
		<input id="it618_pointurl_'.$it618_point['id'].'" type="hidden" value="'.$it618_point['it618_pointurl'].'"/>
		<input id="it618_pointimg_'.$it618_point['id'].'" type="hidden" value="'.$it618_point['it618_pointimg'].'"/>
		<input id="it618_pointabout_'.$it618_point['id'].'" type="hidden" value="'.$it618_point['it618_pointabout'].'"/>
		<input id="it618_width_'.$it618_point['id'].'" type="hidden" value="'.$it618_point['it618_width'].'"/>
		<input id="it618_height_'.$it618_point['id'].'" type="hidden" value="'.$it618_point['it618_height'].'"/>';
	}

	echo $it618_point_get;
}
?>