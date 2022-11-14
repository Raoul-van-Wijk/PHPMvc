<?php
namespace App\Requests;


class Request 
{
  public function __construct(array $data)
  {
    if($_SERVER["REQUEST_METHOD"] == "GET") {

      $this->validate($data, useCSRF: false);
    }
    $this->validate($data);
  }

  public function validate($data, $rules = [], $useCSRF = true)
  {
    if(!$this->authorize()) {
      throw new \Exception('Not authorized');
    }
    if($useCSRF) {
      if(!isset($data['_token']) || !isset($_SESSION['_token'])) {
        throw new \Exception('CSRF token mismatch');
      }
      if(!hash_equals($_SESSION['_token'], $data['_token'])) {
        throw new \Exception('CSRF token mismatch');
      }
    }



    $rules = $rules ? $rules : $this->rules();
    foreach($rules as $key => $rule)
    {
      $rule = explode('|', $rule);
      foreach($rule as $r)
      {
        if($r === 'required')
        {
          if(!isset($data[$key]))
          {
            throw new \Exception("The $key field is required");
          }
        } elseif($r === "int") {
          if(!is_int($data[$key]))
          {
            throw new \Exception("The $key field must be an integer");
          }
        } elseif($r === "string") {
          if(!is_string($data[$key]))
          {
            throw new \Exception("The $key field must be a string");
          }
        } elseif($r === "array") {
          if(!is_array($data[$key]))
          {
            throw new \Exception("The $key field must be an array");
          }
        } elseif($r === "bool") {
          if(!is_bool($data[$key]))
          {
            throw new \Exception("The $key field must be a boolean");
          }
        } elseif($r === "float") {
          if(!is_float($data[$key]))
          {
            throw new \Exception("The $key field must be a float");
          }
        } elseif($r === "numeric") {
          if(!is_numeric($data[$key]))
          {
            throw new \Exception("The $key field must be a numeric");
          }
        } elseif($r === "email") {
          if(!filter_var($data[$key], FILTER_VALIDATE_EMAIL))
          {
            throw new \Exception("The $key field must be a valid email");
          }
        } elseif($r === "url") {
          if(!filter_var($data[$key], FILTER_VALIDATE_URL))
          {
            throw new \Exception("The $key field must be a valid url");
          }
        } elseif($r === "ip") {
          if(!filter_var($data[$key], FILTER_VALIDATE_IP))
          {
            throw new \Exception("The $key field must be a valid ip address");
          }
        } elseif($r === "mac") {
          if(!filter_var($data[$key], FILTER_VALIDATE_MAC))
          {
            throw new \Exception("The $key field must be a valid mac address");
          }
        } elseif(str_contains($r, ':')) {
          $r = explode(':', $r);
          if($r[0] === "min")
          {
            if(strlen($data[$key]) < $r[1])
            {
              throw new \Exception("The $key field must be at least $r[1] characters");
            }
          } elseif($r[0] === "max") {
            if(strlen($data[$key]) > $r[1])
            {
              throw new \Exception("The $key field must be at most $r[1] characters");
            }
          } elseif($r[0] === "between") {
            if(strlen($data[$key]) < $r[1] || strlen($data[$key]) > $r[2])
            {
              throw new \Exception("The $key field must be between $r[1] and $r[2] characters");
            }
          } elseif($r[0] === "min_num") {
            if($data[$key] < $r[1])
            {
              throw new \Exception("The $key field must be at least $r[1]");
            }
          } elseif($r[0] === "max_num") {
            if($data[$key] > $r[1])
            {
              throw new \Exception("The $key field must be at most $r[1]");
            }
          } elseif($r[0] === "between_num") {
            if($data[$key] < $r[1] || $data[$key] > $r[2])
            {
              throw new \Exception("The $key field must be between $r[1] and $r[2]");
            }
          } elseif($r[0] === "min_float") {
            if($data[$key] < $r[1])
            {
              throw new \Exception("The $key field must be at least $r[1]");
            }
          } elseif($r[0] === "max_float") {
            if($data[$key] > $r[1])
            {
              throw new \Exception("The $key field must be at most $r[1]");
            }
          } elseif($r[0] === "between_float") {
            if($data[$key] < $r[1] || $data[$key] > $r[2])
            {
              throw new \Exception("The $key field must be between $r[1] and $r[2]");
            }
          }
        }
      }
    }
    $this->setProperties($data);
  }

  public function rules()
  {
    return [];
  }

  public function setProperties(array $data)
  {
    foreach($data as $key => $value) {
      $this->{$key} = $value;
    }
  }

  function authorize()
  {
    return true;
  }
}