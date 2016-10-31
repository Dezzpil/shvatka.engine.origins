<?php

namespace App\Engine\Helper;

/**
 * 
 * @date 31.10.2016
 * @author Nikita Dezzpil Orlov <nikita@shvatka.ru>
 */
class Team
{   
    /**
     * Получить запись о команде
     * @param string $name
     * @return array
     * @throws \App\Engine\Exception
     */
    public function loadByName($name)
    {
        $db = \App\Adapter\DB::getInstance();
        $result = $db->query(sprintf("select * from sh_comands where nazvanie='%s'", $name));
        if (!empty($result)) {
            return array_shift($result);
        }
        throw new \App\Engine\Exception(strintf('Команды с названием %s не существует', $name));
    }
    
    public function unregAll()
    {
        $db = \App\Adapter\DB::getInstance();
        $db->query("update sh_comands set dengi=0");
    }
    
    /**
     * 
     * @param int $id
     */
    public function regToGame($id)
    {
        $db = \App\Adapter\DB::getInstance();
        $db->query("update sh_comands set dengi=1 where n=" . $id);
    }
    
    /**
     * 
     * @param int $id
     */
    public function unregToGame($id)
    {
        $db = \App\Adapter\DB::getInstance();
        $db->query("update sh_comands set dengi=0 where n=" . $id);
    }

    /**
     * Установить игровые значения для команды
     * @param string $teamName
     * @param string $levelStartTime Y-m-d H:i:s
     * @param int $levelNum
     * @param int $tipNum
     */
    public function updateGameStatus($teamName, $levelStartTime, $levelNum = 1, $tipNum = 0)
    {
        \App\Adapter\DB::getInstance()->query(sprintf(
            "update sh_comands set uroven=%d, podskazka=%d, dt_ur='%s' WHERE nazvanie='%s'", 
            $levelNum, $tipNum, $levelStartTime, $teamName
        ));
    }
    
    /**
     * Записать игру в список сыгранных
     * и обнулить игровые значения для команды
     * @param string $teamName
     * @param int $gameId
     */
    public function updateGameHistory($teamName, $gameId)
    {
        \App\Adapter\DB::getInstance()->query(sprintf(
            "update sh_comands set uroven=0, podskazka=0, cmp_games=CONCAT(cmp_games, '%d ') WHERE nazvanie='%s'",
            $gameId, $teamName
        ));
    }
    
    /**
     * 
     * @return array
     */
    public function loadList()
    {
        $db = \App\Adapter\DB::getInstance();
        return $db->query("select * from sh_comands");
    }
    
    /**
     * 
     * @return array
     */
    public function loadRegedList()
    {
        $db = \App\Adapter\DB::getInstance();
        return $db->query("select * from sh_comands WHERE dengi=1");
    }
}
