<?php
if ($_POST['cotizacion']!=''){
	$cotizacion = rawurldecode($_POST['cotizacion']);			  
} elseif ($_GET['cotizacion']!=''){
	$cotizacion = rawurldecode($_GET['cotizacion']);
}
if ($_POST['cotizanum']!=''){
	$cotizanum = rawurldecode($_POST['cotizanum']);			  
} elseif ($_GET['cotizanum']!=''){
	$cotizanum = rawurldecode($_GET['cotizanum']);
}

if (!is_numeric($cotizanum)){
	$cotizanum=0;
	$action='';
	$cotizacion='';
}

if ($_POST['itemnum']!=''){
	$itemnum = rawurldecode($_POST['itemnum']);			  
	if (!is_numeric($itemnum)){
		$cotizanum=0;
		$action='';
		$cotizacion='';
		$itemnum=0;
	}
} elseif ($_GET['itemnum']!=''){
	$itemnum = rawurldecode($_GET['itemnum']);
	if (!is_numeric($itemnum)){
		$cotizanum=0;
		$action='';
		$cotizacion='';
		$itemnum=0;
	}
}


$qlineas = 20;
$id_usuario=$_SESSION['uid'];

if ($_SESSION['logged']){
	if ($cotizacion=="show"){
		if ($action=="send"){
			include("enviar_cotizacion.inc.php");
		}
		include("ver_cotizacion.inc.php");
	} else {
		if ($action=="draft"){
			include("guardar_cotizacion.inc.php");
		}	
		include("listar_cotizaciones.inc.php");
	}
} 
?>
<P><P>
