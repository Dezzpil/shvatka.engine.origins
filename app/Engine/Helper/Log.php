<?php
namespace App\Engine\Helper;

/**
 * 
 * @date 16.11.2016
 * @author Nikita Dezzpil Orlov <nikita@shvatka.ru>
 */
class Log
{   
    /**
     * Сделать запись в игровом логе
     * @param string $teamName
     * @param string $text
     * @param string $playerName
     * @param int $levelNum запись о том, что команда прошла уровень
     */
    function write($teamName, $text, $playerName, $levelNum = null)
    {
        $db = \App\Adapter\DB::getInstance();
        if ($levelNum) {
            $sql = sprintf(
                'INSERT INTO sh_log (comanda, time, keytext, autor, levdone) values("%s", "%s", "%s", "%s", %d)',
                $teamName, date('Y-m-d H:i:s'), $text, $playerName, $levelNum
            );
        } else {
            $sql = sprintf(
                'INSERT INTO sh_log (comanda, time, keytext, autor) values("%s", "%s", "%s", "%s")',
                $teamName, date('Y-m-d H:i:s'), $text, $playerName
            );
        }
        $db->query($sql);
    }
}
