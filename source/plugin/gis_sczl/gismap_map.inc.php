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
    $usession = C::app()->session;   
    return ($_G['uid'] > 0 && $_G['adminid'] === '1' && !empty($usession->var['username'])) ? true : false;
}

//if($_GET['mod'] == 'article'){
//    
//}else{
//    $mapboxtop = $_GET['mod'] == 'addmap' ? 5 : 50;
//    include template('gis_sczl:gismap_iframe');
//}

switch($_GET['mod']){
    case 'article':
        include template('gis_sczl:gismap_article');
        break;
    case 'index':
        include template('gis_sczl:gd_iframe');
        break;
    default:
        $mapboxtop = $_GET['mod'] == 'addmap' ? 5 : 50;
        include template('gis_sczl:gismap_iframe');
}
