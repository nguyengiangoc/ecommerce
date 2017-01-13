<?php
    class Country extends Application {
        
        private $_table = 'countries';
        
        public function getCountries($getZero = false) {
            $sql = "SELECT * FROM `{$this->_table}`";
            $sql .= $getZero == false ? " WHERE `include` = 1" : null;
            $sql .= " ORDER BY `name` ASC";
            return $this->db->fetchAll($sql);
        }
        
        public function getCountry($id = null) {
            if(!empty($id)) {
                $sql = "SELECT * FROM `{$this->_table}` WHERE `id` = ".intval($id)." AND `include` = 1";
                return $this->db->fetchOne($sql);
            }
        }
        
        public function getAllExceptLocal() {
            $sql = "SELECT * FROM `{$this->_table}` WHERE `id` != ".COUNTRY_LOCAL." ORDER BY `name` ASC";
            return $this->db->fetchAll($sql);
        }
        
        public function getOne($id = null) {
            if(!empty($id)) {
                $sql = "SELECT * FROM `{$this->_table}` WHERE `id` = ".intval($id);
                return $this->db->fetchOne($sql);
            }
        }
        
        public function addCountry($array = null) {
            if(!empty($array)) {
                $this->db->prepareInsert($array);
                return $this->db->insert($this->_table);
            }
            return false;
        }
        
        public function update($params = null, $id = null) {
            if(!empty($params) && !empty($id)) {
                $this->db->prepareUpdate($params);
                return $this->db->update($this->_table, $id);
            }
            return false;
        }
        
        public function remove($id = null) {
            if(!empty($id)) {
                $sql = "DELETE FROM `{$this->_table}` WHERE `id` = ".intval($id);
                return $this->db->query($sql);
            }
            return false;
        }
    }
?>