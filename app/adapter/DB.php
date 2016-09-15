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
    /**
     * @todo вынести в конфиг
     */
    const dbHost = '127.0.0.1';
    const dbUser = 'root';
    const dbPass = 'toor';
    const dbName = 'shvatka';
    const dbPort = 3306;
    
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
        $this->_mysqli = new \mysqli(self::dbHost, self::dbUser, self::dbPass, self::dbName, self::dbPort);
    }
    
    /**
     * При SELECT сохраняет результат в объекте,
     * также как и возвращает его
     * @param string $sql
     */
    function query($sql)
    {
        //$sql = mysqli_real_escape_string($this->_mysqli, $sql);
        $result = $this->_mysqli->query($sql);
        if ($result === false) {
            var_dump($sql);
            var_dump($this->_mysqli->error);
            die;
            $result = [];
        }
        $this->_fetchedResult = $this->_result = $result;
        return $this->_result;
    }
    
    /**
     * 
     * @return int
     */
    function get_num_rows()
    {
        return count($this->_result);
    }
    
    /**
     * Непонятный параметр. В большинстве вызовах этого
     * методы переменная, явл. аргументом, даже не инициалицированна
     * 
     * @param type $something
     * @return array|bool
     */
    function fetch_row($something = null)
    {
        if (count($this->_fetchedResult)) {
            return array_shift($this->_fetchedResult);
        }
        return false;
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
