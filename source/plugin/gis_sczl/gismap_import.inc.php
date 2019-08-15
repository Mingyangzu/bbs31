<?php

if (!defined('IN_DISCUZ')) {
    exit('Access Denied');
}

$response = array('code' => 0, 'data' => array(), 'msg' => '', 'time' => time());

//提交数据 验证用户是否管理员权限
$umsg = isadminuser($_G);
if ($umsg === true) {

    $tablepre = $_G['config']['db'][1]['tablepre'];

    if (!empty($_FILES)) {
        // 保存文件
        $filepath = upfiles($_FILES);

        if ($filepath != false) {
            
            // 读取文件 转数组
            $arrInfos = filetoArray($filepath);
            if (empty($arrInfos)) {
                unlink($filepath); //删除 文件
                $response['msg'] .= '获取表中数据失败';
            } else {
                // 添加数据入库
                if (saveArrtodb($arrInfos, $tablepre)) {
                    $response['msg'] = '数据导入成功';
                } else {
                    $response['msg'] .= '数据导入失败';
                }
            }
        }
    }
} else {
    $response['msg'] = '请重新登录!';
}

include template('gis_sczl:gismap_import');




// 验证是否管理员
function isadminuser($_G) {
    $usession = C::app()->session;
    return ($_G['uid'] > 0 && $_G['adminid'] === '1' && !empty($usession->var['username']) ) ? true : false;
}


function filetoArray($filePath) {
    require_once dirname(__FILE__) . '/PHPExcel/Classes/PHPExcel/IOFactory.php';

    $objReader = PHPExcel_IOFactory::load($filePath);
    $reader = $objReader->getWorksheetIterator();
    //循环读取sheet  

    foreach ($reader as $sheet) {
        $content = $sheet->getRowIterator();   //读取表内容  
        //逐行处理  
        $res_arr = array();
        $field_arr = array();

        foreach ($content as $key => $items) {
            $rows = $items->getRowIndex();           //行  
            $columns = $items->getCellIterator();       //列  
            $row_arr = array();

            //逐列读取
            $lines = 0;
            foreach ($columns as $head => $cell) {
                //获取cell中数据  
                $data = $cell->getValue();

                if ($rows > 1) {
                    //从第二行开始,存每行字段值 
                    $row_arr[$field_arr[$lines]] = $data;
                } else {
                    //存第一行字段名 
                    $field_arr[] = $data;
                }
                $lines++;
            }
            if ($rows > 1) {
                //从第二行开始,存每行字段值 
                $res_arr[] = $row_arr;
            }
        }
    }

    return $res_arr;
}

// 表数据入库
function saveArrtodb($data, $tablepre = '') {
    global $filepath, $response;
    
    $savearr = $uparr = array();
    $createtime = time();
    $inputSql = $updateSql = '';
    foreach ($data as $key => $val) {
        $ucode = $createtime .'+'. $val['aid'] ; //md5(time() .'+'. $v['aid']);
        $savearr = "('{$val['gisucode']}', {$val['types']}, '{$val['lnglat']}', '{$val['icon']}', {$val['backgrse']}, {$val['radius']}, {$createtime}) ;";
        $inputSql .= "insert into " . $tablepre . "portal_article_gis(gisucode,types,lnglat,icon,backgrse,radius,create_time) values" . $savestr;
        $updateSql .= 'update' . $tablepre . "portal_article_title set gisucode='".$ucode."' where id=".$val['aid'].';' ;
    }

//    print_r($inputSql) ; 
//    print_r($updateSql); 
//    die;
    
    try{
        $info = DB::query($inputSql);
        return $info;
    } catch (Exception $e){
        unlink($filepath); //删除 文件
        $response['msg'] = $e->getMessage() . "<br/>" ;
        return false;
    }
    
}


//上传文件
function upfiles($data) {
    global $response;

    $data = $data['upfiles'];
    if ($data["error"] > 0) {
        $response['msg'] = '上传错误 ' . $data["error"];
        return false;
    }

    $randNum = rand(1, 10000) . rand(1, 10000);
    $filetype = $data["type"];
    $filename = $data["name"];
    $dirname = '/source/plugin/gis_sczl/uploadfiles/';
    $savedir = realpath(dirname(__FILE__)) . '/uploadfiles/';
    $savepath = $savedir . $filename;
    $allowtype = array('application/octet-stream', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/vnd.ms-excel', 'application/x-excel');

    //检查文件格式
    if (!in_array($data["type"], $allowtype)) {
        $response['msg'] = '上传文件应为xls/xlsx文件';
        return false;
    }

    if (file_exists($savepath)) {
        $response['msg'] = '文件重复上传';
        return false;
    }

    //检查文件大小是否超出限制
    if ($data["size"] > 10240000) {
        $response['msg'] = '文件最大10M';
        return false;
    }

    //创建目录失败
    if (!file_exists($savedir) && !mkdir($savedir, 0777, true)) {
        $response['msg'] = '目录创建失败';
        return false;
    } else if (!is_writeable($savedir)) {
        $response['msg'] = '目录没有写权限';
        return false;
    }

    //移动文件
    if (!(move_uploaded_file($data["tmp_name"], $savepath) && file_exists($savepath))) {
        return false;
    } else {
//        return $dirname . $filename;
        return $savepath;
    }
}
