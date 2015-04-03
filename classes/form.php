<?php
    class Form {
        
        public function isPost($field = null) {
            if(!empty($field)) {
                if(isset($_POST[$field])) {
                    return true;
                } //kiem tra xem co mot index nao giong voi bien field duoc nhap vao trong post hay khong
                return false;
            } else {
                if(!empty($_POST)) {
                    return true;
                } //kiem tra xem co mot index nao do duoc set trong post hay chua
                return false;
            }
        }
        
        public function getPost($field = null) {
            if(!empty($field)) {
                return $this->isPost($field) ? strip_tags($_POST[$field]) : null;
            }
        }
        
        public function stickySelect($field, $value, $default = null) {
            if ($this->isPost($field) && $this->getPost($field) == $value) {
                return " selected=\"selected\"";
                //neu nhu trong array post co thanh phan nay va id dang xet giong voi value cua thanh phan da chon
                //cho no lam select 
            } else {
                return !empty($default) && $default == $value ? " selected=\"selected\"" : null; 
                //neu so nhap vao cho bien record bang voi id cua quoc gia dang xet thi cho quoc gia do la selected, duoc chon mac dinh
            }
        }
        
        public function getPostArray($expected = null) {
            $out = array();
            if($this->isPost()) {
                foreach($_POST as $key => $value) {
                    if(!empty($expected)) {
                        if(in_array($key, $expected)) {
                            //tim xem trong array post co cac key giong nhu cac key duoc dua vao tu bien expected hay khong
                            $out[$key] = strip_tags($value);
                            //neu co thi cho vao bien out de return
                        }
                    } else {
                        $out[$key] = strip_tags($value);
                    }
                }
            }
            return $out;
        }
        
        public function stickyText($field, $value = null) {
            if($this->isPost($field)) {
                return stripslashes($this->getPost($field));
            } else {
                return !empty($value) ? $value : null;
            }
        }
        
        public function stickyRadio($field = null, $value = null, $data = null) {
            $post = $this->getPost($field);
            if(!Helper::isEmpty($post)) {
                if($post == $value) {
                    return ' checked="checked"';
                }
            } else {
                return !Helper::isEmpty($data) && $value == $data ? ' checked ="checked"' : null;
            }
        }
        
        public function stickyRemoveClass ($field = null, $value = null, $data= null, $class = null, $single = false) {
            $post = $this->getPost($field);
            if(!Helper::isEmpty($post)) {
                if($post != $value) {
                    return $single ? ' class="'.$class.'"' : ' '.$class;
                }
            } else {
                if($value != $data) {
                    return $single ? ' class="'.$class.'"' : ' '.$class;
                }
            }
        }
        
        public function getCountriesSelect($record = null, $name = 'country',$selectOption = false) {
            //record la so id cua quoc gia muon dc chon lam mac dinh
            $objCountry = new Country();
            $countries = $objCountry->getCountries();
            if(!empty($countries)) {
                $out = "<select name=\"{$name}\" id=\"{$name}\" class=\"sel\">";
                if(empty($record) || $selectOption == true) {
                    $out .= "<option value=\"\">Select one&hellip;</option>";
                }
                //neu nhu khong nhap so vao bien record thi se cho hang dau tien la select one, neu co nhap record thi da co gia tri mac dinh
                //khong can hang select one
                //doi voi shipping thi cai record co the co du lieu la shipping country tu lan order truoc
                //nhung lan order sau co the ship ve cung country voi billing
                //nen van can de select one
                foreach ($countries as $country) {
                    $out .= "<option value=\"".$country['id']."\"".$this->stickySelect($name, $country['id'], $record).">".$country['name']."</option>";
                }
                $out .= "</select>";
                return $out;
            }
        }
        
    }
?>
