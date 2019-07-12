<?php if(!defined('IN_DISCUZ')) exit('Access Denied'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="initial-scale=1.0, user-scalable=no, width=device-width">
<title>地图 文章</title>
<link rel="stylesheet" href="<?php echo $_G['gis']['dirstyle'];?>css/index.css" />
<link rel="stylesheet" href="<?php echo $_G['gis']['dirstyle'];?>css/zTreeStyle/zTreeStyle.css" />
<link rel="stylesheet" href="https://a.amap.com/jsapi_demos/static/demo-center/css/demo-center.css" type="text/css">
<script src="<?php echo $_G['gis']['dirstyle'];?>js/city.js" type="text/javascript"></script>
<script src="<?php echo $_G['gis']['dirstyle'];?>js/jquery-1.4.4.min.js" type="text/javascript"></script>
<script src="<?php echo $_G['gis']['dirstyle'];?>js/jquery.ztree.core.js" type="text/javascript"></script>
<script src="<?php echo $_G['gis']['dirstyle'];?>js/jquery.ztree.excheck.js" type="text/javascript"></script>
<script src="<?php echo $_G['gis']['dirstyle'];?>js/jquery.ztree.exhide.js" type="text/javascript"></script>
<script src="https://webapi.amap.com/maps?v=1.4.14&key=b6d11a2c1cbd93f3ef41a0d02e9fe553&plugin=AMap.MouseTool" type="text/javascript"></script>
              
</head>
<body>
<div id="container" style="top:5px;"></div>
                <?php if(!empty($defaultgis)) { ?>
<script> citys = JSON.parse(<?php echo $defaultgis;?>) ;</script>
                <?php } ?>
<script src="<?php echo $_G['gis']['dirstyle'];?>js/biaozhu.js" type="text/javascript"></script>
</body>
      
</html>
