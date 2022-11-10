<?php
    //Load the model and the view
    class Controller 
    {
        protected function model($model) : mixed
        {
            //Require model file
            require_once '../app/Models/' . $model . '.php';

            //Instantiate model
            return new $model();
        }

        //Load the view (checks for the file)
        protected function view($view, $data = []) 
        {
            if (file_exists('../app/Views/' . $view . '.php')) 
            {
                require_once '../app/Views/' . $view . '.php';
            } 
            else 
            {
                die("View does not exists.");
            }
        }
    }
