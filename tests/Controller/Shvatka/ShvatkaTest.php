<?php
namespace App\Tests\Controller\Shvatka;

/**
 * 
 * @date 11.10.2016
 * @author Nikita Dezzpil Orlov <nikita@shvatka.ru>
 */
class ShvatkaTest extends \App\Tests\AbstractTest
{
    protected function _loginUser()
    {
        $client = $this->createClient();
        $client->request('POST', '/index/login', ['name' => 'Shepard', 'password' => 'Normandia']);
    }
    
    public function testNoUpcomingGameAction()
    {        
        $this->_loginUser();
        $client = $this->createClient();
        $client->request('GET', '/shvatka', ['module' => 'shvatka']);
        $this->assertTrue($client->getResponse()->isOk());
        
        $html = $client->getResponse()->getContent();
        $test = strpos($html, 'Дата предстоящей игры пока не определена');
        
        $this->assertGreaterThan(0, $test);
    }
    
    // TODO продолжить
}
