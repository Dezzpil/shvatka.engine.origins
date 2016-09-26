<?php
error_reporting(E_ALL);
date_default_timezone_set('Europe/Moscow');
$loader = require __DIR__ . '/../vendor/autoload.php';

/**
 * Список используемых таблиц (тех, что без префикса sh_)
 * admin_logs         - mod_reps.php:264
 * pfields_content    - mod_shvatka.php:401
 * members            - shvatka:168
 */

// Запускаем и настраиваем микрофреймворк для целей маршрутизации по модулям
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Silex\Application;

$app = new Application;
$app['debug'] = true;

$app->match(
    '/{controller}/{action}', 
    function(Request $request, Application $app, $controller, $action) use ($loader)
    {
        $controller = ucfirst(strtolower($controller));
        $action = strtolower($action);
        $class = '\\App\\Controller\\' . $controller;
        
        if (empty($loader->loadClass($class))) {
            throw new Exception('Контроллера ' . $controller . ' не существует');
        }
        
        $object = new $class;
        
        // Если контроллер использует трейт для хранения 
        // автолоадера классов, пока это нужно только для контроллера движка
        if (method_exists($object, 'setClassLoader')) {
            $object->setClassLoader($loader);
        }
        
        if (method_exists($object, $action)) {
            return $object->$action($request, $app);
        } else {
            throw new Exception('У контроллера ' . $controller . ' не существует действия ' . $action);
        }
    }
)
->value('controller', 'index')
->value('action', 'index');

$app->error(function (\Exception $e, Request $request, $code) {
    switch ($code) {
        case 404:
            $message = 'The requested page could not be found.';
            break;
        default:
            $message = 'We are sorry, but something went terribly wrong.';
    }

    var_dump($e->getCode(), $e->getMessage(), $e->getTrace());
    
    return new Response($message);
});

$app->run();