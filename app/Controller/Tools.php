<?php
namespace App\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

/**
 * 
 * @date 26.09.2016
 * @author Nikita Dezzpil Orlov <nikita@shvatka.ru>
 */
class Tools extends Base
{
    /**
     * 
     * @param string $name
     * @return array
     * @throws \Exception
     */
    protected function _loadUser($name)
    {
        if (empty($name)) {
            throw new \Exception('Не указан name пользователя');
        }

        $result = \App\Adapter\DB::getInstance()->query(
            "select * from members where name='{$name}'"
        );

        if (empty($result)) {
            throw new \Exception("Пользователя {$name} не существует");
        }

        return $result[0];
    }
    
    /**
     * Выдать доступ к управлению сценарием игры
     * @param Request $request
     * @param Application $app
     * @return type
     */
    public function scenario(Request $request, Application $app)
    {   
        $name = $request->get('name');
        $user = $this->_loadUser($name);
        \App\Adapter\DB::getInstance()->query(
            "insert into pfields_content (field_4, member_id) values ('y', {$user['id']})"
        );
        return "Пользователю {$name} добавлены права на редактирования сценария";
    }
    
    /**
     * Изьят доступ к управлению игрой
     * @param Request $request
     * @param Application $app
     * @return type
     */
    public function unscenario(Request $request, Application $app)
    {
        $name = $request->get('name');
        $user = $this->_loadUser($name);
        \App\Adapter\DB::getInstance()->query(
            "delete from pfields_content where member_id={$user['id']}"
        );
        return "У пользователя {$name} изьяты права на редактирование сценария";
    }
    
    /**
     * @todo выпилить, уязвимость
     * @param Request $request
     * @param Application $app
     * @return string
     */
    public function hash(Request $request, Application $app)
    {
        $password = $request->get('password');
        $auth = \App\Auth::getInstance();
        return $auth->hashPassword($password);
    }
}
