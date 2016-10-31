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
            $unit = new Helper\Unit($levelNum, $tipNum);
            if ($execute == 1) {
                $ptm = $this->ipsclass->input['ptm'] ?: 0;
                $text = $this->ipsclass->input['text'];
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
            } else {
                foreach ($unit as $key => $value) {
                    $this->ipsclass->input->set($key, $value);
                }
            }
        }
        if ($this->ipsclass->input['return']) {
            $this->ipsclass->redirect('/shvatka?module=reps&cmd=scn');
        }
        
        $this->ipsclass->input->set('error', $error);
        $this->ipsclass->render(
            'module/edit.twig', 
            $this->ipsclass->input->toArray()
        );
    }
}