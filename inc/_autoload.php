<?php
    require_once('config.php');
    function __autoload($class_name) {
        //ham nay tu dong tim cac class trong cac file khi gap mot class chua duoc define tu truoc
        //chuc nang nay giup do mat cong include class
        $class = explode("_", $class_name);
        //tach class name ra thanh 2 thanh phan trong array
        $path = implode("/", $class) . ".php";
        //ghep vao, cacn nhau dau /, de thanh phan thu nhat lam ten folder, thanh phan thu 2 lam ten file
        @require_once(ROOT_PATH.DS.CLASSES_DIR.DS.$path);
    }
?>