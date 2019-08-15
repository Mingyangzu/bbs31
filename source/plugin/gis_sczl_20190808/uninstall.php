<?php

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}


$sql = <<<EOF
        
DROP TABLE IF  EXISTS `pre_home_gis`;

EOF;

runquery($sql);

$finish = TRUE;

