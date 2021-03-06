<?php

    use \Exception;
    use SSD\Form;
    use SSD\Validation;
    use SSD\Plugin;
    use SSD\Country;
    use SSD\Helper;

    $objForm = new Form();
    $objValid = new Validation($objForm);
    $objValid->expected = array('name', 'local');
    $objValid->required = array('name');
    
    try {
        if($objValid->isValid()) {
            if($objShipping->addType($objValid->post)) {
                $replace = array();
                $urlSort = $this->objURL->getCurrent(array('action', 'id'), false, array('action', 'sort'));
                if(!empty($objValid->post['local'])) {
                    $rows = $objShipping->getTypes(1);
                    $zones = $objShipping->getZones();
                    $replace['#typesLocal'] = PLugin::get('admin'.DS.'shipping', array('rows' => $rows, 'objURL' => $this->objURL, 'urlSort' => $urlSort, 'zones' => $zones));
                } else {
                    $rows = $objShipping->getTypes();
                    $objCountry = new Country();
                    $countries = $objCountry->getAllExceptLocal();
                    $replace['#typesInternational'] = Plugin::get('admin'.DS.'shipping', array('rows' => $rows, 'objURL' => $this->objURL, 'urlSort' => $urlSort, 'countries' => $countries));
                }
                echo Helper::json(array('error' => false, 'replace' => $replace));
            } else {
                $objValid->add2Errors('name', 'Record could not be updated');
                throw new Exception('Record could not be updated');
            }
        } else {
            throw new Exception('Missing parameter');
        }
    } catch (Exception $e) {
        echo Helper::json(array('error' => true, 'validation' => $objValid->errorsMessages));
    }
?>