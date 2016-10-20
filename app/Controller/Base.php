<?php
namespace App\Controller;

use App\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * 
 * @date 27.09.2016
 * @author Nikita Dezzpil Orlov <nikita@shvatka.ru>
 */
abstract class Base
{
    /**
     * Получить актуальную сессию или, если такой нет, создать новую
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Session\Session
     */
    protected function _getSession(Request $request)
    {
        $session = $request->getSession();
        if (empty($session)) {
            $session = new Session();
        }
        return $session;
    }
    
    public function before(Request $request, Application $app)
    {
        
    }
}
