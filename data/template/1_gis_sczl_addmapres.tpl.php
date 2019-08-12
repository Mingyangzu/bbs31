<?php if(!defined('IN_DISCUZ')) exit('Access Denied'); ?>
<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="initial-scale=1.0, user-scalable=no, width=device-width">
<title>文章添加地图标注信息</title>
<link rel="stylesheet" href="<?php echo $_G['gis']['dirstyle'];?>css/inputs.css" />
<link rel="stylesheet" href="<?php echo $_G['gis']['dirstyle'];?>layui/css/layui.css" />
<!-- <script src="<?php echo $_G['gis']['dirstyle'];?>js/city.js" type="text/javascript"></script> -->
<script src="<?php echo $_G['gis']['dirstyle'];?>js/jquery-1.4.4.min.js" type="text/javascript"></script>
<script src="<?php echo $_G['gis']['dirstyle'];?>layui/layui.js" type="text/javascript"></script>
<script src="https://webapi.amap.com/maps?v=1.4.14&key=b6d11a2c1cbd93f3ef41a0d02e9fe553&plugin=AMap.MouseTool" type="text/javascript"></script>
</head>

<body>
<div id="container" class="wagvc" style="top: <?php echo $mapboxtop;?>px; z-index: 199;"></div>
<div class="fixed" style="top: <?php echo $mapboxtop;?>px; z-index: 199;">
<div class="zTree">
<div class="contract_btn" style="display: block;">
<img src="<?php echo $_G['gis']['dirstyle'];?>images/right.png" alt="收缩按钮" style="position: relative;left:5px;top:30px;" />
</div>
<section>
<div class="title_bg_img title_bg_img_nav">
<ul class="leftHeadTab">
<li class="dtcx_Resourcecatalog active">地图标注</li>
</ul>
</div>
<div class="leftOverflow">
<div class="tab_left">
<!-- 地图标注 -->
                                                        <div id="" >
                                                            <div class="baiozhu" >

                                                <ul class="jiho">
                                                        <li class="xuanzhong">
                                                                <span></span>
                                                                <div>点</div>
                                                        </li>
                                                        <li>
                                                                <span></span>
                                                                <div>线</div>
                                                        </li>
                                                        <li>
                                                                <span></span>
                                                                <div>面<div>
                                                        </li>
                                                        <li>
                                                                <span></span>
                                                                <div>矩形</div>
                                                        </li>
                                                        <li>
                                                                <span></span>
                                                                <div>圆</div>
                                                        </li>
                                                        <li>
                                                                <span></span>
                                                        </li>
                                                </ul>
                                                <div id="srxpo">
                                                        <div class="wkkh baiohg">
                                                                <div class="icon">
                                                                        <div class="left">
                                                                                <ul>
                                                                                        <li class="xuzgh"><span></span></li>
                                                                                        <li><span></span></li>
                                                                                        <li><span></span></li>
                                                                                </ul>
                                                                        </div>
                                                                        <div id="contentBox" style="width: 85%;">
                                                                                <div class="right xianshi" style="float: none;">
                                                                                        <div style="margin:10px 0;color:#002147;">基础图标</div>
                                                                                        <ul>
                                                                                                <li class="wsghk">
                                                                                                        <div class="ttu"><img src="<?php echo $_G['gis']['dirstyle'];?>images/17.png" class="imgs" /></div>
                                                                                                        <div class="radios"></div>
                                                                                                </li>
                                                                                                <li>
                                                                                                        <div class="ttu"><img src="<?php echo $_G['gis']['dirstyle'];?>images/18.png" class="imgs" /></div>
                                                                                                        <div class="radios"></div>
                                                                                                </li>
                                                                                                <li>
                                                                                                        <div class="ttu"><img src="<?php echo $_G['gis']['dirstyle'];?>images/19.png" class="imgs" /></div>
                                                                                                        <div class="radios"></div>
                                                                                                </li>
                                                                                                <li>
                                                                                                        <div class="ttu"><img src="<?php echo $_G['gis']['dirstyle'];?>images/20.png" class="imgs" /></div>
                                                                                                        <div class="radios"></div>
                                                                                                </li>
                                                                                                <li>
                                                                                                        <div class="ttu"><img src="<?php echo $_G['gis']['dirstyle'];?>images/21.png" class="imgs" /></div>
                                                                                                        <div class="radios"></div>
                                                                                                </li>
                                                                                                <li>
                                                                                                        <div class="ttu"><img src="<?php echo $_G['gis']['dirstyle'];?>images/22.png" class="imgs" /></div>
                                                                                                        <div class="radios"></div>
                                                                                                </li>
                                                                                        </ul>
                                                                                </div>
                                                                                <div class="right"  style="float: none;">
                                                                                        <div style="margin:10px 0;color:#002147;">丝路图标</div>
                                                                                        <ul>
                                                                                                <li>
                                                                                                        <div class="ttu"><img src="<?php echo $_G['gis']['dirstyle'];?>images/14.png" class="imgs" /></div>
                                                                                                        <div class="radios"></div>
                                                                                                </li>
                                                                                                <li>
                                                                                                        <div class="ttu"><img src="<?php echo $_G['gis']['dirstyle'];?>images/15.png" class="imgs" /></div>
                                                                                                        <div class="radios"></div>
                                                                                                </li>
                                                                                                <li>
                                                                                                        <div class="ttu"><img src="<?php echo $_G['gis']['dirstyle'];?>images/16.png" class="imgs" /></div>
                                                                                                        <div class="radios"></div>
                                                                                                </li>
                                                                                                <li>
                                                                                                        <div class="ttu"><img src="<?php echo $_G['gis']['dirstyle'];?>images/35.png" class="imgs" /></div>
                                                                                                        <div class="radios"></div>
                                                                                                </li>
                                                                                                <li>
                                                                                                        <div class="ttu"><img src="<?php echo $_G['gis']['dirstyle'];?>images/36.png" class="imgs" /></div>
                                                                                                        <div class="radios"></div>
                                                                                                </li>
                                                                                                <li>
                                                                                                        <div class="ttu"><img src="<?php echo $_G['gis']['dirstyle'];?>images/37.png" class="imgs" /></div>
                                                                                                        <div class="radios"></div>
                                                                                                </li>
                                                                                                <li>
                                                                                                        <div class="ttu"><img src="<?php echo $_G['gis']['dirstyle'];?>images/38.png" class="imgs" /></div>
                                                                                                        <div class="radios"></div>
                                                                                                </li>
                                                                                        </ul>
                                                                                </div>
                                                                                <div class="right"  style="float: none;">
                                                                                        <div style="margin:10px 0;color:#002147;">军事图标</div>
                                                                                        <ul>
                                                                                                <li>
                                                                                                        <div class="ttu"><img src="<?php echo $_G['gis']['dirstyle'];?>images/42.png" class="imgs" /></div>
                                                                                                        <div class="radios"></div>
                                                                                                </li>
                                                                                                <li>
                                                                                                        <div class="ttu"><img src="<?php echo $_G['gis']['dirstyle'];?>images/43.png" class="imgs" /></div>
                                                                                                        <div class="radios"></div>
                                                                                                </li>
                                                                                        </ul>
                                                                                </div>
                                                                        </div>
                                                                </div>
                                                                <div class="jwd">
                                                                        <div><span>经度：</span><input type="text" id="lats" /></div>
                                                                        <div><span>维度：</span><input type="text" id="lngs" /></div>
                                                                        <!--<button>定位</button>-->
                                                                </div>
                                                        </div>
                                                        <div class="wkkh">
                                                                <div class="sytf">
                                                                        <div>
                                                                                <div class="vvcx">颜色</div>
                                                                                <div>
                                                                                        <ul class="sxutr">
                                                                                                <li class="xxes1 yanse"></li>
                                                                                                <li class="xxes2"></li>
                                                                                                <li class="xxes3"></li>
                                                                                                <li class="xxes4"></li>
                                                                                                <li class="xxes5"></li>
                                                                                                <li class="xxes6"></li>
                                                                                                <li class="xxes7"></li>
                                                                                                <li class="xxes8"></li>
                                                                                                <li class="xxes9"></li>
                                                                                                <li class="xxes10"></li>
                                                                                        </ul>
                                                                                </div>
                                                                        </div>
                                                                </div>
                                                        </div>
                                                        <div class="wkkh">
                                                                <div class="sytf">
                                                                        <div>
                                                                                <div class="vvcx">颜色</div>
                                                                                <div>
                                                                                        <ul class="sxutr">
                                                                                                <li class="xxes1 yanse"></li>
                                                                                                <li class="xxes2"></li>
                                                                                                <li class="xxes3"></li>
                                                                                                <li class="xxes4"></li>
                                                                                                <li class="xxes5"></li>
                                                                                                <li class="xxes6"></li>
                                                                                                <li class="xxes7"></li>
                                                                                                <li class="xxes8"></li>
                                                                                                <li class="xxes9"></li>
                                                                                                <li class="xxes10"></li>
                                                                                        </ul>
                                                                                </div>
                                                                        </div>
                                                                </div>
                                                        </div>
                                                        <div class="wkkh">
                                                                <div class="sytf">
                                                                        <div>
                                                                                <div class="vvcx">颜色</div>
                                                                                <div>
                                                                                        <ul class="sxutr">
                                                                                                <li class="xxes1 yanse"></li>
                                                                                                <li class="xxes2"></li>
                                                                                                <li class="xxes3"></li>
                                                                                                <li class="xxes4"></li>
                                                                                                <li class="xxes5"></li>
                                                                                                <li class="xxes6"></li>
                                                                                                <li class="xxes7"></li>
                                                                                                <li class="xxes8"></li>
                                                                                                <li class="xxes9"></li>
                                                                                                <li class="xxes10"></li>
                                                                                        </ul>
                                                                                </div>
                                                                        </div>
                                                                </div>
                                                        </div>
                                                        <div class="wkkh">
                                                                <div class="sytf" style="margin-top:10px;">
                                                                        <div>
                                                                                <div class="vvcx">颜色</div>
                                                                                <div>
                                                                                        <ul class="sxutr">
                                                                                                <li class="xxes1 yanse"></li>
                                                                                                <li class="xxes2"></li>
                                                                                                <li class="xxes3"></li>
                                                                                                <li class="xxes4"></li>
                                                                                                <li class="xxes5"></li>
                                                                                                <li class="xxes6"></li>
                                                                                                <li class="xxes7"></li>
                                                                                                <li class="xxes8"></li>
                                                                                                <li class="xxes9"></li>
                                                                                                <li class="xxes10"></li>
                                                                                        </ul>
                                                                                </div>
                                                                        </div>
                                                                </div>
                                                        </div>

                                                </div>

                                                <div class="layui-layer-btn layui-layer-btn-c">
                                                    <a class="layui-layer-btn0" id="addreslist">添加标注</a>
                                                </div> 
                                                                
                                                <table class="layui-hide" id="addrestable"  lay-filter="resList"></table>
</div>
                                                                <!-- 编辑点信息 -->
                                                                <div class="wdfbn"></div>
                                                        </div>
                                                            
                                                </div>
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
<script src="<?php echo $_G['gis']['dirstyle'];?>js/left.js" type="text/javascript"></script>
<script src="<?php echo $_G['gis']['dirstyle'];?>js/index.js" type="text/javascript"></script>
<script src="<?php echo $_G['gis']['dirstyle'];?>js/fafang.js" type="text/javascript"></script>
</body>
<script type="text/html" id="barDemo">
    <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
</script>
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
