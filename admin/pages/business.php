<?php

    use SSD\Login;
    
    $objLogin = new Login;
    $objLogin->restrictAdmin();
	
	$action = $this->objURL->get('action');
	
	switch($action) {
    	case 'edited':
    	require_once('business'.DS.'edited.php');
    	break;
    	
    	case 'edited-failed':
    	require_once('business'.DS.'edited-failed.php');
    	break;
    	
    	default:
    	require_once('business'.DS.'edit.php');
	}
 ?>