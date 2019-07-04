<?php

/**
 * ������
 *
 * @author yuxinqi<yuxinqi@vip.qq.com>
 * @version 1.0
 * @link http://docs.liangjianyun.com/
 */


if(!defined('IN_DISCUZ')) {
    exit('Access Denied');
}

class aljhtx{
    public static function ossZip($pic, $dir = 'test',$pluginid=''){
        global $_G;
        require_once DISCUZ_ROOT . './source/plugin/aljhtx/class/class_oss_zip.php';
        $oss = new Oss();
        $rand = rand(100, 999);
        $object = $dir.'/'.date('Ymd',TIMESTAMP).'/'.$pluginid.TIMESTAMP.$rand.'.zip';
        $result = $oss->upload($object, $pic);

        if(is_array($result) && $result['info']['url']){
            @unlink($pic);
            $oss_domain = $_G['cache']['plugin']['aljoss']['cdn_domain'] ? $_G['cache']['plugin']['aljoss']['cdn_domain'] : $_G['cache']['plugin']['aljoss']['domain'];
            return 'https://'.$oss_domain.'/'.$object;
        }else{
            return false;
        }
    }
    public static function ossXml($pic, $dir = 'test'){
        global $_G;
        
        require_once DISCUZ_ROOT . './source/plugin/aljhtx/class/class_oss_xml.php';
        
        $oss = new Oss_xml();
        $rand = rand(100, 999);
        $object = $dir;
        
        $result = $oss->upload($object, $pic);
        
        if(is_array($result) && $result['info']['url']){
            @unlink($pic);
            $oss_domain = $_G['cache']['plugin']['aljoss']['cdn_domain'] ? $_G['cache']['plugin']['aljoss']['cdn_domain'] : $_G['cache']['plugin']['aljoss']['domain'];
            return 'https://'.$oss_domain.'/'.$object;
        }else{
            return false;
        }
    }
    public static function oss_getUrl ($file) {
        global $_G;
        require_once DISCUZ_ROOT . './source/plugin/aljhtx/class/class_oss_xml.php';
        $oss = new Oss_xml();
        return $oss->getUrl($file);		
    }
    public static function delete_oss_xml ($file) {
        global $_G;
        require_once DISCUZ_ROOT . './source/plugin/aljhtx/class/class_oss_xml.php';
        $oss = new Oss_xml();
        $oss->delete($file);
        return true;		
    }
    public static function oss($pic, $dir = 'test'){
        global $_G;
        T::getObject('oss');
        $oss = new Oss();
        $rand = rand(100, 999);
        $object = $dir.'/'.date('Ymd',TIMESTAMP).'/'.TIMESTAMP.$rand.'.png';
        $result = $oss->upload($object, $pic);
        if(is_array($result) && $result['info']['url']){
            @unlink($pic);
            $oss_domain = $_G['cache']['plugin']['aljoss']['cdn_domain'] ? $_G['cache']['plugin']['aljoss']['cdn_domain'] : $_G['cache']['plugin']['aljoss']['domain'];
            return 'https://'.$oss_domain.'/'.$object;
        }else{
            return false;
        }
    }
    public static function delete_oss ($file) {
        global $_G;
        $oss_domain = $_G['cache']['plugin']['aljoss']['cdn_domain'] ? $_G['cache']['plugin']['aljoss']['cdn_domain'] : $_G['cache']['plugin']['aljoss']['domain'];
        if($oss_domain && strpos($file,$oss_domain) !== false){
            
            T::getObject('oss');
            
            $oss = new Oss();
            $oss->delete($file);
            return true;		
        }
        return false;
    }
    public static function upload($formname, $base = 'source/plugin/aljhtx/static/img/setting'){
        if($_FILES[$formname]['tmp_name']) {
            $picname = $_FILES[$formname]['name'];
            $picsize = $_FILES[$formname]['size'];
            if ($picname != "") {
                $type = strtolower(strrchr($picname, '.'));
                if ($type != ".gif" && $type != ".jpg"&& $type != ".png"&& $type != ".jpeg") {

                }
                $rand = rand(100, 999);
                $pics = date("YmdHis") . $rand . $type;
                $dir=$base.'/'.date('Ymd',TIMESTAMP).'/';
                if(!is_dir($dir)) {
                    @mkdir($dir, 0777);
                }
                $src = $dir. $pics;
                if(@copy($_FILES[$formname]['tmp_name'], $src)||@move_uploaded_file($_FILES[$formname]['tmp_name'], $src)){
                    @unlink($_FILES[$formname]['tmp_name']);
                }
            }
        }
        return array('file' => $src, 'name' => $picname, 'size' => $picsize);
    }
    /**
     * ��Ѷ��֤��
     *
     * @return array()
     */
    public static function verify(){
        global $_G;
        $param = array(
            'aid' => $_G['cache']['plugin']['aljhtx']['verify_appid'],
            'AppSecretKey' => $_G['cache']['plugin']['aljhtx']['verify_appsecret'],
            'Ticket' => $_GET['ticket'],
            'Randstr' => $_GET['randstr'],
            'UserIP' => $_G['clientip'],
        );
        $http_query = http_build_query($param , '' , '&');
        $verify_url = 'https://ssl.captcha.qq.com/ticket/verify?'.$http_query;
        $verify_result_json = dfsockopen($verify_url);

        return json_decode($verify_result_json, true);
    }
    /**
     * ���Ŀ¼�Ƿ��д
     *
     * @param string $dir Ҫ����Ŀ¼·��
     * @return integer
     */
    public static function dir_writeable($dir) {
        $writeable = 0;
        if(!is_dir($dir)) {
            @mkdir($dir, 0777);
        }
        if(is_dir($dir)) {
            if($fp = @fopen("$dir/test.txt", 'w')) {
                @fclose($fp);
                @unlink("$dir/test.txt");
                $writeable = 1;
            } else {
                $writeable = 0;
            }
        }
        return $writeable;
    }

    /**
     * gbkתutf-8
     *
     * @param mixed $arr Ҫת������ַ���
     * @return mixed
     */
    public static function ajaxPostCharSet($arr) {
        if(is_array($arr)){
            if (strtolower(CHARSET) == 'gbk') {
                foreach ($arr as $key => $val) {
                    if(is_array($val)){
                        $pt_goods[$key] = self::ajaxPostCharSet($val);
                    }else{
                        $pt_goods[$key] = diconv($val,'gbk','utf-8');
                    }

                }
                return $pt_goods;
            }
            return $arr;
        } else {
            if (strtolower(CHARSET) == 'gbk') {
                return diconv($arr,'gbk','utf-8');
            }
            return $arr;
        }
    }

    /**
     * תΪ��ǰ����������
     *
     * @param mixed $arr Ҫת������ַ���
     * @return mixed
     */
    public static function ajaxGetCharSet($arr) {
        if(is_array($arr)){
            if (strtolower(CHARSET) == 'gbk') {
                foreach ($arr as $key => $val) {
                    if(is_array($val)){
                        $pt_goods[$key] = T::ajaxGetCharSet($val);
                    }else{
                        $pt_goods[$key] = diconv($val,'utf-8','gbk');
                    }

                }
                return $pt_goods;
            }
            return $arr;
        } else {
            if (strtolower(CHARSET) == 'gbk') {
                return diconv($arr,'utf-8','gbk');
            }
            return $arr;
        }

    }

    /**
     * ���json
     *
     * @param array $arr Ҫ���������
     * @return void
     */
    public static function responseJson($arr = array()) {
        if($arr){
            echo  json_encode(T::ajaxPostCharSet($arr));
        }else{
            echo  json_encode(array('code' => 200, 'msg' => 'ok'));
        }
        exit;
    }

    /**
     * gbkתutf-8
     *
     * @param int $page ��ǰҳ
     * @param int $allnum ����������
     * @param int $pagesize ÿҳ����������
     * @param int $url ҳ������
     * @return string
     */
    public static function multi($page,$allnum,$pagesize=10,$url){
        $total=ceil($allnum/$pagesize);
        $prevpage=($page>1)?$page-1:1;
        $nextpage=($page>=$total)?$total:$page+1;
        if($page==1){
            $prev='<a href="#" class="btn btn-default btn-sm"><i class="fa fa-chevron-left"></i></a>';
        }else{
            $prev='<a href="'.$url.$prevpage.'" class="btn btn-default btn-sm"><i class="fa fa-chevron-left"></i></a>';
        }
        if($page==$total){
            $next='<a href="#" class="btn btn-default btn-sm"><i class="fa fa-chevron-right"></i></a>';
        }else{
            $next='<a href="'.$url.$nextpage.'" class="btn btn-default btn-sm"><i class="fa fa-chevron-right"></i></a>';
        }
        $tmp_str = '<div class="pull-right">
               '.$page.'/'.$total.'  
			   <div class="btn-group">
                 '.$prev.$next.'
                 </div>
                </div>';
        return $tmp_str;
    }

    /**
     * ��õ�ǰ����
     *
     * @param mixed $gets Ҫת������ַ���
     * @return mixed
     */
    public static function urlImplode($gets) {
        $arr = array();
        foreach ($gets as $key => $value) {
            if($value) {
                $arr[] = $key.'='.urlencode($value);
            }
        }
        return implode('&', $arr);
    }

    /**
     * ��õ�ǰ����
     *
     * @param mixed $gets Ҫת������ַ���
     * @return mixed
     */
    public static function urlEncodeImplode($gets) {
        $arr = array();
        foreach ($gets as $key => $value) {
            if($value) {
                $arr[] = $key.'='.urlencode($value);
            }
        }
        return implode('%26', $arr);
    }

    /**
     * app�����ж�
     * Appbyme:С�ƣ�ԭ���ף�
     * MAGAPPX:���
     * qianfan:ǧ��
     * MicroMessenger:΢��
     *
     * @return array
     */
    public static function appEnvironment() {
        $isapparray = array();
        strpos($_SERVER["HTTP_USER_AGENT"],'Appbyme')>0 ? $isapparray['Appbyme'] = true : $isapparray['Appbyme'] = false;
        strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false ? $isapparray['wechat'] = true : $isapparray['wechat'] = false;
        strpos($_SERVER["HTTP_USER_AGENT"],'MAGAPPX')>0 ? $isapparray['MAGAPPX'] = true : $isapparray['MAGAPPX'] = false;
        strpos(strtolower($_SERVER['HTTP_USER_AGENT']), 'qianfan') !== false ? $isapparray['qianfan'] = true : $isapparray['qianfan'] = false;
        stripos($_SERVER['HTTP_USER_AGENT'], 'iPhone')!==false||stripos($_SERVER['HTTP_USER_AGENT'], 'iPad')!==false ? $isapparray['ios'] = true : $isapparray['ios'] = false;
        return $isapparray;
    }

    /**
     * ����򵥸��ַ�unicodeת����
     *
     * @param mixed $str Ҫת������ַ���
     * @return mixed
     */
    public static function unescape($str) {
        if(is_array($str)) {
            foreach($str as $tmp_key=> $tmp_value) {
                $str[$tmp_key] = self::unicode_decode($tmp_value);
            }
            return $str;
        }else {
            return self::unicode_decode($str);
        }
    }

    /**
     * �����ַ�unicodeת����
     *
     * @param string $str Ҫת������ַ���
     * @return string
     */
    public static function unicode_decode($str) {
        $str = rawurldecode($str);
        preg_match_all("/(?:%u.{4})|&#x.{4};|&#\d+;|.+/U",$str,$r);
        $ar = $r[0];
        foreach($ar as $k=>$v) {
            if(substr($v,0,2) == "%u"){
                if(strtolower(CHARSET) == 'gbk'){
                    $ar[$k] = iconv("UCS-2BE","gbk",pack("H4",substr($v,-4)));
                }else {
                    $ar[$k] = iconv("UCS-2BE","UTF-8",pack("H4",substr($v,-4)));
                }

            }
            elseif(substr($v,0,3) == "&#x"){
                if(strtolower(CHARSET) == 'gbk'){
                    $ar[$k] = iconv("UCS-2BE","gbk",pack("H4",substr($v,3,-1)));
                }else{
                    $ar[$k] = iconv("UCS-2BE","UTF-8",pack("H4",substr($v,3,-1)));
                }
            }
            elseif(substr($v,0,2) == "&#") {
                if(strtolower(CHARSET) == 'gbk'){
                    $ar[$k] = iconv("UCS-2BE","gbk",pack("n",substr($v,2,-1)));
                }else{
                    $ar[$k] = iconv("UCS-2BE","UTF-8",pack("n",substr($v,2,-1)));
                }
            }
        }
        return join("",$ar);
    }

    /**
     * ������־
     *
     * @param string $tablename ����
     * @param array $arr ����
     */
    public static function insertlog($tablename,$arr=array()) {
        DB::insert($tablename,$arr);
    }

    /**
     * �绰���������ж�
     *
     * @param int $mobile �绰����
     * @return bool
     */
    public static function is_mobile($mobile) {
        return preg_match('#^\d[\d-]{3,20}\d$#', $mobile) ? true : false;
    }
    /**
     * �ж��ֻ���
     *
     * @param int $mobile �ֻ���
     *
     * @return bool
     */
    public static function isMobile ($mobile){
        global $_G;
        return preg_match($_G['cache']['plugin']['aljhtx']['regular'], $mobile) ? true : false;
    }
    /**
     * ����������
     *
     * @param string $biaoshi key
     * @return string
     */
    public static function create_token($biaoshi) {
        //��ǰʱ���
        $timestamp = TIMESTAMP;
        dsetcookie($biaoshi,$timestamp);
        return md5($timestamp);
    }

    /**
     * ����������
     *
     * @param $alj_token Ҫ������ַ���
     * @param string $getcook �ַ�������
     * @param string $biaoshi �ַ�������
     * @return bool
     */
    public static function valid_token($alj_token,$getcook='',$biaoshi='') {
        if(isset($getcook) && isset($alj_token) && $alj_token == md5($getcook))
        {
            //����ȷ�������������ٵ�
            dsetcookie($biaoshi,'');
            return true;
        }
        return false;
    }

    /**
     * �ַ�������ͷβ�м��*��
     *
     * @param string $user_name Ҫ������ַ���
     * @param string $bianma �ַ�������
     * @return string
     */
    public static function substr_cut($user_name,$bianma){
        //��ȡ�ַ�������
        $strlen = mb_strlen($user_name, $bianma);
        //����ַ�������С��2�������κδ���
        if($strlen<2){
            return $user_name;
        }else{
            //mb_substr �� ��ȡ�ַ����Ĳ���
            $firstStr = mb_substr($user_name, 0, 1, $bianma);
            $lastStr = mb_substr($user_name, -1, 1, $bianma);
            //str_repeat �� �ظ�һ���ַ���
            return $strlen == 2 ? $firstStr . str_repeat('*', mb_strlen($user_name, $bianma) - 1) : $firstStr . str_repeat("*", $strlen - 2) . $lastStr;
        }
    }
    /**
     * �Ķ���ת��1k 1w
     * @param int $num Ҫ���������
     * @return string
     */
    public static function num2tring($num) {
        if ($num >= 10000) {
            $num = round($num / 10000 * 100) / 100 .' W';
        } elseif($num >= 1000) {
            $num = round($num / 1000 * 100) / 100 . ' K';
        } else {
            $num = $num;
        }
        return $num;
    }
    /**
     * ȥ���ַ���bbcode����
     * @param string $text_to_search Ҫ������ַ���
     * @return string
     */
    public static function stripBBCode($text_to_search) {
        $pattern = '|[[\/\!]*?[^\[\]]*?]|si';
        $replace = '';
        return preg_replace($pattern, $replace, $text_to_search);
    }

    /**
     * h5ͼƬѹ���ϴ����ɱ���ͼƬ
     * @param string $pic �����ϴ���BASE64ͼƬ����
     * @param string $path ѹ�����ͼƬ�ϴ�Ŀ¼
     * @return string
     */
    public static function h5Caesium($pic,$path) {
        $rand = rand(100, 999);
        $pics = date("YmdHis").$rand.'.png';
        $dir= $path.'/'.date('Ymd',TIMESTAMP).'/';
        if(!is_dir($dir)) {
            @mkdir($dir, 0777);
        }
        $src = $dir. $pics;
        if(file_put_contents($src,file_get_contents($pic))) {
            return $src;
        }else {
            return '';
        }

    }
    public static function saveimg($pic,$path) {
        global $_G;
        if($_G['cache']['plugin']['aljbd']['iswatermark']) {
            require_once DISCUZ_ROOT . 'source/plugin/aljbd/class/class_image.php';
            $image = new aljbd_image;
        }
        $rand = rand(100, 999);
        $pics = date("YmdHis") . $rand . '.jpg';
        $dir = $path.'/'.date('Ymd',TIMESTAMP).'/';
        if(!is_dir($dir)) {
            @mkdir($dir, 0777);
        }
        $src = $dir. $pics;

        if(IN_MINI == 1){
            require_once DISCUZ_ROOT.'source/plugin/aljwx/class/wechatclient.lib.class.php';
            $wechat_client = new WeChatClient($_G['cache']['plugin']['aljwx']['g_appid'],$_G['cache']['plugin']['aljwx']['g_AppSecret']);
            $return = $wechat_client -> download($pic);
            if(file_put_contents(DISCUZ_ROOT.'./'.$src, $return) !== false){
                if($_G['cache']['plugin']['aljbd']['iswatermark']){
                    $image->Watermark(DISCUZ_ROOT.'./'.$src,'', 'forum','');
                }
                if (file_exists($src)) {
                    if($_G['cache']['plugin']['aljoss']['Access_Key']) {
                        $src = T::oss($src, 'aljbd');
                    }
                }
                return $src;
            }else {
                return '';
            }
        }else{
            if(file_put_contents($src,file_get_contents($pic))) {

                if($_G['cache']['plugin']['aljbd']['iswatermark']){
                    $image->Watermark(DISCUZ_ROOT.'./'.$src,'', 'forum','');
                }
                if (file_exists($src)) {
                    if($_G['cache']['plugin']['aljoss']['Access_Key']) {
                        $src = T::oss($src, 'aljbd');
                    }
                }
                return $src;
            }else {
                return '';
            }
        }
    }
    /**
     * �����ļ���־(1M�л�)
     * @param string $filename ����·���ļ���
     * @param string $data ��־����
     */
    public static function wlog($filename='',$data=''){
        if (!is_dir(dirname($filename))){
            mkdir(dirname($filename),0777, true);
        }
        //�����־�ļ���С������1M����������
        if(is_file($filename) && floor(1024000) <= filesize($filename)){
            rename($filename,dirname($filename).'/'.date("YmdHis").'-'.basename($filename));
        }
        error_log($data,3,$filename);
    }

    /**
     * ���� ת ����
     *
     * @param array $arr ����
     * @return object
     */
    public static function arrayToobject($arr) {
        if (!is_array($arr)) {
            return $arr;
        }
        foreach ($arr as $k => $v) {
            if (is_array($v) || is_object($v)) {
                $newarr[$k] = (object)self::arrayToobject($v);
            }else{
                $newarr[$k] = $v;
            }
        }
        return (object)$newarr;
    }

    /**
     * ���� ת ����
     *
     * @param object $obj ����
     * @return array
     */
    public static function objectToArray($obj) {
        $obj = (array)$obj;
        foreach ($obj as $k => $v) {
            if (is_resource($v)) {
                $newObj[$k] = $v;
                continue;
            }
            if (is_array($v) || is_object($v)) {
                $newObj[$k] = (array)self::objectToArray($v);
            }else{
                $newObj[$k] = $v;
            }
        }

        return $newObj;
    }

    /**
     * ��ȡ����Ĺ�������
     * @param string $className ����
     * @param array $classParam ��ʼ������
     * * @param boolean $load ֻ�����಻��ʼ��
     * @return mixed
     */
    public static function getObject($className='', $classParam = array(), $load = false){
        if(file_exists(DISCUZ_ROOT .'source/plugin/'.APP_ID.'/class/class_'.strtolower($className).'.php')){
            require_once DISCUZ_ROOT .'source/plugin/'.APP_ID.'/class/class_'.strtolower($className).'.php';
        }else{
            require_once DISCUZ_ROOT .'source/plugin/aljhtx/class/class_'.strtolower($className).'.php';
        }
        if(!$load){
            return new $className($classParam);
        }
    }

    public static function f($name){
        require_once 'source/function/function_'.$name.'.php';
    }

    public static function stringSettingToArray($str){
        if(!$str){
            return array();
        }
        $sj_index_dh = explode ("\n", str_replace ("\r", "", $str));

        foreach($sj_index_dh as $key=>$value){
            $arr=explode('|',$value);
            $sj_index_dh_types[]=$arr;
        }


        return $sj_index_dh_types;
    }

    public static function arrayToStringSetting($lz_types){
        foreach($lz_types as $key=>$value){
            $str=implode('|',$value);
            $lz_types[$key]=$str;
        }
        $lz_types = implode("\n", $lz_types);
        return $lz_types;
    }

    public static function getPhotoFilePath(){
        $img_dir = 'source/plugin/aljhtx/static/img/setting/'.date('Ymd',TIMESTAMP).'/';
        if (!is_dir($img_dir)) {
            mkdir($img_dir);
        }
        $pic = $img_dir . date("YmdHis") . rand(100, 999) . '.jpg';
        return $pic;
    }
    public static function getaljurl($geturl,$param){
        if($param){
            foreach($param as $k => $v){
                $geturl[$k] = $v;
            }
        }
        require_once libfile('function/home');
        return 'plugin.php?'.url_implode($geturl);
    }
    public static function upload_file($files, $path = "source/plugin/aljht/static/video",$imagesExt=array('mov','mp4'))

    {
        // �жϴ����
        if (@$files['error'] == 00) {
            // �ж��ļ�����
            $ext = strtolower(pathinfo(@$files['name'],PATHINFO_EXTENSION));
            if (!in_array($ext,$imagesExt)){
                return array('code'=>0,'txt'=>lang("plugin/aljhtx","class_aljhtx_php_1"));
            }
            // �ж��Ƿ�����ϴ�����Ŀ¼
            if (!is_dir($path)){
                mkdir($path,0777,true);
            }
            // ����Ψһ���ļ���
            $fileName = md5(uniqid(microtime(true),true)).'.'.$ext;
            // ���ļ���ƴ�ӵ�ָ����Ŀ¼��
            $destName = $path."/".$fileName;
            // �����ļ��ƶ�
            if (!move_uploaded_file($files['tmp_name'],$destName)){
                return array('code'=>0,'txt'=>lang("plugin/aljhtx","class_aljhtx_php_2"));
            }
            return array('code'=>1,'path'=>$destName);
        } else {
            // ���ݴ���ŷ�����ʾ��Ϣ
            switch (@$files['error']) {
                case 1:
                    return array('code'=>0,'txt'=>lang("plugin/aljhtx","class_aljhtx_php_3"));
                    break;
                case 2:
                    return array('code'=>0,'txt'=>lang("plugin/aljhtx","class_aljhtx_php_4"));
                    break;
                case 3:
                    return array('code'=>0,'txt'=>lang("plugin/aljhtx","class_aljhtx_php_5"));
                    break;
                case 4:
                    return array('code'=>0,'txt'=>lang("plugin/aljhtx","class_aljhtx_php_6"));
                    break;
                case 6:
                case 7:
                    return array('code'=>0,'txt'=>lang("plugin/aljhtx","class_aljhtx_php_7"));
                    break;
            }

        }

    }
    /**
     *
     * ��ȡ΢�Ű汾��
     * @param string $ua $_SERVER['HTTP_USER_AGENT'] ��������
     * @param string $param �����ؼ��� micromessenger/
     * @return string $version ΢�Ű汾��
     *
     */
    public static function getVersion($ua,$param)
    {
        //���Դ�Сд
        $ua = strtolower($ua);
        //����ַ������ֵ�λ��
        $pos = strpos($ua, $param);
        //�ָ��ַ���
        $a = substr($ua, $pos);
        //���ñ�Ƿָ��ַ���
        $b = strtok($a,' ');
        return $version = trim(str_replace($param, '', $b));
    }
    /**
     *
     * �ж� ios �� android
     *
     * @return string $type �ն�
     *
     */
    public static function get_device_type()
    {
        //ȫ�����Сд��ĸ
        $agent = strtolower($_SERVER['HTTP_USER_AGENT']);
        $type = 'other';
        //�ֱ�����ж�
        if(strpos($agent, 'iphone') || strpos($agent, 'ipad'))
        {
            $type = 'ios';
        }

        if(strpos($agent, 'android'))
        {
            $type = 'android';
        }
        return $type;
    }
    /**
     *
     * Ʒ���̼ҷ���֪ͨ
     *
     * @param array $param ��Ϣ����
     * @param int $uid ������ UID
     * @param int $touid  ������ UID
     * @param string $url ��ת����
     * @param int $sendol �Ƿ����͵�����������ҳ
     *
     * @return string
     *
     */
    public static function aljbd_notification($touid=0,$param,$url='',$uid=0,$sendol=0) {
        $cron = T::getObject('cron', array(
            'param' => serialize($param),
            'type' => 'bindtemplate',
            'uid' => $uid,
            'touid' => $touid,
            'content' => $url,
        ));
        $cron->push();
        if($sendol == 1){
            //���͵�����������ҳ��
        }
    }
}
class T extends aljhtx {}
