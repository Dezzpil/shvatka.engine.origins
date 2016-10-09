<?php
// Жестко определяем значение окружения
define('APPLICATION_ENV', 'testing');

// запускаем приложение
return require __DIR__ . '/../app.php';