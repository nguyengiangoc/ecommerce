<?php
    require_once("../inc/config.php");
    $objBasket = new Basket();
    $out = array();
    $out['bl_ti'] = $objBasket->_number_of_items;
    $out['bl_st'] = number_format($objBasket->_sub_total, 2);
    $out['bl_we'] = $objBasket->_weight;
    echo Helper::json($out);
?>