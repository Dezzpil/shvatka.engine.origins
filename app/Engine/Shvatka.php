<?php
namespace App\Engine;

class Shvatka extends Base
{
    function parsdig($st)
    {
        $chisla=array('0','1','2','3','4','5','6','7','8','9');
        $ya=true;            	
        for ($i=0; $i<strlen($st); $i++) {
            if (!in_array(substr($st,$i,1),$chisla)) {                    	
                $ya=false;
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
    
    function run_module()
    {
        parent::run_module();

        switch ($this->ipsclass->input['cmd']) {
            case 'sh':
               $this->do_game($this->ipsclass->input['keyw'], $this->ipsclass->input['b_keyw']);
               break;
            case 'cap':
                $this->cap($this->ipsclass->input['cnc'], $this->ipsclass->input['del'], $this->ipsclass->input['yes']);
                break;
            case 'nmem':
                $this->nmem($this->ipsclass->input['kuda'], $this->ipsclass->input['cnc']);
                break;
            default:
                $this->do_game("","");
                break;
        }
        
        $html = '<font size=2>'.$this->result.'</font>';
        $this->ipsclass->print->add_output( $html );
        $this->nav[] = "<a href='{$this->ipsclass->base_url}act=module&module=shvatka'>СХВАТКА</a>";
        return $this->ipsclass->print->do_output(array('OVERRIDE' => 0, 'TITLE' => 'СХВАТКА', 'NAV' => $this->nav));
    }

    /**
     * Управление игроками команды и заявками в команду
     * @param string $cn отказ в принятии заявки от рекрута с указанным n
     * @param string $de исключить игрока с указанным n
     * @param string $ye принять рекрута с указанным n
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
             if (((($cn!="")and(!$this->parsdig($cn)))or(($de!="")and(!$this->parsdig($de))))or(($ye!="")and(!$this->parsdig($ye))))
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
             if  ((($iid!="")and($this->parsdig($iid)))or(($zvan!="")and($this->parsdig($zvan))))
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

             if (($ye!="")and(count($this->ipsclass->DB->query("select * from sh_recrut WHERE n=".$ye))!=0))
             {
                   $frows = $this->ipsclass->DB->fetch_row($fquery);
                   $kto=$frows['kto'];
                   if (count($this->ipsclass->DB->query("select * from sh_igroki WHERE (nick='".$kto."')and(komanda='".$komanda."')")))
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
                             if (count($this->ipsclass->DB->query("select * from groups WHERE g_title='".$komanda."'")))
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
             if (($cn!="")and(count($this->ipsclass->DB->query("select * from sh_recrut WHERE (n=".$cn.")and(kuda=".$id_team.")"))!=0))
             {
                     $this->ipsclass->DB->query("update sh_recrut set otvet='Капитан вам отказал, отзовите свою заявку.' WHERE n=".$cn);
             }
             $this->ipsclass->DB->query("select * from sh_igroki WHERE komanda='".$komanda."'");
             $res=$res."<br><TABLE cellspacing=\"1\" class=\"borderwrap\" align=\"center\"><tr><th COLSPAN=3>Ваша команда</th></tr>";
             while ($frows = $this->ipsclass->DB->fetch_row($fquery))
             {
                   $res=$res.'<tr class="ipbtable"><td class="row1"><b>'.($frows['nick']).'</b></center></td><td class="row1"><form  action="' . $this->ipsclass->base_url . '" method="post">
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

                   $res.='</select></form></td><td class="row1"><center><a href="' . $this->ipsclass->base_url . '?act=module&module=shvatka&cmd=cap&del='.($frows['n']).'"><font size=1 color=red>Исключить</font></center></td></tr>';
             }
             $res=$res."</table ><br><TABLE cellspacing=\"1\" class=\"borderwrap\" align=\"center\"><tr><th COLSPAN=2>Заявки в вашу команду.</th></tr>";
             $this->ipsclass->DB->query("select * from sh_recrut  WHERE kuda='".$id_team."'");
             while ($frows = $this->ipsclass->DB->fetch_row($fquery))
             {
                   if ($frows['otvet']!="Капитан вам отказал, отзовите свою заявку.")
                   {
                         $res=$res."<tr class=\"ipbtable\"><td class=\"row1\"><center><b>".$frows['kto']."</b></center></td>";
                         $res=$res.'<td class="row2"><center><a href="' . $this->ipsclass->base_url . '?act=module&module=shvatka&cmd=cap&yes='.($frows['n']).'"><font size=1 color=green>Принять</font></a>  <a href="' . $this->ipsclass->base_url . '?act=module&module=shvatka&cmd=cap&cnc='.($frows['n']).'"><font size=1 color=red>Отказать</font></a></center></td></tr>';
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
                           if (count($this->ipsclass->DB->query("select * from sh_comands WHERE dengi=1"))==0)
                           {$this->ipsclass->DB->query("update  sh_games set status='т' WHERE status='п'");}
                           header('Location:'.$this->ipsclass->base_url.'?act=module&module=shvatka&cmd=cap');
                        }
                        else
                        {
                            $res.="<br><div align='center' id='here' class=\"borderwrap\"><br>Сейчас идёт игра. Если вы поняли, что ваша команда не хочет<br>или не может продолжать игру, то нажмите кнопку<br>
                            <form action={$this->ipsclass->base_url} id='action' method='post'>
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

    /**
     * Управление заявками игрока
     * @todo Уязвимость - можно отменить чужую заявку, если перебирать числа
     * @todo надо добавить проверку на имя игрока для заяки с номером n
     * 
     * @todo Чепуха. Капитан команды А может подать заявку в команду Б! Поправить
     * 
     * @param string $ku n команды, в которую игрок желает подать заявку
     * @param string $cn n заявки, которую игрок хочет отменить
     */
    function nmem($ku,$cn)
    {
        $chisla=array('0','1','2','3','4','5','6','7','8','9');
        $res="";
        if (($ku!="")and(!$this->parsdig($ku)))
        {
           $res="Заявка не прошла, в запросе использованы недопустимые символы<br>";
           $ku="";
        }
        if (($cn!="")and(!$this->parsdig($cn)))
        {
           $res="Отмена заявки не прошла, в запросе использованы недопустимые символы<br>";
           $cn="";
        }
        if ($this->ipsclass->member['id']!="")
        {
           if ($ku!="")
           {
               if ((count($this->ipsclass->DB->query("select * from sh_recrut WHERE (kuda='".$ku."')and(kto='".($this->ipsclass->member['name'])."')"))==0)and(count($this->ipsclass->DB->query("select * from sh_comands WHERE n=".$ku))!=0))
               {                   	  if (count($this->ipsclass->DB->query("select * from sh_recrut"))==0)
                  {
                     $this->ipsclass->DB->query("ALTER TABLE sh_recrut PACK_KEYS =0 CHECKSUM =0 DELAY_KEY_WRITE =0 AUTO_INCREMENT =1");                   	  }                   	  $this->ipsclass->DB->query("INSERT INTO sh_recrut(kto, kuda) values('".$this->ipsclass->member['name']."', '".$ku."')");
               }
               else
               {$res=$res."Либо вы уже подали заявку в эту команду, либо такой команды не существует!<br>";}
           }
           if ($cn!="")
           {
               if ((count($this->ipsclass->DB->query("select * from sh_recrut WHERE n=".$cn))!=0))
               {
                  $this->ipsclass->DB->query("DELETE FROM sh_recrut WHERE n=".$cn);
               }
               else
               {$res=$res."Такой заявки нет!<br>";}
           }


           $res=$res.'Куда хотите подать заявку, '.$this->ipsclass->member['name'].' ?<br><Form action="'.$this->ipsclass->base_url.'" method="post">
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
           if (count($this->ipsclass->DB->query("select * from sh_recrut WHERE kto='".$this->ipsclass->member['name']."'"))!=0)
           {
               $res=$res."<TABLE cellspacing=\"1\" style='' class=\"borderwrap\"><th COLSPAN=3><b>Вы подали заявки в следующие команды:</b></th>";
               while ($frows = $this->ipsclass->DB->fetch_row($fquery))
               {
                  $res=$res.'<tr class="ipbtable"><td class="row2"><b>'.$cm_array[$frows['kuda']].'</b></td><td class="row2"><center><a href="'.$this->ipsclass->base_url.'?act=module&module=shvatka&cmd=nmem&cnc='.($frows['n']).'"><font size=1 color=red>Отозвать заявку</font></a></td><td class="row2" style={font-style:italic}><blink><font size=1>   '.($frows['otvet']).'</font></blink></td></tr>';
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
    
    /**
     * Процесс игры
     * @param type $k  ключ поля
     * @param type $bk ключ мозга
     */
    function do_game($k,$bk)
    {   
        $playerHelper = new Helper\Player;
        $gameHelper = new Helper\Game;
        $teamHelper = new Helper\Team;
        $logHelper = new Helper\Log;
        
        try {
            $player = $playerHelper->loadByMemberId($this->ipsclass->member['id']);
        } catch (\App\Engine\Exception $e) {
            return $this->ipsclass->render('module/shvatka/bad-player.twig');
        }

        $game = $gameHelper->load();
        if (empty($game)) {
            return $this->ipsclass->render('module/shvatka/no-game.twig');
        }

        $gameTime = strtotime($game['dt_g']);

        /* Смотрим запущена ли игра (время) */
        if ($gameTime > strtotime("now")) {
            $timeRest = $gameTime - strtotime("now");
            return $this->ipsclass->render(
                'module/shvatka/pre-game.twig', [
                    'game' => $game,
                    'timeRestFormatted' => strftime('%d.%m.%y в %H:%M', $gameTime),
                    'timeRestForHuman' => $this->sectostr($timeRest),
                    'teams' => $teamHelper->loadRegedList(),
                    'players' => $playerHelper->loadRegedList()
                ]
            );
        }

        $gameId = $game['n'];
        $isPlayerReged = $player['ch_dengi'];
        $playerName = $player['nick'];
        $teamName = $player['komanda'];

        try {
            $team = $teamHelper->loadByName($teamName);
            $isTeamReged = $team['dengi'];
            
            if (empty($isTeamReged) or empty($isPlayerReged)) {
                return $this->ipsclass->render('module/shvatka/no-access.twig');
            }
        } catch (\App\Engine\Exception $e) {
            return $this->ipsclass->render('module/shvatka/no-access.twig');
        }
        
        $levelNum = $team['uroven'];
        $tipNum = $team['podskazka'];
        $levelStartTime = $team['dt_ur'];

        if ($levelNum == 0) {
            $tipNum = 0;
            $levelNum = 1;
            $levelStartTime = date('Y-m-d H:i:s');
            $teamHelper->updateGameStatus($teamName, $levelStartTime);
        }
        
        // загружаем уровень, важно tipNum == 0
        $level = new Helper\Unit($levelNum, 0);
        if (!$level->isExists()) {
            // такого уровня или подсказки нет ...
            // TODO шо делать, шо делать
            return $this->ipsclass->render('module/shvatka/finish-game.twig', [
                'game' => $game,
                'duration' => $this->sectostr(strtotime($levelStartTime) - $gameTime) 
            ]);
        }
        
        $brainKey = $level['b_keyw'];
        $fieldKey = $level['keyw'];
        
        if ( $k != "" || $bk != "") {
            $logHelper->write($teamName, $fieldKey . $brainKey, $playerName);
        }

        // проверяем ключи, сама логика игры
        if ($k == $fieldKey && $bk == $brainKey) {
            
            $levelNum++;
            $tipNum = 0;
            $levelStartTime = date('Y-m-d H:i:s');
            
            $logHelper->write($teamName, $fieldKey . $brainKey, $playerName);
            
            $level = new Helper\Unit($levelNum, 0);
            if ($level->isExists()) {
                // следующий уровень
                $teamHelper->updateGameStatus($teamName, $levelStartTime, $levelNum, $tipNum);
                
            } else {
                // игра пройдена, стираем данные о положении данной команды
                $teamHelper->updateGameHistory($teamName, $gameId);
                $players = $playerHelper->loadListByTeamName($teamName);
                foreach ($players as $player) {
                    if ($player['ch_dengi'] == 1) {
                        $playerHelper->updateGameHistory($player['n'], $gameId);
                    }
                }

                $gameHelper->updateWinners($teamName);
                
                $activeTeams = $teamHelper->loadRegedList();
                if (count($activeTeams) == 0) {
                    // перевести игру в разряд прошедших
                    $gameHelper->end();
                }

                return $this->ipsclass->render('module/shvatka/finish-game.twig', [
                    'duration' => $this->sectostr(strtotime($levelStartTime) - $gameTime) 
                ]);
            }
        }
        
        // посчитаем общую сумму ожидания след. подсказки
        $tips = $level->loadTipsByLevel($tipNum);
        $delay = $level['p_time'];
        $now = strtotime("now");
        foreach ($tips as $i => $tip) {
            if ($tipNum <= $i) {
                // если у команды, допустим, 1 подсказка, а это 0-ой индекс, то идем дальше
                if ($now >= (60 * $delay + strtotime($levelStartTime))) {
                    $tipNum++;
                    $teamHelper->updateGameStatus($teamName, $levelStartTime, $levelNum, $tipNum);
                } else {
                    // если до следующей подсказки надо еще подождать, 
                    // то удалим ее из списка на отображение
                    unset($tips[$i]);
                }
            }
            $delay += $tip['p_time'];
        }
        
        return $this->ipsclass->render('module/shvatka/game.twig', [
            'game' => $game,
            'brainKey' => $bk,
            'level' => $level,
            'levelNum' => $levelNum,
            'tipNum' => $tipNum,
            'tips' => $tips,
            'duration' => $this->sectostr(strtotime("now") - strtotime($levelStartTime))
        ]);
    }
    
    /**
     * Блок сообщения от организаторов
     * @return string
     */
    protected function _showMessage()
    {
        $adm_msg = '';
        $msg_q = $this->ipsclass->DB->query("select * from sh_admin_msg WHERE ((komand='все')|(FIND_IN_SET('".$komanda."',komand)!=0))&((endtime>='".time()."')&(FIND_IN_SET('".$komanda."',readed)=0))");
        while ($frows = $this->ipsclass->DB->fetch_row($msg_q)) {
            if ($frows['komand'] == 'все') {
                $color='#EE634F'; $komu='<i><u>всех</u></i> команд';} else {$color='#67ED50'; $komu='команды <i><u>'.$komanda.'</u></i>';
            }

            if ($frows['endtime'] >= time()) {
                $adm_msg .= '<table style="border: 1px solid black;width:100%;background:'.$color.';"><tr><th>Сообщение от организатора '.$frows['autor'].' для '.$komu.'.</th></tr><tr class="ipbtable"><td class="row1">'.$frows['msg'].' <div align="right"><form  "' . $this->ipsclass->base_url .'" method="get">
<input name="act" type="hidden" value="module">
<input name="module" type="hidden" value="utils">
<input name="hsh" type="hidden" value="'.$frows['hash'].'"><input style="font: 8pt tahoma; padding: 0pt;"type="submit" value="Прочитал"></form></div></td></tr></table>';
            }
        }
            
        if ($adm_msg != '') {
            $adm_msg = '<div id="adm_msg_div" style="left:35%;top:35%;width:30%;height:auto;overflow: auto;position:absolute;" onClick="javascript:this.style.display=\'none\'">'.$adm_msg.'</div>';
        }
        
        return $adm_msg;
    }
}