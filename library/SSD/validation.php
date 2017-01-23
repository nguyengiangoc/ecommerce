<?php

    namespace SSD;

    class Validation {
        private $objForm;
        
        private $_errors = array();
        
        public $errorsMessages = array();
        
        public $message = array(
            "first_name" => "Please provide your first name",
            "last_name" => "Please provide your last name",
            "address_1" => "Please provide the first line of your address",
            "address_2" => "Please provide the second line of your address",
            "town" => "Please provide your town name",
            "county" => "Please provide your conty name",
            "post_code" => "Please provide your post code",
            "country" => "Please select your country",
            
            "same_address" => "Please select one option",
            "ship_address_1" => "Please provide the first line of the shipping address",
            "ship_address_2" => "Please provide the second line of the shipping address",
            "ship_town" => "Please provide the shipping town name",
            "ship_county" => "Please provide the shipping conty name",
            "ship_post_code" => "Please provide the shipping post code",
            "ship_country" => "Please select the shipping country",
            
            "email" => "Please provide your valid email address",
            "login" => "Username and/or password are incorrect",
            "password" => "Please choose your password",
            "confirm_password" => "Please confirm your password",
            "password_mismatch" => "Passwords did not match",
            "email_duplicate" => "This email has already been registered",
            "category" => "Please select one category",
            "name" => "Please provide a name",
            "description" => "Please provide a description",
            "price" => "Please provide a price",
            "name_duplicate" => "This name is already taken",
            "email_inactive" => "This email is not activated",
            "identity" => "Please provide the identity",
            "duplicate_identity" => "This identity is already taken",
            "meta_title" => "Please provide the meta title",
            "meta_description" => "Please provide the meta description",
            "meta_keywords" => "Please provide the meta keywords",
            
            "weight" => "Please provide the weight",
            "cost" => "Please provide the cost"
        );
        
        public $expected = array();
        //de cho vao nhung~ thanh phan trong form can duoc dem vao xu ly
        
        public $required = array();
        
        public $special = array();
        
        public $post = array();
        
        public $post_remove = array();
        
        public $post_format = array();
        
        public function __construct($objForm = null) {
            $this->objForm = is_object($objForm) ? $objForm : new Form();
        }
        
        public function process() {
            if($this->objForm->isPost()) {
                //neu da co cac thanh phan trong array post va trong array required co ten cac field can phai dien
                $this->post = !empty($this->post) ? $this->post : $this->objForm->getPostArray($this->expected);
                //chi lay tu array post cac thanh phan co key nam trong array expected 
                //lay vao trong array post cua objValid
                if(!empty($this->post)) {
                    foreach($this->post as $key => $value) {
                    //luc nay da lay xong cac thanh phan trong array post
                        $this->check($key, $value); 
                        //tien hanh kiem tra tung thanh phan trong array post
                        //thanh phan email nam trong array special
                        //nen khi vong lap chay toi thanh phan email se chay quay ham checkSpecial, tuc la chay qua ham isEmail\
                        //neu ten key co trong array required nhung gia tri lay tu post la rong thi phai bao loi
                        //cho vao array error
                    }
                }
            }
        }
        
        public function check($key, $value) {
            if(!empty($this->special) && array_key_exists($key, $this->special)) {
                $this->checkSpecial($key, $value);
            } else {
                if(in_array($key, $this->required) && Helper::isEmpty($value)) {
                //neu 
                    $this->add2Errors($key);
                }
            }
        }
        
        public function add2Errors($key = null, $value = null) {
            if(!empty($key)) {
                $this->_errors[] = $key; //them vao thanh phan tiep theo, index la so, khong phai co key rieng
                if(!empty($value)) {
                    $this->errorsMessages['valid_'.$key] = $this->wrapWarn($value); 
                    //value dung de tao re validation message rieng khac voi message da co san trong array cua object
                } elseif (array_key_exists($key, $this->message)) {
                    $this->errorsMessages['valid_'.$key] = $this->wrapWarn($this->message[$key]);
                }
            }
            
        }
        
        public function checkSpecial($key, $value) {
            switch($this->special[$key]) {
                case('email'):
                if(!$this->isEmail($value)) {
                    $this->add2Errors($key);
                }
                break;
            }
        }
        
        public function isEmail($email = null) {
            if(!empty($email)) {
                $result = filter_var($email, FILTER_VALIDATE_EMAIL);
                return !$result ? false : true;
            }
            return false;
        }
        
        public function isValid($array = null) {
            //phai cho ham nay chay thi process moi duoc chay
            //sau khi process chay xong thi se dua het error vao trong array error
            if(!empty($array)) {
                $this->post = $array;
            }
            $this->process();
            if (empty($this->_errors) && !empty($this->post)) {
                //remove all unwanted fields
                if(!empty($this->post_remove)) {
                    //neu co thanh phan nao trong post remove, tuc la thanh phan nay la mot field trong form nhung khi xu ly khong can dung den
                    //thi xoa ra khoi array post
                    foreach($this->post_remove as $value) {
                        unset($this->post[$value]);
                    }
                }
                //format all required field
                if(!empty($this->post_format)) {
                    foreach($this->post_format as $key => $value) {
                        $this->format($key, $value);
                    }
                }
                return true;
            }
            return false;
        }
        
        public function format($key, $value) {
            switch($value) {
                case 'password':
                $this->post[$key] = Login::string2hash($this->post[$key]);
                break;
            }
        }
        
        public function validate($key) {
            if(!empty($this->_errors) && in_array($key, $this->_errors)) {
                return $this->wrapWarn($this->message[$key]);
            }
            //method nay de hien thi loi~ cu the cua mot field
            //duoc goi ra ngay truoc field do trong form
        }
        
        
        public function wrapWarn($mess = null) {
            if(!empty($mess)) {
                return "<span class=\"warn\">{$mess}</span>";
            }
        }
        
        
        
        
        
        
        
    }
?>