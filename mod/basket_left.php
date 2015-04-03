<?php $objBasket = new Basket(); ?>
<h2>Your Basket</h2>
<dl id="basket_left">
    <dt>No. of items:</dt>
    <dd class="bl_ti"><span><?php echo $objBasket->_number_of_items; ?></span></dd>
    <dt>Sub-total:</dt>
    <dd class="bl_st">&pound;<span><?php echo $objBasket->_sub_total; ?></span></dd>
    <dt>Total weight:</dt>
    <dd class="bl_we"><span><?php echo $objBasket->_weight; ?></span></dd>
</dl>
<div class="dev br_td">&#160;</div>
<p><a href="/ecommerce/<?php echo $this->objURL->href('basket'); ?>">View basket</a> | <a href="/ecommerce/<?php echo $this->objURL->href('checkout'); ?>">Checkout</a></p>
<div class="dev br_td">&#160;</div>