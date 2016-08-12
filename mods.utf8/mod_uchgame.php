<?php
if ( ! defined( 'IN_IPB' ) )
{
        print "<h1>НЕ ЛЕЗЬ КУДА НЕ НАДО</h1>Этот файл так нифига не вызовеш. Заходи через форум.";
        exit();
}


class module
{
        //=====================================
        // Define vars if required
        //=====================================

        var $ipsclass;
        var $class  = "";
        var $module = "";
        var $html   = "";
        var $result = "";

        //=====================================
        // Constructer, called and run by IPB
        //=====================================

        function run_module()
        {
             if (isset($this->ipsclass->input['gid']))
             {
                 $id=$this->ipsclass->input['gid'];
                 $res="";
                 if (is_numeric($id))
                 {
                    //$res.='<div align="center"><b><u>Участники игры номер '.$id.'</u></b></div>';
                    $kkom=0;
                    $ob_kol_ig=0;
                    $kquery=$this->ipsclass->DB->query("select * from ibf_sh_comands where cmp_games!='' order by ochki DESC, nazvanie");
	                while ($frows = $this->ipsclass->DB->fetch_row($kquery))
	                {
		                  $cur_cmd_games=explode(' ',strip_tags($frows['cmp_games']));
		                  if ( in_array($id,$cur_cmd_games))
		                  {                   	         $kkom++;
                   	         $kig=0;
                   	         $res.="<h3><a href=\"#\">Команда ".$frows['nazvanie']."</a></h3><div><table>";
                   	         $iquery=$this->ipsclass->DB->query("select * from ibf_sh_igroki where (komanda='".$frows['nazvanie']."')and(LOCATE('".$id."',games)!=0) order by ochki DESC");
                   	         while ($ifrows = $this->ipsclass->DB->fetch_row($iquery))
                   	         {                   	         	$cur_igrok_games=explode(' ',strip_tags($ifrows['games']));
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
                  //echo("<html><head><script type=\"text/javascript\" src=\"jquery/js/jquery-1.3.2.min.js\"></script><title></title></head><body>".$res."</body></html>");
                echo $res;
             }

        }
}
?>