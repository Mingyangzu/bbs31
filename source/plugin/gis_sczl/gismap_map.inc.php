<?php

if (!defined('IN_DISCUZ')) {
    exit('Access Denied');
}

$_G['gis']['dir'] = '/source/plugin/gis_sczl/';
$_G['gis']['dirstyle'] = '/source/plugin/gis_sczl/style/';


$_G['siteurl']; // 当前网站域名
//验证用户权限
$umsg = isadminuser($_G);

//$testvar = '我从后端来';

function isadminuser($_G) {
    $usession = C::app()->session;
    return ($_G['uid'] > 0 && $_G['adminid'] === '1' && !empty($usession->var['username'])) ? true : false;
}

$defaultgis = false;

switch ($_GET['mod']) {
    case 'article':
        $defaultgis = getdefaultgis($_GET['res']);
        $defaultgis = $defaultgis !== false ? json_encode($defaultgis) : false ; 
        include template('gis_sczl:gismap_article');
        break;
    case 'index':
        include template('gis_sczl:gd_iframe');
        break;
    case 'articlegis':
        addarticle($_POST);
        break;
    default:
        $mapboxtop = $_GET['mod'] == 'addmap' ? 5 : 50;
        include template('gis_sczl:gismap_iframe');
}

//根据资源目录获取 默认标注信息
function getdefaultgis($resid) {
    global $response;

    if (empty($resid)) {
        $resid = '9';
    }

    $residarr = is_array($resid) ? implode(',', $resid) : $resid;
    $condition_str = ' resid3 in (' . $residarr . ') and types = 1 ';
    $options = 'name,lnglat,texts,imgs,icon,color';
    $info = C::t('#gis_sczl#common_gis')->findlist($condition_str, $options, 1, 1000);
//print_r($info);die; 

    if (!empty($info) && is_array($info)) {
        foreach ($info as $k => $v) {
            $info[$k]['lnglat'] = json_decode($v['lnglat'], true);
            $info[$k]['http'] = $_G['siteurl'];
            $info[$k]['img'] = $v['imgs'];
            $info[$k]['style'] = 2;
        }
        return $info;
    } else {
        return false;
    }
}
