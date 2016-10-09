<?php

namespace App;

use App\Adapter\DB;

/**
 * 
 * @date 27.09.2016
 * @author Nikita Dezzpil Orlov <nikita@shvatka.ru>
 */
class Auth
{
    // самая наипростейшая аутентификация
    // завязанная на таблице members
    
    use Singleton;
    
    protected $_member = null;
    
    protected function __construct()
    {
        @session_start();
        
        if (array_key_exists('auth', $_SESSION)) {
            $this->_member = $_SESSION['auth'];
        }
    }


    /**
     * Пользватель аутентифицирован?
     * @return boolean
     */
    function isAuth()
    {
        return empty($this->_member) ? false : true;
    }
    
    /**
     * Получить аутентиф. пользователя
     * @return array
     * @throws \Exception
     */
    function getAuthedMemder()
    {
        if (empty($this->_member)) {
            throw new \Exception('No authed member', 1);
        }
        return $this->_member;
    }
    
    function signUp($name, $password)
    {
        // TODO самостоятельная регистрация пока запрещена
    }
    
    /**
     * Получить пользователя из БД
     * @param string $name
     * @return array
     * @throws \Exception
     */
    protected function _loadMember($name)
    {
        if (empty($name)) {
            throw new \Exception('Не указан name пользователя', 1);
        }

        $result = DB::getInstance()->query(
            "select m.*, i.komanda from members m left join sh_igroki i on m.id = i.n where m.name='{$name}'"
        );

        if (empty($result)) {
            throw new \Exception("Пользователя {$name} не существует", 2);
        }

        return $result[0];
    }
    
    /**
     * Захешировать пароль по-тупому, пока через md5
     * @todo сделать нормальное хеширование
     * @todo ввести обратную совместимость с теми паролями, которые захешированы
     * @param string $password
     * @return string
     */
    public function hashPassword($password)
    {
        return md5('thisIs' . $password . 'salt!');
    }


    /**
     * Войти
     * @param type $name
     * @param type $password
     * @return array
     * @throws \Exception
     */
    function login($name, $password)
    {
        $member = $this->_loadMember($name);
        if ($this->hashPassword($password) === $member['password']) {
            unset($member['password']);
            $this->_member = $member;
            $_SESSION['auth'] = $this->_member;
            
            return $this->getAuthedMemder();
        } else {
            throw new \Exception('Логин или пароль указаны неверно!', 10);
        }
    }
    
    /**
     * 
     */
    function logout()
    {
        if ($this->isAuth()) {
            $this->_member = null;
            unset($_SESSION['auth']);
            session_destroy();
        }
    }
    
    /**
     * Для логина пользователя из кода
     * @param type $login
     * @return array
     */
    function auth($login)
    {
        $this->_member = $this->_loadMember($name);
        return $this->_member;
    }
    
}
