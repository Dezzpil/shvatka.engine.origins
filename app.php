<?php
// Базовые настройки
error_reporting();
date_default_timezone_set('Europe/Moscow');
session_save_path(__DIR__ . '/data/sessions');

// композер и автолоад наших классов
$loader = require __DIR__ . '/vendor/autoload.php';

// Загружаем и разбираем конфигурацию
try {
    $config = App\Config::parse(__DIR__ . '/config.json', APPLICATION_ENV);
} catch (Exception $e) {
    // ... на случай тестов
    $config = App\Config::getInstance();
}

// Настраиваем приложение
$db = $config['database'];
App\Adapter\DB::config($db['host'], $db['user'], $db['password'], $db['schema'], $db['port']);

// Запускаем и настраиваем микрофреймворк для маршрутизации по модулям
use Symfony\Component\Debug\ExceptionHandler;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Silex\Provider\SessionServiceProvider;
use Silex\Provider\TwigServiceProvider;
use App\Application;

ExceptionHandler::register();

$app = new Application;
$app['debug'] = $config['debug'];

// без этого кука в тестах будет генериться каждый раз по-новой,
// потому что приложение не будет подхватывать ранее созданную
$app->register(new SessionServiceProvider(), [
    // это нужно определить именно здесь, если мы запускаем тесты
    // позже уже не получается
    // http://stackoverflow.com/questions/13586447/phpunit-fails-when-using-silex-sessionserviceprovider
    'session.test' => APPLICATION_ENV === 'testing'
]);

$app->register(new TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/app/view',
    'twig.options' => [
        'cache' => __DIR__ . '/cache',
        'debug' => $config['debug'],
        'strict_variables' => false
    ]
));

$app['twig']->addExtension(new Twig_Extension_Debug());

/**
 * Простой роут для проверки работы рендера представлений
 */
//$app->match('/render', function(Request $r, Application $a) {
//   return $a->render('test.twig', ['foo' => 'bar', 'pew' => 'pew']);
//});

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
            $result = $object->$action($request, $app);
            return $result;
        } else {
            $app->abort(404, 'У контроллера ' . $controller . ' не существует действия ' . $action);
        }
    }
)
->value('controller', 'index')
->value('action', 'index');

$app->error(function (Exception $e, Request $request, $code) use ($app) {
    switch ($code) {
        case 404:
            $message = 'Ошибка 404. Страница не найдена.<br/>' . $e->getMessage();
            break;
        case 500:
            var_dump($e->getMessage(), $e->getTraceAsString());
            die;
        default:
            return $app->render($code . '.twig', ['error' => $e->getMessage(), 'code' => $code]);
            //$message = 'Ошибка, код ' . $e->getCode() . ': ' . $e->getMessage();
    }
    
    return new Response($message);
});

// здесь запускать приложение не надо
// это зависит от того, как мы его используем:
// * если тесты - они запускают самостоятельно, ориентируясь на код функц. теста
// 
// * если запрос с сервара - вызов происходит из public/index.php, и запуск
//   ориентируется на данные, полученные от сервера
return $app;