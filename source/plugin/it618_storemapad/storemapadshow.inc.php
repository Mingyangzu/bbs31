<?php
/**
 *	开发团队：IT618
 *	it618_copyright 插件设计：<a href="http://www.cnit618.com" target="_blank" title="专业Discuz!应用及周边提供商">IT618</a>
 */
 
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

global $_G;

$it618_storemapad = $_G['cache']['plugin']['it618_storemapad'];
require_once libfile('function/discuzcode');

$cid = intval($_GET['cid']);
if($cid>0){
	if($it618_class2 = DB::fetch_first("SELECT * FROM ".DB::table('it618_storemapad_class2')." WHERE id=".$cid)) {
		$it618_storemapad_point=$it618_class2['it618_point'];
		$it618_storemapad_zoom=$it618_class2['it618_zoom'];
		
		$n=1;
		if($_GET['ac']=="find"){
			$query = DB::query("SELECT * FROM ".DB::table('it618_storemapad')." WHERE it618_pointname like '%".addslashes($_POST['findkey'])."%'");
		}else{
			$query = DB::query("SELECT * FROM ".DB::table('it618_storemapad')." WHERE it618_class2_id=".$it618_class2['id']);
		}
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
			
			$tmp=explode(",",$it618_storemapad_t['it618_point']);
			$it618_storemapad_points.='<ul>
			<li class="pointx">'.$tmp[0].'</li>
			<li class="pointy">'.$tmp[1].'</li>
			<li class="aid">'.$it618_storemapad_t['id'].'</li>
			<li class="color">'.$it618_storemapad['ad_labelcolor'].'</li>
			<li class="title">'.$it618_storemapad_t['it618_pointname'].'</li>
			<li class="content"><div>'.$strtmp.'<p style=\'margin:0;line-height:1.5;font-size:13px;\'>'.$it618_storemapad_t['it618_pointabout'].'</p></div></li>
			<li class="width">'.$it618_storemapad_t['it618_width'].'</li>
			<li class="height">'.$it618_storemapad_t['it618_height'].'</li>
			</ul>';
			
			$strtmp="";
		}
	
	}
	
	$ad_groups = unserialize($it618_storemapad["ad_groups"]);
	if(in_array($_G[groupid], $ad_groups)){
		$powerbtn='<input type="button" value="'.$it618_storemapad["ad_title"].'" onClick="parent.it618_showwindow()"/>';
	}else{
		$powerbtn='';
	}

	$allmapheight=$it618_storemapad["ad_height"]-30;
	$allmapwidth=$it618_storemapad["ad_width"]-200;
	$resultheight=$it618_storemapad["ad_height"]-35;
	
	include template('it618_storemapad:storemapadshow');
}else{
	echo str_replace(array("\r\n", "\r", "\n"),"<br>",$it618_storemapad["ad_index"]);
}
?>