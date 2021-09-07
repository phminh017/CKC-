<?php
function __autoload($classname)
{
    //Load Class from Framework
    if (file_exists(__DIR__ . DS . $classname . '.php')) {
        require_once __DIR__ . DS . $classname . '.php';
    }

    //load Model.
    if (file_exists(APPROOT . DS . "models" . DS . ucfirst($classname) . '.php')) {
        require_once APPROOT . DS . "models" . DS . ucfirst($classname) . '.php';
    }

    //Load Controller
    if (strpos($classname, "Controller")) {
        $controlerId = str_replace("Controller", "", $classname);
        require_once APPROOT . DS . "controllers" . DS . ucfirst($controlerId) . "Controller.php";
    }
}
