<?php
use App\Libraries\Route;

use App\Controllers\TestController;
// Dont forget to include the Controller class that you want to use


// This file is used to register all the routes for the application.
Route::post('/home', [TestController::class, 'test'], 'Home');
Route::get('/', [TestController::class, 'index'], 'HomePage');
