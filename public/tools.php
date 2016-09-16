<?php
include_once __DIR__ . '/../init.php';

$db = \App\Adapter\DB::getInstance();

/**
 * 
 * @param string $name
 * @return array
 */
function loadUser($name)
{
    if (empty($name)) {
        die('Не указан name пользователя');
    }

    $result = \App\Adapter\DB::getInstance()->query(
        "select * from members where name='{$name}'"
    );
        
    if (empty($result)) {
        die("Пользователя {$name} не существует");
    }

    return $result[0];
}

switch (@$_REQUEST['act']) {
    
    case 'scenario':
        $name = $_REQUEST['name'];
        $user = loadUser($name);
        $db->query("insert into pfields_content (field_4, member_id) values ('y', {$user['id']})");
        die("Пользователю {$name} добавлены права на редактирования сценария");
        
    case 'unscenario':
        $name = $_REQUEST['name'];
        $user = loadUser($name);
        $db->query("delete from pfields_content where member_id={$user['id']}");
        die("У пользователя {$name} изьяты права на редактирование сценария");
        
    default:
        die('Не указано действие (act)');
}

die;
