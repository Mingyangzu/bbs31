<?php

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}


$sql = <<<EOF

DROP TABLE IF EXISTS pre_common_gis;        
CREATE TABLE IF NOT EXISTS `pre_common_gis` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(127)  NOT NULL comment '名称',
  `types` tinyint unsigned NOT NULL comment'类型 1：点， 2：线， 3：面',
  `lnglat` varchar(512) NOT NULL comment'经纬度',
  `resid` varchar(10) NOT NULL,
  `resid2` int(10) unsigned NOT NULL DEFAULT '0',
  `resid3` int(10) unsigned DEFAULT '0',
  `texts` text  comment'内容',
  `imgs` varchar(255) comment'图片',
  `icon` varchar(127) comment '图标',
  `color` varchar(15) comment '色码值',
  `create_time` int(10) comment '添加时间',
  `update_time` int(10) comment '修改时间',
  PRIMARY KEY (`id`),
  key `gisresid` (`resid`),
  key `gisresid2` (`resid2`),
  key `gistypes` (`types`)
) ENGINE=innodb  DEFAULT CHARSET=utf8 comment'地理信息标注点';

DROP TABLE IF EXISTS pre_common_resources;
CREATE TABLE IF NOT EXISTS `pre_common_resources` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(127)  NOT NULL comment '名称',
  `fid` int unsigned NOT NULL default 0 comment'上级资源id',
  `types` tinyint unsigned NOT NULL default 1 comment'层级',
  `create_time` int(10) comment '添加时间',
  `update_time` int(10) comment '修改时间',
  PRIMARY KEY (`id`),
  key `resources_fid` (`fid`),
  key `resources_types` (`types`)
) ENGINE=innodb  DEFAULT CHARSET=utf8 comment '资源目录'; 

EOF;

runquery($sql);

$finish = TRUE;


