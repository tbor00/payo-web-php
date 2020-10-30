<?php
require_once("include/config_admin.inc.php");
require_once("../include/config_database.inc.php");
require_once("../include/adodb/adodb.inc.php");
require_once("../include/phpmailer/class.phpmailer.php");
require_once('include/functions.inc.php');

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

		$this->web_title       		=  $site_config->web_title;
		$this->session_name    		=  $site_config->session_name;
		$this->session_timeout 		=  $site_config->session_timeout;
		$this->list_row        		=  $site_config->list_row;
		$this->lang            		=  $site_config->lang;
		$this->set_idioma      		=  $site_config->set_idioma;
		$this->flog            		=  $site_config->flog;
		$this->log_table           =  $site_config->log_table;
		$this->sessions_table      =  $site_config->sessions_table;

		$this->body_bgcolor        =  $site_config->body_bgcolor;
		$this->body_bg             =  $site_config->body_bg;
		$this->title_bgcolor       =  $site_config->title_bgcolor;
		$this->title_color         =  $site_config->title_color;
		$this->border_color        =  $site_config->border_color;
		$this->table_bgcolor       =  $site_config->table_bgcolor;
		$this->table_headercolor   =  $site_config->table_headercolor;
		$this->table_bgheadercolor =  $site_config->table_bgheadercolor;
		$this->row_bgcolor         =  $site_config->row_bgcolor;
		$this->row_bgcolor_over    =  $site_config->row_bgcolor_over;
		$this->dialog_bgcolor      =  $site_config->dialog_bgcolor;
		$this->menu_bgcolor        =  $site_config->menu_bgcolor;
		$this->menu_fontcolor      =  $site_config->menu_fontcolor;
		$this->menu_foncolor_over  =  $site_config->menu_foncolor_over;
		$this->menu_bgcolor_over   =  $site_config->menu_bgcolor_over;
		$this->menu_bordercolor    =  $site_config->menu_bordercolor;
		$this->menu_separatorcolor =  $site_config->menu_separatorcolor;
		$this->fieldrequired_color =  $site_config->fieldrequired_color;
		$this->error_color         =  $site_config->error_color;

		$this->encode	         =  $site_config->encode;
	  
		$this->decimales			= $site_config->decimales;
		$this->enable_gestion		= $site_config->enable_gestion;
		$this->show_stock			= $site_config->show_stock;
		$this->o_venta				= $site_config->o_venta;



		if ($this->dbtype == 'oci8'){
			$this->first_column_table = 1;
		} else {
			$this->first_column_table = 0;
		}

		$this->menu = array();
		$this->permisos = array();
		$this->lenguajes = array("1");

	}
	//---------------------------------------------------------------------------
	function menuadmin($padre=0, $prefix="mn_Array1", $dbm=""){
		if ($_SESSION['uid'] == ""){
			return;
		}
		$s=1;
		if ($dbm==""){
			$db = connect();
		} else {
			$db = $dbm;
		}
		$db->debug = 0;
		if ($_SESSION['administrador'] != 1 ){
			$filtro = "INNER JOIN user_admmenu ON adm_menues.id_menu=user_admmenu.menu_id WHERE user_admmenu.usuario_id=".$_SESSION['uid']." AND id_parent=$padre" ;
		} else {
			$filtro = "WHERE id_parent=$padre";
		}

		$query = "SELECT * FROM adm_menues $filtro ORDER BY id_parent, nivel, posicion";
		$rs = $db->Execute($query);
		if ( $rs && !$rs->EOF){
			while(!$rs->EOF){
				$this->menu[$prefix][$rs->fields[menu]] = $rs->fields[comando];
				$this->permisos[] = $rs->fields[comando];
				if (strlen(trim($rs->fields[comando])) <= 0){
					$this->menuadmin($rs->fields[id_menu], "{$prefix}_{$s}", $db);
				}
				$s++;
				$rs->MoveNext();
			}
		}
		$this->permisos[] = "misdatos";
	}
	//---------------------------------------------------------------------------
	function lenguajes($set_idioma="no",$dbm=""){
		if($set_idioma=='yes') {
			if ($dbm==""){
				$db = connect();
			} else {
				$db = $dbm;
			}
			$lang_query = "SELECT id_lenguaje FROM lenguajes";
			$lang_res = $db->Execute($lang_query);
			if ($lang_res && !$lang_res->EOF) {
				$this->lenguajes = array();
				while(!$lang_res->EOF){
					$this->lenguajes[] = $lang_res->fields[id_lenguaje];
					$lang_res->MoveNext();
				}
			}
		}
		return $this->lenguajes;
	}
	//---------------------------------------------------------------------------
	//---------------------------------------------------------------------------
	//---------------------------------------------------------------------------
	//---------------------------------------------------------------------------



}
$default = new CDefaults();
if (ini_get('register_globals') == false ){
	extract($_GET,EXTR_OVERWRITE,"");
	extract($_POST,EXTR_OVERWRITE,"");
	$PHP_SELF = $_SERVER['PHP_SELF'];
}
//---------------------------------------------------------------------------
$ADODB_LANG = 'es';
$ADODB_SESSION_DRIVER= $default->dbtype;
$ADODB_SESSION_CONNECT= $default->dbhost;
$ADODB_SESSION_USER = $default->dbuser;
$ADODB_SESSION_PWD = $default->dbuser_pass;
$ADODB_SESSION_DB = $default->database;
$ADODB_SESSION_TBL = $default->sessions_table;
$ADODB_SESS_LIFE = $default->session_timeout;
$ADODB_SESSION_EXPIRE_NOTIFY = array('USERID','NotifyFn');
include("../include/adodb/session/adodb-session2.php");
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
function NotifyFn($expireref, $sesskey) {
	global $ADODB_SESS_CONN, $default; 					# the session connection object
	$user = $ADODB_SESS_CONN->qstr($expireref);
	if (preg_match("/oci8/i",$default->dbtype)){
		$dquery="DELETE FROM $default->log_table WHERE trunc((((86400*(sysdate-fecha))/60)/60)/24) >= $default->flog";
	} elseif (preg_match("/postgres/i",$default->dbtype)){
		$dquery="DELETE FROM $default->log_table WHERE fecha < (date(now())-$default->flog)";
	} else {
		$dquery="DELETE FROM $default->log_table WHERE to_days(fecha) < to_days(now()) - $default->flog";
	}
	$ADODB_SESS_CONN->Execute($dquery);
}
//---------------------------------------------------------------------------
function Echo_Error($t_error,$redideccion="",$tiempo=5000) {
	echo "<P><BR></P>";
	echo "<TABLE BGCOLOR=\"#000000\" BORDER=\"0\" CELLPADDING=\"0\" CELLSPACING=\"0\" ALIGN=\"CENTER\">\n"; 
	echo "<TR><TD VALIGN=\"MIDDLE\" ALIGN=\"CENTER\">\n"; 
	echo "<TABLE WIDTH=\"100%\" CELLPADDING=\"2\" CELLSPACING=\"1\">\n"; 
	echo "<TR><TD BGCOLOR=\"#FF0000\"><P STYLE=\"color: white; font-weight: bold\">Error</P></TD></TR>\n";
	echo "<TR><TD BGCOLOR=\"#FFFFFF\"><P STYLE=\"color:red;\">$t_error</P></TD></TR>\n";
	echo "</TABLE></TD></TR>\n";
	echo "</TABLE><BR>\n";
	if ($redideccion != ""){
		echo "<SCRIPT LANGUAGE=\"JavaScript\">\n";
		echo "window.setTimeout(\"window.location='".$redideccion."'\", $tiempo);\n";
		echo "</SCRIPT>\n";
	}
}
//---------------------------------------------------------------------------
function sql_escape($texto="", $escapar="'"){
	$new_texto = str_replace("\0","\\\0", str_replace('\\','\\\\',$texto));
	$new_texto = str_replace($escapar,"\\{$escapar}", $new_texto);
	return $new_texto;
}
//---------------------------------------------------------------------------
function timest2dt($tm, $separator="/") {
	//$mdate = substr($tm,6,2)."/".substr($tm,4,2)."/".substr($tm,0,4)." ".substr($tm,8,2).":".substr($tm,10,2);
	$mdate = substr($tm,8,2).$separator.substr($tm,5,2).$separator.substr($tm,0,4)." ".substr($tm,11,2).":".substr($tm,14,2);
	return ($mdate);
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
function timestd2sql($tm) {
	if ($tm != "") {
		$mday =  substr($tm,0,2);
		$myear = substr($tm,6,4);
		$mmonth = substr($tm,3,2);
		if (checkdate($mmonth,$mday,$myear)){
			$mdate = $myear."-".$mmonth."-".$mday;
		} else {
			$mdate = "";
		}
	} else {
		$mdate = "";
	}
	return ($mdate);
}
//---------------------------------------------------------------------------
function session_defaults() {
	$db = connect();
	$sess_id = session_id();
	///$db->Execute("DELETE FROM producto_componente_tmp WHERE session_id='$sess_id'");
	$_SESSION = array();
}
//-------------------------------------------------------------------------
function checkuser($wuser="",$wpass="",$challenge=""){
	global $default;
	$retval = false;
	if ($challenge == ''){
		return false;
	}
	$db_u = connect();
	$db_u->debug = SDEBUG;
	$user_re = $db_u->Execute("SELECT * FROM usuarios WHERE username=?",array($wuser));
	if( $user_re  && !$user_re->EOF ){
		$password_base = $user_re->fields['password'];
		$hash = $password_base . $challenge;
		$hash = md5($hash);
		if( $hash == $wpass){
			adodb_session_regenerate_id();
			$_SESSION["uid"] = $user_re->fields['id_usuario'];
	  		$_SESSION["user_name"] = $wuser;
			$_SESSION['logged'] = "yes";
			$_SESSION['last_access'] = time();
			$_SESSION["administrador"] = $user_re->fields['administrador'];
			$_SESSION['timeout'] = $default->session_timeout;
			$GLOBALS['USERID'] =& $user_re->fields['id_usuario'];
			$retval = true;
			if (preg_match("/oci8/i",$default->dbtype)){
				$dquery="DELETE FROM $default->log_table WHERE trunc((((86400*(sysdate-fecha))/60)/60)/24) >= $default->flog";
			} elseif (preg_match("/postgres/i",$default->dbtype)){
				$dquery="DELETE FROM $default->log_table WHERE fecha < (date(now())-$default->flog)";
			} else {
				$dquery="DELETE FROM $default->log_table WHERE to_days(fecha) < to_days(now()) - $default->flog";
			}
			$db_u->Execute($dquery);
		}
	}
	return $retval;
}
//---------------------------------------------------------------------------
function convert_str($str, $tipo="HTML"){
	global $default;
	$returnstr = "";
	$iso = array("°","¢","£","§","•","¶","ß","®","©","™","´","¨","≠","Æ","Ø","∞","±","≤","≥","¥","µ","∂","∑","∏","π","∫","ª","º","Ω","æ","ø","¿","¡","¬","√","ƒ","≈","∆","«","»","…"," ","À","Ã","Õ","Œ","œ","–","—","“","”","‘","’","÷","◊","ÿ","Ÿ","⁄","€","‹","›","ﬁ","ﬂ","‡","·","‚","„","‰","Â","Ê","Á","Ë","È","Í","Î","Ï","Ì","Ó","Ô","","Ò","Ú","Û","Ù","ı","ˆ","˜","¯","˘","˙","˚","¸","˝","˛","ˇ");
	$utf8 = array("¬°","¬¢","¬£","¬§","¬•","¬¶","¬ß","¬®","¬©","¬™","¬´","¬¨","¬≠","¬Æ","¬Ø","¬∞","¬±","¬≤","¬≥","¬¥","¬µ","¬∂","¬∑","¬∏","¬π","¬∫","¬ª","¬º","¬Ω","¬æ","¬ø","√Ä","√Å","√Ç","√É","√Ñ","√Ö","√Ü","√á","√à","√â","√ä","√ã","√å","√ç","√é","√è","√ê","√ë","√í","√ì","√î","√ï","√ñ","√ó","√ò","√ô","√ö","√õ","√ú","√ù","√û","√ü","√ ","√°","√¢","√£","√§","√•","√¶","√ß","√®","√©","√™","√´","√¨","√≠","√Æ","√Ø","√∞","√±","√≤","√≥","√¥","√µ","√∂","√∑","√∏","√π","√∫","√ª","√º","√Ω","√æ","√ø");
	$html = array("&#161","&#162","&#163","&#164","&#165","&#166","&#167","&#168","&#169","&#170","&#171","&#172","&#173","&#174","&#175","&#176","&#177","&#178","&#179","&#180","&#181","&#182","&#183","&#184","&#185","&#186","&#187","&#188","&#189","&#190","&#191","&#192","&#193","&#194","&#195","&#196","&#197","&#198","&#199","&#200","&#201","&#202","&#203","&#204","&#205","&#206","&#207","&#208","&#209","&#210","&#211","&#212","&#213","&#214","&#215","&#216","&#217","&#218","&#219","&#220","&#221","&#222","&#223","&#224","&#225","&#226","&#227","&#228","&#229","&#230","&#231","&#232","&#233","&#234","&#235","&#236","&#237","&#238","&#239","&#240","&#241","&#242","&#243","&#244","&#245","&#246","&#247","&#248","&#249","&#250","&#251","&#252","&#253","&#254","&#255");
	for ($i=0;$i < strlen($str); $i++){
		if (ord(substr($str,$i,1)) > 127){
			$posicion = array_search(substr($str,$i,1),$iso);
			if ($posicion > 0){
				if (strtoupper($tipo) == "HTML"){
					$returnstr .= $html[$posicion];
				} elseif (strtoupper($tipo) == "UTF-8"){
					$returnstr .= $utf8[$posicion];
				} else {
					$returnstr .= substr($str,$i,1);
				}
			} else {
				$returnstr .= substr($str,$i,1);
			}
		} else {
			$returnstr .= substr($str,$i,1);
		}
	}
	return $returnstr;
} 
//---------------------------------------------------------------------------
function chr_to_utf8($mnr) {
	$cc = "";
	$c = ord($mnr);

	if ($c < 128) {
		$cc .= chr($c);
	} elseif($c < 2048) {
		$e = (($c >> 6) | 192);
		$cc .= chr($e);
		$e = (($c & 63) | 128);
		$cc .= chr($e);
	} elseif($c >= 2048 && $c < 65535) {
		$cc .= chr((224 | $c >> 12));
		$cc .= chr((128 | $c >> 6 & 63));
		$cc .= chr((128 | ($c & 63)));
	} elseif($c >= 65535 && $c < 131072){
		$cc .= chr((240 | $c >> 24));
		$cc .= chr((128 | $c >> 12 & 63));
		$cc .= chr((128 | $c >> 6 & 63));
		$cc .= chr((128 | $c & 63));
	}
  

   /*


  if ($c < (80)) {
    $cc .= $c;
  } elseif ($c < 800) {
    $cc.= (C0 | $c >> 6);
    $cc.= (80 | $c & 0x3F);
  } elseif ($c < 10000) {
    $cc.= (E0 | $c >> 12);
    $cc.= (80 | $c >> 6 & 0x3F);
    $cc.= (80 | $c & 0x3F);
  } elseif ($c < 200000) {
    $cc.= (F0 | $c >> 18);
    $cc.= (80 | $c >> 12 & 0x3F);
    $cc.= (80 | $c >> 6 & 0x3F);
    $cc.= (80 | $c & 0x3F);
  }
  */
  


  return $cc;
}


//---------------------------------------------------------------------------
function encode2html($rb){
	global $default;
		$rb = str_replace("√°", "&aacute;", $rb);
		$rb = str_replace("√©", "&eacute;", $rb);
		$rb = str_replace("¬Æ", "&reg;", $rb);
		$rb = str_replace("√≠", "&iacute;", $rb);
		$rb = str_replace("ÔøΩ", "&iacute;", $rb);
		$rb = str_replace("√≥", "&oacute;", $rb);
		$rb = str_replace("√∫", "&uacute;", $rb);
		$rb = str_replace("n~", "&ntilde;", $rb);
		$rb = str_replace("¬∫", "&ordm;", $rb);
		$rb = str_replace("¬™", "&ordf;", $rb);
		$rb = str_replace("√É¬°", "&aacute;", $rb);
		$rb = str_replace("√±", "&ntilde;", $rb);
		$rb = str_replace("√ë", "&Ntilde;", $rb);
		$rb = str_replace("√É¬±", "&ntilde;", $rb);
		$rb = str_replace("n~", "&ntilde;", $rb);
		$rb = str_replace("√ö", "&Uacute;", $rb);

		
		$rb = str_replace("°", "&iexcl;", $rb);
		$rb = str_replace("¢", "&cent;", $rb);
		$rb = str_replace("£", "&pound;", $rb);
		$rb = str_replace("§", "&curren;", $rb);
		$rb = str_replace("•", "&yen;", $rb);
		$rb = str_replace("¶", "&brvbar;", $rb);
		$rb = str_replace("ß", "&sect;", $rb);
		$rb = str_replace("®", "&uml;", $rb);
		$rb = str_replace("©", "&copy;", $rb);
		$rb = str_replace("™", "&ordf;", $rb);
		$rb = str_replace("´", "&laquo;", $rb);
		$rb = str_replace("¨", "&not;", $rb);
		$rb = str_replace("≠", "&shy;", $rb);
		$rb = str_replace("Æ", "&reg;", $rb);
		$rb = str_replace("Ø", "&macr;", $rb);
		$rb = str_replace("∞", "&deg;", $rb);
		$rb = str_replace("±", "&plusmn;", $rb);
		$rb = str_replace("≤", "&sup2;", $rb);
		$rb = str_replace("≥", "&sup3;", $rb);
		$rb = str_replace("¥", "&acute;", $rb);
		$rb = str_replace("µ", "&micro;", $rb);
		$rb = str_replace("∂", "&para;", $rb);
		$rb = str_replace("∑", "&middot;", $rb);
		$rb = str_replace("∏", "&cedil;", $rb);
		$rb = str_replace("π", "&sup1;", $rb);
		$rb = str_replace("∫", "&ordm;", $rb);
		$rb = str_replace("ª", "&raquo;", $rb);
		$rb = str_replace("º", "&frac14;", $rb);
		$rb = str_replace("Ω", "&frac12;", $rb);
		$rb = str_replace("æ", "&frac34;", $rb);
		$rb = str_replace("ø", "&iquest;", $rb);
		$rb = str_replace("¿", "&Agrave;", $rb);
		$rb = str_replace("¡", "&Aacute;", $rb);
		$rb = str_replace("¬", "&Acirc;", $rb);
		$rb = str_replace("√", "&Atilde;", $rb);
		$rb = str_replace("ƒ", "&Auml;", $rb);
		$rb = str_replace("≈", "&Aring;", $rb);
		$rb = str_replace("∆", "&AElig;", $rb);
		$rb = str_replace("«", "&Ccedil;", $rb);
		$rb = str_replace("»", "&Egrave;", $rb);
		$rb = str_replace("…", "&Eacute;", $rb);
		$rb = str_replace(" ", "&Ecirc;", $rb);
		$rb = str_replace("À", "&Euml;", $rb);
		$rb = str_replace("Ã", "&Igrave;", $rb);
		$rb = str_replace("Õ", "&Iacute;", $rb);
		$rb = str_replace("Œ", "&Icirc;", $rb);
		$rb = str_replace("œ", "&Iuml;", $rb);
		$rb = str_replace("–", "&ETH;", $rb);
		$rb = str_replace("—", "&Ntilde;", $rb);
		$rb = str_replace("“", "&Ograve;", $rb);
		$rb = str_replace("”", "&Oacute;", $rb);
		$rb = str_replace("‘", "&Ocirc;", $rb);
		$rb = str_replace("’", "&Otilde;", $rb);
		$rb = str_replace("÷", "&Ouml;", $rb);
		$rb = str_replace("◊", "&times;", $rb);
		$rb = str_replace("ÿ", "&Oslash;", $rb);
		$rb = str_replace("Ÿ", "&Ugrave;", $rb);
		$rb = str_replace("⁄", "&Uacute;", $rb);
		$rb = str_replace("€", "&Ucirc;", $rb);
		$rb = str_replace("‹", "&Uuml;", $rb);
		$rb = str_replace("›", "&Yacute;", $rb);
		$rb = str_replace("ﬁ", "&THORN;", $rb);
		$rb = str_replace("ﬂ", "&szlig;", $rb);
		$rb = str_replace("‡", "&agrave;", $rb);
		$rb = str_replace("·", "&aacute;", $rb);
		$rb = str_replace("‚", "&acirc;", $rb);
		$rb = str_replace("„", "&atilde;", $rb);
		$rb = str_replace("‰", "&auml;", $rb);
		$rb = str_replace("Â", "&aring;", $rb);
		$rb = str_replace("Ê", "&aelig;", $rb);
		$rb = str_replace("Á", "&ccedil;", $rb);
		$rb = str_replace("Ë", "&egrave;", $rb);
		$rb = str_replace("È", "&eacute;", $rb);
		$rb = str_replace("Í", "&ecirc;", $rb);
		$rb = str_replace("Î", "&euml;", $rb);
		$rb = str_replace("Ï", "&igrave;", $rb);
		$rb = str_replace("Ì", "&iacute;", $rb);
		$rb = str_replace("Ó", "&icirc;", $rb);
		$rb = str_replace("Ô", "&iuml;", $rb);
		$rb = str_replace("", "&eth;", $rb);
		$rb = str_replace("Ò", "&ntilde;", $rb);
		$rb = str_replace("Ú", "&ograve;", $rb);
		$rb = str_replace("Û", "&oacute;", $rb);
		$rb = str_replace("Ù", "&ocirc;", $rb);
		$rb = str_replace("ı", "&otilde;", $rb);
		$rb = str_replace("ˆ", "&ouml;", $rb);
		$rb = str_replace("˜", "&divide;", $rb);
		$rb = str_replace("¯", "&oslash;", $rb);
		$rb = str_replace("˘", "&ugrave;", $rb);
		$rb = str_replace("˙", "&uacute;", $rb);
		$rb = str_replace("˚", "&ucirc;", $rb);
		$rb = str_replace("¸", "&uuml;", $rb);
		$rb = str_replace("˝", "&yacute;", $rb);
		$rb = str_replace("˛", "&thorn;", $rb);
		$rb = str_replace("ˇ", "&yuml;", $rb);


	return $rb;
}
//---------------------------------------------------------------------------
function make_absoluteURI($url, $protocol = null, $port = null) {
	if (!preg_match('!^([a-z0-9]+)://!i', $url)) {
		// Resolve relative URLs
		if (isset($_SERVER)) {
			$aServerVars =& $_SERVER;
		} else {
			global $HTTP_SERVER_VARS;
			$aServerVars =& $HTTP_SERVER_VARS;
		}

		$sHost = (!empty($aServerVars['HTTP_HOST'])) ? $aServerVars['HTTP_HOST'] : ((!empty($aServerVars['SERVER_NAME'])) ? $aServerVars['SERVER_NAME'] : 'localhost');

		// HTTP_HOST could contain port information, we need
		// to remove it to normalize the code below
		list($sHost) = explode(':', $sHost);

		if (empty($protocol)) {
			$sProtocol = (strtoupper(@$aServerVars['HTTPS']) == 'ON') ? 'https' : 'http';
			$iPort = (!is_int($port)) ? $aServerVars['SERVER_PORT'] : $port;

		} else {
			// Just in case the user passes parameters by reference
			// we don't want to change them...
			$sProtocol = $protocol;
			$iPort = (is_int($port)) ? $port : null;
		}

		$sServer = $sProtocol . '://' . $sHost . ((is_null($iPort) || ( $iPort == 80 || $iPort == 443 )) ? '' : (':' . $iPort));

		if ($url{0} == '/') {
			return $sServer . $url;
		} elseif (!empty($aServerVars['PATH_INFO'])) {
			// Correct for PATH_INFO that offsets the path
			// to current script
			return $sServer . dirname(substr($aServerVars['PHP_SELF'], 0, -1 * strlen($aServerVars['PATH_INFO']))) . '/' . $url;
		} else {
			return $sServer . dirname($aServerVars['PHP_SELF']) . '/' . $url;
		}
	} elseif (!empty($protocol)) {
		// Change scheme
		$sURL = $protocol . ':' . array_pop(explode(':', $url, 2));
		// Since we change protocol but stay on the same server,
		// the port MUST change... Is that new port the default one
		// for this protocol?
		$sPort = (!is_int($port) 
			|| empty($port) 
			|| $port == ($iPort = HTTP::_getStandardPort($protocol))
			|| is_null($iPort)) ? '\1' : '\1:' . $port;

		return preg_replace('!^(([a-z0-9]+)://[^/:]+)(:[\d]+)?!i', $sPort, $sURL);
	}
	return $url;
}
//---------------------------------------------------------------------------
function BaseUri(){
	$uri = make_absoluteURI($PHP_SELF);
	$uri = str_replace(basename($PHP_SELF),'',$uri);
   $uri = str_replace(ADM_FOLDER,'',$uri);
	return $uri;
}
//---------------------------------------------------------------------------
function notificar_activacion($id) {

	$db = connect();
	$res = $db->Execute("SELECT * FROM e_webusers WHERE user_id=$id");
	if ($res && !$res->EOF){
		$secc_res = $db->Execute("SELECT texto FROM e_secciones WHERE codigo='CTAACTIVA'");
		$body = $secc_res->fields[texto];
		$mail_body = txt_email_body("ELECTROPUERTO MAX");
		$mmail_body = str_replace("##TEXTO##","$body",$mail_body); 
		$txt_body  = str_replace("<BR>","\n",nl2br($body)); 
		$mail = new PHPMailer();
		$mail->IsHTML(true);
		$mail->From = txt_email_to();
		$mail->FromName = "ELECTROPUERTO MAX";
		$mail->Mailer = "mail";
		$mail->AddAddress($res->fields[email]);
		$mail->Priority = 1;
		$mail->Subject = ":: ELECTROPUERTO MAX ::" ." - ActivaciÛn de Usuario";
		$mail->AddEmbeddedImage("img/logo.jpg", "logo", "logo.jpg");
		$mail->AltBody = $txt_body;
		$mail->Body = $mmail_body;
		$mail->Send();	
		$mail->ClearAddresses();
		$mail->ClearReplyTos();
	}

}
//---------------------------------------------------------------------------
function txt_email_body($lname="",$lwidth="261",$lheight="66",$twidth="500") {
	global $default;
	$ret_body = "<HTML><BODY>##TEXTO##</BODY></HTML>";
	$db = connect();
	$foot_query = "SELECT emailbody FROM e_parametros WHERE lenguaje_id=1";
	$foot_res = $db->Execute( $foot_query );
  	if ($foot_res && !$foot_res->EOF){
		if (strlen($foot_res->fields['emailbody'])>0){
			$ret_body = $foot_res->fields['emailbody'];
		}
	}
	$ret_body = str_replace("##LOGO_NAME##",$lname,$ret_body);
	$ret_body = str_replace("##LOGO_WIDTH##",$lwidth,$ret_body);
	$ret_body = str_replace("##LOGO_HEIGHT##",$lheight,$ret_body);
	$ret_body = str_replace("##TABLE_WIDTH##",$twidth,$ret_body);
	return($ret_body);
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
function reenviar_cotiza($id=0){
	global $default;
	$db = connect();
	$db->debug = SDEBUG;
	$query = "SELECT * FROM e_cotiza WHERE id_cotiza=$id";
	$cotizacion_r=$db->Execute($query);
	if ($cotizacion_r && !$cotizacion_r->EOF){
		$user_query = "select * from e_webusers where user_id=".$cotizacion_r->fields['user_id'];
		$user_res=$db->Execute($user_query);
		if($user_res && ! $user_res->EOF ){
			$FromName = 'Administrador WEB';
			$MailFrom = txt_email_to();
			if ($user_res->fields['razonsocial']) {
				$Empresa =  $user_res->fields['razonsocial'];
			} else {
				$Empresa =  $user_res->fields['nombres']." ".$user_res->fields['apellidos'];
			}
			$Direccion = $user_res->fields['direccion'];
			$Localidad = $user_res->fields['ciudad'];
			$Cod_Postal = $user_res->fields['cp'];
			$Telefonos = $user_res->fields['telefonos'];
			$Mailto = txt_email_to(0,$user_res->fields['vendedor_id']);
			$vendedor = $user_res->fields['vendedor_id'];
			if ($Mailto==''){
				$Mailto=$mail->AddAddress(txt_email_to(2));
			}	
			$fin=0;
			$htmlbody .= "<HTML><HEAD>\n";
			$htmlbody .= "<TITLE>.:. PEDIDO .:.</TITLE>\n";
			$htmlbody .= "<META HTTP-EQUIV=\"Content-Type\" CONTENT=\"text/html; charset=iso-8859-1\">\n";
			$htmlbody .= "<STYLE TYPE=\"text/css\">\n";

			$htmlbody .= "BODY {font-family: Arial, Helvetica, sans-serif; font-size: 9pt; color: #000000;}\n";
			$htmlbody .= "P {font-family: Arial, Helvetica, sans-serif; font-size: 9pt; margin-top: 0px; margin-bottom: 3px; color: #000000;}\n";
			$htmlbody .= "STRONG {font-family: Arial, Helvetica, sans-serif;font-weight: bold;}\n";
			$htmlbody .= "SMALL {font-family: Arial, Helvetica, sans-serif;font-size: 8pt;}\n";
			$htmlbody .= "TD, TH {font-family: Arial, Helvetica, sans-serif;font-size: 9pt;}\n";
			$htmlbody .= ".textobrowse {font-family: Arial, Verdana, Helvetica, sans-serif;font-size: 11px;color: #000000;text-decoration: none;letter-spacing: 0px;word-spacing: 1px;}\n";
			$htmlbody .= ".titulobrowse {font-family: Arial, Verdana, Helvetica, sans-serif;font-size: 11px;color: #000000;text-decoration: none;letter-spacing: 0px;word-spacing: 1px;font-weight: normal;}\n";
			$htmlbody .= ".titulobrowses {font-family: Arial, Verdana, Helvetica, sans-serif;font-size: 11px;color: #000000;text-decoration: none;letter-spacing: 0px;word-spacing: 1px;font-weight: bold;}\n";
			$htmlbody .= "</STYLE>\n";
			$htmlbody .= "</HEAD>\n";
			$htmlbody .= "<BODY BGCOLOR=\"#C7C7C7\">\n";

			$htmlbody .= "<TABLE WIDTH=\"100%\" BORDER=\"0\" CELLPADDING=\"0\" CELLSPACING=\"2\" BGCOLOR=\"#eeeeee\">\n";
			$htmlbody .= "<TR VALIGN=\"TOP\">";
			$htmlbody .= "<TD BGCOLOR=\"#FFFFFF\" VALIGN=\"TOP\" ALIGN=\"CENTER\">\n";
			$htmlbody .= "<TABLE WIDTH=\"100%\" BORDER=\"0\" CELLPADDING=\"3\" CELLSPACING=\"0\" BGCOLOR=\"#FFFFFF\">\n";
			$htmlbody .= "<TR>\n";
			$htmlbody .= "<TD BGCOLOR=\"#eeeeee\" ALIGN=\"LEFT\"><IMG SRC=\"logo.gif\" ALT=\"\" BORDER=\"0\"></TD>\n";
			$htmlbody .= "</TR></TABLE><BR>\n";
			$htmlbody .= "<TABLE BORDER=\"0\" CELLPADDING=\"3\" CELLSPACING=\"0\" WIDTH=\"100%\">\n";
			$htmlbody .= "<TR><TD CLASS=\"titulobrowses\" BGCOLOR=\"#eeeeee\">PEDIDO NRO.: ".str_pad($cotizacion_r->fields['id_cotiza'],8,"0",STR_PAD_LEFT)."</TD>";
			$htmlbody .= "<TD ALIGN=\"RIGHT\" CLASS=\"titulobrowses\" BGCOLOR=\"#eeeeee\">".timest2dt($cotizacion_r->fields['fecha'])."</TD></TR>\n";
			$htmlbody .= "<TR><TD COLSPAN=\"2\"><STRONG>REFERENCIA: </STRONG>".htmlentities($cotizacion_r->fields['referencia'],ENT_QUOTES,$default->encode)."</TD></TR>\n";
			$htmlbody .= "<TR><TD COLSPAN=\"2\"><STRONG>EMPRESA: </STRONG>".htmlentities($Empresa,ENT_QUOTES,$default->encode)."</TD></TR>\n";
			$htmlbody .= "<TR><TD COLSPAN=\"2\"><STRONG>DIRECCION: </STRONG>".htmlentities($Direccion,ENT_QUOTES,$default->encode)."</TD></TR>\n";
			$htmlbody .= "<TR><TD COLSPAN=\"2\"><STRONG>LOCALIDAD: </STRONG>".htmlentities($Localidad,ENT_QUOTES,$default->encode)." - $Cod_Postal</TD></TR>\n";
			$htmlbody .= "<TR><TD COLSPAN=\"2\"><STRONG>TELEFONOS: </STRONG>".htmlentities($Telefonos,ENT_QUOTES,$default->encode)."</TD></TR>\n";
			$htmlbody .= "</TABLE>\n";
			$htmlbody .= "<P><BR></P>";
			$fecha = floor(adodb_mktime(0,0,0,substr($cotizacion_r->fields['fecha'],5,2),substr($cotizacion_r->fields['fecha'],8,2),substr($cotizacion_r->fields['fecha'],0,4))- adodb_mktime(0,0,0,12,29,1800))/(60 * 60 * 24);
		} else {
			$fin=1;
		}
	} else {
		echo "<P>Pedido de CotizaciÛn no encontrado</P>\n";
		$fin = 1;
	}
	if ($fin == 0){
		$query = "SELECT * FROM e_cotiza_lineas WHERE id_cot=$id order by descripcion_prod ASC";
		if ($items_r=$db->Execute($query)){
			$htmlbody .= "<TABLE WIDTH=\"100%\" CELLPADDING=\"3\" CELLSPACING=\"0\" BORDER=\"0\">\n";
			$htmlbody .= "<TR BGCOLOR=\"#eeeeee\">\n";
			$htmlbody .= "<TH ALIGN=\"LEFT\" STYLE=\"border: 1px solid #000000\">C&oacute;digo</TH>\n";
			$htmlbody .= "<TH ALIGN=\"LEFT\" STYLE=\"border: 1px solid #000000\">Descripci&oacute;n</TH>\n";
			$htmlbody .= "<TH ALIGN=\"LEFT\" STYLE=\"border: 1px solid #000000\">Marca</TH>\n";
			$htmlbody .= "<TH ALIGN=\"LEFT\" STYLE=\"border: 1px solid #000000\">Modelo</TH>\n";
			$htmlbody .= "<TH ALIGN=\"LEFT\" STYLE=\"border: 1px solid #000000\">Unidad</TH>\n";
			$htmlbody .= "<TH ALIGN=\"CENTER\" STYLE=\"border: 1px solid #000000\">Cantidad</TH>\n";
			$htmlbody .= "<TH ALIGN=\"CENTER\" STYLE=\"border: 1px solid #000000\">Precio</TH>\n";
			$htmlbody .= "<TH ALIGN=\"CENTER\" STYLE=\"border: 1px solid #000000\">IVA</TH>\n";
			$htmlbody .= "<TH ALIGN=\"CENTER\" STYLE=\"border: 1px solid #000000\">Subtotal</TH>\n";
			$htmlbody .= "</TR>\n";
			if ($items_r->EOF){
				$htmlbody .= "<TR>\n";
				$htmlbody .= "<TD COLSPAN=\"9\" ALIGN=\"CENTER\" CLASS=\"textobrowse\">No se encontraron items en el Pedido de CotizaciÛn</TD>\n";
				$htmlbody .= "</TR>\n";
			}
			while (!$items_r->EOF){
				$nn++;
				$htmlbody .= "<TR>\n";
				$htmlbody .= "<TD ALIGN=\"LEFT\" CLASS=\"textobrowse\" STYLE=\"border-bottom: 1px solid #000000\">".htmlentities($items_r->fields['codigo_prod'],ENT_QUOTES,$default->encode)."</A></TD>\n";
				$htmlbody .= "<TD ALIGN=\"LEFT\" CLASS=\"textobrowse\" STYLE=\"border-bottom: 1px solid #000000\">".htmlentities($items_r->fields['descripcion_prod'],ENT_QUOTES,$default->encode)."</TD>\n";
				$htmlbody .= "<TD ALIGN=\"LEFT\" CLASS=\"textobrowse\" STYLE=\"border-bottom: 1px solid #000000\">".htmlentities($items_r->fields['marca_prod'],ENT_QUOTES,$default->encode)."</TD>\n";
				$htmlbody .= "<TD ALIGN=\"LEFT\" CLASS=\"textobrowse\" STYLE=\"border-bottom: 1px solid #000000\">".htmlentities($items_r->fields['modelo_prod'],ENT_QUOTES,$default->encode)."</TD>\n";
				$htmlbody .= "<TD ALIGN=\"LEFT\" CLASS=\"textobrowse\" STYLE=\"border-bottom: 1px solid #000000\">".htmlentities($items_r->fields['unidad_prod'],ENT_QUOTES,$default->encode)."</TD>\n";
				$htmlbody .= "<TD ALIGN=\"RIGHT\" CLASS=\"textobrowse\" STYLE=\"border-bottom: 1px solid #000000\">".$items_r->fields['cantidad']."</TD>\n";
				$htmlbody .= "<TD ALIGN=\"RIGHT\" CLASS=\"textobrowse\" STYLE=\"border-bottom: 1px solid #000000\">".decimales($items_r->fields['unitario'])."</TD>\n";
				$htmlbody .= "<TD ALIGN=\"RIGHT\" CLASS=\"textobrowse\" STYLE=\"border-bottom: 1px solid #000000\">".$items_r->fields['iva']."</TD>\n";
				$htmlbody .= "<TD ALIGN=\"RIGHT\" CLASS=\"textobrowse\" STYLE=\"border-bottom: 1px solid #000000\">".sprintf("%1.2f",$items_r->fields['cantidad']*$items_r->fields['unitario'])."</TD>\n";
				$htmlbody .= "</TR>\n";
				$total = $total + ($items_r->fields['cantidad']*$items_r->fields['unitario']);
				if ($cotizacion_r->fields[iva]==0){
					$tiva = $tiva + ($items_r->fields['cantidad']*$items_r->fields['unitario']*$items_r->fields['iva']/100);
				}

				$eba_l .= "<ARTICULO>\n";
				$eba_l .= "<N_CONS>".$nn."</N_CONS>\n";
				$eba_l .= "<COD_PROD>".$items_r->fields['codigo_prod']."</COD_PROD>\n";
				$eba_l .= "<PRODUCTO>".$items_r->fields['descripcion_prod']."</PRODUCTO>\n";
				$eba_l .= "<UNIDAD>".$items_r->fields['unidad_prod']."</UNIDAD>\n";
				$eba_l .= "<MARCA>".$items_r->fields['marca_prod']."</MARCA>\n";
				$eba_l .= "<CANTIDAD>".$items_r->fields['cantidad']."</CANTIDAD>\n";
				$eba_l .= "<PRECIO>".round($items_r->fields['unitario'],$default->decimales)."</PRECIO>\n";
				$eba_l .= "<TOTAL>".sprintf("%1.2f",$items_r->fields['cantidad']*round($items_r->fields['unitario'],$default->decimales))."</TOTAL>\n";
				$eba_l .= "<IVA>".$items_r->fields['iva']."</IVA>\n";
				$eba_l .= "<SALIDAS>"."0"."</SALIDAS>\n";
				$eba_l .= "<DESC_AMP>".""."</DESC_AMP>\n";
				$eba_l .= "<DESC>"."0"."</DESC>\n";
				$eba_l .= "<CODFAB>".$items_r->fields['codfab']."</CODFAB>\n";
				$eba_l .= "<AIVA>"."0"."</AIVA>\n";
				$eba_l .= "<OIVA>".$items_r->fields['iva']."</OIVA>\n";
				$eba_l .= "<MONEDA_O>"."1"."</MONEDA_O>\n";
				$eba_l .= "<PRECIO_O>".round($items_r->fields['unitario'],$default->decimales)."</PRECIO_O>\n";
				$eba_l .= "<PRE_ORIG>".round($items_r->fields['unitario'],$default->decimales)."</PRE_ORIG>\n";
				$eba_l .= "<COEF>"."1"."</COEF>\n";
				$eba_l .= "<MODELO>".$items_r->fields['modelo_prod']."</MODELO>\n";
				$eba_1 .= "<MARCA_C>"."0"."</MARCA_C>\n";
				$eba_l .= "</ARTICULO>\n";
				$items_r->MoveNext();
			}
			if ($cotizacion_r->fields['iva']==0){
				$htmlbody .= "<TR>\n";
				$htmlbody .= "<TD ALIGN=\"LEFT\" CLASS=\"textobrowse\"></TD>\n";
				$htmlbody .= "<TD ALIGN=\"LEFT\" CLASS=\"textobrowse\"></TD>\n";
				$htmlbody .= "<TD ALIGN=\"LEFT\" CLASS=\"textobrowse\"></TD>\n";
				$htmlbody .= "<TD ALIGN=\"LEFT\" CLASS=\"textobrowse\"></TD>\n";
				$htmlbody .= "<TD ALIGN=\"LEFT\" CLASS=\"textobrowse\"></TD>\n";
				$htmlbody .= "<TD ALIGN=\"RIGHT\" CLASS=\"textobrowse\"></TD>\n";
				$htmlbody .= "<TD ALIGN=\"RIGHT\" COLSPAN=\"2\" CLASS=\"textobrowse\" STYLE=\"border: 1px solid #000000\"><STRONG>Subtotal</STRONG></TD>\n";
				$htmlbody .= "<TD ALIGN=\"RIGHT\" CLASS=\"textobrowse\" STYLE=\"border: 1px solid #000000\">".sprintf("%01.2f",$total)."</TD>\n";
				$htmlbody .= "</TR>\n";
				if ($cotizacion_r->fields['descuento']>0){
					$i_descuento = $total*$cotizacion_r->fields['descuento']/100;
					$tiva = $tiva * (1-($cotizacion_r->fields['descuento']/100));
					$htmlbody .= "<TR>\n"; 
					$htmlbody .= "<TD ALIGN=\"LEFT\" CLASS=\"textobrowse\"></TD>\n";
					$htmlbody .= "<TD ALIGN=\"LEFT\" CLASS=\"textobrowse\"></TD>\n";
					$htmlbody .= "<TD ALIGN=\"LEFT\" CLASS=\"textobrowse\"></TD>\n";
					$htmlbody .= "<TD ALIGN=\"LEFT\" CLASS=\"textobrowse\"></TD>\n";
					$htmlbody .= "<TD ALIGN=\"LEFT\" CLASS=\"textobrowse\"></TD>\n";
					$htmlbody .= "<TD ALIGN=\"RIGHT\" CLASS=\"textobrowse\"></TD>\n";
					$htmlbody .= "<TD ALIGN=\"RIGHT\" COLSPAN=\"2\" CLASS=\"textobrowse\" STYLE=\"border: 1px solid #000000\"><STRONG>".$cotizacion_r->fields['leyenda_d']." (".$cotizacion_r->fields['descuento']."%)</STRONG></TD>\n";
					$htmlbody .= "<TD ALIGN=\"RIGHT\" CLASS=\"textobrowse\" STYLE=\"border: 1px solid #000000\">".sprintf("%01.2f",$i_descuento)."</TD>\n";
					$htmlbody .= "</TR>\n";
					$htmlbody .= "<TR>\n"; 
					$htmlbody .= "<TD ALIGN=\"LEFT\" CLASS=\"textobrowse\"></TD>\n";
					$htmlbody .= "<TD ALIGN=\"LEFT\" CLASS=\"textobrowse\"></TD>\n";
					$htmlbody .= "<TD ALIGN=\"LEFT\" CLASS=\"textobrowse\"></TD>\n";
					$htmlbody .= "<TD ALIGN=\"LEFT\" CLASS=\"textobrowse\"></TD>\n";
					$htmlbody .= "<TD ALIGN=\"LEFT\" CLASS=\"textobrowse\"></TD>\n";
					$htmlbody .= "<TD ALIGN=\"RIGHT\" CLASS=\"textobrowse\"></TD>\n";
					$htmlbody .= "<TD ALIGN=\"RIGHT\" COLSPAN=\"2\" CLASS=\"textobrowse\" STYLE=\"border: 1px solid #000000\"><STRONG>Subtotal</STRONG></TD>\n";
					$htmlbody .= "<TD ALIGN=\"RIGHT\" CLASS=\"textobrowse\" STYLE=\"border: 1px solid #000000\">".sprintf("%01.2f",$total-$i_descuento)."</TD>\n";
					$htmlbody .= "</TR>\n";
				}
				$htmlbody .= "<TR>\n";
				$htmlbody .= "<TD ALIGN=\"LEFT\" CLASS=\"textobrowse\"></TD>\n";
				$htmlbody .= "<TD ALIGN=\"LEFT\" CLASS=\"textobrowse\"></TD>\n";
				$htmlbody .= "<TD ALIGN=\"LEFT\" CLASS=\"textobrowse\"></TD>\n";
				$htmlbody .= "<TD ALIGN=\"LEFT\" CLASS=\"textobrowse\"></TD>\n";
				$htmlbody .= "<TD ALIGN=\"LEFT\" CLASS=\"textobrowse\"></TD>\n";
				$htmlbody .= "<TD ALIGN=\"RIGHT\" CLASS=\"textobrowse\"></TD>\n";
				$htmlbody .= "<TD ALIGN=\"RIGHT\" COLSPAN=\"2\" CLASS=\"textobrowse\" STYLE=\"border: 1px solid #000000\"><STRONG>Total IVA</STRONG></TD>\n";
				$htmlbody .= "<TD ALIGN=\"RIGHT\" CLASS=\"textobrowse\" STYLE=\"border: 1px solid #000000\">".sprintf("%01.2f",$tiva)."</TD>\n";
				$htmlbody .= "</TR>\n";
			} else {
				if ($cotizacion_r->fields['descuento']>0) {
					$i_descuento = $total*$cotizacion_r->fields['descuento']/100;
					$htmlbody .= "<TR>\n"; 
					$htmlbody .= "<TD ALIGN=\"LEFT\" CLASS=\"textobrowse\"></TD>\n";
					$htmlbody .= "<TD ALIGN=\"LEFT\" CLASS=\"textobrowse\"></TD>\n";
					$htmlbody .= "<TD ALIGN=\"LEFT\" CLASS=\"textobrowse\"></TD>\n";
					$htmlbody .= "<TD ALIGN=\"LEFT\" CLASS=\"textobrowse\"></TD>\n";
					$htmlbody .= "<TD ALIGN=\"LEFT\" CLASS=\"textobrowse\"></TD>\n";
					$htmlbody .= "<TD ALIGN=\"RIGHT\" CLASS=\"textobrowse\"></TD>\n";
					$htmlbody .= "<TD ALIGN=\"RIGHT\" COLSPAN=\"2\" CLASS=\"textobrowse\" STYLE=\"border: 1px solid #000000\"><STRONG>".$cotizacion_r->fields['leyenda_d']." (".$cotizacion_r->fields['descuento']."%)</STRONG></TD>\n";
					$htmlbody .= "<TD ALIGN=\"RIGHT\" CLASS=\"textobrowse\" STYLE=\"border: 1px solid #000000\">".sprintf("%01.2f",$i_descuento)."</TD>\n";
					$htmlbody .= "</TR>\n";
					$htmlbody .= "<TR>\n"; 
					$htmlbody .= "<TD ALIGN=\"LEFT\" CLASS=\"textobrowse\"></TD>\n";
					$htmlbody .= "<TD ALIGN=\"LEFT\" CLASS=\"textobrowse\"></TD>\n";
					$htmlbody .= "<TD ALIGN=\"LEFT\" CLASS=\"textobrowse\"></TD>\n";
					$htmlbody .= "<TD ALIGN=\"LEFT\" CLASS=\"textobrowse\"></TD>\n";
					$htmlbody .= "<TD ALIGN=\"LEFT\" CLASS=\"textobrowse\"></TD>\n";
					$htmlbody .= "<TD ALIGN=\"RIGHT\" CLASS=\"textobrowse\"></TD>\n";
					$htmlbody .= "<TD ALIGN=\"RIGHT\" COLSPAN=\"2\" CLASS=\"textobrowse\" STYLE=\"border: 1px solid #000000\"><STRONG>Subtotal</STRONG></TD>\n";
					$htmlbody .= "<TD ALIGN=\"RIGHT\" CLASS=\"textobrowse\" STYLE=\"border: 1px solid #000000\">".sprintf("%01.2f",$total-$i_descuento)."</TD>\n";
					$htmlbody .= "</TR>\n";
				}
			}
			$htmlbody .= "<TR>\n";
			$htmlbody .= "<TD ALIGN=\"LEFT\" CLASS=\"textobrowse\"></TD>\n";
			$htmlbody .= "<TD ALIGN=\"LEFT\" CLASS=\"textobrowse\"></TD>\n";
			$htmlbody .= "<TD ALIGN=\"LEFT\" CLASS=\"textobrowse\"></TD>\n";
			$htmlbody .= "<TD ALIGN=\"LEFT\" CLASS=\"textobrowse\"></TD>\n";
			$htmlbody .= "<TD ALIGN=\"LEFT\" CLASS=\"textobrowse\"></TD>\n";
			$htmlbody .= "<TD ALIGN=\"RIGHT\" CLASS=\"textobrowse\"></TD>\n";
			$htmlbody .= "<TD ALIGN=\"RIGHT\" COLSPAN=\"2\" CLASS=\"textobrowse\" STYLE=\"border: 1px solid #000000\"><STRONG>Total</STRONG></TD>\n";
			$htmlbody .= "<TD ALIGN=\"RIGHT\" CLASS=\"textobrowse\" STYLE=\"border: 1px solid #000000\">".sprintf("%01.2f",$total+$tiva-$i_descuento)."</TD>\n";
			$htmlbody .= "</TR>\n";

			$htmlbody .= "</TABLE>\n";

			if ($cotizacion_r->fields['comentario'] != ""){
				$htmlbody .= "<P><BR></P>";
				$htmlbody .= "<TABLE BORDER=\"0\" CELLPADDING=\"3\" CELLSPACING=\"0\" WIDTH=\"100%\">\n";
				$htmlbody .= "<TR><TD CLASS=\"titulobrowses\" BGCOLOR=\"#eeeeee\">COMENTARIOS</TD></TR>\n";
				$htmlbody .= "<TR><TD>".nl2br($cotizacion_r->fields['comentario'])."</TD></TR></TABLE>\n";
			}
			$htmlbody .= "<P><BR></P>";
			$htmlbody .= "</BODY></HTML>\n";
			
			$eba = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>\n";
			if ($default->o_venta){
				$eba .= "<ORDENDEVENTA>\n";
			} else {
				$eba .= "<PRESUPUESTO>\n";
			}	
			$eba .= "<CABECERA>\n";
			$eba .= "<COD_CLIENTE>".$user_res->fields['eb_cod']."</COD_CLIENTE>\n";
			$eba .= "<RAZON_SOCIAL>".$user_res->fields['razonsocial']."</RAZON_SOCIAL>\n";
			$eba .= "<CUIT>".$user_res->fields['cuit']."</CUIT>\n";
			$eba .= "<FECHA>".$fecha."</FECHA>\n";
			$eba .= "<O_CPRA>"."WWW: ".str_pad($cotizacion_r->fields['id_cotiza'],8,"0",STR_PAD_LEFT)."</O_CPRA>\n";
			$eba .= "<VENDEDOR>".$user_res->fields['vendedor_id']."</VENDEDOR>\n";
			$eba .= "<OBRA>".""."</OBRA>\n";
			$eba .= "<USUARIO>".""."</USUARIO>\n";
			$eba .= "<COND_VTA>"."0"."</COND_VTA>\n";
			$eba .= "<REFERENCIA>".$cotizacion_r->fields['referencia']."</REFERENCIA>\n";
			$eba .= "<PARCIAL>".sprintf("%01.2f",$total)."</PARCIAL>\n";
			$eba .= "<PDESC>".$descuento."</PDESC>\n";
			$eba .= "<IDESC>".sprintf("%01.2f",$i_descuento)."</IDESC>\n";
			$eba .= "<SUBTOT>".sprintf("%01.2f",$total-$i_descuento)."</SUBTOT>\n";
			$eba .= "<PIVA>"."0"."</PIVA>\n";
			$eba .= "<IIVA>".sprintf("%01.2f",$tiva)."</IIVA>\n";
			$eba .= "<PIVA2>"."0.00"."</PIVA2>\n";
			$eba .= "<IIVA2>"."0.00"."</IIVA2>\n";
			$eba .= "<TOTALG>".sprintf("%01.2f",$total+$tiva-$i_descuento)."</TOTALG>\n";
			$eba .= "<OPERADOR>".""."</OPERADOR>\n";
			$eba .= "<GRB>"."1"."</GRB>\n";
			$eba .= "<COMIS_O>"."0"."</COMIS_O>\n";
			$eba .= "<COMIS_R>"."0"."</COMIS_R>\n";
			$eba .= "<COMIS_P>"."0"."</COMIS_P>\n";
			$eba .= "<MONEDA_ID>"."1"."</MONEDA_ID>\n";
			$eba .= "<COTIZACION>"."1"."</COTIZACION>\n";
			$eba .= "<COEF>"."1"."</COEF>\n";
			$eba .= "<DES_ID>"."0"."</DES_ID>\n";
			$eba .= "<MARCADA>"."0"."</MARCADA>\n";
			$eba .= "<AUTORIZO>".""."</AUTORIZO>\n";
			$eba .= "<COMENTARIO>".$cotizacion_r->fields['comentario']."</COMENTARIO>\n";
			$eba .= "</CABECERA>\n";

			$eba .= "<ARTICULOS>\n";
			$eba .= $eba_l;
			$eba .= "</ARTICULOS>\n";
			if ($default->o_venta){
				$eba .= "</ORDENDEVENTA>\n";
			} else {
				$eba .= "</PRESUPUESTO>\n";
			}
			
			$mail = new PHPMailer();
			$mail->IsHTML(true);
			$mail->AddEmbeddedImage("../image/logo.gif", "logo.gif", "logo.gif");
			$mail->From = $MailFrom;
			$mail->FromName = $FromName;
			$mail->Mailer = "mail";
			$mail->Subject = "Pedido de CotizaciÛn";
			$mail->AltBody="Debe habilitar html para ver este mensaje";
			$mail->Body = $htmlbody;
			$mail->AddAddress($Mailto);
			//$mail->AddBCC($MailFrom);
			$mail->AddReplyTo($MailFrom);
			$mail->AddStringAttachment($eba, "WWW".str_pad($cotizacion_r->fields['id_cotiza'],8,"0",STR_PAD_LEFT).".eba");
			$mail->Send();
			$mail->ClearAddresses();
			$mail->ClearReplyTos();
		} else {
			echo "<P>ERROR: Al conectarse a la base de datos</P>";
		}
	}

}
//---------------------------------------------------------------------------
function decimales($valor=0){
	global $default;
	$mascara = "%01.".$default->decimales."f";
	$devol=sprintf($mascara,round($valor,$default->decimales));
	return $devol;
}
//---------------------------------------------------------------------------
?>