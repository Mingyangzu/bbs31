<?php
/*
	Install Uninstall Upgrade AutoStat System Code
*/
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
//start to put your own code
$sql = <<<EOF
CREATE TABLE IF NOT EXISTS `pre_aljhtx_qrcode_miniprogram` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `url` varchar(255) NOT NULL,
  `qrcode` varchar(255) NOT NULL,
  `dateline` int(10) NOT NULL,
  `num` int(10) NOT NULL,
  PRIMARY KEY (`id`)
);
CREATE TABLE IF NOT EXISTS `pre_aljhtx_qrcode_common` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `url` varchar(255) NOT NULL,
  `qrcode` varchar(255) NOT NULL,
  `dateline` int(10) NOT NULL,
  `num` int(10) NOT NULL,
  PRIMARY KEY (`id`)
);
CREATE TABLE IF NOT EXISTS `pre_aljhtx_qrcode_wechat` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `ticket` varchar(255) NOT NULL,
  `type` int(11) NOT NULL,
  `qrcode` varchar(255) NOT NULL,
  `mykeyword` varchar(255) NOT NULL,
  `num` int(10) NOT NULL,
  `expire_seconds` int(10) NOT NULL,
  `action_name` int(11) NOT NULL,
  `scene_id` int(11) NOT NULL,
  `dateline` int(11) NOT NULL,
  PRIMARY KEY (`id`)
);
CREATE TABLE IF NOT EXISTS `pre_aljhtx_qrcode_goods` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `ticket` varchar(255) NOT NULL,
  `type` int(11) NOT NULL,
  `qrcode` varchar(255) NOT NULL,
  `scene_str` varchar(255) NOT NULL,
  `num` int(10) NOT NULL,
  `expire_seconds` int(10) NOT NULL,
  `action_name` int(11) NOT NULL,
  `gid` int(11) NOT NULL,
  `dateline` int(11) NOT NULL,
  PRIMARY KEY (`id`)
);
CREATE TABLE IF NOT EXISTS `pre_aljhtx_notification` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `param` text NOT NULL,
  `content` text NOT NULL,
  `url` varchar(255) NOT NULL,
  `dateline` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `news_type` TINYINT(3) NOT NULL,
  `logo` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `pre_aljhtx_diy` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `page` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `module` varchar(255) NOT NULL,
  `auto_module` varchar(255) NOT NULL,
  `displayorder` int(11) NOT NULL,
  `template` varchar(255) NOT NULL,
  `data` text NOT NULL,
  PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `pre_aljhtx_type` (
  `type_id` int(10) NOT NULL AUTO_INCREMENT,
  `type_upid` int(10) NOT NULL,
  `type_name` varchar(255) NOT NULL,
  `type_displayorder` int(10) NOT NULL,
  `type_logo` varchar(255) NOT NULL,
  `type_mod` varchar(255) NOT NULL,
  `type_pluginid` varchar(255) NOT NULL,
  `type_icon` varchar(255) NOT NULL,
  PRIMARY KEY (`type_id`)
);
CREATE TABLE IF NOT EXISTS `pre_aljhtx_cron` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `pid` bigint(9) NOT NULL,
  `uid` int(10) NOT NULL,
  `touid` int(10) NOT NULL,
  `type` varchar(255) NOT NULL,
  `param` text NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `dateline` int(10) NOT NULL,
  `status` tinyint(3) NOT NULL,
  `success` tinyint(3) NOT NULL,
  `sync` tinyint(3) NOT NULL,
  PRIMARY KEY (`id`)
);
CREATE TABLE IF NOT EXISTS  `pre_aljhtx_setting` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `root` varchar(255) NOT NULL,
  `controller` varchar(255) NOT NULL,
  `action` varchar(255) NOT NULL,
  `skey` varchar(255) NOT NULL,
  `svalue` text NOT NULL,
   PRIMARY KEY (`id`)
);
CREATE TABLE IF NOT EXISTS `pre_aljhtx_system_setting` (
  `skey` varchar(255) NOT NULL,
  `svalue` text NOT NULL,
  PRIMARY KEY (`skey`)
);
REPLACE INTO `pre_aljhtx_diy` (`id`, `page`, `type`, `module`, `auto_module`, `displayorder`, `template`, `data`) VALUES
(1, 'index', 'system', 'gg', '', 1, '', ''),
(2, 'index', 'system', 'mobile_index_Photo_Ads', '', 2, '', ''),
(3, 'index', 'system', 'aljad_index_sangead', '', 3, '', ''),
(4, 'index', 'system', 'mobile_index_tad', '', 3, '', ''),
(5, 'index', 'system', 'aljad_index_four_lattice', '', 4, '', ''),
(6, 'index', 'system', 'mobile_index_fad', '', 4, '', ''),
(7, 'index', 'system', 'sj_index_dh', '', 0, '', '');
EOF;
runquery($sql);
//finish to put your own code
$finish = TRUE;
?>
