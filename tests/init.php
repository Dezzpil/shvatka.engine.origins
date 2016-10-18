<?php
// Жестко определяем значение окружения
if (!defined('APPLICATION_ENV')) {
    define('APPLICATION_ENV', 'testing');
}

// запускаем приложение
$app = require __DIR__ . '/../app.php';
return $app;