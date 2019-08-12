<?php

if (!defined('IN_DISCUZ')) {
    exit('Access Denied');
}

$response = array('code' => 500, 'data' => array(), 'msg' => '');
$siturl = $_G['siteurl'];
//jsonresponse(C::app()->session);

$_G['gis']['dir'] = '/source/plugin/gis_sczl/';
$_G['gis']['dirstyle'] = '/source/plugin/gis_sczl/style/';
$postmd = array('gisinput', 'gisdel', 'getgis', 'getresgis', 'getres', 'getdefaultgis', 'articlegis', 'upimgs');
$getmd = array('getreslist', 'getindex', 'searchlist');  // 上线后关闭 getindex
//提交数据 验证用户是否管理员权限
$umsg = true; //isadminuser($_G);  //测试不验证cookie,正式打开

if ($_SERVER['REQUEST_METHOD'] == 'GET' && in_array($_GET['mod'], $getmd)) {
    switch ($_GET['mod']) {
        case 'getreslist':
            getreslist($_GET);
            break;
        case 'getindex':
            getindex($_GET);
            break;
        case 'searchlist':
            searchlist($_GET);
            break;
        default:
            $response['msg'] = '请求接口有误';
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && in_array($_POST['mod'], $postmd)) {
    switch ($_GET['mod']) {
        case 'gisinput':
            if ($umsg !== true) {
                $response['msg'] = '请重新登录';
                jsonresponse($response);
            }
            gisinput($_POST);
            break;
        case 'gisdel':
            if ($umsg !== true) {
                $response['msg'] = '请重新登录';
                jsonresponse($response);
            }
            gisdel($_POST);
            break;
        case 'getgis':
            getgis($_POST);
            break;
        case 'getresgis':
            getresgis($_POST);
            break;
        case 'getres':
            getres($_POST);
            break;
        case 'getdefaultgis':
            getdefaultgis($_POST);
            break;
        case 'articlegis':
            if ($umsg !== true) {
                $response['msg'] = '请重新登录';
                jsonresponse($response);
            }
            articlegis($_POST);
            break;
        case 'upimgs':
            if ($umsg !== true) {
                $response['msg'] = '请重新登录';
                jsonresponse($response);
            }
            upimgs($_FILES, $_POST);
            break;
        default:
            $response['msg'] = '请求接口有误';
    }
}

jsonresponse($response);

// 验证是否管理员
function isadminuser($_G) {
    $usession = C::app()->session;
    return ($_G['uid'] > 0 && $_G['adminid'] === '1' && !empty($usession->var['username']) ) ? true : false;
}

function jsonresponse($res) {
    header('Access-Control-Allow-Origin:*');  // 接口调试 开放跨域请求, 上线后关闭
    header("content:application/json;chartset=uft-8");
    echo json_encode($res);
    die;
}

function getindex($data) {
    include template('gis_sczl:index');
    die;
}

// 添加、编辑标注点 线 面
function gisinput($data) {
    global $response;

    $mustarr = empty($data['gisig']) ? array() : array('name', 'types', 'lnglat', 'resid', 'resid2');    
    
    $checkst = checkoptions($data, $mustarr);
    if ($checkst !== true) {
        $response['msg'] = $checkst;
        jsonresponse($response);
    }
//    jsonresponse($data);
    $savedata = array();
    !empty($data['name']) && $savedata['name'] = $data['name'];
    !empty($data['types']) && $savedata['types'] = $data['types'];
    !empty($data['lnglat']) && $savedata['lnglat'] = str_replace('"', '', json_encode($data['lnglat']));  // 去除双引号
    !empty($data['icon']) && $savedata['icon'] = $data['icon'];
    !empty($data['color']) && $savedata['color'] = $data['color'];
    !empty($data['radius']) && $savedata['radius'] = $data['radius'];
    if(empty($data['resid'])){
        $response['msg'] = '请选择一级目录';
        jsonresponse($response);
    }  
    $savedata['resid'] = $data['resid'];
    
    if(empty($data['resid2'])){
        $response['msg'] = '请选择二级目录';
        jsonresponse($response);
    } 
    $savedata['resid2'] = $data['resid2'];
    
    // 二级目录下有三级目录时,三级目录为必选项
    if(empty($data['resid3'])){
        $leves3 = C::t('#gis_sczl#common_resources')->counts(" where fid = ".$data['resid2']);
        if($leves3[0]['count'] > 0){
            $response['msg'] = '所选二级目录下有三级目录,请选择三级目录';
            $response['count'] = $leves3[0]['count'];
            jsonresponse($response);
        }
    }else{
        $savedata['resid3'] = $data['resid3'];
    }
    
    $savedata['texts'] = $data['texts'];
    
    
    if(!empty($data['file'])){
        $savedata['imgs'] = $data['file'];
    }
//    if(!empty($_FILES)){
//        $savedata['imgs'] = upfiles($_FILES['file']);
//    }
//    jsonresponse($savedata);

    if (isset($data['gisid'])) {
        $savedata['update_time'] = time();
        $condition = array('id' => $data['gisid']);
        $info = C::t('#gis_sczl#common_gis')->update($savedata, $condition);
    } else {
        $savedata['create_time'] = time();
        $info = C::t('#gis_sczl#common_gis')->inster($savedata);
    }

//        jsonresponse($info);

    if ($info == true) {
        $response['code'] = 0;
        $response['msg'] = 'success';
    } else {
        $response['msg'] = '保存失败!';
    }

    jsonresponse($response);
}

// 删除标注点
function gisdel($data) {
    global $response;

    if (empty($data['gisid'])) {
        $response['msg'] = '提交数据有误!';
        jsonresponse($response);
    }

    $condition = array('id' => $data['gisid']);
    $info = C::t('#gis_sczl#common_gis')->delete($condition);
//    jsonresponse($info);

    if ($info == true) {
        $response['code'] = 0;
        $response['msg'] = 'success';
    } else {
        $response['msg'] = '删除失败!';
    }

    jsonresponse($response);
}

// 搜索列表
function searchlist($data) {
    global $response;

    !empty($data['types']) && $data['types'] = (int) $data['types'];
    if (empty($data['types']) && empty($data['name'])) {
        $response['code'] = 0;
        $response['msg'] = '';  //'提交数据有误!';
        jsonresponse($response);
    }

    $limit = empty($data['limit']) ? 10 : $data['limit'];
    $pages = ($data['page'] < 2) ? 0 : (int) $data['page'] - 1;

    $condition_str = '';
    !empty($data['types']) && $condition_str .= ' types = ' . $data['types'];
    !empty($data['types']) && !empty($data['name']) && $condition_str .= ' and ';
    !empty($data['name']) && $condition_str .= " name like'%" . $data['name'] . "%' ";

    $options = 'id,name';
    $info = C::t('#gis_sczl#common_gis')->findlist($condition_str, $options, $pages * $limit, $limit);
//    jsonresponse($info);

    $counts = C::t('#gis_sczl#common_gis')->counts($condition_str);
//    jsonresponse($counts);

    if (!empty($info) && is_array($info)) {
        $response['code'] = 0;
        $response['msg'] = 'success';
        $response['data'] = $info;
        $response['count'] = $counts;
    } else {
        $response['code'] = 0;
        $response['msg'] = '无更多数据!';
        $response['count'] = 0;
    }

    jsonresponse($response);
}

//获取标注点详情
function getgis($data) {
    global $response;

    $data['gisid'] = (int) $data['gisid'];
    if (empty($data['gisid'])) {
        $response['msg'] = '提交数据有误!';
        jsonresponse($response);
    }

    $condition_str = " id = " . $data['gisid'];
    $options = 'name,types,lnglat,radius,resid,resid2,resid3,texts,imgs,icon,color';
    $info = C::t('#gis_sczl#common_gis')->findinfo($condition_str, $options);
//    jsonresponse($info);

    if (!empty($info) && is_array($info)) {
        $response['code'] = 0;
        $response['msg'] = 'success';
        $response['data'] = $info;
    } else {
        $response['msg'] = '获取失败!';
    }

    jsonresponse($response);
}

// 获取资源目录
//function getreslist($data) {
//    global $response;
//
//    $data['resid'] = (int) $data['resid'];
//
//    $options = 'id,name,fid,types';
//    $data['resid'] > 0 && $condition_str = ' WHERE id = ' . $data['resid'];
//    $info = C::t('#gis_sczl#common_resources')->findlist($condition_str, $options);
////    jsonresponse($info);
//
//    if (!empty($info) && is_array($info)) {
//        // 格式化列表
//        $onelist = $towlist = $threelist = [];
//        foreach ($info as $v) {
//            switch ($v['types']) {
//                case 1:
//                    $onelist[] = array('title' => $v['name'], 'id' => $v['id'], 'level' => 1, 'children' => []);
//                    break;
//                case 2:
//                    $twolist[$v['fid']][] = array('title' => $v['name'], 'id' => $v['id'], 'level' => 2, 'children' => []);
//                    break;
//                case 3:
//                    $threelist[$v['fid']][] = array('title' => $v['name'], 'id' => $v['id'], 'level' => 3, 'children' => []);
//                    break;
//            }
//        }
//        foreach ($twolist as $key => $val) {
//            foreach ($val as $kk => $vv) {
//                $twolist[$key][$kk]['children'] = $threelist[$vv['id']];
//            }
//        }
//        foreach ($onelist as $ok => $ov) {
//            $onelist[$ok]['children'] = $twolist[$ov['id']];
//        }
//
////        jsonresponse($onelist);  die;
//        $response['code'] = 0;
//        $response['msg'] = 'success';
//        $response['data'] = $onelist;
//    } else {
//        $response['code'] = 0;
//        $response['msg'] = '无更多数据!';
//    }
//
//    jsonresponse($response);
//}

// 获取文章目录列表
function getreslist($data) {
    global $response;

    $data['resid'] = (int) $data['resid'];

    $options = 'catid,catname,upid';
    $data['resid'] > 0 && $condition_str = ' WHERE catid = ' . $data['resid'];
    $info = C::t('#gis_sczl#portal_category')->findlist($condition_str, $options);
//    jsonresponse($info);

    if (!empty($info) && is_array($info)) {
        // 格式化列表
        $infolist = array();
        foreach($info as $val){
            $infolist[$val['catid']] = $val;
        }
        
        $onelist = $towlist = $threelist = array();
        foreach ($infolist as $v) {
            if($v['upid'] == 0){
                $onelist[] = array('title' => $v['catname'], 'id' => $v['catid'], 'level' => 1, 'children' => []);
            }else if($infolist[$v['upid']]['upid'] == 0){
                $twolist[$v['upid']][] = array('title' => $v['catname'], 'id' => $v['catid'], 'level' => 2, 'children' => []);
            }else{
                $threelist[$v['upid']][] = array('title' => $v['catname'], 'id' => $v['catid'], 'level' => 3, 'children' => []);
            }
        }
//        jsonresponse($twolist);  
        
        foreach ($twolist as $key => $val) {
            foreach ($val as $kk => $vv) {
                $twolist[$key][$kk]['children'] = $threelist[$vv['id']];
            }
        }
        foreach ($onelist as $ok => $ov) {
            $onelist[$ok]['children'] = $twolist[$ov['id']];
        }

//        jsonresponse($onelist); 
        $response['code'] = 0;
        $response['msg'] = 'success';
        $response['data'] = $onelist;
    } else {
        $response['code'] = 0;
        $response['msg'] = '无更多数据!';
    }

    jsonresponse($response);
}



// 获取资源目录下 标注信息
function getresgis($data) {
    global $response;

    if (empty($data['resid3']) && empty($data['resid2'])) {
            $response['msg'] = '提交参数为空';
            jsonresponse($response);
    }
    
    $condition_str = '';
    if(!empty($data['resid3'])){
        $resid3 = is_array($data['resid3']) ? implode(',', $data['resid3']) : $data['resid3'];
        $condition_str = ' resid3 in(' . $resid3 . ')';
    }
    
    if(!empty($data['resid2'])){
        $resid2 = is_array($data['resid2']) ? implode(',', $data['resid2']) : $data['resid2'];
        $condition_str != '' && $condition_str .= ' or ';
        $condition_str .= ' ( resid3 = 0 and resid2 in(' . $resid2 . ') )' ;
    }
    
    $options = 'id,name,types,lnglat,radius,texts,imgs,icon,color';
    $info = C::t('#gis_sczl#common_gis')->findlist($condition_str, $options, 0, 500);
//    jsonresponse($info);

    if (!empty($info) && is_array($info)) {
        foreach ($info as $k => $v) {
            $info[$k]['http'] = 'http://baidu.com';
            $info[$k]['style'] = 2;
        }
        $response['code'] = 0;
        $response['msg'] = 'success';
        $response['data'] = $info;
    } else {
        $response['code'] = 0;
        $response['msg'] = 'success';
        $response['data'] = [];
    }

    jsonresponse($response);
}

//根据资源目录获取 默认标注信息
function getdefaultgis($data) {
    global $response;

    if (empty($data['resid'])) {
        $data['resid'] = '8';
        // $response['msg'] = '提交参数有误';
        // jsonresponse($response);
    }

    $residarr = is_array($data['resid']) ? implode(',', $data['resid']) : $data['resid'];
    $condition_str = ' resid3 in (' . $residarr . ') ';
    $options = 'id,name,types,lnglat,radius,resid3,texts,imgs,icon,color';
    $info = C::t('#gis_sczl#common_gis')->findlist($condition_str, $options, 0, 1000);
//    jsonresponse($info);    

    if (!empty($info) && is_array($info)) {
        foreach ($info as $k => $v) {
            $info[$k]['http'] = 'http://baidu.com';
            $info[$k]['style'] = 2;
        }
        $response['code'] = 0;
        $response['msg'] = 'success';
        $response['data'] = $info;
    } else {
        $response['code'] = 0;
        $response['msg'] = '无更多数据!';
    }

    jsonresponse($response);
}

// 获取资源目录 三级联动
function getres($data) {
    global $response;

    if(empty($data['resid'])){
        $data['resid'] = 0;
    }else{
        $data['resid'] = (int) $data['resid'];
    }

    $options = 'id,name,fid,types';
    $condition_str = ' WHERE fid = ' . $data['resid'];
    $info = C::t('#gis_sczl#common_resources')->findlist($condition_str, $options);
//    jsonresponse($info);

    if (!empty($info) && is_array($info)) {
        $response['code'] = 0;
        $response['msg'] = 'success';
        $response['data'] = $info;
    } else {
        $response['code'] = 0;
        $response['msg'] = '无更多数据!';
    }

    jsonresponse($response);
}

// 验证参数
function checkoptions($data, $mustoptions = array()) {
    $msg = true;
    $optionzh = array('gisid' => '标注点', 'name' => '名称', 'types' => '类型', 'lnglat' => '经纬度', 'resid' => '一级资源目录', 'resid2' => '二级资源目录');
    foreach ($mustoptions as $v) {
        if (!isset($data[$v])) {
            return $optionzh[$v] . ' 不能为空';
        }
    }

    foreach ($data as $key => $val) {
        $val = trim($val);
        switch ($key) {
            case 'name' :
                $msg = empty($val) ? '名称有误' : true;
                break 2;
            case 'types':
                $msg = !in_array($val, array(1, 2, 3)) ? '标注点内容有误' : true;
                break 2;
            case 'gisid':
                $msg = $val < 1 ? '修改标注有误' : true;
                break 2;
            case 'lnglat':
                $msg = empty($val) ? '经纬度为空' : true;
                break 2;
            case 'resid':
                $msg = $val < 0 ? '所属一级资源目录有误' : true;
                break 2;
            case 'resid2':
                $msg = $val < 0 ? '所属二级资源目录有误' : true;
                break 2;
            default:
                break;
        }
    }
    return $msg;
}

function upfiles($data) {
    global $response;

    if ($data["error"] > 0) {
        $response['msg'] = '上传错误 ' . $data["error"];
        jsonresponse($response);
    }

    $randNum = rand(1, 10000) . rand(1, 10000);
    $filetype = $data["type"];
    $filename = time() . $randNum;
    $fileext = strtolower(strrchr($data['name'], '.'));
    $imgdir = date('Ym') . '/';
    $dirname = '/source/plugin/gis_sczl/uplocad/' . $imgdir;
    $savedir = realpath(dirname(__FILE__)) . '/uplocad/' . $imgdir;
    $savepath = $savedir . $filename . $fileext;
    $allowtype = array('image/gif', 'image/jpeg', 'image/pjpeg', 'image/png');

    //检查文件格式
    if (!in_array($data["type"], $allowtype)) {
        $response['msg'] = '图片格式应为jpg/gif/png';
        jsonresponse($response);
    }

    //检查文件大小是否超出限制
    if ($data["size"] > 4096000) {
        $response['msg'] = '图片最大4M';
        jsonresponse($response);
    }

    //创建目录失败
    if (!file_exists($savedir) && !mkdir($savedir, 0777, true)) {
        $response['msg'] = '目录创建失败';
        jsonresponse($response);
    } else if (!is_writeable($savedir)) {
        $response['msg'] = '目录没有写权限';
        jsonresponse($response);
    }

    //移动文件
    if (!(move_uploaded_file($data["tmp_name"], $savepath) && file_exists($savepath))) {
        $response['msg'] = '文件保存时出错';
        jsonresponse($response);
    } else {
        return $dirname . $filename . $fileext;
    }

    $response['msg'] = '上传失败';
    jsonresponse($response);
}

// 文章编辑页 加入地图数据入库
function articlegis($data) {
    global $response;

    if (empty($data['texts'])) {
        $response['msg'] = '提交参数有误';
        $response['data'] = '';
        jsonresponse($response);
    }

    $savedata = array();
    $savedata['texts'] = base64_decode($data['texts']);
    $savedata['create_time'] = time();
    $info = C::t('#gis_sczl#common_gis_article')->inster($savedata);

//    jsonresponse($info);

    if ($info == true) {
        $response['code'] = 0;
        $response['msg'] = 'success';
        $response['data'] = DB::insert_id();
    } else {
        $response['msg'] = '保存失败!';
        $response['data'] = '';
    }

    jsonresponse($response);
}


// 上传文件
function upimgs($files, $data = []){
    global $response;
//    jsonresponse($files);
     if (empty($files)) {
        $response['msg'] = '未获取到文件';
        $response['data'] = '';
        jsonresponse($response);
    }
    
    $response['code'] = 0;
    $response['msg'] = 'success';
    $response['data'] = upfiles($files['file']);
    jsonresponse($response);
    
}
