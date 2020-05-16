<?php

namespace vendor\core;

/**
 * Router
 */
class Router
{
    protected static $routes = [];
    protected static $route = [];

    public static function add($regexp, array $route = []){
        self::$routes[$regexp] = $route;
    }

    public static function getRoutes(){
        return self::$routes;
    }

    public static function getRoute(){
        return self::$route;
    }


    /**
     * проверяет соответствие Url из запроса одному из шаблонов
     * @param string $url входящий URL
     * @return bool
     */
    private static function matchRoute($url){
        foreach (self::$routes as $pattern => $route){
            if (preg_match("#$pattern#i", $url, $matches)){
                foreach ($matches as $k => $v){
                    if (is_string($k)) $route[$k] = $v;
                }
                if (!isset($route['action'])) $route['action'] = 'index';
                self::$route = $route;
                debug($route);
                return true;
            }
        }
        return false;
    }


    /**
     * определяет контроллер и перенаправляет на него запрос
     * @param string $url входящий URL
     * @return void
     */
    public static function dispatch($url){
        if (!self::matchRoute($url)) {
            http_response_code('404');
            include '404.html';
            return;
        }

        $controllerName = 'app\controllers\\' . self::toCamelCase(self::$route['controller']);
        if (!class_exists($controllerName)){
            echo "Контроллер <b>$controllerName</b> не найден";
            return;
        }

        $controller = new $controllerName;
        $action = 'action' . self::toCamelCase(self::$route['action']);
        if (!method_exists($controller, $action)) {
            echo "Action <b>$action</b> не найден";
            return;
        }

        $controller->$action();
    }

    /**
     * Преобразует строку формата kebab-case в формат CamelCase
     * @param string $str строка для преобразования
     * @return string
     */
    private static function toCamelCase($str){
        return str_replace(' ', '', ucwords(str_replace('-', ' ', $str)));
    }
}