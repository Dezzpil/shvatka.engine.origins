<?php

namespace App;

/**
 * 
 * @date 26.09.2016
 * @author Nikita Dezzpil Orlov <nikita@shvatka.ru>
 */
trait ClassLoaderBridge {
    
    /**
     *
     * @var \Composer\Autoload\ClassLoader 
     */
    protected $_loader;
    
    /**
     * 
     * @param \Composer\Autoload\ClassLoader $loader
     * @return type
     */
    public function setClassLoader(\Composer\Autoload\ClassLoader $loader)
    {
        $this->_loader = $loader;
        return $this;
    }
    
}
