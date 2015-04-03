<?php 
    $id = $this->objURL->get('id');
    if(!empty($id)) {
        
        $objOrder = new Order();
        $order = $objOrder->getOrder($id);
        
        if(!empty($order)) {
                   
            $objForm = new Form();
            $objValid = new Validation($objForm);
            $objUser = new User();
            $user = $objUser->getUser($order['client']);
            //$objCountry = new Country();
            $objCatalogue = new Catalogue();
            $items = $objOrder->getOrderItems($id);
            $status = $objOrder->getStatuses();
            
            if($objForm->isPost('status')) {
                $objValid->_expected = array('status', 'notes');
                $objValid->_required = array('status');
                
                $vars = $objForm->getPostArray($objValid->_expected);
                
                if($objValid->isValid()) {
                    if($objOrder->updateOrder($id, $vars)) {
                        Helper::redirect('/ecommerce/'.$this->objURL->getCurrent(array('action', 'id'), false, array('action', 'edited')));
                    } else {
                        Helper::redirect('/ecommerce/'.$this->objURL->getCurrent(array('action', 'id'), false, array('action', 'edited-failed')));
                    }
                }
            }
            require_once('_header.php'); 
?>
        <h1>Orders :: View</h1>
        <form action="" method="post">
            <table cellpadding="0" cellspacing="0" border="0" class="tbl_insert">
                <tr>
                    <th>Date</th>
                    <td colspan="4"><?php echo Helper::setDate(2, $order['date']); ?></td>
                </tr>
                <tr>
                    <th>Order no:</th>
                    <td colspan="4"><?php echo $order['id']; ?></td>
                </tr>
                <?php if (!empty($items)) { ?>
                    <tr>
                        <th rowspan="<?php echo count($items) + 1; ?>">Items:</th>
                        <td class="col_5">Id</td>
                        <td>Item</td>
                        <td class="col_5 ta_r">Qty</td>
                        <td class="col_15 ta_r">Amount</td>
                    </tr>
                    <?php 
                        foreach($items as $item) { 
                            $product = $objCatalogue->getProduct($item['product']);
                    ?>
                        <tr>
                            <td><?php echo $product['id']; ?></td>
                            <td><?php echo Helper::encodeHTML($product['name']); ?></td>
                            <td class="ta_r"><?php $item['qty']; ?></td>
                            <td class="ta_r">&pound;<?php echo number_format(($item['price'] * $item['qty']), 2); ?></td>
                        </tr>
                    <?php } ?>
                <?php } ?>
                <tr>
                    <th>Shipping:</th>
                    <td colspan="3"><?php echo Helper::encodeHTML($order['shipping_type']); ?></td>
                    
                    <td>&pound;<?php echo number_format($order['shipping_cost'], 2); ?></td>
                </tr>
                <tr>
                    <th>Sub-total:</th>
                    <td colspan="4" class="ta_r">&pound;<?php echo number_format($order['subtotal'], 2); ?></td>
                </tr>
                <tr>
                    <th>VAT (<?php echo $order['vat_rate']; ?>%):</th>
                    <td colspan="4" class="ta_r">&pound;<?php echo number_format($order['vat'], 2); ?></td>
                </tr>
                <tr>
                    <th>Total:</th>
                    <td colspan="4" class="ta_r"><strong>&pound;<?php echo number_format($order['total'], 2); ?></strong></td>
                </tr>
                <tr>
                    <th>Client:</th>
                    <td colspan="4">
                        <?php 
                            echo '<p>';
                            echo Helper::encodeHTML($order['full_name']).'<br />';
                            echo '<a href="mailto:'.$user['email'].'">'.$user['email'].'</a>';
                            echo '</p>';
                        ?>
                    </td>
                </tr>
                <tr>
                    <th>Billing:</th>
                    <td colspan="4">
                        <?php
                            echo '<p>';
                            echo Helper::encodeHTML($order['address']).'<br />';
                            echo Helper::encodeHTML($order['town']).'<br />';
                            echo Helper::encodeHTML($order['county']).'<br />';
                            echo Helper::encodeHTML($order['post_code']).'<br />';
                            echo Helper::encodeHTML($order['country_name']);
                            echo '</p>';
                        ?>
                    </td>
                </tr>
                <tr>
                    <th>Shipping:</th>
                    <td colspan="4">
                        <?php
                            echo '<p>';
                            echo Helper::encodeHTML($order['shipping_address']).'<br />';
                            echo Helper::encodeHTML($order['shipping_town']).'<br />';
                            echo Helper::encodeHTML($order['shipping_county']).'<br />';
                            echo Helper::encodeHTML($order['shipping_post_code']).'<br />';
                            echo Helper::encodeHTML($order['shipping_country_name']);
                            echo '</p>';
                        ?>
                    </td>
                </tr>
                <tr>
                    <th>PP status:</th>
                    <td colspan="4"><?php echo !empty($order['payment_status']) ? Helper::encodeHTML($order['payment_status']) : "Pending"; ?></td>
                </tr>
                <tr>
                    <th><label for="status">Order status:</label></th>
                    <td colspan="4">
                        <?php 
                            $objValid->validate('status'); 
                            if(!empty($status)) { ?>
                                <select name="status" id="status" class="sel">
                                    <?php foreach($status as $stat) { ?>
                                        <option value="<?php echo $stat['id']; ?>" <?php echo $objForm->stickySelect('status', $stat['id'], $order['status']); ?>><?php echo Helper::encodeHTML($stat['name']); ?></option>
                                    <?php } ?>
                                </select>
                        <?php } ?>
                    </td>
                </tr>
                <tr>
                    <th><label for="note">Notes:</label></th>
                    <td colspan="4"><textarea name="notes" id="notes" cols="" rows="" class="tar"><?php echo $objForm->stickyText('notes', $order['notes']); ?></textarea></td>
                </tr>
                <tr>
                    <th>&nbsp;</th>
                    <td colspan="4">
                        <div class="sbm sbm_blue fl_r">
                            <a href="/ecommerce/<?php echo $this->objURL->getCurrent(array('action'), false, array('action', 'invoice'));?>" class="btn" target="_blank">Invoice</a>
                        </div>
                        <div class="sbm sbm_blue fl_l mr_r4">
                            <a href="/ecommerce/<?php echo $this->objURL->getCurrent(array('action', 'id')); ?>" class="btn">Go back</a>
                        </div>
                        <label for="btn_update" class="sbm sbm_blue fl_l"><input type="submit" id="btn_update" class="btn" value="Update" /></label>
                    </td>
                </tr>
            </table>
        </form>
    
<?php 
            require_once('_footer.php'); 
        }
    }
?>