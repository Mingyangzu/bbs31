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
//加载地图上的默认点
actives()

function actives() {
    $.ajax({
        type: "POST",
        url: "http://silu.topmy.cn/plugin.php?id=gis_sczl:gisapi",
        dataType: "json",
        data: {
            "mod": "getdefaultgis"
        },
        success: function (res) {
            console.log(res.data)
            var citys = res.data;
            //marker标记
            var infoWindow = new AMap.InfoWindow({
                offset: new AMap.Pixel(0, -30)
            });
            for (var i = 0, marker; i < citys.length; i++) {
                var marker = new AMap.Marker({
                    position: JSON.parse(citys[i].lnglat),
                    title: citys[i].name,
                    map: map,
                    icon: '/source/plugin/gis_sczl/style/images/' + citys[i].icon
                });
                marker.content = '<div class="infos">' +
                        '<div class="name"><a href="' + citys[i].http + '" target="_blank">' + citys[i].name + '</a></div>' +
                        '<div class="shg">' +
                        '<div class="wenzi">' + citys[i].texts + '</div>' +
                        '<div class="kjh"><img src="http://silu.topmy.cn/' + citys[i].imgs + '" style="width:100%;height:100%;"/></div>' +
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
        },
    })
}

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
], function () {
    // 在图面添加工具条控件，工具条控件集成了缩放、平移、定位等功能按钮在内的组合控件
    map.addControl(new AMap.ToolBar({
        // 简易缩放模式，默认为 false
        liteStyle: true
    }));
});
var icon = '/source/plugin/gis_sczl/style/images/17.png';
var backgrse = '#E6241F';
var mouseTool = new AMap.MouseTool(map);
//监听draw事件可获取画好的覆盖物
var overlays = [];
mouseTool.on('draw', function (e) {
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
$(".my-maptool-china").click(function () {
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
    actives() //加载地图默认点
})
//点击 点-线-面
var index = 0;
$(".jiho li").click(function () {
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
$("#clear").click(function () {
    map.remove(overlays)
    overlays = [];
})

//基础图标  丝路图标 军事图标  
$(".icon .left li").click(function () {
    var index = $(this).index();
    $(this).addClass("xuzgh").siblings().removeClass("xuzgh");
    $("#contentBox .right").eq(index).addClass("xianshi").siblings().removeClass("xianshi");
})

//------------------------
var lnglat = [];
//-------------
//标注
$(".my-maptool-label").click(function () {
    //点经纬度采集
    map.on('click', function (e) {
        if (index == 0) {
            lnglat = [];
            //			$("#lngs").val(e.lnglat.lat) //标注精度
            //			$("#lats").val(e.lnglat.lng) //标注维度
            $("#lng").val(e.lnglat.lat) //信息录入精度
            $("#lat").val(e.lnglat.lng) //信息录入维度
            lnglat.push(e.lnglat.lng);
            lnglat.push(e.lnglat.lat);
            console.log(lnglat)
            draw('marker')
        } else if (index == 1) {
            var ppsm = [];
            $("#lng").val(e.lnglat.lat) //信息录入精度
            $("#lat").val(e.lnglat.lng) //信息录入维度
            ppsm.push(e.lnglat.lng);
            ppsm.push(e.lnglat.lat);
            lnglat.push(ppsm)
            console.log(lnglat)
        } else if (index == 2) {
            var ppsm = [];
            $("#lng").val(e.lnglat.lat) //信息录入精度
            $("#lat").val(e.lnglat.lng) //信息录入维度
            ppsm.push(e.lnglat.lng);
            ppsm.push(e.lnglat.lat);
            lnglat.push(ppsm)
            console.log(lnglat)
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
// ----------------------------
$("#gbzg").click(function () {
    index = 1000;
    mouseTool.close(true) //关闭绘图
    $(".baiozhu").css({
        "display": "none"
    })
    $(".sousuo").css({
        "display": "none"
    })
})
// ----------------------------
$("#soush").click(function () {
    $(".sousuo").css({
        "display": "none"
    })
})
//点标注 选中
$(".icon .right li").click(function () {
    var index = $(this).index();
    icon = $(this).find('img').attr("src");
    $(this).addClass("wsghk")
    $(this).siblings('li').removeClass('wsghk')
})
//线
$(".wxpgv li").click(function () {
    $(this).addClass("xian")
    $(this).siblings('li').removeClass('xian')
})
//面
$(".ssppk li").click(function () {
    $(this).addClass("mian")
    $(this).siblings('li').removeClass('mian')
})
//特殊图形
$(".eesx li").click(function () {
    $(this).addClass("tstx")
    $(this).siblings('li').removeClass('tstx')
})

//颜色
$(".sxutr li").click(function () {
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
$(".my-maptool-location").click(function () {
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
$(".tijiao").click(function () {
    $(".wdfbn").css({
        "display": "none"
    })
})
$("#pppnv").click(function () {
    $(".wdfbn").css({
        "display": "none"
    })
})

// ************************************
$.ajax({//渲染左侧
    type: "GET",
    url: "http://silu.topmy.cn/plugin.php?id=gis_sczl:gisapi&mod=getreslist",
    dataType: "json",
    success: function (res) {
        if (res.code == 0) {
            var lists = res.data;
            layui.use(['tree', 'util', 'form'], function () {
                var tree = layui.tree,
                        layer = layui.layer,
                        util = layui.util,
                        form = layui.form,
                        data = lists;
                console.log(lists)
                //点击复选框获取选中的数据
                tree.render({
                    elem: '#test12',
                    data: lists,
                    showCheckbox: true, //是否显示复选框
                    id: 'demoId1',
                    oncheck: function (obj) {
                        var resid2 = []; //二级目录ID
                        var resid3 = []; //三级目录id
                        if (obj.data.level == 2) {
                            resid2.push(obj.data.id);
                        } else if (obj.data.level == 3) {
                            resid3.push(obj.data.id)
                        }
                        console.log(obj.data.level); //得到当前点击的层级
                        console.log(obj.data.id); //得到当前节点的id
                        console.log(obj.checked); //当前节点的状态  
                        if (obj.checked == true) {
                            $.ajax({//获取点击节点的数据
                                type: "POST",
                                url: "http://silu.topmy.cn/plugin.php?id=gis_sczl:gisapi",
                                dataType: "json",
                                data: {
                                    "resid2": resid2,
                                    "resid3": resid3,
                                    "mod": "getresgis"
                                },
                                success: function (res) {
                                    console.log(res.data)
                                    var citys = res.data;
                                    //marker标记
                                    var infoWindow = new AMap.InfoWindow({
                                        offset: new AMap.Pixel(0, -30)
                                    });
                                    for (var i = 0, marker; i < citys.length; i++) {
                                        var marker = new AMap.Marker({
                                            position: JSON.parse(citys[i].lnglat),
                                            title: citys[i].name,
                                            map: map,
                                            icon: 'style/images/' + citys[i].icon
                                        });
                                        marker.content = '<div class="infos">' +
                                                '<div class="name"><a href="' + citys[i].http + '" target="_blank">' + citys[i].name +
                                                '</a></div>' +
                                                '<div class="shg">' +
                                                '<div class="wenzi">' + citys[i].texts + '</div>' +
                                                '<div class="kjh"><img src="http://silu.topmy.cn/' + citys[i].imgs +
                                                '" style="width:100%;height:100%;"/></div>' +
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
                                },
                            })
                        } else {
                            actives()
                        }
                    }
                });
            })
        }
    }
});


//渲染三级联动
layui.use(['tree', 'util', 'form', 'upload'], function () {
    var tree = layui.tree,
            layer = layui.layer,
            util = layui.util,
            form = layui.form,
            upload = layui.upload;

    $.ajax({
        type: "GET",
        url: "http://silu.topmy.cn/plugin.php?id=gis_sczl:gisapi&mod=getreslist",
        contentType: "application/x-www-form-urlencoded",
        dataType: "json",
        success: function (res) {
            if (res.code == 0) {
                var lists = res.data;
                var htmls = '';
                for (var i = 0; i < lists.length; i++) {
                    htmls += '<option value="' + lists[i].id + '">' + lists[i].title + '</option>'
                }
                $("select[name=resid]").append(htmls);
                form.render();
                form.on('select(resid)', function (proData) {
                    var value = proData.value; //一级id
                    $.ajax({//获取二级目录
                        type: "POST",
                        url: "http://silu.topmy.cn/plugin.php?id=gis_sczl:gisapi",
                        contentType: "application/x-www-form-urlencoded",
                        dataType: "json",
                        data: {
                            "resid": value,
                            "mod": "getres"
                        },
                        success: function (res) {
                            console.log(res.data)
                            if (res.code == 0) {
                                var erji = res.data;
                                var wwpp = '';
                                for (var i = 0; i < erji.length; i++) {
                                    wwpp += '<option value="' + erji[i].id + '">' + erji[i].name + '</option>'
                                }
                                $("select[name=resid2]").append(wwpp);
                                form.render();
                                form.on('select(resid2)', function (proData) {
                                    var id = proData.value; //二级id
                                    $.ajax({//获取三级目录
                                        type: "POST",
                                        url: "http://silu.topmy.cn/plugin.php?id=gis_sczl:gisapi",
                                        contentType: "application/x-www-form-urlencoded",
                                        dataType: "json",
                                        data: {
                                            "resid": id,
                                            "mod": "getres"
                                        },
                                        success: function (res) {
                                            console.log(res.data)
                                            if (res.code == 0) {
                                                var sanji = res.data;
                                                console.log(sanji)
                                                var wwpp = '';
                                                for (var i = 0; i < sanji.length; i++) {
                                                    wwpp += '<option value="' + sanji[i].id + '">' + sanji[i].name + '</option>'
                                                }
                                                $("select[name=resid3]").append(wwpp);
                                                form.render();
                                                form.on('select(resid3)', function (proData) {
                                                    var pp = proData.value; //三级id
                                                })
                                            }
                                        }
                                    })
                                })
                            }
                        }
                    })
                })
            }
        }
    });
    var tupian;
    //添加上传文件
    upload.render({
        elem: '#test7',
        url: 'http://silu.topmy.cn/plugin.php?id=gis_sczl:gisapi',
        data: {
            "mod": "upimgs"
        },
        done: function (res) {
            tupian = res.data;
            console.log(res.data)
        }
    });
    form.on('submit(demo1)', function (data) {
        var field = data.field; //提交的数据
        $.ajax({
            type: "POST",
            url: "http://silu.topmy.cn/plugin.php?id=gis_sczl:gisapi",
            dataType: "json",
            data: {
                "name": field.name,
                "types": field.types,
                "lnglat": lnglat,
                "resid": field.resid,
                "resid2": field.resid2,
                "resid3": field.resid3,
                "texts": field.texts,
                "file": tupian,
                "color": backgrse,
                "icon": icon.split('/').pop(),
                "mod": "gisinput"
            },
            success: function (res) {
                console.log(res)
                if (res.code == 0) {
                    layer.alert('录入成功', {
                        icon: 6,
                        title: '信息录入'
                    })
                }
            }
        })
    });
});