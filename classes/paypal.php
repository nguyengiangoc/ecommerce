<?php
    class PayPal {
        public $objURL;
        private $_environment = 'sandbox';
        private $_url_production = 'https://www.paypal.com/cgi-bin/webscr' ;
        private $_url_sandbox = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
        private $_url;
        private $_cmd;
        //_xclick = buy now button, _cart = basket
        private $_products = array();
        private $_fields = array();
        private $_business = 'nguyen.gia.ngoc.2710-facilitator@gmail.com ';
        private $_page_style = null;
        private $_return;
        private $_cancel_payment;
        private $_notify_url;
        private $_currency_code = 'GBP';
        public $_tax_cart = 0;
        public $_tax = 0;
        public $_shipping = 0;
        
        
        //pre-populate check out pages, can phai dien het cac muc can dien, neu khong se khong hoat dong
        public $_populate = array();
        private $_ipn_data = array();
        private $_log_file = null;
        private $_ipn_result;
        
        public function __construct($objURL = null, $cmd = '_cart') {
            $this->objURL = is_object($objURL) ? $objURL : new URL();
            $this->_url = $this->_environment == 'sandbox' ? $this->_url_sandbox : $this->_url_production;
            $this->_cmd = $cmd;
            //$this->_return = SITE_URL.$this->objURL->href('return'); //sau khi thanh toan thi dua nguoi dung ve trang nay
            $this->_cancel_payment = SITE_URL.$this->objURL->href('cancel');
            $this->_notify_url = SITE_URL.$this->objURL->href('ipn'); //ipn = instant payment navigation
            $this->_log_file = ROOT_PATH.DS."log".DS."ipn.log";
        }
        
        public function addProduct($number, $name, $price = 0, $qty = 1) {
            switch($this->_cmd) {
                case '_cart':
                $id = count($this->_products) + 1;
                //dem xem trong array products da co san bao nhieu san pham 
                //cong them 1 se ra so thu tu cua san pham tiep theo
                $this->_products[$id]['item_number_'.$id] = $number;
                $this->_products[$id]['item_name_'.$id] = $name;
                $this->_products[$id]['amount_'.$id] = $price;
                $this->_products[$id]['quantity_'.$id] = $qty;
                break;
                case '_xclick':
                if(empty($this->_products)) {
                    //neu bam vao nut buy now thi chi mua duoc 1 san pham
                    $this->_products[0]['item_number'] = $number;
                    $this->_products[0]['item_name'] = $name;
                    $this->_products[0]['amount'] = $price;
                    $this->_products[0]['quantity'] = $qty;
                }
                break;
            }
        }
        
        private function addField($name = null, $value = null) {
            if(!empty($name) && !empty($value)) {
                $field = "<input type=\"hidden\" name=\"".$name."\" value=\"".$value."\" />";
                $this->_fields[] = $field;
            }
        }
        
        public function render() {
            $out = "<form action=\"".$this->_url."\" method=\"post\" id=\"frm_paypal\">".$this->getFields()."<input type=\"submit\" value=\"Submit\" /></form>";
            //chuyen het nhung thanh phan trong field thanh input cua form
            //sau do dua sang cho sandbox cua paypal xu ly
            return $out;
        }
        
        public function getFields() {
            $this->processFields();
            if(!empty($this->_fields)) {
                return implode("", $this->_fields);
            }
        }
        
        public function processFields() {
            $this->standardFields();
            if(!empty($this->_products)) {
                foreach($this->_products as $product) {
                    foreach($product as $key => $value) {
                        $this->addField($key, $value);
                    }
                }
            }
            $this->prePopulate();
        }
        
        private function standardFields() {
            $this->addField('cmd', $this->_cmd);
            $this->addField('business', $this->_business);
            if($this->_page_style != null) {
                $this->addField('page_style', $this->_page_style);
            }
            $this->addField('return', $this->_return);
            $this->addField('notify_url', $this->_notify_url);
            $this->addField('cancel_payment', $this->_cancel_payment);
            $this->addField('currency_code', $this->_currency_code);
            $this->addField('rm', 2); //neu la 2 thi cach gui du lieu se la POST
            if(!empty($this->_shipping)) {
                $this->addField('handling_cart', $this->_shipping);
            }
            switch($this->_cmd) {
                case '_cart':
                if($this->_tax_cart != 0) {
                    $this->addField('tax_cart', $this->_tax_cart);
                }
                $this->addField('upload', 1);
                break;
                case '_xclick':
                if($this->_tax_cart != 0) {
                    $this->addField('tax', $this->_tax);
                }
                break;
            }
        }
        
        private function prePopulate() {
            if(!empty($this->_populate)) {
                foreach($this->_populate as $key => $value) {
                    $this->addField($key, $value);
                }
            }
        }
        
        public function run($transaction_token = null) {
            if(!empty($transaction_token)) {
                $this->_return = SITE_URL.'/'.$this->objURL->href('return', array('token', $transaction_token));
                $this->addField('custom', $transaction_token);
            }
            return $this->render();
        }
        
        private function validateIPN() {
            $hostname = gethostbyaddr($_SERVER['REMOTE_ADDR']);
            if(!preg_match('/paypal\.com$/', $hostname)) {
                return false;
            }
            $objForm = new Form();
            $this->_ipn_data = $objForm->getPostArray();
            if(!empty($this->_ipn_data) && array_key_exists('receiver_email', $this->_ipn_data) 
            && strtolower($this->_ipn_data['receiver_email']) != strtolower($this->_business)) {
                return false;
            }
            return true;
        }
        
        private function sendCurl() {
            $response = $this->getReturnParams();
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $this->_url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $response);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/x-www-form-urlencoded", "Content-Length: ".strlen($response)));
            curl_setopt($ch, CURLOPT_VERBOSE, 1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);
            
            $this->_ipn_result = curl_exec($ch);
            curl_close($ch);
        }
        
        private function getReturnParams() {
            //dua du lieu nguoc ve cho paypal
            $out = array('cmd=_notify-validate');
            if(!empty($this->_ipn_data)) {
                foreach($this->_ipn_data as $key => $value) {
                    $value = function_exists('get_magic_quotes_gpc') ? urlencode(stripslashes($value)) : urlencode($value);
                    $out[] = "{$key}={$value}";
                }
            }
            return implode("&", $out);
        }
        
        public function ipn() {
            if($this->validateIPN()) {
                $this->sendCurl();
                if(strcmp($this->_ipn_result, "VERIFIED") == 0) {
                //so sanh giua hai string, ipn result va verified
                    $objOrder = new Order();
                    if(!empty($this->_ipn_data)) {
                        $objOrder->approve($this->_ipn_data, $this->_ipn_result);
                    }
                }
                $this->saveLog();
            }
        }
        
        private function saveLog() {
            if($this->_log_file != null) {
                $out = array();
                $out[] = "Date: ".date('d/m/Y H:i:s', time());
                $out[] = "Status: ".$this->_ipn_result;
                $out[] = "IPN Response:\n\n";
                if(!empty($this->_ipn_data)) {
                    foreach($this->_ipn_data as $key => $value) {
                        $out[] = "{$key} : {$value}";
                    }
                }
                $fp = fopen($this->_log_file, 'a');
                $text = implode("\n, $out");
                $text .= "\n\n------------\n\n";
                fwrite($fp, $text);
                fclose($fp);
            }
        }
               
    }
?>