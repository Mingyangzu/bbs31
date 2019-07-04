<?php

define('DISABLEXSSCHECK', true);

require '../../../../source/class/class_core.php';

$discuz = C::app();

$cachelist = array('plugin');

$discuz->cachelist = $cachelist;
$discuz->init();
loadcache('plugin');
$gid = intval($_GET['gid']);
$duid = intval($_GET['d']);
if($duid>0){
    $gid = $gid.'_'.$duid;
}
$_G['siteurl'] = str_replace('source/plugin/aljhtx/api', '',$_G['siteurl'] );
$filepath = 'source/plugin/aljhtx/static/img/qrcode/goods/'.$gid;
$file = $filepath.'.jpg';
if(is_file(DISCUZ_ROOT.$file)){
    $url = $_G['siteurl'].$file;
    dheader('Location:'.$url);
    exit;
}
$config = $_G['cache']['plugin']['aljwsq'];
$appid = $config['appid'];
$appsecret = $config['appsecret'];
if($appid && $appsecret){
    require_once DISCUZ_ROOT . 'source/plugin/aljwsq/mapp_wechatclient.lib.class.php';
    $wechat_client = new WeChatClient($appid, $appsecret);
    $access_token = $wechat_client -> getAccessToken();

    $scene_id = array(
        'scene' => array('scene_str' => 'gid_'.$gid)
    );

    $postdatas = array(
        'action_name' => 'QR_LIMIT_STR_SCENE',
        'action_info' => $scene_id
    );

    $postdatas = json_encode($postdatas);
    $qrcode = https_request('https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token='.$access_token, $postdatas);
    $qrcode = json_decode($qrcode,true);

    if(!is_file(DISCUZ_ROOT.$file) && $gid>0){
        $returndata = downloadImage('https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket='.$qrcode['ticket'],$filepath);
        DB::insert('aljhtx_qrcode_goods', array(
            'gid' => $gid,
            'qrcode' => $returndata['filename'],
            'ticket' => $returndata['ticket'],
            'type' => 1,
            'scene_str' => 'gid_'.$gid,
            'dateline' => TIMESTAMP,
        ));
    }
    if(is_file(DISCUZ_ROOT.$file)){
        $url = $_G['siteurl'].$file;
        dheader('Location:'.$url);
        exit;
    }
}




function https_request($url, $data = null) {
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
    if (!empty($data)) {
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
    }
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $output = curl_exec($curl);
    curl_close($curl);
    return $output;
}

function downloadImage($url, $filepath) {
    $filepath = DISCUZ_ROOT.$filepath;
    //服务器返回的头信息
    $responseHeaders = array();
    //原始图片名
    $originalfilename = '';
    //图片的后缀名
    $ext = '';
    $ch = curl_init($url);
    //设置curl_exec返回的值包含Http头
    curl_setopt($ch, CURLOPT_HEADER, 1);
    //设置curl_exec返回的值包含Http内容
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    //设置抓取跳转（http 301，302）后的页面
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    //设置最多的HTTP重定向的数量
    curl_setopt($ch, CURLOPT_MAXREDIRS, 2);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//禁止直接显示获取的内容 重要

    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); //不验证证书下同

    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false); //


    //服务器返回的数据（包括http头信息和内容）
    $html = curl_exec($ch);
    //获取此次抓取的相关信息
    $httpinfo = curl_getinfo($ch);
    curl_close($ch);
    if ($html !== false) {
        //分离response的header和body，由于服务器可能使用了302跳转，所以此处需要将字符串分离为 2+跳转次数 个子串
        $httpArr = explode("\r\n\r\n", $html, 2 + $httpinfo['redirect_count']);
        //倒数第二段是服务器最后一次response的http头
        $header = $httpArr[count($httpArr) - 2];
        //倒数第一段是服务器最后一次response的内容
        $body = $httpArr[count($httpArr) - 1];
        $header.="\r\n";

        //获取最后一次response的header信息
        preg_match_all('/([a-z0-9-_]+):\s*([^\r\n]+)\r\n/i', $header, $matches);
        if (!empty($matches) && count($matches) == 3 && !empty($matches[1]) && !empty($matches[1])) {
            for ($i = 0; $i < count($matches[1]); $i++) {
                if (array_key_exists($i, $matches[2])) {
                    $responseHeaders[$matches[1][$i]] = $matches[2][$i];
                }
            }
        }
        //获取图片后缀名
        if (0 < preg_match('{(?:[^\/\\\\]+)\.(jpg|jpeg|gif|png|bmp)$}i', $url, $matches)) {
            $originalfilename = $matches[0];
            $ext = $matches[1];
        } else {
            if (array_key_exists('Content-Type', $responseHeaders)) {
                if (0 < preg_match('{image/(\w+)}i', $responseHeaders['Content-Type'], $extmatches)) {
                    $ext = $extmatches[1];
                }
            }
        }
        //保存文件
        if (!empty($ext)) {
            $filepath .= ".$ext";
            //如果目录不存在，则先要创建目录s
            $local_file = fopen($filepath, 'w');
            if (false !== $local_file) {
                if (false !== fwrite($local_file, $body)) {
                    fclose($local_file);
                    $sizeinfo = getimagesize($filepath);
                    return array('filepath' => realpath($filepath), 'width' => $sizeinfo[0], 'height' => $sizeinfo[1], 'orginalfilename' => $originalfilename, 'filename' => pathinfo($filepath, PATHINFO_BASENAME));

                }
            }
        }
    }
    return false;
}
