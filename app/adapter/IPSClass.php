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
            
            /**
             * если мы используем vagrant с нашим bootstrap.sh (nginx) - это 100% 
             * @todo доработать на случай, если не в корне сайта
             */
            case 'base_url':
                return $_SERVER['SERVER_NAME'] . '/?';
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
                
            /**
             * Тут хранится массив с данными текущего пользователя
             * используются 3 ключа. 
             * Что такое mgroup?
             */
            case 'member':
                return [ 'id' => 0, 'mgroup' => 0, 'name' => 'Неизвестный' ];
        }
    } 
}
