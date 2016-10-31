<?php

namespace App\Engine\Helper;

/**
 * 
 * @date 31.10.2016
 * @author Nikita Dezzpil Orlov <nikita@shvatka.ru>
 */
class Player 
{
    public function unregAll()
    {
        $db = \App\Adapter\DB::getInstance();
        $db->query("update sh_igroki set ch_dengi=0");
    }
    
    public function regToGame($id)
    {
        $db = \App\Adapter\DB::getInstance();
        $db->query("update sh_igroki set ch_dengi=1 where n=" . $id);
    }
    
    public function unregToGame($id)
    {
        $db = \App\Adapter\DB::getInstance();
        $db->query("update sh_igroki set ch_dengi=0 where n=" . $id);
    }
    
    /**
     * 
     * @param string $name
     * @return array
     */
    public function getListByTeamName($name)
    {
        $db = \App\Adapter\DB::getInstance();
        return $db->query("select * from sh_igroki where komanda='" . $name . "'");
    }
}
