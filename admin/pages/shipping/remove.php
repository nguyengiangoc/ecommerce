<?php

    use \Exception;
    use SSD\Helper;
    
    if($objShipping->removeType($type['id'])) {
        echo Helper::json(array('error' => false));
        
    } else {
        throw new Exception('Record can not be removed');
    }
    
?>