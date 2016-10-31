<?php
namespace App\Engine\Helper;

/**
 * 
 * @date 24.10.2016
 * @author Nikita Dezzpil Orlov <nikita@shvatka.ru>
 */
class Scenario
{   
    /**
     * Удалить весь сценарий
     */
    public function clear()
    {
        $db = \App\Adapter\DB::getInstance();
        $db->query('DELETE FROM sh_game');
    }
    
    /**
     * Получить список всех уровней и подсказок
     * @return array
     */
    public function loadList()
    {
        $db = \App\Adapter\DB::getInstance();
        return $db->query('SELECT * FROM sh_game ORDER BY uroven, n_podskazki');
    }
}
