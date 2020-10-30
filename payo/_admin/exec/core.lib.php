<?php
///$site_config = new stdClass();

require_once("../include/config_admin.inc.php");
require_once("../../include/config_database.inc.php");
require_once("../../include/adodb/adodb.inc.php");

//---------------------------------------------------------------------------
class CDefaults {
	//---------------------------------------------------------------------------
	function CDefaults(){
		global $site_config;
		$this->dbtype      			=  $site_config->DBTYPE;
		if ($site_config->DBPORT != ""){
			$this->dbhost = "$site_config->DBHOST:$site_config->DBPORT";
		} else {
			$this->dbhost = $site_config->DBHOST;
		}
		$this->database    			=  $site_config->DATABASE;
		$this->dbuser      			=  $site_config->DBUSER;
		$this->dbuser_pass 			=  $site_config->DBUSER_PASS;

		$this->encode	         =  $site_config->encode;
	  	
		if ($this->dbtype == 'oci8'){
			$this->first_column_table = 1;
		} else {
			$this->first_column_table = 0;
		}


	}
	//---------------------------------------------------------------------------
	//---------------------------------------------------------------------------



}
$default = new CDefaults();
//---------------------------------------------------------------------------
//---------------------------------------------------------------------------
function connect() {
	global $default;
	$db = NewADOConnection($default->dbtype);
	if (preg_match("/oci8/i",$default->dbtype)){
		$db->connectSID = true;
	}

	$db->clientFlags |= 128;
	if (!$db->Connect($default->dbhost, $default->dbuser,$default->dbuser_pass,$default->database)) {
		return 0;
	} else {
	  $db->setCharset('latin1');		
	  return $db;
	}
}
//---------------------------------------------------------------------------
function timesql2std($tm) {
	if ($tm != ""){
                $tm = substr($tm,0,10);
		list($year,$month,$day) = split("-",$tm);
		$mdate = $day."/".$month."/".$year;
		if ($mdate == "00/00/0000"){
			$mdate="";
		}
	} else {
		$mdate = "";
	}
	return ($mdate);
}
//---------------------------------------------------------------------------
function timest2dt($tm,$separator="/") {
	//$mdate = substr($tm,6,2)."/".substr($tm,4,2)."/".substr($tm,0,4)." ".substr($tm,8,2).":".substr($tm,10,2);
	//$mdate = substr($tm,5,2).$separator.substr($tm,8,2).$separator.substr($tm,0,4)." ".substr($tm,11,2).":".substr($tm,14,2);
	$mdate = substr($tm,8,2).$separator.substr($tm,5,2).$separator.substr($tm,0,4)." ".substr($tm,11,2).":".substr($tm,14,2);
	return ($mdate);
}
//---------------------------------------------------------------------------
function txt_email_to($tt=1,$pven=0) {
	global $default;
	$ret_email = $default->mailto;
	$db = connect();
	if ($pven > 0){
		$ven_q = "Select email from e_vendedores where id_vendedor=$pven";
		$ven_r = $db->Execute($ven_q);
		if ($ven_r && !$ven_r->EOF){
			$ret_email = $ven_r->fields['email'];
		}	
	} else {
		$foot_query = "SELECT email,email_cotiza FROM e_parametros WHERE lenguaje_id=1";
		$foot_res = $db->Execute( $foot_query );
		if ($foot_res && !$foot_res->EOF){
			if ($tt==2){
				$ret_email = $foot_res->fields['email_cotiza'];
			} else {
				$ret_email = $foot_res->fields['email'];
			}
		}
	}	return($ret_email);
}


//---------------------------------------------------------------------------


?>