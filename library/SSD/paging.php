<?php

    namespace SSD;

    class Paging {
        public $objURL;
        private $_records;
        private $_max_pp;
        private $_numb_of_pages;
        private $_current;
        private $_offset = 0;
        
        public static $key = 'pg';
        public $url;
        
        public function __construct($objURL = null, $rows = null, $max = 10) {
            $this->objURL = is_object($objURL) ? $objURL : new URL() ;
            $this->_records = $rows;
            $this->_numb_of_records = count($this->_records);
            $this->_max_pp = $max;
            $this->url = $this->objURL->getCurrent(array(self::$key, 'call'));
            $current = $this->objURL->get(self::$key);
            $this->_current = !empty($current) ? $current : 1;
            $this->numberOfPages();
            $this->getOffset();
        }
        
        private function numberOfPages() {
            $this->_numb_of_pages = ceil($this->_numb_of_records/$this->_max_pp);
        }
        
        private function getOffset() {
            $this->_offset = ($this->_current - 1) * $this->_max_pp;
        }
        //tong so thanh phan o tat ca cac trang truoc trang hien tai
        
        public function getRecords() {
            $out = array();
            if($this->_numb_of_pages > 1) {
                $last = ($this->_offset + $this->_max_pp);
                for ($i = $this->_offset; $i < $last; $i++) {
                    if ($i < $this->_numb_of_records) {
                        $out[] = $this->_records[$i];
                    }
                }
            } else {
                $out = $this->_records;
            }
            return $out;
        }
        //ham nay lay ra so thanh phan duoc trinh bay ra trong 1 trang
        
        public function getLinks() {
            if($this->_numb_of_pages > 1) {
                $out = array();
                if($this->_current > 1) {
                    $out[] = "<a href=\"/ecommerce/".$this->url.PAGE_EXT."\">First</a>";
                    //property url duoc goi tu function get url o tren, da~ bo attribute pg di roi, nen khong co pg, ma mac dinh k co pg
                    //tuc la trang 1
                } else {
                    $out[] = "<span>First</span>";
                    //neu trang dang xem la trang so 1 thi khong can de link ma de span de css lam noi bat
                }
                
                if($this->_current > 1) {
                    $id = ($this->_current - 1);
                    $url = $id > 1 ? $this->url."/".self::$key."/".$id.PAGE_EXT : $this->url.PAGE_EXT;
                    $out[] = "<a href=\"/ecommerce/{$url}\">Previous</a>";
                } else {
                    $out[] = "<span>Previous</span>";
                }
                
                if($this->_current != $this->_numb_of_pages) {
                    $id = ($this->_current + 1);
                    $url = $this->url."/".self::$key."/".$id.PAGE_EXT;
                    $out[] = "<a href=\"/ecommerce/{$url}\"\">Next</a>";
                } else {
                    $out[] = "<span>Next</span>";
                }
                
                if($this->_current != $this->_numb_of_pages) {
                    $url = $this->url."/".self::$key."/".$this->_numb_of_pages;
                    $out[] = "<a href=\"/ecommerce/{$url}\"\">Last</a>";
                } else {
                    $out[] = "<span>Last</span>";
                }
                
                return "<li>" . implode("</li><li>", $out) . "</li>";
            }
        }
        
        public function getPaging() {
            $links = $this->getLinks();
            if(!empty($links)) {
                $out = "<ul class=\"paging\">" . $links . "</ul>";
                return $out;
            }
        }
    }
?>