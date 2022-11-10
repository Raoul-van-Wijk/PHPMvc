<?php
namespace App\Libraries;

use App\Libraries\Router;


class Route 
{
  public static Router $RouterInstance;

  public static function init()
  {
    if(!isset(self::$RoutesInstance)) {
      self::$RouterInstance = new Router;
    }
  }

  public static function get(string $routePath, array $controllerMethod, string $routeName)
  {
    self::$RouterInstance->registerRoute($routePath, $controllerMethod, $routeName, 'GET');
  }

  public static function post(string $routePath, array $controllerMethod, string $routeName)
  {
    self::$RouterInstance->registerRoute($routePath, $controllerMethod, $routeName, 'POST');
  }


  public static function getRoute(string $routeName, array $params)
  {
    return self::$RouterInstance->getUrlByName($routeName, $params);
  }

  public static function getRouterInstance()
  {
    if(!isset(self::$RoutesInstance)) {
      self::$RouterInstance = new Router;
    }
    return self::$RouterInstance;
  }
}


