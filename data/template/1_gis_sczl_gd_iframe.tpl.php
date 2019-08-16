<?php if(!defined('IN_DISCUZ')) exit('Access Denied'); ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="initial-scale=1.0, user-scalable=no, width=device-width">
        <title>地图</title>
        <link rel="stylesheet" href="<?php echo $_G['gis']['dirstyle'];?>css/index.css" />
        <link rel="stylesheet" href="<?php echo $_G['gis']['dirstyle'];?>layui/css/layui.css" />
        <!-- <script src="<?php echo $_G['gis']['dirstyle'];?>js/city.js" type="text/javascript"></script> -->
        <script src="<?php echo $_G['gis']['dirstyle'];?>js/jquery-1.4.4.min.js" type="text/javascript"></script>
        <script src="<?php echo $_G['gis']['dirstyle'];?>layui/layui.js" type="text/javascript"></script>
        <script src="https://webapi.amap.com/maps?v=1.4.14&key=b6d11a2c1cbd93f3ef41a0d02e9fe553&plugin=AMap.MouseTool" type="text/javascript"></script>
    </head>

    <body>
        <div id="container"></div>
        <div class="fixed">
            <div class="zTree">
                <div class="contract_btn" style="display: block;">
                    <img src="<?php echo $_G['gis']['dirstyle'];?>images/right.png" alt="收缩按钮" style="position: relative;left:5px;top:30px;" />
                </div>
                <section>
                    <div class="title_bg_img title_bg_img_nav">
                        <ul class="leftHeadTab">
                            <li class="dtcx_Resourcecatalog">文章栏目</li>
                        </ul>
                    </div>
                    <div class="leftOverflow">
                        <div class="tab_left">
                            <!-- 资源目录 -->
                            <div id="test12" class="demo-tree-more"></div>
                        </div>
                </section>
            </div>
        </div>
        <div id="app">
            <div class="biao">
                <ul class="my-avg-sm-1 my-dropdown-content">
                    <li class="iStyle" title="中心点">
                        <i class="my-icon-maptool1 my-maptool-china" value="0"></i>
                    </li>
                    <li class="iStyle" id="mapsearch" title="搜索">
                        <i class="my-icon-maptool7 my-maptool-location" value="0"></i>
                    </li>
                </ul>
            </div>
        </div>
        <script src="<?php echo $_G['gis']['dirstyle'];?>js/left.js" type="text/javascript"></script>
        <script src="<?php echo $_G['gis']['dirstyle'];?>js/fafang.js" type="text/javascript"></script>
    </body>
    <script type="text/javascript">
        var nowpage = 'gdiframe';
    </script>
    <script src="<?php echo $_G['gis']['dirstyle'];?>js/index.js" type="text/javascript"></script>
</html>
