<?php

    use \Exception;
    use SSD\Form;
    use SSD\Validation;
    use SSD\Plugin;
    use SSD\Helper;

    $objForm = new Form();
    $objValid = new Validation($objForm);
    $objValid->expected = array('post_code');
    $objValid->required = array('post_code');
    
    try {
        if($objValid->isValid()) {
            $postCode = strtoupper(Helper::alphaNumericalOnly($objValid->post['post_code']));
            if($objShipping->isDuplicatePostCode($postCode)) {
                $objValid->add2Errors('post_code', 'Duplicate post code');
                throw new Exception('Duplicate post code');
            }
            $array = array('post_code' => $postCode, 'zone' => $zone['id']);
            if($objShipping->addPostCode($array)) {
                $replace = array();
                $postCodes = $objShipping->getPostCodes($zone['id']);
                $replace['#postCodeList'] = Plugin::get('admin'.DS.'post-code', array('rows' => $postCodes, 'objURL' => $this->objURL));
                echo Helper::json(array('error' => false, 'replace' => $replace));
            } else {
                $objValid->add2Errors('post_code', 'Record could not be added');
                throw new Exception('Record could not be added');
            }
        } else {
            throw new Exception('Invalid entry');
        }
    } catch (Exception $e) {
        echo Helper::json(array('error' => true, 'validation' => $objValid->errorsMessages));
    }
?>