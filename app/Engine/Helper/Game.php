<?php
namespace App\Engine\Helper;

/**
 * 
 * @date 19.10.2016
 * @author Nikita Dezzpil Orlov <nikita@shvatka.ru>
 */
class Game
{
    const STATUS_ACTUAL = 'п';
    const STATUS_ENDED = 'т';
    
    
    /**
     * 
     * @return array|null
     */
    public function load()
    {
        $db = \App\Adapter\DB::getInstance();
        $result = $db->query("SELECT * FROM sh_games WHERE status='" . self::STATUS_ACTUAL . "'");
        if (!empty($result)) {
            return $result[0];
        }
        return null;
    }
    
    /**
     * Изменить данные предстоящей игры
     * @param type $name
     * @param type $datetime
     * @param type $money
     */
    public function update($name, $datetime, $money)
    {
        $db = \App\Adapter\DB::getInstance();
        $sql = sprintf(
            "UPDATE sh_games SET g_name='%s', dt_g='%s', fond='%s' WHERE status='%s'",
            $name, $datetime, $money, self::STATUS_ACTUAL
        );
        $db->query($sql);
    }
    
    /**
     * Завершить игру
     */
    public function end()
    {
        $db = \App\Adapter\DB::getInstance();
        $db->query(sprintf(
            "update sh_games set status='%s' WHERE status='%s'",
            self::STATUS_ENDED, self::STATUS_ACTUAL
        ));
    }
    
    /**
     * Создать новую предстоящую игру
     * @param type $name
     * @param type $datetime
     * @param type $money
     */
    public function insert($name, $datetime, $money)
    {
        $db = \App\Adapter\DB::getInstance();
        $db->query("UPDATE sh_comands SET uroven=0, podskazka=0, dt_ur='0000-00-00 00:00:00'" );
        $db->query("DELETE FROM sh_log");
        $sql = sprintf(
            "INSERT INTO sh_games (g_name, dt_g, status, fond) VALUES ('%s', '%s', '%s', '%s')", 
            $name, $datetime, self::STATUS_ACTUAL, $money
        );
        $db->query($sql);
    }
    
    /**
     * Удалить текущую игру
     */
    public function delete()
    {
        $db = \App\Adapter\DB::getInstance();
        $db->query("update sh_comands set uroven=0, podskazka=0, dengi=0, dt_ur='0000-00-00 00:00:00'");
        $db->query("update sh_igroki set ch_dengi=0");
        $db->query("delete from sh_games where status='" . self::STATUS_ACTUAL . "'");
    }
    
    /**
     * 
     * @param string $name
     * @param string $datetime
     * @param string $money
     */
    public function save($name, $datetime, $money)
    {
        //ввод данных игры
        $db = \App\Adapter\DB::getInstance();
        if (!empty($this->load())) {
            // обновление данных предстоящей игры
            $sql = sprintf(
                "UPDATE sh_games SET g_name='%s', dt_g='%s', fond='%s' WHERE status='%s'",
                $name, $datetime, $money, self::STATUS_ACTUAL
            );
        } else {
            // создание записи о предстоящей игре
            $db->query("UPDATE sh_comands SET uroven=0, podskazka=0, dt_ur='0000-00-00 00:00:00'" );
            $db->query("DELETE FROM sh_log");
            $sql = sprintf(
                "INSERT INTO sh_games (g_name, dt_g, status, fond) VALUES ('%s', '%s', '%s', '%s')", 
                $name, $datetime, self::STATUS_ACTUAL, $money
            );
        }
        $db->query($sql);
    }
    
    /**
     * Сохранить информацию о распределении мест в этой игре.
     * Добавить команду на пьедестал
     * @param string $teamName
     */
    public function updateWinners($teamName)
    {
        $db = \App\Adapter\DB::getInstance();
        $db->query(sprintf(
            "update sh_games set pedistal=CONCAT(pedistal, '%s') WHERE status='%s'",
            $teamName . '<br>', self::STATUS_ACTUAL
        ));
    }
}
