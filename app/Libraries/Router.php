<?php
namespace App\Libraries;

require_once dirname(__DIR__) . './Config/Config.php';

class Router
{
  public static array $registeredPostRoutes = [];
  public static array $registeredGetRoutes = [];

  public static array $registeredRoutes = [];

  public array $params;

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
    self::$registeredRoutes[$routePath] = [
      'requestMethod' => $requestMethod,
      'routeName' => $routeName,
      'controllerMethod' => $controllerMethod
    ];
  }

  public function loadRoute(string $url, ...$params) 
  {
    $validRoutes;
    switch ($_SERVER['REQUEST_METHOD'])
    {
      case 'GET':
        $validRoutes = self::$registeredGetRoutes;
        break;
      case 'POST':
        $validRoutes = self::$registeredPostRoutes;
        break;
      default:
        echo 'Invalid request method';
        break;
    }

  if($url == null) $url = "/";
  //dd(self::$registeredRoutes);
    if(isset($validRoutes[$url])) {
      //dd($url); 
      $p = $validRoutes[$url];
      $callbackClass = new $p['controllerMethod'][0]();
      //dd($callbackClass);
      call_user_func_array([$callbackClass, $p['controllerMethod'][1]], $params);
      return;
    } else {
      $url = explode('/', $url);
      foreach($validRoutes as $routeUrl => $routeData) 
      {
        $routeUrl = explode('/', $routeUrl);
        if(count($url) == count($routeUrl)) 
        {
          if(self::verifyRoute($url, $routeUrl)) {
            $callbackClass = new $routeData['controllerMethod'][0]();
            call_user_func_array([$callbackClass, $routeData['controllerMethod'][1]], $this->params);
            return;
            break;
          };
        }
      }
    }
  }


  public function verifyRoute($url, $routeUrl)
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
      elseif ($i == count($url) -1) 
      {
        return true;
      } 
      else 
      {
        $this->params = [];
        break;
      }
    }
  }



  public function getUrlByName(string $routeName, array $params = [])
  {
    if(\in_array($routeName, array_column(self::$registeredRoutes, 'routeName'))) {
      $route = array_keys(self::$registeredRoutes)[array_search($routeName, array_column(self::$registeredRoutes, 'routeName'))];
      $route = explode('/', $route);
      foreach($route as $key => $value) {
        if(str_starts_with($value, ':')) {
          $route[$key] = $params[trim($value, ':')];
        }
      }
      return URLROOT. implode('/', $route);
    }
  }

}
