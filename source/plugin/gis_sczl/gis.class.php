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
        return '页面嵌入-普通版';
    }

    function gis_header() {

        return 'gis页面嵌入 header';
    }

    function portalcp_extend() {
        $siturl = $_G['siteurl'];

       return  file_get_contents(template('gis_sczl:gis_article'));
    }
    
    


}
