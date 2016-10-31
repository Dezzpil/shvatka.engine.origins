<?php

namespace App\Tests\Controller\Shvatka;

/**
 * 
 * @date 19.10.2016
 * @author Nikita Dezzpil Orlov <nikita@shvatka.ru>
 */
class RepsTest extends \App\Tests\OrganizationControllerTest
{   
    function testNoUpcomingGame()
    {
        $client = $this->createClient();
        $client->request('GET', '/shvatka',
            ['module' => 'reps', 'cmd' => 'addg']
        );

        // Тупо, но проверяем, что нет кнопки Удалить игру
        $this->assertNotContains('Удалить игру', $client->getResponse()->getContent());
    }
    
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