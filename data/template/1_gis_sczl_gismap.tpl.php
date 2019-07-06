<?php if(!defined('IN_DISCUZ')) exit('Access Denied'); ?>
<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="ie=edge">
<title>Document</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
<link rel="stylesheet" href="<?php echo $_G['gis']['dirstyle'];?>css/1.css">
</head>

<body>
<!-- 定位logo -->
<div class="sl_logo">
<a href="#" class="logo">

</a>
<div class="down_text">
<a href="#">English</a>
<a href="#">中文</a>
<a href="#">PYCCKNN</a>
</div>
</div>
<div id="my_top">
<!-- 全屏 -->
<div class="section sl_one">
<div class="sl_map">
<!-- 解决页面抖动 -->
<div class="sl_map_box">
<div class="gd_map">
<iframe name="gd_map_iframe" id="gd_map_iframe" src="/plugin.php?id=gis_sczl:gismap_map&amp;mod=index" frameborder="0" align="left" width="100%" height="100%" scrolling="no"></iframe>
</div>
</div>
</div>
<div class="sl_down">
<div class="full_down" id="full_down"></div>
</div>
</div>
</div>
<div class="section sl_last" id="sl_last">
<div class="bg_color">
<div class="dv_box" id="wk1">
<div class="beijing">
<a href="http://www.silkroads.org.cn/CN/">
<div class="ttu"><img src="<?php echo $_G['gis']['dirstyle'];?>images/a1.png" style="width:100%;height:100%;" /></div>
<div class="rightw">
<div class="wenzi">遗产点频道</div>
<div class="jiao"><img src="<?php echo $_G['gis']['dirstyle'];?>images/a6.png" style="width:100%;height:100%;" /></div>
</div>	
</a>
</div>
</div>
<div class="dv_box" id="wk2">
<div class="beijing">
<a href="http://lib.silkroads.org.cn/">
<div class="ttu"><img src="<?php echo $_G['gis']['dirstyle'];?>images/a2.png" style="width:100%;height:100%;" /></div>
<div class="rightw">
<div class="wenzi">文献馆</div>
<div class="jiao"><img src="<?php echo $_G['gis']['dirstyle'];?>images/a6.png" style="width:100%;height:100%;" /></div>
</div>	
</a>
</div>
</div>
<div class="dv_box" id="wk3">
<div class="beijing">
<a href="http://www.silkroadscloud.org/">
<div class="ttu"><img src="<?php echo $_G['gis']['dirstyle'];?>images/a3.png" style="width:100%;height:100%;"/></div>
<div class="rightw">
<div class="wenzi">天山廊道的路网</div>
<div class="jiao"><img src="<?php echo $_G['gis']['dirstyle'];?>images/a6.png" style="width:100%;height:100%;"/></div>
</div>	
</a>
</div>
</div>
<div class="dv_box" id="wk4">
<div class="beijing">
<a href="http://www.silkroads.org.cn/forum.php?mod=forumdisplay&amp;fid=42">
<div class="ttu"><img src="<?php echo $_G['gis']['dirstyle'];?>images/a4.png" style="width:100%;height:100%;" /></div>
<div class="rightw">
<div class="wenzi">协调管理</div>
<div class="jiao"><img src="<?php echo $_G['gis']['dirstyle'];?>images/a6.png" style="width:100%;height:100%;" /></div>
</div>	
</a>
</div>
</div>
<div class="dv_box" id="wk5">
<div class="beijing">
<a href="http://www.iicc.org.cn/">
<div class="ttu"><img src="<?php echo $_G['gis']['dirstyle'];?>images/a5.png" style="width:100%;height:100%;" /></div>
<div class="rightw">
<div class="wenzi">关于我们</div>
<div class="jiao"><img src="<?php echo $_G['gis']['dirstyle'];?>images/a6.png" style="width:100%;height:100%;" /></div>
</div>	
</a>
</div>
</div>
</div>
<div class="sl_down">
<div class="full_down" id="full_top"></div>
</div>
</div>
<script src="https://code.jquery.com/jquery-3.1.1.min.js" type="text/javascript"></script>
<script src='<?php echo $_G['gis']['dirstyle'];?>js/1.js'></script>

<script>
$(function() {
// 获取当前页面的高度
var height = $(window).height()
$('.section').height(height)
// 点击下箭头滚动
$('#full_down').on('click', function() {
$("#my_top").slideUp("slow");
$("#sl_last").slideDown("slow");
$("#wk1").addClass("current");
$("#wk2").addClass("current1");
$("#wk3").addClass("current2");
$("#wk4").addClass("current3");
$("#wk5").addClass("current4");
$("#wk1").removeClass("dv_box");
$("#wk2").removeClass("dv_box");
$("#wk3").removeClass("dv_box");
$("#wk4").removeClass("dv_box");
$("#wk5").removeClass("dv_box");
})
$('#full_top').on('click', function() {
$("#my_top").slideDown("slow");
$("#sl_last").slideUp("slow");
$("#wk1").removeClass("current");
$("#wk2").removeClass("current1");
$("#wk3").removeClass("current2");
$("#wk4").removeClass("current3");
$("#wk5").removeClass("current4");
$("#wk1").addClass("dv_box");
$("#wk2").addClass("dv_box");
$("#wk3").addClass("dv_box");
$("#wk4").addClass("dv_box");
$("#wk5").addClass("dv_box");
})
//悬停换背景
var child = document.getElementsByClassName("dv_box");
var last = document.getElementById("sl_last");
for(var i = 0; i < child.length; i++) {
var a = child[i];
a.index = i; //给每个className为child的元素添加index属性;
if(a.index == 0) {
a.style.background = "url(<?php echo $_G['gis']['dirstyle'];?>images/1.png)";
} else if(a.index == 1) {
a.style.background = "url(<?php echo $_G['gis']['dirstyle'];?>images/2.png)";
} else if(a.index == 2) {
a.style.background = "url(<?php echo $_G['gis']['dirstyle'];?>images/3.png)";
} else if(a.index == 3) {
a.style.background = "url(<?php echo $_G['gis']['dirstyle'];?>images/4.png)";
} else if(a.index == 4) {
a.style.background = "url(<?php echo $_G['gis']['dirstyle'];?>images/5.png)";
}
a.onmouseout = function() {
last.style.background = "url(<?php echo $_G['gis']['dirstyle'];?>images/last.png)"
}
}
//添加点击下标跳转
var child = document.getElementsByClassName("bg_text");
for(var i = 0; i < child.length; i++) {
var a = child[i];
a.index = i; //给每个className为child的元素添加index属性;
a.onclick = function() {
var index = this.index;
if(index == 0) {
location.href = "http://www.silkroads.org.cn/CN/"
} else if(index == 1) {
location.href = "http://lib.silkroads.org.cn/"
} else if(index == 2) {
location.href = "http://www.silkroadscloud.org/"
} else if(index == 3) {
location.href = "http://www.silkroads.org.cn/forum.php?mod=forumdisplay&amp;fid=42"
} else if(index == 4) {
location.href = "http://www.iicc.org.cn/"
}
}
}
// 筛选不同类型标注    
$("#select_PointType").change(function() {
window.frames["gd_map_iframe"].window.removeMark();
if($("#select_PointType option:selected").val() == "0") {
window.frames["gd_map_iframe"].window.setMarkAll();
} else if($("#select_PointType option:selected").val() == "1") {
window.frames["gd_map_iframe"].window.setMarkShi();
} else if($("#select_PointType option:selected").val() == "2") {
window.frames["gd_map_iframe"].window.setMarkSilu();
} else if($("#select_PointType option:selected").val() == "3") {
window.frames["gd_map_iframe"].window.setMarkYu();
}
})
//滚动动画
windowAddMouseWheel();

function windowAddMouseWheel() {
var scrollFunc = function(e) {
e = e || window.event;
if(e.wheelDelta) { //判断浏览器IE，谷歌滑轮事件
if(e.wheelDelta > 0) { //当滑轮向上滚动时
$("#my_top").slideDown("slow");
$("#sl_last").slideUp("slow");
$("#wk1").removeClass("current");
$("#wk2").removeClass("current1");
$("#wk3").removeClass("current2");
$("#wk4").removeClass("current3");
$("#wk5").removeClass("current4");
$("#wk1").addClass("dv_box");
$("#wk2").addClass("dv_box");
$("#wk3").addClass("dv_box");
$("#wk4").addClass("dv_box");
$("#wk5").addClass("dv_box");
}
if(e.wheelDelta < 0) { //当滑轮向下滚动时
$("#my_top").slideUp("slow");
$("#sl_last").slideDown("slow");
$("#wk1").addClass("current");
$("#wk2").addClass("current1");
$("#wk3").addClass("current2");
$("#wk4").addClass("current3");
$("#wk5").addClass("current4");
$("#wk1").removeClass("dv_box");
$("#wk2").removeClass("dv_box");
$("#wk3").removeClass("dv_box");
$("#wk4").removeClass("dv_box");
$("#wk5").removeClass("dv_box");
}
} else if(e.detail) { //Firefox滑轮事件
if(e.detail < 0) { //当滑轮向上滚动时
$("#my_top").slideDown("slow");
$("#sl_last").slideUp("slow");
}
if(e.detail > 0) { //当滑轮向下滚动时
$("#my_top").slideUp("slow");
$("#sl_last").slideDown("slow");
}
}
};
//给页面绑定滑轮滚动事件
if(document.addEventListener) {
document.addEventListener('DOMMouseScroll', scrollFunc, false);
}
//滚动滑轮触发scrollFunc方法
window.onmousewheel = document.onmousewheel = scrollFunc;
}

});
</script>

</body>

</html>