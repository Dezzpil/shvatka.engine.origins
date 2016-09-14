<?php
/*
+--------------------------------------------------------------------------
|   Invision Power Board v2.1.6
|   =============================================
|   by Matthew Mecham
|   (c) 2001 - 2005 Invision Power Services, Inc.
| http://www.ws.ea7.net & http://wowdesign.com.ua/
|   =============================================
| Web: http://www.ws.ea7.net & http://wowdesign.com.ua/
|   Time: Sun, 09 Oct 2005 11:51:26 GMT
|   Release: 1a47e28f0443faa9f14d0c0a45151e54
| Licence Info: http://www.ws.ea7.net & http://wowdesign.com.ua/
+---------------------------------------------------------------------------
|
|   > MODULE FILE (EXAMPLE)
|   > Module written by Matt Mecham
|   > Date started: Thu 14th April 2005 (17:59)
|
+--------------------------------------------------------------------------
*/

//=====================================
// Define class, this must be the same
// in all modules
//=====================================

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
		//=====================================
		// Do any set up here, like load lang
		// skin files, etc
		//=====================================

		$this->ipsclass->load_language('lang_boards');
        $this->ipsclass->load_template('skin_boards');

		//=====================================
		// Set up structure
		//=====================================
       if ((isset($this->ipsclass->input['hsh']))&($this->ipsclass->input['hsh']!=''))
       {
       	 $this->ipsclass->DB->query("select * from sh_igroki WHERE nick='".$this->ipsclass->member['name']."'");
       	 $frows = $this->ipsclass->DB->fetch_row($fquery);
       	 $komanda=$frows['komanda'];
       	 $this->ipsclass->DB->query("UPDATE sh_admin_msg SET readed=concat(readed,',','".$komanda."') WHERE (hash='".strip_tags($this->ipsclass->input['hsh'])."')&(((komand='все')|(FIND_IN_SET('".$komanda."',komand)!=0))&(FIND_IN_SET('".$komanda."',readed)=0))");
       }
       header('Location:./index.php?act=module&module=shvatka');
	   exit();
	}

}


?>