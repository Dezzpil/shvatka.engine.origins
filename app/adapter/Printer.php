<?php
namespace App\Adapter;

/**
 * Description of Printer
 * 
 * @date 2016.09.15
 * @author Nikita Dezzpil Orlov
 */
class Printer
{
    use \App\Singleton;
    
    protected $_data = '';
    
    function add_output($string)
    {
        $this->_data .= $string;
        return $this;
    }
    
    /**
     * Параметры могут быть, например, такие:
     * array('OVERRIDE' => 0, 'TITLE' => 'СХВАТКА', 'NAV' => $this->nav)
     * 
     * @todo реализовать работу с шаблоном и подстановку параметров
     * @param type $params
     */
    function do_output(array $params = [])
    {
        echo $this->_data;
    }
    
}
