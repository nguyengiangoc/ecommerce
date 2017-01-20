<?php

    use SSD\Plugin;

    $action = $this->objURL->get('action');

    require_once("_header.php");   
?>
    <h1>Basket</h1>
    <div id="big_basket">
        <?php echo Plugin::get('front'.DS.'basket_view', array('objURL' => $this->objURL, 'objCurrency' => $this->objCurrency)); ?>
    </div>
<?php  

    require_once("_footer.php");
    
?>