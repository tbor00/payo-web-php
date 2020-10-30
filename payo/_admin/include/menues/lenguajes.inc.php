<?php
// -------------------------------------------------------------
//  Definicion de variables Modificables
// -------------------------------------------------------------
$config['database']=$default->database;
$config['lineas']=$default->list_row;
$config['menu']="Lenguajes";
$config['tabla']="lenguajes";
$config['join']="";
$config['campos'][0] = array(
   'id_lenguaje'=>"",
  	'lenguaje'=>"Lenguaje",
   'lng'=>"",
	'binario'=>array(
		nombre=>Activo,
		campo=>activo
	),
);
$config['include_form']="templates/listar.inc.php";
$config['id']="id_lenguaje";
$config['default_order']="lenguaje";
$config['default_ord'] = "";
$config['set_idioma']="";
$config['eorden']="";
$config['edit']="yes";
$config['agregar']="";
$config['delete']="";
$config['form_edit']="forms/lenguajes.inc.php";
$config['listar_condicion']="";
$config['em_condicion']="id_lenguaje=$id";
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
			if ($activo[1]==""){
				$activo[1]="0";
			}
			$config['campos'][$id_l]=array(
				"lenguaje"=>$lenguaje[$id_l],
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
				$config['condicion']=$config['listar_condicion'];
				listar($config);
			}
		}
		break;
	}
}//Fin del switch
?>

