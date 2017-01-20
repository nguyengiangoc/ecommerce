<?php

    //echo  $_SERVER['REQUEST_URI'];

    

    date_default_timezone_set('Europe/London');
    
    
    
    if(!isset($_SESSION)) {
        session_start();
    }
    
    //0 => production, 1 => development
    
    defined('ENVIRONMENT')
        || define('ENVIRONMENT', 1);
        
    if(ENVIRONMENT == 1) {
        ini_set('display_error', 'On');
        error_reporting(-1);
    } else {
        ini_set('display_error', 'Off');
        error_reporting(0);
    }
    
    
    
    defined("DS")
        || define("DS", DIRECTORY_SEPARATOR);
   

    require_once('inc'.DS.'config.php');
    require_once('SSD'.DS.'SSDException.php');
    require_once('SSD'.DS.'autoloader.php');
    
    
    
    
    
    set_exception_handler(array('SSD\SSDException', 'getOutput'));
    spl_autoload_register(array('SSD\autoloader', 'load'));

    
    
    
    use SSD\Core;
    
    
    
    
    $core = new Core();
    
    
    
    $core->run();
    
?>  
            