<?php

namespace App;

/**
 * 
 * @date 09.10.2016
 * @author Nikita Dezzpil Orlov <nikita@shvatka.ru>
 */
class Config implements \ArrayAccess
{
    use Singleton;
    
    /**
     * 
     * @param string $path
     * @param string $env
     * @return static
     * @throws Exception
     */
    static public function parse($path, $env)
    {
        if (static::$instance !== null) {
            throw new Exception('Конфигурация уже была загружена ранее');
        }
        static::$instance = new static($path, $env);
        return static::$instance;
    }
    
    /**
     * Оригинальные данные из файла
     * @var array
     */
    protected $rawData = [];

    /**
     * Данные для текущего окружения
     * @var type 
     */
    protected $workData = [];
    /**
     * Хранилище
     * @var array
     */
    protected $sectionsData = [];
    
    public function offsetSet($offset, $value)
    {
        // нельзя ничего добавить
    }

    public function offsetExists($offset)
    {
        return isset($this->workData[$offset]);
    }

    public function offsetUnset($offset)
    {
        // нельзя ничего удалить
    }

    public function offsetGet($offset)
    {
        return isset($this->workData[$offset]) ? $this->workData[$offset] : null;
    }
    
    /**
     * 
     * @param string $path
     * @param string $env
     */
    protected function __construct($path, $env)
    {
        if (!file_exists($path) || !is_readable($path)) {
            throw new Exception('Файл конфигурации (' . $path . ') не существует или недоступен для чтения');
        }
        $content = file_get_contents($path);
        $this->rawData = json_decode($content, true);
        
        foreach ($this->rawData as $sectionName => $sectionData) {
            $this->parseSection($sectionName);
        }
        
        $this->workData = $this->sectionsData[$env];
    }
    
    public function parseSection($sectionName)
    {
        if (strpos($sectionName, ':') > 0) {
            $parenting = explode(':', $sectionName);
            $name = array_shift($parenting);
            if (empty($parenting)) {
                throw new Exception('Не указана родительская секция окружения в ' . $sectionName);
            } else {
                // заменяем родительские значения
                $parent = array_shift($parenting);
                if (array_key_exists($parent, $this->sectionsData)) {
                    $this->sectionsData[$name] = array_replace_recursive(
                        $this->sectionsData[$parent], $this->rawData[$sectionName]
                    );
                }
            }
        } else {
            // сохраняем секцию конфига
            if (array_key_exists($sectionName, $this->rawData)) {
                $this->sectionsData[$sectionName] = $this->rawData[$sectionName];
            } else {
                throw new Exception('Указанная секция (' . $sectionName . ')не существует в файле конфигурации');
            }
        }
    }
}
