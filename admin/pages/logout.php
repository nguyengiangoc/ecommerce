<?php

    use SSD\Login;    
    Login::logout(Login::$login_admin);
    $objLogin = new Login;
    $objLogin->restrictAdmin();

?>