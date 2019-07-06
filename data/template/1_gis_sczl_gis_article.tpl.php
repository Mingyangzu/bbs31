<?php if(!defined('IN_DISCUZ')) exit('Access Denied'); ?>
<button type="button" id="mapbox" class="pn vm" onclick="return editmap();"  style="margin-left: 10px;"><em>加入地图</em></button>

<br/>
<iframe name="gd_map_iframe" id="gd_map_iframe" src="/plugin.php?id=gis_sczl:gismap_map&amp;mod=addmap" frameborder="0" align="left" width="100%" height="100%"  scrolling="no" style="min-height: 600px;"></iframe>

<script>
    function editmap() {
        var editp = window.frames['uchome-ifrHtmlEditor'];
        var editobj = editp.window.frames['HtmlEditor'];
        var editInfos = editobj.document.body.innerHTML;
        editobj.document.body.innerHTML = editInfos + '<br/> <br/><div><iframe name="gd_map_iframe" id="gd_map_iframe" src="/plugin.php?id=gis_sczl:gismap_map&amp;mod=article" frameborder="0" align="left" width="100%"  scrolling="no" style="min-height: 500px;"></iframe></div> <div><br></div><div><br></div> ';

    }

</script>