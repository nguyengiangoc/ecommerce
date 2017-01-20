<?php

    use SSD\Login;
    use SSD\Session;

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Ecommerce website project</title>
    <meta name="description" content="Ecommerece website project" />
    <meta http-equiv="imagetoolbar" content="no" />
    <link href="<?php echo BASE_PATH; ?>/css/core.css" rel="stylesheet" type="text/css" />
    <script type="text/javascript">
        document.write("<base href='<?php echo BASE_PATH; ?>' />");
    </script>
</head>

<body>
    <div id="header">
        <div id="header_in">
            <h5><a href="<?php echo BASE_PATH; ?>/panel">Content Management System</a></h5>
            <?php 
                if(Login::isLogged(Login::$login_admin)) {
                    echo '<div id="logged_as">Logged in as: <strong>'.$this->objAdmin->getFullNameAdmin(Session::getSession(Login::$login_admin)).'</strong> | <a href="'.BASE_PATH.'/panel/logout">Logout</a></div>';
                } else {
                    echo '<div id="logged_as"><a href="'.BASE_PATH.'/panel/">Login</a></div>';   
                }
            ?>
        </div>
    </div>
    <div id="outer">
        <div id="wrapper">
            <div id="left">
                <?php if(Login::isLogged(Login::$login_admin)) { ?> 
                    <h2>Navigation</h2>
                    <div class="dev br_td">&nbsp;</div>
                    <ul id="navigation">
                        <li><a href="<?php echo BASE_PATH; ?>/panel/products" <?php echo $this->objNavigation->active('products'); ?>>Products</a></li>
                        <!-- dung getactive de kiem tra trang dang xem co phai la trang product khong, neu phai thi them class active vao -->
                        <li><a href="<?php echo BASE_PATH; ?>/panel/categories" <?php echo $this->objNavigation->active('categories'); ?>>Categories</a></li>
                        <li><a href="<?php echo BASE_PATH; ?>/panel/orders" <?php echo $this->objNavigation->active('orders'); ?>>Orders</a></li>
                        <li><a href="<?php echo BASE_PATH; ?>/panel/clients" <?php echo $this->objNavigation->active('clients'); ?>>Clients</a></li>
                        <li><a href="<?php echo BASE_PATH; ?>/panel/business" <?php echo $this->objNavigation->active('business'); ?>>Business</a></li>
                        <li><a href="<?php echo BASE_PATH; ?>/panel/shipping" <?php echo $this->objNavigation->active('shipping'); ?>>Shipping</a></li>
                        <li><a href="<?php echo BASE_PATH; ?>/panel/zone" <?php echo $this->objNavigation->active('zone'); ?>>Zones</a></li>
                        <li><a href="<?php echo BASE_PATH; ?>/panel/country" <?php echo $this->objNavigation->active('country'); ?>>Countries</a></li>
                    </ul>
                <?php } else { ?>
                    &nbsp;                
                <?php } ?>
            </div>
            <div id="right">