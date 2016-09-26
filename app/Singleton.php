<?php

namespace App;

/**
 * Singleton
 * для тех классов, что используют одиночку
 * необходимо самостоятельно определить метод
 * __construct как private/protected
 * 
 * @date 2016.09.15
 * @author Nikita Dezzpil Orlov
 */
trait Singleton
{
    protected static $instance = null;
    
    /**
     * @return static
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self;
        }
        
        return self::$instance;
    }
}
