<?php
namespace App\Controllers;

use App\Libraries\Route;
    class Controller 
    {
        protected string $nextUrl;

        protected function view($view, $data = []) 
        {
            if (file_exists('../app/Views/' . $view . '.php')) 
            {
                foreach ($data as $key => $value) 
                {
                    $$key = $value;
                }
                require_once '../app/Views/' . $view . '.php';
            } 
            else 
            {
                die("View does not exists.");
            }
        }


        public function redirect($route)
        {
            $this->nextUrl = Route::getRoute($route);
            return $this;
        }

        public function back()
        {
            header('Location: ' . $_SERVER['HTTP_REFERER']);
        }

        public function route($routePath = null, $params = [])
        {
            if($routePath) {
                $this->nextUrl = $routePath;
            }
            return header('Location: ' . $this->nextUrl);
        }

    }
