<?php
    if($objShipping->removePostCode($code['id'])) {
        $replace = array();
        $postCodes = $objShipping->getPostCodes($zone['id']);
        $replace['#postCodeList'] = Plugin::get('admin'.DS.'post-code', array('rows' => $postCodes, 'objURL' => $this->objURL));
        echo Helper::json(array('error' => false, 'replace' => $replace));
    } else {
        throw new Exception('Record could not be removed');
    }
?>