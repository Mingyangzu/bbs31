<?php

if (!defined('IN_DISCUZ')) {
    exit('Access Denied');
}


$_G['gis']['dir'] = '/source/plugin/gis_sczl/';
$_G['gis']['dirstyle'] = '/source/plugin/gis_sczl/style/';
$siturl = $_G['siteurl'];

//提交数据 验证用户是否管理员权限
$umsg = isadminuser($_G);  //测试不验证cookie,正式打开
if ($umsg == false) {
    jsonresponse('请重新登录后操作!');
}

$response = array('code' => 500, 'msg' => '', 'data' => array(), 'count' => 0);

switch ($_REQUEST['mod']) {
    case 'resgislist':
        resgislist($_GET);
        break;
    case 'resgisadd':
        break;
    case 'resgisdel':
        resgisdel($_POST);
        break;
    case 'resgisarr':
        resgisarr($_POST);
        break;
}



include template('gis_sczl:resgislist');

// 验证是否管理员
function isadminuser($_G) {
    $usession = C::app()->session;
    return ($_G['uid'] > 0 && $_G['adminid'] === '1' && !empty($usession->var['username']) ) ? true : false;
}

function jsonresponse($res) {
    header("content:application/json;chartset=uft-8");
    echo json_encode($res);
    die;
}


// 列表
function resgislist($data) {
    global $response;

    $condition_str = '';
    $limits = empty($data['limit']) ? 10 : $data['limit'] ;
    $pages = empty($data['page']) ? 1 : $data['page'];
    $start = ($pages - 1) * $limits;
    $options = 'id,name,fid,types';
    $lists = C::t('#gis_sczl#common_resources')->listarr($condition_str, $options, $start, $limits);
    if(!empty($lists)){
        $counts = C::t('#gis_sczl#common_resources')->counts($condition_str);
        $response['msg'] = 'success';
        $response['data'] = $lists;
        $response['count'] = $counts[0]['count'];
        $response['code'] = 0;
    }else{
        $response['msg'] = '无符合数据';
    }
    
    jsonresponse($response);
}


//删除
function resgisdel($data){
    global $response;
    
    if(empty($data['resid'])){
        $response['msg'] = '提交数据有误!';
        jsonresponse($response);
    }
    $resid = $data['resid'] ;
    $condition_str = " id in(".$resid.') ';
    $info = true; //C::t('#gis_sczl#common_resources')->delete($condition_str);
    
    if(!empty($info)){
        $response['msg'] = 'success';
        $response['data'] = $info;
        $response['code'] = 0;
    }else{
        $response['msg'] = '删除数据失败';
    }
    
    jsonresponse($response);
}


function resgisadd($data){
    global $response;
    
    if(empty($data['name'])){
        $response['msg'] = '提交数据有误!';
        jsonresponse($response);
    }
    
    $savedata = array();
    $savedata['name'] = $data['name'];
    $savedata['fid'] = $data['fid'];
    $savedata['types'] = $data['level'];
    $info = C::t('#gis_sczl#common_resources')->insert($savedata);
    
    if(!empty($info)){
        $response['msg'] = 'success';
        $response['data'] = $info;
        $response['code'] = 0;
    }else{
        $response['msg'] = '添加数据失败';
    }
    
    jsonresponse($response);
}

// 获取资源目录列表 三级联动
function resgisarr($data){
    global $response;
    
    if(empty($data['fid'])){
        $condition_str = ' types = 0 ';
    }else{
        $condition_str = ' fid = '. $data['fid'] ;
    }   
    
    $options = 'id,name,types';
    $info = C::t('#gis_sczl#common_resources')->listarr($condition_str, $options, 0, 1000);
    
    if(!empty($info)){
        $response['msg'] = 'success';
        $response['data'] = $info;
        $response['code'] = 0;
    }else{
        $response['msg'] = '添加数据失败';
    }
    
    jsonresponse($response);
}