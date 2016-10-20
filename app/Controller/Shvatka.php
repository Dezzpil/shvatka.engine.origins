<?php
namespace App\Controller;

use App\Application;
use Symfony\Component\HttpFoundation\Request;

/**
 * 
 * @date 26.09.2016
 * @author Nikita Dezzpil Orlov <nikita@shvatka.ru>
 */
class Shvatka extends Authed
{
    use \App\ClassLoaderBridge;
    
    const ADMIN_GROUP = 4; // Главные администаторы
    const SH_NAMESPACE = '\\App\\Engine\\';
    const SH_BASEURL = '/shvatka';
    const SH_STARTURL = '/shvatka?module=shvatka';
    
    const EXC_NOMOD = 'Не указан модуль';
    const EXC_NOSUCHMOD = 'Такого модуля не существует';
    
    public function index(Request $request, Application $app)
    {
        // объект для обращения к БД
        $DBAdapter = \App\Adapter\DB::getInstance();

        // Аутентифицированный пользователь
        // Тут можно поменять логин, для удобства разработки
//        $userLogin = 'Jacob';
//        $userLogin = 'Shepard';
//        $member = $DBAdapter->query("select * from members where name='" . $userLogin ."'");
        
        //$auth = \App\Auth::getInstance();
        $session = $this->_getSession($request);
        $auth = new \App\Auth($session);
        
        $member = $auth->getAuthedMemder();
        
        // Создаем и настраиваем адаптер для работы движка игры
        $data = array_merge($request->query->all(), $request->request->all());

        $adapter = new \App\Adapter\IPSClass($data);
        $adapter->setDB($DBAdapter);
        $adapter->setPrinter(\App\Adapter\Printer::getInstance());
        $adapter->setBaseURL(self::SH_BASEURL . '?');
        $adapter->setAuthedUser($member);
        $adapter->setParams([
            'admin_group' => self::ADMIN_GROUP
        ]);
        
        $modName = $request->get('module');
        if (empty($modName)) {
            throw new \Exception(static::EXC_NOMOD);
        }
        
        $modClass = self::SH_NAMESPACE . ucfirst(strtolower($modName));
        if (!class_exists($modClass)) {
            if (empty($this->_loader->loadClass($modClass))) {
                $app->abort(404, static::EXC_NOSUCHMOD);
            }
        }

        $mod = new $modClass;
        $mod->ipsclass = $adapter;

        // The return value of the 
        // closure becomes the content of the page.
        $result = $mod->run_module();
        
        // Задаем проверку на то, используется ли запрос на рендер с использованием
        // стороннего движка. Если да, то передаем данные приложению, а оно должно
        // знать как и с помощью чего все правильно сделать. Таким образом потом
        // движок рендера можно будет поменять в любой момент
        $renderRequest = $adapter->getRenderRequest();
        if (!empty($renderRequest)) {
            return $app->render($renderRequest->getViewName(), $renderRequest->getParams());
        }
        
        $userHtml = "<small>Пользователь {$member['name']} ({$member['komanda']})";
        $userHtml .= "&nbsp;<a href='/index/logout'>Выйти</a></small><br />";
        $result = $userHtml . $result;
        return $result;
    }
}
