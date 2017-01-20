<?php

    use SSD\Order;
    use SSD\Helper;
    use SSD\Paging;
    

    $objOrder = new Order();
    
    if(isset($_POST['srch'])) {
        
        if(!empty($_POST['srch'])) {
            $url = BASE_PATH.'/'.$this->objURL->getCurrent('srch').'/srch/'.urlencode(stripslashes(str_replace('/','',$_POST['srch'])));
        } else {
            $url = BASE_PATH.'/'.$this->objURL->getCurrent('srch');
        }
        Helper::redirect($url);
        
    } else {  
        $srch = stripslashes(urldecode($this->objURL->get('srch')));
    
        if(!empty($srch)) {
            $orders = $objOrder->getOrders($srch);
            $empty = 'There are no results matching your search criteria.';
        } else {
            $orders = $objOrder->getOrders();
            $empty = 'There are currently no records.';
        }
        $objPaging = new Paging($this->objURL, $orders, 5);
        $rows = $objPaging->getRecords();
        //$objPaging->_url = 'admin'.$objPaging->_url;
        require_once('_header.php');
?>
<h1>Orders</h1>
<form action="<?php echo BASE_PATH.'/'.$this->objURL->getCurrent('srch'); ?>" method="post">
    
    <table cellpadding="0" cellspacing="0" border="0" class="tbl_insert">
        <tr>
            <th><label for="srch">Order no. :</label></th>
            <td><input type="text" name="srch" id="srch" value="<?php echo ($srch); ?>" class="fld" /></td>
            <td><label for="btn_add" class="sbm sbm_blue fl_l"><input type="submit" id="btn_add" class="btn" value="Search" /></label></td>
        </tr>
    </table>
</form>
    <?php 
        if(!empty($rows)) { 
    ?>
    <table cellpadding="0" cellspacing="0" border="0" class="tbl_repeat">
        <tr>
            <th class="col_5">Id</th>
            <th>Date</th>
            <th class="col_15 ta_r">Total</th>
            <th class="col_15 ta_r">Status</th>
            <th class="col_15 ta_r">PP Status</th>
            <th class="col_15 ta_r">Remove</th>
            <th class="col_5 ta_r">View</th>
        </tr>
        <?php foreach($rows as $order) { ?>
        <tr>
            <td><?php echo $order['id']; ?></td>
            <td><?php echo Helper::setDate(1, $order['date']); ?></td>
            <td class="ta_r">&pound;<?php echo number_format($order['total'], 2); ?></td>
            <td class="ta_r"><?php $status = $objOrder->getStatus($order['status']); echo $status['name']; ?></td>
            <td class="ta_r"><?php echo $order['payment_status'] != null ? $order['payment_status'] : "Pending"; ?></td>
            <td class="ta_r">
                <?php 
                    if ($order['status'] == 1) { ?>
                        <a href="<?php echo BASE_PATH.'/'.$this->objURL->getCurrent(array('action', 'srch')).'/action/remove/id/'.$order['id']; ?>">Remove</a>
                <?php } else { ?>
                    <span class="inactive">Remove</span>
                <?php } ?>
            
            
            </td>
            <td class="ta_r"><a href="<?php echo BASE_PATH.'/'.$this->objURL->getCurrent(array('action', 'srch')).'/action/edit/id/'.$order['id']; ?>">View</a></td>
        </tr>
        <?php } ?>
    </table>
    <?php echo $objPaging->getPaging(); ?>
<?php 
        } else {
            echo '<p>'.$empty.'</p>' ;
        } 
        require_once('_footer.php');
    }
?>