<?php

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

class table_portal_article_gis extends discuz_table{
    
    public function __construct() {
        $this->_table = 'portal_article_gis';
        $this->_pk = 'id';
        
        parent::__construct($para);
    }
    
    public function inster($data){
       return DB::insert($this->_table, $data, true, true);
    }
    
    public function update($data, $condition, $unbuffered = false, $low_priority = false) {
       return DB::update($this->_table, $data, $condition, $unbuffered, $low_priority);
    }
    
    public function delete($condition, $limit = 1) {
        return DB::delete($this->_table, $condition, $limit);
    }
    
    public function findinfo($condition_str, $options = 'texts'){
        $data = DB::fetch_all('SELECT '. $options .' FROM '.DB::table($this->_table).' WHERE '.$condition_str);
        return $data;
    }
    
    public function findlist($condition_str, $options = 'id', $start = 0, $limit = 35){
        $data = DB::fetch_all('SELECT '. $options .' FROM '.DB::table($this->_table).' WHERE '.$condition_str . " limit $start,$limit");
        return $data;
    }
    
    public function joinArticlelist($condition_str, $options = 'a.name', $limit = '', $orderby = '') {
        $sql = "select $options from ". DB::table($this->_table) ." a left join ".DB::table('portal_article_title')." b on a.gisucode = b.gisucode $condition_str $orderby $limit;";
        return DB::fetch_all($sql);
    }
    
}

