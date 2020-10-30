<?php
// -------------------------------------------------------------
//  Definicion de variables Modificables
// -------------------------------------------------------------
$config['database']=$default->database;
$config['lineas']=$default->list_row;
$config['menu']="Productos c/Precio";
$config['tabla']="e_pproductos";
$config['join']="LEFT JOIN e_ofertas ON codigo=cod_prod";
$config['campos'][0] = array(
	'codigo'=>"C&oacute;digo",
	'descripcion'=>"Descripci&oacute;n",
	'marca'=>"Marca",
	'proveedor'=>"Proveedor",
	'imagen'=>"Imagen",
	'precio'=>"Precio",
	'precio2'=>"Precio Dist",
	'cod_prod'=>"",
	'fecha_baja'=>"",
);
$config['include_form']="templates/listar.inc.php";
$config['id']="codigo";
$config['default_order']="descripcion";
$config['default_ord'] = "";
$config['set_idioma']="";
$config['eorden']="";
$config['edit']="no";
$config['agregar']="no";
$config['delete']="yes";
$config[plugin]=array("pproductos_ofertas.inc.php");
$config['form_edit']="forms/productos.inc.php";
$config['listar_condicion']="";
$config['em_condicion']="codigo='$id'";
$config['eliminar_control']=array();
$config['modificar_control']=array();
$config['alta_control']=array();

// Para importar productos
$menu_extra_script_1 = "<SCRIPT LANGUAGE=\"JavaScript1.2\">\n";
$menu_extra_script_1 .= "function Importar_Productos(){\n";
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
$menu_extra_script_1 .= "var url = 'pproductos_import.php';\n";
$menu_extra_script_1 .= "window.open( url , 'importar',sPropsVentana);\n";
$menu_extra_script_1 .= "}\n";
$menu_extra_script_1 .= "</SCRIPT>\n";

$menu_extra_script_2 = "<SCRIPT LANGUAGE=\"JavaScript1.2\">\n";
$menu_extra_script_2 .= "function Eliminar_Productos(){\n";
$menu_extra_script_2 .= "var height = \"240\";\n";
$menu_extra_script_2 .= "var width = \"480\";\n";
$menu_extra_script_2 .= "var altoPantalla = window.screen.height;\n";
$menu_extra_script_2 .= "var anchoPantalla = window.screen.width;\n";
$menu_extra_script_2 .= "var sPropsVentana;\n";
$menu_extra_script_2 .= "var left = (anchoPantalla / 2) - (width / 2);\n";
$menu_extra_script_2 .= "var top = (altoPantalla / 2) - (height / 2);\n";
$menu_extra_script_2 .= "sPropsVentana  = 'width='+ width+ ',height=' + height;\n";
$menu_extra_script_2 .= "sPropsVentana += ',top=' + top + ',left=' + left;\n";
$menu_extra_script_2 .= "sPropsVentana += ',scrollbars=no,resizable=no';\n";
$menu_extra_script_2 .= "var url = 'pproductos_delete.php';\n";
$menu_extra_script_2 .= "window.open( url , 'eliminar',sPropsVentana);\n";
$menu_extra_script_2 .= "}\n";
$menu_extra_script_2 .= "</SCRIPT>\n";

$menu_extra_script_3 = "<SCRIPT LANGUAGE=\"JavaScript1.2\">\n";
$menu_extra_script_3 .= "function Imagenes_Productos(){\n";
$menu_extra_script_3 .= "var height = \"400\";\n";
$menu_extra_script_3 .= "var width = \"600\";\n";
$menu_extra_script_3 .= "var altoPantalla = window.screen.height;\n";
$menu_extra_script_3 .= "var anchoPantalla = window.screen.width;\n";
$menu_extra_script_3 .= "var sPropsVentana;\n";
$menu_extra_script_3 .= "var left = (anchoPantalla / 2) - (width / 2);\n";
$menu_extra_script_3 .= "var top = (altoPantalla / 2) - (height / 2);\n";
$menu_extra_script_3 .= "sPropsVentana  = 'width='+ width+ ',height=' + height;\n";
$menu_extra_script_3 .= "sPropsVentana += ',top=' + top + ',left=' + left;\n";
$menu_extra_script_3 .= "sPropsVentana += ',scrollbars=no,resizable=no';\n";
$menu_extra_script_3 .= "var url = 'pproductos_imagenes.php';\n";
$menu_extra_script_3 .= "window.open( url , 'imagenes',sPropsVentana);\n";
$menu_extra_script_3 .= "}\n";
$menu_extra_script_3 .= "</SCRIPT>\n";

$config['menu_extra'][1] = array( 
		"menu" =>"javascript:Imagenes_Productos();",
		'menu_extra_img' => "img/gif.gif",
		'menu_extra_tit' => "Imagenes",
		'menu_extra_script' => $menu_extra_script_3,
		);

$config['menu_extra'][0] = array( 
		"menu" =>"javascript:Importar_Productos();",
		'menu_extra_img' => "img/import.gif",
		'menu_extra_tit' => "Importar",
		'menu_extra_script' => $menu_extra_script_1,
		);

$config['menu_extra'][2] = array( 
		"menu" =>"javascript:Eliminar_Productos();",
		'menu_extra_img' => "img/delete.gif",
		'menu_extra_tit' => "Eliminar",
		'menu_extra_script' => $menu_extra_script_2,
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
	case 'om':{
		$glo_form_tit = $config['menu'] . " - Modificar Oferta";
		$config['op']="ocm";
		include('include/templates/form_header.inc.php');
		include("include/forms/pproductos_ofertas.inc.php");
		include('include/templates/form_footer.inc.php');
		break;
	}
	case 'ocm':{
		$glo_form_tit = $config['menu'] . " - Modificar Oferta";
		$config['campos'][1]=array(
			"oferta"=>$oferta[1],
			"fecha_alta"=>timestd2sql($fecha_alta[1]),
			"fecha_baja"=>timestd2sql($fecha_baja[1]),
		);
		$config['op']="ocm";
		$config[condicion]="cod_prod='$id'";
		$config['join']="";
		$config[tabla]='e_ofertas';
		if (modificar($config)){
			$config['condicion']=$config['listar_condicion'];
			$config['tabla']="e_pproductos";
			$config['join']="LEFT JOIN e_ofertas ON codigo=cod_prod";
			listar($config);
		}
		break;
	}
	case 'oa':{
		$glo_form_tit = $config['menu'] . " - Activar Oferta";
		$config['op']="oca";
		include('include/templates/form_header.inc.php');
		include("include/forms/pproductos_ofertas.inc.php");
		include('include/templates/form_footer.inc.php');
		break;
	}
	case 'oca':{
		$glo_form_tit = $config['menu'] . " - Activar Oferta";
		$config['campos'][1]=array(
			"cod_prod"=>$cod_prod[1],
			"oferta"=>$oferta[1],
			"fecha_alta"=>timestd2sql($fecha_alta[1]),
			"fecha_baja"=>timestd2sql($fecha_baja[1]),
			"id_oferta"=>"auto_increment",
		);
		$config['op']="ocm";
		$config[condicion]="cod_prod='$id'";
		$config['join']="";
		$config[tabla]='e_ofertas';
		if ($id=alta($config)){
			$config['tabla']="e_pproductos";
			$config['join']="LEFT JOIN e_ofertas ON codigo=cod_prod";
			$config['condicion']=$config['listar_condicion'];
			listar($config);
		}
		break;
	}
	case 'oce':{
		$config[condicion]="cod_prod='$id'";
		$config['join']="";
		$config[tabla]='e_ofertas';
		if (eliminar($config)){
			$config['tabla']="e_pproductos";
			$config['join']="LEFT JOIN e_ofertas ON codigo=cod_prod";
			$config['condicion']=$config['listar_condicion'];
			listar($config);
		}
		break;
	}

}//Fin del switch
?>
