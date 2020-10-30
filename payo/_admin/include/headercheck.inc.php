<?php
session_name($default->session_name);
session_start();

$session_error = "";
if ($authen == 'login'){
	if(isset($_SESSION[challenge]) && $_SESSION[challenge]!="") {
		$challenge = $_SESSION[challenge];
		unset($_SESSION[challenge]);
	} else {
		$challenge="";
	}
	if (!checkuser($login,$passwd,$challenge)){
		$authen = "failed";
		$destruir_sesion = true;
		$session_error = "1";
	}
} elseif ($authen=="logout"){
	$destruir_sesion = true;
}

if (!$destruir_sesion) {
	if ($_SESSION['logged'] == "yes") {
	//	$destruir_sesion = true;
	//	$session_error = "1";
	//} else {
		if($_SESSION['last_access'] < (time() - $_SESSION['timeout'])) {
			$authen = "failed";
			$destruir_sesion = true;
			$session_error = "2";
		} else {
			$GLOBALS['USERID'] =& $_SESSION['uid'];
			$_SESSION['last_access'] = time();
		}
	}
}

if ($destruir_sesion){
	session_defaults();
	session_unset();
	if (basename($_SERVER['SCRIPT_NAME']) != 'index.php') {
		header("Location:index.php?authen=failed&err=$session_error$urlaction");
	}
} else {
	/*
	$default->menuadmin();
	if ($accion!=""){
		if (!in_array($accion, $default->permisos)){
			$accion="no";
		}
	}
	*/
}
?>

