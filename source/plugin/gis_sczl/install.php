<?php

if (!defined('IN_DISCUZ')) {
    exit('Access Denied');
}
/*
 *
 *  source/include/portalcp/portalcp_article.php
 *  line 23 add : empty($article['gisucode']) && $article['gisucode'] = $gisucode;
 *  line 26 add : $gisucode = md5(time());
 *  line 98 add : 'gisucode' => trim($_POST['gisucode']),
 * 
 *  template/default/portal/portalcp_article.htm
 *  line 210 add : {template gis_sczl:gis_article}
 * 
 * 
 *  */

$sql = <<<EOF

//DROP TABLE IF EXISTS pre_common_gis;        
//CREATE TABLE IF NOT EXISTS `pre_common_gis` (
//  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
//  `name` varchar(127)  NOT NULL comment '名称',
//  `types` tinyint unsigned NOT NULL comment'类型 1：点， 2：线， 3：面',
//  `lnglat` text NOT NULL comment'经纬度',
//  `radius` varchar(32) DEFAULT '0' comment'圆半径',
//  `resid` varchar(10) NOT NULL comment'一级资源目录',
//  `resid2` int(10) unsigned NOT NULL DEFAULT '0' comment'二级资源目录',
//  `resid3` int(10) unsigned DEFAULT '0' comment'三级资源目录',
//  `texts` text  comment'内容',
//  `imgs` varchar(255) comment'图片',
//  `icon` varchar(127) comment '图标',
//  `color` varchar(15) comment '色码值',
//  `create_time` int(10) comment '添加时间',
//  `update_time` int(10) comment '修改时间',
//  PRIMARY KEY (`id`),
//  key `gisresid` (`resid`),
//  key `gisresid2` (`resid2`),
//  key `gistypes` (`types`)
//) ENGINE=innodb  DEFAULT CHARSET=utf8 comment'地理信息标注点';
//
//DROP TABLE IF EXISTS pre_common_resources;
//CREATE TABLE IF NOT EXISTS `pre_common_resources` (
//  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
//  `name` varchar(127)  NOT NULL comment '名称',
//  `fid` int unsigned NOT NULL default 0 comment'上级资源id',
//  `types` tinyint unsigned NOT NULL default 1 comment'层级',
//  `create_time` int(10) comment '添加时间',
//  `update_time` int(10) comment '修改时间',
//  PRIMARY KEY (`id`),
//  key `resources_fid` (`fid`),
//  key `resources_types` (`types`)
//) ENGINE=innodb  DEFAULT CHARSET=utf8 comment '资源目录'; 
//
//DROP TABLE IF EXISTS pre_common_gis_article;
//create table if not exists `pre_common_gis_article`(
//    `id` int unsigned not null auto_increment,
//    `texts` text comment '地图标注数据',
//    `create_time` int comment '添加时间',
//    `update_time` int comment '修改时间',
//    primary key (`id`)
//) engine=innodb default charset=utf8 comment'文章中插入地图数据';        
        
insert into `pre_common_plugin` (available,adminid,name,identifier,,description,datatables,directory,copyright,modules,version) values(1,0,'地理信息标注','gis_sczl','','','gis_sczl/','','a:4:{i:0;a:11:{s:4:"name";s:6:"resgis";s:5:"param";s:0:"";s:4:"menu";s:18:"标注信息管理";s:3:"url";s:0:"";s:4:"type";s:1:"3";s:7:"adminid";s:1:"0";s:12:"displayorder";i:0;s:8:"navtitle";s:0:"";s:7:"navicon";s:0:"";s:10:"navsubname";s:0:"";s:9:"navsuburl";s:0:"";}i:1;a:11:{s:4:"name";s:6:"gismap";s:5:"param";s:0:"";s:4:"menu";s:24:"首页";s:3:"url";s:0:"";s:4:"type";s:1:"1";s:7:"adminid";s:1:"0";s:12:"displayorder";i:0;s:8:"navtitle";s:0:"";s:7:"navicon";s:0:"";s:10:"navsubname";s:0:"";s:9:"navsuburl";s:0:"";}i:2;a:11:{s:4:"name";s:13:"gismap_import";s:5:"param";s:11:"&ac=article";s:4:"menu";s:18:"地理信息导入";s:3:"url";s:0:"";s:4:"type";s:1:"3";s:7:"adminid";s:1:"0";s:12:"displayorder";i:8;s:8:"navtitle";s:0:"";s:7:"navicon";s:0:"";s:10:"navsubname";s:0:"";s:9:"navsuburl";s:0:"";}i:3;a:11:{s:4:"name";s:3:"gis";s:5:"param";s:0:"";s:4:"menu";s:0:"";s:3:"url";s:0:"";s:4:"type";s:2:"11";s:7:"adminid";s:1:"0";s:12:"displayorder";i:9;s:8:"navtitle";s:0:"";s:7:"navicon";s:0:"";s:10:"navsubname";s:0:"";s:9:"navsuburl";s:0:"";}}','1.0.1');

alter table `pre_portal_article_title` add gisucode char(32) comment'标注表关联唯一码' after title;
   
CREATE TABLE IF NOT EXISTS `pre_portal_article_gis` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `gisucode` char(32) NOT NULL comment '文章表关联字段',
  `types` tinyint comment '类型：1 点、2 线、3 面、4 矩形、5 圆',
  `lnglat` text NOT NULL comment'经纬度',
  `icon` varchar(255) comment'图标',
  `backgrse` char(32) comment'填充色码',
  `radius` varchar(32) default '0' comment'圆半径',
  `types_text` varchar(32)  comment'类型名',
  `create_time` int comment '添加时间',
  PRIMARY KEY (`id`),
  key `gisucode` (`gisucode`)
) ENGINE=innodb  DEFAULT CHARSET=utf8 comment'文章所属标注信息表';
        
EOF;

runquery($sql);

$finish = TRUE;


