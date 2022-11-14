<?php
namespace App\Helpers;

class FileManager
{
    public static function make($function, $name)
    {
      switch ($function) {
        case 'controller':
          self::makeController($name);
          break;
        case 'model':
          self::makeModel($name);
          break;
        case 'request':
          self::makeRequest($name);
          break;
        default:
          echo "Invalid function";
          break;
      }
    }

    public static function makeController($name)
    {
      if(\file_exists("app/Controllers/" . $name . ".php")) {
        echo "File already exists";
      } else {
        echo "File created";
        fwrite(fopen("app/Controllers/" . $name . ".php", "w"), self::controllerTemplate($name));
      }
    }

    public static function makeModel($name)
    {
      if(\file_exists("app/Models/" . $name . ".php")) {
        echo "File already exists";
      } else {
        echo "File created";
        fwrite(fopen("app/Models/" . $name . ".php", "w"), self::modelTemplate($name));
      }
    }

    public static function makeRequest($name)
    {
      if(\file_exists("app/Requests/" . $name . ".php")) {
        echo "File already exists";
      } else {
        echo "File created";
        fwrite(fopen("app/Requests/" . $name . ".php", "w"), self::requestTemplate($name));
      }
    }


    public static function controllerTemplate($name)
    {
      return 
"<?php
namespace App\Controllers;

class {$name} extends Controller
{
  public function index()
  {
    // use this function to return a view
  }
}
";
    }

    public static function modelTemplate($name)
    {
      return
'<?php
namespace App\Models;

use App\Libraries\Model;

class '.$name.' extends Model
{
  protected string $databaseTable = "Youre database table name";
  protected string $primaryKey = "id";
  
  // to change the database table name
  public function __construct()
  {
    parent::__construct("database name");
  }
} ';
    }

    public static function requestTemplate($name)
    {
      return
'<?php
namespace App\Requests;

use App\Requests\Request;

class '.$name.' extends Request 
{

  /**
   * Determine if the user is authorized to make this request.
   * 
   * @return bool
   */
  public function authorize()
  {
    return true;
  }

  /**
   * Get the validation rules that apply to the request.
   * 
   * @return array
   */
  public function rules()
  {
    return [];
  }
}';
    }
}