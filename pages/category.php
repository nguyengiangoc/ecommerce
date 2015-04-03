<?php
    $cat = $this->objURL->get('category'); //luc nay duong dan tren link la identity
    if(empty($cat)) { 
        //kiem tra xem attribute category co tren duong dan khong
        require_once("error.php");
    } else {
        $objCatalogue = new Catalogue();
        $category = $objCatalogue->getCategoryByIdentity($cat);
        
        if(empty($category)) {
            //luc nay la da ton tai attribute category, xem trong csdl co category nao giong voi cat id tren duong dan khong
            require_once("error.php");
        } else {
            $this->_meta_title = $category['meta_title'];
            $this->_meta_description = $category['meta_description'];
            $this->_meta_keywords = $category['meta_keywords'];
            
            $rows = $objCatalogue->getProducts($category['id']);
            
            $objPaging = new Paging($this->objURL, $rows, 5);
            $rows = $objPaging->getRecords();
            
            require_once("_header.php");
?>

<h1>Category: <?php echo $category['name'];  ?></h1>

<?php
            if(!empty($rows)) {
                foreach ($rows as $row) {
?>    
                <div class="catalogue_wrapper">
                    <div class="catalogue_wrapper_left">
                        <?php
                            $image = !empty($row['image']) ? $row['image'] : 'unavailable.png';
                            
                            $width = Helper::getImgSize(CATALOGUE_PATH.DS.$image, 0);
                            $width = $width > 120 ? 120 : $width;
                            $link = $this->objURL->href('catalogue-item', array('category', $category['identity'], 'item', $row['identity']));
                        ?>
                        <a href="<?php echo $link; ?>"> 
                        <img src="/ecommerce/media/catalogue/<?php echo $image; ?>" alt="<?php echo Helper::encodeHTML($row['name'], 1); ?>" width="<?php echo $width; ?>" />
                        </a>
                    </div>
                    <div class="catalogue_wrapper_right">
                    
                        <h4><a href="<?php echo $link; ?>">
                        <?php echo Helper::encodeHTML($row['name'], 1); ?>                
                        </a></h4>
                        
                        <h4>Price: <?php echo Catalogue::$_currency; echo number_format($row['price'],2); ?></h4>
                        
                        <p><?php echo Helper::shortenString(Helper::encodeHTML($row['description'])); ?></p>
                        
                        <p><?php echo Basket::activeButton($row['id']); ?></p>
                    </div>
                </div>
<?php
                }
                echo $objPaging->getPaging();
            } else {
?>
                <p>There are no products in this category.</p>
<?php
            }
            require_once("_footer.php");
        }
        
    }
?>