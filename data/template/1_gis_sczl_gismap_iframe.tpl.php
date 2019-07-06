<?php if(!defined('IN_DISCUZ')) exit('Access Denied'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="initial-scale=1.0, user-scalable=no, width=device-width">
<title>地图</title>
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
<div id="container" style="top: <?php echo $mapboxtop;?>px; z-index: 199;">

</div>
<div class="fixed" style="top: <?php echo $mapboxtop;?>px; z-index: 199;">
<div class="zTree">
<div class="contract_btn" style="display: block;">
<img src="<?php echo $_G['gis']['dirstyle'];?>images/right.png" alt="收缩按钮" style="position: relative;left:5px;top:30px;" />
</div>
<section>
<div class="title_bg_img title_bg_img_nav">
<ul class="leftHeadTab">
<li class="dtcx_Resourcecatalog active">资源目录</li>
                                                        <?php if($umsg === true  ) { ?>
<li class="dtcx_displayControl">信息录入</li>
                                                        <?php } ?>
</ul>
</div>
<div class="leftOverflow">
<div class="tab_left">
<!-- 资源目录 -->
<div class="changingOver ztree_tab">
<ul id="treeDemo" class="ztree"></ul>
</div>
</div>
<div class="xxlr" style="display: none;">
                                                    <?php if($umsg === true  ) { ?>
                                                    <form method="post" autocomplete="off" id="gisform" action="" enctype="multipart/form-data" onsubmit="return checkGisInput();">
<div>
<div style="margin-right:10px;">名称:</div>
<div>
<input type="text" name='title' id='gistitle' placeholder="请输入名称" maxlength='126'/>
</div>
</div>
<div>
<div style="margin-right:10px;">类型:</div>
<div style="width:40%;">
<select name='types' id='gistypes'>
<option value='1'>点</option>
<option value='2'>线</option>
<option value='3'>面</option>
</select>
</div>
</div>
<div>
<div style="margin-right:10px;">经度:</div>
<div>
<input type="text" name='lngs' id="lngs" placeholder="请在地图上拾取经度"/>
</div>
</div>
<div>
<div style="margin-right:10px;">维度:</div>
<div>
<input type="text" name='lats' id="lats" placeholder="请在地图上拾取维度"/>
</div>
</div>
<div>
<div style="margin-right:10px;">内容:</div>
<div>
                                                                    <textarea name="contents" id='giscontents' rows="5" cols="23" placeholder="请输入点标记的内容" maxlength='1020' ></textarea>
</div>
</div>
<div><input type="file" name="file0" id="file0" multiple="multiple" class="shangchuan" /></div>
<button type='submit' name='submit' value='gisinput' id='gisinput' >确定</button>
                                                        </form>
                                                    <?php } ?>
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
<li class="iStyle" title="标注">
<i class="my-icon-maptool4 my-maptool-label" value="0"></i>
</li>
<li class="iStyle" title="搜索">
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
<div class="title">
<div style="display: flex;flex-direction: row;">
<div style="width:20px;height:20px;margin:5px 5px 0;"><img src="<?php echo $_G['gis']['dirstyle'];?>images/map.png" class="imgs" /></div>
<div>地图标注</div>
</div>
<div style="width:25px;height:25px;margin:0 10px;" id="gbzg">
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
<div><span>经度：<?php echo $testvar;?></span><input type="text" id="lat" /></div>
<div><span>维度：</span><input type="text" id="lng" /></div>
<button>确定</button>
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
<!-- <div>
<div class="vvcx">绘制类型：</div>
<div>
<ul class="wxpgv">
<li class="zexy1 xian"></li>
<li class="zexy2"></li>
<li class="zexy3"></li>
<li class="zexy4"></li>
</ul>
</div>
</div> -->
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
<!-- <div>
<div class="vvcx">绘制类型：</div>
<div>
<ul class="ssppk">
<li class="wwsa1 mian"></li>
<li class="wwsa2"></li>
<li class="wwsa3"></li>
<li class="wwsa4"></li>
<li class="wwsa5"></li>
<li class="wwsa6"></li>
<li class="wwsa7"></li>
<li class="wwsa8"></li>
</ul>
</div>
</div> -->
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
<!-- <div>
<div class="vvcx">绘制类型：</div>
<div>
<ul class="eesx">
<li class="aaz1 tstx"></li>
<li class="aaz2"></li>
<li class="aaz3"></li>
<li class="aaz4"></li>
<li class="aaz5"></li>
<li class="aaz6"></li>
<li class="aaz7"></li>
</ul>
</div>
</div> -->
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
<!-- <div class="jwd">
<div><span>经度：</span><input type="text" id="lat2" /></div>
<div><span>维度：</span><input type="text" id="lng2" /></div>
<button>确定</button>
</div> -->
<div class="sytf" style="margin-top:10px;">
<div>
<div class="vvcx">颜色：</div>
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
<!-- <div class="wzbzs">
<div class="vvcx">文字：</div>
<div>
<input type="text" value="文字标注"/>
</div>
</div> -->
</div>
</div>
</div>
</div>
<div class="sousuo" style="display: none;">
<div class="title">
<div style="display: flex;flex-direction: row;">
<div style="width:20px;height:20px;margin:5px 5px 0;"><img src="<?php echo $_G['gis']['dirstyle'];?>images/zuobiao.png" class="imgs" /></div>
<div>地图搜索</div>
</div>
<div style="width:25px;height:25px;margin:0 10px;" id="soush">
<img src="<?php echo $_G['gis']['dirstyle'];?>images/delete.png" class="imgs" />
</div>
</div>
<div class="sser">
<div class="sskh">
<select class="selecs">
<option value="dian">点</option>
<option value="xian">线</option>
<option value="mian">面</option>
</select>
<input placeholder="请输入要搜索的名称" class="cheng" />
<button>搜索</button>
</div>
<div class="liste">
<table border="1" cellspacing="0" style="width:100%;text-align:center;">
<tr>
<th>序号</th>
<th>名称</th>
<th>类型</th>
<th style="width:200px;">操作</th>
</tr>
<tr style="height:50px;">
<td>1</td>
<td>大雁塔</td>
<td>点</td>
<td>
<button style="background:#2f5fa5;color:#fff;line-height:30px;width:70px;border:0px;border-radius:3px;" onclick="bianji(1)">编辑</button>
<button style="background: red;color:#fff;line-height:30px;width:70px;border:0px;border-radius:3px;">删除</button>
</td>
</tr>
<tr style="height:50px;">
<td>1</td>
<td>大雁塔</td>
<td>点</td>
<td>
<button style="background:#2f5fa5;color:#fff;line-height:30px;width:70px;border:0px;border-radius:3px;">编辑</button>
<button style="background: red;color:#fff;line-height:30px;width:70px;border:0px;border-radius:3px;">删除</button>
</td>
</tr>
<tr style="height:50px;">
<td>1</td>
<td>大雁塔</td>
<td>点</td>
<td>
<button style="background:#2f5fa5;color:#fff;line-height:30px;width:70px;border:0px;border-radius:3px;">编辑</button>
<button style="background: red;color:#fff;line-height:30px;width:70px;border:0px;border-radius:3px;">删除</button>
</td>
</tr>
<tr style="height:50px;">
<td>1</td>
<td>大雁塔</td>
<td>点</td>
<td>
<button style="background:#2f5fa5;color:#fff;line-height:30px;width:70px;border:0px;border-radius:3px;">编辑</button>
<button style="background: red;color:#fff;line-height:30px;width:70px;border:0px;border-radius:3px;">删除</button>
</td>
</tr>
<tr style="height:50px;">
<td>1</td>
<td>大雁塔</td>
<td>点</td>
<td>
<button style="background:#2f5fa5;color:#fff;line-height:30px;width:70px;border:0px;border-radius:3px;">编辑</button>
<button style="background: red;color:#fff;line-height:30px;width:70px;border:0px;border-radius:3px;">删除</button>
</td>
</tr>
<tr style="height:50px;">
<td>1</td>
<td>大雁塔</td>
<td>点</td>
<td>
<button style="background:#2f5fa5;color:#fff;line-height:30px;width:70px;border:0px;border-radius:3px;">编辑</button>
<button style="background: red;color:#fff;line-height:30px;width:70px;border:0px;border-radius:3px;">删除</button>
</td>
</tr>
<tr style="height:50px;">
<td>1</td>
<td>大雁塔</td>
<td>点</td>
<td>
<button style="background:#2f5fa5;color:#fff;line-height:30px;width:70px;border:0px;border-radius:3px;">编辑</button>
<button style="background: red;color:#fff;line-height:30px;width:70px;border:0px;border-radius:3px;">删除</button>
</td>
</tr>
<tr style="height:50px;">
<td>1</td>
<td>大雁塔</td>
<td>点</td>
<td>
<button style="background:#2f5fa5;color:#fff;line-height:30px;width:70px;border:0px;border-radius:3px;">编辑</button>
<button style="background: red;color:#fff;line-height:30px;width:70px;border:0px;border-radius:3px;">删除</button>
</td>
</tr>
</table>
</div>
</div>
</div>
<!-- 编辑点信息 -->
<div class="wdfbn">
<div style="width:25px;height:25px;margin-left:95%;cursor: pointer;" id="pppnv">
<img src="<?php echo $_G['gis']['dirstyle'];?>images/deletewp.png" class="imgs" />
</div>
<div>
<span>名称：</span>
<div><input type="text" placeholder="大雁塔" /></div>
</div>
<div>
<span>经度：</span>
<div><input type="text" placeholder="116.258446" /></div>
</div>
<div>
<span>纬度：</span>
<div><input type="text" placeholder="37.686622" /></div>
</div>
<div>
<span>内容：</span>
<div>
<textarea rows="5" cols="23" placeholder="请输入点标记的内容">

</textarea>
</div>
</div>
<div><input type="file" name="file0" id="file0" multiple="multiple" class="shangchuan" /></div>
<div id="ihgfm">
<img src="" id="img0"  class="hide" style="width:100%;height:100%;">
</div>
<div><button class="tijiao" onclick="console.log('提交');">提交</button></div>
</div>
</div>
            
<script src="<?php echo $_G['gis']['dirstyle'];?>js/left.js" type="text/javascript"></script>
<script src="<?php echo $_G['gis']['dirstyle'];?>js/biaozhu.js" type="text/javascript"></script>
</body>
        <script>
            function checkGisInput(){
                if($('#gistitle').val() == ''){
                    alert('请填写标题');
                    return false;
                }
                
                if($('#gistypes').val() == ''){
                    alert('请选择类型');
                    return false;
                }
                
                if($('#lngs').val() == '' || $('#lats').val() == ''){
                    alert('请填写经纬度');
                    return false;
                }
//                $('#gisinput').submit();
                return  true;
            };
        </script>
</html>