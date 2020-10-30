<?php
// -------------------------------------------------------------
//  Definicion de variables Modificables
// -------------------------------------------------------------
$config['database']=$default->database;
$config['lineas']=$default->list_row;
$config['menu']="Novedades";
$config['tabla']="e_novedades";
$config['join']="";
$config['campos'][0] = array(
   'id_novedad'=>"",
	'titulo'=>"T&iacute;tulo",
	'imagen'=>"",
	'img_align'=>"",
	'img_epigrafe'=>"",
	'sumario'=>"",
	'novedad'=>"",
	'destino'=>"",
	'fecha_inicio'=>"Publicado",
	'fecha_fin'=>"Caduca",
	'binario'=>array(
		nombre=>"Activo",
		campo=>activo
	),
);
$config['include_form']="templates/listar.inc.php";
$config['id']="id_novedad";
$config['default_order']="fecha_inicio";
$config['default_ord'] = "";
$config['set_idioma']=$default->set_idioma;
$config['eorden']="";
$config['edit']="yes";
$config['agregar']="yes";
$config['delete']="yes";
$config['form_edit']="forms/novedades.inc.php";
$config['listar_condicion']="";
$config['em_condicion']="id_novedad=$id";
$config['eliminar_control']=array();
$config['modificar_control']=array('e_novedades'=>"titulo='$titulo[1]' AND id_novedad!='$id'");
$config['alta_control']=array('e_novedades'=>"titulo='$titulo[1]'");

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
		foreach ($default->lenguajes($config['set_idioma']) as $id_l){
			if ($activo[$id_l]==""){
				$activo[$id_l]="0";
			}
			$config['campos'][$id_l]=array(
				"titulo"=>$titulo[$id_l],
				"imagen"=>$imagen[$id_l],
				"img_align"=>$img_align[$id_l],
				"img_epigrafe"=>$img_epigrafe[$id_l],
				"sumario"=>$sumario[$id_l],
				"novedad"=>$novedades[$id_l],
				"fecha_inicio"=>timestd2sql($fecha_inicio[1]),
				"fecha_fin"=>timestd2sql($fecha_fin[1]),
				"destino"=>$destino[$id_l],
				"enviada"=>'0',
				"activo"=>$activo[$id_l],
				"id_lenguaje"=>"$id_l",
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
				if ($imagen != "" && $chimagen == 1){
					$sourcepic = "../novedades/imagenes/". trim($imagen);
			   	$destpic = "../novedades/miniaturas/". trim($imagen);
					$thumb_res="150x150";
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
		foreach ($default->lenguajes($config['set_idioma']) as $id_l){
			if ($activo[$id_l]==""){
				$activo[$id_l]="0";
			}
			$config['campos'][$id_l]=array(
				"titulo"=>$titulo[$id_l],
				"imagen"=>$imagen[$id_l],
				"img_align"=>$img_align[$id_l],
				"img_epigrafe"=>$img_epigrafe[$id_l],
				"sumario"=>$sumario[$id_l],
				"novedad"=>$novedades[$id_l],
				"fecha_inicio"=>timestd2sql($fecha_inicio[1]),
				"fecha_fin"=>timestd2sql($fecha_fin[1]),
				"destino"=>$destino[$id_l],
				"enviada"=>'0',
				"activo"=>$activo[$id_l],
				"id_lenguaje"=>"$id_l",
			);
			$config['campos'][$id_l]["id_novedad"]="auto_increment";
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
				if ($imagen != "" ){
					$sourcepic = "../novedades/imagenes/". trim($imagen);
			   	$destpic = "../novedades/miniaturas/". trim($imagen);
					$thumb_res="150x150";
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
