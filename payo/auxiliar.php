<?php 
require('include/site.lib.php');
session_name($default->session_name);
session_start();
require_once('include/translate.inc.php');
require_once('include/users.lib.php');
require_once('include/excel2/Classes/PHPExcel.php');
//require_once('include/excel/Worksheet.php');
//require_once('include/excel/Workbook.php');
require('include/phpmailer/class.phpmailer.php');

if ($_POST['op']!=''){
	$op = $_POST['op'];			  
} elseif ($_GET['op']!=''){
	$op = $_GET['op'];
}
if ($_SESSION['logged']){
	if ($op=='productos'){
		if (file_exists("include/templates/view_productos.inc.php")){
			include("include/templates/view_productos.inc.php");
		}
	} elseif ($op=='gencaptcha'){
		e_randomIMG();
	} elseif ($op=='xls_list'){
		if (file_exists("include/templates/xls_productos.inc.php")){
			include("include/templates/xls_productos.inc.php");
		}
	} elseif ($op=='print'){
		if (file_exists("include/templates/print_productos.inc.php")){
			include("include/templates/print_productos.inc.php");
		}
	} elseif ($op=='cprint'){
		if (file_exists("include/templates/print_ctacte.inc.php")){
			include("include/templates/print_ctacte.inc.php");
		}
	} elseif ($op=='caprint'){
		if (file_exists("include/templates/print_ctasacobrar.inc.php")){
			include("include/templates/print_ctasacobrar.inc.php");
		}
	} elseif ($op=='oprint'){
		if (file_exists("include/templates/print_cotizacion.inc.php")){
			include("include/templates/print_cotizacion.inc.php");
		}
	} elseif ($op=='oxls_list'){
		if (file_exists("include/templates/xls_cotizacion.inc.php")){
			include("include/templates/xls_cotizacion.inc.php");
		}
	} elseif ($op=='procesar'){
		if (file_exists("include/templates/procesar.inc.php")){
			include("include/templates/procesar.inc.php");
		}
	} elseif ($op=='cc_payment'){
		if (file_exists("include/templates/cc_payment.inc.php")){
			include("include/templates/cc_payment.inc.php");                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                  
		}
	} elseif ($op=='cot_payment'){
		if (file_exists("include/templates/cot_payment.inc.php")){
			include("include/templates/cot_payment.inc.php");                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                  
		}
	} elseif ($op=='cuotas'){
		if (file_exists("include/templates/cuotas.inc.php")){
			include("include/templates/cuotas.inc.php");
		}
	} elseif ($op=='phones'){
		if (file_exists("include/templates/phones.inc.php")){
			include("include/templates/phones.inc.php");
		}
	}
} else {
	if ($op=='productos'){
		if (file_exists("include/templates/view_productos.inc.php")){
			include("include/templates/view_productos.inc.php");
		}
	} elseif ($op=='gencaptcha'){
		e_randomIMG();
	} elseif ($op=='phones'){
		if (file_exists("include/templates/phones.inc.php")){
			include("include/templates/phones.inc.php");
		}
	}
}	
?>
