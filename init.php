<?php
error_reporting(E_ALL);
$loader = require __DIR__ . '/vendor/autoload.php';

define('ROOT_PATH', __DIR__ . '/');
define('ADMIN_GROUP', 4); // Главные администаторы


/**
 * Список используемых таблиц (тех, что без префикса sh_)
 * admin_logs         - mod_reps.php:264
 * pfields_content    - mod_shvatka.php:401
 * members            - shvatka:168
 */