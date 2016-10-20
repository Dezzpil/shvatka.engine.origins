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
    
    /**
     * Войти под пользователем
     * @return \App\Auth
     */
    protected function _loginUser()
    {
        $client = $this->createClient();
        $client->request('POST', '/index/login', ['name' => 'Shepard', 'password' => 'Normandia']);
        $session = $client->getRequest()->getSession();
        $auth = new \App\Auth($session);
        return $auth;
    }
    
    /**
     * Дать пользователю админские права для работы с игрой
     * @param string $login
     */
    protected function _makeAdmin($login)
    {
        $client = $this->createClient();
        $client->request('GET', '/tools/scenario', ['name' => 'Shepard']);
    }
    
    protected function _unmakeAdmin($login)
    {
        $client = $this->createClient();
        $client->request('GET', '/tools/unscenario', ['name' => 'Shepard']);
    }
}
