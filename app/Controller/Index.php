<?php

namespace App\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

/**
 * 
 * @date 26.09.2016
 * @author Nikita Dezzpil Orlov <nikita@shvatka.ru>
 */
class Index extends Base
{
    protected function _htmlForm($name = null, $error = null)
    {
        $form = <<<HTML
<form method="POST" action="/index/login">
    <label>Логин: </label><input type="text" name="name" value="{$name}" /><br />
    <label>Пароль: </label><input type="password" name="password" value="" /><br />
    <input type="submit" value="Войти" />
</form>
HTML;
        if (!empty($error)) {
            $form .= "<p style='color: orangered'>Ошибка: {$error}</p>";
        }
        
        return $form;
    }

    public function index(Request $request, Application $app)
    {
        $auth = \App\Auth::getInstance();
        
        if ($auth->isAuth()) {
            return $app->redirect(Shvatka::SH_STARTURL);
        } else {
            // рисуем форму для аутентификации
            return $this->_htmlForm();
        }
    }
    
    public function login(Request $request, Application $app)
    {
        $auth = \App\Auth::getInstance();
        $name = $request->get('name');
        $pass = $request->get('password');
        try {
            $auth->login($name, $pass);
            return $app->redirect(Shvatka::SH_STARTURL);
        } catch (\Exception $e) {
            return $this->_htmlForm($name, $e->getMessage());
        }
    }
    
    public function logout(Request $request, Application $app)
    {
        $auth = \App\Auth::getInstance();
        $auth->logout();
        return $app->redirect('/');
    }
}