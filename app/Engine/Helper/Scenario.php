<?php
namespace App\Engine\Helper;

/**
 * 
 * @date 24.10.2016
 * @author Nikita Dezzpil Orlov <nikita@shvatka.ru>
 */
class Scenario
{   
    protected $_unit;
    
    /**
     * Возвращает единицу сценария
     * Если такой нет, то будет создан объект для сохранения
     * @param int $levelNum
     * @param int $tipNum
     * @return \App\Engine\Helper\Scenario
     */
    function __construct($levelNum, $tipNum) 
    {
        $sql = "SELECT * FROM sh_game WHERE uroven=%d and n_podskazki=%d";
        $sqlPrepared = sprintf($sql, $levelNum, $tipNum);
        $db = \App\Adapter\DB::getInstance();
        $result = $db->query($sqlPrepared);
        if (!empty($result)) {
            $this->_unit = new \App\DataContainer(
                array_shift($result)
            );
        } else {
            $this->_unit = new \App\DataContainer([
                'uroven' => $levelNum,
                'n_podskazki' => $tipNum
            ]);
        }
        
        return $this;
    }
    
    /**
     * Существующая единица?
     * @return bool
     */
    function isExists()
    {
        return !!$this->_unit['n'];
    }
    
    /**
     * Изменить либо добавить загруженную единицу
     * @param type $key
     * @param type $text
     * @param type $minForTip
     * @param type $brainKey
     */
    function save($key, $text, $minForTip = 0, $brainKey = '') 
    {
        $levelNum = $this->_unit['uroven'];
        $tipNum = $this->_unit['n_podskazki'];
        if ($this->isExists()) {
            $sql = "UPDATE sh_game SET p_time=%d, keyw='%s', b_keyw='%s', text='%s' WHERE uroven=%d AND n_podskazki=%d";
        } else {
            $sql = "INSERT INTO sh_game (p_time, keyw, b_keyw, text, uroven, n_podskazki) VALUES (%d, '%s', '%s', '%s', %d, %d)";
        }
        
        $sqlPrepared = sprintf($sql, $minForTip, $key, $brainKey, $text, $levelNum, $tipNum);
        $db = \App\Adapter\DB::getInstance();
        $db->query($sqlPrepared);
    }
    
    /**
     * 
     */
    function delete()
    {
        $levelNum = $this->_unit['uroven'];
        $tipNum = $this->_unit['n_podskazki'];
        $sql = sprintf("DELETE FROM sh_game WHERE uroven=%d && n_podskazki=%d", $levelNum, $tipNum);
        $db = \App\Adapter\DB::getInstance();
        $db->query($sql);
    }
}
