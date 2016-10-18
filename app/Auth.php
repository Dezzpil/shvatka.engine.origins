<?php

namespace App;

use App\Adapter\DB;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * 
 * @date 27.09.2016
 * @author Nikita Dezzpil Orlov <nikita@shvatka.ru>
 */
class Auth
{
    // самая наипростейшая аутентификация
    // завязанная на таблице members
    
    //use Singleton;
    
    /**
     *
     * @var \SessionIdInterface
     */
    protected $_session = null;
    
    /**
     *
     * @var array
     */
    protected $_member = null;
    
    /**
     * 
     * @param SessionInterface $session
     */
    public function __construct(SessionInterface $session)
    {
        $this->_session = $session;
        
        if (!$this->_session->isStarted()) {
            $this->_session->start();
        }
        
        if ($this->_session->has('auth')) {
            $this->_member = $this->_session->get('auth');
        }
    }
    
    const EXC_NONAME = 'Не указан логин пользователя!';
    const EXC_NOUSER = 'Пользователя с указанным логином не существует!';
    const EXC_BADDATA = 'Логин или пароль указаны неверно!';
    const EXC_NOAUTH = 'Нет данных об аутентификации!';
    
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
            throw new \Exception(static::EXC_NOAUTH, 1);
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
            throw new \Exception(static::EXC_NONAME, 1);
        }

        $result = DB::getInstance()->query(
            "select m.*, i.komanda from members m left join sh_igroki i on m.id = i.n where m.name='{$name}'"
        );

        if (empty($result)) {
            throw new \Exception(static::EXC_NOUSER, 2);
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
            $this->_session->set('auth', $this->_member);
            return $this->_member;
        } else {
            throw new \Exception(static::EXC_BADDATA, 10);
        }
    }
    
    /**
     * 
     */
    function logout()
    {
        if ($this->isAuth()) {
            $this->_member = null;
            $this->_session->remove('auth');
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
