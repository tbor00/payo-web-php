<?php 

if ($_SESSION['logged']){
	$destiny=1;
} else {
	$destiny=0;
}

$query = "SELECT * from e_menues where tipo=1 and activo=1 and destino<=$destiny order by posicion";
$result = $db->Execute($query);
if ($result && !$result->EOF){
	while(!$result->EOF){
		$menu_array[] = array(
			'href'=>$_SERVER['PHP_SELF']."?op=".$result->fields['id_menu'].$result->fields['ejecutar'],
			'titulo'=>$result->fields['titulo'],
		);
		$result->MoveNext();
	}
}
if ( sizeof($menu_array) > 0 ) {
?>

<?php
}
echo "<div class=\"container\" style=\"	min-height: 650px !important;\">\n";
if ($default->mantenimiento) {
	if (file_exists("include/templates/manteinance.inc.php")){
		include("include/templates/manteinance.inc.php");
	}
} else {
	if (is_array($a_alert)){
		for ($i=0; $i<sizeof($a_alert); $i++) {
			echo "<div class=\"alert alert-danger\"><i class=\"fa fa-exclamation-circle\"></i>".$a_alert[$i]."</div>\n";
		}
	}
	if (is_numeric($op) && $op>0) {
		if (!isset($op) || $op==""){
			$op=0;
		}
		
		if ($sectionname!=""){
			$op=search_op($sectionname);
		}
		
		if ($sop != 0){
			$n_op= $sop;
		} else {
			$n_op = $op;
		}
	
		if ($n_op==0 || $n_op==""){
		  $n_op = 1;
		}
	
	
		$texto = display_text($n_op);
	} elseif ($op<>"") {
		if ($op=='profile' && $_SESSION['logged']){
			if (file_exists("include/templates/eprofile.inc.php")){
				include("include/templates/eprofile.inc.php");
			}
		}
		if ($op=='pedidos' && $_SESSION['logged']){
			if (file_exists("include/templates/cotizaciones.inc.php")){
				include("include/templates/cotizaciones.inc.php");
			}
		}
		if ($op=='productos' && $_SESSION['logged']){
			if (file_exists("include/templates/view_productos.inc.php")){
				include("include/templates/view_productos.inc.php");
			}
		}
	
	} else {
		if ($auth=="registration"){
			include('include/templates/registration.inc.php');
		} elseif ($auth=="forgot"){
			include('include/templates/userforgotpass.inc.php');
		} elseif ($auth=="login" && !$_SESSION['logged']){
				include('include/templates/login_form.inc.php');
		} else {
			//if (!$_SESSION['logged']){
			//	include('include/templates/login_form.inc.php');
			//} else {
				$texto = display_text(1);
			//}
		}
	}
}
echo "</div>\n";
?>

