<?php

if (!defined('IN_DISCUZ')) {
    exit('Access Denied');
}

$_G['gis']['dir'] = '/source/plugin/gis_sczl/';
$_G['gis']['dirstyle'] = '/source/plugin/gis_sczl/style/';


//验证用户权限
$umsg = isadminuser($_G);
$testvar = '我从后端来';

function isadminuser($_G) {
    return ($_G['uid'] > 0 && $_G['adminid'] === '1' && !empty($usession->var['username'])) ? true : false;
}

if($_GET['mod'] == 'article'){
    include template('gis_sczl:gismap_article');
}else{
    include template('gis_sczl:gd_iframe');
}

