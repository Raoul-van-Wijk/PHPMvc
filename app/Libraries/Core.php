<?php
namespace App\Libraries;

use App\Libraries\Route;
use Reflection;
use ReflectionClass;

  class Core 
  {
      private Router $router;
      public function __construct()
      {
        $router = Route::getRouterInstance();

        $url = $this->getUrl();
        if(isset($_POST["_method"])) {
          $method = $_POST["_method"];
        } else {
          $method = $_SERVER['REQUEST_METHOD'];
        }
        $call = $router->loadRoute($url, $method);
        if(isset($_GET) || isset($_POST)) {
          $ref = new ReflectionClass($call[0][0]);
          $method = $ref->getMethod($call[0][1]);
          $requestParams = $method->getParameters();
          if(isset($requestParams[0]) && str_ends_with($requestParams[0]->getType()->getName(), 'Request')) {
            $request = $requestParams[0]->getType()->getName();
            $call[0][2] = $request;
            $request = new $request($_POST ? $_POST : $_GET);
            array_unshift($call[1], $request);
          }
          call_user_func_array([$call[0][0], $call[0][1]], $call[1]);
        }else {
          call_user_func_array([$call[0][0], $call[0][1]], $call[1]);
        }


        // handle the request

      }

      public function getUrl()
      {
        return $_SERVER['REQUEST_URI'];
          // if(isset($_GET['url']))
          // {
          //   return $_GET['url'];
          // } else {
          //   return '/';
          // }

      }
  } 
