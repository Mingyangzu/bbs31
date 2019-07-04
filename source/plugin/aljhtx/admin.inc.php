<?php

/**
 * 商家助手后台入口页
 *
 * @author yuxinqi<yuxinqi@vip.qq.com>
 * @version 1.0
 * @link http://docs.liangjianyun.com/
 */

if (!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
    exit('Access Denied');
}

echo "<script language='javascript'>";
echo "parent.location.href='plugin.php?id=aljhtx'";
echo "</script>";
exit;
?>