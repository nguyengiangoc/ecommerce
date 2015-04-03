<?php
    $id = $this->objURL->get('id');
    if(!empty($id)) {
        $objCatalogue = new Catalogue();
        $product = $objCatalogue->getProduct($id);
        if(!empty($product)) {
            $yes = '/ecommerce/'.$this->objURL->getCurrent().'remove/1' ;
            $no = 'javascript:history.go(-1)';
            $remove = $this->objURL->get('remove');
            if(!empty($remove) && $category['products_count'] == 0) {
                $objCatalogue->removeProduct($id);
                Helper::redirect('/ecommerce/'.$this->objURL->getCurrent(array('action', 'id', 'remove', 'srch', Paging::$_key))); //tuc la quay ve trang product list
            }
            require_once('_header.php');
?>
            <h1>Products :: Remove</h1>
            <p>Are you sure you want to remove this record?<br />The action cannot be reversed.<br />
            <a href="<?php echo $yes; ?>">Yes</a> | <a href="<?php echo $no; ?>">No</a>
            </p>
<?php
            require_once('_footer.php');
        }
    }
?>