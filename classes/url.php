<?php
    class URL {
        public $key_page = 'page';
        public $key_modules = array('panel');
        public $module = 'front';
        public $main = 'index';
        public $cpage = 'index';
        public $c = 'login';
        public $a = 'index';
        public $params = array(); //tat ca nhung phan trong dau '/' tren link
        public $paramsRaw = array();
        public $stringRaw;
        
        public function __construct() {
            $this->process();
        }
        
        public function process() {
            $uri = $_SERVER['REQUEST_URI'];
            if(!empty($uri)) {
                $uriQ = explode('?', $uri); 
                //cau truc cua link la abc/xyz.php?param=blah&param=blah
                //tach ra de tim xem co param nao o tren link, sau dau cham hoi se la tat ca cac param duoc gan bang dau &, nen day la array
                $uri = $uriQ[0]; //thanh phan dau tien se la link day du
                if(count($uriQ) > 1) { //tren link ngoai link day du con co param
                    $this->stringRaw = $uriQ[1]; 
                    $uriRaw = explode('&', $uriQ[1]); //tach ra tung param
                    if(count($uriRaw > 1)) { //neu co nhieu hon 1 param
                        foreach($uriRaw as $key => $row) {
                            $this->splitRaw($row); //dua param va property vao trong array paramsRaw theo cau truc key: param, value: property
                        }
                    } else {
                        $this->splitRaw($uriRaw[0]); //neu co 1 param thi dua param dau tien vao trong array paramsRaw
                    }
                }
                $uri = Helper::clearString($uri, PAGE_EXT); 
                //xoa page_ext khoi duoi cua uri
                //vi ext la .html, ma uri cung co duoi la .html, thi luc sau khi gan vao se la .html.html, khong duoc, nen phai xoa
                $firstChar = substr($uri, 0, 1);
                if($firstChar == '/') {
                    $uri = substr($uri, 1); //xoa dau '/' dau tien ra khoi uri
                }
                $lastChar = substr($uri, -1);
                if($lastChar == '/') {
                    $uri = substr($uri, 0, -1); //xoa dau '/' cuoi cung ra khoi uri
                }
                // vi du uri tra ve la /ecommerce/ sau khi xoa di thi con lai ecommerce
                if(!empty($uri)) {
                    $uri = explode('/', $uri);
                    //vd nhu dang o page ecommerce/panel, thi tach ra lam array ecommerce va panel
                    $first = array_shift($uri); //loai phan tu dau tien khoi mang va tra ve phan tu dau tien, luc nay first la ecommerce
                    $first = array_shift($uri); //phai lay lan thu hai moi ra duoc category
                    //neu lam truc tiep tren host thi ten website la domain luon, nen uri se tra ve catalogue/category..., chi can 1 lan arrayshift
                    //nhung dang lam tren localhost
                    //uri tra ve la ecommerce/catalogue/category nen phai 2 lan array shift
                    if(empty($first)) { $first = 'index'; }
                    if(in_array($first, $this->key_modules)) {
                        $this->module = $first; //module hien dang la front, chuyen sang thanh panel
                        $first = empty($uri) ? $this->main : array_shift($uri); //neu van con thanh phan thi loai thanh phan dau tien ra
                    }
                    $this->main = $first; 
                    //vd nhu 
                    //vi du nhu ecommerce/panel/product, luc nay main se la product
                    //con neu ma ecommerce/category/blah blah, luc nay main se la category
                    $this->cpage = $this->main;
                    
                    if(count($uri) > 1) {
                        $pairs = array();
                        foreach($uri as $key => $value) {
                            $pairs[] = $value;
                            if(count($pairs) > 1) {
                                if(!Helper::isEmpty($pairs[1])) {
                                    if($pairs[0] == $this->key_page) {
                                        $this->cpage = $pairs[1];
                                    } else if ($pairs[0] == 'c') {
                                        $this->c = $pairs[1];
                                    } else if ($pairs[0] == 'a') {
                                        $this->a = $pairs[1];
                                    }
                                    $this->params[$pairs[0]] = $pairs[1];
                                }
                                $pairs = array();
                                //cu het hai value thi empty array pair mot lan de trong array pair chi duoc 2 value thoi
                                //value dau tien la key trong param, value thu hai la gia tri cua param
                            }
                        }
                    }
                }
            }
        }
        
        public function splitRaw($item = null) {
            if(!empty($item) && !is_array($item)) {
                $itemRaw = explode('=', $item);
                if(count($itemRaw) > 1 && !Helper::isEmpty($itemRaw[1])) {
                    $this->paramsRaw[$itemRaw[0]] = $itemRaw[1];
                }
            }
        }
        
        public function getRaw($param = null) {
            if(!empty($param) & array_key_exists($param, $this->paramsRaw)) {
                return $this->paramsRaw[$param];
            }
        }
        
        public function get($param = null) {
            if(!empty($param) && array_key_exists($param, $this->params)) {
                return $this->params[$param];
            }
        }
        
        public function href($main = null, $params = null) {
            if(!empty($main)) {
                $out = array($main);
                if(!empty($params) && is_array($params)) {
                    foreach($params as $key => $value) {
                        $out[] = $value; //array cho vao se co dang ten param va property
                    }
                }
                return implode('/', $out).PAGE_EXT; //khi xuat ra se co dang main/ten param/property
            }
        }
        
        public function getCurrent($exclude = null, $extension = false, $add = null) {
            $out = array();
            if($this->module != 'front') {
                $out[] = $this->module;
            }
            $out[] = $this->main;
            if(!empty($this->params)) {
                if(!empty($exclude)) {
                    $exclude = Helper::makeArray($exclude);
                    foreach($this->params as $key => $value) {
                        if(!in_array($key, $exclude)) { //neu co exclude thi chi cho vao array out nhung gi khong phai exclude
                            $out[] = $key;
                            $out[] = $value;
                        }
                    }
                } else {
                    foreach($this->params as $key => $value) {
                        $out[] = $key;
                        $out[] = $value;
                    }
                }
            }
            if(!empty($add)) {
                $add = Helper::makeArray($add);
                foreach($add as $item) {
                    $out[] = $item;
                }
            }
            $url = implode('/', $out);
            $url .= $extension ? PAGE_EXT : null;
            return $url;
            //ket qua la dang index/blah blah/blah blah
        }

    }
?>