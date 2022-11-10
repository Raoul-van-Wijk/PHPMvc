<?php
namespace App\Controllers;
    class Controller 
    {
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

    }
