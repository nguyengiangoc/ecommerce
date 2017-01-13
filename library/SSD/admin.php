<?php
    class Admin extends Application {
        private $_table = 'admins';
        public $_id;
        
        public function isUser($email = null, $password = null) {
            if(!empty($email) && !empty($password)) {
                $password = Login::string2Hash($password);
                $sql = "SELECT * FROM `{$this->_table}` WHERE `email` = '".$this->db->escape($email)."' AND `password` = '".$this->db->escape($password)."'";
                $result = $this->db->fetchOne($sql);
                if(!empty($result)) {
                    $this->_id = $result['id'];
                    return true;
                }
                return false;
            }
        }
        
        public function getFullNameAdmin($id = null) {
            if(!empty($id)) {
                $sql = "SELECT *, CONCAT_WS(' ', `first_name`, `last_name`) AS `full_name` FROM `{$this->_table}` WHERE `id` = ".intval($id);
                $result = $this->db->fetchOne($sql);
                if(!empty($result)) {
                    return $result['full_name'];
                }
            }
        }
    }
?>