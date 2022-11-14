<?php
namespace App\Libraries;


require_once dirname(__DIR__) . './Config/Config.php';

class Router
{
  public static array $registeredPostRoutes = [];
  public static array $registeredGetRoutes = [];

  public static array $registeredRouteNames = [];

  public array $params = [];

  public function registerRoute(string $routePath, array $controllerMethod, string $routeName, string $requestMethod)
  {
    if($requestMethod === 'GET') {
      self::$registeredGetRoutes[$routePath] = [
        'requestMethod' => $requestMethod,
        'routeName' => $routeName,
        'controllerMethod' => $controllerMethod
      ];
    } else if($requestMethod === 'POST') {
      self::$registeredPostRoutes[$routePath] = [
        'requestMethod' => $requestMethod,
        'routeName' => $routeName,
        'controllerMethod' => $controllerMethod
      ];
    }
    self::$registeredRouteNames[$routeName] = [
      'routePath' => $routePath,
    ];
  }

  public function loadRoute(string $url, string $method = "") 
  {
    if(str_contains($url, '?')) {
      $url = explode('?', $url)[0];
    }
    switch ($method)
    {
      case 'GET':
        $validRoutes = self::$registeredGetRoutes;
        break;
      case 'POST':
        $validRoutes = self::$registeredPostRoutes;
        break;
      default:
        die('Invalid request method');
        break;
    }
    if(isset($validRoutes[$url])) {
      $class = $validRoutes[$url];
      $callbackClass = new $class['controllerMethod'][0]();
      return [[$callbackClass, $class['controllerMethod'][1]], []];
    } else {
      $url = explode('/', $url);
      foreach($validRoutes as $routeUrl => $routeData) 
      {
        $routeUrl = explode('/', $routeUrl);
        if(count($url) == count($routeUrl)) 
        {
          if(self::verifyRoute($url, $routeUrl)) {
            $callbackClass = new $routeData['controllerMethod'][0]();
            return [[$callbackClass, $routeData['controllerMethod'][1]], $this->params];
            break;
          } else {
            continue;
          }
        } else {
          continue;
        }
      }
      die('404 Page not found');
    }
  }


  public function verifyRoute(array $url, array $routeUrl)
  {
    for ($i = 0; $i < count($url); $i++) 
    { 
      if($url[$i] == $routeUrl[$i]) 
      {
        
        if($i == count($url) -1) {return true;}
        continue;
      } 
      else if (str_starts_with($routeUrl[$i], ':')) 
      {
        $this->params[trim($routeUrl[$i], ':')] = $url[$i];
        if($i == count($url) -1) {return true;}
        continue;
      } 
      else 
      {
        $this->params = [];
        return false;
        break;
      }
    }
  }



  public function getUrlByName(string $routeName, array $params = [])
  {
    if(isset(self::$registeredRouteNames[$routeName])) {
      $route = self::$registeredRouteNames[$routeName]['routePath'];
      $route = explode('/', $route);
      foreach($route as $key => $value) {
        if(str_starts_with($value, ':')) {
          $route[$key] = $params[trim($value, ':')];
        }
      }
      return '.' . implode('/', $route);
    } else {
      echo die("A route with the name $routeName does not exist");
    }
  }
}
