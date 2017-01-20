<?php

    use SSD\Form;
    use SSD\Catalogue;
    use SSD\Session;
    use SSD\Basket;
    use SSD\Helper;
    use SSD\Plugin;
    
    $objForm = new Form();
    
    $array = $objForm->getPostArray();
    
    if(!empty($array)) {
     
        $objCatalogue = new Catalogue();
        
        foreach($array as $key => $value) {
            
            $identity = explode('-', $key);
            
            if(count($identity) == 2 && $identity[0] == 'qty') {
                
                $product = $objCatalogue->getProduct($identity[1]);
                
                if(empty($product)) {
                    continue;
                }
                
                if(empty($value)) {
                    
                    Session::removeItem($product['id']);   
                    
                } else {
                    
                    
                    
                }
                
            }
            
        }  
        
    } 

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