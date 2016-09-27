<?php
error_reporting(E_ALL);
date_default_timezone_set('Europe/Moscow');
session_save_path(__DIR__ . '/../data/sessions');

// композер и автолоад наших классов
$loader = require __DIR__ . '/../vendor/autoload.php';

// Загружаем и разбираем конфигурацию
$configFilePath = __DIR__ . '/../config.json';
if ( ! file_exists($configFilePath)) {
    http_response_code(500);
    die('Невозможно запустить приложение. Файл конфигурации не существует');
}

$configContents = file_get_contents($configFilePath);
$config = json_decode($configContents, true);

// настариваем подключение
$db = $config['database'];
App\Adapter\DB::config($db['host'], $db['user'], $db['password'], $db['schema'], $db['port']);

// Запускаем и настраиваем микрофреймворк для целей маршрутизации по модулям
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Silex\Application;

$app = new Application;
$app['debug'] = $config['debug'];

// настраиваем роуты
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
        
        $result = $object->before($request, $app);
        if (!empty($result)) {
            // на случай редиректа
            return $result;
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

$app->error(function (Exception $e, Request $request, $code) {
    switch ($code) {
        case 404:
            $message = 'The requested page could not be found.';
            break;
        default:
            $message = 'Ошибка, код ' . $e->getCode() . ': ' . $e->getMessage();
    }
    
    return new Response($message);
});

$app->run();