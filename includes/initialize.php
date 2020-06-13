<?php

    defined('DS') ? null : define('DS',DIRECTORY_SEPARATOR);
    defined('SITE_ROOT') ? null : define('SITE_ROOT', $_SERVER['DOCUMENT_ROOT']);
    defined('LIB_PATH') ? null : define('LIB_PATH',SITE_ROOT.DS.'..'.DS.'includes') ;



    require_once(LIB_PATH . "/session.php") ;
    require_once(LIB_PATH . "/database.php") ;
    require_once(LIB_PATH . "/functions.php") ;

    require_once(LIB_PATH . "/database.php") ;
    require_once(LIB_PATH . "/user.php") ;
    
    if(($_SERVER["PHP_SELF"] != "/sign_up.php") && ($_SERVER["PHP_SELF"] != "/sign_in.php") && ($_SERVER["PHP_SELF"] != "/sign_out.php")){
        user::confirm_sign_in();
    }

    require_once(LIB_PATH . "/category.php");
    require_once(LIB_PATH . "/expense.php");

    include("layouts/header.php");

