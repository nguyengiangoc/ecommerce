<?php
    require_once('../inc/config.php');
    if(isset($_POST['qty']) && isset($_POST['id'])) {
        $out = array();
        $id = $_POST['id'];
        $val = $_POST['qty'];
        
        $objCatalogue = new Catalogue();
        $product = $objCatalogue->getProduct($id);
        
        if(!empty($product)) {
            switch($val) {
                case 0:
                Session::removeItem($id);
                //neu nhu so luong duoc nhap vao la 0 thi coi nhu la xoa, thi hanh lenh remove
                break;
                default:
                Session::setItem($id, $val);
                //neu nhu khac 0 thi dat so luong vao qty tren session
            }
        }
    }

?>