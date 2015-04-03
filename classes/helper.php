<?php
    class Helper {
        
        /*public static function getActive($page = null) {
            if(!empty($page)) {
                if(is_array($page)) {
                    $error = array();
                    foreach($page as $key => $value) {
                        if(URL::getParam($key) != $value) {
                            //get param key la de lay attribute cua key tren link
                            //sau do so sanh xem co giong voi gia tri duoc dua vao ham hay khong
                            //gia su dang o trong computer va IT, id tren link = 2
                            //ham nay se duoc goi moi khi liet ke ra mot category
                            //khi duoc goi ra, ham se so sanh id cua tung category voi id = 2 tren link
                            //neu khac thi se dua attribute vao array
                            array_push($error, $key);
                            //array push dua them mot phan tu vao lam phan tu cuoi cua mot mang
                            //o day ham nay dua key vao lam phan tu moi trong array error
                        }
                    }
                    return empty($error) ? " class=\"act\"" : null;
                    //neu array rong, id dang xet giong voi id tren link thi them class active vao link
                }
            }
            return $page = URL::cPage() ? " class=\"act\"" : null;
        }*/
        
        public static function encodeHTML($string, $case = 2) {
            switch($case) {
                case 1:
                return htmlentities($string, ENT_NOQUOTES, 'UTF-8', false);
                break;
                
                case 2:
                $pattern = '<([a-zA-Z0-9\.\, "\'_\/\-\+~=;:\(\)?&#%![\]@]+)>';
                $textMatches = preg_split('/' . $pattern . '/', $string);
                $textSanitised = array();
                foreach($textMatches as $key => $value) {
                    $textSanitised[$key] = htmlentities(html_entity_decode($value, ENT_QUOTES, 'UTF-8'), ENT_QUOTES, 'UTF-8');
                }
                
                foreach($textMatches as $key => $value) {
                    $string = str_replace($value, $textSanitised[$key], $string);
                }
                
                return $string;
                break;
            }
        }
        
        public static function getImgSize($image, $case) {
            if(is_file($image)) {
                //0 = width, 1 = height, 2=type, 3=attribute
                $size = getimagesize($image);
                return $size[$case];
            }
        }
        
        public static function shortenString($string, $len = 150) {
            if(strlen($string) > $len) {
                $string = trim(substr($string, 0, $len));  
                $string = substr($string, 0, strrpos($string, " ")) . "&hellip;";
                //strrpos tim vi tri cuoi cung cua mot ki tu nao do trong chuoi             
            } else {
                $string .= "&hellip;";
                //hellip la dau ba cham
            }
            return $string;
        }
        
        public static function redirect($url = null) {
            if(!empty($url)) {
                header("Location: {$url}");
                exit;
            }
        }
        
        public static function setDate($case = null, $date = null) {
            $date = empty($date) ? time() : strtotime($date);
            
            switch($case) {
                case 1:
                return date('d/m/Y', $date);
                break;
                
                case 2:
                return date('l, jS F Y, H:i:s', $date);
                break;
                
                case 3:
                return date('Y-m-d-H-i-s', $date);
                break;
                
                default:
                return date('Y-m-d H:i:s', $date);
                
            }
        }
        
        public static function cleanString($name = null) {
            if(!empty($name)) {
                return strtolower(preg_replace('/[^a-zA-Z0-9.]/', '-', $name));
            }
        }
        
        public static function clearString($string = null, $array = null) {
            if(!empty($string) && !self::isEmpty($array)) {
                $array = self::makeArray($array);
                foreach($array as $key => $value) {
                    $string = str_replace($value, '', $string);
                }
                return $string;
            }
            return $string;
        }
        
        public static function isEmpty($value = null) {
            return empty($value) && !is_numeric($value) ? true : false;
            //so 0 van bi ham empty coi la empty nhung thuc ra la van co gia tri nen phai la not empty
            //nen moi phai dung method rieng de kiem tra xem vua empty va vua khong phai la so 0
            //luc do moi tra ve la true
        }
        
        public static function makeArray($array = null) {
            return is_array($array) ? $array : array($array);
        }
        
        public static function alphaNumericalOnly($string = null) {
            if(!empty($string)) {
                return preg_replace("/[^A-Za-z0-9]/", '', $string); //nhung ki tu khong phai chu va so thi xoa het
            }
        }
        
        public static function json($input = null) {
            if(!empty($input)) {
                if(defined("JSON_UNESCAPED_UNICODE")) {
                    return json_encode($input, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);
                } else {
                    return json_encode($input, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);
                }
            }
        }
    }
?>