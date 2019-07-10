<?php if(!defined('IN_DISCUZ')) exit('Access Denied'); ?>
<button type="button" id="mapbox" class="pn vm" onclick="return editmap();"  style="margin-left: 10px;"><em>加入地图</em></button>

<br/>
<iframe name="gd_map_iframe" id="gd_map_iframe" src="/plugin.php?id=gis_sczl:gismap_map&amp;mod=addmap" frameborder="0" align="left" width="100%" height="100%"  scrolling="no" style="min-height: 600px;"></iframe>

<script src="/source/plugin/gis_sczl/style/js/base64.js" type="text/javascript"></script>
<script src="/source/plugin/gis_sczl/style/js/jquery-1.4.4.min.js" type="text/javascript"></script>


<script>
    var jq = jQuery.noConflict();
    var texts = [{"name": "点 - 标注信息", "lnglat": [116.258446, 37.686622], "texts": "gis  test ", "imgs": "/source/plugin/gis_sczl/style/images/last.png", "icon": "/source/plugin/gis_sczl/style/images/way_btn1.png", "color": "#ccc", "http": null, "img": "/source/plugin/gis_sczl/style/images/last.png", "style": 2}, {"name": "点 - 标注信息", "lnglat": [98.51, 40.4], "texts": "gis  test ", "imgs": "/source/plugin/gis_sczl/style/images/last.png", "icon": "/source/plugin/gis_sczl/style/images/way_btn1.png", "color": "#ccc", "http": null, "img": "/source/plugin/gis_sczl/style/images/last.png", "style": 2}];
    texts = JSON.stringify(texts);
    texts = BASE64.encode(texts);

    function editmap() {
        if(!texts){
            alter('获取标注信息有误');
        }
        var argis = 0;
        jq.ajax({
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
        }

    }

</script>

