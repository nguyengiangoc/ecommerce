<?php

    use \Exception;
    use SSD\Helper;
    use SSD\Plugin;

    if($objCountry->delete($country['id'])) {
        $replace = array();
        $countries = $objCountry->getCountries(true);
        $replace['#countryList'] = Plugin::get('admin'.DS.'country', array('rows' => $countries, 'objURL' => $this->objURL));
        echo Helper::json(array('error' => false, 'replace' => $replace));
    } else {
        throw new Exception('Record could not be removed');
    }
?>