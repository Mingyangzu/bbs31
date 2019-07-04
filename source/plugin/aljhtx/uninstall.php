<?php
/*
	Install Uninstall Upgrade AutoStat System Code
*/
if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}
//start to put your own code
$request_url = str_replace('&step='.$_GET['step'], '', $_SERVER['QUERY_STRING']);
$_G['lang']['admincp']['ok'] = '&#20445;&#30041;&#26412;&#25554;&#20214;&#25968;&#25454;';
$_G['lang']['admincp']['cancel'] = '&#21024;&#38500;&#26412;&#25554;&#20214;&#25968;&#25454;';
switch($_GET['step']) {
    case 'deletesql':
		$sql = <<<EOF
		DROP TABLE IF  EXISTS `pre_aljhtx_type`;
        DROP TABLE IF  EXISTS `pre_aljhtx_cron`;
        DROP TABLE IF  EXISTS `pre_aljhtx_setting`;
        DROP TABLE IF  EXISTS `pre_aljhtx_diy`;
        DROP TABLE IF  EXISTS `pre_aljhtx_qrcode_common`;
        DROP TABLE IF  EXISTS `pre_aljhtx_qrcode_wechat`;
        DROP TABLE IF  EXISTS `pre_aljhtx_qrcode_miniprogram`;
EOF;
        runquery($sql);
        $finish = TRUE;
        break;

    case 'ok':
        $finish = TRUE;
        break;

    default:
        $checkplugin = C::t('common_plugin')->fetch($pluginid);
        if(empty($checkplugin)) {
            C::t('common_plugin')->insert($plugin);
        }
        cpmsg('<img src="static/image/common/info.gif" /><br/><br/><b>&#35831;&#36873;&#25321;&#21368;&#36733;&#21518;&#26159;&#21542;&#20445;&#30041;&#26412;&#25554;&#20214;&#25968;&#25454;&#65311;</b><br/><br/>&#22914;&#26524;&#24744;&#24076;&#26395;&#20445;&#30041;&#26412;&#25554;&#20214;&#25968;&#25454;&#35831;&#28857;&#20987;&#12300;&#20445;&#30041;&#26412;&#25554;&#20214;&#25968;&#25454;&#12301;&#65292;&#22914;&#26524;&#28857;&#20987;&#12300;&#21024;&#38500;&#26412;&#25554;&#20214;&#25968;&#25454;&#12301;&#21017;&#20250;&#21024;&#38500;&#26412;&#25554;&#20214;&#30340;&#25152;&#26377;&#25968;&#25454;&#34920;&#25968;&#25454;<br/><br/><b>&#27880;&#24847;&#65306;&#36873;&#25321;&#12300;&#21024;&#38500;&#12301;&#21518;&#22914;&#26524;&#20877;&#37325;&#26032;&#23433;&#35013;&#26412;&#25554;&#20214;&#25152;&#26377;&#25968;&#25454;&#37117;&#20250;&#27704;&#20037;&#20002;&#22833;&#19988;&#19981;&#21487;&#24674;&#22797;</b><br/><br/>', "{$request_url}&step=ok", 'form', array(), '', TRUE, ADMINSCRIPT."?{$request_url}&step=deletesql");
        break;
}
?>
