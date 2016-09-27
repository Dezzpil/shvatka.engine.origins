<?php

namespace App\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

/**
 * 
 * @date 27.09.2016
 * @author Nikita Dezzpil Orlov <nikita@shvatka.ru>
 */
abstract class Base
{
    public function before(Request $request, Application $app)
    {
    
    }
}
