<?php
// -------------------------------------------------------------
//  Definicion de variables Modificables
// -------------------------------------------------------------
$config['database']=$default->database;
$config['lineas']=$default->list_row;
$config['menu']="Slide Banners";
$config['tabla']="e_carrousel";
$config['join']="";
$config['campos'][0] = array(
   'id_carrousel'=>"",
	'titulo'=>"T&iacute;tulo",
	'imagen'=>"",
	'url'=>"",
	"publico"=>"",
	'posicion'=>"Posici&oacute;n",
	'nivel'=>"",
	'binario'=>array(
		nombre=>"Activo",
		campo=>activo
	),
);
$config['include_form']="templates/listar.inc.php";
$config['id']="id_carrousel";
$config['default_order']="titulo";
$config['default_ord'] = "";
$config['set_idioma']=$default->set_idioma;
$config['eorden']="";
$config['edit']="yes";
$config['agregar']="yes";
$config['delete']="yes";
$config['form_edit']="forms/carrousel.inc.php";
$config['listar_condicion']="";
$config['em_condicion']="id_carrousel=$id";
$config['eliminar_control']=array();
$config['modificar_control']=array('e_carrousel'=>"titulo='$titulo[1]' AND id_carrousel!='$id'");
$config['alta_control']=array('e_carrousel'=>"titulo='$titulo[1]'");

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
			if ($posicion[$id_l]==""){
				$posicion[$id_l]="0";
			}
			if ($publico[$id_l]==""){
				$publico[$id_l]="0";
			}
			$config['campos'][$id_l]=array(
				"titulo"=>$titulo[$id_l],
				"imagen"=>$imagen[$id_l],
				"url"=>$url[$id_l],
				"nivel"=>$nivel[$id_l],
				"posicion"=>$posicion[$id_l],
				"activo"=>$activo[$id_l],
				"publico"=>$publico[$id_l],
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
					$sourcepic = "../carrousel/imagenes/". trim($imagen);
					$thumb_res="300x300";
					$thumb_quality="80";
					$destpic = "../carrousel/miniaturas/".strtolower($partes_ruta['filename'])."-".$thumb_res.".".strtolower($partes_ruta['extension']);
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
			if ($posicion[$id_l]==""){
				$posicion[$id_l]="0";
			}
			if ($publico[$id_l]==""){
				$publico[$id_l]="0";
			}

			$config['campos'][$id_l]=array(
				"titulo"=>$titulo[$id_l],
				"imagen"=>$imagen[$id_l],
				"url"=>$url[$id_l],
				"nivel"=>$nivel[$id_l],
				"posicion"=>$posicion[$id_l],
				"activo"=>$activo[$id_l],
				"publico"=>$publico[$id_l],
			);
			$config['campos'][$id_l]["id_carrousel"]="auto_increment";
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
					$thumb_res="300x300";
					$thumb_quality="80";
					$sourcepic = "../carrousel/imagenes/". trim($imagen);
					$destpic = "../carrousel/miniaturas/".strtolower($partes_ruta['filename'])."-".$thumb_res.".".strtolower($partes_ruta['extension']);
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
