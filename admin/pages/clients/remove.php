<?php

    use SSD\User;
    use SSD\Helper;
    use SSD\Order;
    use SSD\Paging;

    $id = $this->objURL->get('id');
    if (!empty($id)) {
        $objUser = new User();
        $user = $objUser->getUser($id);
        if (!empty($user)) {
            $objOrder = new Order();
            $orders = $objOrder->getClientOrders($id);
            if (empty($orders)) {
                $yes = $this->objURL->getCurrent().'/remove/1';
                $no = 'javascript:history.go(-1)';
                $remove = $this->objURL->get('remove');
                if (!empty($remove)) {
                    $objUser->removeUser($id);
                    Helper::redirect($this->objURL->getCurrent(array('action', 'id', 'remove', 'srch', Paging::$key)));
                }
                require_once('_header.php');
?>
                <h1>Clients :: Remove</h1>
                <p>Are you sure you want to remove this client (<?php echo $user['first_name']." ".$user['last_name']; ?>)?<br />
                There is no undo!<br />
                <a href="<?php echo $yes; ?>">Yes</a> | <a href="<?php echo $no; ?>">No</a></p>
<?php
                require_once('_footer.php');
            }
        }
    }
?>
