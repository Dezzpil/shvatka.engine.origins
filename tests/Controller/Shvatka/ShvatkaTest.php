<?php
namespace App\Tests\Controller\Shvatka;

/**
 * 
 * @date 11.10.2016
 * @author Nikita Dezzpil Orlov <nikita@shvatka.ru>
 */
class ShvatkaTest extends \App\Tests\AbstractTest
{
    /**
     * @skip
     */
    public function testNoUpcomingGameAction()
    {        
        $this->_loginUser();
        $client = $this->createClient();
        $client->request('GET', '/shvatka', ['module' => 'shvatka']);
        $this->assertTrue($client->getResponse()->isOk());
        $this->assertContains(
            'Дата предстоящей игры пока не определена',
            $client->getResponse()->getContent()
        );
    }
    
    // TODO продолжить
}
