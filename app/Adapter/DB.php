<?php
namespace App\Adapter;

/**
 * Class DB
 * 
 * @data 2016.09.15
 * @author Nikita Dezzpil Orlov
 */
class DB
{
    static protected $host;
    static protected $user;
    static protected $password;
    static protected $schema;
    static protected $port;
    
    static public function config($host, $user, $password, $schema, $port)
    {
        static::$host = $host;
        static::$user = $user;
        static::$password = $password;
        static::$schema = $schema;
        static::$port = $port;
    }
    
    use \App\Singleton;
    
    /**
     * Результат метода query
     * @var array
     */
    protected $_result = [];
    
    /**
     * Временный массив, из которого 
     * разбираются результаты
     * @var array 
     */
    protected $_fetchedResult = [];

    /**
     *
     * @var mysqli 
     */
    protected $_mysqli;
    
    private function __construct()
    {
        // установить соединение
        $this->_mysqli = new \mysqli();
        $this->_mysqli->connect(self::$host, self::$user, self::$password, self::$schema, self::$port);
        $this->_mysqli->query('SET NAMES utf8');
    }
    
    /**
     * При SELECT сохраняет результат в объекте,
     * также как и возвращает его
     * @param string $sql
     * @param boolean $escape Нужно ли экранировать символы?
     */
    function query($sql, $escape = false)
    {
        $data = [];
        
        if ($escape) {
            $sql = mysqli_real_escape_string($this->_mysqli, $sql);
        }
        
        /* @var $result \mysqli_result */
        $result = $this->_mysqli->query($sql);
        
        /**
         * @link http://php.net/manual/ru/mysqli.query.php
         * Возвращает FALSE в случае неудачи. В случае успешного выполнения 
         * запросов SELECT, SHOW, DESCRIBE или EXPLAIN mysqli_query() вернет 
         * объект mysqli_result. Для остальных успешных запросов mysqli_query() 
         * вернет TRUE.
         */
        if ($result === true) return $this->_result;
        
        if ($this->_mysqli->errno) {
            var_dump($sql, $result, $this->_mysqli->errno, $this->_mysqli->error);
            die;
        }
        
        if ($result !== false) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
            $result->free();
        }
        
        $this->_fetchedResult = $this->_result = $data;
        return $this->_result;
    }
    
    
    /**
     * @param array Иногда может быть передан результат из query()
     * @return int
     */
    function get_num_rows($result = null)
    {
        if (!empty($result)) {
            return count($result);
        } else {
            return count($this->_result);
        }
    }
    
    /**
     * Непонятный параметр. В большинстве вызовах этого
     * методы переменная, явл. аргументом, даже не инициалицированна
     * 
     * @param type $something
     * @return array|null
     */
    function fetch_row(&$result = null)
    {
        if ($result !== null) {
            if (!empty($result)) {
                return array_shift($result);
            } else {
                return false;
            }
        } else {
            return array_shift($this->_fetchedResult);
        }
    }

    /**
     * Этот метод используется только в одном месте - mod_reps.php:262
     * для записи данных о входе в админку. Проще его переписать там, чем реализовывать метод
     * @todo переписать ?
     * @param string $tableName
     * @param array $values
     */
    function do_insert($tableName, array $values)
    {
        
    }
}
