<?php 
    require_once('PHPMailer-master/PHPMailer.php');
    
    class Email {
        
        public $objURL;
        
        public function __construct($objURL = null) {
            $this->objURL = is_object($objURL) ? $objURL : new URL();
            $this->objMailer = new PHPMailer();
            $this->objMailer->isSMTP();
            $this->objMailer->SMTPAuth = true;
            $this->objMailer->SMTPKeepAlive = true;
            $this->objMailer->Host = "smtp.gmail.com";
            $this->objMailer->Port = 25;
            $this->objMailer->Username = "dummyemailaddress@gmail.com";
            $this->objMailer->Password = "password";
            $this->objMailer->SetFrom("dummyemailaddress@gmail.com", "Sebastian Sulinski");
            $this->objMailer->AddReplyTo("dummyemailaddress@gmail.com", "Sebastian Sulinski");
            
            
        }
        
        public function process($case = null, $array = null) {
            if(!empty($case)) {
                switch($case) {
                    case 1:
                    $link = "<a href=\"".SITE_URL.$this->objURL->href('activate', array('code', $array['hash']))."\">".SITE_URL.$this->objURL->href('activate', array('code', $array['hash']))."</a>";
                    $array['link'] = $link;
                    $this->objMailer->Subject = "Activate your account";
                    $this->objMailer->msgHTML($this->fetchEmail($case, $array));
                    $this->objMailer->addAddress($array['email'], $array['first_name']." ".$array['last_name']);
                    break;                    
                }
                if($this->objMailer->send()) {
                    $this->objMailer->clearAddresses();
                    return true;
                }
                return false;
            }
        }
        
        public function fetchEmail($case = null, $array = null) {
            if(!empty($case)) {
                if(!empty($array)) {
                    foreach($array as $key => $value) {
                        ${$key} = $value;
                    }
                }
                ob_start();
                //Without output buffering (the default), your HTML is sent to the browser in pieces as PHP processes through your script. 
                //With output buffering, your HTML is stored in a variable and sent to the browser as one piece at the end of your script.
                require_once(EMAILS_DIR.DS.$case.".php");
                $out = ob_get_clean();
                return $this->wrapEmail($out);
            }
        }
        
        public function wrapEmail($content = null) {
            if(!empty($content)) {
                return "<div style=\"font-family: Arial, Verdana, Sans-setif;font-size:12px;color:#333;line-height:21px;\">{$content}</div>";
            }
        }
        
    }

?>