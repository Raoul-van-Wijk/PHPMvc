<?php
namespace App\Libraries;

use App\Libraries\Route;



  class Core 
  {
      private Router $router;
      public function __construct()
      {
        $router = Route::getRouterInstance();

        $url = $this->getUrl();
        $router->loadRoute($url);
      }

      public function getUrl()
      {
          if(isset($_GET['url']))
          {
            return $_GET['url'];
          } else {
            return '/';
          }

      }
  } 
