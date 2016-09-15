<?php
namespace Shvatka;

class Shvatka
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
        function parsdig($st)
        {
            $chisla=array('0','1','2','3','4','5','6','7','8','9');
            $ya=true;            	for ($i=0; $i<strlen($st); $i++)
            {
                if (!in_array(substr($st,$i,1),$chisla))
                {                    	$ya=false;
                    break;
                }
            }
            return $ya;
        }

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
            // Do any set up here, like load lang
            // skin files, etc
            //=====================================

              //$this->ipsclass->load_language('lang_boards');
              //$this->ipsclass->load_template('skin_boards');

            //=====================================
            // Set up structure
            //=====================================

            switch( $this->ipsclass->input['cmd'] )
            {
              case 'sh':
                   $this->do_game($this->ipsclass->input['keyw'],$this->ipsclass->input['b_keyw']);
                   break;
              case 'cap':
                    $this->cap($this->ipsclass->input['cnc'],$this->ipsclass->input['del'],$this->ipsclass->input['yes']);
                    break;
              case 'nmem':
                    $this->nmem($this->ipsclass->input['kuda'],$this->ipsclass->input['cnc']);
                    break;
              default:
                    $this->do_game("","");
                    break;
            }
            if ($this->ipsclass->input['lofver']!=1)
            {
                $html=$html.'<font size=2>'.$this->result.'</font>';
                $this->ipsclass->print->add_output( $html );
                $this->nav[] = "<a href='{$this->ipsclass->base_url}act=module&module=shvatka'>СХВАТКА</a>";
                $this->ipsclass->print->do_output(array('OVERRIDE' => 0, 'TITLE' => 'СХВАТКА', 'NAV' => $this->nav));
            }
            else
            {
               echo('<html><head><meta http-equiv="Content-Type" content="text/html; charset=windows-1251" /><title>Схваточка</title></head><body><font size=2>'.$this->result.'</font></body></html>');
            }

            exit();
    }

    /**
     * 
     * @param string $cn 
     * @param string $de
     * @param string $ye
     */
    function cap($cn,$de,$ye)
    {
          $z=0;                                                                           /* Поправка времени в часах */
          $z=$z*3600;
          $zvaniya=array(1=>'Капитан', 2=>'Мозг', 3=>'Полевой', 4=>'Бегунок', 5=>'Радист', 6=>'Радистка', 7=>'Властелин фонарика', 8=>'Водитель', 9=>'Водила', 10=>'Герой асфальта', 11=>'Человек-компас', 12=>'Экстрасенс', 13=>'Грузчик', 14=>'Грузчица', 15=>'Мобильный мозг', 16=>'Доктор', 17=>'Почти СэнСэй', 18=>'Сапёр', 19=>'Спонсор', 20=>'Клоун',21=>'Рекрут',22=>'Стажер',23=>'НаёМник',24=>'СэнСэй');
          $this->ipsclass->DB->query("select * from sh_igroki WHERE (nick='".$this->ipsclass->member['name']."')and(status_in_cmd='Капитан')");
          if ($this->ipsclass->DB->get_num_rows())
          {
               $chisla=array('0','1','2','3','4','5','6','7','8','9');
               $res="<SCRIPT LANGUAGE=\"JavaScript\">
                              function flashhere()
                              {
                                  if (document.getElementById(\"here\").style.color!='rgb(255,0,0)')
                                  {
                                      document.getElementById(\"here\").style.color='rgb(255,0,0)';
                                      self.setTimeout(\"flashhere()\",1500);
                                  }
                                  else
                                  {
                                      document.getElementById(\"here\").style.color='rgb(0,0,0)';
                                      self.setTimeout(\"flashhere()\",100);
                                  }
                              }
                              function quitconfirm()
                              {
                                  if (confirm(\"Вы действительно хотите покинуть игру?\"))
                                  {document.getElementById(\"action\").submit();}
                              }
                              </SCRIPT>";
               if (((($cn!="")and(!parsdig($cn)))or(($de!="")and(!parsdig($de))))or(($ye!="")and(!parsdig($ye))))
               {
                    $res="<font color=red>Запрос не прошел, в запросе использованы недопустимые символы. </font><br><br>";
                    $cn="";
                    $de="";
                    $ye="";
               }
               $frows = $this->ipsclass->DB->fetch_row($fquery);
               $komanda=$frows['komanda'];
               $iid=$this->ipsclass->input['iid'];
               $zvan=$this->ipsclass->input['zvan'];
               if  ((($iid!="")and(parsdig($iid)))or(($zvan!="")and(parsdig($zvan))))
               {
                    if ($iid==$frows['n'])
                    {$zvan=-1;}
                    switch ($zvan)
                    {
                      case -1:
                        $res.='<font size=4 color=red><b><center>Капитан не может поменять себе звание!<br>Можно назначить другого капитаном.</ctnter></b></font>';
                        break;
                      case 1:
                        $res.='<font size=4 color=red><b><center>Теперь вы не капитан - покиньте капитанский мостик!</ctnter></b></font>';                 	  	  $this->ipsclass->DB->query("update sh_igroki set status_in_cmd='Полевой' WHERE (status_in_cmd='Капитан')and(komanda='".$komanda."')");
                        $this->ipsclass->DB->query("update sh_igroki set status_in_cmd='Капитан' WHERE (n=".$iid.")and(komanda='".$komanda."')");
                        break;
                      default:                 	  	  if (in_array($zvan,array_keys($zvaniya)))
                        $this->ipsclass->DB->query("update sh_igroki set status_in_cmd='".$zvaniya[$zvan]."' WHERE (n=".$iid.")and(komanda='".$komanda."')");
                        else
                        $res.='<font size=4 color=red><b><center>Такого звания нет!</ctnter></b></font>';
                        break;
                    }                 }
               $res=$res."<div class=\"borderwrap\"><center class=\"maintitle\"><b>Капитанский мостик команды ".$komanda."</b></center>";
               $this->ipsclass->DB->query("select * from sh_comands WHERE nazvanie='".$komanda."'");
               $frows = $this->ipsclass->DB->fetch_row($fquery);
               $id_team=$frows['n'];
               if ($de!="")
               {
                     $this->ipsclass->DB->query("select * from sh_igroki WHERE n=".$de);
                     $frows = $this->ipsclass->DB->fetch_row($fquery);
                     if ($frows['komanda']==$komanda)
                     {
                          $this->ipsclass->DB->query("update members  set mgroup='3'WHERE name='".$frows['nick']."'");
                          $this->ipsclass->DB->query("update sh_igroki set komanda='Не в команде', status_in_cmd='' WHERE n=".$de);
                     }
                     else
                     {
                          $res=$res.'<br>Не удалось исключить! Наверное этот игрок не в вашей команде!<br>';
                     }
               }
               if (($ye!="")and(mysql_num_rows($this->ipsclass->DB->query("select * from sh_recrut WHERE n=".$ye))!=0))
               {
                     $frows = $this->ipsclass->DB->fetch_row($fquery);
                     $kto=$frows['kto'];
                     if (mysql_num_rows($this->ipsclass->DB->query("select * from sh_igroki WHERE (nick='".$kto."')and(komanda='".$komanda."')")))
                     {
                          $res=$res."<br>Этот игрок уже зачилен в вашу команду.<br>";
                          $this->ipsclass->DB->query("delete from sh_recrut WHERE n=".$ye);
                     }
                     else
                     {
                          $this->ipsclass->DB->query("select * from sh_igroki WHERE (nick='".$kto."')");
                          $frows = $this->ipsclass->DB->fetch_row($fquery);
                          if (($frows['n']!="")and($frows['komanda']!="Не в команде"))
                          {
                               $res=$res."<br>Этот игрок в другой команде и не может быть зачислен в вашу команду, пока не уйдёт оттуда.<br>";
                          }
                          else
                          {
                               if (mysql_num_rows($this->ipsclass->DB->query("select * from groups WHERE g_title='".$komanda."'")))
                               {
                                    $fr = $this->ipsclass->DB->fetch_row($fquery);
                               }
                               $this->ipsclass->DB->query("update members  set mgroup='".$fr['g_id']."'WHERE name='".$kto."'");
                               if (($frows['n']!="")and($frows['komanda']=="Не в команде"))
                               {
                                    $this->ipsclass->DB->query("update sh_igroki set komanda='".$komanda."', status_in_cmd='Полевой' WHERE nick='".$kto."'");
                                    $this->ipsclass->DB->query("delete from sh_recrut WHERE n=".$ye);
                               }
                               else
                               {
                                    $this->ipsclass->DB->query("INSERT INTO sh_igroki (nick, komanda) values('".$kto."', '".$komanda."')");
                                    $this->ipsclass->DB->query("delete from sh_recrut WHERE n=".$ye);
                               }
                          }
                     }
               }
               if (($cn!="")and(mysql_num_rows($this->ipsclass->DB->query("select * from sh_recrut WHERE (n=".$cn.")and(kuda=".$id_team.")"))!=0))
               {
                       $this->ipsclass->DB->query("update sh_recrut set otvet='Капитан вам отказал, отзовите свою заявку.' WHERE n=".$cn);
               }
               $this->ipsclass->DB->query("select * from sh_igroki WHERE komanda='".$komanda."'");
               $res=$res."<br><TABLE cellspacing=\"1\" style={width:auto;} class=\"borderwrap\" align=\"center\"><tr><th COLSPAN=3>Ваша команда</th></tr>";
               while ($frows = $this->ipsclass->DB->fetch_row($fquery))
               {
                     $res=$res.'<tr class="ipbtable"><td class="row1"><b>'.($frows['nick']).'</b></center></td><td class="row1"><form  action="./index.php" method="post">
<input type=HIDDEN name="act" value="module">
<input type=HIDDEN name="module" value="shvatka">
<input type=HIDDEN name="cmd" value="cap">
<input type=HIDDEN name="iid" value='.$frows['n'].'>
<select name="zvan" onchange="submit()">';
                     foreach ($zvaniya as $ind=>$zn)
                     {                       	$res.='<option value='.$ind.' ';
                      if ($frows['status_in_cmd']==$zn)
                        {$res.='selected';}
                      if (!in_array($frows['status_in_cmd'], $zvaniya)) { if ($zn=='Полевой') {$res.='selected';}}
                      $res.='>'.$zn;
                     }

                     $res.='</select></form></td><td class="row1"><center><a href="./index.php?act=module&module=shvatka&cmd=cap&del='.($frows['n']).'"><font size=1 color=red>Исключить</font></center></td></tr>';
               }
               $res=$res."</table ><br><TABLE cellspacing=\"1\" style={width:auto;} class=\"borderwrap\" align=\"center\"><tr><th COLSPAN=2>Заявки в вашу команду.</th></tr>";
               $this->ipsclass->DB->query("select * from sh_recrut  WHERE kuda='".$id_team."'");
               while ($frows = $this->ipsclass->DB->fetch_row($fquery))
               {
                     if ($frows['otvet']!="Капитан вам отказал, отзовите свою заявку.")
                     {
                           $res=$res."<tr class=\"ipbtable\"><td class=\"row1\"><center><b>".$frows['kto']."</b></center></td>";
                           $res=$res.'<td class="row2"><center><a href="./index.php?act=module&module=shvatka&cmd=cap&yes='.($frows['n']).'"><font size=1 color=green>Принять</font></a>  <a href="./index.php?act=module&module=shvatka&cmd=cap&cnc='.($frows['n']).'"><font size=1 color=red>Отказать</font></a></center></td></tr>';
                     }
               }
               $res=$res."</table><br>";
               $res.='<font size=2 color=red><center><br>Внимание! Смена звания у игрока происходит автоматически, как только вы изменили его в форме.<br>При назначении нового капитана, старый приобретает звание "Полевой" и теряет доступ на капитанский мостик.<br>Капитан не может поменять своё звание!<br>Будьте внимательны!!!</center></font></div>';
               $this->ipsclass->DB->query("select * from sh_games WHERE status='п'");
               $frows = $this->ipsclass->DB->fetch_row($fquery);
               if ($frows['n']!="")
               {
                 $g_id=$frows['n'];
                 if (strtotime($frows['dt_g'])<=($z+(strtotime("now"))))
                 {

                    $this->ipsclass->DB->query("select * from sh_comands WHERE nazvanie='".$komanda."'");
                    $frows = $this->ipsclass->DB->fetch_row($fquery);
                    if ($frows['dengi'])
                    {
                          if ($this->ipsclass->input['quit']=='34523422342323244234543')
                          {
                             $this->ipsclass->DB->query("update sh_comands set uroven=0, podskazka=0, dengi=0, dt_ur='".(date('Y-m-d H:i:s',($z+strtotime("now"))))."', cmp_games='".$frows['cmp_games']."<s>".$g_id."</s> ' WHERE nazvanie='".$komanda."'");
                             $this->ipsclass->DB->query("update sh_igroki set ch_dengi=0 WHERE (ch_dengi=1)and(komanda='".$komanda."')");
                             if (mysql_num_rows($this->ipsclass->DB->query("select * from sh_comands WHERE dengi=1"))==0)
                             {$this->ipsclass->DB->query("update  sh_games set status='т' WHERE status='п'");}
                             header('Location:./index.php?act=module&module=shvatka&cmd=cap');
                          }
                          else
                          {
                              $res.="<br><div align='center' style={width:auto;} id='here' class=\"borderwrap\"><br>Сейчас идёт игра. Если вы поняли, что ваша команда не хочет<br>или не может продолжать игру, то нажмите кнопку<br>
                              <form action=./index.php id='action' method='post'>
                              <input name=\"act\" type=\"hidden\" value=\"module\">
                              <input name=\"module\" type=\"hidden\" value=\"shvatka\">
                              <input name=\"cmd\" type=\"hidden\" value=\"cap\">
                              <input name=\"quit\" type=\"hidden\" value=\"34523422342323244234543\">
                              <input type=button value='Покинуть игру' onclick=\"quitconfirm()\"><br>
                              <table><tr><td align=\"left\" style={width:500px;}>Выход из игры означает:<br>                         
                              1. Иргоки и команда переводятся в статус \"несдавшие деньги\".<br>
                              2. Очки вашей команде и игрокам за эту игру не начисляются.<br>
                              3. Участие в игре игрокам команды не засчитывается.<br>
                              4. Участие в игре команде засчитывается как неполное (зачеркнутый номер игры в статистике команды)<br>
                              5. Доступ к текущей игре для вас закрывается.<br></td></tr></table>&nbsp;</div>
                              <SCRIPT LANGUAGE=\"JavaScript\">
                              self.setTimeout(\"flashhere()\",100);
                              </SCRIPT><br>";
                          }
                    }

                 }
               }
          }
          else
          {
               $res="Вы не капитан! Вам сюда нельзя!";
          }
          $this->result=$res;
    }

    function nmem($ku,$cn)
    {
          $chisla=array('0','1','2','3','4','5','6','7','8','9');
          $res="";
          if (($ku!="")and(!parsdig($ku)))
          {
             $res="Заявка не прошла, в запросе использованы недопустимые символы<br>";
             $ku="";
          }
          if (($cn!="")and(!parsdig($cn)))
          {
             $res="Отмена заявки не прошла, в запросе использованы недопустимые символы<br>";
             $cn="";
          }
          if ($this->ipsclass->member['id']!="")
          {
             if ($ku!="")
             {
                 if ((mysql_num_rows($this->ipsclass->DB->query("select * from sh_recrut WHERE (kuda='".$ku."')and(kto='".($this->ipsclass->member['name'])."')"))==0)and(mysql_num_rows($this->ipsclass->DB->query("select * from sh_comands WHERE n=".$ku))!=0))
                 {                   	  if (mysql_num_rows($this->ipsclass->DB->query("select * from sh_recrut"))==0)
                    {
                       $this->ipsclass->DB->query("ALTER TABLE sh_recrut PACK_KEYS =0 CHECKSUM =0 DELAY_KEY_WRITE =0 AUTO_INCREMENT =1");                   	  }                   	  $this->ipsclass->DB->query("INSERT INTO sh_recrut(kto, kuda) values('".$this->ipsclass->member['name']."', '".$ku."')");
                 }
                 else
                 {$res=$res."Либо вы уже подали заявку в эту команду, либо такой команды не существует!<br>";}
             }
             if ($cn!="")
             {
                 if ((mysql_num_rows($this->ipsclass->DB->query("select * from sh_recrut WHERE n=".$cn))!=0))
                 {
                    $this->ipsclass->DB->query("DELETE FROM sh_recrut WHERE n=".$cn);
                 }
                 else
                 {$res=$res."Такой заявки нет!<br>";}
             }


             $res=$res.'Куда хотите подать заявку, '.$this->ipsclass->member['name'].' ?<br><Form action="./index.php" method="post">
<input type=HIDDEN name="act" value="module">Команда: <input type=HIDDEN name="module" value="shvatka">
<input type=HIDDEN name="cmd" value="nmem"><SELECT name="kuda">';
             $this->ipsclass->DB->query("select * from sh_comands");
             $cm_array=array();
             while ($frows = $this->ipsclass->DB->fetch_row($fquery))
             {
                 $cm_array[$frows['n']]=$frows['nazvanie'];
                 $res=$res."<OPTION value='".$frows['n']."'>".$cm_array[$frows['n']];
             }
             $res=$res."</option></select><input type=submit value=' Подать заявку ' style='background:#D2D0D0;border:1px;border:outset;border-color:#ffffff'><br></Form>";
             if (mysql_num_rows($this->ipsclass->DB->query("select * from sh_recrut WHERE kto='".$this->ipsclass->member['name']."'"))!=0)
             {
                 $res=$res."<TABLE cellspacing=\"1\" style={width:auto;} class=\"borderwrap\"><th COLSPAN=3><b>Вы подали заявки в следующие команды:</b></th>";
                 while ($frows = $this->ipsclass->DB->fetch_row($fquery))
                 {
                    $res=$res.'<tr class="ipbtable"><td class="row2"><b>'.$cm_array[$frows['kuda']].'</b></td><td class="row2"><center><a href="./index.php?act=module&module=shvatka&cmd=nmem&cnc='.($frows['n']).'"><font size=1 color=red>Отозвать заявку</font></a></td><td class="row2" style={font-style:italic}><blink><font size=1>   '.($frows['otvet']).'</font></blink></td></tr>';
                 }
                 $res=$res.'</table>';
             }
          }
          else
          {
             $res='Вы не залогинились на форуме. Сначала залогинтесь на форуме!<br>';
          }
          $this->result=$res;
    }

      function do_game($k,$bk)
      {
            $z=0;                                                                           /* Поправка времени в часах */
            $z=$z*3600;
            //if ((strstr($k, "&")!="")or(strstr($k, "<")!="")) {$k='Были использованы запрещённые символы';};
            $bk=addslashes($bk);
            $k=addslashes($k);
            $gameover=false;
            $res="";
            $res.= "
                <div id=\"userlinks\">
                <p class=\"home\"><b>Управление СХВАТКОЙ:</b></p>
                <p>";
            $qqq=$this->ipsclass->DB->query("select field_4 from pfields_content where member_id=".$this->ipsclass->member['id']."");
            $fff = $this->ipsclass->DB->fetch_row($qqq);
            if (( $this->ipsclass->member['mgroup'] == $this->ipsclass->vars['admin_group'] )or($fff['field_4']=='y'))
            {
                $res.= "<a href='{$this->ipsclass->base_url}act=module&module=reps'>Админцентр Схватки</a> &middot;&nbsp;";
            }
            $this->ipsclass->DB->query("select * from sh_igroki WHERE nick='".$this->ipsclass->member['name']."'");
            $frows = $this->ipsclass->DB->fetch_row($fquery);
            if ($frows['status_in_cmd']=='Капитан') $res.="<a href='{$this->ipsclass->base_url}act=module&module=shvatka&cmd=cap'>Капитанский мостик</a> &middot;&nbsp;";
            $res.="<a href='{$this->ipsclass->base_url}act=module&module=shvatka&cmd=nmem'>Хочу в команду</a> &middot;&nbsp;
                <a href='{$this->ipsclass->base_url}act=module&module=shstat'>SH-cтатистика</a>
                </p>
                </div>
                <br>
                ";
            $komanda="";
            $lev=0;
            $podskazka=0;
            $dtcom="";
            $this->ipsclass->DB->query("select * from sh_igroki WHERE nick='".$this->ipsclass->member['name']."'");
            $frows = $this->ipsclass->DB->fetch_row($fquery);
                if (( $frows['n'] == "" )or($frows['komanda'] == "Не в команде"))            /* Смотрим зачислен ли чел к какую-нить команду */
                {
                  if ($this->ipsclass->member['id']!="")
                  {
                       $res=$res."Вы не зачисленные ни в одну из команд.<br>";
                       $this->ipsclass->DB->query("select * from sh_games WHERE status='п'");
                       $frows = $this->ipsclass->DB->fetch_row($fquery);
                       if ($frows['n']!="")                                                   /* Смотрим есть ли предстоящая или текущая игра */
                       {
                            $res=$res.'<center><b>Название игры: '.$frows['g_name'].'</b></center><br>';

                            if (strtotime($frows['dt_g'])>($z+(strtotime("now"))))                    /* Смотрим запущена ли игра (время) */
                            {
                                if ($frows['fond']!="")
                                {$res=$res.'<br>';}
                                $ctd=strtotime($frows['dt_g'])-($z+(strtotime("now")));
                                $res=$res.'<center id="gtimer" >Игра начнется '.strftime('<span id=\'dt\'>%d.%m.%y в %H:%M</span>', strtotime( $frows['dt_g'])).' т.е. через '.sectostr($ctd).' </center><br>';
                                $res=$res. <<<EOF
<SCRIPT LANGUAGE="JavaScript">
function CountDown()
{ ctd=0;
tmp=0;
tmp2=0;
tmp3=0;
tmp4=0;
if (document.getElementById('days')!=null) ctd=parseInt(document.getElementById('days').innerHTML)*24*60*60;
if (document.getElementById('hours')!=null) ctd+=parseInt(document.getElementById('hours').innerHTML)*60*60;
if (document.getElementById('mins')!=null) ctd+=parseInt(document.getElementById('mins').innerHTML)*60;
if (document.getElementById('secs')!=null) ctd+=parseInt(document.getElementById('secs').innerHTML);
st="Игра начнется <span id='dt'>";
st+=document.getElementById('dt').innerHTML;
st+="</span> т.е. через "
if (ctd<=0)
{
ctd=0;
st+="... УЖЕ НАЧАЛАСЬ!!!";
}
else
{
ctd=ctd-1;
tmp=Math.floor(ctd/86400);
tmp2=Math.floor((ctd-(tmp*86400))/3600);
tmp3=Math.floor((ctd-(tmp*86400)-(tmp2*3600))/60);
tmp4=Math.floor(ctd-(tmp*86400)-(tmp2*3600)-(tmp3*60));
if (tmp>0) st+="<span id='days'>"+tmp+"</span> д. ";
if (tmp2>0) st+="<span id='hours'>"+tmp2+"</span> ч. ";
if (tmp3>0) st+="<span id='mins'>"+tmp3+"</span> м. ";
if (tmp4>=0) st+="<span id='secs'>"+tmp4+"</span> сек. ";
}
document.getElementById('gtimer').innerHTML =st;
window.setTimeout("CountDown()",1000);
}
CountDown()
</SCRIPT>
EOF;
                                $this->ipsclass->DB->query("select * from sh_comands WHERE dengi=1");
                                $res=$res."<center><br><TABLE cellspacing=\"1\" style={width:auto;} class=\"borderwrap\">
<tr><td class=\"maintitle\" align=\"center\" colspan=\"2\">Заявленные команды</td></tr><tr><th align=\"center\"><b>Название</b></th><th align=\"center\"><b>Очки</b></th></tr>";
                                while ($frows = $this->ipsclass->DB->fetch_row($fquery))
                                {
                                       $res=$res."<tr class=\"ipbtable\"><td class=\"row1\"><b>".$frows['nazvanie']."</b></td><td class=\"row1\"> ".$frows['ochki']."</td></tr>";
                                }
                                $res=$res."</table></center><br>";
                                $this->ipsclass->DB->query("select * from sh_igroki WHERE ch_dengi=1 order by komanda");
                                $res=$res."<center><TABLE cellspacing=\"1\" style={width:auto;} class=\"borderwrap\"><tr><td class=\"maintitle\" align=\"center\" colspan=\"3\">Игроки сделавшие взносы</td></tr>
<tr><th align=\"center\"><b>Участник</b></th><th align=\"center\"><b>Команда</b></th><th align=\"center\"><b>Очки</b></td></tr>";
                                while ($frows = $this->ipsclass->DB->fetch_row($fquery))
                                {
                                       $res=$res."<tr class=\"ipbtable\"><td class=\"row1\"><b>".$frows['nick']."</b></td><td class=\"row1\"> ".$frows['komanda']."</td><td class=\"row1\"> ".$frows['ochki']."</td></tr>";
                                }
                                $res=$res."</table></center>";
                           }
                           else
                           {
                                $res=$res.'Игра уже идёт.<br><br>';
                           }
                        }
                        else
                        {
                                $res=$res.'Дата предстоящей игры пока не определена.<br><br>';
                        }
                  }
                  else
                  {
                       $res='Вы не залогинились на форуме. Сначала залогинтесь!<br>';
                  }

                }
                else                                                                         /* Зачислен */
                {
                  $chel=$frows['ch_dengi'];                                              /* регистрация на игру */
                  $komanda = $frows['komanda'];                                          /* название команды */
//                        $res=$frows['n'].'<br>'.$frows['nick'].'<br>'.$frows['komanda'].'<br>'.$frows['status_in_cmd'].'<br>'.$frows['ochki'].'<br>' ;
                  $this->ipsclass->DB->query("select * from sh_games WHERE status='п'");
                  $frows = $this->ipsclass->DB->fetch_row($fquery);
                  $tmg=strtotime($frows['dt_g']);
                  $g_id=$frows['n'];
                  if ($frows['n']!="")                                                   /* Смотрим есть ли предстоящая или текущая игра */
                  {
                      $res=$res.'<center><b>Название игры: '.$frows['g_name'].'</b></center><br>';
                      if (strtotime($frows['dt_g'])>($z+(strtotime("now"))))                    /* Смотрим запущена ли игра (время) */
                      {
                          if ($frows['fond']!="")
                          {$res=$res.'<br>';}
                          $ctd=strtotime($frows['dt_g'])-($z+(strtotime("now")));
                          $res=$res.'<center id="gtimer" >Игра начнется '.strftime('<span id=\'dt\'>%d.%m.%y в %H:%M</span>', strtotime( $frows['dt_g'])).' т.е. через '.sectostr($ctd).' </center><br>';
                          $res=$res. <<<EOF
<SCRIPT LANGUAGE="JavaScript">
function CountDown()
{ ctd=0;
tmp=0;
tmp2=0;
tmp3=0;
tmp4=0;
if (document.getElementById('days')!=null) ctd=parseInt(document.getElementById('days').innerHTML)*24*60*60;
if (document.getElementById('hours')!=null) ctd+=parseInt(document.getElementById('hours').innerHTML)*60*60;
if (document.getElementById('mins')!=null) ctd+=parseInt(document.getElementById('mins').innerHTML)*60;
if (document.getElementById('secs')!=null) ctd+=parseInt(document.getElementById('secs').innerHTML);
st="Игра начнется <span id='dt'>";
st+=document.getElementById('dt').innerHTML;
st+="</span> т.е. через "
if (ctd<=0)
{
ctd=0;
st+="... УЖЕ НАЧАЛАСЬ!!!";
}
else
{
ctd=ctd-1;
tmp=Math.floor(ctd/86400);
tmp2=Math.floor((ctd-(tmp*86400))/3600);
tmp3=Math.floor((ctd-(tmp*86400)-(tmp2*3600))/60);
tmp4=Math.floor(ctd-(tmp*86400)-(tmp2*3600)-(tmp3*60));
if (tmp>0) st+="<span id='days'>"+tmp+"</span> д. ";
if (tmp2>0) st+="<span id='hours'>"+tmp2+"</span> ч. ";
if (tmp3>0) st+="<span id='mins'>"+tmp3+"</span> м. ";
if (tmp4>=0) st+="<span id='secs'>"+tmp4+"</span> сек. ";
}
document.getElementById('gtimer').innerHTML =st;
window.setTimeout("CountDown()",1000);
}
CountDown()
</SCRIPT>
EOF;
                          $this->ipsclass->DB->query("select * from sh_comands WHERE dengi=1");
                          $res=$res."<center><br><TABLE cellspacing=\"1\" style={width:auto;} class=\"borderwrap\">
<tr><td class=\"maintitle\" align=\"center\" colspan=\"2\">Заявленные команды</td></tr><tr><th align=\"center\"><b>Название</b></th><th align=\"center\"><b>Очки</b></th></tr>";
                          while ($frows = $this->ipsclass->DB->fetch_row($fquery))
                          {
                               $res=$res."<tr class=\"ipbtable\"><td class=\"row1\"><b>".$frows['nazvanie']."</b></td><td class=\"row1\"> ".$frows['ochki']."</td></tr>";
                          }
                          $res=$res."</table></center><br>";
                          $this->ipsclass->DB->query("select * from sh_igroki WHERE ch_dengi=1 order by komanda");
                          $res=$res."<center><TABLE cellspacing=\"1\" style={width:auto;} class=\"borderwrap\"><tr><td class=\"maintitle\" align=\"center\" colspan=\"3\">Игроки сделавшие взносы</td></tr>
<tr><th align=\"center\"><b>Участник</b></th><th align=\"center\"><b>Команда</b></th><th align=\"center\"><b>Очки</b></td></tr>";
                          while ($frows = $this->ipsclass->DB->fetch_row($fquery))
                          {
                               $res=$res."<tr class=\"ipbtable\"><td class=\"row1\"><b>".$frows['nick']."</b></td><td class=\"row1\"> ".$frows['komanda']."</td><td class=\"row1\"> ".$frows['ochki']."</td></tr>";
                          }
                          $res=$res."</table></center>";
                      }
                      else
                      {
     //                   $res=$res.'Игра началась!<br>';
                          $this->ipsclass->DB->query("select * from sh_comands WHERE nazvanie='".$komanda."'");
                          $frows = $this->ipsclass->DB->fetch_row($fquery);
                          $lev=$frows['uroven'];                                         /* уровень на котором находится команда */
                          $podskazka=$frows['podskazka'];                                /* номер подсказки команды */
                          $dtcom=$frows['dt_ur'];                                        /* время начала текущего уровня команды */
                          if (($frows['dengi'])and($chel))                               /* Смотрим зарегистрирован ли игрок на игру*/
                          {
     //                        $res=$res.'Деньги сданы<br>';
                               if ($lev==0)
                               {
                                   $lev=1;
                                   $podskazka=0;
                                   $dtcom=date('Y-m-d H:i:s',($z+strtotime("now")));
                                   $this->ipsclass->DB->query("update sh_comands set uroven=1, podskazka=0, dt_ur='".$dtcom."' WHERE nazvanie='".$komanda."'");
                               }
                               $this->ipsclass->DB->query("select * from sh_game WHERE (uroven=". $lev.")and(n_podskazki=0)");
                               $frows = $this->ipsclass->DB->fetch_row($fquery);
                               $level_b_key=$frows['b_keyw'];
                               if (($k!="")or($bk!=""))                                               /* Если ключ введён - пишем лог и проверяем ключ, если нет - смотрим пришло ли время для подсказки*/
                               {
                                   /*$this->ipsclass->DB->query("select * from sh_log WHERE n=(select MAX(n) from sh_log)");
                                   $frows1 = $this->ipsclass->DB->fetch_row($fquery);
                                   $this->ipsclass->DB->query("INSERT INTO sh_log values(".($frows1['n']+1).",'".$komanda."','".date('Y-m-d H:i:s')."','".$k."')");  */
                                   if (($k!=$frows['keyw'])or($level_b_key!=$bk))
                                   {
                                             $this->ipsclass->DB->query("INSERT INTO sh_log (comanda, time, keytext, autor) values('".$komanda."','".date('Y-m-d H:i:s',($z+(strtotime("now"))))."','".str_replace(" ","&nbsp;",htmlspecialchars($k.$bk))."','".$this->ipsclass->member['name']."')");
                                             /*if ($this->ipsclass->input['lofver']==1)
                                             header('Location:./index.php?act=module&module=shvatka&lofver=1');
                                             else
                                             header('Location:./index.php?act=module&module=shvatka');*/
                                   }
                                   else
                                   {
                                             $this->ipsclass->DB->query("INSERT INTO sh_log (comanda, time, keytext, autor, levdone) values('".$komanda."','".date('Y-m-d H:i:s',($z+(strtotime("now"))))."','".str_replace(" ","&nbsp;",htmlspecialchars($k.$bk))."','".$this->ipsclass->member['name']."','".$lev."')");
                                   }

                               }
                               if (($k==$frows['keyw'])and($level_b_key==$bk))
                               {
 //                                $res=$res.'Ключ подошел. Переход на следующий уровень'.'<br>';
                                   $lev=$lev+1;
                                   $podskazka=0;
                                   $this->ipsclass->DB->query("select * from sh_game WHERE (uroven=".$lev.")and(n_podskazki=0)");
                                   $frows = $this->ipsclass->DB->fetch_row($fquery);
                                   $level_b_key=$frows['b_keyw'];
                                   $ltn=date('Y-m-d H:i:s',($z+strtotime("now")));
                                   if ($frows['n']!="")
                                   {
                                        $this->ipsclass->DB->query("update sh_comands set uroven=".$lev.", podskazka=".$podskazka.", dt_ur='".$ltn."' WHERE nazvanie='".$komanda."'");
                                        $dtcom=$ltn;
                                   }
                                   else
                                   {
                                        $this->ipsclass->DB->query("select * from sh_comands WHERE nazvanie='".$komanda."'");
                                        $frows = $this->ipsclass->DB->fetch_row($fquery);
                                        $this->ipsclass->DB->query("update  sh_comands set uroven=0, podskazka=0, dengi=0, dt_ur='".$ltn."', cmp_games='".$frows['cmp_games'].$g_id." ' WHERE nazvanie='".$komanda."'");
                                        $res='Поздравляем. Игра пройдена за '.sectostr(strtotime($ltn)-$tmg);
                                        $gs=array();
                                        $this->ipsclass->DB->query("select * from sh_igroki WHERE (ch_dengi=1)and(komanda='".$komanda."')");
                                        while ($frows = $this->ipsclass->DB->fetch_row($fquery))
                                        {                                        	 $gs[$frows['n']]=$frows['games'].$g_id;
                                        }
                                        foreach ($gs as $nig=>$gm)
                                        {
                                             $this->ipsclass->DB->query("update  sh_igroki set ch_dengi=0, games='".$gm." ' WHERE n='".$nig."'");
                                        }
                                        $this->ipsclass->DB->query("select * from sh_games WHERE n=".$g_id);
                                        $frows = $this->ipsclass->DB->fetch_row($fquery);
                                        $this->ipsclass->DB->query("update  sh_games set pedistal='".$frows['pedistal'].$komanda."<br>' WHERE n=".$g_id);
                                        $gameover=true;
                                        if (mysql_num_rows($this->ipsclass->DB->query("select * from sh_comands WHERE dengi=1"))==0)
                                        {$this->ipsclass->DB->query("update  sh_games set status='т' WHERE status='п'");}

                                   }
                               }
                               if (!$gameover)
                               {
                                   if (($z+strtotime("now")-strtotime($dtcom))>0)
                                   {$res=$res.'<center><b>Уровень '.$lev.'. С начала уровня прошло '.(sectostr($z+strtotime("now")-strtotime($dtcom))).'</b></center><br>'.$frows['text'].'<br>';}
                                   else
                                   {$res=$res.'<center><b>Уровень '.$lev.' начат '.(date('d-m-y в H:i:s',(strtotime($dtcom)))).'</b></center><br>'.$frows['text'].'<br>';}
                                   if ($podskazka>0)
                                   {
                                        for ($i=1;$i<= $podskazka;$i++)
                                        {
                                             $this->ipsclass->DB->query("select * from sh_game WHERE (uroven=". $lev.")and(n_podskazki=".$i.")");
                                             $frows = $this->ipsclass->DB->fetch_row($fquery);
                                             $res=$res.'<b>Подсказка '.$i.': </b>'.$frows['text'].'<br>';
                                        }
                                   }
                                   if (($frows['p_time']!=0)and(((60*$frows['p_time'])+strtotime($dtcom))<=($z+(strtotime("now")))))
                                   {
                                        $podskazka=$podskazka+1;
                                        $this->ipsclass->DB->query("select * from sh_game WHERE (uroven=". $lev.")and(n_podskazki=".$podskazka.")");
                                        $frows = $this->ipsclass->DB->fetch_row($fquery);
                                        if ($frows['n']!="")
                                        {
                                             $this->ipsclass->DB->query("update sh_comands set podskazka=".$podskazka." WHERE nazvanie='".$komanda."'");
                                        }
                                        $res=$res.'<b>Подсказка '.$podskazka.': </b>'.$frows['text'].'<br>';
                                   }
                                   $res=$res.'<Form action="./index.php" autocomplete="on" method="post">
<input type=HIDDEN name="act" value="module">Ключ: <input type=HIDDEN name="module" value="shvatka">
<input type=HIDDEN name="cmd" value="sh">';
if ($this->ipsclass->input['lofver']==1)
{$res.='<input type=HIDDEN name="lofver" value="1">';}
$res.='<input type=text name="keyw" SIZE=50>&nbsp;';
if ($level_b_key!='')
    {
	$res.='Мозговой ключ: <input type=text name="b_keyw" SIZE=50 ';
    if ($level_b_key==$bk) $res.='value="'.$level_b_key.'" readonly >ОК';
    else $res.='value="">';
    }
$res.='<br><input type=submit value="  Проверить ключ/наличие подсказки     " style={background:#D2D0D0;border:1px;border:outset;border-color:#ffffff}>
</form>';
                               }
                          }
                          else
                          {
                               $res=$res.'Одно из трёх,<br>
либо текущая игра пройдена вами, но ещё не закончена другими,<br>
либо ваша команда не заявлена на эту игру,<br>
либо вы лично не сделали взнос.<br><br>';
                          }
                      }
                  }
                  else
                  {
                      $res=$res.'Дата предстоящей игры пока не определена.<br><br>';
                  }
	              $adm_msg='';
	              $msg_q=$this->ipsclass->DB->query("select * from sh_admin_msg WHERE ((komand='все')|(FIND_IN_SET('".$komanda."',komand)!=0))&((endtime>='".mktime()."')&(FIND_IN_SET('".$komanda."',readed)=0))");
	              while ($frows = $this->ipsclass->DB->fetch_row($msg_q))
	              {
	             	if ($frows['komand']=='все') {$color='#EE634F'; $komu='<i><u>всех</u></i> команд';} else {$color='#67ED50'; $komu='команды <i><u>'.$komanda.'</u></i>';}
	             	if ($frows['endtime']>=mktime()) $adm_msg.='<table style="border: 1px solid black;width:100%;background:'.$color.';"><tr><th>Сообщение от организатора '.$frows['autor'].' для '.$komu.'.</th></tr><tr class="ipbtable"><td class="row1">'.$frows['msg'].' <div align="right"><form  "./index.php" method="get">
<input name="act" type="hidden" value="module">
<input name="module" type="hidden" value="utils">
<input name="hsh" type="hidden" value="'.$frows['hash'].'"><input style="font: 8pt tahoma; padding: 0pt;"type="submit" value="Прочитал"></form></div></td></tr></table>';
	              }
	              if ($adm_msg!='') $res.='<div id="adm_msg_div" style="left:35%;top:35%;width:30%;height:auto;overflow: auto;position:absolute;" onClick="javascript:this.style.display=\'none\'">'.$adm_msg.'</div>';
                }
            if ($this->ipsclass->input['lofver']==1)
            {$this->result=$res."<center><font size=1><a href='{$this->ipsclass->base_url}act=module&module=shvatka'>Схватка с оформлением</a></font></center>";}
            else
            {$this->result=$res."<table cellspacing=\"0\" id=\"gfooter\"><tr><td align=\"center\"><b><a href='{$this->ipsclass->base_url}act=module&module=shvatka&lofver=1'}>Схватка без оформления</a></b></td></tr></table>";}
      }
}


?>