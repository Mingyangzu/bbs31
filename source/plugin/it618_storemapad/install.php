<?php
/**
 *	开发团队：IT618
 *	it618_copyright 插件设计：<a href="http://www.cnit618.com" target="_blank" title="专业Discuz!应用及周边提供商">IT618</a>
 */
if(!defined('IN_ADMINCP')) exit('Access Denied');
$sql = <<<EOF
DROP TABLE IF EXISTS `pre_it618_storemapad_class1`;
CREATE TABLE IF NOT EXISTS `pre_it618_storemapad_class1` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `it618_classname` varchar(255) NOT NULL,
  `it618_order` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM;

DROP TABLE IF EXISTS `pre_it618_storemapad_class2`;
CREATE TABLE IF NOT EXISTS `pre_it618_storemapad_class2` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `it618_class1_id` int(10) unsigned NOT NULL,
  `it618_classname` varchar(255) NOT NULL,
  `it618_point` varchar(255) NOT NULL,
  `it618_zoom` varchar(10) NOT NULL,
  `it618_order` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM;

DROP TABLE IF EXISTS `pre_it618_storemapad`;
CREATE TABLE IF NOT EXISTS `pre_it618_storemapad` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `it618_class2_id` int(10) unsigned NOT NULL,
  `it618_uid` int(10) unsigned NOT NULL,
  `it618_point` varchar(255) NOT NULL,
  `it618_pointname` varchar(255) NOT NULL,
  `it618_pointurl` varchar(255) NOT NULL,
  `it618_pointimg` varchar(255) NOT NULL,
  `it618_pointabout` varchar(8000) NOT NULL,
  `it618_width` int(10) unsigned NOT NULL DEFAULT '400',
  `it618_height` int(10) unsigned NOT NULL DEFAULT '300',
  `it618_time` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM;

EOF;

$sql=str_replace("pre_it618_storemapad_class1",DB::table('it618_storemapad_class1'),$sql);
$sql=str_replace("pre_it618_storemapad_class2",DB::table('it618_storemapad_class2'),$sql);
$sql=str_replace("pre_it618_storemapad",DB::table('it618_storemapad'),$sql);
runquery($sql);

//DEFAULT CHARSET=gbk;
$finish = TRUE;
?>