
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Ecommerce website project</title>
    <meta name="description" content="Ecommerece website project" />
    <meta name="keywords" content="Ecommerce website project" />
    <meta http-equiv="imagetoolbar" content="no" />
    <link href="/ecommerce/css/core.css" rel="stylesheet" type="text/css" />
</head>

<body>
    <div id="header">
        <div id="header_in">
            <h5><a href="/ecommerce/panel">Content Management System</a></h5>
            <?php 
                if(Login::isLogged(Login::$_login_admin)) {
                    echo '<div id="logged_as">Logged in as: <strong>'.$this->objAdmin->getFullNameAdmin(Session::getSession(Login::$_login_admin)).'</strong> | <a href="/ecommerce/panel/logout">Logout</a></div>';
                } else {
                    echo '<div id="logged_as"><a href="/ecommerce/panel/">Login</a></div>';   
                }
            ?>
        </div>
    </div>
    <div id="outer">
        <div id="wrapper">
            <div id="left">
                <?php if(Login::isLogged(Login::$_login_admin)) { ?> 
                    <h2>Navigation</h2>
                    <div class="dev br_td">&nbsp;</div>
                    <ul id="navigation">
                        <li><a href="/ecommerce/panel/products" <?php echo $this->objNavigation->active('products'); ?>>Products</a></li>
                        <!-- dung getactive de kiem tra trang dang xem co phai la trang product khong, neu phai thi them class active vao -->
                        <li><a href="/ecommerce/panel/categories" <?php echo $this->objNavigation->active('categories'); ?>>Categories</a></li>
                        <li><a href="/ecommerce/panel/orders" <?php echo $this->objNavigation->active('orders'); ?>>Orders</a></li>
                        <li><a href="/ecommerce/panel/clients" <?php echo $this->objNavigation->active('clients'); ?>>Clients</a></li>
                        <li><a href="/ecommerce/panel/business" <?php echo $this->objNavigation->active('business'); ?>>Business</a></li>
                        <li><a href="/ecommerce/panel/shipping" <?php echo $this->objNavigation->active('shipping'); ?>>Shipping</a></li>
                        <li><a href="/ecommerce/panel/zone" <?php echo $this->objNavigation->active('zone'); ?>>Zones</a></li>
                        <li><a href="/ecommerce/panel/country" <?php echo $this->objNavigation->active('country'); ?>>Countries</a></li>
                    </ul>
                <?php } else { ?>
                    &nbsp;                
                <?php } ?>
            </div>
            <div id="right">