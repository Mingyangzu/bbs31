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
<div id="container">

</div>
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
<li class="iStyle" title="搜索" onclick="WeAdminShow('地图搜索','/plugin.php?id=gis_sczl:gismap_map&mod=search',600, 600)">
<i class="my-icon-maptool7 my-maptool-location" value="0"></i>
</li>
</ul>
</div>
</div>
</div>
<script src="<?php echo $_G['gis']['dirstyle'];?>js/left.js" type="text/javascript"></script>
<script src="<?php echo $_G['gis']['dirstyle'];?>js/index.js" type="text/javascript"></script>
<script src="<?php echo $_G['gis']['dirstyle'];?>js/fafang.js" type="text/javascript"></script>
</body>
        <script type="text/javascript">
   $(function(){
    //    可移动弹出框
             var eject_move = $("#moveArea");
             var eject = $(".baiozhu");
             eject_move.mousedown(function(e){
                 var max_x = $(window).width() - 500;            //获取浏览页面的宽度
                 var max_y = $(window).height() -300;
                 var ev = window.event || e;      
                 var old_mouse_x = ev.clientX;                        //获取鼠标开始的横轴位置
                 var old_mouse_y = ev.clientY;                        //获取鼠标开始的纵轴位置
                 var position_l = eject.offset().left;            //弹出框距离的横轴位置
                 var position_t = eject.offset().top;            //弹出框距离的纵轴位置
                 eject_move.bind('mousemove',function(n){
                     var nv = window.event || n;
                     var new_mouse_x = nv.clientX;                    //获取鼠标现在的横轴位置
                     var new_mouse_y = nv.clientY;                    //获取鼠标现在的纵轴位置
                     var new_x = new_mouse_x - old_mouse_x +position_l;    //弹出框现在的横轴位置
                     var new_y = new_mouse_y - old_mouse_y +position_t;    //弹出框现在的纵轴位置
                     //三元表达式 判断有没有超出边界，有的话置于相应的值
                     new_x = (new_x < 0 )?0:new_x;
                     new_y = (new_y < 0 )?0:new_y;
                     new_x = (new_x > max_x)?max_x:new_x;
                     new_y = (new_y > max_y)?max_y:new_y;
                     eject.css({'left':new_x,'top':new_y});
                 });
                 
             });
         //    鼠标抬起
             eject.mouseup(function(){
                 eject_move.unbind('mousemove');
             });        
   })
  </script>

</html>
