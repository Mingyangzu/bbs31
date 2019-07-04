<?php

// 新应用中心地址：https://addon.dismall.com/

error_reporting(0);
header('content-type:text/html;charset=utf-8');
$copy = true; // true：开启备份文件后缀.bak  false：关闭备份
$files = array('./source/function/function_cloudaddons.php', './source/language/lang_admincp_msg.php', './source/language/lang_admincp.php');

foreach ($files as $f) {
	$code = readFileCode($f);
	if ($copy && !is_file($f . '.bak')) {
		writeFileCode($f . '.bak', $code);
	}

	if (!empty($code)) {
    $find = array(
      '/https?:\/\/open\.discuz\.net/si',
      '/https?:\/\/addon\.discuz\.com/si',
      '/https?:\/\/addon1\.discuz\.com/si',
      '/discuz\.com/si',
      '/dismall\.net/si',
      "/'download_ip' => '[^']+'/si",
      "/'check_ip' => '[^']+'/si",
      "/define\('CLOUDADDONS_DOWNLOAD_IP', '[^']+'\);/",
      "/define\('CLOUDADDONS_CHECK_IP', '[^']+'\);/",
      "/'check_url' => '[^']+'/",
      '/\&mysiteid\=\'\.\$_G\[\'setting\'\]\[\'my_siteid\'\];/si',
    );
    $replace = array(
      'https://open.dismall.com',
      'https://addon.dismall.com',
      'https://addon1.dismall.com',
      'dismall.com',
      'dismall.com',
      "'download_ip' => ''",
      "'check_ip' => ''",
      "define('CLOUDADDONS_DOWNLOAD_IP', '');",
      "define('CLOUDADDONS_CHECK_IP', '');",
      "'check_url' => 'https://addon1.dismall.com/md5/'",
      "&mysiteid='.\$_G['setting']['my_siteid'].'&addonversion=1';",
    );
    
		$code = preg_replace($find, $replace, $code);
		if (!empty($code)) {
			writeFileCode($f, $code);
		}
	} else {
	  echo $code;
		echo '文件：<font color="red">' . $f . ' 不存在！</font><br/>';
	}
}

$code = readFileCode('source/function/function_cloudaddons.php');
if(preg_match("#dismall\.com#i", $code)){
  //@unlink('./replacedismall.php');
  exit('新应用中心地址已经替换完成，可进入后台安装想要的应用了！替换完成后，此文件可以删除！如有问题，到应用中心官方网站反馈：<a href="https://www.dismall.com/" target="_blank">https://www.dismall.com/</a>');
}else{
  exit('自动替换新应用中心失败，请尝试其他方法：<a href="https://www.dismall.com/thread-957-1-1.html" target="_blank">https://www.dismall.com/thread-957-1-1.html</a>');
}

function readFileCode($f) {
	if (file_exists($f) === false) {
		return '';
	}

	$fp = fopen($f, 'r');
	$code = '';
	while (!feof($fp)) {
		$code .= fgets($fp, 4096);
	}
	fclose($fp);
	
	return $code;
}
function writeFileCode($f, $code) {
	$fp = fopen($f, 'w');
	fwrite($fp, $code);
	fclose($fp);
}

?>