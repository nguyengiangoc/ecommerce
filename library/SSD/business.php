<?php
    class Business extends Application {
        private $_table = 'business';
        
        public function getBusiness() {
            //goi ra ten cua doanh nghiep
            $sql = "SELECT * FROM {$this->_table} WHERE id = 1";
            return $this->db->fetchOne($sql);
        }
        
        public function getVATrate() {
            $business = $this->getBusiness();
            return $business['vat_rate'];
        }
        
        public function updateBusiness($vars = null) {
            if (!empty($vars)) {
                $this->db->prepareUpdate($vars);
                return $this->db->update($this->_table, 1);
            }
        }
    }
?>