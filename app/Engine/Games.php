<?php
namespace App\Engine;

class Games extends Base
{
    function run_module()
    {
        parent::run_module();
        
        function sectostr($secs)
        {
            $st="";
            $tmp=floor($secs / 86400);
            if ($tmp>0) {$st='<span id=\'days\'>'.$tmp.'</span> д. ';}
            $tmp2=floor(($secs - ($tmp*86400))/3600);
            if ($tmp2>0) {$st=$st.'<span id=\'hours\'>'.$tmp2.'</span> ч. ';}
            $tmp3=floor(($secs - ($tmp*86400) - ($tmp2*3600))/60);
            if ($tmp3>0) {$st=$st.'<span id=\'mins\'>'.$tmp3.'</span> м. ';}
            $tmp=floor($secs - ($tmp*86400) - ($tmp2*3600)-($tmp3*60));
            if ($tmp>0) {$st=$st.'<span id=\'secs\'>'.$tmp.'</span> сек. ';}
            return $st;
        }

        //=====================================
        // Set up structure
        //=====================================
        $html=$html. "";
        switch( $this->ipsclass->input['cmd'] )
        {
            case 'disp':
                $this->disp($this->ipsclass->input['id']);
                break;
            case 'pub':
                $qqq=$this->ipsclass->DB->query("select field_4 from pfields_content where member_id=".$this->ipsclass->member['id']."");
                $fff = $this->ipsclass->DB->fetch_row($qqq);
                if (( $this->ipsclass->member['mgroup'] == $this->ipsclass->vars['admin_group'] )or($fff['field_4']=='y'))
                    {$this->pub();}
                else
                    {$this->result="Вы не администратор и не можете опубликовать результаты игры.";}
                break;
            default:
                $this->result="Укажите номер игры";
                break;
        }

        $html=$html.'<font size=2>'.$this->result.'</font>';
        $this->ipsclass->print->add_output( $html );
        $this->nav[] = "<a href='{$this->ipsclass->base_url}act=module&module=shvatka'>СХВАТКА</a>";
        return $this->ipsclass->print->do_output(array('OVERRIDE' => 0, 'TITLE' => 'Информация об игре', 'NAV' => $this->nav));

        ////exit();
    }

    function pub()
    {
        $res="";
        $ptm="";
        if (count($this->ipsclass->DB->query("select * from sh_games WHERE status='т'"))!=0)
        {
      //Сценарий
                 	$res=$res.'<div align="center"><b>Cценарий</b></div><br><div  class="borderwrap" style={margin:2px;}>';
                 	$res.="<table style={width:100%;}><tr class=\"ipbtable\"><td class=\"row1\">";
                 	$this->ipsclass->DB->query("select * from sh_game order by uroven, n_podskazki");
                 	while ($frows = $this->ipsclass->DB->fetch_row($fquery))
                 	{
                 	if ($frows['n_podskazki']!='0')
                   	  {
                             $res=$res.'<b>Подсказка №'.$frows['n_podskazki'].' ('.$ptm.' мин.)</b>';
                             if ($frows['p_time']=='0')
                             {
                                 $res.=' Последняя подсказка уровня.';
                             }
                             $res.='<br>';
                   	  }
                  	   else
                      {
                             $res=$res.'<br><center><b>Уровень '.$frows['uroven'].'. ';
                             $res=$res.'Ключ: </b>'.$frows['keyw'].'';
                             if ($frows['b_keyw']!='') $res=$res.'<b>  Мозговой ключ: </b>'.$frows['b_keyw'];
                             $res=$res.'</center><br>';
                      };
                      $res=$res.$frows['text'].'<br>';
                      if ($frows['p_time']!='0')
                      {
                             $ptm=$frows['p_time'];
                      }
                      else
                      {
                             $ptm="";
                      }
                    }
                    $res.='</center></td></tr></table></div>';
                    $this->ipsclass->DB->query("update  sh_games set scenariy='".addslashes($res)."' WHERE status='т'");
      //Таблица прохождения
                    $res="";
                 	$comd=array();
                 	$levkeys=array();
                 	$tab=array();
                 	$res='<div align="center"><b>Таблица проходжения уровней</b></div><br><div align="center" style={margin:2px;}>';
                 	$levkeys=array();
                 	$this->ipsclass->DB->query("select * from sh_comands");
                 	while ($frows = $this->ipsclass->DB->fetch_row($fquery))
                 	{
                 	  $comd[$frows['n']]=$frows['nazvanie'];
                 	}
                 	$this->ipsclass->DB->query("select * from sh_game where n_podskazki=0 order by uroven");
                 	while ($frows = $this->ipsclass->DB->fetch_row($fquery))
                 	{
                 	  $levkeys[]=$frows['uroven'];
                 	}
                 	foreach ($comd as $n=>$naz)
                 	{
                 	   $fin=array();
                 	   $fq=$this->ipsclass->DB->query("select * from sh_log where (comanda='".$naz."')and(levdone>0) order by levdone");
                 	   while ($frows = $this->ipsclass->DB->fetch_row($fq))
                 	   {
                 	        $fin[$frows['levdone']]=$frows['time'];
                 	   }
                 	   if (count($fin)!=0) {$tab[$naz]=$fin;}
                 	}

                 	foreach ($levkeys as $lev)
                 	{
                 	   $maxl=strtotime("now")+10000000;
                 	   $i=0;
                 	   $j="";
                 	   foreach ($tab as $naz=>$fin)
                 	   {
                 	       if (($fin[$lev]!=0)and(strtotime($fin[$lev])<$maxl))
                 	       {
                 	           $maxl=strtotime($fin[$lev]);
                 	           $i=$lev;
                 	           $j=$naz;
                 	       }
                 	   }
                 	   if (($i!=0)and($j!=""))
                 	   {
                 	        foreach ($tab as $naz=>$fin)
                 	        {
                 	             if  ($naz!=$j)
                 	             {
                 	                    if ($tab[$naz][$lev]!=0)
                 	                    $tab[$naz][$lev]=(date('H:i:s',strtotime($tab[$naz][$lev]))).'<br>-'.(sectostr(strtotime($fin[$lev])-strtotime($tab[$j][$i])));
                 	                    else
                 	                    $tab[$naz][$lev]='--:--:--';
                 	             }
                 	        }
                 	        $tab[$j][$i]='<font color=red>'.(date('H:i:s',strtotime($tab[$j][$i]))).'</font>';
                 	   }
                 	}
                 	$res.='<table class="borderwrap"><tr><th>Название команды</th>';
                 	foreach ($levkeys as $lev)
                 	{
                 	       $res.='<th> Уровень '.$lev.'</th>';
                 	}
                 	$res.='</tr>';
                 	foreach ($tab as $naz=>$fin)
                 	{
                 	       $res.='<tr class=\'ipbtable\'><td class="row1"><b>'.$naz.'</b></td>';
                 	       foreach ($levkeys as $lev)
                 	       {
                 	               $res.='<td class="row1" align="center">'.$fin[$lev].'</td>';
                 	       }
                 	       $res.='</tr>';
                 	}
                 	$res.='</table><br></div>';
                    $this->ipsclass->DB->query("update  sh_games set leveltable='".addslashes($res)."' WHERE status='т'");
      //Логи
	                $chisla=array('0','1','2','3','4','5','6','7','8','9');
                 	$res="";
                 	$keyprev="";
                 	$comd=array();
					$this->ipsclass->DB->query("select * from sh_comands");
                 	while ($frows = $this->ipsclass->DB->fetch_row($fquery))
                 	{
                 	  $comd[$frows['n']]=$frows['nazvanie'];
                 	}
                 	$res=$res.'<div>';
                 	foreach ($comd as $n=>$naz)
                 	{  $keyprev="";
                 	   if (count($this->ipsclass->DB->query("select * from sh_log where comanda='".$naz."' order by time"))!=0)
                 	   {
                 	     $res=$res.'<table cellspacing="1" class="borderwrap" align="center"><tr><td align="center" colspan="3" class="maintitle">'.$naz.'</td></tr>';
                 	     $res=$res.'<tr><th align="center">Время</th><th align="center" >Ключ</th><th>Автор</th></tr>';
                 		 while ($frows = $this->ipsclass->DB->fetch_row($fquery))
                 	     {
                 	        if  ($keyprev!=$frows['keytext'])
                 	        {
                 	            $res=$res."<tr class='ipbtable'><td class=\"row1\">".($frows['time'])."</td><td class=\"row2\" align=\"center\">".html_entity_decode($frows['keytext'])."</td><td class=\"row2\" align=\"center\"><b>".($frows['autor'])."</b></td></tr>";
                                    if  ($frows['levdone']!='0')$res=$res."<tr class='ipbtable'><td id=\"gfooter\" colspan=3 align=\"center\">Уровень ".$frows['levdone']." закончен</td></tr>";
                 	        }
                 	        $keyprev=$frows['keytext'];
                 	     }
                 	     $res=$res.'</TABLE><br>';
                 	   }
                 	 }
                 	 $res.='</div>';
                 	$this->ipsclass->DB->query("select * from sh_games WHERE status='т'");
                 	$frows = $this->ipsclass->DB->fetch_row($fquery);
                 	$this->ipsclass->DB->query("update  sh_games set g_name='<a href=\"{$this->ipsclass->base_url}?act=module&module=games\&cmd=disp\&id=".$frows['n']."\">".$frows['g_name']."</a>' WHERE status='т'");
                 	$this->ipsclass->DB->query("update  sh_games set logs='".addslashes($res)."' WHERE status='т'");
                 	$this->ipsclass->DB->query("update  sh_games set status='з' WHERE status='т'");
                 	$this->result="Опубликовано";
                 	header('Location:' . $this->ipsclass->base_url . '?act=module&module=games&cmd=disp&id='.$frows['n']);
                 }
                 else
                 {
                 	$this->result="Нет игры готовой для публикации.";
                 }

      }
      
      function disp($id)
      {
                 $chisla=array('0','1','2','3','4','5','6','7','8','9');
                 $res="";
                 if (($id!="")and(!in_array(substr($id,0,1),$chisla)))
                 {
                      $id="";
                 }
                 $res="";
                 $this->ipsclass->DB->query("select * from sh_games where n='".$id."'");
                 while ($frows = $this->ipsclass->DB->fetch_row($fquery))
                 {
                 	$res.='<script>
                 	function showlogs(ref,di,wh)
                 	 {
                 	  if (document.getElementById(ref).innerText=="Показать "+wh)
                 	  {
                 	  	document.getElementById(ref).innerText="Спрятать "+wh;
                 	  	document.getElementById(di).style.display="inline";
                 	  	document.getElementById(di).style.visibility="visible";
                 	  }
                 	  else
                 	  {
                 	  	document.getElementById(ref).innerText="Показать "+wh;
                 	  	document.getElementById(di).style.display="none";
                 	  	document.getElementById(di).style.visibility="hidden";
                 	  }
                 	 }
                 	</script><div class="borderwrap"><div class="maintitle" align="center">Игра №&nbsp;<b>'.$frows['n'].'</b> Название:&nbsp;<b>'.$frows['g_name'].'</b> Состоялась&nbsp<b>'.strftime('<span id=\'dt\'>%d.%m.%y в %H:%M</span>', strtotime( $frows['dt_g'])).'</b></div><br>';
                 	$res.='
                 	<div align="center" id="lrefon1" onClick="showlogs(\'lrefon1\',\'sc\',\'сценарий\');" style="cursor:pointer;font-weight:bold;">Показать сценарий</div><br>
                 	<div align="center" id="sc" style="display:none;visibility:hidden;margin:2px;">'.$frows['scenariy'].'</div><br>';
                 	$res.='
                 	<div align="center" id="lrefon2" onClick="showlogs(\'lrefon2\',\'tb\',\'статистику\');" style="cursor:pointer;font-weight:bold;">Спрятать статистику</div><br>
                 	<div align="center" id="tb" style="display:inline;visibility:visible;margin:2px;">'.$frows['leveltable'].'</div><br>';
                 	$res.='
                 	<div align="center" id="lrefon" onClick="showlogs(\'lrefon\',\'logs\',\'логи\');" style="cursor:pointer;font-weight:bold;">Показать логи</div><br>
                 	<div align="center" id="logs" style="display:none;visibility:hidden;margin:2px;">'.$frows['logs'].'</div><br></div>';
                 }
                 $this->result=$res;
      }

}


?>