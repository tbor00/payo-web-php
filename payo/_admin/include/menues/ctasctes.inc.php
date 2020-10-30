<?php
// -------------------------------------------------------------
//  Definicion de variables Modificables
// -------------------------------------------------------------
$config['database']=$default->database;
$config['lineas']=$default->list_row;
$config['menu']="Movimientos";
$config['tabla']="e_ctasctes";
$config['join']=""; //"LEFT JOIN e_ofertas ON codigo=cod_prod";
$config['campos'][0] = array(
	'fecha'=>"Fecha",
	'comprobante'=>"Comprobante",
	'numero'=>"Numero",
	'credito'=>"Credito",
	'debito'=>"Debito",
);
$config['include_form']="templates/listar.inc.php";
$config['id']="codigo";
$config['default_order']="fecha";
$config['default_ord'] = "";
$config['set_idioma']="";
$config['eorden']="";
$config['edit']="no";
$config['agregar']="no";
$config['delete']="no";
$config[plugin]=array();
$config['form_edit']="";
$config['listar_condicion']="";
$config['em_condicion']="id='$id'";
$config['eliminar_control']=array();
$config['modificar_control']=array();
$config['alta_control']=array();

// Para importar productos
$menu_extra_script_1 = "<SCRIPT LANGUAGE=\"JavaScript1.2\">\n";
$menu_extra_script_1 .= "function Importar_CtasCtes(){\n";
$menu_extra_script_1 .= "var height = \"240\";\n";
$menu_extra_script_1 .= "var width = \"480\";\n";
$menu_extra_script_1 .= "var altoPantalla = window.screen.height;\n";
$menu_extra_script_1 .= "var anchoPantalla = window.screen.width;\n";
$menu_extra_script_1 .= "var sPropsVentana;\n";
$menu_extra_script_1 .= "var left = (anchoPantalla / 2) - (width / 2);\n";
$menu_extra_script_1 .= "var top = (altoPantalla / 2) - (height / 2);\n";
$menu_extra_script_1 .= "sPropsVentana  = 'width='+ width+ ',height=' + height;\n";
$menu_extra_script_1 .= "sPropsVentana += ',top=' + top + ',left=' + left;\n";
$menu_extra_script_1 .= "sPropsVentana += ',scrollbars=no,resizable=no';\n";
$menu_extra_script_1 .= "var url = 'ctasctes_import.php';\n";
$menu_extra_script_1 .= "window.open( url , 'importar',sPropsVentana);\n";
$menu_extra_script_1 .= "}\n";
$menu_extra_script_1 .= "</SCRIPT>\n";


$config['menu_extra'][0] = array( 
		"menu" =>"javascript:Importar_CtasCtes();",
		'menu_extra_img' => "img/import.gif",
		'menu_extra_tit' => "Importar",
		'menu_extra_script' => $menu_extra_script_1,
		);


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
}//Fin del switch
?>
