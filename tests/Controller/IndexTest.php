<?php
namespace App\Tests\Controller;

/**
 * 
 * @date 09.10.2016
 * @author Nikita Dezzpil Orlov <nikita@shvatka.ru>
 */
class IndexTest extends \App\Tests\AbstractTest
{
    public function testIndexAction()
    {        
        $client = $this->createClient();
        $crawler = $client->request('GET', '/');
//   
        $this->assertTrue($client->getResponse()->isOk());
        $this->assertCount(1, $crawler->filter('form'));
        $this->assertCount(1, $crawler->filter('label:contains("Логин")'));
        $this->assertEquals(1, 1);
    }
}
