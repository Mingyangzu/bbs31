<?php

/**
 *����ҳ����
 *
 * @author yuxinqi<yuxinqi@vip.qq.com>
 * @version 1.0
 * @link http://docs.liangjianyun.com/
 */

if(!defined('IN_DISCUZ')) {
    exit('Access Denied');
}

class RequestPage{
    //��ǰҳ��
    public $curPage;
    //��ǰҳ����չʾ������
    public $pageSize = 20;
    //��ӦURL�еĲ���c
    public $controller;
    //��ӦURL�еĲ���a
    public $action;
    //��ӦURL�еĲ���c�����ļ���
    public $controllerFileName;
    //��ӦURL�еĲ���c������
    public $controllerClassName;
    //��¼�û�����
    public $loginUser;
    //��̳ȫ�ֱ���ת����
    public $global;
    //��̳��̨������
    public $setting;
    //���в����������
    public $config;
    //ģ����Ҫʹ�õ��ı����б�
    public $assignDataList;
    //Ҫ���ص�ģ������ʶ��
    public $templatePluginID;
    //Ҫ���صĿ����������ʶ��
    public $controllerPluginID;

    public function __construct() {
        $this->templatePluginID = APP_ID;
        $this->controllerPluginID = APP_ID;
        
        $this->get = $this->get();
        
        $this->curPage = $this->get->curpage ? $this->get->curpage : 1;
        $this->controller = $this->get->c ? $this->get->c : DEFAULT_CONTROLLER;
        $this->controllerFileName = $this->controller;
        $this->controllerClassName = $this->controller . 'action';
        $this->action = $this->get->a ? $this->get->a : DEFAULT_ACTION;
        $this->global = $this->getGlobal();
        $this->setting = $this->global->setting;
        $this->config = $this->getConfig();
        if($this->global->uid){
            $this->loginUser = User::getUser($this->global->uid);
        }
    }

    /**
     * �޸ĵ�ǰ������Ҫ���ص�ģ��Ŀ¼�����ʶ��
     *
     * @param string $templatePluginID �����ʶ��
     * @return object
     */
    public function setTemplatePath($templatePluginID){
        $this->templatePluginID = $templatePluginID;
    }

    /**
     * �޸ĵ�ǰ������Ҫ���صĿ�����Ŀ¼�����ʶ��
     *
     * @param string $controllerPluginID �����ʶ��
     * @return object
     */
    public function setControllerPluginID($controllerPluginID){
        $this->controllerPluginID = $controllerPluginID;
    }

    /**
     * Ϊ��ǰ����ҳ�������б���ʾ����������
     *
     * @param string $pageSize ��������
     * @return object
     */
    public function setDataNum($pageSize){
        $this->pageSize = $pageSize;
    }

    /**
     * ��$_G���鴦���ת��page��global����
     *
     * @return object
     */
    public function getGlobal(){
        global $_G;
        if($_G['cache']['plugin']['aljbd']) {
            $settings = C::t('#aljbd#aljbd_setting')->range();
        }

        $is_openarray = array('iswatermark','is_daohang','alipay','malipay','isextcredit','pic','isgo','isnews','isyouh','ispd','isrewrite','islogo','isqq','ista','sjurl','sj_index_lz','time');
        foreach($settings as $k => $v){
            if(in_array($k,$is_openarray)){//�����ж�
                if($v['value'] == 1){
                    $_G['cache']['plugin']['aljbd'][$k] = 1;
                }elseif($v['value'] == 2){
                    $_G['cache']['plugin']['aljbd'][$k] = 0;
                }
            }else{
                if($v['value']){
                    $_G['cache']['plugin']['aljbd'][$k] = $v['value'];//ͬ����������ֵ���������б���
                }
            }
        }
        return T::arrayToObject($_G);
    }

    /**
     * �������������ת��page��config����
     *
     * @return object
     */
    public function getConfig(){
        $this->global->cache->plugin->{APP_ID}->gids = (array)unserialize($this->global->cache->plugin->{APP_ID}->gids);
        return $this->global->cache->plugin;
    }

    /**
     * ��$_GET��ȫ������ʼ����page������
     *
     * @param $text string ��ʾ�ı�
     * @param $url string ��ת����
     * @return mixed
     */
    public function showMessage($text, $url = ''){

        $this->assign('aljht_tips', $text);
        $this->assign('url', $url, true);
        $this->display(PLUGIN_ID . ':showmessage');
    }

    /**
     * ��$_GET��ȫ������ʼ����page������
     *
     * @return mixed
     */
    public function get(){
        foreach ($_GET as $k => $v){
            if(is_int($v)){
                $param[$k] = intval($v);
            }else{
                $param[$k] = daddslashes($v);
            }
        }
        return T::arrayToObject($param);
    }

    /**
    * �Զ�����action
    *
    * @return array
    */
    public function route(){

        if(ROOT){
            require_once APP_ROOT_PATH.$this->controllerPluginID.'/controller/'.ROOT.'/'.$this->controllerFileName.'.php';
        }else{
            require_once APP_ROOT_PATH.$this->controllerPluginID.'/controller/'.$this->controllerFileName.'.php';
        }

        if (class_exists($this->controllerClassName)) {
            if (method_exists($this->controllerClassName, $this->action)) {

                $actionName = $this->action;
                //(new $this->controllerClassName($this))->$actionName();
                $route= new $this->controllerClassName($this);
                $route->$actionName();
            }else{
                header('HTTP/1.1 404 Not Found');
                header("status: 404 Not Found");
            }
        }else{
            header('HTTP/1.1 404 Not Found');
            header("status: 404 Not Found");
        }
    }


    public function tips($content = '', $url = ''){
        $content = $content ? $content : lang("plugin/aljhtx","class_page_php_1");
        if($url){
            echo '<script>parent.R.tips("'.$content.'","'.$url.'");</script>';
        }else{
            echo '<script>parent.R.tips("'.$content.'");</script>';
        }
        exit;
    }

    public function iframe(){
        if($this->get->ajax != 'yes'){
            if ($this->get->frames != 'yes') {
                echo '<script>
                    if(top.location==self.location) {
                        location.href=location.href+\'&frames=yes\';
                    }
                    </script>';
            } else {
                unset($this->get->frames);
                $this->assign('framesurl', IFRAME_PAGE_URL);
                $this->assign('sidearray', Page::loadConfig('first_nav'), true);
                $this->display(PLUGIN_ID . ':unified');
                exit;
            }
        }

    }

    /**
     * ��ȡ��ǰ���Ҫ���ص�URL����
     *
     * @param int $absolute ��
     * @return string
     */
    public static function getIframeUrl($absolute = 0) {
        parse_str($_SERVER['QUERY_STRING'], $queryStringArray);
        $queryStringArray['ajax'] = 'yes';
        $queryString = http_build_query($queryStringArray);
        if ($absolute) {
            return self::getUrlHeader() . $_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'?'.$queryString;
        } else {
            return $_SERVER['PHP_SELF'].'?'.$queryString;
        }
    }

    /**
     * ����ģ����Ҫ�õ��ı���
     *
     * @param string $key ��
     * @param string $value ֵ
     * @param boolean $html �Ƿ����html
     * @return array
     */
    public function assign($key, $value = '', $html = false){
        if (!$html) {
            $value = dhtmlspecialchars($value);
        }
        if (is_array($key) || is_object($key)) {
            foreach ($key as $k => $v) {
                $this->assign($k, $v);
            }
        } else {
            $this->assignDataList[$key] = $value;
        }
    }


    /**
     * ����ģ��
     *
     * @param string $tpl ģ��·��
     * @return string
     */
    public function display($tpl = ''){
        global $_G;
        foreach($this->assignDataList as $key => $value){
            $$key = $value;
        }

        if ($tpl) {
            include template($tpl);
            exit;
        }else{
            include template($this->templatePluginID . ':' . ROOT .'/' . $this ->controller. '/' . $this->action);
            exit;
        }

    }

    /**
     * �ж�����Э��
     *
     * @return string
     */
    public static function getUrlHeader() {
        if(isset($_SERVER['HTTPS']) && (strcasecmp($_SERVER['HTTPS'], 'on') === 0 || $_SERVER['HTTPS'] == 1)
            || isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && strcasecmp($_SERVER['HTTP_X_FORWARDED_PROTO'], 'https') === 0){
            return 'https';
        }else{
            return 'http';
        }
    }

    /**
     * ��ȡ��ǰҳ���URL����
     *
     * @param int $absolute ��
     * @return string
     */
    public static function getUrl($absolute = 0) {
        if ($absolute) {
            return self::getUrlHeader() . $_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'];
        } else {
            return $_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'];
        }
    }

    /**
     * ���������ļ�
     *
     * @param $fileName string �����ļ���
     * @return mixed
     */
    public static function loadConfig($fileName){
        return include(APP_PATH . 'config/' . $fileName . '.php');
    }

    /**
     * ��ҳ
     *
     * @param $allnum int ��������
     * @param $url string ��ת����
     * @return mixed
     */
    public function paging($allnum, $url){
        $curPage = $this->curPage;
        $pageSize =  $this->pageSize;
        $total=ceil($allnum/$pageSize);
        $prevpage=($curPage>1)?$curPage-1:1;
        $nextpage=($curPage>=$total)?$total:$curPage+1;
        if($curPage!=1){
            $prev = '<a href="'.$url.'1" class="btn btn-default btn-sm"><i class="fa fa-chevron-left"></i><i class="fa fa-chevron-left"></i></a >';
            $prev .='<a href="'.$url.$prevpage.'" class="btn btn-default btn-sm"><i class="fa fa-chevron-left"></i></a >';
        }
        if($total > 1){
            $page_str = '<a  class="btn btn-default btn-sm">'.$curPage.'/'.$total.'</a >';
            $page_input = '<input type="text" style="width:40px;border: 1px solid #ccc;" class="input-sm" onkeydown="if(event.keyCode==13) {window.location=\''.$url.'\'+this.value;}"/>';
            if($curPage!=$total){
                $next='<a href="'.$url.$nextpage.'" class="btn btn-default btn-sm"><i class="fa fa-chevron-right"></i></a >';
                $next.='<a href="'.$url.$total.'" class="btn btn-default btn-sm"><i class="fa fa-chevron-right"></i><i class="fa fa-chevron-right"></i></a >';
            }

            $tmp_str = '<div class="pull-right">
               '.$allnum.'
      <div class="btn-group">
                 '.$prev.$page_str.$next.$page_input.'
                 </div>
                </div>';
        }
        return $tmp_str;
    }
    /**
     * PC��ά����ʾҳ
     *
     * @param string $navtitle SEO����
     * @param string $title ����
     * @param string $url ���ɶ�ά������
     *
     * @return void
     */
    public function pcQrcode ($navtitle,$title='',$url=''){
        $this->assign('navtitle', $navtitle);
        $this->assign('title', $title);
        $this->assign('url', $url,true);
        $this->display('aljhtx:qrcode');
    }
    /**
     * weui�����ʾҳ
     * @param array $info ��ʾ�������
     * @param string $navtitle ��ʾ��
     *
     * @return void
     */
    public function weuiResult ($info=array(),$navtitle=''){
        if(!$navtitle){
            $navtitle = lang("plugin/aljhtx","class_page_php_2");
        }
        if(!$info['title']){
            $info['title'] = lang("plugin/aljhtx","class_page_php_3");
        }
        if(!$info['icon']){
            $info['icon'] = 'weui-icon-warn';
        }
        if(!$info['btn_primary']){
            $info['btn_primary'] = array('value' => lang("plugin/aljhtx","class_page_php_4"), 'url' => 'plugin.php?id=aljbd&act=attend');
        }
        if(!$info['btn_default']){
            $info['btn_default'] = array('value' => lang("plugin/aljhtx","class_page_php_5"), 'url' => 'plugin.php?id=aljbd&act=user');
        }
        $this->assign('result_info', $info);
        $this->assign('navtitle', $navtitle);

        $this->display('aljhtx:result');
    }
}

class P extends RequestPage {}
class Page extends RequestPage {}