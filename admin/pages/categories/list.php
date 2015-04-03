<?php
    $objCatalogue = new Catalogue();
    $categories = $objCatalogue->getCategories();
    $objPaging = new Paging($this->objURL, $categories, 5);
    $rows = $objPaging->getRecords();
    //$objPaging->_url = 'admin'.$objPaging->_url;
    require_once('_header.php');
?>
<h1>Categories</h1>
<p><a href="/ecommerce/<?php echo $this->objURL->getCurrent(array('action', 'id')).'/action/add/'; ?>">New category</a></p>
<?php if(!empty($rows)) { ?>
    <table cellpadding="0" cellspacing="0" border="0" class="tbl_repeat">
        <tr>
            <th>Category</th>
            <th class="col_15 ta_r">Remove</th>
            <th class="col_5 ta_r">Edit</th>
        </tr>
        <?php foreach($rows as $category) { ?>
        <tr>
            <td><?php echo Helper::encodeHTML($category['name']); ?></td>
            <td class="ta_r"><a href="/ecommerce/<?php echo $this->objURL->getCurrent('action').'/action/remove/id/'.$category['id']; ?>">Remove</a></td>
            <td class="ta_r"><a href="/ecommerce/<?php echo $this->objURL->getCurrent('action').'/action/edit/id/'.$category['id']; ?>">Edit</a></td>
        </tr>
        <?php } ?>
    </table>
    <?php echo $objPaging->getPaging(); ?>
<?php 
    } else {
        echo '<p>There are currently no categories.</p>' ;
    } 
?>
<?php require_once('_footer.php'); ?>