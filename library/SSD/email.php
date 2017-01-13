<?php 

    namespace SSD;
    
    use Zend\Mail\Messages;
    use Zend\Mail\Transport\Sendmail as SendmailTransport;
    use Zend\Mail\Transport\Smtp as SmtpTransport;
    use Zend\Mail\Transport\SmtpOptions;
    use Zend\Mime\Message as MimeMessage;
    use Zend\Mime\Part as MimePart;
    
    class Email {
        
        public $objURL;
        
        private $_objMessage;
        private $_objTransport;
        
        private $_useSmtp = SMTP_USE;
        private $_smtpHost = SMTP_HOST;
        private $_smtpUsername = SMTP_USERNAME;
        private $_smtpPassword = SMTP_PASSWORD;
        private $_smtpPort = SMTP_PORT;
        private $_smtpSsl = SMTP_SSL;
        
        const EMAIL_ADMIN = 'nguyen.gia.ngoc.2710@gmail.com';
        const NAME_ADMIN = 'SSD Tutorial';
        
        public function __construct($objURL = null) {
            $this->objURL = is_object($objURL) ? $objURL : new URL();
            
            $this->_objMessage = new Message();
            
            if($this->_useSmtp) {
                
                $this->_objTransport = new SmtpTransport();
                
                $options = new SmtpOptions(
                    array(
                        'host' => $this->_smtpHost,
                        'port' => $this->_smtpPort,
                        'connection_class' => 'login',
                        'connection_config' => array(
                            'username' => $this->_smtpUsername,
                            'password' => $this->_smtpPassword
                        )
                    )
                );
                
                if($this->_smtpSsl) {
                    $options['connection_config']['ssl'] = $this->_smtpSsl;
                }
                
                $this->_objTransport->setOptions($options);
                
                
            } else {
                
                $this->_objTransport = new SendmailTransport();
                
            }
            
            
            
        }
        
        public function process($case = null, $array = null) {
            if(!empty($case)) {
                switch($case) {
                    case 1:
                    $link = "<a href=\"".SITE_URL.$this->objURL->href('activate', array('code', $array['hash']))."\">".SITE_URL.$this->objURL->href('activate', array('code', $array['hash']))."</a>";
                    $array['link'] = $link;
                    
                    $this->_objMessage->addTo($array['email'], $array['first_name'].' '.$array['last_name']);
                    $this->_objMessage->addFrom(self::EMAIL_ADMIN, self::NAME_ADMIN);
                    $this->_objMessage->setSubject('Activate your account');
                    $this->_objMessage->setBody($this->_setHtmlBody($this->fetchEmail($case, $array)));
                    
                    break;                    
                }
                
                
                $this->_objTransport->send($this->_objMessage);
                return true;
                
            }
        }
        
        private function _setHtmlBody($message = null) {
            
            $objMimePart = new MimePart($message);
            $objMimePart->type = "text/text";
            
            $objMimeMessage = new MimeMessage();
            $objMimeMessage->addPart($objMimePart);
            
            return $objMimeMessage;
            
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