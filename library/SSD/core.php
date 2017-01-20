<?php

    namespace SSD;
    use SSD\Catalogue;

    class Core {
        
        public $objURL;
        public $objNavigation;
        public $objCurrency;
        public $objAdmin;
        
        public $meta_title = 'E-commerce project';
        public $meta_description = 'E-commerce project';
        
        public function __construct() {
            $this->objURL = new URL();
            $this->objNavigation = new Navigation($this->objURL);
            $this->objCurrency = new Currency();
        }
        
        public function run() {
            ob_start();

            //var_dump($this->objURL);
//            echo ROOT_PATH.DS.PAGES_DIR.DS.$this->objURL->cpage.'.php';
            switch($this->objURL->module) {
                case 'panel' :
                    set_include_path(implode(PATH_SEPARATOR, array( 
                    ////path separator la de phan tach cac path noi chung va include path noi rieng
                        realpath(ROOT_PATH.DS.'admin'.DS.TEMPLATE_DIR),
                        realpath(ROOT_PATH.DS.'admin'.DS.PAGES_DIR),
                        get_include_path() 
                        //////lay nhung duong dan mac dinh trong include path trong file php.ini cua server
                    )));
                    $this->objAdmin = new Admin();
                    require_once(ROOT_PATH.DS.'admin'.DS.PAGES_DIR.DS.$this->objURL->cpage.'.php');
                break;
                default:
                    //echo ROOT_PATH.DS.PAGES_DIR.DS.$this->objURL->cpage.'.php';
                    set_include_path(implode(PATH_SEPARATOR, array( 
                    ////path separator la de phan tach cac path noi chung va include path noi rieng
                        realpath(ROOT_PATH.DS.TEMPLATE_DIR),
                        realpath(ROOT_PATH.DS.PAGES_DIR),
                        get_include_path() 
                        ////lay nhung duong dan mac dinh trong include path trong file php.ini cua server
                    )));
                    //$objCatalogue = new Catalogue();
                    require_once(ROOT_PATH.DS.PAGES_DIR.DS.$this->objURL->cpage.'.php'); 
//                    //construct cua URL se goi method process, method process se tra va cpage
            }
            ob_get_flush();            
        }
    }
?>