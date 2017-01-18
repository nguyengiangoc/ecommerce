<?php

    namespace SSD;

    class Login {
        
        public static $login_page_front = "/login";
        
        public static $dashboard_front = "/orders";
        
        public static $login_front = "cid";
        
        public static $login_page_admin = "/panel";
        
        public static $dashboard_admin = "/panel/products";
        
        public static $login_admin = "aid";
        
        private static $_valid_login = "valid";
        
        public static $referrer = "refer";
        
        public function __construct() {
            self::$login_page_front = BASE_PATH.self::$login_page_front;
            self::$dashboard_front = BASE_PATH.self::$dashboard_front;
            self::$login_page_admin = BASE_PATH.self::$login_page_admin;
            self::$dashboard_admin = BASE_PATH.self::$dashboard_admin;
            
        }
        
        public static function isLogged($case = null) {
            if(!empty($case)) {
                if(isset($_SESSION[self::$_valid_login]) && $_SESSION[self::$_valid_login] == 1) {
                //kiem tra xem trong array session co attribute valid = 1  khong
                    return isset($_SESSION[$case]) ? true :  false;
                    //kiem tra xem trong array session co attribute cid khong
                }
                return false;
            }
            return false;
        }
        
        public static function loginFront($id, $url = null) {
            if(!empty($id)) {
                $url = !empty($url) ? $url : self::$dashboard_front.PAGE_EXT;
                $_SESSION[self::$login_front] = $id;
                $_SESSION[self::$_valid_login] = 1;
                Helper::redirect($url);
            }
        }
        
        public static function loginAdmin($id = null, $url = null) {
            if(!empty($id)) {
                $url = !empty($url) ? $url : self::$dashboard_admin;
                $_SESSION[self::$login_admin] = $id;
                $_SESSION[self::$_valid_login] = 1;
                Helper::redirect($url);
            }
        }
               
        public static function restrictFront($objURL = null) {
            $objURL = is_object($objURL) ? $objURL : new URL();
            if(!self::isLogged(self::$login_front)) {
                //neu nguoi dung chua login thi chuyen huong sang trang login
                $url = $objURL->cpage != "logout" ?
                //neu trang dang o khong phai la trang log out 
                self::$login_page_front."/".self::$referrer."/".$objURL->cpage.PAGE_EXT :
                    //dua den trang login, tren url them vao thong tin la den tu trang nao trong attribute referrer de sau khi login 
                    //redirect nguoi dung ve trang ho dang xem truoc do
                    self::$login_page_front.PAGE_EXT;
                    //neu trang dang o la trang log out thi dua den trang log in khong them gi vao url
                Helper::redirect($url);
            }    
        }
        
        public static function restrictAdmin() {
            if(!self::isLogged(self::$login_admin)) {
                Helper::redirect(self::$login_page_admin);
            }
        }
        
        public static function string2Hash($string = null) {
            if(!empty($string)) {
                return hash('sha512', $string);
            }
        }
        
        public static function getFullNameFront($id = null) {
            if(!empty($id)) {
                $objUser = new User();
                $user = $objUser->getUser($id);
                if(!empty($user)) {
                    return $user['first_name']." ".$user['last_name'];
                }
            }
        }
        
        public static function logout($case = null) {
            if (!empty($case)) {
                $_SESSION[$case] = null;
                $_SESSION[self::$_valid_login] = null;
                unset($_SESSION[$case]);
                unset($_SESSION[self::$_valid_login]);
            } else {
                session_destroy();
            }
        }
        
    }

?>