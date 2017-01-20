<?php

    use SSD\Order;
    use SSD\Helper;
    use SSD\Paging;

    $id = $this->objURL->get('id');
    if(!empty($id)) {
        $objOrder = new Order();
        $order = $objOrder->getOrder($id);
        if(!empty($order)) {
            $yes = BASE_PATH.'/'.$this->objURL->getCurrent().'/remove/1' ;
            $no = 'javascript:history.go(-1)';
            $remove = $this->objURL->get('remove');
            if(!empty($remove)) {
                $objOrder->delete($id);
                Helper::redirect(BASE_PATH.'/'.$this->objURL->getCurrent(array('action', 'id', 'remove', 'srch', Paging::$key))); //tuc la quay ve trang product list
            }
            require_once('_header.php');
?>
            <h1>Orders :: Remove</h1>
            <p>Are you sure you want to remove this record?<br />The action cannot be reversed.<br />
            <a href="<?php echo $yes; ?>">Yes</a> | <a href="<?php echo $no; ?>">No</a>
            </p>
<?php
            require_once('_footer.php');
        }
    }
?>