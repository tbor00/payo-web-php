<?php
// -------------------------------------------------------------
//  Definicion de variables Modificables
// -------------------------------------------------------------
$config['database']=$default->database;
$config['lineas']=$default->list_row;
$config['menu']="Historial";
$config['tabla']="e_historial";
$config['join']="";
$config['campos'][0] = array(
	'marca'=>"Marca",
	'fecha'=>"Fecha",
	'observaciones'=>"",
	'id_hist'=>"",
);
$config['include_form']="templates/listar.inc.php";
$config['id']="id_hist";
$config['default_order']="fecha";
$config['default_ord'] = "DESC";
$config['set_idioma']="";
$config['eorden']="";
$config['edit']="yes";
$config['agregar']="yes";
$config['delete']="yes";
$config['form_edit']="forms/historial.inc.php";
$config['listar_condicion']="";
$config['em_condicion']="id_hist=$id";
$config['eliminar_control']="";
$config['modificar_control']=array();
$config['alta_control']=array();
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
			$config['campos'][$id_l]=array(
				"marca"=>$marca[1],
				"fecha"=>timestd2sql($fecha[1]),
				"observaciones"=>$observaciones[1],
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
			$config['campos'][$id_l]=array(
				"fecha"=>timestd2sql($fecha[1]),
				"marca"=>$marca[1],
				"observaciones"=>$observaciones[1],
			);
			$config['campos'][$id_l]["id_hist"]="auto_increment";
		}
		// -------------------------------------------------------------
		// -------------------------------------------------------------
		$config['control']=$config['alta_control'];
	 	$glo_form_tit = $config['menu'] . " - Altas";
    		$config['op']="ca";
    		$config['include_form_error']=$config['form_edit'];
    		if(controlar($config)){
			if ($id=alta($config)){
				$config['condicion']=$config['listar_condicion'];
				listar($config);
			}
		}
		break;
	}
}//Fin del switch
?>