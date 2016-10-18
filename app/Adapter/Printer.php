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
        // генерируем навигацию из параметров
        if (array_key_exists('NAV', $params)) {
            $this->_data = '<br />&nbsp;<br />' . $this->_data;
            foreach ($params['NAV'] as $item) {
                $this->_data = $item . '&nbsp;&nbsp;' . $this->_data;
            }
            $this->_data = '<br />&nbsp;<br />' . $this->_data;
        }
        
        if (array_key_exists('TITLE', $params)) {
            //$this->_data = '<body><h1>' . $params['TITLE'] . '</h1>' . $this->_data;
            $this->_data = '<head><title>' . $params['TITLE'] . '</title></head>' . $this->_data;
        }
        
        return $this->_data;
    }
    
}
