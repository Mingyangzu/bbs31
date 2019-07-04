<?php

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

class plugin_gis_sczl_base{
    function _test(){
        return DB;
    }
    
}



class plugin_gis_sczl_portal extends plugin_gis_sczl_base {
    
    function ttone(){
       return parent::_test();
    }
    
    function global_header(){
//        include template('gis_sczl:test');
//        return '页面嵌入-普通版';
    }
    
    function gis_header(){
        
        return 'gis页面嵌入 header';
    }
    
    function portalcp_extend(){
        
        return 'portalcp_extend';
    }
    
    function protalcp_test_output($value){
        return array(
'template' => '当前要输出的模版',
'message' => 'showmessage 的信息内容',
'values' => 'showmessage 的信息变量',
);
    }
    
}


class plugin_gis_sczl_admin extends plugin_gis_sczl_base{
    
function gis_article_extend(){
    return 'article extend';
}

}


