<?php
use App\Libraries\Route;
use App\Libraries\Core;


require_once 'Config/Config.php';

require_once __DIR__ . './autoload.php';

Route::init();
require_once 'Helpers/web.php';
require_once 'Helpers/functions.php';
// Instantiate core class
$init = new Core();

