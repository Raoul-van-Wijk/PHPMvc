<?php
use App\Libraries\Route;
use App\Libraries\Core;



spl_autoload_register(function($className) {
    $className = str_replace('\\', '/', $className);
    $className = explode('/', $className);
    $className = end($className);
    if(str_ends_with($className, 'Controller')) {
        require_once 'Controllers/' . $className . '.php';
    } else if(str_ends_with($className, 'Model')) {
        require_once 'Models/' . $className . '.php';
    } else if(str_ends_with($className, 'Entity')) {
        require_once 'Entities/' . $className . '.php';
    } else if (str_ends_with($className, 'Helper')) {
        require_once 'Helpers/' . $className . '.php';
    } else {
        require_once 'Libraries/' . $className . '.php';
    }
});

Route::init();
require_once 'Helpers/web.php';
require_once 'Helpers/functions.php';
// Instantiate core class
$init = new Core();

require_once 'Config/Config.php';






?>
