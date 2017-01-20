<?php

    use \Exception;
    use SSD\Helper;
    use SSD\Form;

    $objForm = new Form();
    $value = $objForm->getPost('value');
    if(!empty($value)) {
        if($objShipping->updateType(array('name' => $value), $type['id'])) {
            echo Helper::json(array('error' => false));
        } else {
            throw new Exception('Record could not be updated');
        }
        //$result = $objShipping->updateType(array('name' => $value), $type['id']);
        //throw new Exception($result);
    } else {
        throw new Exception('Invalid entry');
    }
?>