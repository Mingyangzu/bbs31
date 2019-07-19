<?php if(!defined('IN_DISCUZ')) exit('Access Denied'); ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="initial-scale=1.0, user-scalable=no, width=device-width">
        <title>地图 文章</title>
        <link rel="stylesheet" href="<?php echo $_G['gis']['dirstyle'];?>css/index.css" />
        <link rel="stylesheet" href="<?php echo $_G['gis']['dirstyle'];?>layui/css/layui.css" />
        <!-- <script src="<?php echo $_G['gis']['dirstyle'];?>js/city.js" type="text/javascript"></script> -->
        <script src="<?php echo $_G['gis']['dirstyle'];?>js/jquery-1.4.4.min.js" type="text/javascript"></script>
        <script src="<?php echo $_G['gis']['dirstyle'];?>layui/layui.js" type="text/javascript"></script>
        <script src="https://webapi.amap.com/maps?v=1.4.14&key=b6d11a2c1cbd93f3ef41a0d02e9fe553&plugin=AMap.MouseTool" type="text/javascript"></script>

    </head>
    <body>
        <div id="container" style="top:5px;"></div>

        <script src="<?php echo $_G['gis']['dirstyle'];?>js/left.js" type="text/javascript"></script>
        <script src="<?php echo $_G['gis']['dirstyle'];?>js/index.js" type="text/javascript"></script>
        <script src="<?php echo $_G['gis']['dirstyle'];?>js/fafang.js" type="text/javascript"></script>
        <?php if(!empty($defaultgis)) { ?>
        <script>
            var citys = JSON.parse(<?php echo $defaultgis;?>);
            var centers = JSON.parse(citys[0].lnglat);
            var map = new AMap.Map('container', {
                zoom: 4, //级别
                center: [centers[0], centers[1]], //中心点坐标
                layers: [
                    new AMap.TileLayer(),
                    // 卫星
                    new AMap.TileLayer.Satellite(),
                    // 路网
                    new AMap.TileLayer.RoadNet()
                ],
                resizeEnable: true
            });
            showAllPoint(citys);

        </script>
        <?php } ?>
    </body>

</html>
