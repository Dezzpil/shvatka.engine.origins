<?php
namespace App\Adapter;

/**
 * Адаптер для использования произвольного движка 
 * отображения представлений
 * 
 * @date 20.10.2016
 * @author Nikita Dezzpil Orlov <nikita@shvatka.ru>
 */
class RenderRequest
{
    protected $_viewName;
    protected $_params;
    
    public function __construct($viewName, array $params = [])
    {
        $this->_viewName = $viewName;
        $this->_params = $params;
    }
    
    public function getViewName()
    {
        return $this->_viewName;
    }
    
    public function getParams()
    {
        return $this->_params;
    }
}
