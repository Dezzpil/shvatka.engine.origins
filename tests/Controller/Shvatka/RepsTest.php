<?php

namespace App\Tests\Controller\Shvatka;

/**
 * 
 * @date 19.10.2016
 * @author Nikita Dezzpil Orlov <nikita@shvatka.ru>
 */
class RepsTest extends \App\Tests\OrganizationControllerTest
{   
    /**
     * @group game
     */
    function testNoUpcomingGame()
    {
        $client = $this->createClient();
        $client->request('GET', '/shvatka',
            ['module' => 'reps', 'cmd' => 'addg']
        );

        // Тупо, но проверяем, что нет кнопки Удалить игру
        $this->assertNotContains('Удалить игру', $client->getResponse()->getContent());
    }
    
    /**
     * @group game
     */
    function testCreateGame()
    {
        $client = $this->createClient();
        $crawler = $client->request('GET', '/shvatka', 
            ['module' => 'reps', 'cmd' => 'addg']
        );
        
        // Тупо, нажимаем Сохранить игру и проверяем ответ
        $buttonCrawlerNode = $crawler->selectButton('Создать игру');
        $form = $buttonCrawlerNode->form();
        $client->submit($form, [
            'gn' => '', // name
            'gt' => date('Y-m-d H:i:s', strtotime("+2 hour")), // date and time
            'gf' => ''  // money
        ]);
        
        $this->assertContains('Игра создана', $client->getResponse()->getContent());
    }
    
    /**
     * @group game
     */
    function testUpdateGame()
    {
        $client = $this->createClient();
        $crawler = $client->request('GET', '/shvatka', 
            ['module' => 'reps', 'cmd' => 'addg']
        );
        
        // Тупо, нажимаем Сохранить игру и проверяем ответ
        $buttonCrawlerNode = $crawler->selectButton('Подтвердить изменения');
        $form = $buttonCrawlerNode->form();
        $client->submit($form, [
            'gn' => 'Тестовая игра', // name
            'gt' => date('Y-m-d H:i:s', strtotime("+4 hour")), // date and time
            'gf' => '0 биткоинов'  // money
        ]);
        
        $this->assertContains('Изменения в настройки игры внесены', $client->getResponse()->getContent());
    }
    
    /**
     * @group reps-deng
     */
    function testRegToGame()
    {
        $client = $this->createClient();
        $client->request('GET', '/shvatka', 
            ['module' => 'reps', 'cmd' => 'deng']
        );
        
        $this->assertContains('зарегистрировано', $client->getResponse()->getContent());
    }
    
    /**
     * @group reps-deng
     */
    function testRegToGameMember()
    {
        $client = $this->createClient();
        $crawler = $client->request('POST', '/shvatka', 
            ['module' => 'reps', 'cmd' => 'deng', 'y' => 1, 'i2' => '1']
        );
        
        $input = $crawler->filter('input[name=i2]');
        $this->assertEquals(1, $input->count());
        $this->assertEquals('checked', $input->attr('checked'));
        
        $input = $crawler->filter('input[name=i1]');
        $this->assertNotEquals('checked', $input->attr('checked'));
    }
    
    /**
     * @group scn
     */
    function testDisplayLevel()
    {
        $level = new \App\Engine\Helper\Unit(1, 0);
        $level->save('SHKEY', 'Ключ уровня SHKEY');
        
        $client = $this->createClient();
        $crawler = $client->request('GET', '/shvatka', 
            ['module' => 'reps', 'cmd' => 'scn']
        );
        
        $this->assertContains('SHKEY', $client->getResponse()->getContent());
        $result = $crawler->filter('form')->each(function($node) {
            return $node->attr('level') == 1 && $node->attr('tip') == 0;
        });
        $this->assertContains(true, $result);
    }
  
    /**
     * @group scn
     */
    function testDisplayTip()
    {
        $level = new \App\Engine\Helper\Unit(1, 1);
        $level->save(null, 'Подсказка 1');
        
        $client = $this->createClient();
        $crawler = $client->request('GET', '/shvatka', 
            ['module' => 'reps', 'cmd' => 'scn']
        );
        
        $this->assertContains('Подсказка 1', $client->getResponse()->getContent());
        $result = $crawler->filter('form')->each(function($node) {
            return $node->attr('level') == 1 && $node->attr('tip') == 1;
        });
        $this->assertContains(true, $result);
    }
    
    /**
     * @group scn
     */
    function testDeleteTip()
    {
        $level = new \App\Engine\Helper\Unit(1, 1);
        $level->delete();
        
        $client = $this->createClient();
        $crawler = $client->request('GET', '/shvatka', 
            ['module' => 'reps', 'cmd' => 'scn']
        );
        
        $this->assertNotContains('Подсказка 1', $client->getResponse()->getContent());
        $result = $crawler->filter('form')->each(function($node) {
            return ($node->attr('level') == 1 && $node->attr('tip') == 1);
        });
        $this->assertNotContains(true, $result);
    }
    
    /**
     * @group scn
     */
    function testDeleteAllScenario()
    {
        $client = $this->createClient();
        $client->request('POST', '/shvatka', 
            ['module' => 'reps', 'cmd' => 'scn', 'delg' => 1]
        );
        
        $this->assertNotContains('SHKEY', $client->getResponse()->getContent());
    }
    
    /**
     * @group game
     */
    function testDeleteGame()
    {
        $client = $this->createClient();
        $crawler = $client->request('GET', '/shvatka', 
            ['module' => 'reps', 'cmd' => 'addg']
        );
        
        // Тупо, нажимаем Сохранить игру и проверяем ответ
        $buttonCrawlerNode = $crawler->selectButton('Удалить игру');
        $form = $buttonCrawlerNode->form();
        $client->submit($form);
        
        $this->assertContains('Игра удалена', $client->getResponse()->getContent());
    }
}