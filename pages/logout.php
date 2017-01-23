<?php

    use SSD\Login;

    Login::logout(Login::$login_front);
    $objLogin = new Login;
    $objLogin->restrictFront($this->objURL);
?>