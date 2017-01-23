<?php

    use SSD\Catalogue;
    use SSD\Helper;
    use SSD\Paging;

    $id = $this->objURL->get('id');
    if(!empty($id)) {
        $objCatalogue = new Catalogue();
        $category = $objCatalogue->getCategory($id);
        if(!empty($category)) {
            $yes = $this->objURL->getCurrent().'remove/1';
            $no = 'javascript:history.go(-1)';
            $remove = $this->objURL->get('remove');
            if(!empty($remove)) {
                $objCatalogue->removeCategory($id);
                Helper::redirect(URL::getCurrentURL(array('action', 'id', 'remove', 'srch', Paging::$key))); //tuc la quay ve trang product list
            }
            require_once('template/_header.php');
?>
            <h1>Categories :: Remove</h1>
            <p>Are you sure you want to remove this record?<br />The action cannot be reversed.<br />
            <a href="<?php echo $yes; ?>">Yes</a> | <a href="<?php echo $no; ?>">No</a>
            </p>
<?php
            require_once('template/_footer.php');
        }
    }
?>