<?php

namespace App\Engine;

/**
 * 
 * @date 26.09.2016
 * @author Nikita Dezzpil Orlov <nikita@shvatka.ru>
 */
abstract class Base
{
    /**
     *
     * @var \App\Adapter\IPSClass
     */
    public $ipsclass;

    /**
     *
     * @var string HTML
     */
    public $result;
    
    /**
     * Здесь складируется навигация
     * @var array навигация
     */
    public $nav = [];
    
    /**
     * Проверка на то, является пользователь оргом или админом
     * @todo задействовать этот метод в коде, выпилить повторения, DRY
     * @return boolean
     */
    protected function _isOrganizator()
    {
        $this->ipsclass->DB->query("select field_4 from pfields_content where member_id=".$this->ipsclass->member['id']."");
        $frows = $this->ipsclass->DB->fetch_row($fquery);
        if (( $this->ipsclass->member['mgroup'] == $this->ipsclass->vars['admin_group'] )or($frows['field_4']=='y')) {
            return true;
        }
        return false;
    }


    public function run_module()
    {
        // добавим навигацию на все страницы
        $this->nav = [
            "<a href='{$this->ipsclass->base_url}act=module&module=uchgame'>Сыгранные игры</a>",
            "<a href='{$this->ipsclass->base_url}act=module&module=stat'>Статистика</a>",
            "<a href='{$this->ipsclass->base_url}act=module&module=notelog'>Лог очков</a>"
        ];
            
        if ($this->_isOrganizator()) {
            $this->nav[] = "<a href='{$this->ipsclass->base_url}act=module&module=reps'>Администрирование</a>";
            $this->nav[] = "<a href='{$this->ipsclass->base_url}act=module&module=edit'>Редактор</a>";
        }
    }
}
