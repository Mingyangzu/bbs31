<?php

/**
 * Controller的log日志模块
 *
 * @author yuxinqi<yuxinqi@vip.qq.com>
 * @version 1.0
 * @link http://docs.liangjianyun.com/
 */


if(!defined('IN_DISCUZ')) {
    exit('Access Denied');
}

class LogAction{
    public $page;
    public $download_url;
    public $website_url;
    public $check_url;
    public function __construct($page) {
        global $requestPage;
        $this->page = $requestPage;
        $this->website_url = 'https://addon.liangjianyun.com/source/plugin/aljhy/AppCenter/';
        $this->download_url = 'https://addon.liangjianyun.com/plugin.php?id=aljhy&act=appcenter';
        $this->check_url = 'https://addon.liangjianyun.com/source/plugin/aljhy/AppCenter/md5/';
        $this->page->assign('bindTemplateList', Page::loadConfig('bind_template'));
    }
    public function dir_clear($dir) {
        if($directory = @dir($dir)) {
            while($entry = $directory->read()) {
                if($entry == '.' || $entry == '..') {
                    continue;
                }
                $filename = $dir.'/'.$entry;
                if(is_file($filename)) {
                    @unlink($filename);
                } else {
                    $this->dir_clear($filename);
                }
            }
            $directory->close();
            @rmdir($dir);
        }
    }
    public function cpmsg_error($message, $url = '', $extra = '', $halt = TRUE) {
        return cpmsg($message, $url, 'error', array(), $extra, $halt);
    }

    public function cpmsg($message, $url = '', $type = '', $values = array(), $extra = '', $halt = TRUE, $cancelurl = '') {
        global $_G;
        $common_template_pluginid = 'aljbd';
        $common_path = 'source/plugin/aljhtx/';
        if($_G['mobile']){
            include template($common_template_pluginid.':new/common/header');
        }
        $vars = explode(':', $message);
        $values['ADMINSCRIPT'] = $_G['siteurl'].GOURL;
        if(count($vars) == 2) {
            $message = lang('plugin/'.$vars[0], $vars[1], $values);
        } else {
            if($message == 'plugins_config_upgrade_missed'){
                $message = lang("plugin/aljhtx","log_php_2");
            }else if($message == 'plugins_config_upgrade_new'){
                $message = lang("plugin/aljhtx","log_php_3");
            }else{
                $message = cplang($message, $values);
            }
        }
        switch($type) {
            case 'download':
            case 'succeed': $classname = 'infotitle2';break;
            case 'error': $classname = 'infotitle3';break;
            case 'loadingform': case 'loading': $classname = 'infotitle1';break;
            default: $classname = 'marginbot normal';break;
        }
        if($url) {
            $url = (substr($url, 0, 5) == 'http:' || substr($url, 0, 6) == 'https:') ? $url : ADMINSCRIPT.'?'.$url;
        }
        $message = "<h4 class=\"$classname\">$message</h4>";
        $url .= $url && !empty($_GET['scrolltop']) ? '&scrolltop='.intval($_GET['scrolltop']) : '';

        if($type == 'form') {
            $message = "<form method=\"post\" action=\"$url\"><input type=\"hidden\" name=\"formhash\" value=\"".FORMHASH."\">".
                "<br />$message$extra<br />".
                "<p class=\"margintop\"><input type=\"submit\" class=\"btn\" name=\"confirmed\" value=\"".cplang('ok')."\"> &nbsp; \n".
                ($cancelurl ? "<input type=\"button\" class=\"btn\" value=\"".cplang('cancel')."\" onClick=\"location.href='$cancelurl'\">" :
                    "<script type=\"text/javascript\">".
                    "if(history.length > (BROWSER.ie ? 0 : 1)) document.write('<input type=\"button\" class=\"btn\" value=\"".cplang('cancel')."\" onClick=\"history.go(-1);\">');".
                    "</script>").
                "</p></form><br />";
        } elseif($type == 'loadingform') {
            $message = "<form method=\"post\" action=\"$url\" id=\"loadingform\"><input type=\"hidden\" name=\"formhash\" value=\"".FORMHASH."\"><br />$message$extra<img src=\"static/image/admincp/ajax_loader.gif\" class=\"marginbot\" /><br />".
                '<p class="marginbot"><a href="###" onclick="$(\'loadingform\').submit();" class="lightlink">'.cplang('message_redirect').'</a></p></form><br /><script type="text/JavaScript">setTimeout("$(\'loadingform\').submit();", 2000);</script>';
        } else {
            $message .= $extra.($type == 'loading' ? '<img src="source/plugin/aljhtx/static/img/ajax_loader.gif" class="marginbot" />' : '');
            if($url) {
                if($type == 'button') {
                    $message = "<br />$message<br /><p class=\"margintop\"><input type=\"submit\" class=\"btn\" name=\"submit\" value=\"".cplang('start')."\" onclick=\"location.href='$url'\" />";
                } else {
                    $message .= '<p class="marginbot"><a href="'.$url.'" class="lightlink">'.cplang($type == 'download' ? 'message_download' : 'message_redirect').'</a></p>';
                    $timeout = $type != 'loading' ? 3000 : 1000;
                    $message .= "<script type=\"text/JavaScript\">setTimeout(\"redirect('$url');\", $timeout);</script>";
                }
            } elseif($type != 'succeed') {
                $message .= '<p class="marginbot">'.
                    "<script type=\"text/javascript\">".
                    "if(history.length > (BROWSER.ie ? 0 : 1)) document.write('<a href=\"javascript:history.go(-1);\" class=\"lightlink\">".cplang('message_return')."</a>');".
                    "</script>".
                    '</p>';
            }
        }

        if($halt) {
            echo '<div class="infobox">'.$message.'</div>';
            if($_G['mobile']){
                include template($common_template_pluginid.':new/common/footer');
            }
            exit();
        } else {
            echo '<div class="infobox">'.$message.'</div>';
            if($_G['mobile']){
                include template($common_template_pluginid.':new/common/footer');

            }
        }
    }
    public function cloudaddons_open($extra, $post = '', $timeout = 15) {
        //debug(cloudaddons_url('&from=s').$extra);
        $param_url = cloudaddons_url('&from=s').$extra;
        $param_url = str_replace('?data','&data',$param_url);
        return dfsockopen($param_url, 0, $post, '', false, CLOUDADDONS_DOWNLOAD_IP, $timeout);
    }
    /**
     * 插件更新
     *
     * @return void
     */
    public function download(){
        global $_G;
        $_G['setting']['hookscript'] = array();
        if($_G['groupid'] != 1){
            $this->page->showMessage(lang("plugin/aljhtx","log_php_4"));
        }
        define('IN_ADMINCP', TRUE);
        
        
        define('NOROBOT', TRUE);
        
        define('CURSCRIPT', 'admin');
        define('HOOKTYPE', 'hookscript');
        define('APPTYPEID', 0);
        define('GOURL', 'plugin.php?id=aljhtx&c=log&a=plugin&ajax=yes&no=yes');
        define('ADMINSCRIPT', 'liangjianyun.php?c=log&a=download&ajax=yes&no=yes');
        $_G['config']['addonsource'] = 'yibaihui';
        $_G['config']['addon'] = array(
            'yibaihui' => array(
                'website_url' => $this->website_url,
                'download_url' => $this->download_url,
                'download_ip' => '',
                'check_url' => $this->check_url,
                'check_ip' => '',
            )
        );
        require_once DISCUZ_ROOT . './source/language/lang_admincp.php';
        require_once libfile('function/admincp');
        $operation = preg_replace('/[^\[A-Za-z0-9_\]]/', '', getgpc('operation'));
        if($operation == 'import' || $operation == 'upgrade' || $operation == 'plugininstall' || $operation == 'pluginupgrade' || $operation == 'enable' || $operation == 'disable' || $operation == 'config'){
            $pluginid = !empty($_GET['pluginid']) ? intval($_GET['pluginid']) : 0;
            $anchor = !empty($_GET['anchor']) ? $_GET['anchor'] : '';
            $isplugindeveloper = isset($_G['config']['plugindeveloper']) && $_G['config']['plugindeveloper'] > 0;
            if(!empty($_GET['dir']) && !ispluginkey($_GET['dir'])) {
                unset($_GET['dir']);
            }
            require_once libfile('function/plugin');
            
            if($_GET['pmod'] == 'app' || $_GET['pmod'] == 'more'){
                dheader('location: //addon.liangjianyun.com/');
                exit;
            }
        }else{
            if($_G['cache']['plugin']['aljhtx']['is_plugin_open']){
                require_once libfile('function/cloudaddons');
            }else{
                require_once DISCUZ_ROOT . './source/plugin/aljhtx/function/function_cloudaddons.php';
            }
        }
        require_once libfile('function/cache');
        $IMGDIR = $_G['style']['imgdir'];
	$STYLEID = $_G['setting']['styleid'];
	$VERHASH = $_G['style']['verhash'];
	$frame = getgpc('frame') != 'no' ? 1 : 0;
	$charset = CHARSET;
	$basescript = ADMINSCRIPT;
        echo <<<EOT
<style>
.container h3{
display:none;
}
.itemtitle h3 {
    display:block !important;
}
.infobox{ 
clear:both; margin-bottom:10px; padding:30px; text-align:center; zoom:1; 
width: 100%;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -70%);
}
    .infotitle1{ margin-bottom:10px; color:#41A5FF; font-size:14px; font-weight:700; }
    .infotitle2{ margin-bottom:10px; color:#090; font-size:14px; font-weight:700; }
    .infotitle3{ margin-bottom:10px; color:#C00; font-size:14px; font-weight:700; }
    .cachelist{ overflow:hidden;}
    .cachelist li{ float:left; margin-right:10px; }
    .colorbox{ clear: both; padding:10px; border-top:4px solid #DEEFFA; border-bottom:4px solid #DEEFFA; background:#F2F9FD; zoom:1; }
    .extcredits, .threadprofilenode { margin:-5px 0 10px; }
    .extcredits a, .threadprofilenode a { margin-right:5px; padding:2px 5px; line-height:220%; border:1px solid #B6CFD9; background:#FFF; white-space:nowrap; }
    .threadprofilenode { width: 650px; }
    .jswizard{ margin:10px 0; }
    .jswizard iframe { border: 1px dashed #DEEFFA; }
    .fileperms{ list-style:disc; margin:15px; }
    .fileperms li{ line-height:180%; }
    .tips{ margin-left:15px; color:#999; }
    .tips2{ line-height:180%; color:#999; word-break:break-all; }
    .tb2 .tipsblock{ background:none; margin-bottom:-10px; }
    .tipsblock ul{ margin-bottom:-11px; }
    .tipsblock li{ margin-bottom:5px; padding:0 0 5px 20px; line-height:160%; background:url(static/admincp/bg_repno.gif) no-repeat -340px 6px; }
    .tips a, .tips2 a, .tipsblock a{ margin:0 3px; text-decoration:underline; color:#666; }
    .tips a:hover, .tips2 a:hover, .tipsblock a:hover{ color:#09C; }
    .infobox a{
        text-decoration:none;
    }
    .infobox img{height:70px}
    .lightlink{color:#41A5FF;font-size: 14px;}
</style>
<script>
    var ISFRAME = 1
    var BROWSER = {};
    var USERAGENT = navigator.userAgent.toLowerCase();
    browserVersion({'ie':'msie','firefox':'','chrome':'','opera':'','safari':'','mozilla':'','webkit':'','maxthon':'','qq':'qqbrowser','rv':'rv'});
    if(BROWSER.safari || BROWSER.rv) {
        BROWSER.firefox = true;
    }
    BROWSER.opera = BROWSER.opera ? opera.version() : 0;

    HTMLNODE = document.getElementsByTagName('head')[0].parentNode;
    if(BROWSER.ie) {
        BROWSER.iemode = parseInt(typeof document.documentMode != 'undefined' ? document.documentMode : BROWSER.ie);
        HTMLNODE.className = 'ie_all ie' + BROWSER.iemode;
    }
    function browserVersion(types) {
        var other = 1;
        for(i in types) {
            var v = types[i] ? types[i] : i;
            if(USERAGENT.indexOf(v) != -1) {
                var re = new RegExp(v + '(\\/|\\s|:)([\\d\\.]+)', 'ig');
                var matches = re.exec(USERAGENT);
                var ver = matches != null ? matches[2] : 0;
                other = ver !== 0 && v != 'mozilla' ? 0 : other;
            }else {
                var ver = 0;
            }
            eval('BROWSER.' + i + '= ver');
        }
        BROWSER.other = other;
    }
</script>
<script type="text/JavaScript">
var admincpfilename = '$basescript', IMGDIR = '$IMGDIR', STYLEID = '$STYLEID', VERHASH = '$VERHASH', IN_ADMINCP = true, ISFRAME = $frame, STATICURL='static/', SITEURL = '$_G[siteurl]', JSPATH = '{$_G[setting][jspath]}';
</script>
<script src="static/js/common.js?{$_G[style][verhash]}" type="text/javascript"></script>
<script src="static/js/admincp.js?{$_G[style][verhash]}" type="text/javascript"></script>
<link rel="stylesheet" href="static/image/admincp/admincp.css?{$_G[style][verhash]}" type="text/css" media="all">
<div id="append_parent"></div><div id="ajaxwaitid"></div>
<div class="container" id="cpcontainer">
EOT;
        //lj_jq("input[name='formhash']").val('{$formhash}');
        //cpmsg('plugins_edit_identifier_invalid', '', 'error');

        if($operation == 'import') {

            if(submitcheck('importsubmit') || isset($_GET['dir'])) {
                cloudaddons_validator($_GET['dir'].'.plugin');

                if(!isset($_GET['installtype'])) {
                    $pdir = DISCUZ_ROOT.'./source/plugin/'.$_GET['dir'];
                    $d = dir($pdir);
                    $xmls = '';
                    $count = 0;
                    $noextra = false;
                    $currentlang = currentlang();
                    while($f = $d->read()) {
                        if(preg_match('/^discuz\_plugin_'.$_GET['dir'].'(\_\w+)?\.xml$/', $f, $a)) {
                            $extratxt = $extra = substr($a[1], 1);
                            if($extra) {
                                if($currentlang && $currentlang == $extra) {
                                    dheader('location: plugin.php?id=aljhtx&c=log&a=download&operation=import&dir='.$_GET['dir'].'&installtype='.rawurlencode($extra));
                                }
                            } else {
                                $noextra = true;
                            }
                            $url = 'plugin.php?id=aljhtx&c=log&a=download&operation=import&dir='.$_GET['dir'].'&installtype='.rawurlencode($extra);
                            $xmls .= '&nbsp;<input type="button" class="btn" onclick="location.href=\''.$url.'\'" value="'.($extra ? $extratxt : $lang['plugins_import_default']).'">&nbsp;';
                            $count++;
                        }
                    }
                    if($count == 1 && $noextra) {
                        dheader('location: plugin.php?id=aljhtx&c=log&a=download&operation=import&dir='.$_GET['dir'].'&installtype=');
                    }
                    $xmls .= '<br /><br /><input class="btn" onclick="location.href=\''.GOURL.'\'" type="button" value="'.$lang['cancel'].'"/>';
                    echo '<div class="infobox"><h4 class="infotitle2">'.$lang['plugins_import_installtype_1'].' '.$_GET['dir'].' '.$lang['plugins_import_installtype_2'].' '.$count.' '.$lang['plugins_import_installtype_3'].'</h4>'.$xmls.'</div>';
                    exit;
                } else {
                    $installtype = $_GET['installtype'];
                    $dir = $_GET['dir'];
                    $license = $_GET['license'];
                    $extra = $installtype ? '_'.$installtype : '';
                    $importfile = DISCUZ_ROOT.'./source/plugin/'.$dir.'/discuz_plugin_'.$dir.$extra.'.xml';
                    $_GET['importtxt'] = @implode('', file($importfile));
                    $pluginarray = getimportdata('Discuz! Plugin');
                    if(empty($license) && $pluginarray['license']) {
                        require_once libfile('function/discuzcode');
                        $pluginarray['license'] = discuzcode(strip_tags($pluginarray['license']), 1, 0);
                        echo '<div class="infobox"><h4 class="infotitle2">'.$pluginarray['plugin']['name'].' '.$pluginarray['plugin']['version'].' '.$lang['plugins_import_license'].'</h4><div style="text-align:left;line-height:25px;">'.$pluginarray['license'].'</div><br /><br /><center>'.
                            '<button onclick="location.href=\'plugin.php?id=aljhtx&c=log&a=download&operation=import&dir='.$dir.'&installtype='.$installtype.'&license=yes\'">'.$lang['plugins_import_agree'].'</button>&nbsp;&nbsp;'.
                            '<button onclick="location.href=\''.GOURL.'\'">'.$lang['plugins_import_pass'].'</button></center></div>';
                        exit;
                    }
                }

                if(!ispluginkey($pluginarray['plugin']['identifier'])) {
                    $this->cpmsg('plugins_edit_identifier_invalid', $_G['siteurl'].''.GOURL.'', 'error');
                }
                if(is_array($pluginarray['vars'])) {
                    foreach($pluginarray['vars'] as $config) {
                        if(!ispluginkey($config['variable'])) {
                            $this->cpmsg('plugins_import_var_invalid', $_G['siteurl'].''.GOURL.'', 'error');
                        }
                    }
                }

                $plugin = C::t('common_plugin')->fetch_by_identifier($pluginarray['plugin']['identifier']);
                if($plugin) {
                    $this->cpmsg('plugins_import_identifier_duplicated', $_G['siteurl'].''.GOURL.'', 'error', array('plugin_name' => $plugin['name']));
                }

                if(!empty($pluginarray['checkfile']) && preg_match('/^[\w\.]+$/', $pluginarray['checkfile'])) {
                    $filename = DISCUZ_ROOT.'./source/plugin/'.$_GET['dir'].'/'.$pluginarray['checkfile'];
                    if(file_exists($filename)) {
                        loadcache('pluginlanguage_install');
                        $installlang = $pluginarray['language']['installlang'];
                        @include $filename;
                    }
                }

                if(empty($_GET['ignoreversion']) && !versioncompatible($pluginarray['version'])) {
                    if(isset($dir)) {
                        $this->cpmsg('plugins_import_version_invalid_confirm', $_G['siteurl'].'plugin.php?id=aljhtx&c=log&a=download&operation=import&ignoreversion=yes&dir='.$dir.'&installtype='.$installtype.'&license='.$license, 'form', array('cur_version' => $pluginarray['version'], 'set_version' => $_G['setting']['version']), '', false, $_G['siteurl'].GOURL);
                    } else {
                        $this->cpmsg('plugins_import_version_invalid', $_G['siteurl'].''.GOURL.'', 'error', array('cur_version' => $pluginarray['version'], 'set_version' => $_G['setting']['version']));
                    }
                }

                $pluginid = plugininstall($pluginarray, $installtype);

                //updatemenu('plugin');

                if(!empty($dir) && !empty($pluginarray['installfile']) && preg_match('/^[\w\.]+$/', $pluginarray['installfile'])) {
                    dheader('location: plugin.php?id=aljhtx&c=log&a=download&operation=plugininstall&dir='.$dir.'&installtype='.$installtype.'&pluginid='.$pluginid);
                }

                cloudaddons_clear('plugin', $dir);

                if(!empty($dir)) {
                    $this->cpmsg('plugins_install_succeed', $_G['siteurl'].''.GOURL.'', 'succeed');
                } else {
                    $this->cpmsg('plugins_import_succeed', $_G['siteurl'].''.GOURL.'', 'succeed');
                }

            }

        }else if(FORMHASH == $_GET['formhash'] && ($operation == 'enable' || $operation == 'disable')) {

            $conflictplugins = '';
            $plugin = C::t('common_plugin')->fetch($_GET['pluginid']);
            if(!$plugin) {
                $this->cpmsg('plugin_not_found', '', 'error');
            }
            $dir = substr($plugin['directory'], 0, -1);
            $modules = dunserialize($plugin['modules']);
            $file = DISCUZ_ROOT.'./source/plugin/'.$dir.'/discuz_plugin_'.$dir.($modules['extra']['installtype'] ? '_'.$modules['extra']['installtype'] : '').'.xml';
            if(!file_exists($file)) {
                $pluginarray[$operation.'file'] = $modules['extra'][$operation.'file'];
                $pluginarray['plugin']['version'] = $plugin['version'];
            } else {
                $_GET['importtxt'] = @implode('', file($file));
                $pluginarray = getimportdata('Discuz! Plugin');
            }
            if(!empty($pluginarray[$operation.'file']) && preg_match('/^[\w\.]+$/', $pluginarray[$operation.'file'])) {
                $filename = DISCUZ_ROOT.'./source/plugin/'.$dir.'/'.$pluginarray[$operation.'file'];
                if(file_exists($filename)) {
                    @include $filename;
                }
            }

            if($operation == 'enable') {

                require_once libfile('cache/setting', 'function');
                list(,, $hookscript) = get_cachedata_setting_plugin($plugin['identifier']);
                $exists = array();
                foreach($hookscript as $script => $modules) {
                    foreach($modules as $module => $data) {
                        foreach(array('funcs' => '', 'outputfuncs' => '_output', 'messagefuncs' => '_message') as $functype => $funcname) {
                            foreach($data[$functype] as $k => $funcs) {
                                $pluginids = array();
                                foreach($funcs as $func) {
                                    $pluginids[$func[0]] = $func[0];
                                }
                                if(in_array($plugin['identifier'], $pluginids) && count($pluginids) > 1) {
                                    unset($pluginids[$plugin['identifier']]);
                                    foreach($pluginids as $pluginid) {
                                        $exists[$pluginid][$k.$funcname] = $k.$funcname;
                                    }
                                }
                            }
                        }
                    }
                }
                if($exists) {
                    $plugins = array();
                    foreach(C::t('common_plugin')->fetch_all_by_identifier(array_keys($exists)) as $plugin) {
                        $plugins[] = '<b>'.$plugin['name'].'</b>:'.
                            '&nbsp;<a href="javascript:;" onclick="display(\'conflict_'.$plugin['identifier'].'\')">'.cplang('plugins_conflict_view').'</a>'.
                            '&nbsp;<a href="'.cloudaddons_pluginlogo_url($plugin['identifier']).'" target="_blank">'.cplang('plugins_conflict_info').'</a>'.
                            '<span id="conflict_'.$plugin['identifier'].'" style="display:none"><br />'.implode(',', $exists[$plugin['identifier']]).'</span>';
                    }
                    $conflictplugins = '<div align="left" style="margin: auto 100px; border: 1px solid #DEEEFA;padding: 4px;line-height: 25px;">'.implode('<br />', $plugins).'</div>';
                }
            }
            $available = $operation == 'enable' ? 1 : 0;
            C::t('common_plugin')->update($_GET['pluginid'], array('available' => $available));
            updatecache(array('plugin', 'setting', 'styles'));
            cleartemplatecache();
            //updatemenu('plugin');
            if($operation == 'enable') {
                if(!$conflictplugins) {
                    $this->cpmsg('plugins_enable_succeed', $_G['siteurl'].GOURL, 'succeed');
                } else {
                    $this->cpmsg('plugins_conflict', $_G['siteurl'].GOURL, 'succeed', array('plugins' => $conflictplugins));
                }
            } else {
                $this->cpmsg('plugins_disable_succeed', $_G['siteurl'].GOURL, 'succeed');
            }
            $this->cpmsg('plugins_'.$operation.'_succeed', $_G['siteurl'].GOURL, 'succeed');

        }elseif($operation == 'plugininstall' || $operation == 'pluginupgrade') {

            $finish = FALSE;
            $dir = $_GET['dir'];
            $installtype = str_replace('/', '', $_GET['installtype']);
            $extra = $installtype ? '_'.$installtype : '';
            $xmlfile = 'discuz_plugin_'.$dir.$extra.'.xml';
            $importfile = DISCUZ_ROOT.'./source/plugin/'.$dir.'/'.$xmlfile;
            if(!file_exists($importfile)) {
                $this->cpmsg('plugin_file_error', '', 'error');
            }
            $_GET['importtxt'] = @implode('', file($importfile));
            $pluginarray = getimportdata('Discuz! Plugin');
            if($operation == 'plugininstall') {
                $filename = $pluginarray['installfile'];
            } else {
                $filename = $pluginarray['upgradefile'];
                $toversion = $pluginarray['plugin']['version'];
            }
            loadcache('pluginlanguage_install');
            $installlang = $_G['cache']['pluginlanguage_install'][$dir];

            if(!empty($filename) && preg_match('/^[\w\.]+$/', $filename)) {
                $filename = DISCUZ_ROOT.'./source/plugin/'.$dir.'/'.$filename;
                if(file_exists($filename)) {
                    @include_once $filename;
                } else {
                    $finish = TRUE;
                }
            } else {
                $finish = TRUE;
            }

            if($finish) {
                updatecache('setting');
                //updatemenu('plugin');
                dsetcookie('aljhtx_addoncheck_plugin', '', -1);
                if($operation == 'plugininstall') {
                    cloudaddons_clear('plugin', $dir);
                    $this->cpmsg('plugins_install_succeed', $_G['siteurl'].''.GOURL.'', 'succeed');
                } else {
                    cloudaddons_clear('plugin', $dir);
                    $plugins_upgrade_succeed = lang("plugin/aljhtx","log_php_5");
                    $this->cpmsg($plugins_upgrade_succeed, $_G['siteurl'].''.GOURL.'', 'succeed', array('toversion' => $toversion));
                }
            }

        } elseif($operation == 'upgrade') {

            $plugin = C::t('common_plugin')->fetch($pluginid);
            $modules = dunserialize($plugin['modules']);
            $dir = substr($plugin['directory'], 0, -1);

            if(!$_GET['confirmed']) {

                $file = DISCUZ_ROOT.'./source/plugin/'.$dir.'/discuz_plugin_'.$dir.($modules['extra']['installtype'] ? '_'.$modules['extra']['installtype'] : '').'.xml';
                $upgrade = false;
                if(file_exists($file)) {
                    $_GET['importtxt'] = @implode('', file($file));

                    $pluginarray = getimportdata('Discuz! Plugin');

                    $newver = !empty($pluginarray['plugin']['version']) ? $pluginarray['plugin']['version'] : 0;
                    $upgrade = $newver > $plugin['version'] ? true : false;
                }
                $entrydir = DISCUZ_ROOT.'./source/plugin/'.$dir;
                $upgradestr = '';
                if(file_exists($entrydir)) {
                    $d = dir($entrydir);
                    while($f = $d->read()) {
                        if(preg_match('/^discuz\_plugin\_'.$plugin['identifier'].'(\_\w+)?\.xml$/', $f, $a)) {
                            $extratxt = $extra = substr($a[1], 1);
                            if(preg_match('/^SC\_GBK$/i', $extra)) {
                                $extratxt = '&#31616;&#20307;&#20013;&#25991;&#29256;';
                            } elseif(preg_match('/^SC\_UTF8$/i', $extra)) {
                                $extratxt = '&#31616;&#20307;&#20013;&#25991;&#85;&#84;&#70;&#56;&#29256;';
                            } elseif(preg_match('/^TC\_BIG5$/i', $extra)) {
                                $extratxt = '&#32321;&#39636;&#20013;&#25991;&#29256;';
                            } elseif(preg_match('/^TC\_UTF8$/i', $extra)) {
                                $extratxt = '&#32321;&#39636;&#20013;&#25991;&#85;&#84;&#70;&#56;&#29256;';
                            }
                            if($modules['extra']['installtype'] == $extratxt) {
                                continue;
                            }
                            $_GET['importtxt'] = @implode('', file($entrydir.'/'.$f));
                            $pluginarray = getimportdata('Discuz! Plugin');
                            $newverother = !empty($pluginarray['plugin']['version']) ? $pluginarray['plugin']['version'] : 0;
                            $upgradestr .= $newverother > $plugin['version'] ? '<input class="btn" onclick="location.href=\'plugin.php?id=aljhtx&c=log&a=download&operation=upgrade&pluginid='.$pluginid.'&confirmed=yes&installtype='.rawurlencode($extra).'\'" type="button" value="'.($extra ? $extratxt : $lang['plugins_import_default']).' '.$newverother.'" />&nbsp;&nbsp;&nbsp;' : '';
                        }
                    }
                }
                if(!empty($pluginarray['checkfile']) && preg_match('/^[\w\.]+$/', $pluginarray['checkfile'])) {
                    $filename = DISCUZ_ROOT.'./source/plugin/'.$plugin['identifier'].'/'.$pluginarray['checkfile'];
                    if(file_exists($filename)) {
                        loadcache('pluginlanguage_install');
                        $installlang = $_G['cache']['pluginlanguage_install'][$plugin['identifier']];
                        @include $filename;
                    }
                }

                if($upgrade) {

                    $this->cpmsg('plugins_config_upgrade_confirm', $_G['siteurl'].'plugin.php?id=aljhtx&c=log&a=download&operation=upgrade&pluginid='.$pluginid.'&confirm=yes', 'form', array('pluginname' => $plugin['name'], 'version' => $plugin['version'], 'toversion' => $newver));

                } elseif($upgradestr) {

                    echo '<div class="infobox"><h4 class="marginbot normal">'.cplang('plugins_config_upgrade_other', array('pluginname' => $plugin['name'], 'version' => $plugin['version'])).'</h4><br /><p class="margintop">'.$upgradestr.
                        '<input class="btn" onclick="location.href=\''.GOURL.'\'" type="button" value="'.$lang['cancel'].'"/></div></div>';

                } else {

                    cloudaddons_installlog($pluginarray['plugin']['identifier'].'.plugin');
                    dsetcookie('aljhtx_addoncheck_plugin', '', -1);

                    cloudaddons_clear('plugin', $dir);
                    $plugins_config_upgrade_missed = lang("plugin/aljhtx","log_php_6");
                    $this->cpmsg($plugins_config_upgrade_missed, $_G['siteurl'].''.GOURL.'', 'succeed');

                }

            } else {

                $installtype = !isset($_GET['installtype']) ? $modules['extra']['installtype'] : (preg_match('/^\w+$/', $_GET['installtype']) ? $_GET['installtype'] : '');
                $importfile = DISCUZ_ROOT.'./source/plugin/'.$dir.'/discuz_plugin_'.$dir.($installtype ? '_'.$installtype : '').'.xml';
                if(!file_exists($importfile)) {
                    $this->cpmsg('plugin_file_error', '', 'error');
                }

                cloudaddons_validator($dir.'.plugin');

                $_GET['importtxt'] = @implode('', file($importfile));
                $pluginarray = getimportdata('Discuz! Plugin');

                if(!ispluginkey($pluginarray['plugin']['identifier']) || $pluginarray['plugin']['identifier'] != $plugin['identifier']) {
                    $this->cpmsg('plugins_edit_identifier_invalid', '', 'error');
                }
                if(is_array($pluginarray['vars'])) {
                    foreach($pluginarray['vars'] as $config) {
                        if(!ispluginkey($config['variable'])) {
                            $this->cpmsg('plugins_upgrade_var_invalid', '', 'error');
                        }
                    }
                }

                if(!empty($pluginarray['checkfile']) && preg_match('/^[\w\.]+$/', $pluginarray['checkfile'])) {
                    if(!empty($pluginarray['language'])) {
                        $installlang[$pluginarray['plugin']['identifier']] = $pluginarray['language']['installlang'];
                    }
                    $filename = DISCUZ_ROOT.'./source/plugin/'.$plugin['directory'].$pluginarray['checkfile'];
                    if(file_exists($filename)) {
                        loadcache('pluginlanguage_install');
                        $installlang = $_G['cache']['pluginlanguage_install'][$plugin['identifier']];
                        @include $filename;
                    }
                }

                pluginupgrade($pluginarray, $installtype);

                if(!empty($plugin['directory']) && !empty($pluginarray['upgradefile']) && preg_match('/^[\w\.]+$/', $pluginarray['upgradefile'])) {
                    dheader('location: plugin.php?id=aljhtx&c=log&a=download&operation=pluginupgrade&dir='.$dir.'&installtype='.$modules['extra']['installtype'].'&fromversion='.$plugin['version']);
                }
                $toversion = $pluginarray['plugin']['version'];

                cloudaddons_clear('plugin', $dir);

                $plugins_upgrade_succeed = lang("plugin/aljhtx","log_php_7");
                $this->cpmsg($plugins_upgrade_succeed, $_G['seteurl'].GOURL, 'succeed', array('toversion' => $toversion));

            }

        } elseif($operation == 'config') {
            $do = preg_replace('/[^\[A-Za-z0-9_\]]/', '', getgpc('do'));
            $action = preg_replace('/[^\[A-Za-z0-9_\]]/', '', getgpc('action'));
            if(empty($pluginid) && !empty($do)) {
                $pluginid = $do;
            }
            if($_GET['identifier']) {
                $plugin = C::t('common_plugin')->fetch_by_identifier($_GET['identifier']);
            } else {
                $plugin = C::t('common_plugin')->fetch($pluginid);
            }
            if(!$plugin) {
                $this->cpmsg('plugin_not_found', '', 'error');
            } else {
                $pluginid = $plugin['pluginid'];
            }
        
            $plugin['modules'] = dunserialize($plugin['modules']);
        
            $pluginvars = array();
            foreach(C::t('common_pluginvar')->fetch_all_by_pluginid($pluginid) as $var) {
                if(strexists($var['type'], '_')) {
                    continue;
                }
                $pluginvars[$var['variable']] = $var;
            }
        
            if($pluginvars) {
                $submenuitem[] = array('config', "plugins&operation=config&do=$pluginid", !$_GET['pmod']);
            }
            if(is_array($plugin['modules'])) {
                foreach($plugin['modules'] as $module) {
                    if($module['type'] == 3) {
                        parse_str($module['param'], $param);
                        if(!$pluginvars && empty($_GET['pmod'])) {
                            $_GET['pmod'] = $module['name'];
                            if($param) {
                                foreach($param as $_k => $_v) {
                                    $_GET[$_k] = $_v;
                                }
                            }
                        }
                        if($param) {
                            $m = true;
                            foreach($param as $_k => $_v) {
                                if(!isset($_GET[$_k]) || $_GET[$_k] != $_v) {
                                    $m = false;
                                    break;
                                }
                            }
                        } else {
                            $m = true;
                        }
                        $submenuitem[] = array($module['menu'], "plugins&operation=config&do=$pluginid&identifier=$plugin[identifier]&pmod=$module[name]".($module['param'] ? '&'.$module['param'] : ''), $_GET['pmod'] == $module['name'] && $m, !$_GET['pmod'] ? 1 : 0);
                    }
                }
            }
        
            if(empty($_GET['pmod'])) {
                
                if(!submitcheck('editsubmit')) {
                    $operation = '';
                    shownav('plugin', $plugin['name']);
                    showsubmenuanchors($plugin['name'], $submenuitem);
        
                    if($pluginvars) {
                        showformheader("plugins&operation=config&do=$pluginid");
                        showtableheader();
                        showtitle($lang['plugins_config']);
        
                        $extra = array();
                        foreach($pluginvars as $var) {
                            if(strexists($var['type'], '_')) {
                                continue;
                            }
                            $var['variable'] = 'varsnew['.$var['variable'].']';
                            if($var['type'] == 'number') {
                                $var['type'] = 'text';
                            } elseif($var['type'] == 'select') {
                                $var['type'] = "<select name=\"$var[variable]\">\n";
                                foreach(explode("\n", $var['extra']) as $key => $option) {
                                    $option = trim($option);
                                    if(strpos($option, '=') === FALSE) {
                                        $key = $option;
                                    } else {
                                        $item = explode('=', $option);
                                        $key = trim($item[0]);
                                        $option = trim($item[1]);
                                    }
                                    $var['type'] .= "<option value=\"".dhtmlspecialchars($key)."\" ".($var['value'] == $key ? 'selected' : '').">$option</option>\n";
                                }
                                $var['type'] .= "</select>\n";
                                $var['variable'] = $var['value'] = '';
                            } elseif($var['type'] == 'selects') {
                                $var['value'] = dunserialize($var['value']);
                                $var['value'] = is_array($var['value']) ? $var['value'] : array($var['value']);
                                $var['type'] = "<select name=\"$var[variable][]\" multiple=\"multiple\" size=\"10\">\n";
                                foreach(explode("\n", $var['extra']) as $key => $option) {
                                    $option = trim($option);
                                    if(strpos($option, '=') === FALSE) {
                                        $key = $option;
                                    } else {
                                        $item = explode('=', $option);
                                        $key = trim($item[0]);
                                        $option = trim($item[1]);
                                    }
                                    $var['type'] .= "<option value=\"".dhtmlspecialchars($key)."\" ".(in_array($key, $var['value']) ? 'selected' : '').">$option</option>\n";
                                }
                                $var['type'] .= "</select>\n";
                                $var['variable'] = $var['value'] = '';
                            } elseif($var['type'] == 'date') {
                                $var['type'] = 'calendar';
                                $extra['date'] = '<script type="text/javascript" src="static/js/calendar.js"></script>';
                            } elseif($var['type'] == 'datetime') {
                                $var['type'] = 'calendar';
                                $var['extra'] = 1;
                                $extra['date'] = '<script type="text/javascript" src="static/js/calendar.js"></script>';
                            } elseif($var['type'] == 'forum') {
                                require_once libfile('function/forumlist');
                                $var['type'] = '<select name="'.$var['variable'].'"><option value="">'.cplang('plugins_empty').'</option>'.forumselect(FALSE, 0, $var['value'], TRUE).'</select>';
                                $var['variable'] = $var['value'] = '';
                            } elseif($var['type'] == 'forums') {
                                $var['description'] = ($var['description'] ? (isset($lang[$var['description']]) ? $lang[$var['description']] : $var['description'])."\n" : '').$lang['plugins_edit_vars_multiselect_comment']."\n".$var['comment'];
                                $var['value'] = dunserialize($var['value']);
                                $var['value'] = is_array($var['value']) ? $var['value'] : array();
                                require_once libfile('function/forumlist');
                                $var['type'] = '<select name="'.$var['variable'].'[]" size="10" multiple="multiple"><option value="">'.cplang('plugins_empty').'</option>'.forumselect(FALSE, 0, 0, TRUE).'</select>';
                                foreach($var['value'] as $v) {
                                    $var['type'] = str_replace('<option value="'.$v.'">', '<option value="'.$v.'" selected>', $var['type']);
                                }
                                $var['variable'] = $var['value'] = '';
                            } elseif(substr($var['type'], 0, 5) == 'group') {
                                if($var['type'] == 'groups') {
                                    $var['description'] = ($var['description'] ? (isset($lang[$var['description']]) ? $lang[$var['description']] : $var['description'])."\n" : '').$lang['plugins_edit_vars_multiselect_comment']."\n".$var['comment'];
                                    $var['value'] = dunserialize($var['value']);
                                    $var['type'] = '<select name="'.$var['variable'].'[]" size="10" multiple="multiple"><option value=""'.(@in_array('', $var['value']) ? ' selected' : '').'>'.cplang('plugins_empty').'</option>';
                                } else {
                                    $var['type'] = '<select name="'.$var['variable'].'"><option value="">'.cplang('plugins_empty').'</option>';
                                }
                                $var['value'] = is_array($var['value']) ? $var['value'] : array($var['value']);
        
                                $query = C::t('common_usergroup')->range_orderby_credit();
                                $groupselect = array();
                                foreach($query as $group) {
                                    $group['type'] = $group['type'] == 'special' && $group['radminid'] ? 'specialadmin' : $group['type'];
                                    $groupselect[$group['type']] .= '<option value="'.$group['groupid'].'"'.(@in_array($group['groupid'], $var['value']) ? ' selected' : '').'>'.$group['grouptitle'].'</option>';
                                }
                                $var['type'] .= '<optgroup label="'.$lang['usergroups_member'].'">'.$groupselect['member'].'</optgroup>'.
                                    ($groupselect['special'] ? '<optgroup label="'.$lang['usergroups_special'].'">'.$groupselect['special'].'</optgroup>' : '').
                                    ($groupselect['specialadmin'] ? '<optgroup label="'.$lang['usergroups_specialadmin'].'">'.$groupselect['specialadmin'].'</optgroup>' : '').
                                    '<optgroup label="'.$lang['usergroups_system'].'">'.$groupselect['system'].'</optgroup></select>';
                                $var['variable'] = $var['value'] = '';
                            } elseif($var['type'] == 'extcredit') {
                                $var['type'] = '<select name="'.$var['variable'].'"><option value="">'.cplang('plugins_empty').'</option>';
                                foreach($_G['setting']['extcredits'] as $id => $credit) {
                                    $var['type'] .= '<option value="'.$id.'"'.($var['value'] == $id ? ' selected' : '').'>'.$credit['title'].'</option>';
                                }
                                $var['type'] .= '</select>';
                                $var['variable'] = $var['value'] = '';
                            }
        
                            showsetting(isset($lang[$var['title']]) ? $lang[$var['title']] : dhtmlspecialchars($var['title']), $var['variable'], $var['value'], $var['type'], '', 0, isset($lang[$var['description']]) ? $lang[$var['description']] : nl2br(dhtmlspecialchars($var['description'])), dhtmlspecialchars($var['extra']), '', true);
                        }
                        showsubmit('editsubmit');
                        showtablefooter();
                        showformfooter();
                        echo implode('', $extra);
                    }
        
                } else {
                    
                    if(is_array($_GET['varsnew'])) {
                        
                        foreach($_GET['varsnew'] as $variable => $value) {
                            if(isset($pluginvars[$variable])) {
                                if($pluginvars[$variable]['type'] == 'number') {
                                    $value = (float)$value;
                                } elseif(in_array($pluginvars[$variable]['type'], array('forums', 'groups', 'selects'))) {
                                    $value = serialize($value);
                                }
                                $value = (string)$value;
                                
                                C::t('common_pluginvar')->update_by_variable($pluginid, $variable, array('value' => $value));
                            }
                        }
                    }
        
                    updatecache(array('plugin', 'setting', 'styles'));
                    cleartemplatecache();
                    $this->cpmsg('plugins_setting_succeed', ADMINSCRIPT.'&action=plugins&operation=config&do='.$pluginid.'&anchor='.$anchor, 'succeed');
        
                }
        
            } else {
                
                $scriptlang[$plugin['identifier']] = lang('plugin/'.$plugin['identifier']);
                $modfile = '';
                if(is_array($plugin['modules'])) {
                    foreach($plugin['modules'] as $module) {
                        if($module['type'] == 3 && $module['name'] == $_GET['pmod']) {
                            $plugin['directory'] .= (!empty($plugin['directory']) && substr($plugin['directory'], -1) != '/') ? '/' : '';
                            $modfile = './source/plugin/'.$plugin['directory'].$module['name'].'.inc.php';
                            break;
                        }
                    }
                }
        
                if($modfile) {
                    shownav('plugin', $plugin['name']);
                    showsubmenu($plugin['name'], $submenuitem);
                    if(!@include(DISCUZ_ROOT.$modfile)) {
                        $this->cpmsg('plugins_setting_module_nonexistence', '', 'error', array('modfile' => $modfile));
                    } else {
                        exit();
                    }
                } else {
                    $this->cpmsg('plugin_file_error', '', 'error');
                }
        
            }
        
        }else{
            $step = intval($_GET['step']);
            $addoni = intval($_GET['i']);

            if(!$_GET['md5hash'] || md5($_GET['addonids'].md5(cloudaddons_getuniqueid().$_GET['timestamp'])) != $_GET['md5hash']) {
                $this->cpmsg('cloudaddons_validator_error', '', 'error');
            }
            $addonids = explode(',', $_GET['addonids']);
            list($_GET['key'], $_GET['type'], $_GET['rid']) = explode('.', isset($addonids[$addoni]) ? $addonids[$addoni] : $addonids[0]);
            if($step == 0) {
                $this->cpmsg('cloudaddons_downloading', $_G['siteurl']."/plugin.php?id=aljhtx&c=log&a=download&addonids=$_GET[addonids]&i=$addoni&step=1&md5hash=".$_GET['md5hash'].'&timestamp='.$_GET['timestamp'], 'loading', array('addonid' => $_GET['key'].'.'.$_GET['type']), '<div>0%</div>', FALSE);
            } elseif($step == 1) {
                $packnum = isset($_GET['num']) ? $_GET['num'] : 0;
                $tmpdir = DISCUZ_ROOT.'./data/download/'.$_GET['rid'];
                $end = '';
                $md5tmp = DISCUZ_ROOT.'./data/download/'.$_GET['rid'].'.md5';

                if($packnum) {
                    list($md5total, $md5s) = unserialize(implode('', @file($md5tmp)));
                    dmkdir($tmpdir, 0777, false);
                } else {
                    $this->dir_clear($tmpdir);
                    @unlink($md5tmp);
                    dmkdir($tmpdir, 0777, false);
                    $md5total = '';
                    $md5s = array();
                }

                $data = $this -> cloudaddons_open('&mod=app&ac=download&rid='.$_GET['rid'].'&packnum='.$packnum);

                $_GET['importtxt'] = $data;

                $array = getimportdata('Discuz! File Pack');

                if(!$array['Status']) {
                    list($_cur, $_max) = explode('/', $array['part']);
                    $percent = intval($_cur/$_max * 100);
                    if($array['type'] != $_GET['type'] || $array['key'] != $_GET['key'] || !$array['files']) {
                        $this->dir_clear($tmpdir);
                        @unlink($md5tmp);
                        cloudaddons_faillog($_GET['rid'], 100);

                        $this->cpmsg('cloudaddons_download_error', '', 'error', array('ErrorCode' => 100));
                    }
                    foreach($array['files'] as $file => $data) {
                        $filename = $tmpdir.'/'.$file.'._addons_';
                        $dirname = dirname($filename);
                        dmkdir($dirname, 0777, false);
                        $fp = fopen($filename, !$data['Part'] ? 'w' : 'a');
                        if(!$fp) {
                            $this->dir_clear($tmpdir);
                            @unlink($md5tmp);
                            cloudaddons_faillog($_GET['rid'], 101);
                            $this->cpmsg('cloudaddons_download_write_error', '', 'error');
                        }
                        fwrite($fp, gzuncompress(base64_decode($data['Data'])));
                        fclose($fp);
                        if($data['MD5']) {
                            $md5total .= $data['MD5'];
                            $md5s[$filename] = $data['MD5'];
                        }
                    }
                    $fp = fopen($md5tmp, 'w');
                    fwrite($fp, serialize(array($md5total, $md5s)));
                    fclose($fp);
                } elseif($array['Status'] == 'Error') {

                    $this->dir_clear($tmpdir);
                    @unlink($md5tmp);
                    cloudaddons_faillog($_GET['rid'], $array['ErrorCode']);
                    $this->cpmsg('cloudaddons_install_error', '', 'error', array('ErrorCode' => $array['ErrorCode']));
                } else {
                    foreach($md5s as $file => $md5) {
                        if($md5 != md5_file($file)) {
                            $this->dir_clear($tmpdir);
                            @unlink($md5tmp);
                            cloudaddons_faillog($_GET['rid'], 102);
                            $this->cpmsg('cloudaddons_download_error', '', 'error', array('ErrorCode' => 102));
                        }
                    }
                    @unlink($md5tmp);

                    $end = rawurlencode(cloudaddons_http_build_query($array));
                }
                if(!$end) {
                    $packnum++;
                    $this->cpmsg('cloudaddons_downloading', $_G['siteurl']."/plugin.php?id=aljhtx&c=log&a=download&addonids=$_GET[addonids]&i=$addoni&step=1&md5hash=".$_GET['md5hash'].'&timestamp='.$_GET['timestamp'].'&num='.$packnum, 'loading', array('addonid' => $_GET['key'].'.'.$_GET['type']), '<div>'.$percent.'%</div>', FALSE);
                } else {
                    if($md5total !== '' && md5($md5total) !== cloudaddons_md5($_GET['key'].'_'.$_GET['rid'])) {
                        $this->dir_clear($tmpdir);
                        @unlink($md5tmp);
                        cloudaddons_faillog($_GET['rid'], 105);

                        $this->cpmsg('cloudaddons_download_error', '', 'error', array('ErrorCode' => 105));
                    }

                    $this->cpmsg('cloudaddons_installing', $_G['siteurl']."/plugin.php?id=aljhtx&c=log&a=download&addonids=$_GET[addonids]&i=$addoni&end=$end&step=2&md5hash=".$_GET['md5hash'].'&timestamp='.$_GET['timestamp'], 'loading', array('addonid' => $_GET['key'].'.'.$_GET['type']), FALSE);
                }
            } elseif($step == 2) {
                $tmpdir = DISCUZ_ROOT.'./data/download/'.$_GET['rid'];
                if(!file_exists($tmpdir)) {
                    $this->dir_clear($tmpdir);
                    cloudaddons_faillog($_GET['rid'], 103);
                    $this->cpmsg('cloudaddons_download_error', '', 'error', array('ErrorCode' => 103));
                }
                $typedir = array(
                    'plugin' => 'source/plugin',
                    'template' => 'template',
                    'pack' => '.',
                );
                if(!$typedir[$_GET['type']]) {
                    $this->dir_clear($tmpdir);
                    cloudaddons_faillog($_GET['rid'], 104);
                    $this->cpmsg('cloudaddons_download_error', '', 'error', array('ErrorCode' => 104));
                }
                if($_GET['type'] != 'pack') {
                    $descdir = DISCUZ_ROOT.$typedir[$_GET['type']].'/';
                    $subdir = $_GET['key'];
                } else {
                    $descdir = DISCUZ_ROOT;
                    $subdir = '';
                }
                $unwriteabledirs = cloudaddons_dirwriteable($descdir, $subdir, $tmpdir);

                if($unwriteabledirs) {
                    $cloudaddons_unwriteabledirs = '站点 {basedir} 目录下的以下目录不可写，无法在线安装此应用，请修改以下目录的写入权限:<br />{unwriteabledirs}';
                    $this->cpmsg($cloudaddons_unwriteabledirs, '', 'error', array('basedir' => $typedir[$_GET['type']] != '.' ? $typedir[$_GET['type']] : '/', 'unwriteabledirs' => implode(', ', $unwriteabledirs)));
                }
                $descdir .= $subdir;
                cloudaddons_comparetree($tmpdir, $descdir, $tmpdir, $_GET['key'].'.'.$_GET['type'], 1);
                if(!empty($_G['treeop']['oldchange']) && empty($_GET['confirmed'])) {
                    $this->cpmsg('cloudaddons_install_files_changed', '', 'form', array('files' => implode('<br />', $_G['treeop']['oldchange'])));
                }
                cloudaddons_copytree($tmpdir, $descdir);
                cloudaddons_savemd5($_GET['key'].'.'.$_GET['type'], $_GET['end'], $_G['treeop']['md5']);
                cloudaddons_deltree($tmpdir);
                if(count($addonids) - 1 > $addoni) {

                    $addoni++;
                    $this->cpmsg('cloudaddons_downloading', $_G['siteurl']."/plugin.php?id=aljhtx&c=log&a=download&addonids=$_GET[addonids]&i=$addoni&step=1&md5hash=".$_GET['md5hash'].'&timestamp='.$_GET['timestamp'], 'loading', array('addonid' => $_GET['key'].'.'.$_GET['type']), FALSE);
                }
                list($_GET['key'], $_GET['type'], $_GET['rid']) = explode('.', $addonids[0]);
                cloudaddons_downloadlog($_GET['key'].'.'.$_GET['type']);
                if($_GET['type'] == 'plugin') {
                    $plugin = C::t('common_plugin')->fetch_by_identifier($_GET['key']);

                    if(!$plugin['pluginid']) {
                        dheader('location: plugin.php?id=aljhtx&c=log&a=download&operation=import&dir='.$_GET['key']);
                    } else {
                        dheader('location: plugin.php?id=aljhtx&c=log&a=download&operation=upgrade&pluginid='.$plugin['pluginid']);
                    }
                } elseif($_GET['type'] == 'template') {
                    dheader('location: plugin.php?id=aljhtx&c=log&a=download&action=styles&operation=import&dir='.$_GET['key']);
                } else {
                    cloudaddons_validator($_GET['key'].'.pack');
                    cloudaddons_installlog($_GET['key'].'.pack');
                    if(file_exists(DISCUZ_ROOT.'./data/addonpack/'.$_GET['key'].'.php')) {
                        dheader('location: '.$_G['siteurl'].'data/addonpack/'.$_GET['key'].'.php');
                    }
                    $this->cpmsg('cloudaddons_pack_installed', '', 'succeed');
                }
            }
        }
        echo "\n</div>";
    }
    /**
     * 目录权限
     *
     * @return void
     */
    public function check(){
        if(!$this->page->config->aljqb){
            $this->page->showMessage(lang("plugin/aljhtx","log_php_8"), '<a style="color: rgb(30, 159, 255);" target="_blank" href="http://addon.dismall.com/?@aljqb.plugin">' . lang("plugin/aljhtx","log_php_1") . '</a>');
        }
        require_once DISCUZ_ROOT.'./source/discuz_version.php';
        $mapp_wechat = unserialize($this->page->setting->mapp_wechat);
        $dataListOther = array(
            '1' => array(
                'identifier' => 'aljbd',
                'name' => lang("plugin/aljhtx","log_php_9"),
                'intro' => $_SERVER["SERVER_SOFTWARE"],
                'is_install' => 1
            ),
            '2' => array(
                'identifier' => 'aljbd',
                'name' => 'MySQL'.lang("plugin/aljhtx","log_php_10"),
                'intro' => helper_dbtool::dbversion(),
                'is_install' => 1
            ),
            '3' => array(
                'identifier' => 'aljbd',
                'name' => lang("plugin/aljhtx","log_php_11").'/IP',
                'intro' => $_SERVER[SERVER_NAME]. '('. $_SERVER[SERVER_ADDR].':'.$_SERVER[SERVER_PORT].')',
                'is_install' => 1
            ),
            '4' => array(
                'identifier' => 'aljbd',
                'name' => 'Discuz! '.lang("plugin/aljhtx","log_php_12"),
                'intro' => 'Discuz! '.DISCUZ_VERSION.' Release '.DISCUZ_RELEASE,
                'is_install' => 1
            ),
            '5' => array(
                'identifier' => 'aljbd',
                'name' => 'curl'.lang("plugin/aljhtx","log_php_13"),
                'intro' => lang("plugin/aljhtx","log_php_14").'CURL'.lang("plugin/aljhtx","log_php_15"),
                'is_install' => function_exists('curl_init') && function_exists('curl_exec') ? 1 : ''
            ),
            '6' => array(
                'identifier' => 'aljbd',
                'name' => 'file_get_contents'.lang("plugin/aljhtx","log_php_16"),
                'intro' => lang("plugin/aljhtx","log_php_17").'PHP'.lang("plugin/aljhtx","log_php_18").'file_get_contents',
                'is_install' => function_exists('file_get_contents') ? 1 : ''
            ),
            '7' => array(
                'identifier' => 'aljbd',
                'name' => 'gd'.lang("plugin/aljhtx","log_php_19"),
                'intro' => lang("plugin/aljhtx","log_php_20").'PHP'.lang("plugin/aljhtx","log_php_21").'GD'.lang("plugin/aljhtx","log_php_22"),
                'is_install' => function_exists('gd_info') ? 1 : ''
            ),
            '8' => array(
                'identifier' => 'aljbd',
                'name' => lang("plugin/aljhtx","log_php_23"),
                'intro' => lang("plugin/aljhtx","log_php_24"),
                'is_install' => DB::result_first('select `value` from %t where `key`=%s', array('aljqb_paysetting', 'OAuth')) ? 1 : ''
            ),
            '9' => array(
                'identifier' => 'aljbd',
                'name' => lang("plugin/aljhtx","log_php_25").'OAuth2.0'.lang("plugin/aljhtx","log_php_26"),
                'intro' => lang("plugin/aljhtx","log_php_27").'OAuth2.0'.lang("plugin/aljhtx","log_php_28"),
                'is_install' => DB::result_first('select `value` from %t where `key`=%s', array('aljgwc_paysetting', 'OAuth')) ? 1 : ''
            ),
            '10' => array(
                'identifier' => 'aljbd',
                'name' => lang("plugin/aljhtx","log_php_29"),
                'intro' => lang("plugin/aljhtx","log_php_30"),
                'is_install' => $mapp_wechat['wxurl'] ? 1 : ''
            )
        );

        $dataListAljqb = array(
            'aljbdx' => array(
                'identifier' => 'aljbdx',
                'name' => lang("plugin/aljhtx","log_php_31"),
                'intro' => lang("plugin/aljhtx","log_php_32"),
            ),
            'aljqb' => array(
                'identifier' => 'aljqb',
                'name' => lang("plugin/aljhtx","log_php_33"),
                'intro' => lang("plugin/aljhtx","log_php_34"),
            ),
            'aljad' => array(
                'identifier' => 'aljad',
                'name' => lang("plugin/aljhtx","log_php_35"),
                'intro' => lang("plugin/aljhtx","log_php_36"),
            ),
            'aljbgp' => array(
                'identifier' => 'aljbgp',
                'name' => lang("plugin/aljhtx","log_php_37"),
                'intro' => lang("plugin/aljhtx","log_php_38"),
            ),
            'aljpay' => array(
                'identifier' => 'aljpay',
                'name' => lang("plugin/aljhtx","log_php_39"),
                'intro' => lang("plugin/aljhtx","log_php_40"),
            ),
            'aljds' => array(
                'identifier' => 'aljds',
                'name' => lang("plugin/aljhtx","log_php_41"),
                'intro' => lang("plugin/aljhtx","log_php_42"),
            ),
            'aljhb' => array(
                'identifier' => 'aljhb',
                'name' => lang("plugin/aljhtx","log_php_43"),
                'intro' => lang("plugin/aljhtx","log_php_44"),
            )
        );
        $dataList = array_merge($dataListOther, $dataListAljqb);
        if($this->page->get->render == 'yes') {
            $secretKeyData = DB::fetch_all('select * from %t', array('aljqb_secretkey'));
            foreach($secretKeyData as $k => $v){
                $secretKeyList[$v['pluginname']] = $v;
            }
            foreach($dataList as $k => $v){
                if(is_int($k)){
                    continue;
                }
                if($secretKeyList[$v['identifier']]){
                    $dataList[$k]['is_install'] = 1;
                }

            }

            //debug($dataList);
            T::responseJson(array(
                'code' => 0,
                'msg' => "",
                'count' => count($dataList),
                'data' => $dataList
            ));
        } elseif ($this->page->get->render == 'repair'){
            if(!$this->page->config->aljqb){
                T::responseJson(array('code' => 40001, 'msg' => 'aljqb not installed'));
            }else{
                $pluginid = $this->page->get->identifier;
                if(!$this->page->config->{$pluginid}){
                    T::responseJson(array('code' => 40002, 'msg' => 'aljbd not installed'));
                }
                if($this->page->get->identifier == 'aljbdx'){
                    DB::insert('aljqb_secretkey', array(
                        'pluginname' => 'aljbdx',
                        'secretkey' => 'ko999654',
                        'tokenurl' => $this->page->global->siteurl.'source/plugin/aljbdx/pay/pay.php',
                    ));
                    DB::insert('aljqb_secretkey', array(
                        'pluginname' => 'aljbdx_rz',
                        'secretkey' => 'ko999654',
                        'tokenurl' => $this->page->global->siteurl.'source/plugin/aljbdx/pay/pay_rz.php',
                    ));
                }else{
                    if(array_key_exists($this->page->get->identifier, $dataListAljqb)){
                        DB::insert('aljqb_secretkey', array(
                            'pluginname' => $this->page->get->identifier,
                            'secretkey' => 'shenmikey123',
                            'tokenurl' => $this->page->global->siteurl.'source/plugin/'.$this->page->get->identifier.'/pay/pay.php',
                        ));
                    }
                }

                T::responseJson();
            }

        }
        $this->page->display();
    }

    /**
     * 目录权限
     *
     * @return void
     */
    public function dir(){
        if($this->page->get->render == 'yes'){
            $dataList = array(
                '0' => array(
                    'identifier' => './',
                    'name' => lang("plugin/aljhtx","log_php_45"),
                    'intro' => lang("plugin/aljhtx","log_php_46"),
                ),
                '1' => array(
                    'identifier' => './source/plugin/',
                    'name' => lang("plugin/aljhtx","log_php_47"),
                    'intro' => lang("plugin/aljhtx","log_php_48"),
                ),
                '2' => array(
                    'identifier' => './source/plugin/aljhtx/static/setting/',
                    'name' => lang("plugin/aljhtx","log_php_49"),
                    'intro' => lang("plugin/aljhtx","log_php_50"),
                ),
                '3' => array(
                    'identifier' => './source/plugin/aljbd/images/',
                    'name' => lang("plugin/aljhtx","log_php_51"),
                    'intro' => lang("plugin/aljhtx","log_php_52"),
                ),
                '4' => array(
                    'identifier' => './source/plugin/aljbd/images/logo/',
                    'name' => lang("plugin/aljhtx","log_php_53"),
                    'intro' => lang("plugin/aljhtx","log_php_54"),
                ),
                '5' => array(
                    'identifier' => './source/plugin/aljbd/images/consume/',
                    'name' => lang("plugin/aljhtx","log_php_55"),
                    'intro' => lang("plugin/aljhtx","log_php_56"),
                ),
                '6' => array(
                    'identifier' => './source/plugin/aljbd/images/album/',
                    'name' => lang("plugin/aljhtx","log_php_57"),
                    'intro' => lang("plugin/aljhtx","log_php_58"),
                ),
                '7' => array(
                    'identifier' => './source/plugin/aljbd/images/adv/',
                    'name' => lang("plugin/aljhtx","log_php_59"),
                    'intro' => lang("plugin/aljhtx","log_php_60"),
                ),
                '8' => array(
                    'identifier' => './source/plugin/aljbd/images/type/',
                    'name' => lang("plugin/aljhtx","log_php_61"),
                    'intro' => lang("plugin/aljhtx","log_php_62"),
                ),
                '9' => array(
                    'identifier' => './source/plugin/aljol/static/img/file/',
                    'name' => lang("plugin/aljhtx","log_php_63"),
                    'intro' => lang("plugin/aljhtx","log_php_64"),
                ),
                '10' => array(
                    'identifier' => './source/plugin/aljad/static/imgfile/',
                    'name' => lang("plugin/aljhtx","log_php_65"),
                    'intro' => lang("plugin/aljhtx","log_php_66"),
                ),
            );

            foreach($dataList as $k => $v){
                $dataList[$k]['is_install'] = T::dir_writeable($v['identifier']);
            }

            //debug($dataList);
            T::responseJson(array(
                'code' => 0,
                'msg' => "",
                'count' => count($dataList),
                'data' => $dataList
            ));
        }
        $this->page->display();
    }
    public function cloudaddons_upgradecheck($addonids) {
        $post = array();
        foreach($addonids as $addonid) {
            $array = cloudaddons_getmd5($addonid);

            $post[] = 'rid['.$addonid.']='.$array['RevisionID'].'&sn['.$addonid.']='.$array['SN'].'&rd['.$addonid.']='.$array['RevisionDateline'];
        }

        return $this -> cloudaddons_open('&mod=app&ac=validator&ver=2', implode('&', $post), 15);
    }
    public function cloudaddons_app() {
        
        return $this -> cloudaddons_open('&mod=app&ac=validator&ver=yes', '', 15);
    }
    /**
     * 插件列表
     *
     * @return void
     */
    public function plugin(){
        global $_G;
        if($_G['groupid'] != 1){
            $this->page->showMessage(lang("plugin/aljhtx","log_php_67"));
        }
        $_G['setting']['hookscript'] = array();

        $_G['config']['addonsource'] = 'yibaihui';
        $_G['config']['addon'] = array(
            'yibaihui' => array(
                'website_url' => $this->website_url,
                'download_url' => $this->download_url,
                'download_ip' => '',
                'check_url' => $this->check_url,
                'check_ip' => '',
            )
        );
        
        require_once DISCUZ_ROOT . './source/language/lang_admincp.php';
        if($_G['cache']['plugin']['aljhtx']['is_plugin_open']){
            require_once libfile('function/cloudaddons');
        }else{
            require_once DISCUZ_ROOT . './source/plugin/aljhtx/function/function_cloudaddons.php';
        }
        $param_url = cloudaddons_url('');
        $param_url = str_replace('?data','&data',$param_url);
        if($this->page->get->render == 'update_app'){
            dsetcookie('aljhtx_addoncheck_plugin', '', -1);
            echo 1;
            exit;
        }else if($this->page->get->render == 'yes'){

            $dataList = array(
                '0' => array(
                    'identifier' => 'aljbd',
                    'name' => lang("plugin/aljhtx","log_php_68"),
                    'intro' => lang("plugin/aljhtx","log_php_69"),
                    'is_install' => 0,
                ),
                '1' => array(
                    'identifier' => 'aljgwc',
                    'name' => lang("plugin/aljhtx","log_php_70"),
                    'intro' => lang("plugin/aljhtx","log_php_71"),
                ),
                '2' => array(
                    'identifier' => 'aljbdx',
                    'name' => lang("plugin/aljhtx","log_php_72"),
                    'intro' => lang("plugin/aljhtx","log_php_73"),
                ),
                '3' => array(
                    'identifier' => 'aljqb',
                    'name' => lang("plugin/aljhtx","log_php_74"),
                    'intro' => lang("plugin/aljhtx","log_php_75"),
                ),
                '4' => array(
                    'identifier' => 'aljwx',
                    'name' => lang("plugin/aljhtx","log_php_76"),
                    'intro' => lang("plugin/aljhtx","log_php_77") . 'http://docs.liangjianyun.com/miniprogram/',
                ),
                '5' => array(
                    'identifier' => 'aljtc',
                    'name' => lang("plugin/aljhtx","log_php_78"),
                    'intro' => lang("plugin/aljhtx","log_php_79"),
                ),
                '6' => array(
                    'identifier' => 'aljstg',
                    'name' => lang("plugin/aljhtx","log_php_80"),
                    'intro' => lang("plugin/aljhtx","log_php_81"),
                ),
                '7' => array(
                    'identifier' => 'aljht',
                    'name' => lang("plugin/aljhtx","log_php_82"),
                    'intro' => lang("plugin/aljhtx","log_php_83"),
                ),
                '8' => array(
                    'identifier' => 'aljhtx',
                    'name' => lang("plugin/aljhtx","log_php_84"),
                    'intro' => lang("plugin/aljhtx","log_php_85"),
                ),
                '9' => array(
                    'identifier' => 'aljol',
                    'name' => lang("plugin/aljhtx","log_php_86"),
                    'intro' => lang("plugin/aljhtx","log_php_87"),
                ),
                '10' => array(
                    'identifier' => 'aljdx',
                    'name' => lang("plugin/aljhtx","log_php_88"),
                    'intro' => lang("plugin/aljhtx","log_php_89"),
                ),
                '11' => array(
                    'identifier' => 'aljyzm',
                    'name' => lang("plugin/aljhtx","log_php_90"),
                    'intro' => lang("plugin/aljhtx","log_php_91"),
                ),
                '12' => array(
                    'identifier' => 'aljyzm',
                    'name' => 'QQ'.lang("plugin/aljhtx","log_php_92"),
                    'intro' => lang("plugin/aljhtx","log_php_93"),
                ),
                '13' => array(
                    'identifier' => 'mapp_wechat',
                    'name' => lang("plugin/aljhtx","log_php_94"),
                    'intro' => lang("plugin/aljhtx","log_php_95"),
                ),
                '14' => array(
                    'identifier' => 'mapp_template',
                    'name' => lang("plugin/aljhtx","log_php_96"),
                    'intro' => lang("plugin/aljhtx","log_php_97"),
                ),
                '15' => array(
                    'identifier' => 'aljlogin',
                    'name' => lang("plugin/aljhtx","log_php_98"),
                    'intro' => lang("plugin/aljhtx","log_php_99"),
                ),
                '16' => array(
                    'identifier' => 'aljdm',
                    'name' => lang("plugin/aljhtx","log_php_100"),
                    'intro' => lang("plugin/aljhtx","log_php_101"),
                ),
                '17' => array(
                    'identifier' => 'aljad',
                    'name' => lang("plugin/aljhtx","log_php_102"),
                    'intro' => lang("plugin/aljhtx","log_php_103"),
                ),
                 '18' => array(
                    'identifier' => 'aljspt',
                    'name' => lang("plugin/aljhtx","log_php_104"),
                    'intro' => lang("plugin/aljhtx","log_php_105"),
                ),
                '19' => array(
                    'identifier' => 'aljsyh',
                    'name' => lang("plugin/aljhtx","log_php_106"),
                    'intro' => lang("plugin/aljhtx","log_php_107"),
                 ),
                '20' => array(
                    'identifier' => 'aljsfx',
                    'name' => lang("plugin/aljhtx","log_php_108"),
                    'intro' => lang("plugin/aljhtx","log_php_109"),
                ),
                '21' => array(
                    'identifier' => 'aljapp',
                    'name' => lang("plugin/aljhtx","log_php_110").'/'.lang("plugin/aljhtx","log_php_111").'APP',
                    'intro' => lang("plugin/aljhtx","log_php_112").'/'.lang("plugin/aljhtx","log_php_113").'APP'.lang("plugin/aljhtx","log_php_114"),
                ),
                '22' => array(
                    'identifier' => 'aljpay',
                    'name' => lang("plugin/aljhtx","log_php_115"),
                    'intro' => lang("plugin/aljhtx","log_php_116").'Discuz!'.lang("plugin/aljhtx","log_php_117"),
                ),
                '23' => array(
                    'identifier' => 'aljhelp',
                    'name' => lang("plugin/aljhtx","log_php_118"),
                    'intro' => lang("plugin/aljhtx","log_php_119"),
                ),
                '24' => array(
                    'identifier' => 'aljwsq',
                    'name' => lang("plugin/aljhtx","log_php_120"),
                    'intro' => lang("plugin/aljhtx","log_php_121"),
                ),
                '25' => array(
                    'identifier' => 'mapp_share',
                    'name' => lang("plugin/aljhtx","log_php_122"),
                    'intro' => lang("plugin/aljhtx","log_php_123"),
                ),
                '26' => array(
                    'identifier' => 'aljhb',
                    'name' => lang("plugin/aljhtx","log_php_124"),
                    'intro' => lang("plugin/aljhtx","log_php_125"),
                ),
                '27' => array(
                    'identifier' => 'mapp_base',
                    'name' => 'MAPP'.lang("plugin/aljhtx","log_php_126"),
                    'intro' => lang("plugin/aljhtx","log_php_127"),
                ),
                '28' => array(
                    'identifier' => 'aljlbs',
                    'name' => lang("plugin/aljhtx","log_php_128").'LBS',
                    'intro' => lang("plugin/aljhtx","log_php_129"),
                ),
                '29' => array(
                    'identifier' => 'aljgz',
                    'name' => lang("plugin/aljhtx","log_php_130"),
                    'intro' => lang("plugin/aljhtx","log_php_131"),
                ),
                '30' => array(
                    'identifier' => 'aljsyy',
                    'name' => lang("plugin/aljhtx","log_php_132"),
                    'intro' => lang("plugin/aljhtx","log_php_133"),
                ),
                '31' => array(
                    'identifier' => 'aljsp',
                    'name' => lang("plugin/aljhtx","log_php_134"),
                    'intro' => lang("plugin/aljhtx","log_php_135"),
                ),
            );

            $pluginListData = DB::fetch_all('select * from %t', array('common_plugin'));

            foreach($pluginListData as $k => $v){
                $v['modules'] = unserialize($v['modules']);
                $pluginList[$v['identifier']] = $v;
            }

            if(empty($_G['cookie']['aljhtx_addoncheck_plugin'])) {
                foreach($dataList as $p_key => $plugin) {
                    $addonids[$p_key] = $plugin['identifier'].'.plugin';
                }

                $checkresult = dunserialize($this -> cloudaddons_upgradecheck($addonids));
                $checkresult_app = dunserialize($this -> cloudaddons_app());
                //debug(cloudaddons_upgradecheck($addonids));
                savecache('aljhtx_addoncheck_plugin', $checkresult);
                savecache('aljhtx_addoncheck_plugin_app', $checkresult_app);
                dsetcookie('aljhtx_addoncheck_plugin', 1, 43200);
            } else {
                loadcache('aljhtx_addoncheck_plugin');
                loadcache('aljhtx_addoncheck_plugin_app');
                $checkresult = $_G['cache']['aljhtx_addoncheck_plugin'];
                $checkresult_app = $_G['cache']['aljhtx_addoncheck_plugin_app'];
                if($checkresult_app){
                    $dataList = $checkresult_app;
                }
            }

            if($_G['charset']=='gbk'){
                if($checkresult){
                    $checkresult = T::ajaxGetCharSet($checkresult);
                }
                if($checkresult_app){
                    $dataList = T::ajaxGetCharSet($checkresult_app);
                }
            }

            
            foreach($dataList as $k => $v){
                $addonid = $v['identifier'].'.plugin';
                $dataList[$k]['is_install'] = $pluginList[$v['identifier']] ? 1 : 0;
                $dataList[$k]['available'] = $pluginList[$v['identifier']]['available'];
                $dataList[$k]['pluginid'] = $pluginList[$v['identifier']]['pluginid'];
                $dataList[$k]['name'] = $checkresult[$addonid] ? $v['name'].$pluginList[$v['identifier']]['version'].'<a href="'.$param_url.'&addonids='.$v['identifier'].'" title="'.$lang['plugins_online_update'].'" target="_blank"><font color="red">'.$lang['plugins_find_newversion'].' '.$checkresult[$addonid]['revisionname'].'</font></a>' : $v['name'].$pluginList[$v['identifier']]['version'];
                $dataList[$k]['is_update'] = $checkresult[$addonid] ? 1 : 0;
                $dataList[$k]['is_update_url'] = $param_url.'&addonids='.$v['identifier'];
            }

            //debug($dataList);
            T::responseJson(array(
                'code' => 0,
                'msg' => "",
                'count' => count($dataList),
                'data' => $dataList
            ));
        }
        $this->page->assign('navtitle', lang("plugin/aljhtx","log_php_136"));
        $this->page->assign('param_url', $param_url);
        $this->page->display();
    }

    /**
     * 删除系统消息
     *
     * @return void
     */
    public function deleteLog(){
        DB::delete('home_notification', array('id' => $this->page->get->logid));
        T::responseJson();
    }

    /**
     * 系统消息
     *
     * @return void
     */
    public function log(){
        T::getObject('Log');
        $count = Log::countNotificationList('system', $this->page->get->search);
        $logList = Log::getNotificationList('system', $this->page->get->search, $this->page->get->page>0 ? $this->page->get->page :1, 20);
        if($this->page->get->render == 'yes'){
            foreach($logList as $k => $v){
                if($v['new']){
                    $logList[$k]['new'] = lang("plugin/aljhtx","log_php_137");
                }else{
                    $logList[$k]['new'] = lang("plugin/aljhtx","log_php_138");
                }
                if(!$v['author']){
                    $logList[$k]['author'] = lang("plugin/aljhtx","log_php_139");
                }
                $logList[$k]['dateline'] = dgmdate($v['dateline'], 'u');
            }
            T::responseJson(array(
                'code' => 0,
                'msg' => "",
                'count' => $count,
                'data' => $logList
            ));
        }
        $this->page->assign('dourl', PAGE_URL); //当前请求页面URL
        $this->page->assign('logList', $logList, true);
        $this->page->display();
    }

    /**
     * 短信发送日志
     *
     * @return void
     */
    public function aljdx(){
        if($this->page->config->aljdx){
            T::getObject('Log');
            $count = Log::countAljdx();
            $logList = Log::aljdx($this->page->get->search, $this->page->curPage, 20);
            $pagingUrl = A_URL.'&search='.$this->page->get->search.'&curpage='; //分页跳转链接
            $this->page->assign('dourl', PAGE_URL); //当前请求页面URL
            $this->page->assign('logList', $logList, true);
            $this->page->assign('paging', $this->page->paging($count, $pagingUrl), true); //总用户数
        }
        $this->page->display();
    }

    /**
     *在线聊天记录
     *
     * @return void
     */
    public function aljol(){
        if($this->page->config->aljol) {
            T::getObject('Log');
            $count = Log::countAljol();
            $logList = Log::aljol($this->page->get->search, $this->page->curPage, 20);
            $pagingUrl = A_URL . '&search=' . $this->page->get->search . '&curpage='; //分页跳转链接
            $this->page->assign('dourl', PAGE_URL); //当前请求页面URL
            $this->page->assign('logList', $logList, true);
            $this->page->assign('paging', $this->page->paging($count, $pagingUrl), true); //总用户数
        }
        $this->page->display();
    }

    /**
     *定期清理在线聊天记录
     *
     * @return void
     */
    public function aljol_setting(){
        if(submitcheck('formhash')){
            if(DB::result_first('select svalue from %t where skey=%s ', array('aljhtx_setting', 'cycle'))){
                DB::update('aljhtx_setting', array(
                    'svalue' => intval($_GET['cycle']),
                ), array('skey' => 'cycle'));
            }else{
                DB::insert('aljhtx_setting', array(
                    'root' => 'admin',
                    'controller' => 'log',
                    'action' => 'aljol_setting',
                    'skey' => 'cycle',
                    'svalue' => intval($_GET['cycle']),
                ));
            }
            $this->page->tips();
        }else{
            $this->page->display();
        }
    }

}
function ubbReplace($str) {
    $str = str_replace ( ">", '<；', $str );
    $str = str_replace ( ">", '>；', $str );
    $str = str_replace ( "\n", '>；br/>；', $str );
    if($_GET['act']){
        $str = formatUrlsInText($str);
    }
    $str = preg_replace ( "[\[em_([0-9]*)\]]", "<img style='width:20px;' src=\"source/plugin/aljol/static/img/face/$1.gif\" / class='Em'>", $str );

    return $str;
}

