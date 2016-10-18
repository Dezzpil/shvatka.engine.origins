<?php
namespace App\Engine;

class Uchgame extends Base
{
        function run_module()
        {
            parent::run_module();
            
            $res="";
            
            if (isset($this->ipsclass->input['gid']))
            {
                $id=$this->ipsclass->input['gid'];
                 
                if (is_numeric($id))
                {
                    //$res.='<div align="center"><b><u>Участники игры номер '.$id.'</u></b></div>';
                    $kkom=0;
                    $ob_kol_ig=0;
                    $kquery=$this->ipsclass->DB->query("select * from sh_comands where cmp_games!='' order by ochki DESC, nazvanie");
                    while ($frows = $this->ipsclass->DB->fetch_row($kquery))
                    {
                        $cur_cmd_games=explode(' ',strip_tags($frows['cmp_games']));
                        if ( in_array($id,$cur_cmd_games))
                        {
                            $kkom++;
                            $kig=0;
                            $res.="<h3><a href=\"#\">Команда ".$frows['nazvanie']."</a></h3><div><table>";
                            $iquery=$this->ipsclass->DB->query("select * from sh_igroki where (komanda='".$frows['nazvanie']."')and(LOCATE('".$id."',games)!=0) order by ochki DESC");
                            while ($ifrows = $this->ipsclass->DB->fetch_row($iquery))
                            {
                                $cur_igrok_games=explode(' ',strip_tags($ifrows['games']));
                                if (in_array($id,$cur_igrok_games))
                                {
                                    $res.="<tr><td>".$ifrows['nick']."</td></tr>";
                                    $kig++;
                                }
                            }
                            $res.="</table> <i>Всего от команды играло ".$kig." человек</i></div> ";
                            $ob_kol_ig=$ob_kol_ig+$kig;
                        }
                    }
                }
                
                $res.="<h3><a href=\"#\">Общая статистика</a></h3><div><p><b>Всего команд играло - ".$kkom."</b></p><p><b>Общее количество игравших - ".$ob_kol_ig."</b></p></div>";
               
            } else {
                $kquery=$this->ipsclass->DB->query("select * from sh_games where `status`='з'");
                while ($frows = $this->ipsclass->DB->fetch_row($kquery))
                {
                    $name = preg_replace('/(<.*?>)(.*?)(<\/.*?>)/i', '$2', $frows['g_name']);
                    $res .= "<a href='{$this->ipsclass->base_url}act=module&module=uchgame&gid={$frows['n']}'>{$name}</a><br />";
                }
            }
            $this->ipsclass->print->add_output($res);    
            return $this->ipsclass->print->do_output(['NAV' => $this->nav]);

        }
}
?>