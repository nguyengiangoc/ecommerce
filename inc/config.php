<?php

    defined("BASE_PATH")
        || define("BASE_PATH", $_SERVER['REQUEST_URI']);
    
    defined("COUNTRY_LOCAL")
        || define("COUNTRY_LOCAL", 229);
        
    defined("INTERNATIONAL_VAT")
        || define("INTETNATIONAL_VAT", false);
    
    defined("PAGE_EXT")
        || define("PAGE_EXT", "");
    //define phan duoi cua URL 
    
    defined("SITE_URL")
        || define("SITE_URL", "http://localhost/ecommerce");
    //kiem tra xem site url da duoc dat chua, neu chua thi` dat, neu dat roi thi khong lam gi ca
    // $_SERVER['SERVER_NAME'] tra ve ten mien cua website
    //define constant de co the dung bat cu dau
    

    //dau phan cach duong dan trong he dieu hanh window la dau \, vi du nhw C:\program file, con trong Linus va Mac la dau "/"
    //directory separator co vai tro tim xem dau phan cach duong dan la dau gi
    //tim duoc roi thi gan cho DS

    defined("ROOT_PATH")
        || define("ROOT_PATH", realpath(dirname(__FILE__).DS."..".DS));
    //dirname(__FILE__) tra ve duong dan den folder chua file hien dang dung 
    //dau hai cham la de nhay ve truoc mot tang folder
    //ket qua la quay ve thu muc goc
    
    defined("CLASSES_DIR")
        || define("CLASSES_DIR", "library");
    //ten folder classes
    
    defined("CLASSES_PATH")
        || define("CLASSES_PATH", ROOT_PATH.DS.CLASSES_DIR);
        
    defined("PLUGIN_PATH")
        || define("PLUGIN_PATH", ROOT_PATH.DS."plugin");
    
    defined("PAGES_DIR")
        || define("PAGES_DIR", "pages");    
    
    defined("MODULE_DIR")
        || define("MODULE_DIR", "mod");
        
    defined("INC_DIR")
        || define("INC_DIR", "inc");
    
    defined("TEMPLATE_DIR")
        || define("TEMPLATE_DIR", "template");
        
    defined("EMAILS_DIR")
        || define("EMAILS_DIR", ROOT_PATH . DS . "emails");
    
    defined("CATALOGUE_DIR")
        || define("CATALOGUE_DIR", "media" . DS . "catalogue");
    
    defined("CATALOGUE_PATH")
        || define("CATALOGUE_PATH", ROOT_PATH . DS . CATALOGUE_DIR);

//SMTP


    defined("SMTP_USE")
        || define("SMTP_USE", false);  
    
    defined("SMTP_HOST")
        || define("SMTP_HOST", '');
    
    defined("SMTP_USERNAME")
        || define("SMTP_USERNAME", '');
        
    defined("SMTP_PASSWORD")
        || define("SMTP_PASSWORD", '');
        
    defined("SMTP_PORT")
        || define("SMTP_PORT", '');
        
    defined("SMTP_SSL")
        || define("SMTP_SSL", '');
        
    
//DATABASE

    defined("DB_HOST")
        || define("DB_HOST", 'localhost:8080');
        
    defined("DB_NAME")
        || define("DB_NAME", '');
    
    defined("DB_USER")
        || define("DB_USER", '');
        
    defined("DB_PASS")
        || define("DB_PASS", '');
        
        
    set_include_path(implode(PATH_SEPARATOR, array(
    //them tat ca cac duong dan o tren vao include path
        realpath(ROOT_PATH . DS . INC_DIR),
        realpath(CLASSES_PATH),
        get_include_path()
    )));
    

    //ten class la autloader, method la load
    
?>