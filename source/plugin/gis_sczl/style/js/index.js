//初始化地图
var map = new AMap.Map('container', {
	zoom: 4, //级别
	center: [107.40, 33.42], //中心点坐标
	layers: [
		new AMap.TileLayer(),
		// 卫星
		new AMap.TileLayer.Satellite(),
		// 路网
		new AMap.TileLayer.RoadNet()
	],
	resizeEnable: true
});

//地图放大缩放
AMap.plugin([
	'AMap.ToolBar',
], function() {
	// 在图面添加工具条控件，工具条控件集成了缩放、平移、定位等功能按钮在内的组合控件
	map.addControl(new AMap.ToolBar({
		// 简易缩放模式，默认为 false
		liteStyle: true
	}));
});


//右侧编辑按钮-start****************************************************************************************************************************
//返回中心点
$(".my-maptool-china").click(function() {
	var map = new AMap.Map('container', {
		zoom: 4, //级别
		center: [107.40, 33.42], //中心点坐标
		layers: [
			new AMap.TileLayer(),
			// 卫星
			new AMap.TileLayer.Satellite(),
			// 路网
			new AMap.TileLayer.RoadNet()
		],
		resizeEnable: true
	});
})
//搜索
$(".my-maptool-location").click(function() {
	$(".sousuo").css({
		"display": "block"
	})
	$(".baiozhu").css({
		"display": "none"
	})
	$("#container").addClass("wagvc")
})
//编辑点信息
function bianji(id) {
	$(".sousuo").css({
		"display": "none"
	})
	$(".wdfbn").css({
		"display": "block"
	})
}

//编辑提交
$(".tijiao").click(function() {
	$(".wdfbn").css({
		"display": "none"
	})
})
$("#pppnv").click(function() {
	$(".wdfbn").css({
		"display": "none"
	})
})
//右侧编辑按钮-end****************************************************************************************************************************

var icon = '/source/plugin/gis_sczl/style/images/17.png';
var backgrse = '#E6241F';
var overlays = [];
var radius = 0;//圆的半径

var mouseTool = new AMap.MouseTool(map);//鼠标绘制插件
//监听draw事件可获取画好的覆盖物
mouseTool.on('draw', function(e) {
	overlays.push(e.obj);
	//如果是绘制线、面、矩形
	if((index == 1)||(index == 2) ||(index == 3)){
		var pathArr = e.obj.getPath();
		for(var i=0; i<pathArr.length; i++){
			var zuobiao = [];//当前点击的坐标经纬度数组
			zuobiao.push(pathArr[i].lng,pathArr[i].lat);
			lnglat.push(zuobiao);
		}
	}
	//绘制圆
	else if(index == 4){
		var centerPoint = e.obj.getCenter();//圆中心点坐标数组
		radius = e.obj.getRadius();//获取半径
  		lnglat.push(centerPoint.lng,centerPoint.lat);
	}
})


//mouseTool插件鼠标绘制标注
function draw(type) {
	switch(type) {
		case 'marker':
			{
				mouseTool.marker({
					icon: icon
				});
				break;
			}
		case 'polyline':
			{
				mouseTool.polyline({
					strokeColor: backgrse
					//同Polyline的Option设置
				});
				break;
			}
		case 'polygon':
			{
				mouseTool.polygon({
					fillColor: backgrse,
					strokeColor: backgrse
					//同Polygon的Option设置
				});
				break;
			}
		case 'rectangle':
			{
				mouseTool.rectangle({
					fillColor: backgrse,
					strokeColor: backgrse
					//同Polygon的Option设置
				});
				break;
			}
		case 'circle':
			{
				mouseTool.circle({
					fillColor: backgrse,
					strokeColor: backgrse
					//同Circle的Option设置
				});
				break;
			}
	}
}


//************************************************************************************************************************************
//******************************************************标注开始**********************************************************************
//************************************************************************************************************************************
if(!index){
    var index = 100; //index==0表示选择录入点信息，index==1表示选择录入线信息，index==2表示选择录入面信息
}
var lnglat = [];//信息录入时要绘制的点/线/面数组信息
var stopDraw = false;//是否停止绘制。true表示一次绘制结束
var types = 1;//要录入的类型（1点，2线，3面，4矩形，5圆）
var startBiaozhu = false;
//选择标注的类型
$(".jiho li:not(.jiho li:last-child)").click(function() {
	firstClick = true;//重新绘制图形的第一次点击，false表示不是第一次
	map.clearMap(); //清除地图上所有点、线、面
	lnglat = [];//清空在其他选项中选择的标注点
	backgrse = '#E6241F'; //默认画出的颜色
	index = $(this).index();
	types = index+1;
	if(index == 0) {
		draw('marker')
	}
	if(index == 1) {
		draw('polyline')
	}
	if(index == 2) {
		draw('polygon')
	}
	if(index == 3) {
		draw('rectangle')
	}
	if(index == 4) {
		draw('circle')
	}
	$(this).addClass("xuanzhong").siblings().removeClass("xuanzhong");
	$("#srxpo .wkkh").eq(index).addClass("baiohg").siblings().removeClass("baiohg");
})
//选择标注的图标（基础图标  丝路图标 军事图标  ）
$(".icon .left li").click(function() {
	var index = $(this).index();
	$(this).addClass("xuzgh").siblings().removeClass("xuzgh");
	$("#contentBox .right").eq(index).addClass("xianshi").siblings().removeClass("xianshi");
})

//清除绘制的图形
$("#clear, .jiho li:last-child").click(function() {
	map.remove(overlays)
	overlays = [];
	lnglat = [];//清空标注点数组
})

//开始标注
$(".my-maptool-label").click(function() {
	startBiaozhu = true;
	$(".baiozhu").css({
		"display": "block"
	})
	$(".sousuo").css({
		"display": "none"
	})
	$("#container").addClass("wagvc")
	$("#lng").val("")
	$("#lat").val("")
})

//点击确定，根据输入的经纬度绘制点标注
$("#drawPointBtn").click(function(){
	lnglat = [];
	map.clearMap();
	var lng = parseFloat($("#lngs").val());
	var lat = parseFloat($("#lats").val());
	$("#lng").val(lng) //信息录入经度
	$("#lat").val(lat) //信息录入纬度
	lnglat[0] = lng;
	lnglat[1] = lat;
	var marker = new AMap.Marker({
	    icon: icon,
	    position: lnglat
	});
	map.add(marker);
})

//开始绘制
map.on('mousedown', function(e){  
        startBiaozhu = true;
	if(startBiaozhu){
		//如果是点、面或圆，且已经绘制
		if(((index==0) || (index==3) || (index==4)) && lnglat.length>0){
			lnglat = [];
			map.clearMap();
		}
		//线、面逻辑
		if(stopDraw){
			lnglat = [];
			map.clearMap();
			stopDraw = false;
		} 
		$("#lng").val(e.lnglat.lng) //信息录入经度
		$("#lat").val(e.lnglat.lat) //信息录入纬度
		//录入点信息
		if(index == 0) {
			lnglat[0] = e.lnglat.lng;
			lnglat[1] = e.lnglat.lat;
                        $("#lngs").val(e.lnglat.lng) //信息录入经度
                        $("#lats").val(e.lnglat.lat) //信息录入纬度
			draw('marker')
		} 
	}
});

//停止绘制
map.on("rightclick", function() {
	stopDraw = true;
})
map.on("dblclick", function() {
	stopDraw = true;
})

//停止标注----------------------------
$("#gbzg").click(function() {
	startBiaozhu = false;
	mouseTool.close(true) //关闭绘图
	$(".baiozhu").css({
		"display": "none"
	})
	$(".sousuo").css({
		"display": "none"
	})
})
// ----------------------------
$("#soush").click(function() {
	$(".sousuo").css({
		"display": "none"
	})
})
//点标注 选中
$(".icon .right li").click(function() {
	var index = $(this).index();
	icon = $(this).find('img').attr("src");
	$(this).addClass("wsghk")
	$(this).siblings('li').removeClass('wsghk')
})
//线
$(".wxpgv li").click(function() {
	$(this).addClass("xian")
	$(this).siblings('li').removeClass('xian')
})
//面
$(".ssppk li").click(function() {
	$(this).addClass("mian")
	$(this).siblings('li').removeClass('mian')
})
//特殊图形
$(".eesx li").click(function() {
	$(this).addClass("tstx")
	$(this).siblings('li').removeClass('tstx')
})

//颜色
$(".sxutr li").click(function() {
	var xiabiao = $(this).index();
	if(xiabiao == 0) {
		backgrse = '#E6241F'
	} else if(xiabiao == 1) {
		backgrse = '#FE9BF1';
	} else if(xiabiao == 2) {
		backgrse = '#7661DF'
	} else if(xiabiao == 3) {
		backgrse = '#32DDE2'
	} else if(xiabiao == 4) {
		backgrse = '#75D91B'
	} else if(xiabiao == 5) {
		backgrse = '#E6DC2F'
	} else if(xiabiao == 6) {
		backgrse = '#CB7C2C'
	} else if(xiabiao == 7) {
		backgrse = '#EA4F1B'
	} else if(xiabiao == 8) {
		backgrse = '#2f5fa5'
	}
	if(index == 0) {
		draw('marker')
	}
	if(index == 1) {
		draw('polyline')
	}
	if(index == 2) {
		draw('polygon')
	}
	if(index == 3) {
		draw('rectangle')
	}
	if(index == 4) {
		draw('circle')
	}
	$(this).addClass("yanse")
	$(this).siblings('li').removeClass('yanse')
})

//标注结束*************************************************************************************************************************************



//************************************************************************************************************************************************
//***************************************************************标注点展示开始********************************************************************
//************************************************************************************************************************************************
var markers = []; //需要绘制的点的集合
var marker = null; //点标记
var overlayGroups = []; //覆盖物群组集合
var polyline = null; //线标记
var polyGon = null; //面标记
//信息框偏移量
var infoWindow = new AMap.InfoWindow({
	offset: new AMap.Pixel(0, -30)
});
var allMarkers = []; //所有点、线、面的详细信息集合
//定义点、线、面信息框内容
function setInfoContent(item) {
	var infoContent = '';
	if(item.imgs){
		infoContent =
		'<div class="infos">' +
		'<div class="name"><a href="' + item.http + '" target="_blank">' + item.name +
		'</a></div>' +
		'<div class="shg">' +
		'<div class="wenzi">' + item.texts + '</div>' +
		'<div class="kjh"><img src="' + item.imgs + '" style="width:100%;height:100%;"/></div>' +
		'</div>' +
		'</div>';
	}else{
		infoContent =
		'<div class="infos">' +
		'<div class="name"><a href="' + item.http + '" target="_blank">' + item.name +
		'</a></div>' +
		'<div>' +
		'<div style="font-size=14px">' + item.texts + '</div>' +
		'<div class="kjh"></div>' +
		'</div>' +
		'</div>';
	}
	return infoContent;
}
//显示点信息框
function markerClick(e) {
	infoWindow.setContent(e.target.content);
	infoWindow.open(map, e.target.getPosition());
}
//显示线/面信息框
function lineOrFaceClick(e) {
	//点击的位置
	var infoAddress = [];
	infoAddress.push(e.lnglat.getLng(), e.lnglat.getLat());
	infoWindow.setContent(e.target.content);
	infoWindow.open(map, infoAddress); //打开信息框
}
//根据左侧目录树是否选中，显示对应的点、线、面
function showAllPoint(allMarkers) {
	overlayGroups = []; //清空覆盖物群组集合
	markers = []; //清空点的集合
	polyline = null; //清空线标记
	polyGon = null; //清空面标记
	for(var i = 0; i < allMarkers.length; i++) {
		var point = eval(allMarkers[i].lnglat);
		var radius = parseFloat(allMarkers[i].radius);//圆半径
		//如果是点标记
		if(allMarkers[i].types == 1) {
			//创建点实例
			marker = new AMap.Marker({
				position: new AMap.LngLat(point[0], point[1]),
				title: allMarkers[i].name,
				map: map,
				icon: allMarkers[i].icon
			});
			marker.content = setInfoContent(allMarkers[i]);
			marker.on('click', markerClick);
			marker.emit('click', {
				target: marker
			});
			markers.push(marker);
			// 创建覆盖物群组，并将 marker 传给 OverlayGroup
			overlayGroups = new AMap.OverlayGroup(markers);
			// 添加覆盖物群组
			map.add(overlayGroups);
		}

		//如果是线标记
		
		else if(allMarkers[i].types == 2){
			//创建线实例
			polyline = new AMap.Polyline({
				path: point,
				strokeColor: allMarkers[i].color || "red",
				strokeOpacity: 1,
				strokeWeight: 2,
				strokeStyle: 'solid',
				strokeDasharray: [10, 5],
				geodesic: true
			});
			polyline.setMap(map);
			polyline.content = setInfoContent(allMarkers[i]);
			polyline.on('click', lineOrFaceClick);
		}

		//如果是面/矩形标记
		else if(allMarkers[i].types == 3 || (allMarkers[i].types == 4)){
			//创建面实例
			polyGon = new AMap.Polygon({
				path: point,
				map: map,
				strokeColor: allMarkers[i].color || "red",
				strokeOpacity: 1,
				strokeWeight: 2,
				strokeStyle: 'solid',
				strokeDasharray: [10, 5],
				fillColor: allMarkers[i].color || "red",
				fillOpacity: 0.2,
				geodesic: true
			})
			polyGon.content = setInfoContent(allMarkers[i]);
			polyGon.on('click', lineOrFaceClick);
		}
		//如果是圆形标记
		else if(allMarkers[i].types == 5){
			//创建圆实例
			polyGon = new AMap.Circle({
				center: point,
				radius: radius,
				map: map,
				strokeColor: allMarkers[i].color || "red",
				strokeOpacity: 1,
				strokeWeight: 2,
				strokeStyle: 'solid',
				strokeDasharray: [10, 5],
				fillColor: allMarkers[i].color || "red",
				fillOpacity: 0.2,
				geodesic: true
			})
			polyGon.content = setInfoContent(allMarkers[i]);
			polyGon.on('click', lineOrFaceClick);
		}
		map.setFitView();
	}
}


//根据左侧勾选的内容，对应展示标注点
$.ajax({ //渲染左侧
	type: "GET",
	url: "/plugin.php?id=gis_sczl:gisapi&mod=getreslist",
	dataType: "json",
	success: function(res) {
		if(res.code == 0) {
			var lists = res.data;
			layui.use(['tree', 'util', 'form'], function() {
				var tree = layui.tree,
					layer = layui.layer,
					util = layui.util,
					form = layui.form,
					data = lists;
				//点击复选框获取选中的数据
				tree.render({
					elem: '#test12',
					data: lists,
					showCheckbox: true, //是否显示复选框
					id: 'demoId1',
					oncheck: function(obj) {
                                            var checkres = [];
                                            var resarr = tree.getChecked('demoId1');
                                            for(var i in resarr){
                                                if(!resarr[i].children || resarr[i].children.length == 0){
                                                    checkres.push(parseInt(resarr[i].id));     //一级
                                                }else{ 
                                                    var twolevel = resarr[i].children;
                                                    for(var k in twolevel){
                                                        if(!twolevel[k].children || twolevel[k].children.length == 0){
                                                            checkres.push(parseInt(twolevel[k].id));   //二级
                                                        }else{
                                                            var threeleve = twolevel[k].children;
                                                            for(var j in threeleve){
                                                              checkres.push(parseInt(threeleve[j].id));   //三级 
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                            console.log(checkres);
                                            if(checkres.length > 0){
						$.ajax({ //获取点击节点的数据
							type: "POST",
							url: "/plugin.php?id=gis_sczl:gisapi",
							dataType: "json",
							data: {
								"checkres": checkres,
								"mod": "getresgis"
							},
							success: function(res) {
                                                                var allMarkers = [];
								var citys = res.data; //当前选中复选框的对应标记集合
								//当前点击的复选框被选中
//								if(obj.checked) {
									for(var i=0; i<citys.length; i++){
										allMarkers.push(citys[i]);
									}
                                                                        map.clearMap(); //清除地图上所有点、线、面
									showAllPoint(allMarkers);
//								} else {//当前点击的复选框取消选中
//									infoWindow.close();
//									for(var i=0; i<citys.length; i++){
//										for(var j=0; j<allMarkers.length; j++){
//											//取消选中的节点对应的标注点==已展示的标注点
//											if(citys[i].id == allMarkers[j].id){
//												allMarkers.splice(j,1);
//											}
//										}
//									}
//									map.clearMap(); //清除地图上所有点、线、面
//									showAllPoint(allMarkers); //重新绘制左侧目录树选中的数据集合
//								}
                                                                layui.sessionData('allMarkers', {key: 'marker', value: allMarkers});
							}
						});
                                            }else{
                                                map.clearMap(); //清除地图上所有点、线、面
                                            }
                                                
					}
				})
			})
		}
	}
});
//***************************************************************标注点展示结束********************************************************************

//*****************************************************************************************************************************************
//*****************************************************************************************************************************************
//渲染三级联动
layui.use(['tree', 'table', 'util', 'form', 'upload'], function () {
    var tree = layui.tree,
            layer = layui.layer,
            util = layui.util,
            form = layui.form,
            upload = layui.upload,
            table = layui.table,
            $ = layui.jquery;


    $('#addreslist').click(function () {
        if (lnglat.length <= 0 || !types) {
            layer.msg("添加失败!", {icon: 5});
            return false;
        }
        if (types == 1 && !icon) {
            layer.msg("获取标注点图标失败!", {icon: 5});
            return false;
        }

        if (types == 5 && !radius) {
            layer.msg("获取圆半径失败!", {icon: 5});
            return false;
        }

        var lists = {};
        lists.lnglat = lnglat;
        lists.types = types;
        lists.icon = types == 1 ? icon : '';
        lists.backgrse = types > 1 ? backgrse : '';
        lists.radius = types == 5 ? radius : 0;

        addresToArticle(lists);
        lnglat = [];
    });

    function addresToArticle(texts) {
        if (!texts || texts.lnglat.length < 1) {
            layer.msg('未获取到标注信息!');
            return false;
        }
        var ucode = layui.sessionData('gisucode');
        ucode = ucode.code;
        if (!ucode || ucode.length < 20) {
            layer.msg('gisucode码有误!');
            return false;
        }
        texts.lnglat = JSON.stringify(texts.lnglat);
//            texts = BASE64.encode(texts);
        var argis = 0;
        var index = layer.load(1);
        $.ajax({
            url: '/plugin.php?id=gis_sczl:gisapi',
            type: 'post',
            dataType: 'json',
            async: true,
            data: {"texts": texts, "gisucode": ucode, "mod": "addrestoarticle"},
            success: function (res) {
                if (res.data) {
                    var reslist = layui.sessionData(ucode);
                    reslist = reslist.marker;
                    argis = res.data;
                    texts.id = res.data;
                    reslist.push(texts);
                    layui.sessionData(ucode, {key: 'marker', value: reslist});
                    table.reload('reslistid', {data: reslist});
                } else {
                    layer.msg(res.msg);
                    layer.close(index);
                    return false;
                }
            },
            error: function (res) {
                console.log(res);
            }
        });
        layer.close(index);
        if (argis > 0) {
            return true;
        } else {
            return false;
        }
    }
  
});

