<?php
namespace App\Tests;

use Silex\WebTestCase;

/**
 * 
 * @date 09.10.2016
 * @author Nikita Dezzpil Orlov <nikita@shvatka.ru>
 */
abstract class AbstractTest extends WebTestCase
{
    public function createApplication()
    {
        $app = require __DIR__.'/init.php';
        unset($app['exception_handler']);

        return $app;
    }
}
