<?php

namespace App\Engine\Helper;

/**
 * 
 * @date 31.10.2016
 * @author Nikita Dezzpil Orlov <nikita@shvatka.ru>
 */
class Team 
{
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
     * 
     * @return array
     */
    public function getList()
    {
        $db = \App\Adapter\DB::getInstance();
        return $db->query("select * from sh_comands");
    }
}
