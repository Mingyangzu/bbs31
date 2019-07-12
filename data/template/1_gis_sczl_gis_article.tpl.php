<?php if(!defined('IN_DISCUZ')) exit('Access Denied'); ?>
<link rel="stylesheet" href="/source/plugin/gis_sczl/style/layui/css/layui.css"  media="all">

<button type="button" id="mapbox" class="pn vm" onclick="return ''; "  style="margin-left: 10px;"><em>添加地图</em></button>

<script src="/source/plugin/gis_sczl/style/js/base64.js" type="text/javascript"></script>
<script src="/source/plugin/gis_sczl/style/layui/layui.js" type="text/javascript" charset="utf-8"></script>

<style>
    body .editmapbox .layui-layer-btn{padding: 5px; background-color: #F8F8F8;}
    body .editmapbox .layui-layer-btn .layui-layer-btn0{position: absolute; top: 0px; }
</style>

<script>
    var texts = [{"name": "点 - 标注信息", "lnglat": [116.258446, 37.686622], "texts": "gis  test ", "imgs": "/source/plugin/gis_sczl/style/images/last.png", "icon": "/source/plugin/gis_sczl/style/images/way_btn1.png", "color": "#ccc", "http": null, "img": "/source/plugin/gis_sczl/style/images/last.png", "style": 2}, {"name": "点 - 标注信息", "lnglat": [98.51, 40.4], "texts": "gis  test ", "imgs": "/source/plugin/gis_sczl/style/images/last.png", "icon": "/source/plugin/gis_sczl/style/images/way_btn1.png", "color": "#ccc", "http": null, "img": "/source/plugin/gis_sczl/style/images/last.png", "style": 2}];
    texts = JSON.stringify(texts);
    texts = BASE64.encode(texts);

    layui.use(['layer', 'jquery'], function () {
        var layer = layui.layer, $ = layui.jquery;
        var index = 0; //添加laoding,0-2两种方式 

        function editmap() {
            if (!texts) {
                alter('获取标注信息有误');
            }
            var argis = 0;
            index = layer.load(1);
            $.ajax({
                url: '/plugin.php?id=gis_sczl:gisapi',
                type: 'post',
                dataType: 'json',
                async: false,
                data: {"texts": texts, "mod": "articlegis"},
                success: function (res) {
                    if (res.data != '') {
                        argis = res.data;
                    } else {
                        alert(res.msg);
                        return false;
                    }
                },
                error: function (res) {
                    console.log(res);
                }
            });
            if (argis > 0) {
                var editp = window.frames['uchome-ifrHtmlEditor'];
                var editobj = editp.window.frames['HtmlEditor'];
                var editInfos = editobj.document.body.innerHTML;
                editobj.document.body.innerHTML = editInfos + '<br/> <br/><div><iframe name="gd_map_iframe" id="gd_map_iframe" src="/plugin.php?id=gis_sczl:gismap_map&amp;mod=article&amp;argis=' + argis + '" frameborder="0" align="left" width="80%"  scrolling="no" style="min-height: 500px;position: relative;"></iframe></div> <div><br></div><div><br></div> ';
                layer.close(index);
                return true;
            }else{
                layer.close(index);
                return false;
            }
            
        }

        $('#mapbox').click(function(){
        layer.open({
            type: 2,
            title: '地理标注信息选则',
            area: ['100%', '100%'],
            content: '/plugin.php?id=gis_sczl:gismap_map&mod=addmap',
            btn: ['添加到文章'],
            btnAlign: 'c',
            closeBtn: 1,
            anim: 0,
            maxmin: true,
            shade: 0,
            skin: 'editmapbox',
            yes: function (index, layero) {
               if(editmap()){
                   layer.msg('添加成功!');
               }else{
                   layer.msg('添加失败!');
               }
               layer.close(index);
            },
            cancel: function () {
                //右上角关闭回调
                //return false 开启该代码可禁止点击该按钮关闭
            },
        });
    });

    });

</script>

