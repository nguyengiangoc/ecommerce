<?php

    namespace SSD;

    class Business extends Application {
        protected $_table = 'business';

        const BUSINESS_ID = 1;
        
        public function getVATrate() {
            $business = $this->getOne(self::BUSINESS_ID);
            return $business['vat_rate'];
        }
        
    }
?>