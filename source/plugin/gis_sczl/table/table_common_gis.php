<?php

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

class table_common_gis extends discuz_table{
    
    public function __construct() {
        $this->_table = 'common_gis';
        $this->_pk = 'id';
        
        parent::__construct($para);
    }
    
    public function inster($data){
       return DB::insert($this->_table, $data, false, true);
    }
    
    public function update($data, $condition, $unbuffered = false, $low_priority = false) {
       return DB::update($this->_table, $data, $condition, $unbuffered, $low_priority);
    }
    
    public function delete($condition, $limit = 1) {
        return DB::delete($this->_table, $condition, $limit);
    }
    
    public function findinfo($condition_str, $options = 'name'){
        $data = DB::fetch_all('SELECT '. $options .' FROM '.DB::table($this->_table).' WHERE '.$condition_str);
        return $data;
    }
    
    public function findlist($condition_str, $options = 'name', $start = 0, $limit = 15){
        $data = DB::fetch_all('SELECT '. $options .' FROM '.DB::table($this->_table).' WHERE '.$condition_str . " limit $start,$limit");
        return $data;
    }
    
    public function counts($condition_str){
//        $data = DB::query("select count(`id`) counts from ".DB::table($this->_table). " WHERE ".$condition_str);
        $data = C::t('#gis_sczl#common_gis')->findlist($condition_str, 'id', 0, 1000);
        return count($data);
    }
    
    
}

