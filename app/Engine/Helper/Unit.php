<?php

namespace App\Engine\Helper;

/**
 * 
 * @date 01.11.2016
 * @author Nikita Dezzpil Orlov <nikita@shvatka.ru>
 */
class Unit extends \App\DataContainer
{   
    /**
     * Возвращает единицу сценария
     * Если такой нет, то будет создан объект для сохранения
     * @param int $levelNum
     * @param int $tipNum
     * @return \App\Engine\Helper\Scenario
     */
    function __construct($levelNum, $tipNum) 
    {
        parent::__construct([]);
        
        $sql = "SELECT * FROM sh_game WHERE uroven=%d and n_podskazki=%d";
        $sqlPrepared = sprintf($sql, $levelNum, $tipNum);
        $db = \App\Adapter\DB::getInstance();
        $result = $db->query($sqlPrepared);
        if (!empty($result)) {
            $this->_data = array_shift($result);
        } else {
            $this->_data = [
                'uroven' => $levelNum,
                'n_podskazki' => $tipNum,
                'p_time' => 0
            ];
        }
    }
    
    /**
     * Существующая единица?
     * @return bool
     */
    function isExists()
    {
        return array_key_exists('n', $this->_data);
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
        $levelNum = $this->_data['uroven'];
        $tipNum = $this->_data['n_podskazki'];
        if ($this->isExists()) {
            $sql = "UPDATE sh_game SET p_time=%f, keyw='%s', b_keyw='%s', text='%s' WHERE uroven=%d AND n_podskazki=%d";
        } else {
            $sql = "INSERT INTO sh_game (p_time, keyw, b_keyw, text, uroven, n_podskazki) VALUES (%f, '%s', '%s', '%s', %d, %d)";
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
        $levelNum = $this->_data['uroven'];
        $tipNum = $this->_data['n_podskazki'];
        $sql = sprintf("DELETE FROM sh_game WHERE uroven=%d && n_podskazki=%d", $levelNum, $tipNum);
        $db = \App\Adapter\DB::getInstance();
        $db->query($sql);
    }
    
    /**
     * Получить все подсказки
     * включая следующую от указанной
     * @param int $currentTipNum
     * @return array
     */
    public function loadTipsByLevel($currentTipNum = null)
    {
        $db = \App\Adapter\DB::getInstance();
        $sql = "select * from sh_game WHERE n_podskazki > 0 AND uroven = " . intval($this->_data['uroven']);
        if ($currentTipNum) {
            $sql .= " AND n_podskazki <= " . ($currentTipNum + 1);
        }
        $sql .= ' ORDER BY n_podskazki';
        return $db->query($sql);
    }
}
