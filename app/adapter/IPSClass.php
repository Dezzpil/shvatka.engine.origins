<?php
namespace App\Adapter;

/**
 * Description of IPSClass
 * 
 * @date 2016.09.16
 * @author Nikita Dezzpil Orlov
 */
class IPSClass
{
    /**
     *
     * @var array 
     */
    protected $_data = [];
    
    /**
     *
     * @var DB 
     */
    protected $_DB = null;


    public function __construct($data = [])
    {
        $this->_data = $data;
    }
    
    /**
     * 
     * @param \App\Adapter\DB $adapter
     * @return \App\Adapter\IPSClass
     */
    public function setDB(DB $adapter)
    {
        $this->_DB = $adapter;
        return $this;
    }
    
    /**
     *
     * @var array
     */
    protected $_authedUser = [ 'id' => 0, 'mgroup' => 0, 'name' => 'EuRo' ];
    
    /**
     * Минимальные ключи массива:
     * id, mgroup, name
     * 
     * @todo Что такое mgroup?
     * 
     * @param array $member
     * @return \App\Adapter\IPSClass
     */
    public function setAuthedUser(array $member)
    {
        $this->_authedUser = $member;
        return $this;
    }
    
    /**
     *
     * @var array различные параметры
     */
    protected $_vars = [];
    
    /**
     * Установить параметры
     * @param array $vars
     * @return \App\Adapter\IPSClass
     */
    public function setParams(array $vars)
    {
        $this->_vars = $vars;
        return $this;
    }
    
    /**
     *
     * @var \App\Adapter\IPSClass 
     */
    protected $_printer;
    
    /**
     * 
     * @param \App\Adapter\Printer $printer
     * @return \App\Adapter\IPSClass
     */
    public function setPrinter(Printer $printer)
    {
        $this->_printer = $printer;
        return $this;
    }
    
    public function __get($name)
    {
        switch ($name) {
            
            case 'print':
                return $this->_printer;
            
            case 'base_url':
                return $_SERVER['DOCUMENT_URI'] . '?';
            /*
             * В input должны лежать все параметры из $_REQUEST
             */
            case 'input':
                return $this->_data;
                
            /**
             * Сюда надо добавить драйвера для БД
             */
            case 'DB':
                return $this->_DB;
            
            case 'member':
                return $this->_authedUser;
                
            case 'vars':
                return $this->_vars;
        }
    } 
}
