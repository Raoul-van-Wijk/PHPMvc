<?php
namespace App\Controllers;

use App\Models\TestModel;
use App\Requests\Request;

class TestController extends Controller
{
  public function index()
  {
    // use this function to return a view
    return $this->view('index');
  }

  public function test()
  {
    // use this function to return a view
    return true;
  }
}
