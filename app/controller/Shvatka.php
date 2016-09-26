<?php

namespace App\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\HttpKernelInterface;

/**
 * 
 * @date 26.09.2016
 * @author Nikita Dezzpil Orlov <nikita@shvatka.ru>
 */
class Shvatka
{
    use \App\ClassLoaderBridge;
    
    const ADMIN_GROUP = 4; // Главные администаторы
    const SH_NAMESPACE = '\\Shvatka\\';
    const SH_BASEURL = '/shvatka';
    
    public function index(Request $request, Application $app)
    {
        // объект для обращения к БД
        $DBAdapter = \App\Adapter\DB::getInstance();

        // Аутентифицированный пользователь
        // Тут можно поменять логин, для удобства разработки
        //$userLogin = 'Jacob';
        $userLogin = 'Shepard';
        $member = $DBAdapter->query("select * from members where name='" . $userLogin ."'");

        // Создаем и настраиваем адаптер для работы движка игры
        $adapter = new \App\Adapter\IPSClass($_REQUEST);
        $adapter->setDB($DBAdapter);
        $adapter->setPrinter(\App\Adapter\Printer::getInstance());
        $adapter->setBaseURL(self::SH_BASEURL . '?');
        $adapter->setAuthedUser($member[0]);
        $adapter->setParams([
            'admin_group' => self::ADMIN_GROUP
        ]);
        
        $modName = $request->get('module');
        if (empty($modName)) {
            // forward to index
            $subRequest = Request::create('/', 'GET');
            return $app->handle($subRequest, HttpKernelInterface::SUB_REQUEST);
        }
        
        $modClass = self::SH_NAMESPACE . ucfirst(strtolower($modName));
        try {
            if (empty($this->_loader->loadClass($modClass))) {
                throw new \Exception('Модуля ' . $modName . ' не существует');
            }
            
            $mod = new $modClass;
            $mod->ipsclass = $adapter;

            // The return value of the 
            // closure becomes the content of the page.
            ob_start();
            $mod->run_module();
            return ob_get_clean();
            
        } catch (\Exception $ex) {
            // forward to index
            $subRequest = Request::create('/', 'GET');
            return $app->handle($subRequest, HttpKernelInterface::SUB_REQUEST);
        }
    }
}
