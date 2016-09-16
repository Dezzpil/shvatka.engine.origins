<?php
require_once __DIR__ . '/../init.php';

define('LOGIN', 'Jacob');

$DBAdapter = \App\Adapter\DB::getInstance();
        
$adapter = new App\Adapter\IPSClass($_REQUEST);
$adapter->setDB($DBAdapter);
$adapter->setPrinter(App\Adapter\Printer::getInstance());

// Аутентифицированный пользователь
$member = $DBAdapter->query("select * from members where name='" . LOGIN ."'");
$adapter->setAuthedUser($member[0]);

$adapter->setParams([
    'admin_group' => ADMIN_GROUP
]);

// TODO добавить маршрутизатор, ориентирующийся на module={mod}
// TODO Silex

$mod = new Shvatka\Reps();
$mod->ipsclass = $adapter;
$mod->run_module();

exit();
