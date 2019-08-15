<?php if(!defined('IN_DISCUZ')) exit('Access Denied'); ?>
<link rel="stylesheet" href="/source/plugin/gis_sczl/style/layui/css/layui.css"  media="all">

<button type="button" id="mapresbox" class="pn vm" style="margin-left: 10px;"><em>添加地图标注信息</em></button>

<input type="hidden" name="gisucode" id="gisucode" value="<?php echo empty($article[gisucode]) ? $gisucode : $article[gisucode] ;?> ">

<button type="button" id="mapbox" class="pn vm" style="margin-left: 10px;"><em>添加地图</em></button>


<script src="/source/plugin/gis_sczl/style/js/base64.js" type="text/javascript"></script>
<script src="/source/plugin/gis_sczl/style/layui/layui.js" type="text/javascript" charset="utf-8"></script>

<style>
    body .editmapbox .layui-layer-btn{padding: 5px; background-color: #F8F8F8;}
    body .editmapbox .layui-layer-btn .layui-layer-btn0{position: absolute; top: 0px; }
</style>

<script>
    layui.use(['table', 'form', 'layer', 'jquery'], function () {
        var table = layui.table, form = layui.form, layer = layui.layer, $ = layui.jquery;
        var index = 0; //添加laoding,0-2两种方式 
        layui.sessionData('gisucode', {key: 'code', value: $('#gisucode').val()});
        
        function editmap() {
            var markers = layui.sessionData('allMarkers');
            var texts = markers.marker;
            if (!texts || texts.length < 1) {
//                layer.msg('未获取到标注信息!');
                return false;
            }
            texts = JSON.stringify(texts);
            texts = BASE64.encode(texts);
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
                        layer.msg(res.msg);
                        layer.close(index);
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
                editobj.document.body.innerHTML = editInfos + '<br/> <br/><div><iframe name="gd_map_iframe" id="gd_map_iframe" src="/plugin.php?id=gis_sczl:gismap_map&amp;mod=article&amp;argis=' + argis + '" frameborder="0" align="left" width="99%"  scrolling="no" style="min-height: 500px;position: relative;"></iframe></div> <div><br></div><div><br></div> ';
                layer.close(index);
                layui.sessionData('allMarkers', {key: 'marker', value: []});
                return true;
            } else {
                layer.close(index);
                return false;
            }

        }

        $('#mapbox').click(function () { 
            layer.open({
                type: 2,
                title: '添加地图',
                area: ['100%', '100%'],
                content: '/plugin.php?id=gis_sczl:gismap_map&mod=addmap',
                btn: ['添加到文章'],
                btnAlign: 'c',
                closeBtn: 1,
                anim: 0,
                maxmin: false,
                shade: 0,
                skin: 'editmapbox',
                yes: function (index, layero) {
                    var markers = layui.sessionData('allMarkers');

                    if (editmap()) {
                        layer.msg('添加成功!');
                    } else {
                        layer.msg('添加失败!');
                    }
                    layer.close(index);
                },
                cancel: function () {
                    //右上角关闭回调
                    //return false 开启该代码可禁止点击该按钮关闭
                    layui.sessionData('allMarkers', {key: 'marker', value: []});
                },
            });
        });
        
        $('#mapresbox').click(function () {
            var ucode = layui.sessionData('gisucode');
            if(!ucode.code){
               layer.msg('未获取到gisucode码,请刷新页面重试!'); 
               return false;
            }
            $.ajax({
                url: '/plugin.php?id=gis_sczl:gisapi',
                type: 'get',
                dataType: 'json',
                async: true,
                data: {"gisucode": ucode.code, "mod": "restoarticle"},
                success: function (res) { console.log(res);
                    if (res.data) {
                        layui.sessionData(ucode.code, {key: 'marker', value: res.data});
                    } else {
                        layer.msg(res.msg);
                    }
                },
                error: function (res) {
                    console.log(res);
                }
            });
            
            layer.open({
                type: 2,
                title: '添加地理标注信息',
                area: ['80%', '90%'],
                content: '/plugin.php?id=gis_sczl:gismap_map&mod=addmapres',
//                btn: ['保存标注到文章'],
                btnAlign: 'c',
                closeBtn: 1,
                anim: 0,
                maxmin: true,
                shade: 0,
                skin: 'editmapbox',
                yes: function (index, layero) {
                    layui.sessionData(ucode.code, {key: 'marker', value: []});
                    layer.close(index);
                },
                cancel: function () {
                    //右上角关闭回调  return false 开启该代码可禁止点击该按钮关闭
                    layui.sessionData(ucode.code, {key: 'marker', value: []});
                },
            });
        });       

    });

</script>

