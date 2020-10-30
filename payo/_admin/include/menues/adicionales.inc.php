<?php
// -------------------------------------------------------------
//  Definicion de variables Modificables
// -------------------------------------------------------------
$config['database']=$default->database;
$config['lineas']=$default->list_row;
$config['menu']="Informaci&oacute;n Adicional";
$config['tabla']="e_adicionales";
$config['join']="";
$config['campos'][0] = array(
   'id_adicional'=>"",
  	'titulo'=>"T&iacute;tulo",
	'texto_up'=>"",
	'inclusion'=>"",
	'texto_down'=>"",
	'binario'=>array(
		nombre=>Activo,
		campo=>activo
	),
	'lenguaje_id'=>"",
	'hidetitle'=>"",
	'destino'=>"",
);
$config['include_form']="templates/listar.inc.php";
$config['id']="id_adicional";
$config['default_order']="titulo";
$config['default_ord'] = "";
$config['set_idioma']=$default->set_idioma;
$config['eorden']="";
$config['edit']="yes";
$config['agregar']="yes";
$config['delete']="yes";
$config['form_edit']="forms/adicionales.inc.php";
$config['listar_condicion']="";
$config['em_condicion']="id_adicional=$id";
$config['eliminar_control']=array('e_menu_adic'=>"e_menu_adic.adicional_id='$id' AND e_menu_adic.lenguaje_id='$idioma'");
$config['modificar_control']=array('e_adicionales'=>"titulo='$titulo[1]' AND id_adicional!='$id'");
$config['alta_control']=array('e_adicionales'=>"titulo='$titulo[1]'");

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
			if ($hidetitle[$id_l]==""){
				$hidetitle[$id_l]="0";
			}
			if ($destino[$id_l]==""){
				$destino[$id_l]="0";
			}
			$config['campos'][$id_l]=array(
				"titulo"=>$titulo[$id_l],
				"texto_up"=>StripUri($texto_up[$id_l]),
				"inclusion"=>$inclusion[$id_l],
				"texto_down"=>StripUri($texto_down[$id_l]),
				"activo"=>$activo[$id_l],
				"hidetitle"=>$hidetitle[$id_l],
				"destino"=>$destino[$id_l],
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
		foreach ($default->lenguajes($config['set_idioma']) as $id_l){
			if ($activo[$id_l]==""){
				$activo[$id_l]="0";
			}
			if ($hidetitle[$id_l]==""){
				$hidetitle[$id_l]="0";
			}
			if ($destino[$id_l]==""){
				$destino[$id_l]="0";
			}
			$config['campos'][$id_l]=array(
				"titulo"=>$titulo[$id_l],
				"texto_up"=>StripUri($texto_up[$id_l]),
				"inclusion"=>$inclusion[$id_l],
				"texto_down"=>StripUri($texto_down[$id_l]),
				"activo"=>$activo[$id_l],
				"hidetitle"=>$hidetitle[$id_l],
				"destino"=>$destino[$id_l],
			);
			$config['campos'][$id_l]["lenguaje_id"]=$id_l;
			$config['campos'][$id_l]["id_adicional"]="auto_increment";
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

