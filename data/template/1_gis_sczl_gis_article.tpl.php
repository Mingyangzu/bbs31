<?php if(!defined('IN_DISCUZ')) exit('Access Denied'); ?>
<button type="button" id="mapbox" class="pn vm" onclick="return editmap();"><em>加入地图</em></button>

<br/>
<iframe name="gd_map_iframe" id="gd_map_iframe" src="/plugin.php?id=gis_sczl:gismap_map" frameborder="0" align="left" width="100%" height="100%"  scrolling="no" style="min-height: 700px;position: relative; top: -40px; display: none;"></iframe>

<script>
    function editmap() {
        var editp = window.frames['uchome-ifrHtmlEditor'];
        var editobj = editp.window.frames['HtmlEditor'];
        var editInfos = editobj.document.body.innerText;
        editobj.document.body.innerHTML = editobj.document.body.innerText + '<br/> <br/><iframe name="gd_map_iframe" id="gd_map_iframe" src="/plugin.php?id=gis_sczl:gismap_map&amp;mod=article" frameborder="0" align="left" width="90%"  scrolling="no" style="min-height: 500px;position: relative; top: -40px;margin: 10px auto; "></iframe><br/> <br/> ';
//        var mapp = window.frames['gd_map_iframe'];  console.log(mapp);
//        var mapInfos = '<html lang="en">' + mapp.document.head.innerHTML + mapp.document.body.innerHTML;  console.log(mapInfos) + '</html>';  //mapp.document.body.innerHTML;
//        editobj.document.body.innerHTML = editobj.document.body.innerText + '<br/><br/>' + mapInfos + '<br/><br/>';
//        console.log(mapobj.document.body.innerText);
//        console.log(mapInfos);
    }

</script>