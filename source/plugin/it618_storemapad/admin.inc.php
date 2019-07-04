<?php
/**
 *	开发团队：IT618
 *	it618_copyright 插件设计：<a href="http://www.cnit618.com" target="_blank" title="专业Discuz!应用及周边提供商">IT618</a>
 */
(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) && exit('Access Denied');

global $_G;

loadcache('plugin');
$it618_storemapad = $_G['cache']['plugin']['it618_storemapad'];

loadcache('pluginlanguage_template');
loadcache('pluginlanguage_script');
$scriptlang['it618_storemapad'] = $_G['cache']['pluginlanguage_script']['it618_storemapad'];

$it618_lang = $scriptlang['it618_storemapad'];

for($i=1;$i<=42;$i++){
	$it618_lang_admin[$i]=$it618_lang['it618_admin'.$i];
}

require_once DISCUZ_ROOT.'./source/plugin/it618_storemapad/function/it618_storemapad.func.php';

$ppp = $it618_storemapad['pagecount'];
$page = max(1, intval($_GET['page']));
$startlimit = ($page - 1) * $ppp;
$deletes = '';
$extrasql = '';

$hosturl=ADMINSCRIPT."?action=";
$identifier = $_GET['identifier'];
$urls = '&pmod=admin&identifier='.$identifier.'&operation='.$operation.'&do='.$do;

$cparray = array('admin_class1', 'admin_class2','admin_mapadsum');
$cp = !in_array($_GET['cp'], $cparray) ? 'admin_class1' : $_GET['cp'];
define(TOOLS_ROOT, dirname(__FILE__).'/');

for($i=0;$i<count($cparray);$i++){
	if($cp==$cparray[$i]){
		$strtmp[]='class="current"';
	}else{
		$strtmp[]='';
	}
}

echo '<div class="itemtitle" style="width:100%;margin-bottom:5px;margin-top:3px"><ul class="tab1" id="submenu">
<li '.$strtmp[0].'><a href="'.$hosturl.'plugins&cp=admin_class1'.$urls.'"><span>'.$it618_lang_admin[1].'</span></a></li>
<li '.$strtmp[1].'><a href="'.$hosturl.'plugins&cp=admin_class2'.$urls.'"><span>'.$it618_lang_admin[2].'</span></a></li>
<li '.$strtmp[2].'><a href="'.$hosturl.'plugins&cp=admin_mapadsum'.$urls.'"><span>'.$it618_lang_admin[3].'</span></a></li>
</ul></div>';

require TOOLS_ROOT.'./include/'.$cp.'.inc.php';
showformfooter();
?>