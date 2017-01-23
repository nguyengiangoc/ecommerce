<?php

    use SSD\Catalogue;
    use SSD\Session;
    use SSD\Helper;
    use SSD\Basket;

    require_once('inc/config.php');
    if(isset($_POST['job']) && isset($_POST['id'])) {
        $out = array();
        $job = $_POST['job'];
        $id = $_POST['id'];
        $objCatalogue = new Catalogue();
        $product = $objCatalogue->getProduct($id);
        
        if(!empty($product)) {
            switch($job) {
                case 0:
                Session::removeItem($id);
                $out['job'] = 1;
                //$out = 1;
                break;
                
                case 1:
                Session::setItem($id);
                $out['job'] = 0;
                //$out = 0;
                break;
            }
            
            $objBasket = new Basket();
            
            $out['replace_values'] = array();
            
            $out['replace_values']['.bl_ti'] = $objBasket->number_of_items;
            $out['replace_values']['.bl_st'] = $this->objCurrency->display(number_format($objBasket->sub_total, 2));
            $out['replace_values']['.bl_we'] = $objBasket->weight;
            
            
            //echo json_encode($out);
            echo Helper::json($out) ;
        }
    }
?>