<?php
    $objCatalogue = new Catalogue();
    
    if(isset($_POST['srch'])) {
        
        if(!empty($_POST['srch'])) {
            $url = '/ecommerce/'.$this->objURL->getCurrent('srch').'/srch/'.urlencode(stripslashes(str_replace('/','',$_POST['srch'])));
        } else {
            $url = '/ecommerce/'.$this->objURL->getCurrent('srch');
        }
        Helper::redirect($url);
        
    } else {   
        $srch = stripslashes(urldecode($this->objURL->get('srch')));
        
        if(!empty($srch)) {
            $products = $objCatalogue->getAllProducts($srch);
            $empty = 'There are no results matching your search criteria.';
        } else {
            $products = $objCatalogue->getAllProducts();
            $empty = 'There are currently no records.';
        }
        $objPaging = new Paging($this->objURL, $products, 2);
        $rows = $objPaging->getRecords();
        require_once('_header.php');
?>
<h1>Products</h1>
<form action="/ecommerce/<?php echo $this->objURL->getCurrent('srch'); ?>" method="post">
    <table cellpadding="0" cellspacing="0" border="0" class="tbl_insert">
        <tr>
            <th><label for="srch">Product:</label></th>
            <td><input type="text" name="srch" id="srch" value="<?php echo $srch; ?>" class="fld" /></td>
            <td><label for="btn_add" class="sbm sbm_blue fl_l"><input type="submit" id="btn_add" class="btn" value="Search" /></label></td>
        </tr>
    </table>
</form>
<div class="dev br_td">&#160;</div>
<p><a href="/ecommerce/<?php echo $this->objURL->getCurrent('action').'/action/add'; ?>">New product</a></p>
    <?php 
        if(!empty($rows)) { 
    ?>
    <table cellpadding="0" cellspacing="0" border="0" class="tbl_repeat">
        <tr>
            <th class="col_5">Id</th>
            <th>Product</th>
            <th class="col_15 ta_r">Remove</th>
            <th class="col_5 ta_r">Edit</th>
        </tr>
        <?php foreach($rows as $product) { ?>
        <tr>
            <td><?php echo $product['id']; ?></td>
            <td><?php echo Helper::encodeHTML($product['name']); ?></td>
            <td class="ta_r"><a href="/ecommerce/<?php echo $this->objURL->getCurrent(array('action', 'srch')).'/action/remove/id/'.$product['id']; ?>">Remove</a></td>
            <td class="ta_r"><a href="/ecommerce/<?php echo $this->objURL->getCurrent(array('action', 'srch')).'/action/edit/id/'.$product['id']; ?>">Edit</a></td>
        </tr>
        <?php } ?>
    </table>
    <?php echo $objPaging->getPaging(); ?>
<?php 
        } else {
            echo '<p>'.$empty.'</p>' ;
        } 
?>
<?php 
        require_once('_footer.php'); 
    } 
?>