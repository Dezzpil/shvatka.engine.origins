<?php
namespace App\Engine;

class Stat extends Base
{
        function run_module()
        {
            parent::run_module();
            
               $html=$html. "
                <div id=\"userlinks\">
                <p class=\"home\"><b>Статистика СХВАТКИ:</b></p>
                <p>
                <a href='{$this->ipsclass->base_url}act=module&module=stat&cmd=cmds'>Kоманды</a> &middot;
                <a href='{$this->ipsclass->base_url}act=module&module=stat&cmd=sost'>Составы команд</a> &middot;
                <a href='{$this->ipsclass->base_url}act=module&module=stat&cmd=games'>Прошедшие игры</a> &middot;
                <a href='{$this->ipsclass->base_url}act=module&module=notelog'>Лог начисления очков</a>
                </p>
                </div>
                <br>
                ";
                switch( $this->ipsclass->input['cmd'] )
                {
                        case 'sost':
                             $this->sost($this->ipsclass->input['id']);
                             break;
                        case 'cmds':
                             $this->cmds(-1);
                             break;
                        case 'games':
                             $this->games();
                             break;
                        default:
                             $this->sost("");
                             break;
                }

            $html=$html.'<font size=2>'.$this->result.'</font>';
            $this->ipsclass->print->add_output( $html );
            $this->nav[] = "<a href='{$this->ipsclass->base_url}act=module&module=shvatka'>СХВАТКА</a>";
            return $this->ipsclass->print->do_output(array(OVERRIDE => 0, TITLE => 'Статистика СХВАТКИ', NAV => $this->nav));
        }

      function sost($id)
      {
                 $chisla=array('0','1','2','3','4','5','6','7','8','9');
                 $res="";
                 if (($id!="")and(!in_array(substr($id,0,1),$chisla)))
                 {
                      $id="";
                 }
                 $comd=array();
                 if (($id!="")and($this->ipsclass->DB->query("select * from sh_comands where n='".$id."' order by ochki DESC, nazvanie")))
                 {
                     $this->ipsclass->DB->query("select * from sh_comands where n='".$id."'");
                 }
                 else
                 {
                     $this->ipsclass->DB->query("select * from sh_comands order by ochki DESC, nazvanie");
                 }
                 while ($frows = $this->ipsclass->DB->fetch_row($fquery))
                 {
                   $comd[$frows['n']]=$frows['nazvanie'];
                 }
                 $res=$res.'<div class="borderwrap"><div class="maintitle" align="center">Составы команд </div><br>';
                 foreach ($comd as $n=>$naz)
                 {
                    $res = $res.'<table cellspacing="1" class="borderwrap" align="center"><tr><td align="center" colspan="4" class="maintitle">'.$naz.'</td></tr>';
                    $fq=$this->ipsclass->DB->query("select * from sh_igroki where komanda='".$naz."' order by ochki DESC, nick");
                    $res=$res.'<tr><th align="center">Ник</th><th align="center" >Статус</th><th align="center" >Очки</th><th align="center">Порядковый номер<br> сыграных игр</th></tr>';
                    while ($frows = $this->ipsclass->DB->fetch_row($fq))
                    {
                       $usID=$this->ipsclass->DB->fetch_row($this->ipsclass->DB->query("select * from members where name='".($frows['nick'])."'"));
                       $res=$res."<tr class='ipbtable'><td class=\"row1\"><b>".($frows['nick'])."</b></td><td class=\"row2\" align=\"center\">".($frows['status_in_cmd'])."</td><td class=\"row2\" align=\"center\">".($frows['ochki'])."</td><td class=\"row2\" align=\"center\">".($frows['games'])."</td></tr>";
                    }
                    $res=$res.'</TABLE><br>';
                 }
                 $res.='</div>';
                 $this->result=$res;
      }
      function cmds($cntcm)
      {

                 $res="";
                 $ms=1;
                 $this->ipsclass->DB->query("select * from sh_comands order by ochki DESC, nazvanie");
                 $res=$res.'<table cellspacing="1" class="borderwrap" align="center"><tr><td align="center" colspan="4" class="maintitle">Статистика по командам</td></tr>';
                 $res=$res."<tr class=\"ipbtable\"><th>Место</th><th width=\"50%\">Название</th><th align=\"center\" >Очки</th><th align=\"center\" >Игры</th></tr>";
                 while (($frows = $this->ipsclass->DB->fetch_row($fquery))and($ms!=$cntcm+1))
                 {
                     $res=$res."<tr class='ipbtable'><td align=\"center\" class=\"row2\">$ms</td><td width=\"50%\" class=\"row2\"><b><a  href=\"{$this->ipsclass->base_url}act=module&module=stat&cmd=sost&id=".$frows['n']."\">".($frows['nazvanie'])."</b></td><td align=\"center\" class=\"row1\">".$frows['ochki']."</td><td align=\"center\" class=\"row1\">".$frows['cmp_games']."</td></tr>";
                     $ms++;
                 }
                 $res=$res.'</TABLE><br>';
                 $this->result=$res;
      }
      function games()
      {
                 $res="<script type=\"text/javascript\">
			$(function(){




				// Dialog
				$('#dialog').dialog({
					autoOpen: false,
					width: 300,
					height: 600,
					modal: true,
					show: 'scale',
					hide: 'puff',
					buttons: {
						\"Ok\": function() {
							$(this).dialog(\"close\");
						}
					}
				});

				// Dialog Link
				$('#dialog_link').click(function(){
					$('#dialog').dialog('open');
					return false;
				});

				// Accordion
				$('#acc').accordion();

				//hover states on the static widgets
				//$('#dialog_link, ul#icons li').hover(
				//	function() { $(this).addClass('ui-state-hover'); },
				//	function() { $(this).removeClass('ui-state-hover'); }
				//);

			});
		</script>
		<!--//<style type=\"text/css\">
			#dialog_link {padding: .4em 1em .4em 20px;text-decoration: none;position: relative;}
			#dialog_link span.ui-icon {margin: 0 5px 0 0;position: absolute;left: .2em;top: 50%;margin-top: -8px;}
		</style>//-->
<div id=\"dialog\" title=\"\" align=\"center\"></div>
</div>
<a href=\"\" id=\"dialog_link\"></a> <br>
".'<script type="text/javascript">
                 function reload(id)
                 {
                   document.getElementById(\'dialog_link\').click();
                   $("#dialog").load(".{$this->ipsclass->base_url}?act=module&module=uchgame&gid="+id, function(){$("#dialog").accordion("destroy"); $("#dialog").accordion({ header: "h3", collapsible: true, active: false })});
                   $("#dialog").dialog("option", "title", "Участники игры номер "+id);
                 }

                 </script>';
                 $this->ipsclass->DB->query("select * from sh_games where status!='п'");
                 $res=$res.'<table cellspacing="1" class="borderwrap" align="center"><tr><td align="center" colspan="4" class="maintitle">Прошедшие игры</td></tr>';
                 $res=$res."<tr width=\"auto\" class=\"ipbtable\"><th align=\"center\">№</th><th width=\"50%\" align=\"center\" >Название</th><th align=\"center\" >Пъедестал</th></tr>";
                 while ($frows = $this->ipsclass->DB->fetch_row($fquery))
                 {
                     $res=$res."<tr class='ipbtable'><td align=\"center\" class=\"row2\">".$frows['n']."</td><td align=\"center\" class=\"row1\"><b>".$frows['g_name']."</b></td><td align=\"center\" class=\"row1\"><div onclick=\"javascript:reload(".$frows['n'].");\">".$frows['pedistal']."</div></td></tr>";
                 }
                 $res=$res.'</TABLE>';
                 $this->result=$res;
      }
}


?>