<?php

namespace App;

/**
 * Наипростейший контейнер для доступа к данным в движке
 * 
 * @date 21.10.2016
 * @author Nikita Dezzpil Orlov <nikita@shvatka.ru>
 */
class DataContainer implements \ArrayAccess, \IteratorAggregate
{
    protected $_data = [];
    
    public function __construct(array $data)
    {
        $this->_data = $data;
    }
    
    public function getIterator()
    {
        return new \ArrayIterator($this->_data);
    }
    
    /**
     * 
     * @param string $offset
     * @param mixed $value
     * @return \App\DataContainer
     */
    public function set($offset, $value)
    {
        $this->_data[$offset] = $value;
        return $this;
    }
    
    /**
     * 
     * @param string $offset
     * @param mixed $default
     * @return mixed
     */
    public function get($offset, $default = null)
    {
        if (array_key_exists($offset, $this->_data)) {
            return $this->_data[$offset];
        } else {
            return $default;
        }
    }
    
    /**
     * 
     * @return array
     */
    public function toArray()
    {
        return $this->_data;
    }
    
    public function offsetExists($offset)
    {
        if (array_key_exists($offset, $this->_data)) {
            return true;
        }
        return false;
    }
    
    public function offsetGet($offset)
    {
        return $this->get($offset);
    }
    
    public function offsetSet($offset, $value)
    {
        $this->set($offset, $value);
    }
    
    public function offsetUnset($offset)
    {
        if ($this->offsetExists($offset)) {
            unset($this->_data[$offset]);
        }
    }
}
