<?php
// -------------------------------------------------------------
//  Definicion de variables Modificables
// -------------------------------------------------------------
$config['database']=$default->database;
$config['lineas']=$default->list_row;
$config['menu']="Tel&eacute;fonos";
$config['tabla']="e_telefonos";
$config['join']="";
$config['campos'][0] = array(
    'nombre'=>"Nombre",
    'telefono'=>"Tel&eacute;fono",
    'id_telefono'=>"",
    'id_seccion'=>"",
    'wapp'=>"",
    );
$config['include_form']="templates/listar.inc.php";
$config['id']="id_telefono";
$config['default_order']="nombre";
$config['default_ord'] = "";
$config['set_idioma']="no";
$config['eorden']="";
$config['edit']="yes";
$config['agregar']="yes";
$config['delete']="yes";
$config['form_edit']="forms/telefonos.inc.php";
$config['listar_condicion']="1";
$config['em_condicion']="id_telefono=$id";
$config['eliminar_control']=array();
$config['modificar_control']=array('e_telefonos'=>"nombre='$nombre[1]' AND id_telefono!='$id'");
$config['alta_control']=array('e_telefonos'=>"(e_telefonos.nombre='$nombre[1]')" );

// -------------------------------------------------------------
// Fin de Definicion de variables
// -------------------------------------------------------------

include("include/templates/browse_header.inc.php");

switch($op){
	case 'l':{
		$config['condicion']=$config['listar_condicion'];
		listar($config);
		break;
	}
	case 'e':{
		$config['control']=$config['eliminar_control'];
		$config['titulo']="Atenci&oacute;n";
		$config['mensaje']="Imposible eliminar el usuario.";
		$config['op']='l';
		if (controlar($config)){
			$config['campos'][0]=array($config['default_order']);
			$config['titulo']=$config['menu']." - Eliminar";
			$config['condicion']=$config['em_condicion'];
			$config['mensaje']=" Esta seguro que desea eliminar a";
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
			if ($wapp[$id_l]==""){
				$wapp[$id_l]="0";
			}
			$config['campos'][$id_l]=array(
		 		"nombre"=>$nombre[1],
		 		"telefono"=>$telefono[1],
				"wapp"=>$wapp[$id_l],
				"id_seccion"=>$id_seccion[$id_l],
			);
		}
		// -------------------------------------------------------------
		// -------------------------------------------------------------
		$glo_form_tit = $config['menu'] . " - Modificaciones";
		$config['op']="cm";
		$config['include_form_error']=$config['form_edit'];
		$config['condicion']=$config['em_condicion'];
		$config['control']=$config['modificar_control'];
		if(controlar($config)){
			if (modificar($config)){
				$config['condicion']=$config['listar_condicion'];
				listar($config);
			} else {
				Echo_Error(ERROR_DB_FAILTRANS);
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
			if ($wapp[$id_l]==""){
				$wapp[$id_l]="0";
			}
			$config['campos'][$id_l]=array(
		 		"nombre"=>$nombre[1],
		 		"telefono"=>$telefono[1],
				"wapp"=>$wapp[$id_l],
				"id_seccion"=>$id_seccion[$id_l],
			);
			$config['campos'][$id_l]["id_telefono"]="auto_increment";

		}
		// -------------------------------------------------------------
		// -------------------------------------------------------------
		$config['control']=$config['alta_control'];
		$glo_form_tit = $config['menu'] . " - Altas";
		$config['op']="ca";	
		$config['include_form_error']=$config['form_edit'];
		if(controlar($config)){
			if ($id = alta($config)){
				$config['condicion']=$config['listar_condicion'];
				listar($config);
			} else {
				Echo_Error(ERROR_DB_FAILTRANS);
			}
		}
		break;	
	}
}//Fin del switch
?>
