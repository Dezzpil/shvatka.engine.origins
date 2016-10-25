<?php
namespace App\Tests\Controller\Shvatka;

use Symfony\Component\HttpKernel\Client;

/**
 * 
 * @date 19.10.2016
 * @author Nikita Dezzpil Orlov <nikita@shvatka.ru>
 */
class EditTest extends \App\Tests\OrganizationControllerTest
{       
    const GAME_NAME = 'Игра 1: Эффект массы';
    const GAME_DATE = '+1 day';
    const GAME_MONEY = '10000 кредитов';
    
    public function setUp()
    {
        parent::setUp();
        
        // создаем игру, чтобы уровни привязывались к ней
        $helper = new \App\Engine\Helper\Game();
        if (empty($helper->load())) {
            $date = date('Y-m-d H:i:s', strtotime(self::GAME_DATE));
            $helper->insert(self::GAME_NAME, $date, self::GAME_MONEY);
        }
    }
    
    public function tearDown()
    {
        // удаляем игру только после всех тестов
        $client = $this->createClient();
        $client->request('GET', '/shvatka', 
            ['module' => 'reps', 'cmd' => 'scn']
        );
        
        $body = $client->getResponse()->getContent();
        if (strpos($body, 'SHКЛЮЧПОЛЯ') === false) {
            $helper = new \App\Engine\Helper\Game();
            $helper->delete();
            echo 'game deleted after levels checks';
        }
        
        parent::tearDown();
    }
    
    /**
     * @var array поля формы редактирования уровня
     */
    protected $_levelData = [
        'ptm' => 10,
        'textl' => 'Текст',
        'keyw' => 'SHКЛЮЧПОЛЯ',
        'b_keyw' => ''
    ];
    
    /**
     *
     * @var array поля формы редактирования подсказки
     */
    protected $_tipData = [
        'ptm' => 10,
        'textl' => 'Подсказка'
    ];


    /**
     * Подтвердить изменение формы и проверить, что данные 
     * отображаются на странице сценария
     * @param Client $client
     * @param array $data значения полей формы
     */
    protected function _submitForm(Client $client, array $data)
    {
        $crawler = $client->getCrawler();
        $form = $crawler->filter('form[name=edi]')->form();
        $client->submit($form, array_merge($form->getValues(), $data));
        
        $this->assertEquals(200, $client->getResponse()->isOk());
        
        // Смотрим, что уровень или подсказка отображаются в сценарии
        $crawler = $client->request('GET', '/shvatka', 
            ['module' => 'reps', 'cmd' => 'scn']
        );
        
        $body = $client->getResponse()->getContent();
        $this->assertContains($data['textl'], $body);
        if (array_key_exists('keyw', $data) && !empty($data['keyw'])) {
            $this->assertContains($data['keyw'], $body);
        }
        if (array_key_exists('b_keyw', $data) && !empty($data['b_keyw'])) {
            $this->assertContains($data['b_keyw'], $body);
        }
    }
    
    public function testAddLevel()
    {   
        // TODO вынести цифры в константы модуля
        $client = $this->createClient();
        $client->request('GET', '/shvatka', 
            ['module' => 'edit', 'cm' => 3, 'lev' => '1', 'npod' => '0']
        );

        $this->assertTrue($client->getResponse()->isOk());
        $this->_submitForm($client, $this->_levelData);
    }
    
//    public function testAddTwiceLevel()
//    {   
//        // TODO вынести цифры в константы модуля
//        $client = $this->createClient();
//        $client->request('GET', '/shvatka', 
//            ['module' => 'edit', 'cm' => 3, 'lev' => '1', 'npod' => '0']
//        );
//
//        $this->assertTrue($client->getResponse()->isOk());
//        $this->assertContains('Такой уровень или подсказка уже есть', $client->getResponse()->getContent());
//    }
    
    public function testEditLevel()
    {
        // TODO вынести цифры в константу модуля
        $client = $this->createClient();
        $client->request('GET', '/shvatka', 
            ['module' => 'edit', 'cm' => 1, 'lev' => "1", 'npod' => "0"]
        );
        $this->assertEquals(200, $client->getResponse()->isOk());
        
        $data = $this->_levelData;
        $data['textl'] .= ' изменен';
        $data['keyw'] .= 'ИЗМЕНЕН';
        $data['b_keyw'] = 'SHКЛЮЧМОЗГА';
        $this->_submitForm($client, $data);
    }
    
    public function testAddTip()
    {
        $client = $this->createClient();
        $client->request('GET', '/shvatka', 
            ['module' => 'edit', 'cm' => 3, 'lev' => "1", 'npod' => "1"]
        );
        
        $this->_submitForm($client, $this->_tipData);
    }
    
//    public function testAddTwiceTip()
//    {
//        $client = $this->createClient();
//        $client->request('GET', '/shvatka', 
//            ['module' => 'edit', 'cm' => 3, 'lev' => "1", 'npod' => "1"]
//        );
//        
//        $this->assertTrue($client->getResponse()->isOk());
//        $this->assertContains('Такой уровень или подсказка уже есть', $client->getResponse()->getContent());
//    }
    
    public function testEditTip()
    {
        $client = $this->createClient();
        $client->request('GET', '/shvatka', 
            ['module' => 'edit', 'cm' => 1, 'lev' => "1", 'npod' => "1"]
        );
        
        $data = $this->_tipData;
        $data['ptm'] += 5;
        $data['textl'] .= ' изменен';
        $this->_submitForm($client, $data);
    }
    
    public function testDeleteTip()
    {
        $client = $this->createClient();
        $crawler = $client->request('GET', '/shvatka', 
            ['module' => 'edit', 'cm' => 2, 'lev' => 1, 'npod' => 1]
        );
        
        $form = $crawler->filter('form')->form();
        $client->submit($form);
        
        $body = $client->getResponse()->getContent();
        $this->assertContains('Удалено', $body);
    }
    
    public function testDeleteLevel()
    {
        $client = $this->createClient();
        $crawler = $client->request('GET', '/shvatka', 
            ['module' => 'edit', 'cm' => 2, 'lev' => "1", 'npod' => "0"]
        );
        
        $form = $crawler->filter('form')->form();
        $client->submit($form);
        
        $body = $client->getResponse()->getContent();
        $this->assertContains('Удалено', $body);
    }
}