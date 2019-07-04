<?php
/**
 *	开发团队：IT618
 *	it618_copyright 插件设计：<a href="http://www.cnit618.com" target="_blank" title="专业Discuz!应用及周边提供商">IT618</a>
 */
/*
	Install Uninstall Upgrade AutoStat System Code
*/
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$query = DB::query("SHOW COLUMNS FROM ".DB::table('it618_storemapad'));
while($row = DB::fetch($query)) {
	$col_field[]=$row['Field']; 
}

if(!in_array('it618_width', $col_field)){
	$sql = "Alter table ".DB::table('it618_storemapad')." add `it618_width` int(10) unsigned NOT NULL default 400;"; 
	DB::query($sql); 
}
if(!in_array('it618_height', $col_field)){
	$sql = "Alter table ".DB::table('it618_storemapad')." add `it618_height` int(10) unsigned NOT NULL default 300;"; 
	DB::query($sql); 
}

//DEFAULT CHARSET=gbk;
$finish = TRUE;
?>