<?php
    class dbase {
        private $_host = "localhost";
        private $_user = "root";
        private $_password = "";
        private $_name = "ecommerce";
        
        private $_conndb = false;
        public $_last_query = null;
        public $_affected_rows = 0;
        
        public $_insert_keys = array();
        public $_insert_values = array();
        public $_update_sets = array();
        
        public $_id;
        
        public function __construct() {
            //ham construct duoc thi hanh ngay khi tao ra 1 object moi thuoc class nay
            $this->connect();
            //ket noi voi database ngay khi tao ra object dbase
        }
        
        private function connect() {
            $this->_conndb = mysql_connect($this->_host, $this->_user, $this->_password);
            if (!$this->_conndb) {
                die ("Database connection failed:<br/>" . mysql_error());
            } else {
                $_select = mysql_select_db($this->_name, $this->_conndb);
                if (!$_select) {
                    die("Selection failed:</br>" . mysql_error());
                }
                mysql_set_charset("utf8",$this->_conndb);
            }
        }
        
        public function close() {
            if(!mysql_close($this->_conndb)) {
                die ("Closing connection failed.");
            }
        }
        
        public function escape($value) {
            if(function_exists("mysql_real_escape_string")) {
                if (get_magic_quotes_gpc()) {
                    $value = stripslashes($value);
                }
                $value = mysql_real_escape_string($value);    
            } else {
                if (!get_magic_quotes_gpc()) {
                    $value = addslashes($value);
                }
            }
            return $value;
        }
        
        public function query($sql) {
            $this->_last_query = $sql;
            $result = mysql_query($sql, $this->_conndb);
            $this->displayQuery($result);
            return $result;
        }
        
        public function displayQuery($result) {
            if(!$result) {
                $output = "Database query failed: " . mysql_error() . "<br / >";
                $output .= "Last SQL query was: " . $this->_last_query;
                die($output);
            } else {
                $this->_affected_rows = mysql_affected_rows($this->_conndb);
            }
        }
        
        public function fetchAll($sql) {
            $result = $this->query($sql);
            $out = array();
            while ($row = mysql_fetch_assoc($result)) {
                $out[] = $row;
            }
            mysql_free_result($result);
            return $out;
        }
        
        public function fetchOne ($sql) {
            $out = $this->fetchAll($sql);
            return array_shift($out);
            //array shift lay ra thanh phan dau tien trong mot mang
        }
        
        public function lastId() {
            return mysql_insert_id($this->_conndb);
            //sau khi ket noi dc voi database thi conndb khong con gia tri false ma la lan ket noi gan day nhat
        }
        
        public function prepareInsert($array = null) {
            if(!empty($array)) {
                foreach($array as $key => $value) {
                    $this->_insert_keys[] = $key;
                    $this->_insert_values[] = $this->escape($value);
                }
            }
        }
        
        public function insert($table = null) {
            if(!empty($table) && !empty($this->_insert_keys) && !empty($this->_insert_values)) {
            $sql = "INSERT INTO `{$table}` (`".implode("`, `", $this->_insert_keys) . "`) VALUES ('" . implode("', '", $this->_insert_values) . "')";
                
                if($this->query($sql)) {
                    $this->_id = $this->lastId();
                    return true;
                } 
                return false;
            }
        }
        
        public function prepareUpdate($array = null) {
            if(!empty($array)) {
                foreach($array as $key => $value) {
                    $this->_update_sets[] = "`{$key}` = '".$this->escape($value)."'";
                }
            }
        }
        
        public function update($table = null, $id = null) {
            if(!empty($table) && !empty($id) && !empty($this->_update_sets)) {
                $sql = "UPDATE `{$table}` SET ".implode(", ", $this->_update_sets)." WHERE `id` = '".$this->escape($id)."'";
                return $this->query($sql);
            }
        }
        
    }
?>