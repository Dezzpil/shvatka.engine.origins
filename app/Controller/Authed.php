<?php

namespace App\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

/**
 * 
 * @date 27.09.2016
 * @author Nikita Dezzpil Orlov <nikita@shvatka.ru>
 */
abstract class Authed extends Base
{
    public function before(Request $request, Application $app)
    {
        parent::before($request, $app);
        
        $auth = \App\Auth::getInstance();
        if ( !$auth->isAuth()) {
            return $app->redirect('/');
        }
    }
}
