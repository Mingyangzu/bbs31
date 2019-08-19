<?php

if (!defined('IN_DISCUZ')) {
    exit('Access Denied');
}


$_G['gis']['dir'] = '/source/plugin/gis_sczl/';
$_G['gis']['dirstyle'] = '/source/plugin/gis_sczl/style/';
$siturl = $_G['siteurl'];

$response = array('code' => 500, 'msg' => '', 'data' => array(), 'count' => 0);


//提交数据 验证用户是否管理员权限
$umsg = isadminuser($_G);  
if ($umsg == false) {
    echo '请重新登录后操作!';
    die;
}

switch ($_REQUEST['mod']) {
    case 'resgislist':
        resgislist($_GET);
        break;
//    case 'resgisadd':
//        resgisadd($_POST);
//        break;
    case 'resgisdel':
        resgisdel($_POST);
        break;
//    case 'resgisarr':
//        resgisarr($_POST);
//        break;
//    case 'resgisinfo':
//        resgisinfo($_POST);
//        break;
}


$level1 = json_encode( C::t('#gis_sczl#common_resources')->listarr(' where types = 1 ', 'id, name', 0, 1000) );
include template('gis_sczl:resgislist');

// 验证是否管理员
function isadminuser($_G) {
    $usession = C::app()->session;
    return ($_G['uid'] > 0 && $_G['adminid'] === '1' && !empty($usession->var['username']) ) ? true : false;
}

function jsonresponse($res) {
//    header('Access-Control-Allow-Origin:*');  // 接口调试 开放跨域请求, 上线后关闭
    header("content:application/json;chartset=uft-8");
    echo json_encode($res);
    die;
}


// 列表
function resgislist($data) {
    global $response;

    $condition_str = '';
    $data['title'] && $condition_str = " where b.title like '%". $data['title'] ."%' ";
    $pages = empty($data['page']) ? 1 : $data['page'];
    $nums = empty($data['limit']) ? 10 : $data['limit'] ;
    $start = ($pages - 1) * $nums;
    $limits = " limit $start,$nums";
    $orderby = ' order by a.id desc ';
    $options = 'a.id, a.gisucode, a.types, a.lnglat, a.icon, a.backgrse, a.radius, a.create_time, b.aid, b.title';
    $lists = C::t('#gis_sczl#portal_article_gis')->joinArticlelist($condition_str, $options, $limits, $orderby);

    if(!empty($lists)){
        foreach($lists as &$v){
            $v['create_time'] = date('Y-m-d H:i:s', $v['create_time']);
        }
        $counts = C::t('#gis_sczl#portal_article_gis')->joinArticleCount($condition_str)[0]['nums'] ;
        $response['msg'] = 'success';
        $response['data'] = $lists;
        $response['count'] = $counts;
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
    $resid = is_array($data['resid']) ? implode(',', $data['resid']) : $data['resid'] ;   
    $condition_str = " id in(".$resid.') ';
    $info = C::t('#gis_sczl#portal_article_gis')->delete($condition_str, 100);
    
    if(!empty($info)){
        $response['msg'] = 'success';
        $response['data'] = $info;
        $response['code'] = 0;
    }else{
        $response['msg'] = '删除数据失败';
    }
    
    jsonresponse($response);
}




// 详情
function resgisinfo($data) {
    global $response;

    $condition_str = ' where id = ' . $data['resid'] ;
    $options = 'a.id,a.name,a.fid,a.types,b.name title';
    $lists = C::t('#gis_sczl#common_resources')->querylist($condition_str, $options, 0, 1);

    if(!empty($lists)){
        $response['msg'] = 'success';
        $response['data'] = $lists;
        $response['code'] = 0;
    }else{
        $response['code'] = 0;
        $response['msg'] = '无符合数据';
    }
    
    jsonresponse($response);
}


// 添加数据
function resgisadd($data){
    global $response;
    
    if(empty($data['name'])){
        $response['msg'] = '提交数据有误!';
        jsonresponse($response);
    }
    
    $savedata = array();
    $savedata['name'] = $data['name'];
    
    if($data['level3']){
        $savedata['types'] = 4;
        $savedata['fid'] = $data['level3'];
    }else if($data['level2']){
        $savedata['types'] = 3;
        $savedata['fid'] = $data['level2'];
    }else if($data['level1']){
        $savedata['types'] = 2;
        $savedata['fid'] = $data['level1'];
    }else{
        $savedata['types'] = 1;
        $savedata['fid'] = 0;
    }
    
    if(empty($data['editres'])){
        $info = C::t('#gis_sczl#common_resources')->insert($savedata);
    }else{
        $condition = ' id = '. $data['editres'];
        $info = C::t('#gis_sczl#common_resources')->updateres($savedata, $condition);
    }
    
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
        $condition_str = ' where types = 1 and fid = 0 ';
    }else{
        $condition_str = ' where fid = '. $data['fid'] ;
    }   
    
    $options = 'id,name,types';
    $info = C::t('#gis_sczl#common_resources')->listarr($condition_str, $options, 0, 1000);
    
    if(!empty($info)){
        $response['msg'] = 'success';
        $response['data'] = $info;
        $response['code'] = 0;
    }else{
        $response['code'] = 0;
        $response['msg'] = '无更多数据';
    }
    
    jsonresponse($response);
}