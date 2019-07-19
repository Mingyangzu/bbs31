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
<li class="dtcx_Resourcecatalog active">资源目录</li>
                                                        <?php if($umsg ) { ?>
<li class="dtcx_displayControl">信息录入</li>
                                                        <?php } ?>
</ul>
</div>
<div class="leftOverflow">
<div class="tab_left">
<!-- 资源目录 -->
<div id="test12" class="demo-tree-more"></div>
</div>
                                                <?php if($umsg ) { ?>
<div class="xxlr" style="display: none;">
<form class="layui-form" action="" style="min-height:400px;margin-left:40px;">
<div class="layui-form-item">
<div class="layui-input-inline">
<input type="text" name="name" lay-verify="required" autocomplete="off" placeholder="请输入名称" class="layui-input">
</div>
</div>
<!--<div class="layui-form-item">
<div class="layui-input-inline">
<select name="types">
<option value="">请选择类型</option>
<option value="1">点</option>
<option value="2">线</option>
<option value="3">面</option>
</select>
</div>
</div>-->
<div class="layui-form-item">
<div class="layui-input-inline" style="margin-bottom:10px;">
<select name="resid" lay-filter="resid">
<option value="">请选择一级目录</option>
</select>
</div>
<div class="layui-input-inline" style="margin-bottom:10px;">
<select name="resid2" lay-filter="resid2">

</select>
</div>
<div class="layui-input-inline" style="margin-bottom:10px;">
<select name="resid3" lay-filter="resid3">

</select>
</div>
<div class="layui-form-item">
<div class="layui-input-inline">
<input type="text" name="lngs" id="lng" lay-verify="required" autocomplete="off" placeholder="请在地图上拾取经度"
 class="layui-input">
</div>
</div>
<div class="layui-form-item">
<div class="layui-input-inline">
<input type="text" name="lats" id="lat" lay-verify="required" autocomplete="off" placeholder="请在地图上拾取维度"
 class="layui-input">
</div>
</div>
<div class="layui-form-item layui-form-text">
<div class="layui-input-inline">
<textarea name="texts" lay-verify="required" autocomplete="off" placeholder="请输入点标记的内容" class="layui-textarea"></textarea>
</div>
</div>
<div class="layui-form-item">
<button type="button" class="layui-btn layui-btn-danger" id="test7"><i class="layui-icon"></i>上传图片</button>
</div>
<div class="layui-form-item">
<div style="width: 216px; margin: 0;">
<button type="button" class="layui-btn layui-btn-fluid" lay-submit="" lay-filter="demo1" id="test9">立即提交</button>
</div>
</div>
</div>
</form>
</div>
                                                <?php } ?>
</section>
</div>
</div>
<div id="app">
<div class="biao">
<ul class="my-avg-sm-1 my-dropdown-content">
<li class="iStyle" title="中心点">
<i class="my-icon-maptool1 my-maptool-china" value="0"></i>
</li>
<li class="iStyle" title="标注">
<i class="my-icon-maptool4 my-maptool-label" value="0"></i>
</li>
<li class="iStyle" title="搜索" onclick="WeAdminShow('地图搜索','/plugin.php?id=gis_sczl:gismap_map&mod=search',600, 600)">
<i class="my-icon-maptool7 my-maptool-location" value="0"></i>
</li>
<li class="iStyle" title="保存">
<i class="my-icon-maptool9 my-maptool-print" value="0"></i>
</li>
<li class="iStyle" title="清除" id="clear">
<i class="my-icon-maptool10 my-maptool-reset" value="0"></i>
</li>
</ul>
</div>
<div class="baiozhu" style="display: none;">
<div class="title" id="moveArea">
<div style="display: flex;flex-direction: row;">
<div style="width:20px;height:20px;margin:0 5px;"><img src="<?php echo $_G['gis']['dirstyle'];?>images/map.png" class="imgs" /></div>
<div>地图标注</div>
</div>
<div style="width:20px;height:20px;margin:-24px 10px 0;" id="gbzg">
<img src="<?php echo $_G['gis']['dirstyle'];?>images/delete.png" class="imgs" />
</div>
</div>
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
<div class="jwd">
<div><span>经度：</span><input type="text" id="lngs" /></div>
<div><span>纬度：</span><input type="text" id="lats" /></div>
<button id="drawPointBtn">确定</button>
</div>
<div class="icon">
<div class="left">
<ul>
<li class="xuzgh"><span></span>基础图标</li>
<li><span></span>丝路图标</li>
<li><span></span>军事图标</li>
</ul>
</div>
<div id="contentBox" style="width:70%;">
<div class="right xianshi">
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
<div class="right">
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
<div class="right">
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
</div>
<div class="wkkh">
<div class="sytf">
<div>
<div class="vvcx">线条颜色：</div>
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
<div class="vvcx">填充颜色：</div>
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
<div class="vvcx">填充颜色：</div>
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
<div class="vvcx">填充颜色：</div>
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
</div>
<!-- 编辑点信息 -->
<div class="wdfbn">
<div style="width:25px;height:25px;margin-left:95%;cursor: pointer;" id="pppnv">
<img src="<?php echo $_G['gis']['dirstyle'];?>images/deletewp.png" class="imgs" />
</div>
                            <?php if($umsg ) { ?>
<form class="layui-form" action="" style="height:700px;margin-left:40px;">
<div class="layui-form-item">
<div class="layui-input-inline">
<input type="text" name="title" lay-verify="title" autocomplete="off" placeholder="请输入名称" class="layui-input">
</div>
</div>
<div class="layui-form-item">
<div class="layui-input-inline">
<select name="quiz1">
<option value="">请选择类型</option>
<option value="点">点</option>
<option value="线">线</option>
<option value="面">面</option>
</select>
</div>
</div>
<div class="layui-form-item">
<div class="layui-input-inline" style="margin-bottom:10px;">
<select name="quiz2">
<option value="">请选择一级目录</option>
</select>
</div>
<div class="layui-input-inline" style="margin-bottom:10px;">
<select name="quiz3">
<option value="">请选择二级目录</option>
</select>
</div>
<div class="layui-input-inline" style="margin-bottom:10px;">
<select name="quiz4">
<option value="">请选择三级目录</option>
</select>
</div>
<div class="layui-form-item">
<div class="layui-input-inline">
<input type="text" name="lngs" lay-verify="title" autocomplete="off" placeholder="请在地图上拾取经度" class="layui-input">
</div>
</div>
<div class="layui-form-item">
<div class="layui-input-inline">
<input type="text" name="lats" lay-verify="title" autocomplete="off" placeholder="请在地图上拾取纬度" class="layui-input">
</div>
</div>
<div class="layui-form-item layui-form-text">
<div class="layui-input-inline">
<textarea placeholder="请输入点标记的内容" class="layui-textarea"></textarea>
</div>
</div>
<div class="layui-form-item">
<div class="layui-upload">
<button type="button" class="layui-btn layui-btn-normal" id="test10">选择文件</button>
</div>
</div>
<div class="layui-form-item">
<div style="width: 216px; margin: 0;">
<button type="button" class="layui-btn layui-btn-fluid" lay-submit="" lay-filter="demo1" id="test11">立即提交</button>
</div>
</div>
</div>
</form>
                            <?php } ?>
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
