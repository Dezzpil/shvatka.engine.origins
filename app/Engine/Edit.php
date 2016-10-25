<?php
namespace App\Engine;

class Edit extends Organization
{   
    function run_module()
    {
        parent::run_module();
        
        $error = null;
        $levelNum = $this->ipsclass->input['lev'] ?: 0;
        $tipNum = $this->ipsclass->input['npod'] ?: 0;
        $execute = $this->ipsclass->input['execs'] ?: 0;

        if ($levelNum == 0) {
            $error = 'Не задан номер уровня';
        } else {
            if  ($execute == 1) {
                $unit = new Helper\Scenario($levelNum, $tipNum);
                
                $ptm = $this->ipsclass->input['ptm'] ?: 0;
                $text = $this->ipsclass->input['textl'];
                $b_keyw = $this->ipsclass->input['b_keyw'];
                $keyw = $this->ipsclass->input['keyw'];
                $cmd = intval($this->ipsclass->input['cm']) ?: 1;
                switch ($cmd) {
                    case 1:
                    case 3: // для обратной совместимости. Создать еще один все равно нельзя
                        $unit->save($keyw, $text, $ptm, $b_keyw);
                        break;
                    case 2:
                        $unit->delete();
                        break;
                }
            }
        }
        $this->ipsclass->input->set('error', $error);
        $this->ipsclass->render(
            'module/scenario.twig', 
            $this->ipsclass->input->toArray()
        );
    }
}