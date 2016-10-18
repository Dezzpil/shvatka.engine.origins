<?php
namespace App\Tests\Controller;

/**
 * 
 * @date 09.10.2016
 * @author Nikita Dezzpil Orlov <nikita@shvatka.ru>
 */
class IndexTest extends \App\Tests\AbstractTest
{
    public function testNoNameGivenAction()
    {
        $client = $this->createClient();
        $client->request('POST', '/index/login', ['name' => '', 'password' => 'Normandia']); 
        $body = $client->getResponse()->getContent();
        $this->assertGreaterThan(0, strpos($body, \App\Auth::EXC_NONAME));
    }
    
    public function testBadNameGivenAction()
    {
        $client = $this->createClient();
        $client->request('POST', '/index/login', ['name' => 'Sh', 'password' => 'Normandia']); 
        $body = $client->getResponse()->getContent();
        $this->assertGreaterThan(0, strpos($body, \App\Auth::EXC_NOUSER));
    }
    
    public function testBadPasswordGivenAction()
    {
        $client = $this->createClient();
        $client->request('POST', '/index/login', ['name' => 'Shepard', 'password' => 'No']); 
        $body = $client->getResponse()->getContent();
        $this->assertGreaterThan(0, strpos($body, \App\Auth::EXC_BADDATA));
    }
    
    public function testLoginAction()
    {   
        $client = $this->createClient();
        $client->request('POST', '/index/login', ['name' => 'Shepard', 'password' => 'Normandia']);
        $this->assertTrue(!empty($client->getRequest()->getSession()->get('auth')));
        $this->assertTrue($client->getResponse()->isRedirect());
    }
    
    public function testLogoutAction()
    {
        $client = $this->createClient();
        $client->request('POST', '/index/logout');
        $this->assertTrue($client->getResponse()->isRedirect());
        $this->assertTrue(empty($client->getRequest()->getSession()->get('auth')));
    }
}
