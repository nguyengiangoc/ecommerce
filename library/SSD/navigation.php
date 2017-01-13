<?php
    class Navigation {
        public $objURL;
        public $classActive = 'act';
        
        public function __construct($objURL = null) {
            $this->objURL = is_object($objURL) ? $objURL : new URL() ;
        }
        
        public function active($main = null, $pairs = null, $single = true) {
            if(!empty($main)) {
                if(empty($pairs)) {
                    if($main == $this->objURL->main) {
                        return !$single ? ' '.$this->classActive : ' class="'.$this->classActive.'"';
                    }
                } else { //pair duoc cho vao o dang array 1 thanh phan: key la param, element la property
                    $exceptions = array();
                    foreach($pairs as $key => $value) {
                        $paramURL = $this->objURL->get($key); //lay trong array param cua objURL element co key giong voi key trong param dua vao
                        if($paramURL != $value) { //neu property tren url khac voi property duoc dua vao, tuc la neu giong thi khong cho vao
                            $exceptions[] = $key;
                        }
                    }
                    if($main == $this->objURL->main && empty($exceptions)) { 
                        //exception trong tuc la param k dc dua vao, tuc la property cho vao giong voi property tren url
                        return !$single ? ' '.$this->classActive : ' class="'.$this->classActive.'"'; //cho them class act
                    }
                }
            }
        }
        
    }

?>