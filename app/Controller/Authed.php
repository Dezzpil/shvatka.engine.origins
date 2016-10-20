<?php
namespace App\Controller;

use App\Application;
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
        
        $session = $this->_getSession($request);
        $auth = new \App\Auth($session);
        if ( !$auth->isAuth()) {
            return $app->redirect('/');
        } else {
            // Если пользователь аутентифицирован,
            // то будем отображать блок с информацией
            $app->setTemplateVariable('member', $auth->getAuthedMemder());
        }
    }
}
