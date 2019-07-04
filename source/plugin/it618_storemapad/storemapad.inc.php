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
$navtitle = $it618_storemapad['seotitle'];
$metakeywords = $it618_storemapad['seokeywords'];
$metadescription = $it618_storemapad['seodescription'];

$defaultcid="";
$query1 = DB::query("SELECT * FROM ".DB::table('it618_storemapad_class1')." ORDER BY it618_order");
while($it618_class1 = DB::fetch($query1)) {
	
	$it618_class1['it618_classname'] = dhtmlspecialchars($it618_class1['it618_classname']);
	$strmenu.='<div class="collapsed"><span>&nbsp;&nbsp;'.$it618_class1['it618_classname'].getadcount1($it618_class1['id']).'</span>';
	
	$query2 = DB::query("SELECT * FROM ".DB::table('it618_storemapad_class2')." where it618_class1_id=".$it618_class1['id']." ORDER BY it618_order");
	while($it618_class2 = DB::fetch($query2)) {
		if($defaultcid==""){
			if($it618_storemapad["ad_isindex"]==0){
				$defaultcid=$it618_class2['id'];
			}else{
				$defaultcid=0;
			}
		}
		
		$it618_class2['it618_classname'] = dhtmlspecialchars($it618_class2['it618_classname']);
		$strmenu.='<A class="" onclick="javascript:document.getElementById(\'it618_storemapad_frame\').src=\''.$_G['siteurl'].'plugin.php?id=it618_storemapad:storemapadshow&cid='.$it618_class2['id'].'&rand=\'+Math.random();document.getElementById(\'currentclassid\').value='.$it618_class2['id'].';myMenu.init();return false;" id="'.$it618_class2['id'].'" href="javascript:" >&nbsp;&nbsp;&nbsp;'.$it618_class2['it618_classname'].getadcount2($it618_class2['id']).'</A>';
	}
	$strmenu.='</div>';
}

function getadcount1($class1_id){
	
	$class2_ids="-1";
	$query = DB::query("SELECT * FROM ".DB::table('it618_storemapad_class2')." where it618_class1_id=".$class1_id);
	while($it618_storemapad_class2 = DB::fetch($query)) {

		$class2_ids.=",".$it618_storemapad_class2['id'];
	}
	
	$count = DB::result_first("SELECT COUNT(1) FROM ".DB::table('it618_storemapad')." where it618_class2_id in(".$class2_ids.")");
	if($count>0){
		return '(<font color=red>'.$count.'</font>)';
	}
}

function getadcount2($class2_id){
	
	$count = DB::result_first("SELECT COUNT(1) FROM ".DB::table('it618_storemapad')." where it618_class2_id in(".$class2_id.")");
	if($count>0){
		return '(<font color=red>'.$count.'</font>)';
	}
}

$spanwidth=$it618_storemapad["ad_menuwidth"]-30;

include template('it618_storemapad:storemapad');
?>