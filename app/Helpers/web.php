<?php
use App\Libraries\Route;

// This file is used to register all the routes for the application.



Route::post('home/:id', [HomeController::class, 'index'], 'Posthome');
Route::get('home/:id', [HomeController::class, 'loadView'], 'home');
