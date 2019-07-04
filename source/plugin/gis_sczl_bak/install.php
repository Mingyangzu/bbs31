<?php

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}


$sql = <<<EOF
        
DROP TABLE IF EXISTS pre_home_gis;
CREATE TABLE pre_home_gis (
  `uid` mediumint(8) unsigned NOT NULL default '0',
  `appid` text NOT NULL,
  PRIMARY KEY (`uid`)
)ENGINE=MyISAM;
 
EOF;

runquery($sql);

$finish = TRUE;


