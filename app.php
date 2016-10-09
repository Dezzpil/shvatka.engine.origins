<?php
// Базовые настройки
error_reporting(E_ALL);
date_default_timezone_set('Europe/Moscow');
session_save_path(__DIR__ . '/data/sessions');

// композер и автолоад наших классов
$loader = require __DIR__ . '/vendor/autoload.php';

// Загружаем и разбираем конфигурацию
$config = App\Config::parse(__DIR__ . '/config.json', APPLICATION_ENV);

// Настраиваем приложение
$db = $config['database'];
App\Adapter\DB::config($db['host'], $db['user'], $db['password'], $db['schema'], $db['port']);

// Запускаем и настраиваем микрофреймворк для маршрутизации по модулям
use Symfony\Component\Debug\ExceptionHandler;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Silex\Application;

ExceptionHandler::register();

$app = new Application;
$app['debug'] = $config['debug'];

// настраиваем роуты
$app->match(
    '/{controller}/{action}', 
    function(Request $request, Application $app, $controller, $action) use ($loader)
    {
        $controller = ucfirst(strtolower($controller));
        $class = '\\App\\Controller\\' . $controller;
        
        if (empty($loader->findFile($class))) {
            $app->abort(404, 'Контроллера ' . $controller . ' не существует');
        }
        
        $object = new $class;
        
        // Если контроллер использует трейт для хранения 
        // автолоадера классов, пока это нужно только для контроллера движка
        if (method_exists($object, 'setClassLoader')) {
            $object->setClassLoader($loader);
        }
        
        $result = $object->before($request, $app);
        if (!empty($result)) {
            // на случай редиректа
            return $result;
        }
        
        $action = strtolower($action);
        if (method_exists($object, $action)) {
            return $object->$action($request, $app);
        } else {
            $app->abort(404, 'У контроллера ' . $controller . ' не существует действия ' . $action);
        }
    }
)
->value('controller', 'index')
->value('action', 'index');

$app->error(function (Exception $e, Request $request, $code) {
    switch ($code) {
        case 404:
            $message = 'Ошибка 404. Страница не найдена.<br/>' . $e->getMessage();
            break;
        default:
            $message = 'Ошибка, код ' . $e->getCode() . ': ' . $e->getMessage();
    }
    
    return new Response($message);
});

$app->run();
return $app;