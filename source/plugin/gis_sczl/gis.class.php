<?php

if (!defined('IN_DISCUZ')) {
    exit('Access Denied');
}

class plugin_gis_sczl_base {

    function _test() {
        return DB;
    }

}

class plugin_gis_sczl_portal extends plugin_gis_sczl_base {

    function ttone() {
        return parent::_test();
    }

    function global_header() {
//        include template('gis_sczl:test');
//        return '页面嵌入-普通版';
    }

    function gis_header() {

        return 'gis页面嵌入 header';
    }

    function portalcp_extend() {

//        return '<div class="sl_map_box">
//						<div class="gd_map">
//							<iframe name="gd_map_iframe" id="gd_map_iframe" src="/plugin.php?id=gis_sczl:gismap_map" frameborder="0" align="left" width="100%" height="100%" scrolling="no"></iframe>
//						</div>
//					</div>';
       return  file_get_contents(template('gis_sczl:gis_article'));
    }

//    function gismap_map(){
//        return file_get_contents(template('gis_sczl:gd_iframe'));
//    }
}
