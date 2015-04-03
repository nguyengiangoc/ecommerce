<?php
    $objCatalogue = new Catalogue();
    $cats = $objCatalogue->getCategories();
    
    $objBusiness = new Business();
    //khi tao ra object nay thi da ket noi voi database roi
    $business = $objBusiness->getBusiness();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title><?php echo $this->_meta_title; ?></title>
    <meta name="description" content="<?php echo $this->_meta_description; ?>" />
    <meta name="keywords" content="<?php echo $this->_meta_keywords; ?>" />
    <meta http-equiv="imagetoolbar" content="no" />
    <link href="/ecommerce/css/core.css" rel="stylesheet" type="text/css" />
</head>

<body>
    <div id="header">
        <div id="header_in">
            <h5><a href="/ecommerce/"><?php echo $business['name']; ?></a></h5>
            <?php 
                if(Login::isLogged(Login::$_login_front)) {
                    echo '<div id="logged_as">Logged in as: <strong>'.Login::getFullNameFront(Session::getSession(Login::$_login_front)).'</strong> | <a href="/ecommerce/';
                    echo $this->objURL->href('orders'); //chuyen sang trang order
                    echo '">My orders</a> | <a href="';
                    echo '/ecommerce/'.$this->objURL->href('logout');
                    echo '">Logout</a></div>';
                } else {
                    echo '<div id="logged_as"><a href="/ecommerce/';
                    echo $this->objURL->href('login');
                    echo '">Login</a></div>';   
                }
            ?>
        </div>
    </div>
    <div id="outer">
        <div id="wrapper">
            <div id="left">
                <?php 
                    if($this->objURL->cpage != 'summary') {
                        require_once('basket_left.php');
                    }
                ?>
                <?php 
                    if (!empty($cats)) { ?>
                        <h2>Categories</h2>
                        <ul id="navigation">
                        <?php
                            foreach($cats as $cat) {
                                echo '<li><a href="/ecommerce/'.$this->objURL->href('catalogue', array('category', $cat['identity'])).'"';
                                //echo '<li><a href="/ecommerce/'.$this->objURL->href('category', array($cat['identity'])).'"';
                                echo $this->objNavigation->active('catalogue', array('category' => $cat['identity'])); 
                                echo '>';
                                //xem cat dang duoc hien ra co giong voi cat tren link khong, neu co thi cho them class="act"
                                echo Helper::encodeHtml($cat['name']);
                                echo '</a></li>';
                            }
                        ?>
                        </ul>
                    <?php } ?>
            </div>
            <div id="right">