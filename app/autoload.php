<?php
spl_autoload_register(function($className) {
  $className = str_replace('\\', '/', $className);
  if(file_exists(dirname(__DIR__). '/' . $className . '.php')) {
      require_once dirname(__DIR__). '/' . $className . '.php';
  } elseif($className != 'App/Libraries/PDO')  {
      die('Class does not exists.');
  }
});