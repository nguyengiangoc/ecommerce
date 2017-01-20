<?php

    use SSD\User;
    use SSD\Helper;
    use SSD\Order;
    use SSD\Paging;

    $objUser = new User();
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
        
        if (!empty($srch)) {
            $users = $objUser->getUsers($srch);
            $empty = 'There are no results matching your searching criteria.';
        } else {
            $users = $objUser->getUsers();
            $empty = 'There are currently no records.';
        }
        $objPaging = new Paging($this->objURL, $users, 5);
        $rows = $objPaging->getRecords();
        require_once('_header.php');
?>
    <h1>Clients</h1>
    <form action="<?php echo BASE_PATH.'/'.$this->objURL->getCurrent('srch'); ?>" method="post">

        <table cellpadding="0" cellspacing="0" border="0" class="tbl_insert">
            <tr>
                <th><label for="srch">Name:</label></th>
                <td><input type="text" name="srch" id="srch" value="<?php echo $srch; ?>" class="fld" /></td>
                <td><label for="btn_add" class="sbm sbm_blue fl_l"><input type="submit" id="btn_add" class="btn" value="Search" /></label></td>
            </tr>
        </table>
    </form>
    <?php 
        if (!empty($rows)) { 
    ?>
            <table cellpadding="0" cellspacing="0" border="0" class="tbl_repeat">
                <tr>
                    <th>Name</th>
                    <th class="col_15 ta_r">Remove</th>
                    <th class="col_5 ta_r">View</th>
                </tr>
                <?php foreach($rows as $user) { ?>
                    <tr>
                    <td><?php echo Helper::encodeHtml($user['first_name']." ".$user['last_name']); ?></td>
                    <td class="ta_r">
                        <?php
                            $orders = $objOrder->getClientOrders($user['id']);
                            if (empty($orders)) {
                        ?>
                                <a href="<?php echo BASE_PATH.'/'.$this->objURL->getCurrent(array('action', 'id')).'/action/remove/id/'.$user['id']; ?>">Remove</a>
                        <?php } else { ?>
                            <span class="inactive">Remove</span>
                        <?php } ?>
                    </td>
                    <td class="ta_r"><a href="<?php echo BASE_PATH.'/'.$this->objURL->getCurrent(array('action', 'id')).'/action/edit/id/'.$user['id']; ?>">Edit</a></td>
                    </tr>
                <?php } ?>
            </table>
<?php
            echo $objPaging->getPaging();
        } else {
            echo '<p>'.$empty.'</p>';
        }
    require_once('_footer.php');
    }
?>
