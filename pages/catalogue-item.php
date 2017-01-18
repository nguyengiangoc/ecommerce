<?php
    $id = $this->objURL->get('item');
    if(!empty($id)) {
        $objCatalogue = new Catalogue();
        $product = $objCatalogue->getProductByIdentity($id);

        if(!empty($product)) {
            $this->_meta_title = $product['meta_title'];
            $this->_meta_description = $product['meta_description'];
            $this->_meta_keywords = $product['meta_keywords'];
            
            $category = $objCatalogue->getCategory($product['category']);
            require_once('_header.php');
            
            echo "<h1>Catalogue: " . $category['name'] . "</h1>";
            
            $image = !empty($product['image']) ? $product['image'] : 'unavailable.png';
            
            $width = Helper::getImgSize(CATALOGUE_PATH.DS.$image, 0);
            $width = $width > 120 ? 120 : $width;
            echo "<div class=\"fl_l\">";
            echo "<div class=\"lft\"><img src=\"".BASE_PATH.DS.CATALOGUE_DIR.DS.$image."\" alt=\"";
            echo Helper::encodeHTML($product['name'], 1);
            echo "\" width=\"{$width}\" /></div>";

            
            echo "<div class=\"rgt\"><h3>" . $product['name'] . "</h3>";
            echo "<h4><strong>&pound;".$product['price']."</strong></h4>";
            echo Basket::activeButton($product['id']);
            echo "</div>"; //dong div rgt
            echo "</div>"; //dong div fl_l
            echo "<div class=\"dev\">&#160;</div>";
            echo "<p>" . Helper::encodeHTML($product['description']) . "</p>";
            echo "<div class=\"dev br_td\">&#160;</div>";
            echo "<p><a href=\"javascript:history.go(-1)\">Go back</a></p>";
            
            
            require_once('_footer.php');
        } else {
            require_once(error.php);
        }
    } else {
        require_once(error.php);
    }
?>