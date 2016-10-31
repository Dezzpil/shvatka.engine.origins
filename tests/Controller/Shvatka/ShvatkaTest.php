<?php
namespace App\Tests\Controller\Shvatka;

/**
 * 
 * @date 11.10.2016
 * @author Nikita Dezzpil Orlov <nikita@shvatka.ru>
 */
class ShvatkaTest extends \App\Tests\ControllerTest
{
    protected $_member;
    
    protected function setUp()
    {
        parent::setUp();
        $auth = $this->_loginUser();
        $this->_member = $auth->getAuthedMemder();
    }
    
    /**
     * @group shvatka
     */
    public function testNoUpcomingGame()
    {        
        $client = $this->createClient();
        $client->request('GET', '/shvatka', ['module' => 'shvatka']);
        $this->assertTrue($client->getResponse()->isOk());
        $this->assertContains(
            'Дата предстоящей игры пока не определена',
            $client->getResponse()->getContent()
        );
    }
    
    protected $_gameName = 'Тестовая игра';
    protected $_gameDate = '2038-01-01 23:00:00';
    protected $_gameMoney = 'ноль';
    
    /**
     * @group shvatka
     */
    public function testGameNoReg()
    {   
        $helperGame = new \App\Engine\Helper\Game;
        $helperGame->insert($this->_gameName, date('Y-m-d H:i:s'), $this->_gameMoney);
        
        $client = $this->createClient();
        $client->request('GET', '/shvatka', ['module' => 'shvatka']);
        $this->assertTrue($client->getResponse()->isOk());
        
        $this->assertContains(
            'Одно из трёх',
            $client->getResponse()->getContent()
        );
        
        $helperGame->delete();
    }
    
        /**
     * @group shvatka
     */
    public function testUpcomingGameWithReg()
    {
        $helperGame = new \App\Engine\Helper\Game;
        $helperGame->insert($this->_gameName, $this->_gameDate, $this->_gameMoney);
        
        // Получаем игрока и регаем его на игру
        $playerHelper = new \App\Engine\Helper\Player;
        $player = $playerHelper->loadByMemberId($this->_member['id']);
        $playerHelper->regToGame($player['n']);
        
        // Нужно зарегать и команду, вообще хотя бы одну
        $teamHelper = new \App\Engine\Helper\Team;
        $team = $teamHelper->loadByName($player['komanda']);
        $teamHelper->regToGame($team['n']);
        
        $client = $this->createClient();
        $client->request('GET', '/shvatka', ['module' => 'shvatka']);
        $this->assertTrue($client->getResponse()->isOk());
        
        $this->assertContains(
            'Название игры: ' . $this->_gameName,
            $client->getResponse()->getContent()
        );
        
        $helperGame->delete();
    }
    
    /**
     * Заготовка для уровней игры
     * @var array
     */
    protected $_unitTemplate = [
        'text' => 'Уровень %d',
        'keyw' => 'SH%d',
        'ptm' => 0.05,
        'tips' => [
            [
                'text' => 'Подсказка %d',
                'ptm' => 0.05
            ],
            [
                'text' => 'SH%d',
                'ptm' => 0
            ]
        ]
    ];
    
    /**
     * Тест прохождения элементарной игры без мозговых
     * @group shvatka
     * @group action
     */
    public function testGameRegOK()
    {   
        // Создаем игру
        $helperGame = new \App\Engine\Helper\Game;
        $helperGame->insert($this->_gameName, date('Y-m-d H:i:s'), $this->_gameMoney);
        
        // Получаем игрока и регаем его на игру
        $playerHelper = new \App\Engine\Helper\Player;
        $player = $playerHelper->loadByMemberId($this->_member['id']);
        $playerHelper->regToGame($player['n']);
        
        // Нужно зарегать и команду, вообще хотя бы одну
        $teamHelper = new \App\Engine\Helper\Team;
        $team = $teamHelper->loadByName($player['komanda']);
        $teamHelper->regToGame($team['n']);
        
        $levelsCount = 2;
        
        // Правильно создаем уровни игры
        for ($i = 1; $i <= $levelsCount; $i++) {
            // создаем уровень
            $unitHelper = new \App\Engine\Helper\Unit($i, 0);
            $text = sprintf($this->_unitTemplate['text'], $i);
            $key = sprintf($this->_unitTemplate['keyw'], $i);
            $ptm = $this->_unitTemplate['ptm'];
            $unitHelper->save($key, $text, $ptm);
            
            $tips = $this->_unitTemplate['tips'];
            for ($j = 0; $j < count($tips); $j++) {
                $unitHelper = new \App\Engine\Helper\Unit($i, $j + 1);
                $text = sprintf($tips[$j]['text'], $i);
                $ptm = $tips[$j]['ptm'];
                $unitHelper->save(null, $text, $ptm);
            }
        }
        
        $client = $this->createClient();
        for ($i = 1; $i <= $levelsCount; $i++) {
            
            $client->request('GET', '/shvatka', ['module' => 'shvatka', 't' => time()]);
            $this->assertContains('Уровень ' . $i, $client->getResponse()->getContent());
            sleep(5);

            $client->request('GET', '/shvatka', ['module' => 'shvatka', 't' => time()]);
            $this->assertContains('Подсказка 1', $client->getResponse()->getContent());
            sleep(5);

            $client->request('GET', '/shvatka', ['module' => 'shvatka', 't' => time()]);
            $content = $client->getResponse()->getContent();
            preg_match('/(SH'.$i.')/ism', $content, $match);
            $this->assertNotEmpty($match);
            
            $client->request('POST', '/shvatka', [
                'module' => 'shvatka', 'cmd' => 'sh', 'keyw' => $match[1], 't' => time()
            ]);
        }
        
        $this->assertContains('Поздравляем', $client->getResponse()->getContent());
            
        $teamHelper->unregAll();
        $playerHelper->unregAll();
        
        $scenarioHelper = new \App\Engine\Helper\Scenario;
        $scenarioHelper->clear();
        
        $helperGame->delete();
    }
    
}
