<?php

namespace App\Engine;

/**
 * 
 * @date 26.10.2016
 * @author Nikita Dezzpil Orlov <nikita@shvatka.ru>
 */
abstract class Organization extends Base
{
    public function run_module()
    {
        if ($this->_isOrganizator()) {
            parent::run_module();
            $this->nav[] = "<a href='{$this->ipsclass->base_url}act=module&module=reps'>Администрирование</a>";
            $this->nav[] = "<a href='{$this->ipsclass->base_url}act=module&module=edit'>Редактор</a>";
        } else {
            $html = "Вы не орг, вам сюда нельзя :)";
            $this->ipsclass->print->add_output($html);
            return $this->ipsclass->print->do_output(array('OVERRIDE' => 0, 'TITLE' => 'Вы не орг'));
        }
    }
}
