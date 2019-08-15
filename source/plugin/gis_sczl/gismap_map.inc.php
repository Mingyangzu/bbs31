<?php

if (!defined('IN_DISCUZ')) {
    exit('Access Denied');
}

$_G['gis']['dir'] = '/source/plugin/gis_sczl/';
$_G['gis']['dirstyle'] = '/source/plugin/gis_sczl/style/';
$siturl = $_G['siteurl'];

$_G['siteurl']; // 当前网站域名
//验证用户权限
$umsg = isadminuser($_G);


function isadminuser($_G) {
    $usession = C::app()->session;
    return ($_G['uid'] > 0 && $_G['adminid'] === '1' && !empty($usession->var['username'])) ? true : false;
}

$defaultgis = false;
$response = array('code' => 0, 'data' => array(), 'msg' => '', 'time' => time());

switch ($_GET['mod']) {
    case 'index':
        include template('gis_sczl:gd_iframe');
        break;
    case 'search':
        include template('gis_sczl:gis_search');
        break;
    case 'edit':
        include template('gis_sczl:gis_edit');
        break;
    case 'addmapres':
        $mapboxtop = 1;
        $float = false;
        include template('gis_sczl:addmapres');
        break;
    default:
        return false;
}

//根据资源目录获取 默认标注信息
function getdefaultgis($argis) {
    if(!$argis){
        return false;
    }
    $condition_str = ' id = '. $argis;
    $options = 'texts';
    $info = C::t('#gis_sczl#common_gis_article')->findinfo($condition_str, $options);

    if (!empty($info[0]['texts'])) {
        return $info[0]['texts'];
    } else {
        return false;
    }
}



function jsonresponse($res) {
    header('Access-Control-Allow-Origin:*');  // 接口调试 开放跨域请求, 上线后关闭
    header("content:application/json;chartset=uft-8");
    echo json_encode($res);
    die;
}

