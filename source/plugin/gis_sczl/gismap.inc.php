<?php

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}


$_G['gis']['dir'] = '/source/plugin/gis_sczl/';
$_G['gis']['dirstyle'] = '/source/plugin/gis_sczl/style/';

include template('gis_sczl:gismap');

