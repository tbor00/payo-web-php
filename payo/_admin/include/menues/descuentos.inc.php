<?php
// -------------------------------------------------------------
//  Definicion de variables Modificables
// -------------------------------------------------------------
$config['database']=$default->database;
$config['lineas']=$default->list_row;
$config['menu']="Descuentos";
$config['tabla']="e_descuentos";
$config['join']="";
$config['campos'][0] = array(
	'leyenda'=>"Descuento",
	'id_descuento'=>"",
	'nivel'=>"",
	'porcentaje'=>"",
	'tarjeta'=>"",
	'cuotas'=>"",
	'imagen'=>"",
	'informacion'=>"",
	'binario'=>array(
		nombre=>"Activo",
		campo=>activo
		),
);
$config['include_form']="templates/listar.inc.php";
$config['id']="id_descuento";
$config['default_order']="leyenda";
$config['default_ord'] = "";
$config['set_idioma']="";
$config['eorden']="";
$config['edit']="yes";
$config['agregar']="yes";
$config['delete']="yes";
$config['form_edit']="forms/descuentos.inc.php";
$config['listar_condicion']="";
$config['em_condicion']="id_descuento=$id";
$config['eliminar_control']=array();
$config['modificar_control']=array('e_descuentos'=>"(leyenda='$leyenda[1]') AND id_descuentos!='$id'");
$config['alta_control']=array('e_descuentos'=>"(leyenda='$leyenda[1]')");
// -------------------------------------------------------------
// Fin de Definicion de variables
// -------------------------------------------------------------

include('include/templates/browse_header.inc.php');

switch($op){
	case 'l':{
		$config['condicion']=$config['listar_condicion'];
		listar($config);
		break;
	}
	case 'e':{
		$config['control']=$config['eliminar_control'];
		$config['titulo']="Atenci&oacute;n";
		$config['mensaje']="Los datos que desea eliminar est&aacute;n<BR>siendo utilizados ";
		$config['op']= "l";
		if (controlar($config)){
			$config['campos'][0]=array($config['default_order']);
			$config['titulo']=$config['menu']." - Eliminar";
			$config['condicion']=$config['em_condicion'];
			$config['mensaje']=" Esta seguro que desea eliminar ";
			$config['op']= "ce";
			conf_eliminar($config);
		}
		break;
	}
	case 'ce':{
		$config['condicion']=$config['em_condicion'];
		if (eliminar($config)){
			$config['condicion']=$config['listar_condicion'];
			listar($config);
		}
		break;
	}
	case 'm':{
		$glo_form_tit = $config['menu'] . " - Modificaciones";
		$config['op']="cm";
		include('include/templates/form_header.inc.php');
		$config['include_form']=$config['form_edit'];
		$config['condicion']=$config['em_condicion'];
		listar_modificaciones($config);
		include('include/templates/form_footer.inc.php');
		break;
	}
	case 'cm':{
		// -------------------------------------------------------------
		// Datos Modificables
		// -------------------------------------------------------------
		foreach ($default->lenguajes() as $id_l){
			if ($activo==''){
				$activo[1]=0;
			}
			if ($tarjeta==''){
				$tarjeta[1]=0;
			}
			$config['campos'][$id_l]=array(
				"leyenda"=>$leyenda[1],
				"porcentaje"=>$porcentaje[1],
				"tarjeta"=>$tarjeta[1],
				"cuotas"=>$cuotas[1],
				"nivel"=>$nivel[1],
				"imagen"=>$imagen[$id_l],				
				"informacion"=>$informacion[$id_l],				
				"activo"=>$activo[1],
			);
		}
		// -------------------------------------------------------------
		// -------------------------------------------------------------
		$config['control']=$config['modificar_control'];
	 	$glo_form_tit = $config['menu'] . " - Modificaciones";
    	$config['op']="cm";
    	$config['include_form_error']=$config['form_edit'];
		$config['condicion']=$config['em_condicion'];
    	if(controlar($config)){
			if (modificar($config)){
				$imagen = $imagen[1];
				$partes_ruta = pathinfo($imagen);
				if ($imagen != "" && $chimagen == 1){
					$sourcepic = "../descuentos/imagenes/". trim($imagen);
					$destpic = "../descuentos/miniaturas/".strtolower($partes_ruta['filename'])."-130x200.".strtolower($partes_ruta['extension']);
					$thumb_res="130x120";
					$thumb_quality="80";
					if (preg_match("/(.jpg|.jpeg)$/i",$imagen)) {
						$typeimg="jpg";
					} elseif (preg_match("/.png$/i",$imagen)) {
						$typeimg="png";
					} elseif (preg_match("/.gif$/i",$imagen)) {
						$typeimg="gif";
					} 
					convert_image($sourcepic, $destpic, $thumb_res, $thumb_quality, $typeimg);
				}

				$config['condicion']=$config['listar_condicion'];
				listar($config);
			}
		}
		break;
	}
	case 'a':{
		$glo_form_tit = $config['menu'] . " - Altas";
		$config['op']="ca";	
		include("include/templates/form_header.inc.php");
		include("include/".$config['form_edit']);
		include("include/templates/form_footer.inc.php");
		break;
	}
	case 'ca':{
		// -------------------------------------------------------------
		// Datos Modificables
		// -------------------------------------------------------------
		foreach ($default->lenguajes() as $id_l){
			if ($activo[1]==''){
				$activo[1]=0;
			}
			if ($tarjeta==''){
				$tarjeta[1]=0;
			}
			$config['campos'][$id_l]=array(
				"leyenda"=>$leyenda[1],
				"porcentaje"=>$porcentaje[1],
				"tarjeta"=>$tarjeta[1],
				"cuotas"=>$cuotas[1],
				"nivel"=>$nivel[1],
				"imagen"=>$imagen[$id_l],				
				"informacion"=>$informacion[$id_l],				
				"activo"=>$activo[1],
			);
			$config['campos'][$id_l]["id_descuento"]="auto_increment";
		}
		// -------------------------------------------------------------
		// -------------------------------------------------------------
		$config['control']=$config['alta_control'];
	 	$glo_form_tit = $config['menu'] . " - Altas";
    	$config['op']="ca";
    	$config['include_form_error']=$config['form_edit'];
    	if(controlar($config)){
			if ($id=alta($config)){
				$imagen = $imagen[1];
				$partes_ruta = pathinfo($imagen);
 				if ($imagen != "" ){
					$sourcepic = "../descuentos/imagenes/". trim($imagen);
			   	$destpic = "../descuentos/miniaturas/".strtolower($partes_ruta['filename'])."-130x200.".strtolower($partes_ruta['extension']);
					$thumb_res="130x120";
					$thumb_quality="80";
					if (preg_match("/(.jpg|.jpeg)$/i",$imagen)) {
						$typeimg="jpg";
					} elseif (preg_match("/.png$/i",$imagen)) {
						$typeimg="png";
					} elseif (preg_match("/.gif$/i",$imagen)) {
						$typeimg="gif";
					} 
					convert_image($sourcepic, $destpic, $thumb_res, $thumb_quality, $typeimg);
				}

				$config['condicion']=$config['listar_condicion'];
				listar($config);
			}
		}
		break;
	}
}//Fin del switch
?>

