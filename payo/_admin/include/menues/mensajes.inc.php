<?php
// -------------------------------------------------------------
//  Definicion de variables Modificables
// -------------------------------------------------------------
$config['database']=$default->database;
$config['lineas']=$default->list_row;
$config['menu']="Mensajes del Sistema";
$config['tabla']="e_secciones";
$config['join']="";
$config['campos'][0] = array(
   'id_seccion'=>"",
  	'seccion'=>"T&iacute;tulo",
	'codigo'=>"",
	'texto'=>"",
	'id_lenguaje'=>"",
);
$config['include_form']="templates/listar.inc.php";
$config['id']="id_seccion";
$config['default_order']="seccion";
$config['default_ord'] = "";
$config['set_idioma']=$default->set_idioma;
$config['eorden']="";
$config['edit']="yes";
$config['agregar']="";
$config['delete']="";
$config['form_edit']="forms/mensajes.inc.php";
$config['listar_condicion']="";
$config['em_condicion']="id_seccion=$id";
$config['eliminar_control']="";
$config['modificar_control']="";
$config['alta_control']="";

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
			$config['campos'][$id_l]=array(
				"texto"=>StripUri($texto[$id_l]),
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
				$config['condicion']=$config['listar_condicion'];
				listar($config);
			}
		}
		break;
	}
}//Fin del switch
?>

