<?php

/**
 * Controller的二维码处理模块
 *
 * @author yuxinqi<yuxinqi@vip.qq.com>
 * @version 1.0
 * @link http://docs.liangjianyun.com/
 */


if(!defined('IN_DISCUZ')) {
    exit('Access Denied');
}

class QrcodeAction{
    public $page;

    public function __construct() {
        global $requestPage;
        $this->page = $requestPage;
        if(!$this->page->config->aljwx && ACTION == 'miniprogram'){
            //$this->page->showMessage(lang("plugin/aljhtx","qrcode_php_6"), '<a style="color: rgb(30, 159, 255);" target="_blank" href="http://addon.dismall.com/?@aljwx.plugin">' . lang("plugin/aljhtx","qrcode_php_3") . '</a>');
        }
        if((!$this->page->config->mapp_wechat || !$this->page->config->mapp_template) && ACTION == 'wechat'){
            if(!$this->page->config->mapp_wechat){
                //$this->page->showMessage(lang("plugin/aljhtx","qrcode_php_7"), '<a style="color: rgb(30, 159, 255);" target="_blank" href="http://addon.dismall.com/?@mapp_wechat.plugin">' . lang("plugin/aljhtx","qrcode_php_4") . '</a>');
            }else{
                //$this->page->showMessage(lang("plugin/aljhtx","qrcode_php_8"), '<a style="color: rgb(30, 159, 255);" target="_blank" href="http://addon.dismall.com/?@mapp_template.plugin">' . lang("plugin/aljhtx","qrcode_php_5") . '</a>');
            }
        }
    }

    public function wechat_delete(){
        DB::delete('aljhtx_qrcode_wechat', array('id' => $_GET['qid']));
        T::responseJson();
    }

    public function wechat(){
        if($this->page->get->render == 'yes'){
            $per = 20;
            $page = $this->page->get->page>0 ? $this->page->get->page :1;
            $start = ($page - 1) * $per;
            $logList = DB::fetch_all('select * from %t order by dateline desc limit %d, %d', array('aljhtx_qrcode_wechat', $start, $per));
            $count = DB::result_first('select count(*) from %t order by dateline desc', array('aljhtx_qrcode_wechat'));
            foreach($logList as $k => $v){
                $logList[$k]['dateline'] = dgmdate($v['dateline'], 'u');
                if($logList[$k]['type']){
                    $logList[$k]['qrcode'] = 'source/plugin/aljhtx/static/img/qrcode/wechat/'.$logList[$k]['qrcode'];
                }else{
                    $logList[$k]['qrcode'] = 'source/plugin/aljhtx/static/img/qrcode/wechat/tmp/'.$logList[$k]['qrcode'];
                }
            }
            T::responseJson(array(
                'code' => 0,
                'msg' => "",
                'count' => $count,
                'data' => $logList
            ));
        }
        $this->page->display();
    }

    public function wechat_add(){
        if(submitcheck('formhash')){
            $appid = $this->page->config->aljwsq->appid;
            $appsecret = $this->page->config->aljwsq->appsecret;
            if($appid && $appsecret){
                require_once DISCUZ_ROOT . './source/plugin/aljwsq/mapp_wechatclient.lib.class.php';
                $wechat_client = new WeChatClient($appid, $appsecret);
                $access_token = $wechat_client -> getAccessToken();
                $insertid = DB::result_first('select scene_id from %t where type = %d order by id desc',array('aljhtx_qrcode_wechat',$_GET['type']));
                $insertid = intval($insertid)+1;
                $scene_id = array(
                    'scene' => array('scene_id' => $insertid)
                );

                if(empty($_GET['type'])){
                    $postdatas = array(
                        'expire_seconds' => '2592000',
                        'action_name' => 'QR_SCENE',
                        'action_info' => $scene_id
                    );
                }else{
                    $postdatas = array(
                        'action_name' => 'QR_LIMIT_SCENE',
                        'action_info' => $scene_id
                    );
                }
                $postdatas = json_encode($postdatas);
                $qrcode = https_request('https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token='.$access_token, $postdatas);

                $qrcode = json_decode($qrcode,true);
                if(empty($_GET['type'])){
                    $filepath = 'source/plugin/aljhtx/static/img/qrcode/wechat/tmp/'.$insertid;
                }else{
                    $filepath = 'source/plugin/aljhtx/static/img/qrcode/wechat/'.$insertid;
                }
                $returndata = downloadImage('https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket='.$qrcode['ticket'],$filepath);
                DB::insert('aljhtx_qrcode_wechat', array(
                    'scene_id' => $insertid,
                    'qrcode' => $returndata['filename'],
                    'ticket' => $returndata['ticket'],
                    'type' => $_GET['type'],
                    'mykeyword' => $_GET['mykeyword'],
                    'dateline' => TIMESTAMP,
                ));
            }else{
                $this->page->tips('".lang("plugin/aljhtx","qrcode_php_1")."');
            }
            $this->page->tips();
        }else{
            $this->page->display();
        }
    }
    public function wechat_download(){
        $qrcode = DB::fetch_first('select * from %t where id=%d', array('aljhtx_qrcode_wechat', $_GET['qid']));
        if($qrcode['type']){
            $filename = 'source/plugin/aljhtx/static/img/qrcode/wechat/'.$qrcode['qrcode'];
        }else{
            $filename = 'source/plugin/aljhtx/static/img/qrcode/wechat/tmp/'.$qrcode['qrcode'];
        }
        header('Content-Disposition:attachment;filename=' . basename($filename));
        header('Content-Length:' . filesize($filename));
        readfile($filename);
        exit();
    }
    public function goods_download(){
        $qrcode = DB::fetch_first('select * from %t where id=%d', array('aljhtx_qrcode_goods', $_GET['qid']));

        if($qrcode['type']){
            $filename = 'source/plugin/aljhtx/static/img/qrcode/goods/'.$qrcode['qrcode'];
        }else{
            $filename = 'source/plugin/aljhtx/static/img/qrcode/goods/tmp/'.$qrcode['qrcode'];
        }
        header('Content-Disposition:attachment;filename=' . basename($filename));
        header('Content-Length:' . filesize($filename));
        readfile($filename);
        exit();
    }
    public function goods(){
        if($this->page->get->render == 'yes'){
            $per = 20;
            $page = $this->page->get->page>0 ? $this->page->get->page :1;
            $start = ($page - 1) * $per;
            $logList = DB::fetch_all('select * from %t order by dateline desc limit %d, %d', array('aljhtx_qrcode_goods', $start, $per));
            $count = DB::result_first('select count(*) from %t order by dateline desc', array('aljhtx_qrcode_goods'));
            foreach($logList as $k => $v){
                $logList[$k]['dateline'] = dgmdate($v['dateline'], 'u');
                if($logList[$k]['type']){
                    $logList[$k]['qrcode'] = 'source/plugin/aljhtx/static/img/qrcode/goods/'.$logList[$k]['qrcode'];
                }else{
                    $logList[$k]['qrcode'] = 'source/plugin/aljhtx/static/img/qrcode/goods/tmp/'.$logList[$k]['qrcode'];
                }
            }
            T::responseJson(array(
                'code' => 0,
                'msg' => "",
                'count' => $count,
                'data' => $logList
            ));
        }
        $this->page->display();
    }

    public function goods_add(){
        if(submitcheck('formhash')){
            $appid = $this->page->config->aljwsq->appid;
            $appsecret = $this->page->config->aljwsq->appsecret;
            if($appid && $appsecret){
                require_once DISCUZ_ROOT . './source/plugin/aljwsq/mapp_wechatclient.lib.class.php';
                $wechat_client = new WeChatClient($appid, $appsecret);
                $access_token = $wechat_client -> getAccessToken();
                $gid = intval($_GET['mykeyword']);
                $scene_id = array(
                    'scene' => array('scene_str' => 'gid_'.$gid)
                );

                if(empty($_GET['type'])){
                    $postdatas = array(
                        'expire_seconds' => '2592000',
                        'action_name' => 'QR_STR_SCENE',
                        'action_info' => $scene_id
                    );
                }else{
                    $postdatas = array(
                        'action_name' => 'QR_LIMIT_STR_SCENE',
                        'action_info' => $scene_id
                    );
                }
                $postdatas = json_encode($postdatas);
                $qrcode = https_request('https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token='.$access_token, $postdatas);
                $qrcode = json_decode($qrcode,true);
                if(empty($_GET['type'])){
                    $filepath = 'source/plugin/aljhtx/static/img/qrcode/goods/tmp/'.$gid;
                }else{
                    $filepath = 'source/plugin/aljhtx/static/img/qrcode/goods/'.$gid;
                }
                $returndata = downloadImage('https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket='.$qrcode['ticket'],$filepath);
                DB::insert('aljhtx_qrcode_goods', array(
                    'gid' => $gid,
                    'qrcode' => $returndata['filename'],
                    'ticket' => $returndata['ticket'],
                    'type' => $_GET['type'],
                    'scene_str' => 'gid_'.$gid,
                    'dateline' => TIMESTAMP,
                ));
            }else{
                $this->page->tips('".lang("plugin/aljhtx","qrcode_php_2")."');
            }
            $this->page->tips();
        }else{
            $this->page->display();
        }
    }

    public function goods_delete(){
        DB::delete('aljhtx_qrcode_goods', array('id' => $_GET['qid']));
        T::responseJson();
    }

    public function common(){
        if($this->page->get->render == 'yes'){
            $per = 20;
            $page = $this->page->get->page>0 ? $this->page->get->page :1;
            $start = ($page - 1) * $per;
            $logList = DB::fetch_all('select * from %t order by dateline desc limit %d, %d', array('aljhtx_qrcode_common', $start, $per));
            $count = DB::result_first('select count(*) from %t order by dateline desc', array('aljhtx_qrcode_common'));
            foreach($logList as $k => $v){
                $logList[$k]['dateline'] = dgmdate($v['dateline'], 'u');
                $logList[$k]['qrcode'] = 'source/plugin/aljhtx/static/img/qrcode/common/'.$logList[$k]['qrcode'];
            }
            T::responseJson(array(
                'code' => 0,
                'msg' => "",
                'count' => $count,
                'data' => $logList
            ));
        }
        $this->page->display();
    }

    public function common_delete(){
        DB::delete('aljhtx_qrcode_common', array('id' => $_GET['qid']));
        T::responseJson();
    }

    public function common_link(){
        if($_GET['qid']){
            DB::query('update %t set num = num+1 where id=%d',array('aljhtx_qrcode_common',$_GET['qid']));
            $qrcode = DB::fetch_first('select * from %t where id=%d', array('aljhtx_qrcode_common', $_GET['qid']));
            header('Location: '.$qrcode['url']);
            exit;
        }
    }

    public function common_download(){
        $qrcode = DB::fetch_first('select * from %t where id=%d', array('aljhtx_qrcode_common', $_GET['qid']));
        $filename = 'source/plugin/aljhtx/static/img/qrcode/common/'.$qrcode['qrcode'];
        header('Content-Disposition:attachment;filename=' . basename($filename));
        header('Content-Length:' . filesize($filename));
        readfile($filename);
        exit();
    }


    public function common_add(){
        if(submitcheck('formhash')){
            require_once DISCUZ_ROOT.'source/plugin/aljhtx/class/qrcode.class.php';
            $file = dgmdate(TIMESTAMP, 'YmdHis').random(18).'.jpg';
            $insertid = DB::insert('aljhtx_qrcode_common', array(
                'url' => $_GET['url'],
                'qrcode' => $file,
                'dateline' => TIMESTAMP,
            ), true);
            QRcode::png($this->page->global->siteurl.'plugin.php?id=aljhtx&c=qrcode&a=common_link&ajax=yes&qid='.$insertid, 'source/plugin/aljhtx/static/img/qrcode/common/'.$file, QR_MODE_STRUCTURE, 8);
            $this->page->tips();
        }else{
            $this->page->display();
        }
    }



    public function miniprogram(){
        if($this->page->get->render == 'yes'){
            $per = 20;
            $page = $this->page->get->page>0 ? $this->page->get->page :1;
            $start = ($page - 1) * $per;
            $logList = DB::fetch_all('select * from %t order by dateline desc limit %d, %d', array('aljhtx_qrcode_miniprogram', $start, $per));
            $count = DB::result_first('select count(*) from %t order by dateline desc', array('aljhtx_qrcode_miniprogram'));
            foreach($logList as $k => $v){
                $logList[$k]['dateline'] = dgmdate($v['dateline'], 'u');
                $logList[$k]['qrcode'] = 'source/plugin/aljhtx/static/img/qrcode/miniprogram/'.$v['qrcode'];;
            }
            T::responseJson(array(
                'code' => 0,
                'msg' => "",
                'count' => $count,
                'data' => $logList
            ));
        }
        $this->page->display();
    }

    public function miniprogram_delete(){
        DB::delete('aljhtx_qrcode_miniprogram', array('id' => $_GET['qid']));
        T::responseJson();
    }

    public function go(){
        if($_GET['qid']){
            DB::query('update %t set num = num+1 where id=%d',array('aljhtx_qrcode_miniprogram',$_GET['qid']));
            $qrcode = DB::fetch_first('select * from %t where id=%d', array('aljhtx_qrcode_miniprogram', $_GET['qid']));
            header('Location: '.$qrcode['url']);
        }
    }

    public function miniprogram_download(){
        $qrcode = DB::fetch_first('select * from %t where id=%d', array('aljhtx_qrcode_miniprogram', $_GET['qid']));
        $filename = 'source/plugin/aljhtx/static/img/qrcode/miniprogram/'.$qrcode['qrcode'];
        header('Content-Disposition:attachment;filename=' . basename($filename));
        header('Content-Length:' . filesize($filename));
        readfile($filename);
        exit();
    }


    public function miniprogram_add(){
        if(submitcheck('formhash')){
            $filepath = 'source/plugin/aljhtx/static/img/qrcode/miniprogram/'.TIMESTAMP;

            $insertid = DB::insert('aljhtx_qrcode_miniprogram', array(
                'url' => $_GET['url'],
                'dateline' => TIMESTAMP,
            ), true);

            $fileurl = $this->page->global->siteurl.'source/plugin/aljwx/qrcode.php?url='.urlencode('plugin.php?id=aljhtx&c=qrcode&a=go&ajax=yes&qid='.$insertid);
            $returndata = downloadImage($fileurl,$filepath);
            DB::update('aljhtx_qrcode_miniprogram', array('qrcode' => $returndata['filename']), array('id' => $insertid));
            $this->page->tips();
        }else{
            $this->page->display();
        }
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

