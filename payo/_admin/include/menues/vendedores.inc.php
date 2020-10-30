<?php
// -------------------------------------------------------------
//  Definicion de variables Modificables
// -------------------------------------------------------------
$config['database']=$default->database;
$config['lineas']=$default->list_row;
$config['menu']="Vendedores";
$config['tabla']="e_vendedores";
$config['join']="";
$config['campos'][0] = array(
    'vendedor'=>Vendedor,
    'email'=>Email,
	'username'=>"Usuario",
	'password'=>"",
    'id_vendedor'=>"",
    'id_v'=>"",
	'adm'=>"",
	'binario'=>array(
		nombre=>"Activo",
		campo=>"activo"),
    );
$config['include_form']="templates/listar.inc.php";
$config['id']="id_v";
$config['default_order']="vendedor";
$config['default_ord'] = "";
$config['set_idioma']="no";
$config['eorden']="";
$config['edit']="yes";
$config['agregar']="yes";
$config['delete']="yes";
$config['pass']="yes";
$config['form_edit']="forms/vendedores.inc.php";
$config['listar_condicion']="1";
$config['em_condicion']="id_v=$id";
$config['eliminar_control']=array('e_webusers'=>"$id=vendedor_id");
$config['modificar_control']=array('e_vendedores'=>"vendedor='$vendedor[1]' AND id_v!='$id'");
$config['alta_control']=array('e_vendedores'=>"(e_vendedores.vendedor='$vendedor[1]')" );

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
		if ($activo[1]==""){$activo[1]=0;}
		if ($adm[1]==""){$adm[1]=0;}
		foreach ($default->lenguajes() as $id_l){
			$config['campos'][$id_l]=array(
		 		"vendedor"=>$vendedor[1],
		 		"email"=>$email[1],
				"username"=>$username[1],
				"activo"=>$activo[1],
				"id_vendedor"=>$id_vendedor[1],
				"adm"=>$adm[1],
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
		if ($activo[1]==""){$activo[1]=0;}
		if ($adm[1]==""){$adm[1]=0;}
		foreach ($default->lenguajes() as $id_l){
			$config['campos'][$id_l]=array(
		 		"vendedor"=>$vendedor[1],
		 		"email"=>$email[1],
				"username"=>$username[1],
				"activo"=>$activo[1],
				"id_vendedor"=>$id_vendedor[1],
				"adm"=>$adm[1],
			);
			$config['campos'][$id_l]["id_v"]="auto_increment";
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
	case 'p':{
		$glo_form_tit = $config['menu'] . " - Cambio de Clave";
		$config['op']="cp";	
		include("include/templates/form_header.inc.php");
		$config['include_form']="forms/cambio-password.inc.php";
		$config['condicion']=$config['em_condicion'];
		listar_modificaciones($config);
		include("include/templates/form_footer.inc.php");
		break;
	}
	case 'cp':{
		$db_pa = connect();
		$db_pa->debug = SDEBUG;
		$config['condicion']=$config['em_condicion'];
		$pa_q = "UPDATE $config[tabla] SET password='".md5("$password")."' WHERE ".$config['condicion'];
		$pa_result = $db_pa->Execute($pa_q);
		if ($pa_result){
			dolog($pa_q);
		} else {
			dolog("ERROR: ". $pa_q);
		}
		$config['condicion']=$config['listar_condicion'];
		listar($config);
		break;
	}	
}//Fin del switch
?>
