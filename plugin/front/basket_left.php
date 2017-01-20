<?php 
    use SSD\Basket;
    $objBasket = new Basket(); 
?>
<h2>Your Basket</h2>
<dl id="basket_left">
    <dt>No. of items:</dt>
    <dd class="bl_ti"><span><?php echo $objBasket->number_of_items; ?></span></dd>
    <dt>Sub-total:</dt>
    <dd class="bl_st"><span><?php echo $data['objCurrency']->display($objBasket->sub_total);  ?></span></dd>
    <dt>Total weight:</dt>
    <dd class="bl_we"><span><?php echo $objBasket->weight; ?></span></dd>
</dl>
<div class="dev br_td">&#160;</div>
<p><a href="<?php echo BASE_PATH.'/'.$data['objURL']->href('basket'); ?>">View basket</a> | <a href="<?php echo BASE_PATH.'/'.$data['objURL']->href('checkout'); ?>">Checkout</a></p>
<div class="dev br_td">&#160;</div>