<?php
require_once('include/core.lib.php');
include('include/headercheck.inc.php');
if ($authen=='failed') {
	if (isset($session_error) && $session_error != ""){
		$err = $session_error;
	}
	if ($err == 1 ) {
		$loc_errormessage = convert_str("Error de autenticación.",$default->encode);
	} elseif ($err == 2 ) {
		$loc_errormessage = convert_str("La sesión ha expirado.\\nDebe autenticarse nuevamente.",$default->encode);
	} elseif ($err == 3 ) {
		$loc_errormessage = convert_str("El usuario no cuenta con suficientes privilegios \\n para utilizar esta funcionalidad del sistema.",$default->encode);
	} else {
		$loc_errormessage = convert_str("Error de autenticación.",$default->encode);
	}	
	$loc_onload = "javascript:alert('$loc_errormessage')";
} else {
	$loc_onload = "";
}
$glo_onload = $loc_onload;
include('include/templates/header.inc.php');
if($_SESSION["logged"] == "yes"){
	include('include/templates/menu_admin.inc.php');
	if ($accion != ""){
		if(file_exists("include/menues/$accion.inc.php")){
			include("include/menues/$accion.inc.php");
		} else {
			Echo_Error(ERROR_INC_NOTFOUND);
		}
	}
} else {
	$_SESSION["challenge"] = md5 (uniqid (rand()));
	include('include/templates/login_form.inc.php');
}
include('include/templates/footer.inc.php');
?>
