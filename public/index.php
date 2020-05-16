<?php

use vendor\core\Router;

define('WWW', __DIR__);
define('ROOT', dirname(__DIR__));
define('CORE', ROOT . '/vendor/core');
define('APP', ROOT . '/app');

$query = rtrim($_SERVER['QUERY_STRING'], '/');

require_once '../vendor/libs/functions.php';

spl_autoload_register(function ($className){
    debug($className);
    $file = ROOT . "/$className.php";
    debug($file);
    if (file_exists($file)){
        require_once $file;
    }
});


Router::add('^pages/?(?P<action>[a-z-]+)?$', ['controller' => 'Posts']);
// ------- default routes below -------
Router::add('^$', ['controller' => 'Main', 'action' => 'index']);
Router::add('^(?P<controller>[a-z-]+)/?(?P<action>[a-z-]+)?$');

//debug(Router::getRoutes());

Router::dispatch($query);