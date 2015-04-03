<?php require_once('../inc/config.php');
    try {
        $token2 = Session::getSession('token2');
        $objForm = new Form();
        $token1 = $objForm->getPost("token");
        
        if($token2 == Login::string2hash($token1)) {
            $objUser = new User();
            $user = $objUser->getUser(Session::getSession(Login::$_login_front));
            $objOrder = new Order();
            //khong can phai dua parameter vi moi thu dang duoc luu trong session
            if(!empty($user) && $objOrder->createOrder($user)) {
                $order = $objOrder->getOrder();
                //lay thong tin ve order vua duoc tao
                $items = $objOrder->getOrderItems();
                //lay thong tin ve cac product trong order vua duoc tao
                
                if(!empty($order) && (!empty($items))) {
                    $objBasket = new Basket($user);
                    $objCatalogue = new Catalogue();
                    $objPayPal = new PayPal();
                    
                    foreach($items as $item) {
                        $product = $objCatalogue->getProduct($item['product']);
                        //cot product trong bang order items chi luu product id chu khong luu product name, nen khi xuat ra thi
                        //item[product] se tra ve product id
                        $objPayPal->addProduct($item['product'], $product['name'], $item['price'], $item['qty']); 
                        //them thong tin san pham vao array _products cua class product
                    }
                    
                    $objPayPal->_tax_cart = $objBasket->_final_vat;
                    $objPayPal->_shipping = $objBasket->_final_shipping_cost;
                    
                    $objCountry = new Country();
                    $country = $objCountry->getCountry($user['country']);
                    $objPayPal->_populate = array(
                        "address1" => $user['address_1'],
                        "address2" => $user['address_2'],
                        "city" => $user['town'],
                        "state" => $user['county'],
                        "zip" => $user['post_code'],
                        "country" => $country['code'],
                        "email" => $user['email'],
                        "first_name" => $user['first_name'],
                        "last_name" => $user['last_name']
                    );
                    
                    $form = $objPayPal->run($order['token']);
                    echo Helper::json(array('error' => false, 'form' => $form));
                    
                } else {
                     throw new Exception('There was a problem retrieving your order');
                }    
            } else {
                throw new Exception('Order could not be created');
            }
        } else {
            throw new Exception('Invalid request');
        }
    } catch (Exception $e) {
        echo Helper::json(array('error' => true, 'message' => $e->getMessage()));   
    }
?>