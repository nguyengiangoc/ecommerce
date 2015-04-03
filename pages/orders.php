<?php
    Login::restrictFront($this->objURL);
    $objOrder = new Order();
    $orders = $objOrder->getClientOrders(Session::getSession(Login::$_login_front));
    
    $objPaging = new Paging($this->objURL, $orders, 10);
    $rows = $objPaging->getRecords();
    require_once("_header.php");
?>
    <h1>My orders</h1>
    <?php if(!empty($rows)) { ?>
        <table cellspacing="0" cellpadding="0" border="0" class="tbl_repeat">
            <tr>
                <th>Id</th>
                <th class="ta_r">Date</th>
                <th class="ta_r col_15">Status</th>
                <th class="ta_r col_15">Total</th>
                <th class="ta_r col_15">Invoice</th>
            </tr>
            <?php foreach($rows as $row) { ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td class="ta_r"><?php echo Helper::setDate(1, $row['date']); ?></td>
                    <td class="ta_r"><?php $status = $objOrder->getStatus($row['status']); echo $status['name']; ?></td>
                    <td class="ta_r">&pound;<?php echo number_format($row['total'], 2); ?></td>
                    <td class="ta_r"><?php 
                        if($row['pp_status'] == 1) { 
                            echo '<a href="'.$this->objURL->href('invoice', array('token', $row['token'])).'" target="_blank">Invoice</a>'; 
                        } else {
                            echo '<span class="inactive">Invoice</span>';
                        } ?></td>
                </tr>
            
            <?php } ?>
            
        </table>
        <?php echo $objPaging->getPaging(); ?>
    <?php } else { ?>
        <p>There is currently no order.</p>
    <?php } ?>

<?php require_once("_footer.php");