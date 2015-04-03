<?php
    $code = $this->objURL->get('code');
    if(!empty($code)) {
        $objUser = new User();
        $user = $objUser->getUserByHash($code);
        if(!empty($user)) {
            if($user['active'] == 0) {
                if($objUser->makeActive($user['id'])) {
                    $mess = "<h1>Thank you</h1><p>Your account has been activated<br/ >You can now log in and continue with your order.</p>";
                } else {
                    $mess = "<h1>Activation unsuccessful.</h1><p>There has been a problem activating your account<br />Please contact admin.</p>";
                }
            } else {
                $mess = "<h1>Account already activated</h1><p>This account has already been activated.</p>";
            }
        } else {
            Helper::redirect("/ecommerce/".$this->objURl->href('error'));
        }
        require_once("_header.php");
        echo $mess;
        require_once("_footer.php");
    } else {
        Helper::redirect("/ecommerce/".$this->objURl->href('error'));
    }
    
?>