<?php
namespace App\Engine;

function unch($string)
{
    $trans_tbl = get_html_translation_table(HTML_ENTITIES);
    $trans_tbl = array_flip($trans_tbl);
    return strtr($string, $trans_tbl);
}

class Edit extends Base
{
    function run_module()
    {
        parent::run_module();
        if ($this->_isOrganizator()) {
            $html=$html. "
            <div id=\"userlinks\">
            <p class=\"home\"><b>Редактирование сценария:</b></p>
            <p>
            <a href='{$this->ipsclass->base_url}act=module&module=reps&cmd=scn'>Редактор сценария</a>
            </p>
            </div>
            <br>
            ";
            switch( $this->ipsclass->input['cm'] ) {
                case '1':
                      $this->edit();
                      break;
                case '2':
                      $this->del();
                      break;
                case '3':
                      $this->add();
                      break;
                case '4':
                      $this->files();
                      break;
                default:
                      break;
            }

            $html=$html.'<font size=2>'.$this->result.'</font>';
            $this->ipsclass->print->add_output( $html );
            $this->nav[] = "<a href='{$this->ipsclass->base_url}act=module&module=shvatka'>СХВАТКА</a>";
            return $this->ipsclass->print->do_output(array('OVERRIDE' => 0, 'TITLE' => 'Редактирование сценария', 'NAV' => $this->nav));
        }
        else
        {
            $html=$html."Вы не администратор";
            $this->ipsclass->print->add_output( $html );
            return $this->ipsclass->print->do_output(array('OVERRIDE' => 0, 'TITLE' => 'Вы не администратор'));
        }
        //exit();
    }

      function edit()
      {
                 $res="";
                 $lev = $this->ipsclass->input['lev'];
                 $npod = $this->ipsclass->input['npod'];
                 if (($this->ipsclass->input['execs']=="")and($this->ipsclass->DB->get_num_rows($this->ipsclass->DB->query("select * from sh_game where (uroven=$lev)and(n_podskazki=$npod) "))))
                 {
                    $res='Редактирование сценария. Уровень '.$lev.', подсказка '.$npod;
                    $frows = $this->ipsclass->DB->fetch_row($fquery);
                    $res.='<br>
<script type="text/javascript">
function frmt(d)
{
        var txtarea = document.edi.textl;        theSelection = document.selection.createRange().text; // Get text selection
		if (theSelection) {
			// Add tags around selection
			document.selection.createRange().text = \'<\'+d+\'>\'+theSelection + \'</\'+d+\'>\';
			txtarea.focus();
			theSelection = \'\';
			return;
		}}
function url(p)
{
        var txtarea = document.edi.textl;
        theSelection = document.selection.createRange().text;
        if (p=="img")
        {
            if (theSelection) {document.selection.createRange().text = \'<img src="\'+theSelection+\'"></img>\';}
        	return;
        }
         // Get text selection
		if (theSelection) {
			// Add tags around selection
            d=window.prompt("Введите ссылку:","http://");
			if (d) {			  if (p=="a") {document.selection.createRange().text = \'<a href="\'+d+\'">\' +theSelection +\'</a>\';}
			  txtarea.focus();
			  theSelection = \'\';
			}
			return;
		}
}
</script>
<form action="' . $this->ipsclass->base_url . '" method="POST" ENCTYPE=multipart/form-data name="edi">
<input name="act" type="hidden" value="module">
<input name="module" type="hidden" value="edit">
<input name="cm" type="hidden" value="1">
<input name="lev" type="hidden" value="'.$lev.'">
<input name="npod" type="hidden" value="'.$npod.'">
<input name="execs" type="hidden" value="1">
Время, от начала уровня, через которое выскочит <b>';
if ($npod==0)
{$res.='первая';}
else
{$res.='следующая за этой';}
$res.='</b> подсказка:<input name="ptm" type="text" SIZE="2" value="'.($frows['p_time']).'"> мин.';
if (!$npod==0)
{$res.=' Если это последняя подсказка, то обязательно поставьте 0.';}
$res.='<br>Текст (можно использовать html) уровня или подсказки:<br>
<div style={margin-left:5px}><table cellpadding=\'0\' cellspacing=\'0\' class="rtebuttonbar1">
	   <tr>
	    <td><img class="rteVertSep" src="style_images/1/folder_rte_images/rte_dots.gif" width="3" height="15" border="0" alt=""></td>

	    <td><div id="do_bold"><img class="rteimage" src="style_images/1/folder_rte_images/bold.gif" width="25" height="24" alt="Bold" title="Жырным (Выделите текст)" onclick="javascript:frmt(\'b\')"></div></td>
	    <td><div id="do_italic"><img class="rteimage" src="style_images/1/folder_rte_images/italic.gif" width="25" height="24" alt="Italic" title="Курсивом (Выделите текст)" onclick="javascript:frmt(\'i\')"></div></td>
	    <td><div id="do_underline"><img class="rteimage" src="style_images/1/folder_rte_images/underline.gif" width="25" height="24" alt="Underline" title="Подчеркнутым (Выделите текст)" onclick="javascript:frmt(\'u\')"></div></td>
	    <td><div id="do_strikethrough"><img class="rteimage" src="style_images/1/folder_rte_images/strike.gif" width="25" height="24" alt="Strikethrough" title="Зачеркнутым (Выделите текст)" onclick="javascript:frmt(\'s\')"></div></td>
	    <!--SEP-->
	    <td><img class="rteVertSep" src="style_images/1/folder_rte_images/blackdot.gif" width="1" height="20" border="0" alt=""></td>
	    <!--/SEP-->
	    <td><div id="do_centre"><img class="rteimage" src="style_images/1/folder_rte_images/centre.gif" width="25" height="24" alt="centre" title="По центру (Выделите текст)" onclick="javascript:frmt(\'center\')"></div></td>

	    <!--SEP-->
	    <td><img class="rteVertSep" src="style_images/1/folder_rte_images/blackdot.gif" width="1" height="20" border="0" alt=""></td>
	    <!--/SEP-->
	    <td><div><img class="rteimage" src="style_images/1/folder_rte_images/hyperlink.gif" width="25" height="24" alt="Insert Link" title="Вставка ссылки (Выделите текст)" onclick="javascript:url(\'a\')"></div></td>
	    <td><div><img class="rteimage" src="style_images/1/folder_rte_images/image.gif" width="25" height="24" alt="Insert Image" title="Вставка картинки (Выделите ссылку на картинку)" onclick="javascript:url(\'img\')"></div></td>
	   </tr>
	   </table></div>
<textarea name="textl" rows=30 cols=100 wrap="virtual">'.$frows['text'].'</textarea><br>';
if ($npod==0)
{$res.='Ключ уровня: <input name="keyw" type="text" SIZE="30" value="'.$frows['keyw'].'">
&nbsp;Мозговой ключ уровня: <input name="b_keyw" type="text" SIZE="30" value="'.$frows['b_keyw'].'"><br>';}
$res.='<input type="submit" value="Подтвердить изменения">
</form><br>
<form action="' . $this->ipsclass->base_url . '" ENCTYPE=multipart/form-data method=\'post\'>
<input name="act" type="hidden" value="module">
<input name="module" type="hidden" value="edit">
<input name="cm" type="hidden" value="4">
<input name="lev" type="hidden" value="'.$lev.'">
<input name="from" type="hidden" value="1">
<input name="npod" type="hidden" value="'.$npod.'">
<input type="hidden" name="MAX_FILE_SIZE" value="100000000">
<input type="file" name="fi">
<input type="submit" name="dou" value="Закачать файл"><br>
</form>
';
           	  	 $this->ipsclass->DB->query("select * from sh_games WHERE status='п'");
                 $frows = $this->ipsclass->DB->fetch_row($fquery);
                 if ($d=@opendir("upload/gam".$frows['n']."/l".$this->ipsclass->input['lev']."p".$this->ipsclass->input['npod']))
                  { // открываем каталог
                   $res.='Файлы прикрепленные к уровню (подсказке):<br>';
                   $glfiles=array();
                   // Перебираем все файлы
                   require ROOT_PATH. "conf_global.php";
                   while(($glfname=@readdir($d))!==false)
                   {
                   if (($glfname!=".."))
                   if ((!is_dir($glfname))and($glfname!="index.html"))
                   	  {                   	  	$res.='<a href="'.$INFO['board_url'].'/upload/gam'.$frows['n'].'/l'.$this->ipsclass->input['lev'].'p'.$this->ipsclass->input['npod']."/".$glfname."\" target=blank>".$INFO['board_url']."/upload/gam".$frows['n'].'/l'.$this->ipsclass->input['lev'].'p'.$this->ipsclass->input['npod'].'/'.$glfname.'</a>';
                   	  	$res.='<form action="' . $this->ipsclass->base_url . '" ENCTYPE=multipart/form-data >
<input name="act" type="hidden" value="module">
<input name="module" type="hidden" value="edit">
<input name="cm" type="hidden" value="4">
<input name="lev" type="hidden" value="'.$lev.'">
<input name="npod" type="hidden" value="'.$npod.'">
<input name="from" type="hidden" value="1">
<input name="del" type="hidden" value="1">
<input name="fname" type="hidden" value="'.$glfname.'">
<input type="submit" value="Удалить файл"></form><br>';
                   	  }
                   }
                      @closedir($d);
                  }
                 }
                 else
                 {
                    $res.='Нет такого уровня или подсказки';
                 }
                 if  ($this->ipsclass->input['execs']=='1')
                 {
                         $res='Отредактировано';
                         $ptm = $this->ipsclass->input['ptm'];
                         if ($ptm=="")
                         {$ptm=0;}
                         $text = $this->ipsclass->input['textl'];
                         if ($npod==0)
                         {
                              $b_keyw = $this->ipsclass->input['b_keyw'];
                              $keyw = $this->ipsclass->input['keyw'];
                              $this->ipsclass->DB->query( "update sh_game set p_time=$ptm, keyw='".$keyw."', b_keyw='".$b_keyw."', text='".(unch($text))."'  WHERE (uroven=$lev)and(n_podskazki=$npod)");
                         }
                         else
                         {
                              $this->ipsclass->DB->query("update sh_game set p_time=$ptm, text='".(unch($text))."' WHERE (uroven=$lev)and(n_podskazki=$npod)");
                         }
                         $res.='<script >window.navigate("' . $this->ipsclass->base_url . '?act=module&module=reps&cmd=scn");</script>';

                 }
                 $this->result=$res;
      }
      function del()
      {
                 $res="";
                 $lev = $this->ipsclass->input['lev'];
                 $npod = $this->ipsclass->input['npod'];
                 if (($this->ipsclass->input['execs']=="")and($this->ipsclass->DB->get_num_rows($this->ipsclass->DB->query("select * from sh_game where (uroven=$lev)and(n_podskazki=$npod) "))))
                 {
                         $res='Удаление из сценария. Уровень '.$lev.', подсказка '.$npod;
                         $res.='<br>
<form action="' . $this->ipsclass->base_url . '">
<input name="act" type="hidden" value="module">
<input name="module" type="hidden" value="edit">
<input name="cm" type="hidden" value="2">
<input name="lev" type="hidden" value="'.$lev.'">
<input name="npod" type="hidden" value="'.$npod.'">
<input name="execs" type="hidden" value="1">
<input type="submit" value="Подтвердить удаление">
</form>';
                 }
                 if  ($this->ipsclass->input['execs']=='1')
                 {
                         $this->ipsclass->DB->query("DELETE FROM sh_game WHERE (uroven=$lev)and(n_podskazki=$npod)");
                         $res='<b>Удалено.</b><br>Если это была последняя подсказка на уровне, то не забудьте поставить в предыдущей подсказке нулевое время.<br><a href="' . $this->ipsclass->base_url . '?act=module&module=reps&cmd=scn">Вернуться к сценарию.</a>';
                 }
                 $this->result=$res;
      }
      function add()
      {
                 $res="";
                 If  (($this->ipsclass->input['lev']=="")or($this->ipsclass->input['npod']==""))
                 {$res.='Не задан номер уровня или подсказки';}
                 else
                 {
                     $lev = $this->ipsclass->input['lev'];
                     $npod = $this->ipsclass->input['npod'];
                     if (($this->ipsclass->input['execs']=="")and(!$this->ipsclass->DB->get_num_rows($this->ipsclass->DB->query("select * from sh_game where (uroven=$lev)and(n_podskazki=$npod) "))))
                     {
                        $res='Добавление к сценарию. Уровень '.$lev.', подсказка '.$npod;
                        $res.='<br>
<script type="text/javascript">
function frmt(d)
{
        var txtarea = document.edi.textl;        theSelection = document.selection.createRange().text; // Get text selection
		if (theSelection) {
			// Add tags around selection
			document.selection.createRange().text = \'<\'+d+\'>\'+theSelection + \'</\'+d+\'>\';
			txtarea.focus();
			theSelection = \'\';
			return;
		}}
function url(p)
{
        var txtarea = document.edi.textl;
        theSelection = document.selection.createRange().text;
        if (p=="img")
        {
            if (theSelection) {document.selection.createRange().text = \'<img src="\'+theSelection+\'"></img>\';}
        	return;
        }
         // Get text selection
		if (theSelection) {
			// Add tags around selection
            d=window.prompt("Введите ссылку:","http://");
			if (d) {			  if (p=="a") {document.selection.createRange().text = \'<a href="\'+d+\'">\' +theSelection +\'</a>\';}
			  txtarea.focus();
			  theSelection = \'\';
			}
			return;
		}
}
</script><br>
<form name="edi" action="' . $this->ipsclass->base_url . '" method="POST" ENCTYPE=multipart/form-data>
<input name="act" type="hidden" value="module">
<input name="module" type="hidden" value="edit">
<input name="cm" type="hidden" value="3">
<input name="lev" type="hidden" value="'.$lev.'">
<input name="npod" type="hidden" value="'.$npod.'">
<input name="execs" type="hidden" value="1">
Время, от начала уровня, через которое выскочит <b>';
				    	if ($npod==0)
				    	{$res.='первая';}
				    	else
			    		{$res.='следующая за этой';}
				    	$res.='</b> подсказка:<input name="ptm" type="text" SIZE="2" value=""> мин.';
			    		if (!$npod==0)
			    		{$res.=' Если это последняя подсказка, то обязательно поставьте 0.';}
			    		$res.='<br>Текст (можно использовать html) уровня или подсказки:<br><div style={margin-left:5px}><table cellpadding=\'0\' cellspacing=\'0\' class="rtebuttonbar1">
	   <tr>
	    <td><img class="rteVertSep" src="style_images/1/folder_rte_images/rte_dots.gif" width="3" height="15" border="0" alt=""></td>

	    <td><div id="do_bold"><img class="rteimage" src="style_images/1/folder_rte_images/bold.gif" width="25" height="24" alt="Bold" title="Жырным (Выделите текст)" onclick="javascript:frmt(\'b\')"></div></td>
	    <td><div id="do_italic"><img class="rteimage" src="style_images/1/folder_rte_images/italic.gif" width="25" height="24" alt="Italic" title="Курсивом (Выделите текст)" onclick="javascript:frmt(\'i\')"></div></td>
	    <td><div id="do_underline"><img class="rteimage" src="style_images/1/folder_rte_images/underline.gif" width="25" height="24" alt="Underline" title="Подчеркнутым (Выделите текст)" onclick="javascript:frmt(\'u\')"></div></td>
	    <td><div id="do_strikethrough"><img class="rteimage" src="style_images/1/folder_rte_images/strike.gif" width="25" height="24" alt="Strikethrough" title="Зачеркнутым (Выделите текст)" onclick="javascript:frmt(\'s\')"></div></td>
	    <!--SEP-->
	    <td><img class="rteVertSep" src="style_images/1/folder_rte_images/blackdot.gif" width="1" height="20" border="0" alt=""></td>
	    <!--/SEP-->
	    <td><div id="do_centre"><img class="rteimage" src="style_images/1/folder_rte_images/centre.gif" width="25" height="24" alt="centre" title="По центру (Выделите текст)" onclick="javascript:frmt(\'center\')"></div></td>

	    <!--SEP-->
	    <td><img class="rteVertSep" src="style_images/1/folder_rte_images/blackdot.gif" width="1" height="20" border="0" alt=""></td>
	    <!--/SEP-->
	    <td><div><img class="rteimage" src="style_images/1/folder_rte_images/hyperlink.gif" width="25" height="24" alt="Insert Link" title="Вставка ссылки (Выделите текст)" onclick="javascript:url(\'a\')"></div></td>
	    <td><div><img class="rteimage" src="style_images/1/folder_rte_images/image.gif" width="25" height="24" alt="Insert Image" title="Вставка картинки (Выделите ссылку на картинку)" onclick="javascript:url(\'img\')"></div></td>
	   </tr>
	   </table></div><textarea name="textl" rows=30 cols=100 wrap="virtual"></textarea><br>';
				    	if ($npod==0)
				    	{$res.='Ключ уровня: <input name="keyw" type="text" SIZE="30" value="">&nbsp;';
				    	 $res.='Мозговой ключ уровня: <input name="b_keyw" type="text" SIZE="30" value=""><br>';}
			    		$res.='<input type="submit" value="Добавить к сценарию">
			    		</form>';
			    		$res.='<br>
<form action="' . $this->ipsclass->base_url . '" ENCTYPE=multipart/form-data method=\'post\'>
<input name="act" type="hidden" value="module">
<input name="module" type="hidden" value="edit">
<input name="cm" type="hidden" value="4">
<input name="lev" type="hidden" value="'.$lev.'">
<input name="from" type="hidden" value="3">
<input name="npod" type="hidden" value="'.$npod.'">
<input type="hidden" name="MAX_FILE_SIZE" value="100000000">
<input type="file" name="fi">
<input type="submit" name="dou" value="Закачать файл"><br>
</form>
';
           	  	        $this->ipsclass->DB->query("select * from sh_games WHERE status='п'");
                        $frows = $this->ipsclass->DB->fetch_row($fquery);
                        if ($d=@opendir("upload/gam".$frows['n']."/l".$this->ipsclass->input['lev']."p".$this->ipsclass->input['npod']))
                        { // открываем каталог
                           $res.='Файлы прикрепленные к уровню (подсказке):<br>';
                           $glfiles=array();
                           // Перебираем все файлы
                           require ROOT_PATH. "conf_global.php";
                           while(($glfname=@readdir($d))!==false)
                           {
                   	         if ((!is_dir($glfname))and(($glfname!="index.html")and($glfname!="..")))
                   	         {
                   	  	        $res.='<a href="'.$INFO['board_url'].'/upload/gam'.$frows['n'].'/l'.$this->ipsclass->input['lev'].'p'.$this->ipsclass->input['npod']."/".$glfname."\" target=blank>".$INFO['board_url']."/upload/gam".$frows['n'].'/l'.$this->ipsclass->input['lev'].'p'.$this->ipsclass->input['npod'].'/'.$glfname.'</a>';
                   	  	        $res.='<form action="' . $this->ipsclass->base_url . '" ENCTYPE=multipart/form-data >
<input name="act" type="hidden" value="module">
<input name="module" type="hidden" value="edit">
<input name="cm" type="hidden" value="4">
<input name="lev" type="hidden" value="'.$lev.'">
<input name="npod" type="hidden" value="'.$npod.'">
<input name="from" type="hidden" value="3">
<input name="del" type="hidden" value="1">
<input name="fname" type="hidden" value="'.$glfname.'">
<input type="submit" value="Удалить файл"></form><br>';
                   	         }
                           }
                           @closedir($d);
                        }
                     }
                     else
                     {
                        $res.='Такой уровень или подсказка уже есть.';
                     }
                     if  ($this->ipsclass->input['execs']=='1')
                     {

                             $ptm = $this->ipsclass->input['ptm'];
                             if ($ptm=="")
                             {$ptm=0;}
                             $text = $this->ipsclass->input['textl'];
                             if ($npod==0)
                             {
                                  $b_keyw = $this->ipsclass->input['b_keyw'];
                                  $keyw = $this->ipsclass->input['keyw'];
                                  $this->ipsclass->DB->query( "INSERT INTO sh_game  (p_time, keyw, b_keyw, text, uroven, n_podskazki) VALUES ($ptm, '".$keyw."', '".$b_keyw."', '".(unch($text))."', $lev, $npod)");
                             }
                             else
                             {
                              $this->ipsclass->DB->query("INSERT INTO sh_game  (p_time, text, uroven, n_podskazki) VALUES ($ptm, '".(unch($text))."', $lev, $npod)");
                             }
                             $res='Добавлено';
                             $res.='<script >window.navigate("' . $this->ipsclass->base_url . '?act=module&module=reps&cmd=scn");</script>';

			    	 }
			    }
      			$this->result=$res;
      }

}


?>