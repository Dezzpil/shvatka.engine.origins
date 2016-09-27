<?php
namespace Shvatka;

class Notelog extends Base
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
                        default:
                             $this->showlog("");
                             break;
                }

            $html=$html.'<font size=2>'.$this->result.'</font>';
            $this->ipsclass->print->add_output( $html );
            $this->nav[] = "<a href='{$this->ipsclass->base_url}act=module&module=shvatka'>СХВАТКА</a>";
            $this->ipsclass->print->do_output(array('OVERRIDE' => 0, 'TITLE' => 'Логи начисления очков', 'NAV' => $this->nav));

                //exit();
        }

        //------------------------------------------
        // do_something
        //
        // Test sub, show if admin or not..
        //
        //------------------------------------------

      function showlog($id)
      {
                 $sort_arr=array(1=>"komu", 2=>"komu", 3=>"skolko", 4=>"skolko", 5=>"kogda", 6=>"kogda", 7=>"ktopoctavil", 8=>"ktopoctavil" );
                 $tabindex=array(1=>"Игрок/Команда", 2=>"Очков", 3=>"Когда начисленно", 4=>"Кем начисленно");
                 $res="";
                 $sort=5;
                 $pg=0;
                 $ipp=30;
                 if (isset($this->ipsclass->input['pg'])) $pg=htmlspecialchars($this->ipsclass->input['pg']);
                 if (isset($this->ipsclass->input['sort'])and(in_array($this->ipsclass->input['sort'],array('1','2','3','4','5','6','7','8'))))$sort=$this->ipsclass->input['sort'];
                 $tmpst="select * from sh_log_ochkov where komand='1' order by ".$sort_arr[$sort];
                 if (($sort%2)==0) $tmpst=$tmpst." DESC";
                 $this->ipsclass->DB->query($tmpst);
                 $pcountk=$this->ipsclass->DB->get_num_rows();

                 $res=$res.'<div class="borderwrap"><div class="maintitle" align="center">Лог выставления очков</div><br />';

                 $res=$res."<table cellspacing=\"1\" class=\"borderwrap\" align=\"center\">
                 <tr>";
                 for ($i = 1; $i <= 4; $i++)
                 {
                 	$res=$res."<th align=\"center\">";
                 	switch( $i )
                 	{
                        case 1:
                            switch( $sort )
                            {
                            case 1:$res=$res."<a href='{$this->ipsclass->base_url}act=module&module=notelog&sort=2' title=\"Сортировать\" style=\"color:#A60000\">»".$tabindex[$i]."</a></th>";break;
                            case 2:$res=$res."<a href='{$this->ipsclass->base_url}act=module&module=notelog&sort=1' title=\"Сортировать\" style=\"color:#A60000\">«".$tabindex[$i]."</a></th>";break;
                            default:$res=$res."<a href='{$this->ipsclass->base_url}act=module&module=notelog&sort=1' title=\"Сортировать\">".$tabindex[$i]."</a></th>";break;
                            }
                            break;
                        case 2:
                            switch( $sort )
                            {
                            case 3:$res=$res."<a href='{$this->ipsclass->base_url}act=module&module=notelog&sort=4' title=\"Сортировать\" style=\"color:#A60000\">»".$tabindex[$i]."</a></th>";break;
                            case 4:$res=$res."<a href='{$this->ipsclass->base_url}act=module&module=notelog&sort=3' title=\"Сортировать\" style=\"color:#A60000\">«".$tabindex[$i]."</a></th>";break;
                            default:$res=$res."<a href='{$this->ipsclass->base_url}act=module&module=notelog&sort=3' title=\"Сортировать\">".$tabindex[$i]."</a></th>";break;
                            }
                            break;
                        case 3:
                            switch( $sort )
                            {
                            case 5:$res=$res."<a href='{$this->ipsclass->base_url}act=module&module=notelog&sort=6' title=\"Сортировать\" style=\"color:#A60000\">»".$tabindex[$i]."</a></th>";break;
                            case 6:$res=$res."<a href='{$this->ipsclass->base_url}act=module&module=notelog&sort=5' title=\"Сортировать\" style=\"color:#A60000\">«".$tabindex[$i]."</a></th>";break;
                            default:$res=$res."<a href='{$this->ipsclass->base_url}act=module&module=notelog&sort=5' title=\"Сортировать\">".$tabindex[$i]."</a></th>";break;
                            }
                            break;
                        case 4:
                            switch( $sort )
                            {
                            case 7:$res=$res."<a href='{$this->ipsclass->base_url}act=module&module=notelog&sort=8' title=\"Сортировать\" style=\"color:#A60000\">»".$tabindex[$i]."</a></th>";break;
                            case 8:$res=$res."<a href='{$this->ipsclass->base_url}act=module&module=notelog&sort=7' title=\"Сортировать\" style=\"color:#A60000\">«".$tabindex[$i]."</a></th>";break;
                            default:$res=$res."<a href='{$this->ipsclass->base_url}act=module&module=notelog&sort=7' title=\"Сортировать\">".$tabindex[$i]."</a></th>";break;
                            }
                            break;
                    }
                 }
                 $res=$res.'<tr><td colspan=4 align="center">По командам</td></tr>';
                 $i=0;
                 while ($frows = $this->ipsclass->DB->fetch_row($fquery))
                 {
                 	if (($i>=$pg*$ipp)and($i<($ipp*($pg+1))))
                 	$res=$res."<tr class='ipbtable'><td class=\"row2\"><b>".$frows[$sort_arr[1]]."</b></td><td class=\"row2\" align=\"center\" >".$frows[$sort_arr[3]]."</td><td class=\"row2\">".$frows[$sort_arr[5]]."</td><td class=\"row2\"><b>".$frows[$sort_arr[7]]."</b></td></tr>";
                 	$i++;
                 }

                 $tmpst="select * from sh_log_ochkov where komand='0' order by ".$sort_arr[$sort];
                 if (($sort%2)==0) $tmpst=$tmpst." DESC";
                 $this->ipsclass->DB->query($tmpst);
                 $pcounti=$this->ipsclass->DB->get_num_rows();
                 $res=$res.'<tr><td colspan=4 align="center">По игрокам</td></tr>';
                 $i=0;
                 while ($frows = $this->ipsclass->DB->fetch_row($fquery))
                 {
                 	if (($i>=$pg*$ipp)and($i<($ipp*($pg+1))))
                 	$res=$res."<tr class='ipbtable'><td class=\"row2\"><b>".$frows[$sort_arr[1]]."</b></td><td class=\"row2\" align=\"center\" >".$frows[$sort_arr[3]]."</td><td class=\"row2\">".$frows[$sort_arr[5]]."</td><td class=\"row2\"><b>".$frows[$sort_arr[7]]."</b></td></tr>";
                 	$i++;
                 }
                 $res.='</table><br>';
                 $res=$res.'<div align="center">Страницы: ';
                 $pcount=floor(max($pcounti,$pcountk)/$ipp);
                 if ($pg>0) $res=$res."<a href=\"{$this->ipsclass->base_url}act=module&module=notelog&sort=$sort&pg=".($pg-1)."\"><<</a> ";
                 if ($pg<$pcount) $res=$res."<a href=\"{$this->ipsclass->base_url}act=module&module=notelog&sort=$sort&pg=".($pg+1)."\">>></a> ";
                 $res=$res.'</div><br>
                 <div align="center"><small>На одну страницу выводится по '.$ipp.' строк логов "по командам" и столько же строк "по игрокам".</small></div></div>';
                 $this->result=$res;
      }
}


?>