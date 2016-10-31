<?php
namespace App\Engine\Helper;

/**
 * 
 * @date 31.10.2016
 * @author Nikita Dezzpil Orlov <nikita@shvatka.ru>
 */
class Player
{   
    /**
     * Получить игрока по id пользователя
     * @todo сейчас предполагается, что member.id === sh_igroki.n, но это неявная связь
     * @param int $id
     * @return array
     * @throws \App\Engine\Exception
     */
    public function loadByMemberId($id)
    {
        $db = \App\Adapter\DB::getInstance();
        $result = $db->query(sprintf("select * from sh_igroki where n=%d", $id));
        if (!empty($result)) {
            return array_shift($result);
        }
        throw new \App\Engine\Exception(strintf('Пользователя с id %d не существует', $id));
    }
    
    /**
     * Зарегестрировать ни игру
     * @param int $playerId
     */
    public function regToGame($playerId)
    {
        $db = \App\Adapter\DB::getInstance();
        $db->query("update sh_igroki set ch_dengi=1 where n=" . $playerId);
    }
    
    /**
     * Отменить регистрацию на игру
     * @param int $playerId
     */
    public function unregToGame($playerId)
    {
        $db = \App\Adapter\DB::getInstance();
        $db->query("update sh_igroki set ch_dengi=0 where n=" . $playerId);
    }
    
    /**
     * Сохранить данные об участии в игре
     * и обнулить данные регистрации
     * @param int $playerId 
     * @param int $gameId
     */
    public function updateGameHistory($playerId, $gameId)
    {
        $db = \App\Adapter\DB::getInstance();
        $db->query(sprintf(
            "update sh_igroki set ch_dengi=0, games=CONCAT(games, '%s ') WHERE n=%d",
            $gameId, $playerId
        ));
    }
    
    public function unregAll()
    {
        $db = \App\Adapter\DB::getInstance();
        $db->query("update sh_igroki set ch_dengi=0");
    }
    
    /**
     * 
     * @param string $name
     * @return array
     */
    public function loadListByTeamName($name)
    {
        $db = \App\Adapter\DB::getInstance();
        return $db->query("select * from sh_igroki where komanda='" . $name . "'");
    }
    
    /**
     * 
     * @return array
     */
    public function loadRegedList()
    {
        $db = \App\Adapter\DB::getInstance();
        return $db->query("select * from sh_igroki where ch_dengi > 0 order by komanda");
    }
}
