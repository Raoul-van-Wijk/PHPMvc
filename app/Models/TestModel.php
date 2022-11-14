<?php
namespace App\Models;

use App\Libraries\Model;

class TestModel extends Model
{
  protected string $databaseTable = "cookies";
  protected string $primaryKey = "id";
  
  // to change the database table name
  // public function __construct()
  // {
  //   parent::__construct("database name");
  // }
} 