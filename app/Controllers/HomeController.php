<?php

class HomeController extends Controller
{
    public function index($id)
    {
        echo'aaa';
        $this->view('index');
    }

    public function loadView($id)
    {
        echo 'bbb';
        $this->view('index');
    }
}
