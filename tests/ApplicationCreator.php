<?php
namespace App\Tests;

/**
 * 
 * @date 24.10.2016
 * @author Nikita Dezzpil Orlov <nikita@shvatka.ru>
 */
trait ApplicationCreator
{
    public function createApplication()
    {
        $app = require __DIR__.'/init.php';
        unset($app['exception_handler']);

        return $app;
    }
}
