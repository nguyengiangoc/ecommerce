<?php 

    use SSD\Login;

    Login::restrictAdmin();
    $action = $this->objURL->get('action');
    switch($action) {
        case 'edit':
        require_once('orders'.DS.'edit.php');
        break;
        case 'edited':
        require_once('orders'.DS.'edited.php');
        break;
        case 'edited-failed':
        require_once('orders'.DS.'edited-failed.php');
        break;
        case 'invoice':
        require_once('orders'.DS.'invoice.php');
        break;
        case 'remove':
        require_once('orders'.DS.'remove.php');
        break;
        default:
        require_once('orders'.DS.'list.php');
    }
?>