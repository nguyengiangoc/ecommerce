<?php

    namespace SSD;

    class Session {
        public static function setItem($id, $qty = 1) {
            $_SESSION['basket'][$id]['qty'] = $qty;
        }
        
        public static function removeItem($id, $qty = null) {
            if ($qty != null && $qty < $_SESSION['basket'][$id]['qty']) {
                $_SESSION['basket'][$id]['qty'] = ($_SESSION['basket'][$id]['qty'] - $qty);
                //trnog truong hop 1 mat hang duoc dat voi so luong nhieu hon 1 va so luong can phai xoa di it hon so luong da dat thi tru di
            } else {
                $_SESSION['basket'][$id] = null;
                //trong truong hop qty = null thi so luong da dat la 1, vi vay chi can dua ve 0
                unset($_SESSION['basket'][$id]);
            }
        }
        
        public static function getSession($name = null) {
            if(!empty($name)) {
                return isset($_SESSION[$name]) ? $_SESSION[$name] : null;
            }
        }
        
        public static function setSession($name = null, $value = null) {
            if(!empty($name) && !empty($value)) {
                $_SESSION[$name] = $value;
            }
        }
        
        public static function clear($id = null) {
            if(!empty($id)) {
                if(isset($_SESSION[$id])) {
                    $_SESSION[$id] = null;
                    unset($_SESSION[$id]);
                }
            } else {
                session_destroy();
            }
        }
    }
?>