<?php
// Переменная APPLICATION_ENV должна быть определена в настройках окружения
if (!defined('APPLICATION_ENV')) {
    define('APPLICATION_ENV', getenv('APPLICATION_ENV') ?: 'production');
}

// запускаем приложение
require_once __DIR__ . '/../app.php';