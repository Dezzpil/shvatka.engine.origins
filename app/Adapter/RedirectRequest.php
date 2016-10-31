<?php
namespace App\Adapter;

/**
 * Адаптер для запроса приложения на редирект
 * 
 * @date 30.10.2016
 * @author Nikita Dezzpil Orlov <nikita@shvatka.ru>
 */
class RedirectRequest
{
    protected $_url;
    
    public function __construct($url)
    {
        $this->_url = $url;
    }
    
    public function getUrl()
    {
        return $this->_url;
    }
}
