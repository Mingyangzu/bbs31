<?php
define('DISABLEXSSCHECK', true);

require '../../../../source/class/class_core.php';

$discuz = C::app();

$cachelist = array('plugin');

$discuz->cachelist = $cachelist;
$discuz->init();
loadcache('plugin');
$_G['siteurl'] = str_replace('source/plugin/aljhtx/api', '',$_G['siteurl'] );
include_once DISCUZ_ROOT.'./source/plugin/aljhtx/class/qrcode.class.php';
$url = $_G['siteurl'].$_GET['url'];
echo QRcode::png($url, false, 4, 4, 0);
exit;
?>