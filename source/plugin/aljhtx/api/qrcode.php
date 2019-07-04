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
    //���������ص�ͷ��Ϣ
    $responseHeaders = array();
    //ԭʼͼƬ��
    $originalfilename = '';
    //ͼƬ�ĺ�׺��
    $ext = '';
    $ch = curl_init($url);
    //����curl_exec���ص�ֵ����Httpͷ
    curl_setopt($ch, CURLOPT_HEADER, 1);
    //����curl_exec���ص�ֵ����Http����
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    //����ץȡ��ת��http 301��302�����ҳ��
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    //��������HTTP�ض��������
    curl_setopt($ch, CURLOPT_MAXREDIRS, 2);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//��ֱֹ����ʾ��ȡ������ ��Ҫ

    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); //����֤֤����ͬ

    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false); //


    //���������ص����ݣ�����httpͷ��Ϣ�����ݣ�
    $html = curl_exec($ch);
    //��ȡ�˴�ץȡ�������Ϣ
    $httpinfo = curl_getinfo($ch);
    curl_close($ch);
    if ($html !== false) {
        //����response��header��body�����ڷ���������ʹ����302��ת�����Դ˴���Ҫ���ַ�������Ϊ 2+��ת���� ���Ӵ�
        $httpArr = explode("\r\n\r\n", $html, 2 + $httpinfo['redirect_count']);
        //�����ڶ����Ƿ��������һ��response��httpͷ
        $header = $httpArr[count($httpArr) - 2];
        //������һ���Ƿ��������һ��response������
        $body = $httpArr[count($httpArr) - 1];
        $header.="\r\n";

        //��ȡ���һ��response��header��Ϣ
        preg_match_all('/([a-z0-9-_]+):\s*([^\r\n]+)\r\n/i', $header, $matches);
        if (!empty($matches) && count($matches) == 3 && !empty($matches[1]) && !empty($matches[1])) {
            for ($i = 0; $i < count($matches[1]); $i++) {
                if (array_key_exists($i, $matches[2])) {
                    $responseHeaders[$matches[1][$i]] = $matches[2][$i];
                }
            }
        }
        //��ȡͼƬ��׺��
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
        //�����ļ�
        if (!empty($ext)) {
            $filepath .= ".$ext";
            //���Ŀ¼�����ڣ�����Ҫ����Ŀ¼s
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
