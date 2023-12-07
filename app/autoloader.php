<?php

spl_autoload_register(
  function($class){
    $path = __DIR__ . '/../' .lcfirst(str_replace('\\','/', $class)) . '.php';
    var_dump($path);
    if (file_exists($path)) {
      require $path;
    }
  }
);