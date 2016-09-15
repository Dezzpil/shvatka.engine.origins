<?php

error_reporting(E_ALL);

$loader = require __DIR__ . '/../vendor/autoload.php';

/**
 * Список используемых таблиц (тех, что без префикса sh_)
 * admin_logs         - mod_reps.php:264
 * pfields_content    - mod_shvatka.php:401
 */

$adapter = new App\Adapter\IPSClass($_REQUEST);
$adapter->setDB(\App\Adapter\DB::getInstance());
$adapter->setPrinter(App\Adapter\Printer::getInstance());

$mod = new Shvatka\Shvatka;
$mod->ipsclass = $adapter;
$mod->run_module();

exit();

/**
 * try cmd=cap
 * 
 * cnc=
 * del=
 * yes=
 * 
 */

