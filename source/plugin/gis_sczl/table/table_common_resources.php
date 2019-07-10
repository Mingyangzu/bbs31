<?php

if (!defined('IN_DISCUZ')) {
    exit('Access Denied');
}

class table_common_resources extends discuz_table {

    public function __construct() {
        $this->_table = 'common_resources';
        $this->_pk = 'id';

        parent::__construct($para);
    }

    public function fetch($skey, $auto_unserialize = false) {
        $data = DB::result_first('SELECT svalue FROM ' . DB::table($this->_table) . ' WHERE ' . DB::field($this->_pk, $skey));
        return $auto_unserialize ? (array) unserialize($data) : $data;
    }

    public function fetch_all($skeys = array(), $auto_unserialize = false) {
        $data = array();
        $where = !empty($skeys) ? ' WHERE ' . DB::field($this->_pk, $skeys) : '';
        $query = DB::query('SELECT * FROM ' . DB::table($this->_table) . $where);
        while ($value = DB::fetch($query)) {
            $data[$value['skey']] = $auto_unserialize ? (array) unserialize($value['svalue']) : $value['svalue'];
        }
        return $data;
    }

    public function update($skey, $svalue) {
        return DB::insert($this->_table, array($this->_pk => $skey, 'svalue' => is_array($svalue) ? serialize($svalue) : $svalue), false, true);
    }

    public function update_batch($array) {
        $settings = array();
        foreach ($array as $key => $value) {
            $key = addslashes($key);
            $value = addslashes(is_array($value) ? serialize($value) : $value);
            $settings[] = "('$key', '$value')";
        }
        if ($settings) {
            return DB::query("REPLACE INTO " . DB::table('common_setting') . " (`skey`, `svalue`) VALUES " . implode(',', $settings));
        }
        return false;
    }

    public function skey_exists($skey) {
        return DB::result_first('SELECT skey FROM %t WHERE skey=%s LIMIT 1', array($this->_table, $skey)) ? true : false;
    }

    public function fetch_all_not_key($skey) {
        return DB::fetch_all('SELECT * FROM ' . DB::table($this->_table) . ' WHERE skey NOT IN(' . dimplode($skey) . ')');
    }

    public function fetch_all_table_status() {
        return DB::fetch_all('SHOW TABLE STATUS');
    }

    public function get_tablepre() {
        return DB::object()->tablepre;
    }

    public function update_count($skey, $num) {
        return DB::query("UPDATE %t SET svalue = svalue + %d WHERE skey = %s", array($this->_table, $num, $skey), false, true);
    }

    public function findlist($condition_str = '', $options = 'name') {
        return DB::fetch_all('SELECT ' . $options . ' FROM ' . DB::table($this->_table) . $condition_str);
    }

    public function listarr($condition_str, $options = 'name', $start = 1, $limit = 10) {
        $data = DB::fetch_all('SELECT ' . $options . ' FROM ' . DB::table($this->_table) . $condition_str . " limit $start,$limit");
        return $data;
    }

    public function counts($condition_str = '') {
        return DB::fetch_all('SELECT count(id) count FROM ' . DB::table($this->_table) . $condition_str);
    }

    public function delete($condition, $limit = 1) {
        return DB::delete($this->_table, $condition, $limit);
    }

    public function inster($data) {
        return DB::insert($this->_table, $data, false, true);
    }

    public function querylist($condition_str, $options = 'a.name', $start = 1, $limit = 10) {
        $sql = "select $options from ". DB::table($this->_table) ." a left join ". DB::table($this->_table) ." b on a.fid = b.id $condition_str limit $start,$limit;";
        return DB::fetch_all($sql);
    }
    
    public function updateres($data, $condition, $unbuffered = false, $low_priority = false) {
       return DB::update($this->_table, $data, $condition, $unbuffered, $low_priority);
    }

}
