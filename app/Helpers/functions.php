<?php
use App\Libraries\Route;


function route($routeName, array | int $params = [])
{
    if(is_int($params)) {
    $params = [$params];
    }
    return Route::getRoute($routeName, $params);
}

function dd(...$data)
{
  echo '<pre>';
  var_dump($data);
  echo '</pre>';
  die();
}


function csrf()
{
  $_SESSION['_token'] = bin2hex(random_bytes(32));
  return '<input type="hidden" name="_token" value="' . $_SESSION['_token'] . '">';
}