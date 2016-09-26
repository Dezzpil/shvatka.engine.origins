<?php

namespace Shvatka;

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
    
    public function run_module()
    {
        // добавим навигацию на все страницы
        $this->nav = [
            "<a href='{$this->ipsclass->base_url}act=module&module=uchgame'>Сыгранные игры</a>",
            "<a href='{$this->ipsclass->base_url}act=module&module=stat'>Статистика</a>",
            "<a href='{$this->ipsclass->base_url}act=module&module=edit'>Редактор</a>",
            "<a href='{$this->ipsclass->base_url}act=module&module=notelog'>Лог очков</a>",
            "<a href='{$this->ipsclass->base_url}act=module&module=reps'>Администрирование</a>"
        ];
    }
}
