<?php
namespace App;

/**
 * Singleton
 * 
 * @date 2016.09.15
 * @author Nikita Dezzpil Orlov
 */
trait Singleton
{
    protected static $instance = null;
    
    /**
     * @return DBAdapter
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self;
        }
        
        return self::$instance;
    }
}
