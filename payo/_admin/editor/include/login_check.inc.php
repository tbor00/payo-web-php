<?php
session_name($default->session_name);
session_start();
$session_error = "";
if ($_SESSION['logged'] != "yes") {
	$destruir_sesion = true;
	$sesion_error = "1";
} else {
	if($_SESSION['last_access'] < (time() - $_SESSION['timeout'])) {
		$destruir_sesion = true;
		$sesion_error = "2";
	} else {
		$_SESSION['last_access'] = time();
	}
}
if ($destruir_sesion){
	session_unset();
	session_destroy();
	if (basename($_SERVER['SCRIPT_NAME']) != 'index.php') {
		header("Location:../index.php?authen=failed&err=$sesion_error$urlaction");
	}
}
?>