<?php

/**
 *请求页面类
 *
 * @author yuxinqi<yuxinqi@vip.qq.com>
 * @version 1.0
 * @link http://docs.liangjianyun.com/
 */

if(!defined('IN_DISCUZ')) {
    exit('Access Denied');
}

class RequestPage{
    //当前页数
    public $curPage;
    //当前页数据展示的条数
    public $pageSize = 20;
    //对应URL中的参数c
    public $controller;
    //对应URL中的参数a
    public $action;
    //对应URL中的参数c的类文件名
    public $controllerFileName;
    //对应URL中的参数c的类名
    public $controllerClassName;
    //登录用户对象
    public $loginUser;
    //论坛全局变量转对象
    public $global;
    //论坛后台设置项
    public $setting;
    //所有插件的设置项
    public $config;
    //模板中要使用到的变量列表
    public $assignDataList;
    //要加载的模板插件标识符
    public $templatePluginID;
    //要加载的控制器插件标识符
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
     * 修改当前控制器要加载的模板目录插件标识符
     *
     * @param string $templatePluginID 插件标识符
     * @return object
     */
    public function setTemplatePath($templatePluginID){
        $this->templatePluginID = $templatePluginID;
    }

    /**
     * 修改当前控制器要加载的控制器目录插件标识符
     *
     * @param string $controllerPluginID 插件标识符
     * @return object
     */
    public function setControllerPluginID($controllerPluginID){
        $this->controllerPluginID = $controllerPluginID;
    }

    /**
     * 为当前请求页面设置列表显示的数据条数
     *
     * @param string $pageSize 数据条数
     * @return object
     */
    public function setDataNum($pageSize){
        $this->pageSize = $pageSize;
    }

    /**
     * 将$_G数组处理后转成page的global对象
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
            if(in_array($k,$is_openarray)){//开关判断
                if($v['value'] == 1){
                    $_G['cache']['plugin']['aljbd'][$k] = 1;
                }elseif($v['value'] == 2){
                    $_G['cache']['plugin']['aljbd'][$k] = 0;
                }
            }else{
                if($v['value']){
                    $_G['cache']['plugin']['aljbd'][$k] = $v['value'];//同名变量，有值覆盖设置中变量
                }
            }
        }
        return T::arrayToObject($_G);
    }

    /**
     * 将插件设置项处理后转成page的config对象
     *
     * @return object
     */
    public function getConfig(){
        $this->global->cache->plugin->{APP_ID}->gids = (array)unserialize($this->global->cache->plugin->{APP_ID}->gids);
        return $this->global->cache->plugin;
    }

    /**
     * 将$_GET安全处理后初始化到page对象中
     *
     * @param $text string 提示文本
     * @param $url string 跳转链接
     * @return mixed
     */
    public function showMessage($text, $url = ''){

        $this->assign('aljht_tips', $text);
        $this->assign('url', $url, true);
        $this->display(PLUGIN_ID . ':showmessage');
    }

    /**
     * 将$_GET安全处理后初始化到page对象中
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
    * 自动加载action
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
     * 获取当前框架要加载的URL链接
     *
     * @param int $absolute 键
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
     * 处理模板中要用到的变量
     *
     * @param string $key 键
     * @param string $value 值
     * @param boolean $html 是否解析html
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
     * 加载模板
     *
     * @param string $tpl 模板路径
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
     * 判断网络协议
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
     * 获取当前页面的URL链接
     *
     * @param int $absolute 键
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
     * 加载配置文件
     *
     * @param $fileName string 配置文件名
     * @return mixed
     */
    public static function loadConfig($fileName){
        return include(APP_PATH . 'config/' . $fileName . '.php');
    }

    /**
     * 分页
     *
     * @param $allnum int 数据总数
     * @param $url string 跳转链接
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
     * PC二维码提示页
     *
     * @param string $navtitle SEO标题
     * @param string $title 标题
     * @param string $url 生成二维码链接
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
     * weui结果提示页
     * @param array $info 提示结果数组
     * @param string $navtitle 提示语
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