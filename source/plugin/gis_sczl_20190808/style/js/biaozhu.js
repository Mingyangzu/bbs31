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

//marker标记
var infoWindow = new AMap.InfoWindow({
	offset: new AMap.Pixel(0, -30)
});
for (var i = 0, marker; i < citys.length; i++) {
	var marker = new AMap.Marker({
		position: citys[i].lnglat,
		title: citys[i].name,
		map: map,
		icon: citys[i].icon
	});
	marker.content = '<div class="infos">' +
		'<div class="name"><a href="' + citys[i].http + '" target="_blank">' + citys[i].name + '</a></div>' +
		'<div class="shg">' +
		'<div class="wenzi">' + citys[i].texts + '</div>' +
		'<div class="kjh"><img src="' + citys[i].img + '" style="width:100%;height:100%;"/></div>' +
		'</div>' +
		'</div>';
	marker.on('click', markerClick);
	marker.emit('click', {
		target: marker
	});
}

function markerClick(e) {
	infoWindow.setContent(e.target.content);
	infoWindow.open(map, e.target.getPosition());
}
map.setFitView();
//轨迹回放 ---线
// var marker, lineArr = [];
// map.on("complete", completeEventHandler);
// 地图图块加载完毕后执行函数
// function completeEventHandler() {
// 	for(var i=0;i<citys.length;i++){
// 		lineArr.push(citys[i].lnglat);
// 	}
// 	// 绘制轨迹
// 	var polyline = new AMap.Polyline({
// 		map: map,
// 		path: lineArr,
// 		strokeColor: "#00A", //线颜色
// 		strokeOpacity: 1, //线透明度
// 		strokeWeight: 3, //线宽
// 		strokeStyle: "solid" //线样式
// 	});
// 	map.setFitView();
// }


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
var icon = '../images/17.png';
var backgrse = '#E6241F';
var mouseTool = new AMap.MouseTool(map);
//监听draw事件可获取画好的覆盖物
var overlays = [];
mouseTool.on('draw', function(e) {
	overlays.push(e.obj);
})

function draw(type) {
	switch (type) {
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
	//marker标记
	var infoWindow = new AMap.InfoWindow({
		offset: new AMap.Pixel(0, -30)
	});
	for (var i = 0, marker; i < citys.length; i++) {
		var marker = new AMap.Marker({
			position: citys[i].lnglat,
			title: citys[i].name,
			map: map,
			icon: citys[i].icon
		});
		marker.content = '<div class="infos">' +
			'<div class="name"><a href="' + citys[i].http + '" target="_blank">' + citys[i].name + '</a></div>' +
			'<div class="shg">' +
			'<div class="wenzi">' + citys[i].texts + '</div>' +
			'<div class="kjh"><img src="' + citys[i].img + '" style="width:100%;height:100%;"/></div>' +
			'</div>' +
			'</div>';
		marker.on('click', markerClick);
		marker.emit('click', {
			target: marker
		});
	}

	function markerClick(e) {
		infoWindow.setContent(e.target.content);
		infoWindow.open(map, e.target.getPosition());
	}
	map.setFitView();
})
//点击 点-线-面
var index = 0;
$(".jiho li").click(function() {
	backgrse = '#E6241F'; //默认画出的颜色
	index = $(this).index();
	if (index == 1) {
		console.log('点击了线')
		draw('polyline')
	}
	if (index == 2) {
		console.log('点击了面')
		draw('polygon')
	}
	if (index == 3) {
		console.log('点击了矩形')
		draw('rectangle')
	}
	if (index == 4) {
		console.log('点击了圆')
		draw('circle')
	}
	if (index == 5) { //清除绘制的图形
		map.remove(overlays)
		overlays = [];
	}
	$(this).addClass("xuanzhong").siblings().removeClass("xuanzhong");
	$("#srxpo .wkkh").eq(index).addClass("baiohg").siblings().removeClass("baiohg");
})
//清除绘制的图形
$("#clear").click(function() {
	map.remove(overlays)
	overlays = [];
})

//基础图标  丝路图标 军事图标  
$(".icon .left li").click(function() {
	var index = $(this).index();
	$(this).addClass("xuzgh").siblings().removeClass("xuzgh");
	$("#contentBox .right").eq(index).addClass("xianshi").siblings().removeClass("xianshi");
})
//标注
$(".my-maptool-label").click(function() {
	//点经纬度采集
	map.on('click', function(e) {
		if (index == 0) {
			$("#lng").val(e.lnglat.lat)
			$("#lat").val(e.lnglat.lng)
			draw('marker')
		}
	});
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
$("#gbzg").click(function() {
	index = 1000;
	mouseTool.close(true) //关闭绘图
	$(".baiozhu").css({
		"display": "none"
	})
	$(".sousuo").css({
		"display": "none"
	})
})
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
	if (xiabiao == 0) {
		backgrse = '#E6241F'
	} else if (xiabiao == 1) {
		backgrse = '#FE9BF1';
	} else if (xiabiao == 2) {
		backgrse = '#7661DF'
	} else if (xiabiao == 3) {
		backgrse = '#32DDE2'
	} else if (xiabiao == 4) {
		backgrse = '#75D91B'
	} else if (xiabiao == 5) {
		backgrse = '#E6DC2F'
	} else if (xiabiao == 6) {
		backgrse = '#CB7C2C'
	} else if (xiabiao == 7) {
		backgrse = '#EA4F1B'
	} else if (xiabiao == 8) {
		backgrse = '#2f5fa5'
	}
	if (index == 1) {
		draw('polyline')
	}
	if (index == 2) {
		draw('polygon')
	}
	if (index == 3) {
		draw('rectangle')
	}
	if (index == 3) {
		draw('rectangle')
	}
	if (index == 4) {
		draw('circle')
	}
	$(this).addClass("yanse")
	$(this).siblings('li').removeClass('yanse')
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
//上传图片
$("#file0").change(function() {
	var objUrl = getObjectURL(this.files[0]);
	console.log("objUrl = " + objUrl);
	if (objUrl) {
		$("#img0").attr("src", objUrl);
		$("#img0").removeClass("hide");
	}
});
//建立一個可存取到該file的url
function getObjectURL(file) {
	var url = null;
	if (window.createObjectURL != undefined) { // basic
		url = window.createObjectURL(file);
	} else if (window.URL != undefined) {
		// mozilla(firefox)
		url = window.URL.createObjectURL(file);
	} else if (window.webkitURL != undefined) {
		// webkit or chrome
		url = window.webkitURL.createObjectURL(file);
	}
	return url;
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
