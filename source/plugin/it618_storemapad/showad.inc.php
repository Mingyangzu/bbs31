<?php
/**
 *	开发团队：IT618
 *	it618_copyright 插件设计：<a href="http://www.cnit618.com" target="_blank" title="专业Discuz!应用及周边提供商">IT618</a>
 */
 
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

global $_G;

$it618_houseloupan = $_G['cache']['plugin']['it618_houseloupan'];
$aid = intval($_GET['aid']);
require_once libfile('function/discuzcode');

$query = DB::query("SELECT * FROM ".DB::table('it618_storemapad')." WHERE id=".$aid);
while($it618_storemapad_t =DB::fetch($query)) {
	$it618_storemapad_t['it618_pointname'] = dhtmlspecialchars($it618_storemapad_t['it618_pointname']);
	$it618_storemapad_t['it618_pointurl'] = dhtmlspecialchars($it618_storemapad_t['it618_pointurl']);
	$it618_storemapad_t['it618_pointimg'] = dhtmlspecialchars($it618_storemapad_t['it618_pointimg']);
	
	$it618_storemapad_t['it618_pointabout']=discuzcode($it618_storemapad_t['it618_pointabout'], 0, 0, 0, 1, 1, 1, 0, 0, 0, 0);
	$it618_storemapad_t['it618_pointabout']=str_replace('onclick="zoom(this, this.src, 0, 0, 0)"',"",$it618_storemapad_t['it618_pointabout']);
	$it618_storemapad_t['it618_pointabout']=str_replace('onmouseover="img_onmouseoverfunc(this)" onload="thumbImg(this)"',"",$it618_storemapad_t['it618_pointabout']);
	$it618_storemapad_t['it618_pointabout']=str_replace('"',"'",$it618_storemapad_t['it618_pointabout']);
	$it618_storemapad_t['it618_pointabout'] = str_replace(array("\r\n", "\r", "\n"),"",$it618_storemapad_t['it618_pointabout']);
	
	if($it618_storemapad_t['it618_pointimg']!=""){
		$strtmp='<img style=\'float:left;margin:4px\' src='.$it618_storemapad_t['it618_pointimg'].' border=0/>';
	}
	if($it618_storemapad_t['it618_pointimg']!=""&&$it618_storemapad_t['it618_pointurl']!=""){
		$strtmp='<a href='.$it618_storemapad_t['it618_pointurl'].' target=_blank><img style=\'float:left;margin:4px\' src='.$it618_storemapad_t['it618_pointimg'].' border=0/></a>';
	}
	
	$it618_pointname=$it618_storemapad_t['it618_pointname'];
	$adstr='<div>'.$strtmp.'<p style=\'margin:0;line-height:1.5;font-size:13px;\'>'.$it618_storemapad_t['it618_pointabout'].'</p></div>';
}

include template('it618_storemapad:showad');
?>