<?php
/**
 *	开发团队：IT618
 *	it618_copyright 插件设计：<a href="http://www.cnit618.com" target="_blank" title="专业Discuz!应用及周边提供商">IT618</a>
 */
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
DB::query("DROP TABLE IF EXISTS ".DB::table('it618_storemapad_class1')."");
DB::query("DROP TABLE IF EXISTS ".DB::table('it618_storemapad_class2')."");
DB::query("DROP TABLE IF EXISTS ".DB::table('it618_storemapad')."");

//DEFAULT CHARSET=gbk;
$finish = TRUE;
?>