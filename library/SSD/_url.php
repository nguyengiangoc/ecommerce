<?php
    class URL {
        //static lam cho method hoac property cua mot class co the duoc goi ma khong can phai tao truoc do mot object thuoc class do
        //doi voi static, de goi method hoac property thi phai dung cau truc ten class :: ten method/function
        //doi voi khong static thi goi bang $ten class -> ten method/function
        public static $_page = "page";
        public static $_folder = PAGES_DIR;
        public static $_params = array();
        
        public static function getParam($par) {
            return isset($_GET[$par]) && $_GET[$par] != "" ?
                //kiem tra xem co attribute nhu vay tren link khong va attribute do co noi dung khong
                $_GET[$par] : null;
                //neu nhu cau tra loi la co cho 2 cau hoi tren, tuc la co trong bien get
                //thi tra ve attribute
        }
        
        public static function cPage() {
            //tra ve trang hien dang mo 
            return isset($_GET[self::$_page]) ?
                $_GET[self::$_page] : 'index';
                //neu nhu bien page da duoc set thi ten trang chinh la bien page, neu chua duoc set thi la trang chu index
        }
        
        public static function getPage() {
            // tra ve duong dan den trang hien tai
            $page = self::$_folder . DS . self::cPage() . ".php";
            //bien page tra ve duong dan den mot trang nao do trong folder pages
            //dem vao trong phan core de require
            //vd nhu vao trang chu ecommerce/index.php thi se ham nay se require trang pages/index.php
            $error = self::$_folder . DS . "error.php";
            //bien error tra ve trang error, la trang 404 trang nay khong ton tai
            return is_file($page) ? $page : $error;
            //kiem tra xem co ton tai file nhu vay o duong dan trong bien page khong, neu co thi tra ve duong dan do
            //neu khong ton tai thi tra ve file error
            //sau do ham core se require error php, dem noi dung cua trang php ra cho ng xem
            
        }
        
        public static function getAll() {
            if(!empty($_GET)) {
                foreach($_GET as $key => $value) {
                    if (!empty($value)) {
                        self::$_params[$key] = $value;
                    }
                }
            }
        }
        //ham nay lay cac attribute va property cho vao mot property param cua class nay
        
        public static function getCurrentURL($remove = null) {
            self::getAll();
            $out = array();
            if(!empty($remove)) {
                $remove = !is_array($remove) ? array($remove) : $remove;
                foreach(self::$_params as $key => $value) {
                    if(in_array($key, $remove)) {
                        unset(self::$_params[$key]);
                    }
                }
            }
            foreach(self::$_params as $key => $value) {
                $out[] = $key . "=" . $value; 
            }
            return "?" . implode("&", $out);
        }
        //ham nay tra ve link da duoc loai mot attribute va mot property
        
        public static function getReferrerURL() {
            $page = self::getParam(Login::$_referrer);
            return !empty($page) ? "?page={$page}" : null;
        }
        
        public static function getParams4Search($remove = null) {
            self::getAll();
            $out = array();
            if(!empty(self::$_params)) {
                foreach(self::$_params as $key => $value) {
                    if(!empty($remove)) {
                        $remove = is_array($remove) ? $remove : array($remove);
                        if(!in_array($key, $remove)) {
                            //cac hanh dong sau day chi xay ra khi param lay tu url khac voi param duoc khai bao trong bien remove
                            $input = '<input type="hidden" name="'.$key.'" value="'.$value.'" />';
                            $out[] = $input;
                        }
                    } else {
                        $input = '<input type="hidden" name="'.$key.'" value="'.$value.'" />';
                        $out[] = $input;
                    }
                }
                return implode("", $out);
            }
        }
        
        
        
    }
?>