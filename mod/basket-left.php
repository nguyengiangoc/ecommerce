<?php

    use SSD\Plugin;
    
    echo Plugin::get('front'.DS.'basket_left', array('objURL' => $this->objURL, 'objCurrency' => $this->objCurrency));

?>