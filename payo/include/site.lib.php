<?php
//---------------------------------------------------------------------------
require_once("config.inc.php");
require_once("config_database.inc.php");
require_once("adodb/adodb.inc.php");
require_once("functions.inc.php");
require_once("lib/mercadopago.php");
require_once("lib/Mobile_Detect.php");
//require_once("recaptchalib.php");
//---------------------------------------------------------------------------
class CDefaults {
	//---------------------------------------------------------------------------
	function CDefaults () {
		global $site_config;
		$this->dbtype      			= $site_config->DBTYPE;
		$this->dbhost      			= $site_config->DBHOST;
		$this->dbport      			= $site_config->DBPORT;
		$this->database    			= $site_config->DATABASE;
		$this->dbuser      			= $site_config->DBUSER;
		$this->dbuser_pass 			= $site_config->DBUSER_PASS;
		$this->web_title          	= $site_config->web_title;
		$this->keywords           	= $site_config->keywords;
		$this->copyright          	= $site_config->copyright;
		$this->site_description   	= $site_config->site_description;
		$this->author             	= $site_config->author;
		$this->mailfrom 			= $site_config->mailfrom;
		$this->mailfromname			= $site_config->mailfromname;
		$this->mailto   			= $site_config->mailto;
		$this->mailer   			= $site_config->mailer;
		$this->lang     			= $site_config->lang;
		$this->session_name    		= $site_config->session_name;
		$this->error_color    		= $site_config->error_color;
		$this->words				= $site_config->words;
		$this->paginarpor			= $site_config->paginarpor;
		$this->languages			= array();
		$this->mantenimiento		= 0;
		$this->encode				= $site_config->encode;
		$this->decimales			= $site_config->decimales;
		$this->enable_gestion		= $site_config->enable_gestion;

		$this->list_codigo			= $site_config->list_codigo;
		$this->list_descripcion		= $site_config->list_descripcion;
		$this->list_marca			= $site_config->list_marca;
		$this->list_codfab			= $site_config->list_codfab;
		$this->list_unidades		= $site_config->list_unidades;
		$this->list_precio			= $site_config->list_precio;
		$this->list_imagen			= $site_config->list_imagen;
		$this->list_modelo			= $site_config->list_modelo;
		$this->list_stock			= $site_config->list_stock;
		$this->list_mode 			= $site_config->list_mode;
		$this->show_stock			= strtoupper($site_config->show_stock);
		$this->sin_stock_str		= $site_config->sin_stock_str;
		
		$this->products_dir			= $site_config->products_dir;
		$this->o_venta				= $site_config->o_venta;
		$this->mp_id                = $site_config->mp_id;
		$this->mp_secret            = $site_config->mp_secret;
		$this->mp_rate              = $site_config->mp_rate;
		
		$this->descuento            = 1;
		$this->use_recaptcha		= $site_config->use_recaptcha;
		$this->recaptcha_site_key	= $site_config->recaptcha_site_key;
		$this->recaptcha_secret		= $site_config->recaptcha_secret;
		$this->is_mobile			= false;
	}
	//---------------------------------------------
	function SetLanguages(){
		$db = connect();
		$result=$db->Execute("SELECT * FROM lenguajes WHERE activo=1 ORDER by id_lenguaje");
		if ($result && !$result->EOF){
			while(!$result->EOF){
				$this->languages[$result->fields[lng]]=array($result->fields[lenguaje],$result->fields[id_lenguaje]);
				$result->MoveNext();	
			}
		}
	}
	//---------------------------------------------
	function FindLang($lang=''){
		$n=1;
		foreach ($this->languages as $k => $v){
			if ($k==$lang){
				$n = $v[1];
				break;
			}
		}
		return $n;
	}
	//---------------------------------------------
	function SetManteinance(){
		$db = connect();
		$result=$db->Execute("SELECT * FROM e_parametros WHERE lenguaje_id=1");
		if ($result && !$result->EOF){
			$this->mantenimiento = $result->fields[mantenimiento];
		}
	}
	//---------------------------------------------
	function is_Mobile(){
		$detect = new Mobile_Detect;
		$this->is_mobile = ($detect->isMobile() ? ($detect->isTablet() ? true : true) : false);
	}	
	//---------------------------------------------
}
$default = new CDefaults();
$default->Setlanguages();
$default->SetManteinance();
$default->is_Mobile();
extract($_GET,EXTR_OVERWRITE,"");
extract($_POST,EXTR_OVERWRITE,"");
$PHP_SELF = $_SERVER['PHP_SELF'];
$ADODB_LANG = 'es';
//---------------------------------------------------------------------------
function connect() {
	global $default;
	$db = NewADOConnection($default->dbtype);
	$db->debug = SDEBUG;
	if (preg_match("/oci8/i",$default->dbtype)){
		$db->connectSID = true;
	}
	if (!$db->Connect($default->dbhost, $default->dbuser,$default->dbuser_pass,$default->database)) {
		return 0;
	} else {
		$db->setCharset('latin1');
		return $db;
	}
}
//---------------------------------------------------------------------------
function Echo_Error($t_error) {
	global $default;
	echo "<P ALIGN='CENTER'><STRONG STYLE='color:".$default->error_color."'>Error: $t_error</STRONG></P>\n";
}
//---------------------------------------------------------------------------
function timest2dt($tm,$separator="/") {
	//$mdate = substr($tm,6,2)."/".substr($tm,4,2)."/".substr($tm,0,4)." ".substr($tm,8,2).":".substr($tm,10,2);
	//$mdate = substr($tm,5,2).$separator.substr($tm,8,2).$separator.substr($tm,0,4)." ".substr($tm,11,2).":".substr($tm,14,2);
	$mdate = substr($tm,8,2).$separator.substr($tm,5,2).$separator.substr($tm,0,4)." ".substr($tm,11,2).":".substr($tm,14,2);
	return ($mdate);
}
//---------------------------------------------------------------------------
function timesql2std($tm) {
	if ($tm != ""){
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
function longdate($tm,$langu) {
	$montharray=array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
	list($year,$month,$day) = split("-",$tm);
	if ($langu==2){
		$mdate = translate($montharray[$month-1])." ".$day.",".$year;
	} else {
		$mdate = $day." de ".translate($montharray[$month-1])." de ".$year;
	}
	return ($mdate);
}
//---------------------------------------------------------------------------
function darTitulo($codigo){
	global $default, $lang_q;
	$db=connect();
	$db->debug = SDEBUG;
	if ($codigo == 'profile'){
		$titulo = translate('Perfil');
	} else {
		$sbm_query = "select titulo from e_menues where (lenguaje_id=$lang_q) and activo=1 and (id_menu='". $codigo . "')" ;
		if($sbm_res = $db->Execute($sbm_query)){
			$titulo = $sbm_res->fields['titulo'];
		}
	}
	return $titulo;
}
//---------------------------------------------------------------------------
function imgTitulo($codigo){
	global $default,$lang_q;
	$imgtitulo = "";
	$db=connect();
	$db->debug = SDEBUG;
	$sbm_query = "select imgtitulo from e_menues where (lenguaje_id=$lang_q) and activo=1 and (id_menu='". $codigo . "')" ;
	if($sbm_res = $db->Execute($sbm_query)){
		$imgtitulo = $sbm_res->fields['imgtitulo'];
	}
	return $imgtitulo;
}

//---------------------------------------------------------------------------
function BuscarProps($codigo,$lang_q=1){
	global $default;
	$db=connect();
	$db->debug = SDEBUG;
	$sbm_query = "select titulo,bgcolor,menu_id,destino from e_menues where (lenguaje_id=$lang_q) and activo=1 and (id_menu='". $codigo . "')" ;
	if($sbm_res = $db->Execute($sbm_query)){
		$titulo = $sbm_res->fields['titulo'];
		$bgcolor = $sbm_res->fields['bgcolor'];
		$privado = $sbm_res->fields['destino'];
		if ($sbm_res->fields['menu_id']>0){
			$sm_query = "select bgcolor from e_menues where (lenguaje_id=$lang_q) and (id_menu='". $sbm_res->fields['menu_id'] . "')" ;
			if($sm_res = $db->Execute($sm_query)){
				$bgcolor = $sm_res->fields['bgcolor'];
			}
		}
	}
	return array($titulo,$bgcolor,$privado);
}
//---------------------------------------------------------------------------
function ValidOpcion($codigo){
	$retorno = false;
	$db=connect();
	$sbm_query = "select titulo from e_secciones where id_seccion=". $codigo . "" ;
	$sbm_res = $db->Execute($sbm_query);
	if ($sbm_res->fields['titulo']!=''){
		$retorno = true;
	}
	return $retorno;
}
//---------------------------------------------------------------------------
function mk_keywords(){
	global $default;
	$texto = "";
	$db=connect();
	$k_query = "select * from e_keywords";
	$k_res = $db->Execute($k_query);
	if ($k_res && !$k_res->EOF){
		while(!$k_res->EOF){
			if ($$texto == "") {
				$texto = $k_res->fields[palabra];
		  	} else {
		     	$texto .= ", ".$k_res->fields[palabra];
		  	}
			$k_res->MoveNext();
		}
	}
	return($texto);
}
//---------------------------------------------------------------------------
function mk_menu($section=0,$tipo=0,$subsection=0){
	global $default, $lang_q;
	$db = connect();
	$db->debug = SDEBUG;
	$menu_array = array();
	if ($tipo==1){
		$SUBMENU="AND menu_id=$section";
	}
	$ORDEN="order by posicion";
	$menu_query = "SELECT id_menu,titulo,ejecutar FROM e_menues WHERE tipo=$tipo AND activo=1 AND lenguaje_id=$lang_q $SUBMENU $RESERVADO $ORDEN";
	$menu_res = $db->Execute( $menu_query );
  	if ($menu_res){
   	while( !$menu_res->EOF ){
			if ($tipo==1){
				$mnu = $subsection;
			} else {
				$mnu = $section;
			}
			if ($mnu==$menu_res->fields[id_menu]) {
		     	$sele = 1;
		  	} else {
				$sele = 0;
		  	}
	  		array_push($menu_array,array($menu_res->fields[id_menu],$menu_res->fields[titulo],$menu_res->fields[imgon],$menu_res->fields[imgoff],$menu_res->fields[onoff],$menu_res->fields[accion],$sele));
			$menu_res->MoveNext();
		}
	}
	return($menu_array);
}
//---------------------------------------------------------------------------
function display_search_form($search) {
	global $default, $lang_q;
	echo "<FORM NAME=\"buscar\">\n";
	echo "<TABLE BORDER=\"0\" CELLPADDING=\"2\" CELLSPACING=\"1\">";
	echo "<TR VALIGN=\"MIDDLE\">";
	echo "<TD VALIGN=\"MIDDLE\" ALIGN=\"LEFT\">";
	echo "<INPUT NAME=\"search\" CLASS=\"boxes\" TYPE=\"TEXT\" VALUE=\"". htmlentities(urldecode(stripslashes($search)))."\" SIZE=\"15\">&nbsp;";
	echo "<INPUT NAME=\"enviar\" CLASS=\"button\" TYPE=\"SUBMIT\" VALUE=\"Buscar\">";
	echo "</TD></TR></TABLE>";											      
	echo "<INPUT NAME=\"op\" TYPE=\"HIDDEN\" VALUE=\"sea\">";
	echo "</FORM>\n";
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
function display_text($section=0){
	global $default,$search,$accion,$first,$op,$sop,$action,$lang_q,$useraction,$auth,$id,$novedad,$nanteriores;
	$db = connect();
	$db->debug = SDEBUG;


		$menu_query = "SELECT texto_up,texto_down,inclusion,destino FROM e_menues WHERE id_menu=$section AND activo=1 AND lenguaje_id=$lang_q";
		$menu_res = $db->Execute( $menu_query );
	  	if ($menu_res){
			if (!$_SESSION['logged'] && $menu_res->fields['destino']>0){
				echo "<div class=\"alert alert-danger\"><i class=\"fa fa-exclamation-circle\"></i>&nbsp;Usted no tiene acceso a este contenido</div>";
			} else {
				//----------------------------------------------
				if ($_POST['oc']!="sndmail"){
					echo $menu_res->fields['texto_up'];
				}
				$formm_query = "SELECT * FROM e_menu_form WHERE menu_id=$section AND lenguaje_id=$lang_q ORDER BY posicion_form";
				$formm_res = $db->Execute($formm_query);
				if ($formm_res && !$formm_res->EOF){
					while(!$formm_res->EOF){
						$form_query = "SELECT formulario FROM e_formulars WHERE id_form=".$formm_res->fields['form_id'];
						$form_res = $db->Execute($form_query);
						if ($form_res && !$form_res->EOF){
							if (file_exists("include/templates/".$form_res->fields['formulario'])){
								include("include/templates/".$form_res->fields['formulario']);
							}
						}
						$formm_res->moveNext();
					}
				}
				if ($_POST['oc']!="sndmail"){
					echo $menu_res->fields['texto_down'];
				}
				//----------------------------------------------
			} 
		}

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
		$foot_query = "SELECT email,email_cotiza,email_cobro FROM e_parametros WHERE lenguaje_id=1";
		$foot_res = $db->Execute( $foot_query );
		if ($foot_res && !$foot_res->EOF){
			if ($tt==2){
				$ret_email = $foot_res->fields['email_cotiza'];
			} elseif ($tt==3){
				$ret_email = $foot_res->fields['email_cobro'];
			} else {
				$ret_email = $foot_res->fields['email'];
			}
		}
	}
	return($ret_email);
}
//---------------------------------------------------------------------------
function search_op($section=""){
	$ret_op = 0;
	$db=connect();
	$db->debug=SDEBUG;
	$menu_query = "SELECT id_menu FROM e_menues WHERE name_id='$section' AND activo='1'";
	$menu_res = $db->Execute( $menu_query );
 	if ($menu_res){
		$ret_op = $menu_res->fields['id_menu'];
	}
	return ($ret_op);
}
//---------------------------------------------------------------------------
function isValidURL($url){
	return preg_match('|^http(s)?://[a-z0-9-]+(\.[a-z0-9-]+)*(:[0-9]+)?(/.*)?$|i', $url);
}
//---------------------------------------------------------------------------
function isValidEmail($email){
	$isValid = true;
	$atIndex = strrpos($email, "@");
	if (is_bool($atIndex) && !$atIndex){
		$isValid = false;
	} else {
		$domain = substr($email, $atIndex+1);
		$local = substr($email, 0, $atIndex);
		$localLen = strlen($local);
		$domainLen = strlen($domain);
		if ($localLen < 1 || $localLen > 64) {
			// local part length exceeded
			$isValid = false;
		} else if ($domainLen < 1 || $domainLen > 255) {
			// domain part length exceeded
			$isValid = false;
		} else if ($local[0] == '.' || $local[$localLen-1] == '.') {
			// local part starts or ends with '.'
			$isValid = false;
		} else if (preg_match('/\\.\\./', $local)) {
			// local part has two consecutive dots
			$isValid = false;
		} else if (!preg_match('/^[A-Za-z0-9\\-\\.]+$/', $domain)) {
			// character not valid in domain part
			$isValid = false;
		} else if (preg_match('/\\.\\./', $domain)) {
			// domain part has two consecutive dots
			$isValid = false;
		} else if (!preg_match('/^(\\\\.|[A-Za-z0-9!#%&`_=\\/$\'*+?^{}|~.-])+$/', str_replace("\\\\","",$local))) {
			// character not valid in local part unless 
			// local part is quoted
			if (!preg_match('/^"(\\\\"|[^"])+"$/', str_replace("\\\\","",$local))){
				$isValid = false;
			}
		} 	
		if ($isValid && !(checkdnsrr($domain,"MX") || checkdnsrr($domain,"A"))) {
			// domain not found in DNS
			$isValid = false;
		}
	}
	return $isValid;
}
//---------------------------------------------------------------------------
function uri_val($url,$filter=''){
	$new_uri = "";
	$result = array();
	$urla = array();
	if (!is_array($filter)){
		$filter=array();
	}
	$urla = split ('&', $url);
	foreach($urla as $v){
		$pos = strpos ($v, "=");
		if ($pos !== false) {
			if (!in_array(substr($v, 0,$pos),$filter)) {
				array_push($result, $v);	
			}
		}
	}
	$new_uri = implode ("&", $result);
	return $new_uri;
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
		} elseif (substr($url,0,3)=='../'){
			$url = substr($url,3-strlen($url));
			return $sServer . substr(dirname($aServerVars['PHP_SELF']),0,strpos("/")) . $url;
			//return $sServer . dirname($aServerVars['PHP_SELF']) . '/' . $url;
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
function section_text($cod_secc,$titulo=0) {
	global $default, $lang_q, $glo_img_dir;
	$texto_secc = "";
	$db = connect();
	$secc_query = "SELECT imgtitulo, texto FROM e_secciones WHERE codigo='$cod_secc' AND id_lenguaje=$lang_q";
	$secc_res = $db->Execute( $secc_query );
  	if ($secc_res){
		if (!$secc_res->EOF){
			if ($titulo != 0){
				$imgtitulo=$secc_res->fields['imgtitulo'];
				if (file_exists("$glo_img_dir/$imgtitulo") && $imgtitulo!=""){
					echo "<IMG SRC=\"$glo_img_dir/$imgtitulo\" BORDER=\"0\">";
				}
			}	
			$texto_secc = ($secc_res->fields['texto']);
		}
	}
	return $texto_secc;
}
//---------------------------------------------------------------------------
function paginar($actual, $total, $por_pagina, $enlace) {
	$total_paginas = ceil($total/$por_pagina);
	$anterior = $actual - 1;
	$posterior = $actual + 1;

	$texto = "<TABLE WIDTH=\"100%\" CELLPADDING=\"2\" CELLSPACING=\"0\" BORDER=\"0\">\n";
	$texto .= "<TR VALIGN=\"MIDDLE\">";
//	$texto .= "<TD WIDTH=\"20%\" ALIGN=\"LEFT\" CLASS=\"textobrowse\">".translate("Pág.")." $actual".translate(" de ")."$total_paginas</TD>\n";
	$texto .= "<TD WIDTH=\"20%\" ALIGN=\"LEFT\" CLASS=\"textobrowse\"></TD>\n";
	$texto .= "<TD WIDTH=\"60%\" ALIGN=\"CENTER\" CLASS=\"textobrowse\">";

	if ($actual + 4 > $total_paginas){
		$hasta = $total_paginas;
	} else {
  		$hasta = $actual + 4;
	}
	if ($hasta < 10){
		$hasta = 10;
	}
	if ($hasta > $total_paginas){
		$hasta=$total_paginas;
	}

	if ($actual - 5 > 0){
  		$desde = $actual - 5;
	} else {
  		$desde = 1;
	}

	if ($actual + 5 >= $total_paginas){
		$desde = $total_paginas - 9;
	}
	if ($desde <= 0){
		$desde = 1;
	}

	$texto .= "<TABLE CELLPADDING=\"0\" CELLSPACING=\"0\" BORDER=\"0\">\n";
	$texto .= "<TR>";
	$texto .= "<TD VALIGN=\"MIDDLE\" CLASS=\"text-right\">";
	if ($actual > 1){
		$texto .= "<A HREF=\"{$enlace}1\" class=\"btn btn-default\" style=\"margin-right:5px;\" data-toggle=\"tooltip\" title=\"Primera\"><i class=\"fa fa-fast-backward\"></i></A>";
		$texto .= "<A HREF=\"$enlace$anterior\" class=\"btn btn-default\" style=\"margin-right:5px;\" data-toggle=\"tooltip\" title=\"Anterior\"><i class=\"fa fa-backward\"></i></A>";
	} else {
		$texto .= "<span class=\"btn btn-disable\" style=\"margin-right:5px;\" ><i class=\"fa fa-fast-backward\"></i></span>";
		$texto .= "<span class=\"btn btn-disable\" style=\"margin-right:5px;\" ><i class=\"fa fa-backward\"></i></span>";
	}
	$texto .= "</TD>\n";
	$texto .= "<TD VALIGN=\"MIDDLE\" CLASS=\"text-center\">";
	for ($i=$desde; $i<$actual; $i++) {
		$texto .= "<A HREF=\"$enlace$i\">$i</A> ";
	}
	$texto .= "<B>$actual</B> ";
	for ($i=$actual+1; $i<=$hasta; $i++){
		$texto .= "<A HREF=\"$enlace$i\">$i</A> ";
	}
	$texto .= "</TD>\n";
	$texto .= "<TD VALIGN=\"MIDDLE\" CLASS=\"text-left\">";
	if ($actual<$total_paginas){
		$texto .= "<A HREF=\"$enlace$posterior\" class=\"btn btn-default\" style=\"margin-left:5px;\" data-toggle=\"tooltip\" title=\"Siguiente\"><i class=\"fa fa-forward\"></i></A>";
		$texto .= "<A HREF=\"$enlace$total_paginas\" class=\"btn btn-default\" style=\"margin-left:5px;\" data-toggle=\"tooltip\" title=\"Ultima\"><i class=\"fa fa-fast-forward\"></i></A>";

	} else {
		$texto .= "<span class=\"btn btn-disable\" style=\"margin-left:5px;\" ><i class=\"fa fa-forward\"></i></span>";
		$texto .= "<span class=\"btn btn-disable\" style=\"margin-left:5px;\" ><i class=\"fa fa-fast-forward\"></i></span>";
	}
	$texto .= "</TD>";
	$texto .= "</TR></TABLE>\n";
	$texto .= "</TD>\n";

	$texto .= "<TD WIDTH=\"20%\" ALIGN=\"RIGHT\">&nbsp;";
	$texto .= "</TD>\n";

	$texto .= "</TR></TABLE>\n";
	return $texto;
}
//---------------------------------------------------------------------------
function Titulo_Browse($titulo,$campo,$orden,$ord,$url,$ordenar=1,$ancho=0,$align=""){
	if ($ancho != 0){
	    $colwidth="WIDTH=\"$ancho\"";
	} else {
	    $colwidth="";
	}
	if ($align != ""){
		$tituloclass="titulobrowse-".$align;
	} else {
		$tituloclass="titulobrowse";
	}	
	if ($ordenar == 1) {
		if ($orden == $campo && $ord == "ASC"){
			//$texto = "<td  class=\"".$tituloclass."\" $colwidth>".translate($titulo)."&nbsp;<A HREF=\"$url&orden=$campo&ord=ASC\" data-toggle=\"tooltip\" title=\"Orden Ascendente\"><i class=\"fa fa-arrow-circle-o-down\"></i></A><A HREF=\"$url&orden=$campo&ord=DESC\" data-toggle=\"tooltip\" title=\"Orden Descendente\"><i class=\"fa fa-arrow-circle-up\"></i></a></td>\n";
			$texto = "<td  class=\"".$tituloclass."\" $colwidth>".translate($titulo)."&nbsp;<i class=\"fa fa-arrow-circle-o-down\"></i><A HREF=\"$url&orden=$campo&ord=DESC\" data-toggle=\"tooltip\" title=\"Orden Descendente\"><i class=\"fa fa-arrow-circle-up\"></i></a></td>\n";
		} elseif ($orden == $campo && $ord == "DESC"){
			//$texto = "<td class=\"".$tituloclass."\" $colwidth>".translate($titulo)."&nbsp;<A HREF=\"$url&orden=$campo&ord=ASC\" data-toggle=\"tooltip\" title=\"Orden Ascendente\"><i class=\"fa fa-arrow-circle-down\"></i></A><A HREF=\"$url&orden=$campo&ord=DESC\" data-toggle=\"tooltip\" title=\"Orden Descendente\"><i class=\"fa fa-arrow-circle-o-up\"></i></a></td>\n";
			$texto = "<td class=\"".$tituloclass."\" $colwidth>".translate($titulo)."&nbsp;<A HREF=\"$url&orden=$campo&ord=ASC\" data-toggle=\"tooltip\" title=\"Orden Ascendente\"><i class=\"fa fa-arrow-circle-down\"></i></A><i class=\"fa fa-arrow-circle-o-up\"></i></td>\n";
		} else {
			$texto = "<td class=\"".$tituloclass."\" $colwidth>".translate($titulo)."&nbsp;<A HREF=\"$url&orden=$campo&ord=ASC\" data-toggle=\"tooltip\" title=\"Orden Ascendente\"><i class=\"fa fa-arrow-circle-down\"></i></A><A HREF=\"$url&orden=$campo&ord=DESC\" data-toggle=\"tooltip\" title=\"Orden Descendente\"><i class=\"fa fa-arrow-circle-up\"></i></a></td>\n";
		}
	} else {
		$texto = "<td class=\"".$tituloclass."\" $colwidth>".translate($titulo)."</td>\n";	
	}
	return $texto;
}
//---------------------------------------------------------------------------
function HeaderingExcel($filename) {
	header("Content-type: application/vnd.ms-excel");
	header("Content-Disposition: attachment; filename=$filename" );
	header("Expires: 0");
	header("Cache-Control: must-revalidate, post-check=0,pre-check=0");
	header("Pragma: public");
}
//---------------------------------------------------------------------------
function decimales($valor=0){
	global $default;
	$mascara = "%01.".$default->decimales."f";
	$devol=sprintf($mascara,round($valor,$default->decimales));
	return $devol;
}
//---------------------------------------------------------------------------
function similar_file_exists($filename) {
	if (file_exists($filename)) {
		return $filename;
	}
	$dir = dirname($filename);
	$files = glob($dir . '/*');
	$lcaseFilename = strtolower($filename);
	foreach($files as $file) {
		if (strtolower($file) == $lcaseFilename) {
			return ($file);
		}
	}
	return '';
}
/*
//---------------------------------------------------------------------------
function convert_image($sourcepic,$destpic,$res,$quality=90){
	$thumb_generator = chkgd22();
	if(preg_match("/gd/i",$thumb_generator)) {
		if (preg_match("/(.jpg|.jpeg)$/i",$sourcepic)) {
			$type="jpg";
			$im=imagecreatefromjpeg($sourcepic);
		} elseif (preg_match("/.png$/i",$sourcepic)) {
			$type="png";
			$im=imagecreatefrompng($createfn);
		} elseif (preg_match("/.gif$/i",$sourcepic)) {
			$type="gif";
			$im=imagecreatefromgif($sourcepic);
		} 

		if ($im != "") {
			$dims=explode("x",$res);
			$newh=$dims[1];
			$neww=$newh/imagesy($im) * imagesx($im);
      	if ($neww > imagesx($im)) {
				$neww=imagesx($im);
				$newh=imagesy($im);
			}
			if ($neww > $dims[0]) {
				$neww=$dims[0];
				$newh=$neww/imagesx($im) * imagesy($im);
			}
			
			$im2 = imagecreatetruecolor($neww, $newh);
			if ($type == 'png') {
				imagealphablending($im2, false);
				imagesavealpha($im2, true);
				$background = imagecolorallocatealpha($im2, 255, 255, 255, 127);
				imagecolortransparent($im2, $background);
			} else {
				$background = imagecolorallocate($im2, 255, 255, 255);
			}

			imagefilledrectangle($im2, 0, 0, $width, $height, $background);
			imagecopyresampled($im2, $im, $xpos, $ypos, 0, 0, $neww, $newh, imagesx($im), imagesy($im));
			
			
			if ($type=="jpg") {
				imagejpeg($im2,$destpic,$quality);
			} elseif ($type=="png") {
				imagepng($im2,$destpic);
			} elseif ($type=="gif") {
				imagegif($im2,$destpic);
			}	
			ImageDestroy($im);
			ImageDestroy($im2);
			$ret_val = 1;
		} else {
			$ret_val = 0;
		}
	}

	return($ret_val);
}
*/
//---------------------------------------------------------------------------
function convert_image($sourcepic,$destpic,$res,$quality=90){
	$xpos = 0;
	$ypos = 0;
	if (preg_match("/(.jpg|.jpeg)$/i",$sourcepic)) {
		$type="jpg";
		$im=imagecreatefromjpeg($sourcepic);
	} elseif (preg_match("/.png$/i",$sourcepic)) {
		$type="png";
		$im=imagecreatefrompng($createfn);
	} elseif (preg_match("/.gif$/i",$sourcepic)) {
		$type="gif";
		$im=imagecreatefromgif($sourcepic);
	} 

	if ($im != "") {
		$dims=explode("x",$res);
		$newh=$dims[1];
		$neww=$newh/imagesy($im) * imagesx($im);
		if ($neww > imagesx($im)) {
			$neww=imagesx($im);
			$newh=imagesy($im);
		}
		if ($neww > $dims[0]) {
			$neww=$dims[0];
			$newh=$neww/imagesx($im) * imagesy($im);
		}
		$im2 = imagecreatetruecolor($neww, $newh);
		if ($type == 'png') {
			imagealphablending($im2, false);
			imagesavealpha($im2, true);
			$background = imagecolorallocatealpha($im2, 255, 255, 255, 127);
			imagecolortransparent($im2, $background);
		} else {
			$background = imagecolorallocate($im2, 255, 255, 255);
		}

		imagefilledrectangle($im2, 0, 0, $width, $height, $background);
		imagecopyresampled($im2, $im, $xpos, $ypos, 0, 0, $neww, $newh, imagesx($im), imagesy($im));
		
		/*
		if ( $thumb_generator == "gd2" ) {
			$im2 = imagecreatetruecolor($neww,$newh);
			imagecopyresampled($im2,$im,0,0,0,0,$neww,$newh,imagesx($im),imagesy($im));
		} elseif ( $thumb_generator == "gd" )	{		
			$im2 = imagecreate($neww,$newh);
			imagecopyresized($im2,$im,0,0,0,0,$neww,$newh,imagesx($im),imagesy($im));
		}
		*/
		if ($type=="jpg") {
			imagejpeg($im2,$destpic,$quality);
		} elseif ($type=="png") {
			imagepng($im2,$destpic);
		} elseif ($type=="gif") {
			imagegif($im2,$destpic);
		}	
		ImageDestroy($im);
		ImageDestroy($im2);
		$ret_val = 1;
	} else {
		$ret_val = 0;
	}
	return($ret_val);
}

//--------------------------------------------------------------------------
function create_thumb($filename, $thumbpath, $width, $height) {
	if (!is_file($filename)) {
		$dir = dirname($filename);
		$files = glob($dir . '/*');
		$lcaseFilename = strtolower($filename);
		$filename = '';
		foreach($files as $file) {
			if (strtolower($file) == $lcaseFilename) {
				$filename = $file;
				break;
			}
		}		
	}
	if ($filename==''){
		return;
	}	
	
	$partes_ruta = pathinfo($filename);
	
	$old_image = $filename;
	$new_image = $thumbpath.'/' . rawurlencode(strtolower($partes_ruta['filename'])) . '-' . $width . 'x' . $height . '.' . strtolower($partes_ruta['extension']);

	if (!is_file($new_image) || (filectime($old_image) > filectime($new_image))) {
		//$path = dirname(__FILE__).'';
		$path= SYS_PATH;
		$directories = explode('/', dirname(str_replace('../', '', $new_image)));
		foreach ($directories as $directory) {
			$path = $path . '/' . $directory;
			if (!is_dir($path)) {
				@mkdir($path, 0777);
			}
		}
		list($width_orig, $height_orig) = getimagesize($old_image);

		if ($width_orig != $width || $height_orig != $height) {
			convert_image($old_image,$new_image, $width."x".$height);
		} else {
			copy($old_image, $new_image);
		}
	}
	return $new_image;

}

//--------------------------------------------------------------------------
function chkgd22() { 
   static $gd_version_number = null; 
   if ($gd_version_number === null) { 
       ob_start(); 
       phpinfo(8); 
       $module_info = ob_get_contents(); 
       ob_end_clean(); 
       if (preg_match("/\bgd\s+version\b[^\d\n\r]+?([\d\.]+)/i", 
               $module_info,$matches)) { 
           $gd_version_number = $matches[1]; 
       } else { 
           $gd_version_number = 0; 
       } 
   } 
   
	if ($gd_version_number >= 2) { 
   	return "gd2"; 
	} else {
   	return "gd";
	} 
}
//--------------------------------------------------------------------------
function e_randomTEXT($length=6) {
	$key = "";	
	$pattern = "123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
	$pattern_lenght = strlen($pattern);
	for($i=0;$i<$length;$i++) {
		$letra = $pattern[rand(0,$pattern_lenght-1)];
		$key .= $letra;
	}
	return $key;
} 
//--------------------------------------------------------------------------
function e_randomIMG() {
	$code = $_SESSION['rand_code'];
	$font_dir = 'include/fonts/*.ttf';
	$width = rand(120, 150);
	$height = rand(40, 40);
	$img = imagecreate($width, $height);
	$background = imagecolorallocate($img, rand(200, 255), rand(200, 255), rand(200, 255));

	//
	// Create background
	//
	for ($i = 0; $i < rand(5, 20); $i++){
		$color = imagecolorallocate($img, rand(0, 255), rand(0, 255), rand(0, 255));
		$loc_x = rand(-100, $width + 100);
		$loc_y = rand(-50, $height + 50);
		$end_x = rand(-100, $width + 100);
		$end_y = rand(-50, $height + 50);
		// Background shapes
		$shape = rand(1, 3);
		switch ($shape) {
			case 1:
				// Draw an ellipse
				imageellipse($img, $loc_x, $loc_y, rand(100, 400), rand(50, 400), $color);
				break;
			case 2:
				// Draw a rectangle
				imagerectangle($img, $loc_x, $loc_y, $end_x, $end_y, $color);
				break;
			case 3:
				// Draw a line
				imageline($img, $loc_x, $loc_y, $end_x, $end_y, $color);
				break;
		}
	}
	//
	// Compile image
	//
	$x_move = $width / (strlen($code) + 1);
	$current_pos = rand(0, $x_move / 2);
	
	$less_rotate = array('N', 'Z', '6', '9');
	for ($i = 0; $i < strlen($code); $i++){
		$size = rand(15, 18);
		$angle = (in_array($code[$i], $less_rotate)) ? rand(-15, 15) : rand(-30, 30);
		
		if ($i == 0){
			$x_pos = $current_pos + $x_move - $size;
		} else {
			$x_pos = $current_pos + rand($size + 1, $x_move);
		}

		$y_pos = $height/2 + rand(-1, 8);
		$current_pos = $x_pos;
		
		$fonts = glob($font_dir);
		$font = $fonts[array_rand($fonts)];
		$color = imagecolorallocate($img, rand(0, 127), rand(0, 127), rand(0, 127));
		
		if (!empty($font)){
			imagettftext($img, $size, $angle, $x_pos, $y_pos, $color, $font, $code[$i]);
		} else {
			imagestring($img, 5, $x_pos, $y_pos, $code[$i], $color);
		}
	}
 
	//
	// Create image
	//
	header("content-type: image/png");
	header("Cache-control: no-cache, no-store");
	imagepng($img);
	imagedestroy($img);
}
//--------------------------------------------------------------------------
//------------------------------------------------------------------------------
if (!function_exists('json_encode')) {
	function json_encode($data) {
		switch (gettype($data)) {
			case 'boolean':
				return $data ? 'true' : 'false';
			case 'integer':
			case 'double':
				return $data;
			case 'resource':
			case 'string':
				# Escape non-printable or Non-ASCII characters.
				# I also put the \\ character first, as suggested in comments on the 'addclashes' page.
				$json = '';

				$string = '"' . addcslashes($data, "\\\"\n\r\t/" . chr(8) . chr(12)) . '"';

				# Convert UTF-8 to Hexadecimal Codepoints.
				for ($i = 0; $i < strlen($string); $i++) {
					$char = $string[$i];
					$c1 = ord($char);

					# Single byte;
					if ($c1 < 128) {
						$json .= ($c1 > 31) ? $char : sprintf("\\u%04x", $c1);

						continue;
					}

					# Double byte
					$c2 = ord($string[++$i]);

					if (($c1 & 32) === 0) {
						$json .= sprintf("\\u%04x", ($c1 - 192) * 64 + $c2 - 128);

						continue;
					}

					# Triple
					$c3 = ord($string[++$i]);

					if (($c1 & 16) === 0) {
						$json .= sprintf("\\u%04x", (($c1 - 224) <<12) + (($c2 - 128) << 6) + ($c3 - 128));

						continue;
					}

					# Quadruple
					$c4 = ord($string[++$i]);

					if (($c1 & 8 ) === 0) {
						$u = (($c1 & 15) << 2) + (($c2 >> 4) & 3) - 1;

						$w1 = (54 << 10) + ($u << 6) + (($c2 & 15) << 2) + (($c3 >> 4) & 3);
						$w2 = (55 << 10) + (($c3 & 15) << 6) + ($c4 - 128);

						$json .= sprintf("\\u%04x\\u%04x", $w1, $w2);
					}
				}

				return $json;
			case 'array':
				if (empty($data) || array_keys($data) === range(0, sizeof($data) - 1)) {
					$output = array();

					foreach ($data as $value) {
						$output[] = json_encode($value);
					}

					return '[' . implode(',', $output) . ']';
				}
			case 'object':
				$output = array();

				foreach ($data as $key => $value) {
					$output[] = json_encode(strval($key)) . ':' . json_encode($value);
				}

				return '{' . implode(',', $output) . '}';
			default:
				return 'null';
		}
	}
}

if (!function_exists('json_decode')) {
	function json_decode($json, $assoc = false) {
		$match = '/".*?(?<!\\\\)"/';

		$string = preg_replace($match, '', $json);
		$string = preg_replace('/[,:{}\[\]0-9.\-+Eaeflnr-u \n\r\t]/', '', $string);

		if ($string != '') {
			return null;
		}

		$s2m = array();
		$m2s = array();

		preg_match_all($match, $json, $m);

		foreach ($m[0] as $s) {
			$hash = '"' . md5($s) . '"';
			$s2m[$s] = $hash;
			$m2s[$hash] = str_replace('$', '\$', $s);
		}

		$json = strtr($json, $s2m);

		$a = ($assoc) ? '' : '(object) ';

		$data = array(
			':' => '=>',
			'[' => 'array(',
			'{' => "{$a}array(",
			']' => ')',
			'}' => ')'
		);

		$json = strtr($json, $data);

		$json = preg_replace('~([\s\(,>])(-?)0~', '$1$2', $json);

		$json = strtr($json, $m2s);

		$function = @create_function('', "return {$json};");
		$return = ($function) ? $function() : null;

		unset($s2m);
		unset($m2s);
		unset($function);

		return $return;
	}
}
?>
